<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Business;
class Road extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function territory(){
        return $this->belongsTo(Territory::class,'territory_id');
    }
    function business(){
        return $this->belongsTo(Business::class,'business_id');
    }
}
