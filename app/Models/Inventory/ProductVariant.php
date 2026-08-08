<?php

namespace App\Models\Inventory;

use App\Models\Inventory\Color;
use App\Models\Inventory\Size;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class ProductVariant extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function color(){
        return $this->belongsTo(Color::class,"relation_id",'id')->orderBy('id','asc');
    }
    function size(){
        return $this->belongsTo(Size::class,"relation_id",'id')->orderBy('id','asc');
    }
}
