<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DealerDeliveryTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_delivery_id',
        'status',
        'location',
        'remarks',
        'created_at',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function delivery()
    {
        return $this->belongsTo(
            DealerDelivery::class,
            'dealer_delivery_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}