<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Vendor extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function getImageShowAttribute(){
        return $this->image == '' ? $this->no_image : asset("public/upload/vendors/".$this->image);
    }
    function getNoImageAttribute(){
        return asset("public/images/No-image.jpg");
    }
    function country(){
        return $this->belongsTo(Country::class);
    }
    function state(){
        return $this->belongsTo(State::class);
    }
    function city(){
        return $this->belongsTo(City::class);
    }
}
