<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class ProductWithAttributSet extends Model
{
     use HasFactory, Multitenantable, WithBusiness;
    function attribute(){
        return $this->belongsTo(AttributeSet::class,"attribute_set_id","id");
    }
}
