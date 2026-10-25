<?php

namespace App\Models\Hr\Setting;

use Illuminate\Database\Eloquent\Model;

class OvertimeSetting extends Model
{
    protected $fillable = ['name', 'rate', 'max_day', 'max_month', 'designation_id', 'status'];

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
}
