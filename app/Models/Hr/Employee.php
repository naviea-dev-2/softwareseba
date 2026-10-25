<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Hr\Setting\Department;
use App\Models\Hr\Setting\Designation;
use App\Models\Hr\Setting\Shift;
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
    public function payAssignments()
    {
        return $this->hasMany(PayAssignment::class);
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function salaryRecords()
    {
        return $this->hasMany(SalaryRecord::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
