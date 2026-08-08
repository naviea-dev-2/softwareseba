<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Hr\Employee;
class DeviceMapping extends Model
{
      use HasFactory, Multitenantable, WithBusiness;
       protected $fillable = [
        'is_done'
      ];
      public function employee()
      {
            return $this->belongsTo(Employee::class, 'emp_id');
      }
}
