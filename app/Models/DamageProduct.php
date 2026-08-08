<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Inventory\Branch;
class DamageProduct extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    function items(){
        return $this->hasMany(DamageProductDetail::class,'damage_id','id')->orderBy('id','desc');
    }
}
