<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $fillable = ['employee_id', 'payroll_month', 'title', 'calc', 'value', 'status', 'note'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /** Bonus amount for the month. Percentage bonuses need the salary record's basic. */
    public function amount(): ?float
    {
        if ($this->calc !== '% of Basic') {
            return (float) $this->value;
        }

        $basic = SalaryRecord::where('employee_id', $this->employee_id)
            ->where('payroll_month', $this->payroll_month)
            ->value('basic');

        return $basic === null ? null : round((float) $basic * (float) $this->value / 100, 2);
    }
}
