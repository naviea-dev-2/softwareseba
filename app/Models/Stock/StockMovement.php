<?php

namespace App\Models\Stock;

use App\Models\Inventory\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'warehouse_id', 'business_id', 'type',
        'qty', 'before_qty', 'after_qty',
        'reference_type', 'reference_id', 'reason',
        'from_warehouse_id', 'to_warehouse_id',
        'user_id', 'user_name',
    ];

    protected $casts = [
        'qty' => 'integer',
        'before_qty' => 'integer',
        'after_qty' => 'integer',
    ];

    // This table is append-only - never update
    public static function boot()
    {
        parent::boot();

        static::updating(function () {
            throw new \Exception('Stock movements are immutable and cannot be updated.');
        });

        static::deleting(function () {
            throw new \Exception('Stock movements are immutable and cannot be deleted.');
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

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByReference($query, string $type, string $id)
    {
        return $query->where('reference_type', $type)->where('reference_id', $id);
    }
}