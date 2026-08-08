<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusinesstype;
use App\Models\Business;
class ProductType extends Model
{
    use HasFactory, Multitenantable, WithBusinesstype;
    function business(){
        return $this->belongsTo(Business::class,'business_id');
    }
}
