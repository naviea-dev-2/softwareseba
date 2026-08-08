<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Employee extends Model
{
    use HasFactory, Multitenantable, WithBusiness;

    protected $fillable=
    [
        'employee_id',
        'employee_name',
        'employee_image',
        'department_id',
        'designation_id',
        'email',
        'phone_number',
        'date_of_birth',
        'gander',
        'country',
        'city',
        'post_code',
        'nid_number',
        'qualification',
        'address',
        'salary',
        'join_date',
        'shift_name',
        'password',
        'confirm_password'
    ];
    public function getEmployeeImageShowAttribute(){
        return $this->employee_image != "" ? asset('public/upload/Employee_Image/'. $this?->employee_image) : asset('public/images/No-image.jpg');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
