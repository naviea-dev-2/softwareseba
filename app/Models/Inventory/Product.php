<?php

namespace App\Models\Inventory;

use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Traits\Multitenantable;
// use App\Traits\WithBusinesstype;
use App\Traits\Multitenantable;
use App\Traits\WithBusinesstype;
use App\Models\Business;
class Product extends Model
{
    use HasFactory;
    use HasFactory, Multitenantable, WithBusinesstype;
    function getImageShowAttribute(){
        return $this->image == '' ? $this->no_image : asset("public/upload/products/".$this->image);
    }
    function business(){
        return $this->belongsTo(Business::class,'business_id');
    }
    function getNoImageAttribute(){
        return asset("public/images/No-image.jpg");
    }
    function category(){
        return $this->belongsTo(Category::class)->withDefault();
    }
    function tax(){
        return $this->belongsTo(Tax::class,'tax_id','id')->withDefault();
    }
    function brand(){
        return $this->belongsTo(Brand::class)->withDefault();
    }
    function manufacture(){
        return $this->belongsTo(Manufature::class,'manufacture_id')->withDefault();
    }
    function unit(){
        return $this->belongsTo(Unit::class,'unit_id');
    }
    function generic(){
        return $this->belongsTo(Generic::class,'generic_id');
    }
    function product_type(){
        return $this->belongsTo(ProductType::class,'type_id');
    }
    function p_stock(){
        return $this->hasMany(ProductStock::class,"product_id",'id');
    }
    function getProductStockAttribute(){
        return $this->p_stock->first();
    }
    function product_price(){
        return $this->hasMany(ProductPrice::class,"product_id",'id')->orderBy('id','asc');
    }
    function product_u_price($aar){
        return $this->hasMany(ProductPrice::class,"product_id",'id')
        ->leftJoin('units','units.id','product_prices.unit_id')
        ->orderBy('product_prices.id','asc')->get();
    }

    function product_p_price(){
        return $this->hasMany(ProductPrice::class,"product_id",'id')->orderBy('purchase_price','asc');
    }

    public function productAttributeSets()
    {
        //dd(auth()->user()->business->id);
        return $this->belongsToMany(
            AttributeSet::class,
            'product_with_attribut_sets',
            'product_id',
            'attribute_set_id'
        );
    }
    function variations(){
        return $this->hasMany(ProductVariation::class,"configurable_product_id");
    }
    function d_variations(){
        return $this->hasMany(ProductVariation::class,"configurable_product_id");
    }
    function atttribute_sets(){
        return $this->hasMany(ProductWithAttributSet::class,'product_id');
        // return $this->hasMany(ProductWithAttributSet::class,'product_id')->where('business_id',auth()->user()->business->id);
    }
    public function variationInfo()
    {
        return $this->hasOne(ProductVariation::class, 'product_id')->withDefault();
    }
    public function d_variationInfo()
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }
    protected function getOriginalProductAttribute()
    {
        if (!$this->is_variant) {
            return $this;
        }
        return $this->variationInfo->id ? $this->variationInfo->configurableProduct : $this;

    }
    public function variationProductAttributes()
    {
        return $this
            ->hasMany(ProductVariation::class, 'product_id')
            ->join(
                'product_variation_items',
                'product_variation_items.variation_id',
                '=',
                'product_variations.id'
            )
            ->join('attributes', 'attributes.id', '=', 'product_variation_items.attribute_id')
            ->join(
                'attribute_sets',
                'attribute_sets.id',
                '=',
                'attributes.attribute_set_id'
            )
            ->distinct()
            ->select([
                'product_variations.product_id',
                'product_variations.configurable_product_id',
                'attributes.*',
                'attribute_sets.title as attribute_set_title',
                'attribute_sets.slug as attribute_set_slug',
            ])
            // ->where('product_variations.business_id',auth()->user()->business->id)
            ->orderBy('order');
    }
    public function getVariationAttributesAttribute(): string
    {

        if (! $this->variationProductAttributes->count()) {
            return '';
        }

        $attributes = $this->variationProductAttributes->pluck('title', 'attribute_set_title')->toArray();

        return '(' . mapped_implode(', ', $attributes, ': ') . ')';
    }
    public function getVariationAttributes2Attribute()
    {

        if (! $this->variationProductAttributes->count()) {
            return '';
        }

        $attributes = $this->variationProductAttributes->pluck('title')->toArray();
        $out='(';
        foreach($attributes as $k=>$a){
            if(($k+1) == count($attributes)){
                $out.= $a.')';
            }else{
                $out.= $a."<span>\</span>";
            }

        }
        return $out;
        return '(' .implode("",$attributes).')';
        return '(' . mapped_implode(', ', $attributes, ': ') . ')';
    }
    protected static function booted(): void
    {


        self::deleting(function (self $product) {
            $product->d_variations()->each(fn (ProductVariation $item) => $item->delete());
            $product->d_variationInfo()->each(fn (ProductVariation $item) => $item->delete());
            $product->productAttributeSets()->detach();
        });


    }
}
