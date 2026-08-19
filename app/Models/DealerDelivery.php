<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DealerDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'purchase_order_id',
        'delivery_number',
        'delivery_date',
        'depot_id',
        'vehicle_no',
        'driver_name',
        'driver_mobile',
        'status',
        'note',
        'created_by',
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(DealerPurchaseOrder::class, 'purchase_order_id');
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function items()
    {
        return $this->hasMany(DealerDeliveryItem::class);
    }

    public function trackings()
    {
        return $this->hasMany(DealerDeliveryTracking::class)
            ->latest('created_at');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}