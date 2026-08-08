<?php

namespace App\Models;

use App\Models\Inventory\Product;
use App\Models\Inventory\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosSaleDetails extends Model
{
    use HasFactory;
    function product(){
        return $this->belongsTo(Product::class,"product_id","id");
    }
    
    function unit(){
        return $this->belongsTo(Unit::class,"unit_id",'id')->orderBy('id','asc');
    }
    function pos_sale(){
        return $this->belongsTo(PosSale::class,'invoice_id');
    }
}
