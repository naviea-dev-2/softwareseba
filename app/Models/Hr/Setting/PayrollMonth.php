<?php
namespace App\Models\Hr\Setting;

use Illuminate\Database\Eloquent\Model;

class PayrollMonth extends Model
{
    protected $table = 'payroll_months';

    protected $fillable = ['month', 'working_days', 'status'];

    /** Salary records that belong to this payroll month. */
    public function salaryRecords()
    {
        return $this->hasMany(SalaryRecord::class, 'payroll_month', 'month');
    }

    public function label(): string
    {
        return \Carbon\Carbon::createFromFormat('Y-m', $this->month)->format('F Y');
    }
}
