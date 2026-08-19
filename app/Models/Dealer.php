<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dealer extends Model
{
    protected $fillable = [
        'depot_id',
        'code',
        'name',
        'business_name',
        'owner_name',
        'phone',
        'email',
        'address',
        'division',
        'district',
        'area',
        'nid',
        'trade_license',
        'credit_limit',
        'payment_terms',
        'opening_balance',
        'sales_person_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => 'boolean',
        'credit_limit' => 'decimal:2',
        'opening_balance' => 'decimal:2',
    ];

    public function depot(): BelongsTo
    {
        return $this->belongsTo(Depot::class);
    }

    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function securityMoney(): HasMany
    {
        return $this->hasMany(DealerSecurityMoney::class);
    }

    public function getSecurityBalanceAttribute(): float
    {
        return (float) $this->securityMoney()
            ->selectRaw("
                COALESCE(
                    SUM(
                        CASE
                            WHEN transaction_type = 'deposit'
                                THEN amount
                            WHEN transaction_type = 'refund'
                                THEN -amount
                            WHEN transaction_type = 'adjustment'
                                THEN amount
                            ELSE 0
                        END
                    ), 0
                ) as balance
            ")
            ->value('balance');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(
            DealerPurchaseOrder::class
        );
    }

    public function deliveries()
    {
        return $this->hasMany(DealerDelivery::class);
    }
}
