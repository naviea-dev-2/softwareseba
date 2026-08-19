<?php

namespace App\Models;

use App\Models\Inventory\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DealerDeliveryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_delivery_id',
        'product_id',
        'purchase_order_item_id',
        'quantity',
        'unit_price',
        'note',
    ];

    public function delivery()
    {
        return $this->belongsTo(
            DealerDelivery::class,
            'dealer_delivery_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(
            DealerPurchaseOrderItem::class,
            'purchase_order_item_id'
        );
    }
}