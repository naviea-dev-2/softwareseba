<?php

namespace App\Services;

use App\Models\Hr\Attendance;
use App\Models\Hr\Setting\LateRule;
use App\Models\Hr\LeaveApplication;
use App\Models\Hr\Setting\OvertimeSetting;

class AttendanceCalculator
{
    /**
     * Status, late minutes, overtime hours and working minutes for one attendance row.
     * An approved leave covering the date overrides everything.
     */
    public function evaluate(Attendance $a): array
    {
        $a->loadMissing(['employee', 'shift']);

        if ($this->onApprovedLeave($a)) {
            return ['status' => 'Leave', 'late_min' => 0, 'ot_hours' => 0.0, 'work_min' => 0];
        }

        $off   = in_array($a->status, ['Absent', 'Leave'], true);
        $rule  = LateRule::where('status', 'Active')->first();
        $grace = $rule?->grace_min ?? 0;
        $late  = 0;
        $ot    = 0.0;
        $work  = 0;

        if (! $off && $a->shift && $a->in_time) {
            $delay = $this->minutes($a->in_time, $a->shift->start_time);
            if ($delay <= 720 && $delay > $grace) {
                $late = $delay;
            }
        }

        if (! $off && $a->shift && $a->out_time) {
            $setting = $this->overtimeFor($a->employee?->designation_id);
            $after   = $this->minutes($a->out_time, $a->shift->end_time);
            if ($setting && $after <= 720) {
                $ot = min($after / 60, (float) $setting->max_day);
            }
        }

        if (! $off && $a->in_time && $a->out_time) {
            $break = (int) ($a->shift?->break_min ?? 0);
            $work  = max(0, $this->minutes($a->out_time, $a->in_time) - $break);
        }

        $status = $a->status;
        if (! $off && $a->in_time) {
            if ($a->status === 'Remote') {
                $status = 'Remote';
            } elseif ($late > 0) {
                $status = $this->lateStatus($a, $rule);
            } else {
                $status = 'Present';
            }
        } elseif (! $off && ! $a->in_time && in_array($status, ['Present', 'Late'], true)) {
            $status = 'Absent';
        }

        return [
            'status'   => $status,
            'late_min' => $late,
            'ot_hours' => round($ot, 2),
            'work_min' => $work,
        ];
    }

    public function lateMinutes(Attendance $a, int $grace): int
    {
        $a->loadMissing('shift');
        if (! $a->shift || ! $a->in_time || in_array($a->status, ['Remote', 'Absent', 'Leave'], true)) {
            return 0;
        }
        $delay = $this->minutes($a->in_time, $a->shift->start_time);

        return ($delay <= 720 && $delay > $grace) ? $delay : 0;
    }

    public function onApprovedLeave(Attendance $a): bool
    {
        return LeaveApplication::where('employee_id', $a->employee_id)
            ->where('status', 'Approved')
            ->whereDate('from_date', '<=', $a->attendance_date)
            ->whereDate('to_date', '>=', $a->attendance_date)
            ->exists();
    }

    /** The first N late arrivals per month stay "Late"; later ones take the deduction status. */
    private function lateStatus(Attendance $a, ?LateRule $rule): string
    {
        if (! $rule) {
            return 'Late';
        }

        $day   = $a->attendance_date->copy();
        $month = [$day->copy()->startOfMonth()->toDateString(), $day->copy()->endOfMonth()->toDateString()];
        $date  = $day->toDateString();

        $earlier = Attendance::with('shift')
            ->where('employee_id', $a->employee_id)
            ->whereBetween('attendance_date', $month)
            ->where(function ($q) use ($date, $a) {
                $q->where('attendance_date', '<', $date)
                  ->orWhere(fn ($q2) => $q2->where('attendance_date', $date)->where('id', '<', $a->id ?? 0));
            })
            ->get();

        $rank = $earlier->filter(fn ($r) => $this->lateMinutes($r, $rule->grace_min) > 0)->count() + 1;

        if ($rank <= $rule->late_limit) {
            return 'Late';
        }

        return match ($rule->deduction) {
            'Full Day' => 'Absent',
            'Half Day' => 'Half Day',
            default    => 'Late',
        };
    }

    /** Minutes from $t2 to $t1 on a 24-hour clock (handles night shifts). */
    private function minutes(string $t1, string $t2): int
    {
        [$h1, $m1] = array_map('intval', explode(':', substr($t1, 0, 5)));
        [$h2, $m2] = array_map('intval', explode(':', substr($t2, 0, 5)));

        return (($h1 * 60 + $m1) - ($h2 * 60 + $m2) + 1440) % 1440;
    }

    public function overtimeFor(?int $designationId): ?OvertimeSetting
    {
        return OvertimeSetting::where('status', 'Active')
            ->where(fn ($q) => $q->where('designation_id', $designationId)->orWhereNull('designation_id'))
            ->orderByRaw('designation_id IS NULL')
            ->first();
    }
}
