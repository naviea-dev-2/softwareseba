<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariationItem extends Model
{
    use HasFactory;
    protected $table = "product_variation_items";
    protected $fillable = [
        'attribute_id',
        'variation_id',
    ];
    public static function getVariationsInfo(array $versionIds)
    {
        return self::query()
            ->join('attributes', 'attributes.id', '=', 'product_variation_items.attribute_id')
            ->join(
                'attribute_sets',
                'attribute_sets.id',
                '=',
                'attributes.attribute_set_id'
            )
            ->distinct()
            ->whereIn('product_variation_items.variation_id', $versionIds)
            ->select([
                'product_variation_items.variation_id',
                'attributes.*',
                'attribute_sets.title as attribute_set_title',
                'attribute_sets.slug as attribute_set_slug',
            ])
            ->get();
    }
     public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id')->withDefault();
    }
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id')->withDefault();
    }


}
