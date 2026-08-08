<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Business;
class AttributeSet extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    protected $fillable = [
        'business_type_id',
        'business_id',
        'title',
        'slug',
		'order',
    ];
    function business(){
        return $this->belongsTo(Business::class,'business_id');
    }
    function attributes(){
        return $this->hasMany(Attribute::class,'attribute_set_id');
    }
    // public function categories(): MorphToMany
    // {
    //     return $this->morphToMany(ProductCategory::class, 'reference', 'ec_product_categorizables', 'reference_id', 'category_id');
    // }
    protected static function booted(): void
    {
        // self::saving(function (self $model) {
        //     $model->slug = self::createSlug($model->slug ?: $model->title, $model->getKey());
        // });

        self::deleting(function (AttributeSet $productAttributeSet) {
            $productAttributeSet->attributes()->each(fn (Attribute $attribute) => $attribute->delete());
        });
    }
     public static function getByProductId($productId)
    {
        if (! is_array($productId)) {
            $productId = [$productId];
        }
       // return $productId;
        return self::query()
            ->join(
                'product_with_attribut_sets',
                'attribute_sets.id',
                'product_with_attribut_sets.attribute_set_id'
            )
            ->whereIn('product_with_attribut_sets.product_id', $productId)

            ->distinct()
            ->with(['attributes'])
            ->select(['attribute_sets.*', 'product_with_attribut_sets.order'])
            ->orderBy('product_with_attribut_sets.order')
            ->get();
    }

}
