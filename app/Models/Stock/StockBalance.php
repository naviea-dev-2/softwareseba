<?php

namespace App\Models\Stock;

use App\Models\Inventory\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'business_id',
        'total_qty',
        'reserved_qty',
        'available_qty',
        'incoming_qty',
        'reorder_point',
        'reorder_qty',
        'is_reorder_alert',
        'last_counted_at',
        'last_moved_at',
    ];

    protected $casts = [
        'total_qty' => 'integer',
        'reserved_qty' => 'integer',
        'available_qty' => 'integer',
        'incoming_qty' => 'integer',
        'reorder_point' => 'integer',
        'reorder_qty' => 'integer',
        'is_reorder_alert' => 'boolean',
        'last_counted_at' => 'datetime',
        'last_moved_at' => 'datetime',
    ];

    // Auto-calculate available_qty
    protected static function booted()
    {
        static::saving(function ($balance) {
            $balance->available_qty = $balance->total_qty - $balance->reserved_qty;
            $balance->is_reorder_alert = $balance->available_qty <= $balance->reorder_point;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function movements()
    {
        return StockMovement::where(
            'product_id',
            $this->product_id
        )
            ->where(
                'warehouse_id',
                $this->warehouse_id
            );
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('available_qty', '<=', 'reorder_point');
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_qty', '>', 0);
    }
}
