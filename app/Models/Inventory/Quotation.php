<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Quotation extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    function items(){
        return $this->hasMany(ProductQuotation::class,'quotation_id','id')->orderBy('id','desc');
    }
}
