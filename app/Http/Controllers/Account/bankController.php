<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Bank;
use App\User;
use Session;
use Validator;
use Auth;
use Illuminate\Support\Facades\DB;

class bankController extends Controller
{
    public function view(){
        $data['banks']=Bank::orderBy('id','DESC')->get();
        return view ('Accounts.bank.manage',$data);
    }

    public function store(Request $request)
    {
        DB::transaction(function() use($request){

            $this->validate($request,[
              'bankName'=>'required'
            ]);

                if($request->id==0){
                    $data=new Bank();
                }
                else{
                    $data=Bank::find($request->id);
                }
                $data->name=$request->bankName;
                $data->save();

        });

         $notification=array(
            'message'=>"Save Success",
            'alert-type'=>'success'
         );

        return redirect()->route('bank.view')->with($notification);
    }

    public function delete(Request $request){

        $data=Bank::find($request->id);
        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

            return redirect()->route('bank.view');

    }

    public function edit(Request $request){

        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Bank::find($request->id);

        }

        return response()->json(['bankID'=>$data->id,'bankName'=>$data->name]);
    }
}
