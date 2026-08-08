<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $data['taxes']=Tax::orderBy('id','DESC')->get();
        return view ('admin.Inventory.vat_rate.manage',$data);
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
       
        $validator = Validator::make($request->all(),[
            'name'=>'required'
        ]);
        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            if($request->id==0){
                $data=new Tax();
            }
            else{
                $data=Tax::find($request->id);
            }
            $data->name=$request->name;
            $data->short_name = $request->short_name;
            $data->rate_type = $request->rate_type;
            $data->rate = $request->tax_rate ?? 0;
            $data->tax_number = $request->tax_number;
            $data->remarks = $request->note;
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
                 'data' => $e->getMessage(),
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

           $data=Tax::find($request->id);

          $html='';

        }
        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name,'short_name'=>$data->short_name,'rate_type'=>$data->rate_type,'tax_rate'=>$data->rate,'tax_number'=>$data->tax_number,'note'=>$data->remarks]);
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
            $data=Tax::find($id);
            $data->delete();
             DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('admin.tax.index')->with($notification);
           
        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('admin.tax.index')->with($notification);
        }
    }
}
