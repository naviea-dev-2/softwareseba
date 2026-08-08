<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Inventory\Country;
use App\Models\Inventory\State;
use App\Models\Inventory\City;
class Member extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function getImageShowAttribute(){
        return $this->image == '' ? $this->no_image : asset("public/upload/member/".$this->image);
    }
    function getNoImageAttribute(){
        return asset("public/images/No-image.jpg");
    }
    public function member_type()
    {
        return $this->belongsTo(MemberType::class, 'member_type_id');
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
}
