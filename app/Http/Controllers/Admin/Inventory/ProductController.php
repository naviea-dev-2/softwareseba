<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Category;
use App\Models\Inventory\Generic;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductPrice;
use App\Models\Inventory\ProductType;
use App\Models\Inventory\Tax;
use App\Models\Inventory\ProductVariant;
use App\Models\Inventory\ProductVariation;
use App\Models\Inventory\ProductVariationItem;
use App\Models\Inventory\ProductWithAttributSet;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
       // $data['categories']=Category::orderBy('id','DESC')->get();
        //$data['brands']=Brand::orderBy('id','DESC')->get();
       // $data['products']=$products=Product::where('is_variant',0)->orderBy('id','DESC')->get();

        //$data['taxes'] = Tax::orderBy('id','DESC')->get();
        //dd($products[0]->variant('color'));
        return view ('admin.Inventory.product.manage');
        // return view ('admin.Inventory.product.manage',$data);
    }
    function ajaxProduct(Request $request){
         $columns = array(
            0 => 'products.id',
            1 => 'products.image',
            2 => 'products.product_name',
            3 => 'products.product_code',
            4 => 'categories.name',
            5 => 'brands.name',
            6 => 'products.business_id',
            7 => 'options',
        );
        $totalData = Product::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $products = Product::leftJoin('categories','categories.id','products.category_id')
                            ->leftJoin('brands','brands.id','products.brand_id')
                            ->where('products.is_variant',0);
        if(!empty($search))
        {
            $products = $products->where("products.product_name","LIKE","%{$search}%")
            ->orWhere("products.product_code","LIKE","%{$search}%")
            ->orWhere("categories.name","LIKE","%{$search}%")
            ->orWhere("brands.name","LIKE","%{$search}%");
        }
        $products = $products->select('products.*','categories.name as c_name','brands.name as b_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        $data = array();
        if(!empty($products))
        {
             $i = $start == 0 ? 1 : $start+1;
         
            foreach($products as $product)
            {
                $nestedData['id'] = $i++;

                $nestedData['thumbnail'] = '<img src="'.$product->image_show.'" style="height:50px;width:50px;">';
                $nestedData['name'] = $product->product_name;
                $nestedData['code'] = $product->product_code;
                $nestedData['category_id'] = $product->c_name;
                $nestedData['brand_id'] = $product->b_name;
                $nestedData['business_id'] = $product->business?->business_name;
                // $nestedData['business_type'] = check_business_type($product->business_type_id);
                $nestedData['options'] = '';
               
                $nestedData['options'] = '<a class="btn btn-primary data_edit me-2" href="'.route('admin.product.edit',$product->id).'"><i class="bx bx-edit"></i></a>';
               
                
                 $nestedData['options'] .= '<a href="#" data-id="'.$product->id.'" class="del_data btn btn-danger"><i class="bx bx-trash"></i></a>';
                
                $data[] = $nestedData;

            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return json_encode($json_data);
    }
    function autoSearch(Request $request){
        $search = $request->input('value');
        $products = Product::where('is_variant',0)->where(function($query) use($search){
            $query->where('product_name','like','%'.$search.'%')
            ->orWhere('product_code','like','%'.$search.'%');
        })
        ->orderBy("id",'DESC')->take(10)->get();

        //dd($products);

        $data = view('admin.Inventory.purchase.auto-seach-product',compact('products'))->render();
       // dd($data);
        if($products->isEmpty()){
            return response()->json(array('status'=> 'error','message'=> 'search is empty','data'=>$data));
        }

        return response()->json(array('status'=> 'success','products'=>$products,'search'=>$request->all(),'data'=>$data));
    }
    function select2ProductColor(Request $request){
        $p_colors = ProductVariant::leftJoin('colors','colors.id','product_variants.relation_id')
        ->where("product_id", $request->p_id)->where('relation_with','color')->where("colors.name", "LIKE", "%$request->value%")->get();
        foreach ($p_colors as $p_color) {
            $data[] = ['id' => $p_color->relation_id, 'text' => $p_color->color->name];
        }
        return json_encode($data);
    }
    function select2ProductSize(Request $request){
        $p_colors = ProductVariant::leftJoin('sizes','sizes.id','product_variants.relation_id')->where("product_id", "$request->p_id")->where('relation_with','size')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($p_colors as $p_color) {
            $data[] = ['id' => $p_color->relation_id, 'text' => $p_color->size->name];
        }
        return json_encode($data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
        // $data['categories']=Category::orderBy('id','DESC')->get();
        // $data['brands']=Brand::orderBy('id','DESC')->get();
        $data['taxes'] = Tax::orderBy('id','DESC')->get();
        // if(auth()->user()->business->business_type_id == 5){
        //     $data['generics']=Generic::orderBy('id','DESC')->get();
        //     $data['product_types'] = ProductType::orderBy('id','DESC')->get();
        // }

        //dd($products[0]->variant('color'));
        return view ('admin.Inventory.product.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
      
        $this->validate($request,[
            'product_name'=>[
                'required',
                Rule::unique('products')->where(function ($query) use($request){
                    return $query->where('business_id', $request->business_id);
                }),
            ],
            'business_id'=>'required',
            'product_image'=>'image|mimes:jpeg,png,jpg,webp',
            // 'brand'=>'required',
            // 'product_code'=>'required',
            // 'product_discount'=>'numeric',
            // 'color'=>['required','array'],
            // 'size'=>['required','array'],
            // 'unit'=>'required',
            // 'tax'=>'required',
        ]);


        try{
            DB::beginTransaction();
            if($request->id==0){
                $data=new Product();
                $file=$request->file('product_image');
                if($file){
                    $filename=date('YmdHi')."_product".$file->getClientOriginalName();
                    $file->move(public_path('upload/products'),$filename);
                    $data->image=$filename;
                }
            }
            else{
                $data=Product::find($request->id);
                $file=$request->file('product_image');
                if($file){
                    @unlink(public_path('upload/products/'.$data->image));
                    $filename=date('YmdHi')."_product".$file->getClientOriginalName();
                    $file->move(public_path('upload/products'),$filename);
                    $data->image=$filename;
                }
            }
            $data->product_name=$request->product_name;

            $data->product_code=$request->product_code ?? '';
            $data->manufacture_id=$request->manufacture ?? 0;
            $data->batch_no=$request->batch_no ?? '';
            $data->imei_1=$request->imei_1 ?? '';
            $data->imei_2=$request->imei_2 ?? '';
            
            $data->type_id=$request->p_type ?? 0;
            $data->generic_id=$request->generic ?? 0;
            $data->category_id=$request->category ?? 0;
            $data->brand_id=$request->brand ?? 0;
            $data->exipre_date=$request->exipre_date ?? date('now');
            $data->manufacture_date=$request->manufacture_date ?? date('now');
            $data->discount_type=$request->discount_type;
            $data->discount=$request->product_discount ?? 0;
            $data->is_discount=$request->is_discount ?? 0;
            // $data->is_stock=$request->is_stock ?? 0;
            // $data->stock_status_id=$request->stock_status_id ?? 0;
            // $data->stock_qty=$request->stock_qty ?? 0;
            // $data->length=$request->length ?? 0;
            // $data->width=$request->width ?? 0;
            // $data->height=$request->height ?? 0;
            // $data->weight=$request->weight ?? 0;
            $data->tax_id=$request->tax ?? 0;
            $data->unit_id=$request->unit ?? 0;
            $data->business_id=$request->business_id ?? 0;
            $data->sale_price=$request->sale_price ?? 0;
            $data->purchase_price=$request->purchase_price ?? 0;

            $data->save();
            $product=$data;
            // $product_stock = new ProductStock;
            // $product_stock->discount_type = $request->discount_type ?? 0;
            // $product_stock->discount = $request->product_discount ?? 0;
            // $product_stock->purchase_price = $request->purchase_price ?? 0;
            // $product_stock->sale_price =$request->sale_price ?? 0;
            // $product_stock->product_id =$product->id;
            // $product_stock->business_id = auth()->user()->business->id;
            // $product_stock->save();
            if($request->attribute){
                $attribute_sets = array_unique($request->attribute);
                foreach($attribute_sets as $attribute_set){
                    $p_attribute_set = ProductWithAttributSet::where('product_id',$product->id)->where('attribute_set_id',$attribute_set)->first();
                    if($p_attribute_set == null){
                        $p_attribute_set = new ProductWithAttributSet;
                    }
                   $p_attribute_set->product_id = $product->id;
                   $p_attribute_set->attribute_set_id = $attribute_set;
                   $p_attribute_set->save();
                   
                }
                //$product->productAttributeSets()->sync($attribute_sets);
                $r_attributes = $request->attribute_value;
                $attributes=[];
                $fr_attributes = [];
                foreach ($r_attributes as $key => $value) {
                    $attributes[$key] =array_unique(array_merge($attributes[$key] ?? [] ,$value));
                    $fr_attributes = array_merge( $fr_attributes,$value);
                }
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
                    $new_product->brand_id = $product->brand_id;
                    $new_product->category_id = $product->category_id;
                    $new_product->unit_id = $product->unit_id;
                    $new_product->tax_id = $product->tax_id;
                    $new_product->purchase_price = $product->purchase_price;
                    $new_product->sale_price = $product->sale_price;
                    $new_product->discount = $product->discount;
                    $new_product->discount_type = $product->discount_type;
                    $new_product->is_discount = $product->is_discount;
                    // $new_product->is_stock = $product->is_stock;
                    // $new_product->stock_status_id = $product->stock_status_id;
                    // $new_product->stock_qty = $product->stock_qty;
                    // $new_product->length = $product->length;
                    // $new_product->width = $product->width;
                    // $new_product->height = $product->height;
                    // $new_product->weight = $product->weight;
                    $new_product->is_variant =1;
                    $new_product->save();

                    // $product_stock = new ProductStock;
                    // $product_stock->discount_type = $product->discount_type ?? 0;
                    // $product_stock->discount =$product->discount ?? 0;
                    // $product_stock->purchase_price = $product->purchase_price ?? 0;
                    // $product_stock->sale_price =$product->sale_price ?? 0;
                    // $product_stock->product_id =$new_product->id;
                    // $product_stock->business_id = auth()->user()->business->id;
                    // $product_stock->save();

                    $product_variation = new ProductVariation;
                    $product_variation->configurable_product_id = $product->id;
                    $product_variation->product_id = $new_product->id;
                    $product_variation->is_default =  $is_default;
                    $product_variation->business_id = $request->business_id;
                    $product_variation->save();
                    $is_default = 0;


                    foreach ($res_variant as $r_v) {
                        $p_v_item = new ProductVariationItem;
                        $p_v_item->variation_id = $product_variation->id;
                        $p_v_item->attribute_id = $r_v;
                        $p_v_item->business_id = $request->business_id;;
                        $p_v_item->save();
                    }


                }
            }
            DB::commit();
            $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

            return redirect()->route('admin.product.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
           // dd($e->getMessage());
             $notification=array(
                'message'=>"Save Failed",
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,$id)
    {
        
        $data['taxes'] = Tax::orderBy('id','DESC')->get();
        $data['product']=$product=Product::find($id);
        //dd($product->product_stock);
        // dd($product->product_stock);
        //  dd($product->atttribute_sets);
        //dd($product->productAttributeSets);
        return view('admin.Inventory.product.edit',$data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
       
        $id = $request->id;

        $this->validate($request,[
            'product_name'=>[
                'required',
                Rule::unique('products')->where(function ($query) use ($request,$id) {
                    return $query->where('id', '!=', $id)
                        ->where('is_variant',0)
                        ->where('business_id', $request->business_id);
                }),
            ],
            'business_id'=>'required',
            'product_image'=>'image|mimes:jpeg,png,jpg,webp',
            // 'category'=>'required',
            // 'brand'=>'required',
            // 'product_code'=>'required',
            // 'product_discount'=>'numeric',

            // 'unit'=>'required',
            // 'tax'=>'required',
        ]);


        try{
            DB::beginTransaction();
            if($request->id==0){
                $data=new Product();
                $file=$request->file('product_image');
                if($file){
                    $filename=date('YmdHi')."_product".$file->getClientOriginalName();
                    $file->move(public_path('upload/products'),$filename);
                    $data->image=$filename;
                }
            }
            else{
                $data=Product::find($request->id);
                $file=$request->file('product_image');
                if($file){
                    @unlink(public_path('upload/products/'.$data->image));
                    $filename=date('YmdHi')."_product".$file->getClientOriginalName();
                    $file->move(public_path('upload/products'),$filename);
                    $data->image=$filename;
                }
            }
            $data->product_name=$request->product_name;
            $data->manufacture_id=$request->manufacture ?? 0;
            $data->batch_no=$request->batch_no ?? '';
            $data->type_id=$request->p_type ?? 0;
            $data->generic_id=$request->generic ?? 0;
            $data->product_code=$request->product_code ?? '';
            $data->category_id=$request->category ?? 0;
            $data->brand_id=$request->brand ?? 0;
            $data->exipre_date=$request->exipre_date ?? date('now');
            $data->manufacture_date=$request->manufacture_date ?? date('now');
            $data->discount_type=$request->discount_type;
            $data->discount=$request->product_discount ?? 0;
            $data->tax_id=$request->tax ?? 0;
            $data->unit_id=$request->unit ?? 0;
            $data->sale_price=$request->sale_price ?? 0;
            $data->purchase_price=$request->purchase_price ?? 0;

            $data->business_id=$request->business_id ?? 0;
            $data->save();

            $product = $data;

            // $product_stock =  ProductStock::where('product_id',$product->id)->where('business_id', $request->business_type)->first();
            
            // if($product_stock == null){
            //     $product_stock = new ProductStock();
            // }
            // $product_stock->discount_type = $request->discount_type;
            // $product_stock->discount = $request->product_discount ?? 0;
            // $product_stock->purchase_price = $request->purchase_price ?? 0;
            // $product_stock->sale_price =$request->sale_price ?? 0;
            // $product_stock->product_id =$product->id;
            // $product_stock->business_id = auth()->user()->business->id ?? 0;
            // $product_stock->save();

            //dd( $product_stock);
            // $product->productAttributeSets()->sync($attribute_sets);

            if($request->attribute){
                if($product->atttribute_sets->count() == 0){

                    $attribute_sets = array_unique($request->attribute);
                    // dd( $product->productAttributeSets());
                    // $product->productAttributeSets()->sync($attribute_sets);
                    // dd( $attribute_sets)
                    foreach($attribute_sets as $attribute_set){
                        $product_with_set = $product->atttribute_sets->where($attribute_set)->first();
                        if($product_with_set == null){
                            $product_with_set = new ProductWithAttributSet;
                        }
                        $product_with_set->product_id = $product->id;
                        $product_with_set->attribute_set_id = $attribute_set;
                        //$product_with_set->business_id = auth()->user()->business->id;
                        $product_with_set->save();
                    }

                    $r_attributes = $request->attribute_value;
                    $attributes=[];
                    $fr_attributes = [];
                    foreach ($r_attributes as $key => $value) {
                        $attributes[$key] =array_unique(array_merge($attributes[$key] ?? [] ,$value));
                        $fr_attributes = array_merge( $fr_attributes,$value);
                    }
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
                        $new_product->brand_id = $product->brand_id;
                        $new_product->category_id = $product->category_id;
                        $new_product->unit_id = $product->unit_id;
                        $new_product->tax_id = $product->tax_id;
                        $new_product->purchase_price = $product->purchase_price;
                        $new_product->sale_price = $product->sale_price;
                        $new_product->discount = $product->discount;
                        $new_product->discount_type = $product->discount_type;
                        $new_product->is_discount = $product->is_discount;
                        // $new_product->is_stock = $product->is_stock;
                        // $new_product->stock_status_id = $product->stock_status_id;
                        // $new_product->stock_qty = $product->stock_qty;
                        // $new_product->length = $product->length;
                        // $new_product->width = $product->width;
                        // $new_product->height = $product->height;
                        // $new_product->weight = $product->weight;
                        $new_product->business_id = $request->business_id ?? 0;
                        $new_product->is_variant =1;
                        $new_product->save();
                        // $product_stock = new ProductStock;
                        // $product_stock->discount_type = $product->discount_type;
                        // $product_stock->discount = $product->discount ?? 0;
                        // $product_stock->purchase_price = $product->purchase_price ?? 0;
                        // $product_stock->sale_price =$product->sale_price ?? 0;
                        // $product_stock->product_id =$new_product->id;
                        // $product_stock->business_id = auth()->user()->business->id ?? 0;
                        // $product_stock->save();

                        $product_variation = new ProductVariation;
                        $product_variation->configurable_product_id = $product->id;
                        $product_variation->product_id = $new_product->id;
                        $product_variation->is_default =  $is_default;
                        $product_variation->business_id = $request->business_id ?? 0;
                        $product_variation->save();
                        $is_default = 0;


                        foreach ($res_variant as $r_v) {
                            $p_v_item = new ProductVariationItem;
                            $p_v_item->variation_id = $product_variation->id;
                            $p_v_item->attribute_id = $r_v;
                            $p_v_item->business_id =$request->business_id ?? 0;
                            $p_v_item->save();
                        }


                    }
                }
            }


            DB::commit();
            $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

            return redirect()->route('admin.product.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
             $notification=array(
                'message'=>"Save Failed",
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        
        try{
            DB::beginTransaction();
            $product = Product::find($id);
           
            @unlink(public_path('upload/products/'.$product->image));
            $product->delete();
           // dd($product);
            DB::commit();
            $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
            );

            return redirect()->route('admin.product.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            if($e->getCode() == '23000'){
                $notification=array(
                    'message'=>"This data can not be delete",
                    'alert-type'=>'success'
                );
            
                return redirect()->route('admin.product.index')->with($notification);
            }else{
                $notification=array(
                    'message'=>$e->getMessage(),
                    'alert-type'=>'success'
                );
            
                return redirect()->route('admin.product.index')->with($notification);
            }
        }
        catch(\Error $e){
            DB::rollBack();
            $notification=array(
                'message'=>$e->getMessage(),
                'alert-type'=>'success'
            );
            return redirect()->route('admin.product.index')->with($notification);
        }
    }
    function select2ProductbyCat(Request $request){
         //return json_encode($request->all());
         $products = Product::select('id', 'product_name as name')->where('category_id',$request->cat_id)->where("product_name", "LIKE", "%$request->value%")->get();
        foreach ($products as $product) {
            $data[] = ['id' => $product->id, 'text' => $product->name];
        }
        if($products->count() == 0){
            $data[] = ['id' => '', 'text' => "Search not found"];
        }
        return json_encode($data);
    }
    function productDetailsbyId(Request $request){

        $data['product']=$product = Product::where('id',$request->id)->first();

        //return $request->id;
        $data['row_no'] =$request->row_id;
       // if($request->is_sale == 1){
            $data['is_sale'] = $request->is_sale;
        //}
        if(isset($request->old) && $request->old != 0){
             $data['qty'] =$request->qty;
            $data['item_id'] = $request->old;
            $data_view= view ('admin.Inventory.purchase.ajax-product-edit-data',$data)->render();
        }else{
            $data['qty'] =$request->qty;
            $data_view= view ('admin.Inventory.purchase.ajax-product-data',$data)->render();
        }
        if($product->unit_id > 0){
            $unit_name = $product->unit?->name;
            $unit_id = $product->unit_id;
        }else{
            $unit_name = "";
            $unit_id = 0;
        }
        return response()->json(['data_view'=>$data_view,'p_data'=>$product,'unit_name'=>$unit_name,'unit_id'=>$unit_id]);
    }
}
