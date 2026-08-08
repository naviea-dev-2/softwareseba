<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Category;
use App\Models\Inventory\Generic;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductInvoice;
use App\Models\Inventory\ProductInvoiceReturn;
use App\Models\Inventory\ProductPrice;
use App\Models\Inventory\ProductPurchase;
use App\Models\Inventory\ProductPurchaseReturn;
use App\Models\Inventory\ProductQuotation;
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
use Picqer\Barcode\Renderers\PngRenderer;
use Picqer\Barcode\Types\TypeCodabar;
use Picqer\Barcode\Types\TypeCode128;
use Picqer\Barcode\Types\TypeCode128A;
use Picqer\Barcode\Types\TypeCode128B;
use Picqer\Barcode\Types\TypeCode128C;
use Picqer\Barcode\Types\TypeCode39;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(can_p('product.index') == false){
            return redirect()->route('dashboard');
        }
        $data['categories']=Category::orderBy('id','DESC')->get();
        $data['brands']=Brand::orderBy('id','DESC')->get();
       // $data['products']=$products=Product::where('is_variant',0)->orderBy('id','DESC')->get();

        $data['taxes'] = Tax::orderBy('id','DESC')->get();
        //dd($products[0]->variant('color'));
        return view ('Inventory.product.manage',$data);
    }
    function ajaxProduct(Request $request){
         $columns = array(
            0 => 'id',
            1 => 'thumbnail',
            2 => 'name',
            3 => 'code',
            4 => 'buying_price',
            5 => 'price',
            6 => 'min_qty',
            5 => 'created_by_user_id',
            6 => 'options',
        );
        $totalData = Product::where('business_type_id',auth()->user()->business->business_type_id)->count();


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        if(empty($search))
        {
            $products = Product::where('is_variant',0)->where('business_type_id',auth()->user()->business->business_type_id);
        }else{
            $products = Product::where('is_variant',0)->where('business_type_id',auth()->user()->business->business_type_id)->where("product_name","LIKE","%{$search}%");

        }
        $totalFiltered = $products->count();
        $products = $products->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        $data = array();
        if(!empty($products))
        {
             $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('product.edit');
           // $p_delete = can_p('product.delete');
            $p_barcode = can_p('product.generate_barcode');
            foreach($products as $product)
            {
                $nestedData['id'] = $i++;

                $nestedData['thumbnail'] = '<img src="'.$product->image_show.'" style="height:50px;width:50px;">';
                $nestedData['name'] = $product->product_name;
                $nestedData['code'] = $product->product_code;
                $nestedData['category_id'] = $product->category?->name;
                $nestedData['brand_id'] = $product->brand?->name;
                $nestedData['options'] = '';
                if($p_edit){
                    $nestedData['options'] = '<a class="btn btn-primary data_edit me-2" href="'.route('product.edit',$product->id).'"><i class="bx bx-edit"></i></a>';
                }
                if($p_barcode){
                    $nestedData['options'] .= '<a class="btn btn-primary data_edit me-2" href="'.route('product.generate_barcode','p_id='.$product->id).'"><i class="bx bx-barcode"></i></a>';
                }
               // if($p_delete){
                // $nestedData['options'] .= '<a href="#" data-id="'.$product->id.'" class="del_data btn btn-danger"><i class="bx bx-trash"></i></a>';
                //}
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
        if($request->is_barcode){
            $p_arr = explode('-',$search);
            if(isset($p_arr[1])){
                $products = Product::where('id',$p_arr[1])->where('business_type_id',auth()->user()->business->business_type_id)->get();
            }else{
                $products = Product::where('id',$search)->where('business_type_id',auth()->user()->business->business_type_id)->get();
            }

        }else{
            $products = Product::where('is_variant',0)->where('business_type_id',auth()->user()->business->business_type_id)->where(function($query) use($search){
                $query->where('product_name','like','%'.$search.'%')
                ->orWhere('product_code','like','%'.$search.'%');
            })
            ->orderBy("id",'DESC')->take(10)->get();
        }
        $data = view('Inventory.purchase.auto-seach-product',compact('products'))->render();
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
        if(can_p('product.create') == false){
            return redirect()->route('dashboard');
        }
        // $data['categories']=Category::orderBy('id','DESC')->get();
        // $data['brands']=Brand::orderBy('id','DESC')->get();
        $data['taxes'] = Tax::orderBy('id','DESC')->get();
        // if(auth()->user()->business->business_type_id == 5){
        //     $data['generics']=Generic::orderBy('id','DESC')->get();
        //     $data['product_types'] = ProductType::orderBy('id','DESC')->get();
        // }

        //dd($products[0]->variant('color'));
        return view ('Inventory.product.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(can_p('product.create') == false){
            return redirect()->route('dashboard');
        }
        //dd($request->all());
        $this->validate($request,[
            'product_name'=>[
                'required',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('business_type_id', auth()->user()->business->business_type_id);
                }),
            ],
            'product_image'=>'image|mimes:jpeg,png,jpg,webp',
            // 'category'=>'required',
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
            $data->exipre_date=$request->exipre_date ?? date('Y-m-d H:i:s');
            $data->manufacture_date=$request->manufacture_date ?? date('Y-m-d H:i:s');
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
            $data->sale_price=$request->sale_price ?? 0;
            $data->purchase_price=$request->purchase_price ?? 0;
            $data->business_id=auth()->user()->business_id;
            // dd($data);
            $data->save();
            $product=$data;
            // $product_stock = new ProductStock;
            // $product_stock->discount_type = $request->discount_type ?? 'percent';
            // $product_stock->discount = $request->product_discount ?? 0;
            // $product_stock->purchase_price = $request->purchase_price ?? 0;
            // $product_stock->sale_price =$request->sale_price ?? 0;
            // $product_stock->product_id =$product->id;
            // $product_stock->tax_id=$request->tax ?? 0;
            // $product_stock->unit_id=$request->unit ?? 0;
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
            }
            DB::commit();
            $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

            return redirect()->route('product.index')->with($notification);
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
        if(can_p('product.edit') == false){
            return redirect()->route('dashboard');
        }
        $data['taxes'] = Tax::orderBy('id','DESC')->get();
        $data['product']=$product=Product::where('id',$id)->first();
        // dd($product);
        // dd($product->product_stock);
        //  dd($product->atttribute_sets);
        //dd($product->productAttributeSets);
        return view('Inventory.product.edit',$data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if(can_p('product.edit') == false){
            return redirect()->route('dashboard');
        }
        $id = $request->id;

        $this->validate($request,[
            'product_name'=>[
                'required',
                Rule::unique('products')->where(function ($query) use ($id) {
                    return $query->where('id', '!=', $id)
                        ->where('is_variant',0)
                        ->where('business_type_id', auth()->user()->business->business_type_id);
                }),
            ],
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
            $data->exipre_date=$request->exipre_date ?? date('Y-m-d H:i:s');
            $data->manufacture_date=$request->manufacture_date ?? date('Y-m-d H:i:s');
            $data->discount_type=$request->discount_type;
            $data->discount=$request->product_discount ?? 0;
            $data->tax_id=$request->tax ?? 0;
            $data->unit_id=$request->unit ?? 0;
            $data->sale_price=$request->sale_price ?? 0;
            $data->business_id=auth()->user()->business_id;
            $data->purchase_price=$request->purchase_price ?? 0;


            $data->save();

            $product = $data;

            // $product_stock =  ProductStock::where('product_id',$product->id)->where('business_id', auth()->user()->business->id)->first();

            // if($product_stock == null){
            //     $product_stock = new ProductStock();
            // }
            // $product_stock->discount_type = $request->discount_type ?? 'percent';
            // $product_stock->discount = $request->product_discount ?? 0;
            // $product_stock->purchase_price = $request->purchase_price ?? 0;
            // $product_stock->sale_price =$request->sale_price ?? 0;
            // $product_stock->product_id =$product->id;
            // $product_stock->tax_id=$request->tax ?? 0;
            // $product_stock->unit_id=$request->unit ?? 0;
            // $product_stock->business_id = auth()->user()->business->id;
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
                        $new_product->business_id = auth()->user()->business->id;
                        $new_product->is_variant =1;
                        $new_product->save();
                        // $product_stock = new ProductStock;
                        // $product_stock->discount_type = $product->discount_type;
                        // $product_stock->discount = $product->discount ?? 0;
                        // $product_stock->purchase_price = $product->purchase_price ?? 0;
                        // $product_stock->sale_price =$product->sale_price ?? 0;
                        // $product_stock->product_id =$new_product->id;
                        // $product_stock->business_id = auth()->user()->business->id;
                        // $product_stock->save();

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
                }
            }


            DB::commit();
            $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

            return redirect()->route('product.index')->with($notification);
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
        if(can_p('product.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $product = Product::find($id);

            @unlink(public_path('upload/products/'.$product->image));
            $product->delete();
            DB::commit();
            $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
            );

            return redirect()->route('product.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
           // dd($e->getMessage());
            $notification=array(
                'message'=>"Can not Delete!",
                'alert-type'=>'error'
            );

            return redirect()->route('product.index')->with($notification);
        }
    }
    function select2ProductbyCat(Request $request){
         //return json_encode($request->all());
        
         $products = Product::select('id', 'product_name as name')->where('is_variant',0)->where('business_type_id',auth()->user()->business->business_type_id)->where('category_id',$request->cat_id)->where("product_name", "LIKE", "%$request->value%")->get();
        foreach ($products as $product) {
            if($product->variations->count()){
                foreach ($product->variations as $variation){
                    $data[]=['id'=>$variation->product->id ,'text'=>$variation->product->product_name.$variation->product->variation_attributes];
                }
            }else{
                $data[] = ['id' => $product->id, 'text' => $product->name];
            }
        }
        if($products->count() == 0){
            $data[] = ['id' => '', 'text' => "Search not found"];
        }
        return json_encode($data);
    }
    function productDetailsbyId(Request $request){

        $data['product']=$product = Product::where('id',$request->id)->first();
        if($request->is_sale == 1){
            if($product->qty <= 0 && isset($request->old) && $request->old == 0){
                return response()->json(['status'=>'no','msg'=>'Out of Stock']);
            }
                 //return $request->id;
            $data['row_no'] =$request->row_id;
            // if($request->is_sale == 1){
                $data['is_sale'] = $request->is_sale;
            //}
            if(isset($request->old) && $request->old != 0){
                $data['qty'] =$request->qty;
                $data['item_id'] = $request->old;
                if($request->type == 1){
                    $data['product_item'] = ProductInvoice::where('id',$request->old)->first();
                }else if($request->type == 2){
                    $data['product_item'] = ProductInvoiceReturn::where('id',$request->old)->first();
                }else if($request->type == 3){
                    $data['product_item'] = ProductPurchase::where('id',$request->old)->first();
                }else if($request->type == 2){
                    $data['product_item'] = ProductPurchaseReturn::where('id',$request->old)->first();
                }else if($request->type == 2){
                    $data['product_item'] = ProductQuotation::where('id',$request->old)->first();
                }

                $data_view= view ('Inventory.purchase.ajax-product-edit-data',$data)->render();
            }else{
                $data['qty'] =$request->qty;
                $data_view= view ('Inventory.purchase.ajax-product-data',$data)->render();
            }
            if($product->unit_id > 0){
                $unit_name = $product->unit?->name;
                $unit_id = $product->unit_id;
            }else{
                $unit_name = "";
                $unit_id = 0;
            }
            return response()->json(['status'=>'yes','data_view'=>$data_view,'p_data'=>$product,'unit_name'=>$unit_name,'unit_id'=>$unit_id]);
        }else{
                 //return $request->id;
            $data['row_no'] =$request->row_id;
            // if($request->is_sale == 1){
                $data['is_sale'] = $request->is_sale;
            //}
            if(isset($request->old) && $request->old != 0){
                $data['qty'] =$request->qty;
                $data['item_id'] = $request->old;
                if($request->type == 1){
                    $data['product_item'] = ProductInvoice::where('id',$request->old)->first();
                }else if($request->type == 2){
                    $data['product_item'] = ProductInvoiceReturn::where('id',$request->old)->first();
                }else if($request->type == 3){
                    $data['product_item'] = ProductPurchase::where('id',$request->old)->first();
                }else if($request->type == 2){
                    $data['product_item'] = ProductPurchaseReturn::where('id',$request->old)->first();
                }else if($request->type == 2){
                    $data['product_item'] = ProductQuotation::where('id',$request->old)->first();
                }

                $data_view= view ('Inventory.purchase.ajax-product-edit-data',$data)->render();
            }else{
                $data['qty'] =$request->qty;
                $data_view= view ('Inventory.purchase.ajax-product-data',$data)->render();
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

    function generateBarcode(Request $request){
        $data = [];
        if($request->p_id){
            $product = Product::find($request->p_id);
            if( $product){
                $data['bar_product']=$product;
            }
        }

        return view('Inventory.product.generate_barcode',$data);
    }
    function generateBarcodePost(Request $request){
        // $products = [];
        //dd($request->products);
        //foreach($request->products as $p_id=>$q){
           // $product = Product::find($p_id);
            //dd($product->variations);
            // if($product->variations->count()){
            //     $product->barcode_print_no = $q;
            // }else{
            //     $type_barcode = new TypeCode128;
            //     $barcode = $type_barcode->getBarcode('nbr-'.$p_id);
            //     $renderer = new PngRenderer;
            //     //$renderer = new Picqer\Barcode\Renderers\PngRenderer();
            //     $img_barcode = base64_encode($renderer->render($barcode));
            //     $product->img_barcode = $img_barcode;
            //     $product->barcode_print_no = $q;
            // }

            // $products[]=$product;
        //}
        //dd($request->products);
        $products  = Product::whereIn('id',array_keys($request->products))->get();
        //dd($products);
        $data['products']=$products;
        $data['qtys']=$request->products;
        $data['size']=$request->size;
        $data['name_check']=$request->name_check;
        $data['code_check']=$request->code_check;
        $data['price_check']=$request->price_check;
        $data['dis_price_check']=$request->dis_price_check;
        $data['box_width']=$request->box_width;
        return view('Inventory.product.print_barcode',$data);
    }
}
