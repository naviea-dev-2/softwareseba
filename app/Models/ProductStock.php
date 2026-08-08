<?php

namespace App\Models;

use App\Models\Inventory\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;
    function unit(){
        return $this->belongsTo(Unit::class,'unit_id');
    }
}
