<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Expense extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    function category(){
        return $this->belongsTo(ExpenseCategory::class,'category_id');
    }
    function method(){
        return $this->belongsTo(PaymentMethod::class,'method_id');
    }
    function balance_account(){
        return $this->belongsTo(BalanceAccount::class,'balance_account_id');
    }
}
