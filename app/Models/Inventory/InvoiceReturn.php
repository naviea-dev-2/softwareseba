<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Hr\Employee;
use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
class InvoiceReturn extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function method(){
        return $this->belongsTo(PaymentMethod::class,'payment_method','id');
    }
    function account(){
        return $this->belongsTo(BalanceAccount::class,'bank_account_id','id');
    }
     function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id');
    }
    function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    function items(){
        return $this->hasMany(ProductInvoiceReturn::class,'invoice_return_id','id')->orderBy('id','desc');
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
}
