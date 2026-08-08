<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class State extends Model
{
    use HasFactory;
    function country(){
        return $this->belongsTo(Country::class);
    }
}
