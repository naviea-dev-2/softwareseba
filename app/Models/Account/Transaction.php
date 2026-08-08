<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;

class Transaction extends Model
{
    use HasFactory, Multitenantable, WithBusiness;

    public function balance_account(){
        return $this->belongsTo('App\Models\Account\BalanceAccount', 'balance_account_id');
    }
    public function method(){
        return $this->belongsTo('App\Models\Account\PaymentMethod', 'payment_method_id');
    }

    public function account_head(){
        return $this->belongsTo('App\Models\Account\AccountHead', 'account_head_id');
    }



}
