<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Attendance extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function shift(){
        return $this->belongsTo(Shift::class,"shiftID");
    }
    function employee(){
        return $this->belongsTo(Employee::class,"empID");
    }
}
