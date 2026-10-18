<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\AttendanceImport;
use App\Models\AttendanceImportRow;
use App\Models\Employee;
use App\Models\Shift;
use App\Services\AttendanceCalculationService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class FinalizeAttendanceImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $importId
    ) {}

    public function handle(AttendanceCalculationService $calculator): void
    {
        $batch = AttendanceImport::findOrFail($this->importId);

        if (in_array($batch->status, ['completed', 'failed'], true)) {
            return;
        }

        $batch->update(['status' => 'processing']);

        $errorsFound = false;
        $seen = [];

        /*
         * Phase 1: validate every row.
         * No live attendance writes happen here.
         */
        AttendanceImportRow::where('attendance_import_id', $batch->id)
            ->orderBy('id')
            ->chunkById(500, function ($rows) use (
                $batch,
                &$errorsFound,
                &$seen
            ) {
                foreach ($rows as $row) {
                    $raw = $row->raw_data;
                    $errors = [];

                    try {
                        $empId = $this->requiredId(
                            $raw['empID'] ?? null,
                            'empID'
                        );

                        $shiftId = $this->requiredId(
                            $raw['shiftID'] ?? null,
                            'shiftID'
                        );

                        $date = $this->parseDate(
                            $raw['dutyDate'] ?? null
                        );

                        $inTime = $this->parseTime(
                            $raw['inTime'] ?? null
                        );

                        $outTime = $this->parseOptionalTime(
                            $raw['outTime'] ?? null
                        );

                        if (!Employee::whereKey($empId)->exists()) {
                            $errors[] = "Employee ID {$empId} does not exist.";
                        }

                        if (!Shift::whereKey($shiftId)->exists()) {
                            $errors[] = "Shift ID {$shiftId} does not exist.";
                        }

                        $key = $empId . '|' . $date;

                        if (isset($seen[$key])) {
                            $errors[] = "Duplicate employee and duty date in this file; first occurrence is row {$seen[$key]}.";
                        } else {
                            $seen[$key] = $row->row_number;
                        }

                        if (!$errors) {
                            $shift = Shift::find($shiftId);

                            if ($shift) {
                                // Validate time ordering and overnight duration.
                                $this->validateDuration(
                                    $date,
                                    $inTime,
                                    $outTime,
                                    $shift
                                );
                            }
                        }
                    } catch (\Throwable $e) {
                        $errors[] = $e->getMessage();
                    }

                    $row->update([
                        'errors' => $errors ?: null,
                    ]);

                    if ($errors) {
                        $errorsFound = true;
                    }
                }
            });

        $batch->update([
            'total_rows' => $batch->rows()->count(),
            'error_rows' => $batch->rows()
                ->whereNotNull('errors')
                ->count(),
        ]);

        if ($errorsFound) {
            $batch->update([
                'status' => 'failed',
                'error_message' => 'Validation failed. No attendance records were imported.',
            ]);

            return;
        }

        /*
         * Phase 2: apply the validated rows atomically.
         * A database exception rolls back all attendance writes.
         */
        DB::transaction(function () use ($batch, $calculator) {
            $batch->rows()
                ->orderBy('id')
                ->chunkById(250, function ($rows) use ($calculator) {
                    foreach ($rows as $row) {
                        $raw = $row->raw_data;

                        $empId = $this->requiredId($raw['empID'] ?? null, 'empID');
                        $shiftId = $this->requiredId($raw['shiftID'] ?? null, 'shiftID');
                        $date = $this->parseDate($raw['dutyDate'] ?? null);
                        $inTime = $this->parseTime($raw['inTime'] ?? null);
                        $outTime = $this->parseOptionalTime($raw['outTime'] ?? null);

                        $shift = Shift::findOrFail($shiftId);

                        $calculated = $calculator->calculate(
                            $date,
                            $inTime,
                            $outTime,
                            $shift
                        );

                        /*
                         * Match your manual controller's behavior:
                         * update an existing employee/date record or create it.
                         */
                        $attendance = Attendance::where('dutyDate', $date)
                            ->where('empID', $empId)
                            ->lockForUpdate()
                            ->first();

                        if (!$attendance) {
                            $attendance = new Attendance();
                        }

                        $attendance->empID = $empId;
                        $attendance->shiftID = $shiftId;
                        $attendance->dutyDate = $date;

                        $attendance->inTime = $calculated['inTime'];
                        $attendance->workingMiniute = $calculated['workingMiniute'];
                        $attendance->lateMiniute = $calculated['lateMiniute'];
                        $attendance->in_status = $calculated['in_status'];
                        $attendance->overtimeMiniute = $calculated['overtimeMiniute'];
                        $attendance->status = $calculated['status'];

                        if ($calculated['outTime'] !== null) {
                            $attendance->outTime = $calculated['outTime'];
                            $attendance->out_status = $calculated['out_status'];
                            $attendance->totalShitWorkingMinute =
                                $calculated['totalShitWorkingMinute'];
                        } else {
                            $attendance->outTime = null;
                            $attendance->out_status = null;
                            $attendance->totalShitWorkingMinute =
                                $calculated['totalShitWorkingMinute'];
                        }

                        $attendance->save();
                    }
                });
        }, 3);

        $batch->update([
            'status' => 'completed',
            'error_message' => null,
        ]);
    }

    private function requiredId(mixed $value, string $field): int
    {
        if (
            $value === null ||
            trim((string) $value) === '' ||
            !is_numeric($value) ||
            (int) $value < 1 ||
            (float) $value !== (float) (int) $value
        ) {
            throw new \RuntimeException("{$field} must be a valid positive integer.");
        }

        return (int) $value;
    }

    private function parseDate(mixed $value): string
    {
        if ($value === null || trim((string) $value) === '') {
            throw new \RuntimeException('dutyDate is required.');
        }

        if (is_numeric($value) && (float) $value > 0) {
            return ExcelDate::excelToDateTimeObject((float) $value)
                ->format('Y-m-d');
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable $e) {
            throw new \RuntimeException('dutyDate is not a valid date.');
        }
    }

    private function parseTime(mixed $value): string
    {
        if ($value === null || trim((string) $value) === '') {
            throw new \RuntimeException('inTime is required.');
        }

        return $this->normalizeTime($value);
    }

    private function parseOptionalTime(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return $this->normalizeTime($value);
    }

    private function normalizeTime(mixed $value): string
    {
        if (is_numeric($value) && (float) $value >= 0 && (float) $value < 1) {
            $seconds = (int) round((float) $value * 86400);
            $seconds %= 86400;

            return gmdate('H:i:s', $seconds);
        }

        try {
            return Carbon::parse((string) $value)->format('H:i:s');
        } catch (\Throwable $e) {
            throw new \RuntimeException('Invalid time format. Use HH:MM or HH:MM:SS.');
        }
    }

    private function validateDuration(
        string $date,
        string $inTime,
        ?string $outTime,
        Shift $shift
    ): void {
        if ($outTime === null) {
            return;
        }

        $timezone = config('app.timezone', 'Asia/Dhaka');

        $in = Carbon::parse("$date $inTime", $timezone);
        $out = Carbon::parse("$date $outTime", $timezone);
        $shiftStart = Carbon::parse("$date {$shift->startTime}", $timezone);
        $shiftEnd = Carbon::parse("$date {$shift->endTime}", $timezone);

        if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
            $shiftEnd->addDay();
        }

        if ($out->lessThanOrEqualTo($in)) {
            $out->addDay();
        }

        if ($out->lessThanOrEqualTo($in)) {
            throw new \RuntimeException('Checkout must be after check-in.');
        }

        if ($in->diffInMinutes($out) > 24 * 60) {
            throw new \RuntimeException('Working duration cannot exceed 24 hours.');
        }
    }
}