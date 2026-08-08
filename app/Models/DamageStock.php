<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Inventory\Product;
use App\Models\Inventory\Unit;
class DamageStock extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function product(){
        return $this->belongsTo(Product::class,"product_id","id");
    }
    function unit(){
        return $this->belongsTo(Unit::class,"unit_id",'id')->orderBy('id','asc');
    }
}
