<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Depot extends Model
{
    protected $fillable = [
        'super_depot_id',
        'code',
        'name',
        'manager_id',
        'phone',
        'email',
        'address',
        'division',
        'district',
        'area',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function superDepot(): BelongsTo
    {
        return $this->belongsTo(SuperDepot::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function dealers(): HasMany
    {
        return $this->hasMany(Dealer::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(
            DealerPurchaseOrder::class
        );
    }
}
