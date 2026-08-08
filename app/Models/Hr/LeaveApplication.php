<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class LeaveApplication extends Model
{
    use Multitenantable, WithBusiness;
    protected $fillable = [
        'empDeptID','empDesigID','empID','leaveTypeID','leavePartID','fromDate','toDate','purpose','address','dcEmpDeptID','dcEmpDesigID','dcEmpID','leaveDay','status'
    ];

    function employee(){
        return $this->belongsTo(Employee::class,'empID');
    }
}
