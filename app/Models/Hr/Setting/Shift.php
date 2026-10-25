<?php

namespace App\Models\Hr\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;

class Shift extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    protected $table="shift";
    protected $fillable = ['shiftName', 'startTime', 'endTime', 'break_min', 'status'];
}
