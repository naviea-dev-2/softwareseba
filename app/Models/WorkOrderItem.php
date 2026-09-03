<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderItem extends Model
{
    protected $fillable = [
        'work_order_id', 'item_type', 'product_id', 'description',
        'quantity', 'unit_cost', 'total_cost',
        'source_warehouse_id', 'target_warehouse_id', 'consumed_qty'
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class);
    }

    public function sourceWarehouse(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Stock\Warehouse::class, 'source_warehouse_id');
    }

    public function targetWarehouse(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Stock\Warehouse::class, 'target_warehouse_id');
    }
}