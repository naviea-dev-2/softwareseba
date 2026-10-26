<?php

namespace App\Models\Production;

use App\Models\Production\WheatPurchasePayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WheatPurchase extends Model
{
    use SoftDeletes;
    protected $table = 'production_wheat_purchases';
    protected $guarded = ['id'];
    protected $casts = [
        'purchase_date' => 'date',
        'empty_weight_kg' => 'decimal:2',
        'loaded_weight_kg' => 'decimal:2',
        'net_weight_kg' => 'decimal:2',
        'rate_per_kg' => 'decimal:2',
        'purchase_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }
    public function payments()
    {
        return $this->hasMany(WheatPurchasePayment::class);
    }

    public function getDueAmountAttribute(): float
    {
        return (float) $this->purchase_amount - (float) ($this->paid_amount ?? 0);
    }
}
