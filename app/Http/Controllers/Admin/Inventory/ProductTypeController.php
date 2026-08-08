<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductTypeController extends Controller
{
    public function index()
    {
        return view ('admin.Inventory.product_type.manage');
    }
    function ajaxPType(Request $request){
        $columns = array(
           0 => 'product_types.id',
           1 => 'product_types.name',
           2 => 'businesses.business_name',
       );
       $totalData = ProductType::count();
       $totalFiltered = $totalData;

       $limit = $request->input('length');
       $start = $request->input('start');
       $order = $columns[$request->input('order.0.column')];
       $dir = $request->input('order.0.dir');
       $search = $request->input('search.value');
       $products = ProductType::leftJoin('businesses','businesses.id','product_types.business_id');
       if(!empty($search))
       {
           $products = $products->where("product_types.name","LIKE","%{$search}%")
           ->orWhere("businesses.business_name","LIKE","%{$search}%");
       }
       $products = $products->select('product_types.*','businesses.business_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();
       $data = array();
       if(!empty($products))
       {
            $i = $start == 0 ? 1 : $start+1;
        
           foreach($products as $product)
           {
               $nestedData['id'] = $i++;
               $nestedData['name'] = $product->name;
               $nestedData['business_name'] = $product->business?->business_name;
              
               $nestedData['options'] = '';
              
               $nestedData['options'] = '<a class="btn btn-primary data_edit" href="javascript:void(0)" data-id="'.$product->id.'"><i class="bx bx-edit"></i></a>';
              
               
                $nestedData['options'] .= '<a href="#" data-id="'.$product->id.'" class="del_data btn btn-danger"> <i class="bx bx-trash"></i></a>';
               
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
    function select2PTypeList(Request $request){
        $colors = ProductType::select('id', 'name')->where('business_id',$request->business_id)->where("name", "LIKE", "%$request->value%")->get();
        foreach ($colors as $color) {
            $data[] = ['id' => $color->id, 'text' => $color->name];
        }
        return json_encode($data);
    }
    public function store(Request $request)
    {
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'business_id'=>'required',
                'name'=>[
                    'required',
                    Rule::unique('product_types')->where(function ($query) use($request){
                        return $query->where('business_id', $request->business_id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'business_id'=>'required',
                'name'=>[
                    'required',
                    Rule::unique('product_types')->where(function ($query) use ($request,$id) {
                        return $query->where('id', '!=', $id)
                            ->where('business_id', $request->business_id);
                    }),
                ],
            ]);
        }
       
        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            if($request->id==0){
                $data=new ProductType();
            }
            else{
                $data=ProductType::find($request->id);
            }
            $data->name=$request->name;
            $data->business_id=$request->business_id;
            $data->status = 1;
            $data->save();
            DB::commit();
            if($request->id==0){
                return response([
                    'status' => 1,
                    'success' => 'Save successfully.',
                ]);
            }else{
                return response([
                    'status' => 1,
                    'success' => 'Update successfully.',
                ]);
            }
        }catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'error' => 'Something went Wrong!',
            ]);
        }

    }
     public function edit(Request $request)
    {
        
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=ProductType::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name,'business_id'=>$data->business_id ,'business_name'=>$data->business?->business_name]);
    }
    public function destroy(Request $request,$id)
    {
        
        try{
            DB::beginTransaction();
            $data=ProductType::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('admin.p_type.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('admin.p_type.index')->with($notification);
        }
    }
}
