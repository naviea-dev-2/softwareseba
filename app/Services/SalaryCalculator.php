<?php

namespace App\Services;

use App\Models\Hr\Attendance;
use App\Models\Hr\Holiday;
use App\Models\Hr\Setting\LateRule;
use App\Models\Hr\LeaveApplication;
use App\Models\Hr\Payment;
use App\Models\Hr\PayAssignment;
use App\Models\Hr\SalaryRecord;
use App\Models\Hr\Setting\TaxSetting;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SalaryCalculator
{
    /** Carbon dayOfWeek values for weekly off: 5 = Friday, 6 = Saturday. */
    private const WEEKLY_OFF = [5, 6];

    public function __construct(private AttendanceCalculator $attendance) {}

    public function calculate(SalaryRecord $record): array
    {
        $record->loadMissing('employee');
        $employee = $record->employee;
        $basic    = (float) $record->basic;
        $start    = Carbon::createFromFormat('Y-m', $record->payroll_month)->startOfMonth();
        $end      = $start->copy()->endOfMonth();

        $days = Attendance::with('shift')
            ->where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->map(function ($row) {
                $result = $this->attendance->evaluate($row);

                return [
                    'date'   => $row->attendance_date->toDateString(),
                    'status' => $result['status'],
                    'ot'     => $result['ot_hours'],
                ];
            });

        [$earnLines, $dedLines] = $this->recordLines($record, $basic);
        $earnings = array_sum(array_column($earnLines, 1));

        // Overtime, capped by the monthly maximum
        $setting  = $this->attendance->overtimeFor($employee->designation_id);
        $otHours  = $setting ? min((float) $days->sum('ot'), (float) $setting->max_month) : 0.0;
        $overtime = $setting ? $otHours * ($basic / (26 * 8)) * (float) $setting->rate : 0.0;

        $gross = $basic + $earnings + $overtime;

        // Late roll penalty once the monthly limit is passed
        $rule        = LateRule::where('status', 'Active')->first();
        $lateCount   = $days->where('status', 'Late')->count();
        $latePenalty = 0.0;
        if ($rule && $lateCount > $rule->late_limit) {
            $latePenalty = match ($rule->deduction) {
                'Half Day'   => $basic / 52,
                'Full Day'   => $basic / 26,
                'Salary cut' => $basic * 0.05,
                default      => 0.0,
            };
        }

        // Approved leave: unpaid leave is deducted; leave dates are never "missing" attendance
        $leaves = LeaveApplication::with(['leaveType', 'part'])
            ->where('employee_id', $employee->id)
            ->where('status', 'Approved')
            ->whereDate('from_date', '<=', $end->toDateString())
            ->whereDate('to_date', '>=', $start->toDateString())
            ->get();

        $leaveDates  = $this->leaveDates($leaves, $start, $end);
        $unpaidLeave = (float) $leaves
            ->filter(fn ($l) => $l->leaveType?->paid === 'Unpaid' && $l->from_date->format('Y-m') === $record->payroll_month)
            ->sum(fn ($l) => $l->days());

        // Absent and half-day rows (holidays excluded), plus working days with no entry
        $absentRows = $days->filter(fn ($d) => $d['status'] === 'Absent' && ! $this->isHoliday($d['date'], $employee->department_id))->count();
        $halfRows   = $days->filter(fn ($d) => $d['status'] === 'Half Day' && ! $this->isHoliday($d['date'], $employee->department_id))->count();
        $missing    = $this->missingWorkingDays(
            $employee->department_id,
            $start,
            $end,
            array_merge($days->pluck('date')->all(), $leaveDates)
        );

        $absentDays = $absentRows + $missing;
        $absentDed  = ($absentDays + $unpaidLeave) * $basic / 26 + $halfRows * $basic / 52;

        $deductionLines = $dedLines;
        if ($latePenalty > 0) {
            $deductionLines[] = ['Late penalty', round($latePenalty, 2)];
        }
        if ($absentDed > 0) {
            $deductionLines[] = ["Absent ({$absentDays}) and unpaid leave ({$unpaidLeave})", round($absentDed, 2)];
        }

        $deductions = array_sum(array_column($deductionLines, 1));
        $tax        = $this->tax($gross);
        $net        = $gross - $deductions - $tax['amount'];
        $payable    = $net - (float) $record->advance + (float) $record->due;

        $paid = (float) Payment::where('relation_id', $employee->id)
            ->where('relation_type', 'Salary Payment')
            ->where('payroll_month', $record->payroll_month)
            ->sum('amount');

        return [
            'basic'           => round($basic, 2),
            'earn_lines'      => $earnLines,
            'overtime'        => round($overtime, 2),
            'ot_hours'        => round($otHours, 2),
            'deduction_lines' => $deductionLines,
            'late_count'      => $lateCount,
            'absent_days'     => $absentDays,
            'half_days'       => $halfRows,
            'unpaid_leave'    => round($unpaidLeave, 2),
            'gross'           => round($gross, 2),
            'deductions'      => round($deductions, 2),
            'tax'             => round($tax['amount'], 2),
            'tax_rule'        => $tax['rule'],
            'net'             => round($net, 2),
            'advance'         => round((float) $record->advance, 2),
            'due'             => round((float) $record->due, 2),
            'payable'         => round($payable, 2),
            'paid'            => round($paid, 2),
            'balance'         => round($payable - $paid, 2),
        ];
    }

    /** Saved salary details when the record has them, otherwise the current pay assignments. */
    private function recordLines(SalaryRecord $record, float $basic): array
    {
        if (! $record->details()->exists()) {
            return $this->headLines($record->employee_id, $basic);
        }

        $earn = [];
        $ded  = [];
        foreach ($record->details()->get() as $detail) {
            $line = [$detail->head_name, round((float) $detail->amount, 2)];
            if ($detail->type === 'Earning') {
                $earn[] = $line;
            } else {
                $ded[] = $line;
            }
        }

        return [$earn, $ded];
    }

    /** @return array{0: array, 1: array} earning lines and deduction lines, as [label, amount] */
    private function headLines(int $employeeId, float $basic): array
    {
        $earn = [];
        $ded  = [];

        PayAssignment::with('salaryHead')
            ->where('employee_id', $employeeId)
            ->get()
            ->each(function ($assignment) use ($basic, &$earn, &$ded) {
                $head = $assignment->salaryHead;
                if (! $head || $head->status !== 'Active') {
                    return;
                }

                $amount = $head->calc === '% of Basic'
                    ? $basic * (float) $assignment->value / 100
                    : (float) $assignment->value;

                if ($head->type === 'Earning') {
                    $earn[] = [$head->name, round($amount, 2)];
                } else {
                    $ded[] = [$head->name, round($amount, 2)];
                }
            });

        return [$earn, $ded];
    }

    /** Every calendar date covered by the given leaves, limited to the month. */
    private function leaveDates(Collection $leaves, Carbon $start, Carbon $end): array
    {
        $dates = [];

        foreach ($leaves as $leave) {
            $from = $leave->from_date->lt($start) ? $start->copy() : $leave->from_date->copy();
            $to   = $leave->to_date->gt($end) ? $end->copy() : $leave->to_date->copy();

            for ($d = $from; $d->lte($to); $d->addDay()) {
                $dates[] = $d->toDateString();
            }
        }

        return $dates;
    }

    /** @return array{amount: float, rule: ?string} */
    private function tax(float $gross): array
    {
        $setting = TaxSetting::where('status', 'Active')
            ->whereDate('effective_from', '<=', Carbon::today())
            ->orderByDesc('effective_from')
            ->first();

        if (! $setting) {
            return ['amount' => 0.0, 'rule' => null];
        }

        $threshold = (float) $setting->threshold;
        if ($gross <= $threshold) {
            return ['amount' => 0.0, 'rule' => $setting->name];
        }

        $taxable = $gross - $threshold;
        $amount  = match ($setting->tax_type) {
            'Percentage'   => $taxable * (float) $setting->rate / 100,
            'Fixed Amount' => (float) $setting->rate,
            default        => $this->slabTax($setting, $taxable),
        };

        return ['amount' => $amount, 'rule' => $setting->name];
    }

    private function slabTax(TaxSetting $setting, float $taxable): float
    {
        $tax = 0.0;

        foreach ($setting->slabs()->orderBy('from_amount')->get() as $slab) {
            $upper = ($slab->to_amount !== null && (float) $slab->to_amount > 0) ? (float) $slab->to_amount : INF;
            $part  = min($taxable, $upper) - (float) $slab->from_amount;
            if ($part > 0) {
                $tax += $part * (float) $slab->rate / 100;
            }
        }

        return $tax;
    }

    private function isHoliday(string $date, ?int $departmentId): bool
    {
        return Holiday::where('status', 'Active')
            ->whereDate('holiday_date', $date)
            ->where(fn ($q) => $q->whereNull('department_id')->orWhere('department_id', $departmentId))
            ->exists();
    }

    /** Working days with no attendance entry and no approved leave, excluding holidays. */
    private function missingWorkingDays(?int $departmentId, Carbon $start, Carbon $end, array $recorded): int
    {
        $today = Carbon::today();
        $count = 0;

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($d->gt($today)) {
                break;
            }
            if (in_array($d->dayOfWeek, self::WEEKLY_OFF, true)) {
                continue;
            }
            $date = $d->toDateString();
            if (in_array($date, $recorded, true) || $this->isHoliday($date, $departmentId)) {
                continue;
            }
            $count++;
        }

        return $count;
    }
}
