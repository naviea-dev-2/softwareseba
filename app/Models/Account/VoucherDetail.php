<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class VoucherDetail extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    public function ledger(){
        return $this->belongsTo(AccountHead::class,"ledger_id","id");
    }

}
