<?php

namespace App\Services;

use App\Models\AttendanceSetting;
use App\Models\Shift;
use Carbon\Carbon;

class AttendanceCalculationService
{
    public function calculate(
        string $dutyDate,
        string $inTime,
        ?string $outTime,
        Shift $shift
    ): array {
        $timezone = config('app.timezone', 'Asia/Dhaka');

        $in = Carbon::parse(
            "$dutyDate $inTime",
            $timezone
        );

        $shiftStart = Carbon::parse(
            "$dutyDate {$shift->startTime}",
            $timezone
        );

        $shiftEnd = Carbon::parse(
            "$dutyDate {$shift->endTime}",
            $timezone
        );

        // Shift finishes the next day.
        if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
            $shiftEnd->addDay();
        }

        // Preserve the current delay_time rule.
        $setting = AttendanceSetting::first();

        $lateThreshold = $setting && $setting->delay_time
            ? Carbon::parse(
                "$dutyDate {$setting->delay_time}",
                $timezone
            )
            : $shiftStart->copy();

        $lateMinutes = $in->greaterThan($lateThreshold)
            ? $lateThreshold->diffInMinutes($in)
            : 0;

        $shiftMinutes = $shiftStart->diffInMinutes($shiftEnd);

        $result = [
            'inTime' => $in,
            'outTime' => null,
            'workingMiniute' => 0,
            'lateMiniute' => $lateMinutes,
            'overtimeMiniute' => 0,
            'in_status' => $lateMinutes > 0 ? 2 : 1,
            'out_status' => null,
            'totalShitWorkingMinute' => $shiftMinutes,
            'status' => $lateMinutes > 0 ? 'Late' : 'Ok',
        ];

        if ($outTime === null || trim($outTime) === '') {
            return $result;
        }

        $out = Carbon::parse(
            "$dutyDate $outTime",
            $timezone
        );

        // If checkout is at/before check-in, use next-day checkout.
        if ($out->lessThanOrEqualTo($in)) {
            $out->addDay();
        }

        $workingMinutes = $in->diffInMinutes($out);

        $overtimeMinutes = $out->greaterThan($shiftEnd)
            ? $shiftEnd->diffInMinutes($out)
            : 0;

        if ($lateMinutes > 0 && $overtimeMinutes > 0) {
            $overtimeMinutes = max(
                0,
                $overtimeMinutes - $lateMinutes
            );
        }

        $outStatus = $out->lessThan($shiftEnd) ? 2 : 1;

        $status = $result['status'];

        if ($shiftMinutes > $workingMinutes) {
            $status = $lateMinutes > 0 ? 'Late' : 'Partial';
        }

        return array_merge($result, [
            'outTime' => $out,
            'workingMiniute' => $workingMinutes,
            'overtimeMiniute' => $overtimeMinutes,
            'out_status' => $outStatus,
            'status' => $status,
        ]);
    }
}