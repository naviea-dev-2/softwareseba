<?php

namespace App\Http\Controllers\Inventory;

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
        if(can_p('p_type.index') == false){
            return redirect()->route('dashboard');
        }
        $data['p_types']=ProductType::where('business_type_id',auth()->user()->business->business_type_id)->orderBy('id','DESC')->get();
        return view ('Inventory.product_type.manage',$data);
    }
    function select2PTypeList(Request $request){
        $colors = ProductType::where('business_type_id',auth()->user()->business->business_type_id)->select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($colors as $color) {
            $data[] = ['id' => $color->id, 'text' => $color->name];
        }
        return json_encode($data);
    }
    public function store(Request $request)
    {
        if(can_p('p_type.add') == false){
            return redirect()->route('dashboard');
        }
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('product_types')->where(function ($query) {
                        return $query->where('business_type_id', auth()->user()->business->business_type_id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('product_types')->where(function ($query) use ($id) {
                        return $query->where('id', '!=', $id)
                            ->where('business_type_id', auth()->user()->business->business_type_id);
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
            // $data->business_type_id=auth()->user()->business->business_type_id;
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
        if(can_p('p_type.edit') == false){
            return redirect()->route('dashboard');
        }
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=ProductType::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name]);
    }
    public function destroy(Request $request,$id)
    {
        if(can_p('p_type.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $data=ProductType::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('p_type.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('p_type.index')->with($notification);
        }
    }
}
