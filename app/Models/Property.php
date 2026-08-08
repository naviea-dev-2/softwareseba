<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Inventory\Country;
use App\Models\Inventory\State;
use App\Models\Inventory\City;
class Property extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function getImageShowAttribute(){
        return $this->thumb_image == '' ? asset("public/images/No-image.jpg") : asset("public/upload/property/".$this->thumb_image);
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    function deposits(){
        return $this->hasMany(DepositPayment::class,'land_plot_id','id')->orderBy('id','desc');
    }
}
