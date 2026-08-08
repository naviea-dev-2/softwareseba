<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;

class ProductPurchase extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function product(){
        return $this->belongsTo(Product::class,"product_id","id");
    }
    function color(){
        return $this->belongsTo(Color::class,"color_id",'id')->orderBy('id','asc');
    }
    function size(){
        return $this->belongsTo(Size::class,"size_id",'id')->orderBy('id','asc');
    }
    function unit(){
        return $this->belongsTo(Unit::class,"unit_id",'id')->orderBy('id','asc');
    }
}
