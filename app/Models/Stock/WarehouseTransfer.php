<?php

namespace App\Models\Stock;

use App\Models\Stock\Warehouse;
use App\Models\Stock\WarehouseTransferItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id', 'from_warehouse_id', 'to_warehouse_id', 'status',
        'requested_by', 'approved_by', 'received_by',
        'tracking_number', 'carrier', 'notes',
        'approved_at', 'shipped_at', 'received_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'shipped_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function items()
    {
        return $this->hasMany(WarehouseTransferItem::class, 'transfer_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function approve(int $userId): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    public function ship(string $trackingNumber = null, string $carrier = null): void
    {
        $this->update([
            'status' => 'in_transit',
            'tracking_number' => $trackingNumber,
            'carrier' => $carrier,
            'shipped_at' => now(),
        ]);
    }

    public function receive(int $userId): void
    {
        $this->update([
            'status' => 'completed',
            'received_by' => $userId,
            'received_at' => now(),
        ]);
    }
}