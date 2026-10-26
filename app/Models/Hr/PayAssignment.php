<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Traits\WithBusiness;
class PayAssignment extends Model
{
    use WithBusiness;
    protected $fillable = ['employee_id', 'salary_head_id', 'value'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryHead()
    {
        return $this->belongsTo(SalaryHead::class);
    }
}
