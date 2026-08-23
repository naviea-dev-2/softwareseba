<?php

namespace App\Models\Stock;

use App\Models\Stock\WarehouseTransfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

     protected $fillable = [
        'business_id',
        'name',
        'code',
        'location',
        'type',
        'capacity',
        'used_capacity',
        'is_active',
        'address',
        'contact_person',
        'contact_phone',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'used_capacity' => 'integer',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function stockBalances()
    {
        return $this->hasMany(StockBalance::class);
    }

    public function outgoingTransfers()
    {
        return $this->hasMany(WarehouseTransfer::class, 'from_warehouse_id');
    }

    public function incomingTransfers()
    {
        return $this->hasMany(WarehouseTransfer::class, 'to_warehouse_id');
    }

    public function getUtilizationPercentageAttribute(): float
    {
        return $this->capacity > 0 ? ($this->used_capacity / $this->capacity) * 100 : 0;
    }
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}