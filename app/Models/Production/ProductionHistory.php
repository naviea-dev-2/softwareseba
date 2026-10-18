<?php

namespace App\Models\Production;

use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionHistory extends Model
{
    use HasFactory;

    protected $table = 'production_histories';

    protected $fillable = [
        'user_id',
        'production_order_id',
        'work_order_id',
        'event_type',
        'quantity',
        'recorded_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    /**
     * Production Order relationship
     */
    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(
            ProductionOrder::class,
            'production_order_id'
        );
    }

    /**
     * Work Order relationship
     */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            WorkOrder::class,
            'work_order_id'
        );
    }
}