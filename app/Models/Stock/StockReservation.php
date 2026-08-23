<?php

namespace App\Models\Stock;

use App\Models\DealerPurchaseOrder;
use App\Models\Inventory\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'warehouse_id', 'business_id',
        'qty', 'order_id', 'order_number', 'customer_name',
        'status', 'expires_at', 'fulfilled_at', 'cancelled_at', 'cancel_reason',
    ];

    protected $casts = [
        'qty' => 'integer',
        'expires_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function order()
    {
        return $this->belongsTo(DealerPurchaseOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('expires_at', '>', now());
    }

    public function scopeExpiring($query, int $hours = 24)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '<=', now()->addHours($hours))
            ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'active')->where('expires_at', '<=', now());
    }

    public function fulfill(): void
    {
        $this->update(['status' => 'fulfilled', 'fulfilled_at' => now()]);
    }

    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancel_reason' => $reason,
        ]);
    }
}