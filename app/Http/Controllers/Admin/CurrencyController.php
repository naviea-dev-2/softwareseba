<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class CurrencyController extends Controller
{
     public function index()
    {
        
        $data['currencies']=Currency::orderBy('id','DESC')->get();
        return view ('admin.currency.manage',$data);
    }
    function select2CurrencyList(Request $request){
         $currencies = Currency::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($currencies as $currency) {
            $data[] = ['id' => $currency->id, 'text' => $currency->name];
        }
        return json_encode($data);
    }
    public function store(Request $request)
    {
      
       
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'symbol'=> 'required',
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
                $data=new Currency();
            }
            else{
                $data=Currency::find($request->id);
            }
            $data->name=trim($request->name);
            $data->symbol=trim($request->symbol);
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

           $data=Currency::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name,'symbol'=>$data->symbol]);
    }
    public function destroy(Request $request,$id)
    {
       
        try{
            DB::beginTransaction();
            $data=Currency::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('admin.currency.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('admin.currency.index')->with($notification);
        }
    }
}
