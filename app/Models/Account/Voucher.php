<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Voucher extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function method(){
        return $this->belongsTo(PaymentMethod::class,'fund_id','id');
    }
    function balance_account(){
        return $this->belongsTo(BalanceAccount::class,'voucher_by','id');
    }
    // public function fund(){
    //     return $this->belongsTo(Fund::class,"fund_id","id");
    // }
    // public function v_ledger(){
    //     return $this->belongsTo(Ledger::class,"voucher_by","id");
    // }
    public function details(){
        return $this->hasMany(VoucherDetail::class,"voucher_id",'id')->with('ledger');
    }
    public function trans_items(){
        return $this->hasMany(AccountTransaction::class,"relation_id",'id')->with('ledger');
    }
}
