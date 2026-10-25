<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    protected $fillable = ['salary_record_id', 'salary_head_id', 'head_name', 'type', 'calc', 'value', 'amount'];

    public function salaryRecord()
    {
        return $this->belongsTo(SalaryRecord::class);
    }
}
