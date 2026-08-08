<?php

namespace App\Models;

use App\Models\Inventory\City;
use App\Models\Inventory\Country;
use App\Models\Inventory\Currency;
use App\Models\Inventory\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    function getBusinessLogoShowAttribute(){
        return $this->business_logo == '' ? $this->no_image : asset("public/upload/business/".$this->business_logo);
    }
    function getNoImageAttribute(){
        return asset("public/images/No-image.jpg");
    }
    function currency(){
        return $this->belongsTo(Currency::class,'currency_id');
    }
    function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
    function state(){
        return $this->belongsTo(State::class,'state_id');
    }
    function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    function package(){
        return $this->belongsTo(Package::class,'package_id');
    }
}
