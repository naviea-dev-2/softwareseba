<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\Account\Expense;
use App\Models\Account\ExpenseCategory;
use App\Models\Account\PaymentMethod;
use App\Models\Hr\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\OnlinePaymentSetting;
use App\Library\SslCommerz\SslCommerzNotification;
class ExpenseController extends Controller
{
    function index(){
        if(can_p('expense.index') == false){
            return redirect()->route('dashboard');
        }
        $data['expenses']=Expense::orderBy('id','DESC')->get();
        $data['categories']=ExpenseCategory::orderBy('id','DESC')->get();
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        $data['payment_setting'] = OnlinePaymentSetting::firstOrNew();
        return view ('Accounts.expense.index',$data);
    }
    function store(Request $request){
        if($request->id == 0){
            if(can_p('expense.add') == false){
                return response([
                    'status' => 0,
                    'error' => 'Add permission is not allowed',
                ]);
            }
        }else{
            if(can_p('expense.edit') == false){
                return response([
                    'status' => 0,
                    'error' => 'Edit permission is not allowed',
                ]);
            }
        }
        $payment_setting = OnlinePaymentSetting::firstOrNew();
        if($payment_setting->status != 1){
            $validator = Validator::make($request->all(),[
                'reason'=>'required',
                'category'=>'required',
                'date'=>'required',
                'amount'=>'required',
                'payment_method'=>'required',
                'account'=>'required',

            ]);
        }else{
            $validator = Validator::make($request->all(),[
                'reason'=>'required',
                'category'=>'required',
                'date'=>'required',
                'amount'=>'required',
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
            
            $data=new Expense();
            $data->reference_no = 'ex-' . date("Ymd") . '-'. date("his");
            $data->category_id=$request->category;
            $data->reason=$request->reason;
            $data->amount=$request->amount;
            $data->date=$request->date;
            // if($payment_setting->status != 1){
                $data->method_id=$request->payment_method;
                $data->balance_account_id=$request->account;
            // }else{
            //     $data->payment_status = 0;
            // }
            $data->save();
            // if($payment_setting->status != 1){
                $balance_account = BalanceAccount::find($request->account);
                //return $balance_account;
                $account_transaction = AccountTransaction::where('relation_id',$data->id)->where('relation_with','Expense')->where('account_id',$balance_account->account_head_id)->first();
                if($account_transaction == null){
                    $account_transaction = New AccountTransaction;
                }
                $account_transaction->amount = $request->amount;
                $account_transaction->account_id = $balance_account->account_head_id;
                $account_transaction->type = "debit";
                $account_transaction->sub_type = "Expense";
                $account_transaction->reason = $data->reference_no ." Expense Payment";
                $account_transaction->date = date('Y-m-d');
                $account_transaction->relation_id = $data->id;
                $account_transaction->relation_with = "Expense";
                $account_transaction->save();

                $cap_head =  AccountHead::where('expense_id',$request->category)->first();
                $ex_transaction = AccountTransaction::where('relation_id',$data->id)->where('relation_with','Expense')->where('account_id',$cap_head->id)->first();
                if($ex_transaction == null){
                    $ex_transaction = New AccountTransaction;
                }
                $ex_transaction->amount = $request->amount;
                $ex_transaction->account_id = $cap_head->id;
                $ex_transaction->type = "credit";
                $ex_transaction->sub_type = "Expense";
                $ex_transaction->reason = $data->reference_no ." Expense Payment";
                $ex_transaction->date = date('Y-m-d');
                $ex_transaction->relation_id = $data->id;
                $ex_transaction->relation_with = "Expense";
                $ex_transaction->save();
            // }

            DB::commit();
            return response([
                'status' => 1,
                'success' => 'Save successfully.',
            ]);
            // if($payment_setting->status != 1){
            //     return response([
            //         'status' => 1,
            //         'success' => 'Save successfully.',
            //     ]);
            // }else{
            //     $user = auth()->user();
            //     $res_pay = $this->pay($data,$payment_setting,$user->business);
            //     return $res_pay;
            // }
        }catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'data' => $e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }
    }
    function pay($payment,$payment_setting,$business){
        $apiDomain = $payment_setting->mode == 0 ? "https://sandbox.sslcommerz.com" : "https://securepay.sslcommerz.com";
        config()->set([
            'sslcommerz.' . "apiCredentials" => [
                'store_id' => $payment_setting->store_id,
                'store_password' => $payment_setting->store_password,
            ],
            'sslcommerz.' . "apiDomain" => $apiDomain,
            'sslcommerz.' . "connect_from_localhost" => true,
        ]);
        if( $business->currency){
            $currency = $business->currency->name;
        }else{
            $currency = "BDT";
        }
        $post_data = array();
        $post_data['total_amount'] = $payment->amount; # You cant not pay less than 10
        $post_data['currency'] = $currency;
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        $post_data['multi_card_name'] = "visacard"; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $business->business_name;
        $post_data['cus_email'] =  $business->email ?  $business->email : "test@gmail.com";
        $post_data['cus_add1'] =  $business->address1;
        $post_data['cus_add2'] = $business->address2;
        $post_data['cus_city'] = $business->city?->name;
        $post_data['cus_state'] = $business->state?->name;
        $post_data['cus_postcode'] = $business->post_code;
        $post_data['cus_country'] = $business->country?->name;
        $post_data['cus_phone'] = $business->phone_number;
        $post_data['cus_fax'] = "";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Expense";
        $post_data['product_category'] = "topup";
        $post_data['product_profile'] = "non-physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $payment->id;
        $post_data['value_b'] = "Expense";

        

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        // $payment_options = $sslc->makePayment($post_data, 'hosted');
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            return $payment_options;
            print_r($payment_options);
            $payment_options = array();
        }
        return "";

    }
     public function edit(Request $request)
    {
        if(can_p('expense.edit') == false){
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
            $data=Expense::find($request->id);
            $html='';
        }
        return response()->json(['status'=>1,'html' => $html,'id'=>$data->id,'reason'=>$data->reason,'date'=>date("Y-m-d",strtotime($data->date)),'amount'=>$data->amount,'method'=>$data->method_id,'account_name'=>$data->balance_account->account_name,'account_id'=>$data->balance_account_id,'category'=>$data->category_id]);
    }
    public function delete(Request $request,$id)
    {
        if(can_p('expense.delete') == false){
            return redirect()->route('dashboard');
        }
        $data=Expense::find($id);
        $data->delete();
        $account_transaction = AccountTransaction::where('relation_id',$data->id)->first();
        if($account_transaction){
            $account_transaction->delete();
        }
        $notification=array(
        'message'=>"Delete successfull",
        'alert-type'=>'success'
        );

        return redirect()->route('expense.index')->with($notification);
    }
}
