<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Holiday extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function month(){
        return $this->belongsTo(MonthManage::class,'monthID');
    }
}
