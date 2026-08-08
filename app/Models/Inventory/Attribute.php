<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

     protected $fillable = [
        'title',
        'slug',
        'color',
        'order',
        'attribute_set_id',
        'image',
        'is_default',
    ];


    public function productAttributeSet()
    {
        return $this->belongsTo(AttributeSet::class, 'attribute_set_id');
    }
    public function productVariationItems()
    {
        return $this->hasMany(ProductVariationItem::class, 'attribute_id');
    }
     public function getAttributeStyle($attributeSet = null, $productVariations = [])
    {
        if ($attributeSet && $attributeSet->use_image_from_product_variation) {
            foreach ($productVariations as $productVariation) {
                $attribute = $productVariation->productAttributes->where('attribute_set_id', $attributeSet->id)->first();
                if ($attribute && $attribute->id == $this->id && ($image = $productVariation->product->image)) {
                    return 'background-image: url(' . asset("public/media/".$image) . '); background-size: cover; background-repeat: no-repeat; background-position: center;';
                }
            }
        }

        if ($this->image) {
            return 'background-image: url(' . asset("public/media/".$image) . '); background-size: cover; background-repeat: no-repeat; background-position: center;';
        }

        return 'background-color: ' . ($this->color ?: '#000') . ' !important;';
    }
    protected static function booted(): void
    {
        // self::saving(function (self $model) {
        //     $model->slug = self::createSlug($model->slug ?: $model->title, $model->getKey());
        // });
       self::deleting(function (Attribute $attribute) {
            $attribute->productVariationItems()->each(fn (ProductVariationItem $productVariationItem) => $productVariationItem->delete());
        });

    }
}
