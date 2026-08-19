<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerSecurityMoney extends Model
{
    protected $table = 'dealer_security_money';

    protected $fillable = [
        'dealer_id',
        'transaction_no',
        'transaction_type',
        'amount',
        'payment_method',
        'reference_no',
        'transaction_date',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}