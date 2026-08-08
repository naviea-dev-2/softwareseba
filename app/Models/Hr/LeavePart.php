<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class LeavePart extends Model
{
    use Multitenantable, WithBusiness;
    protected $fillable = [
        'levaePartName','day'
    ];
}
