<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Traits\WithBusiness;
class SalaryHead extends Model
{
    use WithBusiness;
    protected $fillable = ['name', 'type', 'calc', 'status'];

    public function assignments()
    {
        return $this->hasMany(PayAssignment::class);
    }
}
