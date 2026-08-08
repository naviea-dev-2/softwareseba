<?php

namespace App\Models\Inventory;

use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Payment extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function method(){
        return $this->belongsTo(PaymentMethod::class,'payment_method');
    }
    function account(){
        return $this->belongsTo(BalanceAccount::class,'bank_account_id');
    }
    function invoice(){
        return $this->belongsTo(Invoice::class,'relation_id');
    }
}
