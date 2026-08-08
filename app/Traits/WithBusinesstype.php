<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;
trait WithBusinesstype {

    protected static function bootWithBusinesstype()
    {
        if (auth()->guard('web')->check()) {

            $business_type_id = auth()->user()->business->business_type_id;

            static::creating(function ($model) use($business_type_id){
                $model->business_type_id = $business_type_id;
            });

            static::addGlobalScope('business_type_id', function ($model) use($business_type_id){
                //dd($model->getModel()->getTable());
                $model->where($model->getModel()->getTable().'.business_type_id', $business_type_id);
            });
        }
    }
}
