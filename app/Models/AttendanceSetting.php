<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class AttendanceSetting extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
}
