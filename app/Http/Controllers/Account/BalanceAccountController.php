<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use Illuminate\Http\Request;
use App\Models\Account\PaymentMethod;
use App\Models\Account\BalanceAccount;
use Illuminate\Support\Facades\DB;
use Str;

class BalanceAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(can_p('balance_account.index') == false){
            return redirect()->route('dashboard');
        }
        $accounts = BalanceAccount::orderBy('id','DESC')->get();

        return view('Accounts.balance_account.index', compact('accounts'));
    }
    function select2BalanceAccounts(Request $request){
        $accounts = BalanceAccount::select('id', 'account_name');
        if($request->method_id){
            $accounts = $accounts->where('method_id',$request->method_id);
        }
        
        $accounts = $accounts->where("account_name", "LIKE", "%$request->value%")->get();
        foreach ($accounts as $account) {
            $data[] = ['id' => $account->id, 'text' => $account->account_name];
        }
        return json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(can_p('balance_account.add') == false){
            return redirect()->route('dashboard');
        }
        $data['accounts']=AccountHead::orderBy('id','DESC')->get();
        $data['methods'] = PaymentMethod::where('status', 1)->get();
        return view('Accounts.balance_account.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function inital_account(){
        $salesHead = AccountHead::where("code",'3001')->first();
       // dd($salesHead);
        if($salesHead == null){
            $salesHead = new AccountHead;
            $salesHead->title = "Capital";
            $salesHead->code = '3001';
            $salesHead->sys = 0;
            $salesHead->ac_type = 3;
            $salesHead->note = '';
            $salesHead->status = 1;
            $salesHead->save();

        }
        
    }
    public function store(Request $request)
    {
        if(can_p('balance_account.add') == false){
            return redirect()->route('dashboard');
        }
        $this->inital_account();
        $validatedData = $request->validate([
            'method_id' => 'required',
            'account_name' => 'required',
            'account_head' => 'required',
            'account_number' => 'required',
            'balance' => 'required'
        ]);
        $account = new BalanceAccount();
        $account->method_id = $request->method_id;
        $account->account_head_id = $request->account_head;
        $account->account_name = $request->account_name;
        $account->bank_name = $request->bank_name;
        $account->branch_name = $request->branch_name;
        $account->account_number = $request->account_number;
        $account->routing_number = $request->routing_number;
        $account->balance = $request->balance;
        // $account->status = $request->status;
        $account->save();
        if(!empty($request->balance)){

            $capital_head = AccountHead::where("code",'3001')->first();
            $account_transaction = New AccountTransaction;
            $account_transaction->amount = $request->balance;
            $account_transaction->account_id = $capital_head->id;
            $account_transaction->type = "credit";
            $account_transaction->sub_type = "Account Opening Balance";
            $account_transaction->reason = "Account Opening Balance";
            $account_transaction->date = date('Y-m-d');
            $account_transaction->relation_id = $account->id;
            $account_transaction->relation_with = "Bank Account";
            $account_transaction->save();

          

            $pay_trans = New AccountTransaction;
            $pay_trans->amount = $request->balance;
            $pay_trans->account_id = $request->account_head;
            $pay_trans->type = "debit";
            $pay_trans->sub_type = "Account Opening Balance";
            $pay_trans->reason = "Account Opening Balance";
            $pay_trans->date = date('Y-m-d');
            $pay_trans->relation_id = $account->id;
            $pay_trans->relation_with = "Bank Account";
            $pay_trans->trans_id = $account_transaction->id;
            $pay_trans->save();
            
            $account_transaction->trans_id = $pay_trans->id;
            $account_transaction->save();

            

        }

        $notification=array(
            'message' => 'Successfully Done',
            'alert-type' => 'success'
        );
        return redirect()->route('balance_account.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(can_p('balance_account.edit') == false){
            return redirect()->route('dashboard');
        }
        $data = BalanceAccount::findorfail($id);
       // dd($data->account);
        $methods = PaymentMethod::where('status', 1)->get();
        return view('Accounts.balance_account.edit', compact('methods', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(can_p('balance_account.edit') == false){
            return redirect()->route('dashboard');
        }
         $validatedData = $request->validate([
            'method_id' => 'required',
            'account_name' => 'required',
            'account_head' => 'required',
            'account_number' => 'required',
            'balance' => 'required'
        ]);
      // dd($request);
       try{
            DB::beginTransaction();
            $account = BalanceAccount::find($id);
            $account->account_head_id = $request->account_head;
            $account->method_id = $request->method_id;
            $account->account_name = $request->account_name;
            $account->bank_name = $request->bank_name;
            $account->branch_name = $request->branch_name;
            $account->account_number = $request->account_number;
            $account->routing_number = $request->routing_number;
            $account->balance = $request->balance;
            // $account->status = $request->status;
            $account->save();
            if(!empty($request->balance)){
                $capital_head = AccountHead::where("code",'3001')->first();
                $account_transaction =  AccountTransaction::where('relation_id',$account->id)->where('relation_with','Bank Account')->where('account_id', $capital_head->id)->first();
                if($account_transaction == null){
                    $account_transaction = New AccountTransaction;
                }
                $account_transaction->amount = $request->balance;
                $account_transaction->account_id = $capital_head->id;
                $account_transaction->type = "credit";
                $account_transaction->sub_type = "Account Opening Balance";
                $account_transaction->reason = "Account Opening Balance";
                $account_transaction->date = date('Y-m-d');
                $account_transaction->relation_id = $account->id;
                $account_transaction->relation_with = "Bank Account";
                $account_transaction->save();

                
                $pay_trans =  AccountTransaction::find($account_transaction->trans_id);
                if($pay_trans == null){
                    $pay_trans = New AccountTransaction;
                }
                
                $pay_trans->amount = $request->balance;
                $pay_trans->account_id = $request->account_head;
                $pay_trans->type = "debit";
                $pay_trans->sub_type = "Account Opening Balance";
                $pay_trans->reason = "Account Opening Balance";
                $pay_trans->date = date('Y-m-d');
                $pay_trans->relation_id = $account->id;
                $pay_trans->relation_with = "Bank Account";
                $pay_trans->trans_id = $account_transaction->id;
                $pay_trans->save();
                
                $account_transaction->trans_id = $pay_trans->id;
                $account_transaction->save();

             

            }
            DB::commit();
            $notification=array(
                'message' => 'Successfully Updated',
                'alert-type' => 'success'
            );
            return redirect()->route('balance_account.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            //dd($e->getMessage());
            $notification=array(
                'message' => 'Something went wrong!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(can_p('balance_account.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $account = BalanceAccount::find($id);
            $account_transaction =  AccountTransaction::where('relation_id',$account->id)->where('relation_with','Opening')->first();
            if($account_transaction){
                $account_transaction->delete();
            }
            $account->delete();

            $notification=array(
                'message' => 'Successfully Deleted',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            //dd($e->getMessage());
            $notification=array(
                'message' => 'Something went wrong!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}
