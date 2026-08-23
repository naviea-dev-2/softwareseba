<?php

namespace App\Models\Stock;

use App\Models\Stock\WarehouseTransfer;
use App\Models\Inventory\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseTransferItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_id', 'product_id', 'qty', 'shipped_qty', 'received_qty', 'damaged_qty', 'status', 'notes',
    ];

    protected $casts = [
        'qty' => 'integer',
        'shipped_qty' => 'integer',
        'received_qty' => 'integer',
        'damaged_qty' => 'integer',
    ];

    public function transfer()
    {
        return $this->belongsTo(WarehouseTransfer::class, 'transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function markShipped(int $qty): void
    {
        $this->update(['shipped_qty' => $qty, 'status' => 'shipped']);
    }

    public function markReceived(int $qty, int $damaged = 0): void
    {
        $this->update([
            'received_qty' => $qty,
            'damaged_qty' => $damaged,
            'status' => $damaged > 0 ? 'damaged' : 'received',
        ]);
    }
}