<?php

namespace App\Models\Inventory;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;

use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
class Purchase extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function method(){
        return $this->belongsTo(PaymentMethod::class,'payment_method','id');
    }
    function account(){
        return $this->belongsTo(BalanceAccount::class,'bank_account_id','id');
    }
    function vendor(){
        return $this->belongsTo(Vendor::class,'supplier_id','id');
    }
    function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    function items(){
        return $this->hasMany(ProductPurchase::class,'purchase_id','id')->orderBy('id','desc');
    }
}
