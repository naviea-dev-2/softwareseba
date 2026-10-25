<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['employee_id', 'payroll_month', 'paid_on', 'amount', 'method', 'note'];

    protected $casts = ['paid_on' => 'date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
