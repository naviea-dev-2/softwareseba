<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DealerPurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'dealer_id',
        'depot_id',
        'po_date',
        'expected_delivery_date',
        'delivery_address',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'grand_total',
        'status',
        'note',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'po_date' => 'date',
        'expected_delivery_date' => 'date',
        'approved_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function depot(): BelongsTo
    {
        return $this->belongsTo(Depot::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            DealerPurchaseOrderItem::class
        );
    }

    public function histories(): HasMany
    {
        return $this->hasMany(
            DealerPurchaseOrderHistory::class
        )->latest();
    }
}