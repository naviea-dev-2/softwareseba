<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    function index(){
        if(can_p('payment_method.index') == false){
            return redirect()->route('dashboard');
        }
        $data['methods']=PaymentMethod::orderBy('sorting','ASC')->get();
        return view ('Accounts.payment_method.manage',$data);
    }
    function select2PaymentMthods(Request $request){
        $accounts = PaymentMethod::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($accounts as $account) {
            $data[] = ['id' => $account->id, 'text' => $account->name];
        }
        return json_encode($data);
    }
     function store(Request $request){
        //  dd($request->all());
        if($request->id == 0){
            if(can_p('payment_method.add') == false){
                return response([
                    'status' => 0,
                    'error' => 'Add permission is not allowed',
                ]);
            }
        }else{
            if(can_p('payment_method.edit') == false){
                return response([
                    'status' => 0,
                    'error' => 'Edit permission is not allowed',
                ]);
            }
        }
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('payment_methods')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business->id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('payment_methods')->where(function ($query) use ($id) {
                        return $query->where('id', '!=', $id)
                            ->where('business_id', auth()->user()->business->id);
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
                $data=new PaymentMethod();
                $file=$request->file('image');
                if($file){
                    $filename=date('YmdHi')."_payment_methods.".$file->getClientOriginalName();
                    $file->move(public_path('upload/payment_methods'),$filename);
                    $data->image=$filename;
                }
            }
            else{
                $data=PaymentMethod::find($request->id);
                $file=$request->file('image');
                //dd($file);
                // return public_path('upload/customers');
                 if($file){
                     @unlink(public_path('upload/payment_methods/'.$data->image));
                     $filename=date('YmdHi')."_payment_methods.".$file->getClientOriginalName();
                     $file->move(public_path('upload/payment_methods'),$filename);
                     $data->image=$filename;
                 }
            }
            $data->name=$request->name;
            $data->sorting=$request->sorting;
            $data->for_pos=$request->for_pos ?? 0;
            $data->pos_account_id=$request->account ?? 0;
            // $data->account_id=$request->account;
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
        if(can_p('payment_method.edit') == false){
            return response([
                'status' => 0,
                'msg' => 'Edit permission is not allowed',
            ]);
        }
        if (!$request->id) {
            return response([
                'status' => 0,
                'msg' => 'Not Found',
            ]);
        } else {

           $data=PaymentMethod::find($request->id);

          $html='';

        }

        return response()->json(['status'=>1,'html' => $html,'account_name'=>$data->balance_account?->account_name,'account_id'=>$data->pos_account_id,'for_pos'=>$data->for_pos,'id'=>$data->id,'name'=>$data->name,'sorting'=>$data->sorting,'image_show'=>$data->image_show]);
    }
    public function delete(Request $request,$id)
    {
        if(can_p('payment_method.delete') == false){
            return redirect()->route('dashboard');
        }
        $data=PaymentMethod::find($id);
        $data->delete();

        $notification=array(
        'message'=>"Delete successfull",
        'alert-type'=>'success'
        );

        return redirect()->route('payment_method.index')->with($notification);
    }
}
