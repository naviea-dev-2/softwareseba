<?php

namespace App\Models\Inventory;

use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use App\Models\Hr\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Invoice extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function method(){
        return $this->belongsTo(PaymentMethod::class,'payment_method','id');
    }
    function account(){
        return $this->belongsTo(BalanceAccount::class,'bank_account_id','id');
    }
    function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    function dsr(){
        return $this->belongsTo(Employee::class,'dsr_id','id');
    }
    function asr(){
        return $this->belongsTo(Employee::class,'asr_id','id');
    }
    function driver(){
        return $this->belongsTo(Employee::class,'sld_id','id');
    }
    function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    function items(){
        return $this->hasMany(ProductInvoice::class,'invoice_id','id')->orderBy('id','desc');
    }
}
