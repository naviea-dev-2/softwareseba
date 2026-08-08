<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Country extends Model
{
    use HasFactory;
    public static function options()
    {
    	$options[''] = 'Select Country';

        $countries = Self::select(['id', 'name'])->get();

    	foreach($countries as $country)
    		$options[$country->id] = $country->name;

    	return $options;
    }
}
