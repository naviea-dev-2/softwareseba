<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class SalarySheet extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function employee(){
        return $this->belongsTo(Employee::class,'empID','id');
    }
}
