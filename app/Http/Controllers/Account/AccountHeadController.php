<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account\AccountHead;
use App\Models\Account\PaymentMethod;
use App\Models\Account\AccountSubHead;
use App\Models\Account\AccountTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccountHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(can_p('account_head.index') == false){
            return redirect()->route('dashboard');
        }
        $accounts_heads = AccountHead::orderBy('id','DESC')->get();
        //dd($accounts_heads);
        //dd($accounts_heads);
        return view('Accounts.account_head.index', compact('accounts_heads'));
    }
    function select2Account(Request $request){
        $accounts = AccountHead::select('id', 'title')->where("title", "LIKE", "%$request->value%")->get();
        foreach ($accounts as $account) {
            $data[] = ['id' => $account->id, 'text' => $account->title];
        }
        return json_encode($data);
    }
    function select2Ledger(Request $request){
        $ledgers =AccountHead::whereIn('ac_type',json_decode($request->acc_c_id))
        ->where('title','like','%'.$request->value.'%')
        ->get();
        $data[] = ['id' => '', 'text' =>'Select Account Head'];
        foreach ($ledgers as $ledger) {
            $data[] = ['id' => $ledger->id, 'text' => $ledger->title];
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
        // $accounts_list = AccountHead::where('parent','!=',0)->get();
        // $methods = PaymentMethod::where('status', 1)->get();

        // $sub_heads = AccountSubHead::where('status',1)->where('parent_id',0)->get();
        // dd($accounts_list);
        if(can_p('account_head.create') == false){
            return redirect()->route('dashboard');
        }
        return view('Accounts.account_head.create');
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
        if(can_p('account_head.create') == false){
            return redirect()->route('dashboard');
        }
        $this->inital_account();
        $validatedData = $request->validate([
            'ac_type'=>'required',
            'opening_balance'=>'required|integer',
            'title'=>[
                'required',
                Rule::unique('account_heads')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business->id);
                }),
            ],
        ]);
        try{
            DB::beginTransaction();
            $data = new AccountHead();
            $data->title = $request->title;
            $data->ac_type = $request->ac_type;
            $data->note = $request->note;

            $data->status = $request->status;
            $data->save();
            if(!empty($request->opening_balance)){
                $capital_head = AccountHead::where("code",'3001')->first();
                $account_transaction = New AccountTransaction;
                $account_transaction->amount = $request->opening_balance;
                $account_transaction->account_id = $capital_head->id;
                $account_transaction->type = "credit";
                $account_transaction->sub_type = "Opening Balance";
                $account_transaction->reason = "Opening Balance";
                $account_transaction->date = date('Y-m-d');
                $account_transaction->relation_id = $data->id;
                $account_transaction->save();



                $pay_trans = New AccountTransaction;
                $pay_trans->amount = $request->opening_balance;
                $pay_trans->account_id = $data->id;
                $pay_trans->type = "debit";
                $pay_trans->sub_type = "Opening Balance";
                $pay_trans->reason = "Opening Balance";
                $pay_trans->date = date('Y-m-d');
                $pay_trans->relation_id = $data->id;
                $pay_trans->trans_id = $account_transaction->id;
                $pay_trans->save();

                $account_transaction->trans_id = $pay_trans->id;
                $account_transaction->save();

                $data->opening_id = $pay_trans->id;
                $data->save();
            }
            DB::commit();
            $notification=array(
                'message' => 'Successfully Done',
                'alert-type' => 'success'
            );
            return redirect()->route('account_head.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            $notification=array(
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
        }
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
        if(can_p('account_head.edit') == false){
            return redirect()->route('dashboard');
        }
        $data = AccountHead::findorfail($id);

        return view('Accounts.account_head.edit', compact('data'));
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
        if(can_p('account_head.edit') == false){
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'ac_type'=>'required',
            'opening_balance'=>'required|integer',
            'title'=>[
                'required',
                Rule::unique('account_heads')->where(function ($query) use ($id){
                    return $query->where('id', '!=', $id)
                            ->where('business_id', auth()->user()->business->id);
                }),
            ],
        ]);
        try{
            DB::beginTransaction();
            $data = AccountHead::find($id);
            $data->title = $request->title;
            $data->ac_type = $request->ac_type;
            $data->note = $request->note;
            $data->status = $request->status;
            $data->save();
            // dd(!empty($request->opening_balance));
            // if(!empty($request->opening_balance)){
            //dd($data->opening_id);
            if(!empty($request->opening_balance) || $data->opening_id > 0){
                if(empty($request->opening_balance)){

                }
                $pay_trans = AccountTransaction::find($data->opening_id);
                if($pay_trans == null){
                    $pay_trans = New AccountTransaction;
                }
                $pay_trans->amount = $request->opening_balance;
                $pay_trans->account_id = $data->id;
                $pay_trans->type = "debit";
                $pay_trans->sub_type = "Opening Balance";
                $pay_trans->reason = "Opening Balance";
                $pay_trans->date = date('Y-m-d');
                $pay_trans->relation_id = $data->id;

                $pay_trans->save();
                $capital_head = AccountHead::where("code",'3001')->first();
                $account_transaction = New AccountTransaction;
                $account_transaction->amount = $request->opening_balance;
                $account_transaction->account_id = $capital_head->id;
                $account_transaction->type = "credit";
                $account_transaction->sub_type = "Opening Balance";
                $account_transaction->reason = "Opening Balance";
                $account_transaction->date = date('Y-m-d');
                $account_transaction->relation_id = $data->id;
                $account_transaction->trans_id = $pay_trans->id;
                $account_transaction->save();
                $pay_trans->trans_id = $account_transaction->id;
                $pay_trans->save();

                $data->opening_id = $pay_trans->id;
                $data->save();
            }


            //}


            DB::commit();
            $notification=array(
                'message' => 'Successfully Done',
                'alert-type' => 'success'
            );
            return redirect()->route('account_head.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            $notification=array(
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
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
        if(can_p('account_head.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $data = AccountHead::find($id);
            $account_transaction =  AccountTransaction::find($data->opening_id);
            if($account_transaction){
                $account_transactionp =  AccountTransaction::find($account_transaction->trans_id);
                if($account_transactionp){
                    $account_transactionp->delete();
                }
                $account_transaction->delete();
            }

            $data->delete();


             DB::commit();
            $notification=array(
                'message' => 'Successfully Deleted',
                'alert-type' => 'success'
            );
            return redirect()->route('account_head.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            $notification=array(
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}
