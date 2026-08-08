<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Attribute;
use App\Models\Inventory\AttributeSet;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductVariation;
use App\Models\Inventory\ProductVariationItem;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Inventory\ProductWithAttributSet;
class ProductVariationController extends Controller
{
    function addNewProductAttribute(Request $request){

        $attribute_sets = AttributeSet::orderBy('order','asc')->get();
        $product_id = $request->input('id');
        //dd($attribute_sets);
        return view("Inventory.product.product_attribute_sets",compact("attribute_sets","product_id"));
    }
    function getAttributeValue(Request $request){
        $attribute_set = AttributeSet::find( $request->input("id") );
        if($attribute_set){
            $out = "";
            foreach($attribute_set->attributes as $attribute){
                 $check = "";
                if($attribute->is_default == 1){
                    $check = "selected=selected";
                }
                $out .='<option '.$check.' value="'. $attribute->id .'">';
                $out .=$attribute->title;
                $out .='</option>';
            }
            return response()->json(['status'=>'yes','data'=>$out,'test'=>$attribute_set->attributes,'set_name'=>'attribute_value['.$request->input("id").'][]']);
        }
        return response()->json(['status'=>'no']);
    }
    function repeatFunction($attribute_sets,$attributes,$all_attributes){
        foreach($attribute_sets as $attribute_set){
            foreach($all_attributes[$attribute_set] as $attribute){

            }
        }
    }
    function editProdeuctAttribute(Request $request){
        // dd($request);
         try{
            DB::beginTransaction();
            $product_id = $request->input('RecordId');
            $product= Product::find($product_id);
            // dd($product->productAttributeSets);
            $attribute_sets = array_unique($request->attribute);
            // $product->productAttributeSets()->sync($attribute_sets);
            foreach($attribute_sets as $attribute_set){
                $p_attribute_set = ProductWithAttributSet::where('product_id',$product->id)->where('attribute_set_id',$attribute_set)->first();
                if($p_attribute_set == null){
                    $p_attribute_set = new ProductWithAttributSet;
                }
               $p_attribute_set->product_id = $product->id;
               $p_attribute_set->attribute_set_id = $attribute_set;
               $p_attribute_set->save();

            }

            $attributes = Attribute::whereIn('attribute_set_id', $attribute_sets)
                ->pluck('id')
                ->all();
            $attributes = $this->getSelectedAttributes($product, $attributes);
            ProductVariation::correctVariationItems($product->id, $attributes);
            DB::commit();
            $res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
            return response()->json($res);
        }catch(\Exception $e){
            DB::rollBack();
           $res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
            return response()->json($res);
        }
    }
    protected function getSelectedAttributes(Product $product, array $attributes): array
    {

        $attributeSets = $product->productAttributeSets()
            ->select('attribute_set_id')
            ->pluck('attribute_set_id')
            ->toArray();

        $allRelatedAttributeBySet = Attribute::query()
            ->whereIn('attribute_set_id', $attributeSets)
            ->pluck('id')
            ->all();

        $newAttributes = [];

        foreach ($attributes as $item) {
            if (in_array($item, $allRelatedAttributeBySet)) {
                $newAttributes[] = $item;
            }
        }

        return $newAttributes;
    }
    function variationChangeDefault(Request $request,$id){
        try{
            DB::beginTransaction();
            $product_variation = ProductVariation::find($id);
            foreach($product_variation->configurableProduct->variations as $variation){
                $variation->is_default = 0;
                $variation->save();
            }
            $product_variation->is_default = 1;
            $product_variation->save();
            DB::commit();
            $res['msgType'] = 'success';
            $res['msg'] = __('Data Updated Successfully');
            return response()->json($res);
        }catch(\Exception $e){
            DB::rollBack();
           $res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
            return response()->json($res);
        }
    }
    function deleteVariation(Request $request){
        $res = array();

		$id = $request->id;

		if($id != ''){
			$del_variant =  ProductVariation::where('id', $id)->first();
            if($del_variant->product){
                //dd($del_variant->configurableProduct->variations->count() == 1);
                if($del_variant->configurableProduct->variations->count() == 1){
                   $del_variant->configurableProduct->productAttributeSets()->sync([]);
                }
                $del_variant->product->delete();
            }
            $del_variant->variationItems->each->delete();
            $del_variant->delete();
			if($del_variant){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}

		return response()->json($res);
    }
    function deleteAllVariation(Request $request){
        $res = array();

		$ids = $request->ids;

		try{
            DB::beginTransaction();
			$variations = ProductVariation::whereIn('id', $ids)->get();
            foreach($variations as $variation){
                $variation->variationItems->each->delete();
                $variation->delete();

            }
            $product = Product::find($request->id);
            if($product && $product->variations->count() == 0){
                $product->productAttributeSets()->detach();
            }

            DB::commit();
            $res['msgType'] = 'success';
            $res['msg'] = __('Data Removed Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            $res['msgType'] = 'error';
            $res['extra']=$e->getMessage();
            $res['msg'] = __('Data remove failed');

		}

		return response()->json($res);
    }
    function saveVariationsData(Request $request){
        try{
            DB::beginTransaction();
            $product_id = $request->input('RecordId');
            $product= Product::find($product_id);

            $attribute_sets = array_unique($request->attribute);
            // dd($attribute_sets);
            foreach($attribute_sets as $attribute_set){
                $p_attribute_set = ProductWithAttributSet::where('product_id',$product->id)->where('attribute_set_id',$attribute_set)->first();
                if($p_attribute_set == null){
                    $p_attribute_set = new ProductWithAttributSet;
                }
               $p_attribute_set->product_id = $product->id;
               $p_attribute_set->attribute_set_id = $attribute_set;
               $p_attribute_set->save();

            }
            // $product->productAttributeSets()->sync($attribute_sets);
            $attributes=[];
            $fr_attributes = [];
            // foreach($product->variationProductAttributes as $s_attribute){
            //     $attributes[$s_attribute->attribute_set_id][] = $s_attribute->attribute_id;

            //     array_push( $fr_attributes,$s_attribute->attribute_id);
            // }
            $r_attributes = $request->attribute_value;

            foreach ($r_attributes as $key => $value) {
                $attributes[$key] =array_unique(array_merge($attributes[$key] ?? [] ,$value));
                 $fr_attributes = array_merge( $fr_attributes,$value);
            }
         //return array_values($fr_attributes);
            //$product->productAttributeSets()->sync($attribute_sets);

           // $attributes = $request->attribute_value;
             $res_variants = [[]];

            foreach ($attributes as $key => $value) {
                $tmp = [];
                foreach ($res_variants as $item) {
                    foreach ($value as $valueItem) {
                        $tmp[] = array_merge($item, [$key => $valueItem]);
                    }
                }
                $res_variants = $tmp;
            }
            $is_default = 1;

            foreach($res_variants as $res_variant){
                $new_product = new Product;
                $new_product->product_name = $product->product_name;
                $new_product->product_code = $product->product_code;
                $new_product->brand_id = $product->brand_id;
                $new_product->category_id = $product->category_id;
                $new_product->unit_id = $product->unit_id;
                $new_product->tax_id = $product->tax_id;
                // $new_product->purchase_price = $product->purchase_price;
                // $new_product->sale_price = $product->sale_price;
                // $new_product->discount = $product->discount;
                // $new_product->discount_type = $product->discount_type;
                // $new_product->is_discount = $product->is_discount;
                // $new_product->is_stock = $product->is_stock;
                // $new_product->stock_status_id = $product->stock_status_id;
                // $new_product->stock_qty = $product->stock_qty;
                // $new_product->length = $product->length;
                // $new_product->width = $product->width;
                // $new_product->height = $product->height;
                // $new_product->weight = $product->weight;
                $new_product->business_id = auth()->user()->business->id;
                $new_product->is_variant =1;
                $new_product->save();
                $product_stock = new ProductStock;
                $product_stock->discount_type = $product->discount_type;
                $product_stock->discount = $product->discount ?? 0;
                $product_stock->purchase_price = $product->purchase_price ?? 0;
                $product_stock->sale_price =$product->sale_price ?? 0;
                $product_stock->product_id =$new_product->id;
                $product_stock->business_id = auth()->user()->business->id;
                $product_stock->save();

                $product_variation = new ProductVariation;
                $product_variation->configurable_product_id = $product->id;
                $product_variation->product_id = $new_product->id;
                $product_variation->is_default =  $is_default;
                $product_variation->business_id = auth()->user()->business->id;
                $product_variation->save();
                $is_default = 0;


                foreach ($res_variant as $r_v) {
                    $p_v_item = new ProductVariationItem;
                    $p_v_item->variation_id = $product_variation->id;
                    $p_v_item->attribute_id = $r_v;
                    $p_v_item->business_id = auth()->user()->business->id;
                    $p_v_item->save();
                }


            }
            DB::commit();
            return response()->json(['status'=>"ok"]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['status'=>"no",'error_m'=> $e->getMessage()]);
            //return $e->getMessage();
        }



    }
    function saveNewVariationsData(Request $request){

        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(),
               [
                'cost_price'=>'required',
                'sale_price'=>'required',
                'sku'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status'=>"no",'error_m_ex'=> $validator->errors(),'error_m'=>'Data filed is blank']);
                // return back()->withErrors($validator->errors())->with("cus_errors",$validator->errors()->all())->withInput($request->all());
            }

            $attribute_sets = array_unique(array_keys($request->attribute));
           // return empty($request->stock_qty);
            //return empty($request->stock_qty) ? 0 : $request->stock_qty;
            $result = ProductVariation::getVariationByAttributesOrCreate($request->RecordId, $request->attribute);
            if (! $result['created']) {
                $res['status'] = 'no';
                $res['error_m'] = __('Variation Already Exists');
                return response()->json($res);
            }
            $this->postSaveAllVersions(
                [$result['variation']->id => $request->input()],
                $request->RecordId);

            // $product= Product::find($request->RecordId);
            // if($product){
            //     $new_product = new Product;
            //     $new_product->title = $product->title;
            //     $new_product->cost_price = $request->cost_price;
            //     $new_product->sale_price = $request->sale_price;
            //     $new_product->old_price = $request->old_price;
            //     $new_product->start_date = $request->start_date;
            //     $new_product->end_date = $product->end_date;
            //     $new_product->is_discount = $product->is_discount;
            //     $new_product->is_stock = $request->is_stock;
            //     $new_product->stock_status_id = $request->stock_status_id;
            //     $new_product->stock_qty =  empty($request->stock_qty) ? 0 : $request->stock_qty;
            //     $new_product->u_stock_qty = empty($request->stock_qty) ? 0 : $request->stock_qty;
            //     $new_product->length = $request->length;
            //     $new_product->width = $request->width;
            //     $new_product->height = $request->height;
            //     $new_product->actual_weight = $request->actual_weight;
            //     $new_product->is_variant = 1;
            //     $new_product->save();

            //     $product_variation = new ProductVariation;
            //     $product_variation->configurable_product_id = $product->id;
            //     $product_variation->product_id = $new_product->id;
            //     $product_variation->is_default = 0;
            //     $product_variation->save();

            //     $attribute_sets = array_keys($request->attribute);
            //     $product->productAttributeSets()->sync($attribute_sets);
            //     foreach($request->attribute as $attribute_id){
            //         $p_v_item = new ProductVariationItem;
            //         $p_v_item->variation_id = $product_variation->id;
            //         $p_v_item->attribute_id = $attribute_id;
            //         $p_v_item->save();
            //     }
            //     // $product_with_attribute_set = new ProductWithAttributSet;
            //     // $product_with_attribute_set->attribute_set_id = $attribute_set->id;
            //     // $product_with_attribute_set->product_id  = $product->id;
            //     // $product_with_attribute_set->save();
            //     // $attribute = $attribute_set->

            // }
            DB::commit();
            return response()->json(['status'=>"ok"]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['status'=>"no",'error_m_ex'=> $e->getMessage(),'error_m'=>'Something went wrong' ,'extra'=>$e->getMessage()]);
            //return $e->getMessage();
        }
    }
    public function getVariationData(Request $request){
        if(!isset($request->id) && $request->id == ''){
            $res['msgType'] = 'error';
			$res['msg'] = __('Data failed');
            return response()->json($res);
        }
        $id = $request->id;
        $variation = ProductVariation::query()->findOrFail($id);
        $product = Product::query()->findOrFail($variation->product_id);
        $productVariationsInfo = ProductVariationItem::getVariationsInfo([$id]);
        $originalProduct = $product;
        $productId = $variation->configurable_product_id;
       //dd($productVariationsInfo);
         if ($productId) {
            $productAttributeSets = AttributeSet::getByProductId($productId);
        } else {
            $productAttributeSets = AttributeSet::getAllWithSelected($productId);
        }
       // dd("hi");
        $html = view('Inventory.product.product-variation-form',
            compact(
                'variation',
                'productAttributeSets',
                'product',
                'productVariationsInfo',
                'originalProduct'
            )
        )->render();

        $res['msgType'] = 'success';
		$res['html'] = $html;
        $res['product'] = $product;
        return response()->json($res);
    }
    function saveEditVariationsData(Request $request){
       // return $request;
        $validator = Validator::make($request->all(), [
			'attribute_sets' => 'nullable|array',
            'attribute_sets.*' => 'required',
		]);
        if ($validator->fails()) {
            $res['msgType'] = 'error';
            $res['msg'] = __('No Attribute Selected!');
            return response()->json($res);
        }
        try{
            DB::beginTransaction();

            $variation = ProductVariation::query()->findOrFail($request->varition_id);
            $addedAttributes = $request->input('attribute_sets', []);


            if (! empty($addedAttributes) && is_array($addedAttributes)) {
                $result = ProductVariation::getVariationByAttributesOrCreate(
                        $variation->configurable_product_id,
                        $addedAttributes
                    );

                if (! $result['created'] && $result['variation']->id !== $variation->id) {
                    $res['msgType'] = 'error';
                    $res['msg'] = __('Variation Already Exists');
                    return response()->json($res);
                }

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

                DB::commit();
                $res['msgType'] = 'success';
                $res['msg'] = __('Variation Edit Successfully');
                return response()->json($res);
            }
            $res['msgType'] = 'error';
            $res['msg'] = __('No Attribute Selected!');
            return response()->json($res);
        }catch (\Exception $e) {
            DB::rollBack();
            $res['msgType'] = 'error';
            $res['extra'] = $e->getMessage();
            $res['msg'] = __('Something is Wrong');
            return response()->json($res);
        }
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
                $productRelatedToVariation->product_name = $product->product_name;
                $productRelatedToVariation->product_code = Arr::get($version, 'sku',$product->product_code);
                $productRelatedToVariation->brand_id = $product->brand_id;
                $productRelatedToVariation->category_id = $product->category_id;
                $productRelatedToVariation->unit_id = $product->unit_id;
                $productRelatedToVariation->tax_id = $product->tax_id;
                $productRelatedToVariation->purchase_price =  Arr::get($version, 'cost_price', $product->purchase_price);
                $productRelatedToVariation->sale_price = Arr::get($version, 'sale_price', $product->sale_price);
                // $productRelatedToVariation->discount = $product->discount;
                // $productRelatedToVariation->discount_type = $product->discount_type;
                // $productRelatedToVariation->is_discount = $product->is_discount;
                // $productRelatedToVariation->is_stock = $product->is_stock;
                // $productRelatedToVariation->stock_status_id = $product->stock_status_id;
                // $productRelatedToVariation->stock_qty = $product->stock_qty;
                // $productRelatedToVariation->length = $product->length;
                // $productRelatedToVariation->width = $product->width;
                // $productRelatedToVariation->height = $product->height;
                // $productRelatedToVariation->weight = $product->weight;
                $productRelatedToVariation->business_id = auth()->user()->business->id;
                $productRelatedToVariation->is_variant =1;


                $productRelatedToVariation->save();
                $product_stock =  ProductStock::where('product_id',$productRelatedToVariation->id)->where('business_id',auth()->user()->business->id)->first();
                if($product_stock == null){
                    $product_stock = new ProductStock();
                }
                $product_stock->discount_type = $product->discount_type;
                $product_stock->discount = $product->discount ?? 0;
                $product_stock->purchase_price = Arr::get($version, 'cost_price', $product->purchase_price);
                $product_stock->sale_price =Arr::get($version, 'sale_price', $product->sale_price);
                $product_stock->product_id =$productRelatedToVariation->id;
                $product_stock->business_id = auth()->user()->business->id;
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
}
