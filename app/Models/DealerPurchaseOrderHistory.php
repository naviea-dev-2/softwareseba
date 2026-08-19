<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerPurchaseOrderHistory extends Model
{
    protected $fillable = [
        'dealer_purchase_order_id',
        'status',
        'note',
        'created_by',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(
            DealerPurchaseOrder::class,
            'dealer_purchase_order_id'
        );
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}