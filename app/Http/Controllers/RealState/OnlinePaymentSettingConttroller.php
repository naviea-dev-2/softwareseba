<?php

namespace App\Http\Controllers\RealState;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\OnlinePaymentSetting;
class OnlinePaymentSettingConttroller extends Controller
{
    function index(){
        $data['payment_setting'] = OnlinePaymentSetting::firstOrNew();
        return view ('RealState.online_payment.index',$data);
    }
    function store(Request $request){
        $this->validate($request,[
            'store_id'=>['required'],
            'store_pass'=>['required'],
        ]);
         try{
            DB::beginTransaction();
            $onlinePaymentSetting = OnlinePaymentSetting::firstOrNew();
            $onlinePaymentSetting->status = $request->status;
            $onlinePaymentSetting->mode = $request->ssl_mode;
            $onlinePaymentSetting->store_id = $request->store_id;
            $onlinePaymentSetting->store_password = $request->store_pass;
            $onlinePaymentSetting->save();
            DB::commit();
            $notification=array(
                'message'=>"Update successfully.",
                'alert-type'=>'success'
            );

            return redirect()->route('online_payemnt.index')->with($notification);
            
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            $notification=array(
                'message'=>"Update Failed",
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification)->withInput($request->all());
        }
      
    }
}