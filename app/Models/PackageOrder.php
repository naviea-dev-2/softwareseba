<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageOrder extends Model
{
    use HasFactory;
    function package(){
        return $this->belongsTo(Package::class,'package_id');
    }
    function business(){
        return $this->belongsTo(Business::class,'business_id');
    }
}
