<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class LeaveTagline extends Model
{
    use Multitenantable, WithBusiness;
    protected $table = "leave_taglines";
   protected $fillable = [
        'leaveTypeID','leavePartID'
    ];
    function leaveType(){
        return $this->belongsTo(LeaveType::class,'leaveTypeID','id');
    }
    function leavePart(){
        return $this->belongsTo(LeavePart::class,'leavePartID','id');
    }
}
