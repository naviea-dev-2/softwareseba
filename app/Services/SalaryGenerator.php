<?php

namespace App\Services;

use App\Models\Hr\Employee;
use App\Models\Hr\PayAssignment;
use App\Models\Hr\SalaryDetail;
use App\Models\Hr\SalaryRecord;
use Illuminate\Support\Facades\DB;

class SalaryGenerator
{
    /** Creates a salary record and its salary details (one per pay assignment) in one transaction. */
    public function createRecord(array $data): SalaryRecord
    {
        return DB::transaction(function () use ($data) {
            $record = SalaryRecord::create($data);
            $this->snapshotDetails($record);

            return $record;
        });
    }

    /** Creates a monthly record for an employee with the given basic salary. */
    public function create(Employee $employee, string $month, float $basic): SalaryRecord
    {
        return $this->createRecord([
            'employee_id'   => $employee->id,
            'payroll_month' => $month,
            'basic'         => $basic,
            'advance'       => 0,
            'due'           => 0,
        ]);
    }

    /** Replaces the record's details with the employee's current pay assignments. */
    public function refresh(SalaryRecord $record): void
    {
        DB::transaction(fn () => $this->snapshotDetails($record));
    }

    private function snapshotDetails(SalaryRecord $record): void
    {
        $record->details()->delete();

        PayAssignment::with('salaryHead')
            ->where('employee_id', $record->employee_id)
            ->get()
            ->each(function (PayAssignment $assignment) use ($record) {
                $head = $assignment->salaryHead;
                if (! $head || $head->status !== 'Active') {
                    return;
                }

                $basic  = (float) $record->basic;
                $amount = $head->calc === '% of Basic'
                    ? $basic * (float) $assignment->value / 100
                    : (float) $assignment->value;

                SalaryDetail::create([
                    'salary_record_id' => $record->id,
                    'salary_head_id'   => $head->id,
                    'head_name'        => $head->name,
                    'type'             => $head->type,
                    'calc'             => $head->calc,
                    'value'            => $assignment->value,
                    'amount'           => round($amount, 2),
                ]);
            });
    }
}
