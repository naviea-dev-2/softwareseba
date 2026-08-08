<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class EmpBankAccount extends Model
{
    use Multitenantable, WithBusiness;
    protected $fillable = [
        'empID','bankID','acName','branchName','acType','acNumber','routingNumber'
    ];
    public function employee(){
        return $this->belongsTo(Employee::class,'empID','id');
    }
    function bank(){
        return $this->belongsTo(Bank::class,'bankID','id');
    }
}
