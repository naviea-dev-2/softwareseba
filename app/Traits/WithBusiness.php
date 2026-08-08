<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;
trait WithBusiness {

    protected static function bootWithBusiness()
    {
        if (auth()->guard('web')->check()) {

            $business_id = auth()->user()->business->id ?? 0;

            static::creating(function ($model) use($business_id){
                $model->business_id = $business_id;
            });

            static::addGlobalScope('business_id', function ($model) use($business_id){
                //dd($model->getModel()->getTable());
                $model->where($model->getModel()->getTable().'.business_id', $business_id);
            });
        }
    }
}
