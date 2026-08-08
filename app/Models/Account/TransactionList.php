<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;

class TransactionList extends Model
{
    use HasFactory, Multitenantable, WithBusiness;

    public function account_sub_head(){
        return $this->belongsTo('App\Models\Account\AccountSubHead','account_sub_head_id');
    }


}
