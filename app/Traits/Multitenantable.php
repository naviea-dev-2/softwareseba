<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;

trait Multitenantable {

    protected static function bootMultitenantable()
    {
        if (auth()->guard('web')->check()) {


                static::creating(function ($model) {
                    $model->created_by = auth()->user()->id;

                });

                //For Update
                static::saving(function ($model) {
                    $model->updated_by = auth()->user()->id;
                });



        }
    }
}
