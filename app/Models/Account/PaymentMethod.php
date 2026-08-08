<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;

class PaymentMethod extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function getImageShowAttribute(){
        return $this->image == '' ? $this->no_image : asset("public/upload/payment_methods/".$this->image);
    }
    function getNoImageAttribute(){
        return asset("public/images/No-image.jpg");
    }
    public function withdraw(){
        return $this->hasMany('App\Models\Withdraw', 'method_id');
    }
    public function order(){
        return $this->hasMany('App\Models\Order', 'payment_method');
    }
    public function account(){
        return $this->belongsTo(AccountHead::class,'account_id','id');
    }
    function balance_account(){
        return $this->belongsTo(BalanceAccount::class,'pos_account_id');
    }

}
