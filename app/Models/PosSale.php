<?php

namespace App\Models;

use App\Models\Account\PaymentMethod;
use App\Models\Inventory\Branch;
use App\Models\Inventory\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class PosSale extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    function items(){
        return $this->hasMany(PosSaleDetails::class,'sale_id','id')->orderBy('id','desc');
    }
    function pay_method(){
        return $this->belongsTo(PaymentMethod::class,'payment_method','id');
    }
}
