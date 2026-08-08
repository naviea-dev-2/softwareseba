<?php

namespace App\Http\Controllers\Inventory;

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
        if(can_p('brand.index') == false){
            return redirect()->route('dashboard');
        }
        $data['brands']=Brand::where('business_type_id',auth()->user()->business->business_type_id)->orderBy('id','DESC')->get();
        return view ('Inventory.brand.manage',$data);
    }
    function select2BrandList(Request $request){
        $brands = Brand::where('business_type_id',auth()->user()->business->business_type_id)->select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
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
        if(can_p('brand.add') == false){
            return redirect()->route('dashboard');
        }
       if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('brands')->where(function ($query) {
                        return $query->where('business_type_id', auth()->user()->business->business_type_id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('brands')->where(function ($query) use ($id) {
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
                $data=new Brand();
            }
            else{
                $data=Brand::find($request->id);
            }
            $data->name=$request->name;
           // $data->business_type_id=auth()->user()->business->business_type_id;
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
        if(can_p('brand.edit') == false){
            return redirect()->route('dashboard');
        }
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Brand::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name]);
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
        if(can_p('brand.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
             $data=Brand::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('brand.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('brand.index')->with($notification);
        }

    }
}
