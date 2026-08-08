<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Bank;
use App\Models\Hr\BankAccount;
use App\User;

use Session;
use Validator;
use Auth;
use Illuminate\Support\Facades\DB;

class bankaccountController extends Controller
{
    public function view(){
        $data['bankaccounts']=BankAccount::orderBy('bank_accounts.id','DESC')
                              ->get();
        $data['banks']=Bank::orderBy('id','DESC')->get();
        return view ('Accounts.bankAccount.manage',$data);
    }

    public function store(Request $request)
    {
        DB::transaction(function() use($request){

            $this->validate($request,[
              'bankID'=>'required',
              'acName'=>'required',
              'branchName'=>'required',
              'acType'=>'required',
              'acNumber'=>'required',
              'routingNumber'=>'required',
            ]);

                if($request->id==0){
                    $data=new BankAccount();
                }
                else{
                    $data=BankAccount::find($request->id);
                }
                $data->bankID=$request->bankID;
                $data->acName=$request->acName;
                $data->branchName=$request->branchName;
                $data->acType=$request->acType;
                $data->acNumber=$request->acNumber;
                $data->routingNumber=$request->routingNumber;
                $data->note=$request->note;
                $data->initalBalance=$request->initalBalance;
                $data->save();

        });

        $notification=array(
            'message'=>"Save Success",
            'alert-type'=>'success'
        );

        return redirect()->route('bankaccount.view')->with($notification);
    }

    public function delete(Request $request){

        $data=BankAccount::find($request->id);
        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

            return redirect()->route('bankaccount.view');

    }

    public function edit(Request $request){

        if (!$request->id) {
           $html ='Sorry';
        } else {

           $bankaccount=BankAccount::find($request->id);

           $banks=Bank::orderBy('id','DESC')->get();


          $html='<option>-- Select One --</option>';

            foreach($banks as $bank){
                if($bank->id==$bankaccount->bankID){
                    $html.='<option value="'.$bank->id.'" selected>'.$bank->name.'</option>';
                }
                else{
                    $html.='<option value="'.$bank->id.'">'.$bank->name.'</option>';
                }


            }


        }

        return response()->json(['html' => $html,'bankaccountID'=>$bankaccount->id,'acName'=>$bankaccount->acName,'branchName'=>$bankaccount->branchName,'acType'=>$bankaccount->acType,'acNumber'=>$bankaccount->acNumber,'routingNumber'=>$bankaccount->routingNumber,'note'=>$bankaccount->note,'initalBalance'=>$bankaccount->initalBalance]);
    }
}

