<?php

namespace App\Models\Production;

use App\Models\BankAccount;
use App\Models\Production\WheatPurchase;
use Illuminate\Database\Eloquent\Model;

class WheatPurchasePayment extends Model
{
    protected $table = 'production_wheat_purchase_payments';

    protected $fillable = [
        'wheat_purchase_id',
        'amount',
        'method',
        'bank_account_id',
        'note',
    ];

    public function purchase()
    {
        return $this->belongsTo(WheatPurchase::class, 'wheat_purchase_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}