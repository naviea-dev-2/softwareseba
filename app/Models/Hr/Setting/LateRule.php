<?php

namespace App\Models\Hr\Setting;

use Illuminate\Database\Eloquent\Model;

class LateRule extends Model
{
    protected $fillable = ['name', 'grace_min', 'late_limit', 'deduction', 'status'];
}
