<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class SalaryRecord extends Model
{
    protected $fillable = ['employee_id', 'payroll_month', 'basic', 'advance', 'due'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /** One row per pay assignment, saved when the salary was generated. */
    public function details()
    {
        return $this->hasMany(SalaryDetail::class);
    }
}
