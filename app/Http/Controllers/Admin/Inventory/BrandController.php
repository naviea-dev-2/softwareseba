<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class BrandController extends Controller
{
     public function index()
    {
        
        return view ('admin.Inventory.brand.manage');
    }
    function ajaxBrand(Request $request){
        $columns = array(
           0 => 'brands.id',
           1 => 'brands.name',
           2 => 'businesses.business_name',
       );
       $totalData = Brand::count();
       $totalFiltered = $totalData;

       $limit = $request->input('length');
       $start = $request->input('start');
       $order = $columns[$request->input('order.0.column')];
       $dir = $request->input('order.0.dir');
       $search = $request->input('search.value');
       $products = Brand::leftJoin('businesses','businesses.id','brands.business_id');
       if(!empty($search))
       {
           $products = $products->where("brands.name","LIKE","%{$search}%")
           ->orWhere("businesses.business_name","LIKE","%{$search}%");
       }
       $products = $products->select('brands.*','businesses.business_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();
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
    function select2BrandList(Request $request){
        $brands = Brand::select('id', 'name')->where('business_id',$request->business_id)->where("name", "LIKE", "%$request->value%")->get();
        foreach ($brands as $brand) {
            $data[] = ['id' => $brand->id, 'text' => $brand->name]; 
        }
        return json_encode($data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'business_id'=>'required',
                'name'=>[
                    'required',
                    Rule::unique('brands')->where(function ($query) use($request){
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
                    Rule::unique('brands')->where(function ($query) use ($request,$id) {
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
                $data=new Brand();
            }
            else{
                $data=Brand::find($request->id);
            }
            $data->name=$request->name;
            $data->business_id=$request->business_id;
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
    public function edit(Request $request)
    {
       
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Brand::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name,'business_id'=>$data->business_id ,'business_name'=>$data->business?->business_name]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
       
        try{
            DB::beginTransaction();
             $data=Brand::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('admin.brand.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('admin.brand.index')->with($notification);
        }

    }
}
