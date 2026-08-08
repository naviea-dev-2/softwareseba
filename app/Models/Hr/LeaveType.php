<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class LeaveType extends Model
{
    use Multitenantable, WithBusiness;
    protected $fillable = [
        'leaveCode','description','day','hour'
    ];
}
