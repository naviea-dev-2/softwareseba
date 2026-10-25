<?php

namespace App\Models\Hr\Setting;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['holiday_date', 'name', 'type', 'department_id', 'status'];

    protected $casts = ['holiday_date' => 'date'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
