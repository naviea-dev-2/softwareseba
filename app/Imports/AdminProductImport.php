<?php

namespace App\Imports;

use App\Models\Inventory\Attribute;
use App\Models\Inventory\AttributeSet;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Category;
use App\Models\Inventory\Generic;
use App\Models\Inventory\Manufature;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductType;
use App\Models\Inventory\ProductVariation;
use App\Models\Inventory\ProductWithAttributSet;
use App\Models\Inventory\Tax;
use App\Models\Inventory\Unit;
use App\Models\ProductStock;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AdminProductImport implements
    ToModel,
    WithMapping,
    WithValidation,
    SkipsOnFailure,
    SkipsOnError,
    WithChunkReading,
    WithHeadingRow
{
    use Importable;
    use SkipsFailures;
    use SkipsErrors;
    use ImportTrait;
    protected Collection $languages;
    protected Collection $brands;

    protected Collection $categories;
    protected Collection $product_types;

    protected Collection $generics;

    protected Collection $tags;

    protected Collection $taxes;

    protected Collection $stores;
    protected Collection $units;
    protected Collection $manufactures;
    protected int $rowCurrent = 1;
    protected string $business_type_id = '0';
    protected int $business_id = 0;
    protected int $is_variation_column = 0;
    protected Collection $productAttributeSets;
    protected Collection $attributeSetCur;
    protected array $b_types;
    public function __construct(
        protected Request $request,
        $business_type_id
    ) {
        $this->b_types =  f_b_types();
        $this->languages = collect();
        $this->categories = collect();
        $this->brands = collect();
        $this->stores = collect();
        $this->units = collect();
        $this->manufactures = collect();
        $this->taxes = collect();
        $this->productAttributeSets = collect();
        $this->attributeSetCur = collect();
        $this->product_types = collect();
        $this->generics = collect();

        $this->business_type_id = $business_type_id;
        
        $attributeSets= AttributeSet::query()
            ->with('attributes')
            ->get();
        foreach($attributeSets as $attributeSet) {
            $this->productAttributeSets->push([
                'keyword' => $attributeSet->id,
                'name' => str_slug_c($attributeSet->title),
            ]);
        }
        config(['excel.imports.ignore_empty' => true]);
    }

    public function model(array $row): Product|null
    {

       // dd("ss");
    //     $name = $this->request->input('name');
    //     $slug = $this->request->input('slug');
    //      $product = new Product;
    //    // return $product;
         return $this->storeProduct();

    }
    public function postSaveAllVersions($versionInRequest,$id, $isUpdateProduct = true){
        $product = Product::query()->findOrFail($id);
        foreach ($versionInRequest as $variationId => $version) {
            $variation = ProductVariation::query()->find($variationId);

            if (! $variation) {
                continue;
            }
            if (! $variation->product_id || $isUpdateProduct) {
                $isNew = false;
                $productRelatedToVariation = Product::query()->find($variation->product_id);

                if (! $productRelatedToVariation) {
                    $productRelatedToVariation = new Product();
                    $isNew = true;
                }

                $productRelatedToVariation->product_name =Arr::get($version, 'product_name',$product->product_name);
                // $productRelatedToVariation->slug =Arr::get($version, 'slug',$product->slug);

                $productRelatedToVariation->status = $product->status;
                $productRelatedToVariation->brand_id = $product->brand_id;
                $productRelatedToVariation->is_variant = 1;
                $productRelatedToVariation->sku = Arr::get($version, 'sku');
                $productRelatedToVariation->business_id = $this->business_id;
                // $productRelatedToVariation->cost_price = Arr::get($version, 'purchase_price', $product->cost_price);
                // $productRelatedToVariation->sale_price = Arr::get($version, 'sale_price', $product->sale_price);
                // $productRelatedToVariation->whole_sale_price = empty(Arr::get($version, 'wholesale_pirce', $product->whole_sale_price)) ? 0 : Arr::get($version, 'wholesale_pirce', $product->whole_sale_price) ;
                // $productRelatedToVariation->whole_sale_qty = Arr::get($version, 'wholesale_qty', $product->whole_sale_qty);

                // $productRelatedToVariation->is_stock = Arr::get($version, 'is_stock', $product->is_stock);
                // $productRelatedToVariation->stock_qty = empty(Arr::get($version, 'stock_qty', $product->stock_qty)) ? 0 : Arr::get($version, 'stock_qty', $product->stock_qty);
                // $productRelatedToVariation->stock_status_id = empty(Arr::get($version, 'stock_status_id', $product->stock_status_id)) ? 0 : Arr::get($version, 'stock_status_id', $product->stock_status_id);

                // $productRelatedToVariation->length = Arr::get($version, 'ship_length', $product->length);
                // $productRelatedToVariation->width = Arr::get($version, 'ship_width', $product->width);
                // $productRelatedToVariation->height = Arr::get($version, 'ship_height', $product->height);
                // $productRelatedToVariation->actual_weight = Arr::get($version, 'ship_weight', $product->actual_weight);

                //  $productRelatedToVariation->input_length = Arr::get($version, 'input_length', $product->input_length);
                // $productRelatedToVariation->input_width = Arr::get($version, 'input_width', $product->input_width);
                // $productRelatedToVariation->input_height = Arr::get($version, 'input_height', $product->input_height);
                // $productRelatedToVariation->input_weight = Arr::get($version, 'input_weight', $product->input_weight);

                //  $productRelatedToVariation->input_unit = Arr::get($version, 'input_unit', $product->input_unit);
                // $productRelatedToVariation->input_unit_w = Arr::get($version, 'input_unit_w', $product->input_unit_w);
                // $productRelatedToVariation->input_unit_h = Arr::get($version, 'input_unit_h', $product->input_unit_h);
                // $productRelatedToVariation->input_unit_l = Arr::get($version, 'input_unit_l', $product->input_unit_l);
                 //return $productRelatedToVariation;
                $productRelatedToVariation->save();
                $product_stock = new ProductStock;
                $product_stock->qty =Arr::get($version, 'stock_qty', 0);
                $product_stock->purchase_price = Arr::get($version, 'purchase_price', 0);
                $product_stock->sale_price = Arr::get($version, 'sale_price', 0);
                $product_stock->product_id = $productRelatedToVariation->id;
                $product_stock->business_id = $this->business_id;
                $product_stock->save();

                $variation->product_id = $productRelatedToVariation->id;

            }
            $variation->is_default = Arr::get($version, 'variation_default_id', 0) == $variation->id;

            $variation->save();
            if (isset($version['attribute_sets']) && is_array($version['attribute_sets'])) {
                $variation->productAttributes()->sync($version['attribute_sets']);
            }
        }
        return "sucess";
    }
    function storeVairation($variation,$request){
        if ($variation->is_default) {
            $request->merge([
                'variation_default_id' => $variation->id,
            ]);
        }
         $this->postSaveAllVersions(
            [$variation->id => $request->input()],
            $variation->configurable_product_id
        );

        $del_variants = ProductVariation::query()->whereNull('product_id')->get();
        foreach($del_variants as $del_variant) {
                $del_variant->variationItems->each->delete();
                $del_variant->delete();
        }
    }
    public function storeProduct(): Product|Model|null
    {
        //dd($this->request);
       // $product = new Product();

         //return $product;
        $existingProduct = Product::query()->where('product_name',$this->request->name)->where('is_variant',0)->first();


        if($existingProduct) {
            foreach($this->attributeSetCur as $cur_attr){

                $attr_data= $this->request->input($cur_attr['name']);

                if($attr_data != "" || $attr_data != null){
                    $product_with_set = ProductWithAttributSet::where('attribute_set_id',$cur_attr['keyword'])->where('product_id', $existingProduct->id)->first();
                    if($product_with_set == null){
                        $product_with_set = new ProductWithAttributSet();
                        $product_with_set->product_id = $existingProduct->id;
                        $product_with_set->business_id = $this->business_id;
                        $product_with_set->attribute_set_id = $cur_attr['keyword'];
                        $product_with_set->save();
                    }
                    $attribute = Attribute::where('title',trim($attr_data))->first();

                    if($attribute == null){
                        $attribute = new Attribute();
                        $attribute->attribute_set_id = trim($cur_attr['keyword']);
                        $attribute->title = trim($attr_data);
                        $attribute->color = '#61a402';
                        $attribute->save();
                    }
                    $addedAttributes =[$attribute->id];

                    //  dd($this->request);
                    $result = ProductVariation::getVariationByAttributesOrCreate(
                            $existingProduct->id,
                            $addedAttributes
                        );
                  // dd($result);
                    if (! $result['created']) {
                        return $existingProduct;
                    }else{
                        $this->storeVairation($result['variation'],$this->request);
                    }

                }
            }
            //$product = $existingProduct;
           return $existingProduct;
        }
         $product = new Product();
        

        
        // $media_option = Media_option::where('title',  $this->request->featured_image)->first();
        // if($media_option){
        //     $featured_image =$media_option->thumbnail;
        // }else{
             $featured_image='';
        // }
        
        $product->product_name = $this->request->name;
        $product->product_code = $this->request->product_code;
        $product->batch_no = $this->request->batch_no ?? '';
        $product->imei_1=$this->request->imei_1 ?? '';
        $product->imei_2=$this->request->imei_2 ?? '';
		$product->category_id = $this->request->cat_id ?? 0;
		$product->manufacture_id = $this->request->manufacture_id ?? 0;
		$product->brand_id = $this->request->brand_id ?? 0;
		$product->unit_id = $this->request->unit_id ?? 0;
		$product->generic_id = $this->request->generic_id ?? 0;
		$product->type_id = $this->request->type_id ?? 0;
		$product->business_type_id = $this->request->b_type_id ?? 0;
        $product->exipre_date=$this->request->expire_date ?? date('now');
        $product->manufacture_date=$this->request->manufacturer_date ?? date('now');
        $product->discount_type=$this->request->discount_type ? Str::lower($this->request->discount_type) : '';
        $product->discount=$this->request->discount ?? 0;
        $product->tax_id=$this->request->tax_id ?? 0;

        // $product->fill($data);
        $product->save();
         
        
        $is_default=1;
        foreach($this->attributeSetCur as $cur_attr){

            $attr_data= $this->request->input($cur_attr['name']);

            if($attr_data != "" || $attr_data != null){
                $product_with_set = ProductWithAttributSet::where('attribute_set_id',$cur_attr['keyword'])->where('product_id', $product->id)->first();
                if($product_with_set == null){
                    $product_with_set = new ProductWithAttributSet();
                    $product_with_set->product_id = $product->id;
                    $product_with_set->attribute_set_id = $cur_attr['keyword'];
                    $product_with_set->save();
                }
                $attribute = Attribute::where('title',trim($attr_data))->first();

                if($attribute == null){
                    $attribute = new Attribute();
                    $attribute->attribute_set_id = trim($cur_attr['keyword']);
                    $attribute->title = trim($attr_data);
                    $attribute->color = '#61a402';
                    $attribute->save();
                }
                $addedAttributes =[$attribute->id];

                //  dd($this->request);
                $result = ProductVariation::getVariationByAttributesOrCreate(
                        $product->id,
                        $addedAttributes
                    );
                // dd($result);
                if ($result['created']) {
                    $result['variation']->is_default = $is_default;

                    $this->storeVairation($result['variation'],$this->request);
                    $is_default= 0;

                }

            }
        }
        
        $collect = collect([
            'name' => $product->name,
            'model' => $product,
        ]);

        $this->onSuccess($collect);

        return $product;
    }

    public function map($row): array
    {
        // dd($row);
        if($this->rowCurrent == 1){
            foreach($row as $k=>$header){
                $a_set = $this->productAttributeSets->firstWhere('name', $k);
                if($a_set){
                        $this->is_variation_column=1;
                        $this->attributeSetCur->push($a_set);
                }
            }
        }
        //dd($this->attributeSetCur);
        $new_row = array();
        ++$this->rowCurrent;
        $row = $this->mapLocalization($row);
        //dd($new_row);
        $row = $this->setCategoriesToRow($row);
        $row = $this->setProductTypeToRow($row);
        $row = $this->setGenericToRow($row);
        $row = $this->setBrandToRow($row);
        $row = $this->setTaxToRow($row);
        $row = $this->setManufactureToRow($row);
        $row = $this->setUnitToRow($row);
        $row = $this->setBusinessTypeRow($row);

        $this->request->merge($row);
      // dd( $row);

        return $row;
    }
    protected function setCategoriesToRow(array $row): array
    {

        $row['cat_id'] = 0;

        if (! empty($row['category'])) {
            $row['category'] = trim($row['category']);

            $category = $this->categories->firstWhere('keyword', $row['category']);
            if ($category) {
                $categoryId = $category['cat_id'];
            } else {
                if (is_numeric($row['category'])) {
                    $category = Category::query()->find($row['category']);
                } else {
                    $category = Category::query()->where('name', $row['category'])->first();
                }


                if($category == null){

                    $category = new Category;
                    $category->name = $row['category'];
                    $category->save();
                }
                $categoryId = $category ? $category->id : 0;
                $this->categories->push([
                    'keyword' => $row['category'],
                    'cat_id' => $categoryId,
                    'category_ids' => $categoryId,
                ]);
            }


            $row['cat_id'] = $categoryId;
        }

        return $row;
    }
    protected function setProductTypeToRow(array $row): array
    {

        $row['type_id'] = 0;

        if (! empty($row['product_type'])) {
            $row['product_type'] = trim($row['product_type']);

            $product_type = $this->product_types->firstWhere('keyword', $row['product_type']);
            if ($product_type) {
                $product_typeID = $product_type['type_id'];
            } else {
                if (is_numeric($row['product_type'])) {
                    $product_type = ProductType::query()->find($row['product_type']);
                } else {
                    $product_type = ProductType::query()->where('name', $row['product_type'])->first();
                }


                if($product_type == null){

                    $product_type = new ProductType;
                    $product_type->name = $row['product_type'];
                    $product_type->save();
                }
                $product_typeID = $product_type ? $product_type->id : 0;
                $this->product_types->push([
                    'keyword' => $row['product_type'],
                    'type_id' => $product_typeID,
                ]);
            }


            $row['type_id'] = $product_typeID;
        }

        return $row;
    }
     protected function setGenericToRow(array $row): array
    {

        $row['generic_id'] = 0;

        if (! empty($row['generic'])) {
            $row['generic'] = trim($row['generic']);

            $generic = $this->generics->firstWhere('keyword', $row['generic']);
            if ($generic) {
                $genericID = $generic['generic_id'];
            } else {
                if (is_numeric($row['generic'])) {
                    $generic = Generic::query()->find($row['generic']);
                } else {
                    $generic = Generic::query()->where('name', $row['generic'])->first();
                }


                if($generic == null){

                    $generic = new Generic;
                    $generic->name = $row['generic'];
                    $generic->save();
                }
                $genericID = $generic ? $generic->id : 0;
                $this->generics->push([
                    'keyword' => $row['generic'],
                    'generic_id' => $genericID,
                ]);
            }


            $row['generic_id'] = $genericID;
        }

        return $row;
    }

    protected function setTaxToRow(array $row): array
    {
        $row['tax_id'] = 0;
        if (! empty($row['tax'])) {
            $row['tax'] = trim($row['tax']);

            $tax = $this->taxes->firstWhere('keyword', $row['tax']);
            if ($tax) {
                $tax_id = $tax['tax_id'];
            } else {
                if (is_numeric($row['tax'])) {
                    $tax = Tax::query()->find($row['tax']);
                } else {
                    $tax = Tax::query()->where('name', $row['tax'])->first();
                }


                if($tax == null){
                    $tax = new Category;
                    $tax->name = $row['tax'];
                    $tax->save();
                }
                $tax_id = $tax ? $tax->id : 0;
                $this->categories->push([
                    'keyword' => $row['tax'],
                    'tax_id' => $tax_id,
                ]);
            }


            $row['tax_id'] = $tax_id;
        }
        return $row;
    }
    
    protected function setBrandToRow(array $row): array
    {
        $row['brand_id'] = 0;

        if (! empty($row['brand'])) {
            $row['brand'] = trim($row['brand']);

            $brand = $this->brands->firstWhere('keyword', $row['brand']);
            if ($brand) {
                $brandId = $brand['brand_id'];
            } else {
                if (is_numeric($row['brand'])) {
                    $brand = Brand::query()->find($row['brand']);
                } else {
                    $brand = Brand::query()->where('name', $row['brand'])->first();
                }
                 if($brand == null){
                    // $slug = esc(str_slug($row['brand']));
                    $brand = new Brand;
                    $brand->name = esc($row['brand']);
                    // $brand->slug = $slug;
                    $brand->save();
                }
                $brandId = $brand ? $brand->id : 0;
                $this->brands->push([
                    'keyword' => $row['brand'],
                    'brand_id' => $brandId,
                ]);
            }

            $row['brand_id'] = $brandId;
        }

        return $row;
    }
     protected function setManufactureToRow(array $row): array
    {
        $row['manufacture_id'] = 0;

        if (! empty($row['manufacture'])) {
            $row['manufacture'] = trim($row['manufacture']);

            $manufacture = $this->manufactures->firstWhere('keyword', $row['manufacture']);
            if ($manufacture) {
                $manufacture_id = $manufacture['manufacture_id'];
            } else {
                if (is_numeric($row['manufacture'])) {
                    $manufacture = Brand::query()->find($row['manufacture']);
                } else {
                    $manufacture = Brand::query()->where('name', $row['manufacture'])->first();
                }
                 if($manufacture == null){

                    $manufacture = new Manufature;
                    $manufacture->name = esc($row['manufacture']);

                    $manufacture->save();
                }
                $manufacture_id = $manufacture ? $manufacture->id : 0;
                $this->manufactures->push([
                    'keyword' => $row['manufacture'],
                    'manufacture_id' => $manufacture_id,
                ]);
            }

            $row['manufacture_id'] = $manufacture_id;
        }

        return $row;
    }
    protected function setUnitToRow(array $row): array
    {
        $row['unit_id'] = 0;

        if (! empty($row['unit'])) {
            //dd($row['unit']);
            $row['unit'] = trim($row['unit']);

            $unit = $this->units->firstWhere('keyword', $row['unit']);
            if ($unit) {
                $unitId = $unit['unit_id'];
            } else {
                if (is_numeric($row['unit'])) {
                    $unit = Unit::query()->find($row['unit']);
                } else {
                    $unit = Unit::query()->where('name', $row['unit'])->first();
                }
                 if($unit == null){

                    $unit = new Unit;
                    $unit->name = esc($row['unit']);

                    $unit->save();
                }
                $unitId = $unit ? $unit->id : 0;
                $this->brands->push([
                    'keyword' => $row['unit'],
                    'unit_id' => $unitId,
                ]);
            }

            $row['unit_id'] = $unitId;
        }

        return $row;
    }
    protected function setBusinessTypeRow(array $row): array
    {
        $row['b_type_id'] = 0;

        if (! empty($row['business_type'])) {
            $row['business_type'] = Str::lower(trim($row['business_type']));
            // if( $row['business_type'] == )
            $key = array_search($row['business_type'],$this->b_types);
            if( $key) {
                $row['b_type_id'] = $key;
            }

           
        }

        return $row;
    }
    public function mapLocalization(array $row): array
    {

         //dd($row);
       //$new_row=array();

        $name = Arr::get($row, 'product_name');
        if(empty($name)){
            $name = Arr::get($row, 'name');
        }
        if(empty($name)){
            $name = Arr::get($row, 'particular_item');
        }
        if(empty($name)){
            $name = Arr::get($row, 'item_name');
        }
        $row['name'] =  $name;
        if(empty($row['product_code'])){
           $row['product_code'] = Str::upper(Str::random(7));
        }
        $this->setValues($row, [
            ['key' => 'name', 'type' => 'string'],
            ['key' => 'product_code', 'type' => 'string'],
            ['key' => 'batch_no', 'type' => 'string'],
            ['key' => 'generic', 'type' => 'string'],
            ['key' => 'product_type', 'type' => 'string'],
            ['key' => 'tax', 'type' => 'string'],
            ['key' => 'unit', 'type' => 'string'],
            ['key' => 'discount_type', 'type' => 'string'],
            ['key' => 'discount', 'type' => 'number'],
        ]);
        $discount_type = Arr::get($row, 'discount_type');
        if($discount_type && $discount_type == 'percent') {
            $row['is_discount'] = 1;
        }else{
             $row['is_discount'] = 0;
        }
       
        return $row;
    }
    protected function setValues(array &$row, array $attributes = []): self
    {
        foreach ($attributes as $attribute) {
            $this->setValue(
                $row,
                Arr::get($attribute, 'key'),
                Arr::get($attribute, 'type', 'array'),
                Arr::get($attribute, 'default'),
                Arr::get($attribute, 'from')
            );
        }

        return $this;
    }
    protected function setValue(array &$row, string $key, string $type = 'array', $default = null, $from = null): self
    {
        $value = Arr::get($row, $from ?: $key, $default);

        switch ($type) {
            case 'array':
                $value = $value ? explode(',', $value) : [];

                break;
            case 'bool':
                if (Str::lower($value) == 'false' || $value == '0' || Str::lower($value) == 'no') {
                    $value = false;
                }
                $value = (bool)$value;

                break;
            case 'number':
                 $value = $value ? $value : 0;

                break;
            case 'datetime':
                if ($value) {
                    if (in_array(gettype($value), ['integer', 'double'])) {
                        $value = $this->transformDate($value);
                    } else {
                        $value = $this->getDate($value);
                    }
                }

                break;
        }

        Arr::set($row, $key, $value);



        return $this;
    }
    public function rules(): array
    {
       return [
            'name' => 'required|string|max:250',

        ];
    }
    public function chunkSize(): int
    {
        return 100;
    }
}
