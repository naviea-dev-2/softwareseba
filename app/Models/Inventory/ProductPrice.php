<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class ProductPrice extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function unit(){
        return $this->belongsTo(Unit::class);
    }
}
