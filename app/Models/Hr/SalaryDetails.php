<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Traits\WithBusiness;
class SalaryDetails extends Model
{
    use WithBusiness;
    public function salary_head()
    {
        return $this->belongsTo(SalaryHead::class,"head_id","id");
    }
}
