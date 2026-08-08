<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    // use HasFactory;
    protected $fillable = [
        'product_id',
        'configurable_product_id',
        'is_default',
    ];
    public function items(){
        return $this->hasMany(ProductVariationItem::class,'variation_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function configurableProduct()
    {
        return $this->belongsTo(Product::class, 'configurable_product_id')->withDefault();
    }
    public static function getVariationByAttributes($configurableProductId,$attributes)
    {
        //return $attributes;
        return self::query()
            ->where('configurable_product_id', $configurableProductId)

            ->whereHas('variationItems', function ($query) use ($attributes) {
                $query->whereIn('attribute_id', array_unique($attributes));
            }, '=', count(array_unique($attributes)))
            ->with('variationItems')
            ->first();
    }
    public static function getVariationByAttributesOrCreate($configurableProductId,$attributes)
    {

        $variation = self::getVariationByAttributes($configurableProductId, $attributes);

        if (! $variation) {
            $variation = self::query()->create([
                'configurable_product_id' => $configurableProductId,
                'business_id' => auth()->user()->business->id
            ]);

            foreach ($attributes as $attribute) {
                ProductVariationItem::query()->create([
                    'attribute_id' => $attribute,
                    'variation_id' => $variation->id,
                    'business_id' => auth()->user()->business->id
                ]);
            }

            return [
                'variation' => $variation,
                'created' => true,
            ];
        }

        return [
            'variation' => $variation,
            'created' => false,
        ];
    }
    public function productAttributes()
    {
        return $this->belongsToMany(
            Attribute::class,
            'product_variation_items',
            'variation_id',
            'attribute_id'
        );
    }
     public static function correctVariationItems($configurableProductId, array $attributes)
    {
        if (! $attributes) {
            $attributes = [0];
        }

        $items = ProductVariationItem::query()
            ->join(
                'product_variations',
                'product_variations.id',
                '=',
                'product_variation_items.variation_id'
            )
            ->whereRaw(
                'product_variation_items.id IN
                (
                    SELECT product_variation_items.id
                    FROM product_variation_items
                    JOIN product_variations ON product_variations.id = product_variation_items.variation_id
                    WHERE product_variations.configurable_product_id = ' . $configurableProductId . '
                    AND product_variation_items.attribute_id NOT IN (' . implode(',', $attributes) . ')
                )
            '
            )
            ->where('product_variations.configurable_product_id', $configurableProductId)
            ->distinct()
            ->pluck('product_variation_items.id')
            ->all();

        return ProductVariationItem::query()->whereIn('id', $items)->delete();
    }

    public function variationItems()
    {
        return $this->hasMany(ProductVariationItem::class, 'variation_id');
    }
    // public static function boot() {

    //     parent::boot();

    //     self::creating(function ($model) {

    //         $model->uuid = (string)Uuid::generate();

    //     });
    //     self::deleted(function($variation) {
    //         $variation->productAttributes()->detach();

    //         if ($variation->product) {
    //             $variation->product->delete();

    //         }
    //     });

    // }
    protected static function booted():void
    {
     // dd('sdsfsdf');
    //     parent::boot();

    }
    public static function getAttributeIdsOfChildrenProduct(int|string $productId)
    {

        return
         self::query()
            ->join(
                'product_variation_items',
                'product_variation_items.variation_id',
                '=',
                'product_variations.id'
            )
            ->distinct()
            ->select('product_variation_items.attribute_id')
            ->where('product_variations.product_id', $productId)
            ->get()
            ->pluck('attribute_id')
            ->toArray();
    }
}
