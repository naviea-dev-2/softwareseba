<?php

namespace App\Models\Inventory;

use App\Models\Business;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\WithBusinesstype;
class Category extends Model
{
     use HasFactory, Multitenantable, WithBusinesstype;

    function business(){
        return $this->belongsTo(Business::class,'business_id');
    }

}
