<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;

class AccountHead extends Model
{
    use HasFactory, Multitenantable, WithBusiness;

    function transaction(){
        return $this->belongsTo(AccountTransaction::class,'opening_id');
    }
}
