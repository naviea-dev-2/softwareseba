<?php

namespace App\Models\Account;

use App\Models\Hr\BonusPay;
use App\Models\Hr\Employee;
use App\Models\Hr\SalarySheet;
use App\Models\Inventory\Invoice;
use App\Models\Inventory\InvoiceReturn;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\PurchaseReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;

class AccountTransaction extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function expense(){
        return $this->belongsTo(Expense::class,"relation_id",'id');
    }
    function invoice(){
        return $this->belongsTo(Invoice::class,"relation_id",'id');
    }
    function invoice_return(){
        return $this->belongsTo(InvoiceReturn::class,"relation_id",'id');
    }
    function purchase(){
        return $this->belongsTo(Purchase::class,"relation_id",'id');
    }
    function purchase_return(){
        return $this->belongsTo(PurchaseReturn::class,"relation_id",'id');
    }
    function salary(){
        return $this->belongsTo(SalarySheet::class,"relation_id",'id');
    }
    function bonus(){
        return $this->belongsTo(BonusPay::class,"relation_id",'id');
    }
    function emp_loan(){
        return $this->belongsTo(Employee::class,"relation_id",'id');
    }
    function account(){
        return $this->belongsTo(AccountHead::class,'account_id','id');
    }
    function o_tranaction(){
        return $this->belongsTo(AccountTransaction::class,'trans_id');
    }
}
