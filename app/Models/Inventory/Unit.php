<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Business;
class Unit extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function category(){
        return $this->belongsTo(Category::class);
    }
    function business(){
        return $this->belongsTo(Business::class,'business_id');
    }
}
