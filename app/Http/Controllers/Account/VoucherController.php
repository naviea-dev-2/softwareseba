<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account\AccountHead;
use App\Models\Account\PaymentMethod;
use App\Models\Account\Voucher;
use App\Models\Account\VoucherDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\InvoiceNumber;
use App\Models\OnlinePaymentSetting;
use Mpdf\Mpdf;
class VoucherController extends Controller
{
    public function index()
    {
        return view('Accounts.voucher.manage');
    }
    function ajaxData(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'funds.name',
            2 => 'funds.amount_in',
            3 => 'funds.amount_out',
            4 => 'funds.amount_in',
        );
        $totalData = Voucher::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        DB::statement("SET SQL_MODE=''");
        $datalist = Voucher::where('is_m',0);
        if(!empty($search)){
            $datalist =$datalist->where("funds.name","LIKE","%{$search}%");
        }
        $totalFiltered = $datalist->count();
        $datalist = $datalist->offset($start)->limit($limit)->orderBy($order,$dir)->get();


        $data = array();
        if(!empty($datalist))
        {
             $i = $start == 0 ? 1 : $start+1;
            foreach($datalist as $data_v)
            {
                $nestedData['id'] = $i++;
                $nestedData['type'] = $data_v->v_type;
                $nestedData['v_date'] = date('d-m-Y', strtotime($data_v->voucher_date));
                $nestedData['voucher_no'] = $data_v->voucher_no;
                $nestedData['v_amount'] = $data_v->voucher_amount;
                $nestedData['options']='';
                $nestedData['options'] = '<a class="p-1 px-2 btn btn-primary" href="' . route('account.voucher.edit', $data_v->id) . '"><i class="bx bx-edit"></i></a>';
                $nestedData['options'] .= '<button class="p-1 px-2 btn btn-danger ml-2 del_hr_data" data-id="' . $data_v->id . '" id="dataDeleteModal"><i class="bx bx-trash"></i></button>';
                $nestedData['options'] .= '<button class="p-1 px-2 btn btn-info ml-2 download-voucher" value="' . $data_v->id . '"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" style="color: white; font-size: 1.5em;"><path d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4zm-132.9 88.7L299.3 420.7c-6.2 6.2-16.4 6.2-22.6 0L171.3 315.3c-10.1-10.1-2.9-27.3 11.3-27.3H248V176c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16v112h65.4c14.2 0 21.4 17.2 11.3 27.3z"></path></svg></button>';

                $data[] = $nestedData;

            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return json_encode($json_data);
    }
    public function createDebit()
    {
        $data['payment_setting'] = OnlinePaymentSetting::firstOrNew();
        return view('Accounts.voucher.create.create_payment',$data);
    }
    public function createCredit()
    {
        $data['payment_setting'] = OnlinePaymentSetting::firstOrNew();
        return view('Accounts.voucher.create.create_receipt',$data);
    }
    public function createContra()
    {
        return view('Accounts.voucher.create.create_contra');
    }
    public function createJournal()
    {
        return view('Accounts.voucher.create.create_journal');
    }
    function crateInvoice($type){
        $year = date('Y');
        $month = date('m');
        $invoice_m= InvoiceNumber::where('type',"Voucher")->where('year',$year)->where('month',$month)->first();
        if($invoice_m){
            $invoice_m->in_number = $invoice_m->in_number+1;
            $invoice_m->save();
        }else{
            $invoice_m = new InvoiceNumber;
            $invoice_m->year = $year;
            $invoice_m->month = $month;
            $invoice_m->in_number = 1;
            $invoice_m->type = $type;
            $invoice_m->save();
        }
        return $year.$month.str_pad($invoice_m->in_number, 6, '0', STR_PAD_LEFT);
    }
    public function store(Request $request)
    {
        if($request->v_type == "Debit Voucher"){
            $payment_setting = OnlinePaymentSetting::firstOrNew();
            if($payment_setting->status != 1){
                $validator = Validator::make($request->all(), [
                    'p_date' => 'required',
                    'add_account' => 'required',
                    'payment_method' => 'required',
                    'ledgers' => 'required',
                    'amount' => 'required',
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'p_date' => 'required',
                    'ledgers' => 'required',
                    'amount' => 'required',
                ]);
            }
        }
        else if($request->v_type == "Credit Voucher"){
            $payment_setting = OnlinePaymentSetting::firstOrNew();
            if($payment_setting->status != 1){
                $validator = Validator::make($request->all(), [
                    'receipt_date' => 'required',
                    'add_account' => 'required',
                    'payment_method' => 'required',
                    'ledgers' => 'required',
                    'amount' => 'required',
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'receipt_date' => 'required',
                    'ledgers' => 'required',
                    'amount' => 'required',
                ]);
            }
        }
        else if($request->v_type == "Journal"){
            $validator = Validator::make($request->all(), [
                'journal_date' => 'required',

                'ledgers' => 'required',
                'dr_amount' => 'required',
                'cr_amount' => 'required',
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'trans_date' => 'required',
                'trans_from' => 'required',
                'trans_to' => 'required',
                'trans_amount' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        try{

            DB::beginTransaction();
            $user_id =   $user = auth()->user()->id;

            if($request->v_type == "Debit Voucher"){
                
                
                $voucher = new Voucher();
                $voucher->voucher_no= $this->crateInvoice('Payment');
                $voucher->v_type = $request->v_type;
                $voucher->voucher_date = $request->p_date;
                if($payment_setting->status != 1){
                    $voucher->voucher_by = $request->add_account;
                    $voucher->fund_id = $request->payment_method;
                }else{
                    $voucher->is_online_payment = 0;
                    $voucher->status = 0;
                }
                $voucher->voucher_amount = $request->voucher_amount;
                $voucher->ref =  $request->ref;
                $voucher->description =  $request->description;
                $voucher->created_by =  $user_id;
                $voucher->save();
                if($payment_setting->status != 1){
                    $balance_account = BalanceAccount::find($request->add_account);
                    $account_transaction = new AccountTransaction();
                    $account_transaction->amount = $request->voucher_amount;
                    $account_transaction->account_id = $balance_account->account_head_id;
                    $account_transaction->type = "credit";
                    $account_transaction->sub_type = "Voucher";
                    $account_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                    $account_transaction->date =  $request->p_date;
                    $account_transaction->relation_id = $voucher->id;
                    $account_transaction->relation_with = "Voucher";
                    $account_transaction->save();
                }

                if($request->ledgers){
                    foreach($request->ledgers as $k=>$a_id){
                        $detail = new VoucherDetail;
                        $detail->voucher_id = $voucher->id;
                        $detail->ledger_id = $a_id;
                        $detail->debit = $request->amount[$k];
                        $detail->save();
                        if($payment_setting->status != 1){
                            $ex_transaction = new AccountTransaction();
                            $ex_transaction->amount = $request->amount[$k];
                            $ex_transaction->account_id = $a_id;
                            $ex_transaction->type = "debit";
                            $ex_transaction->sub_type = "Voucher";
                            $ex_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                            $ex_transaction->date = $request->p_date;
                            $ex_transaction->relation_id = $voucher->id;
                            $ex_transaction->sub_related_id = $detail->id;
                            $ex_transaction->relation_with = "Voucher";
                            $ex_transaction->save();
                        }

                    }
                }
            }else if($request->v_type == "Credit Voucher"){
                $voucher = new Voucher();
                $voucher->voucher_no= $this->crateInvoice('Receipt');
                $voucher->v_type = $request->v_type;
                $voucher->voucher_date = $request->receipt_date;
                if($payment_setting->status != 1){
                    $voucher->voucher_by = $request->add_account;
                    $voucher->fund_id = $request->payment_method;
                }
                $voucher->voucher_amount = $request->voucher_amount;
                $voucher->ref =  $request->ref;
                $voucher->description =  $request->description;
                $voucher->created_by =  $user_id;
                $voucher->save();
                if($payment_setting->status != 1){
                    $balance_account = BalanceAccount::find($request->add_account);
                    $account_transaction = new AccountTransaction();
                    $account_transaction->amount = $request->voucher_amount;
                    $account_transaction->account_id = $balance_account->account_head_id;
                    $account_transaction->type = "debit";
                    $account_transaction->sub_type = "Voucher";
                    $account_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                    $account_transaction->date =  $request->receipt_date;
                    $account_transaction->relation_id = $voucher->id;
                    $account_transaction->relation_with = "Voucher";
                    $account_transaction->save();
                }

                if($request->ledgers){
                    foreach($request->ledgers as $k=>$a_id){
                        $detail = new VoucherDetail;
                        $detail->voucher_id = $voucher->id;
                        $detail->ledger_id = $a_id;
                        $detail->debit = $request->amount[$k];
                        $detail->save();
                        if($payment_setting->status != 1){
                            $ex_transaction = new AccountTransaction();
                            $ex_transaction->amount = $request->amount[$k];
                            $ex_transaction->account_id = $a_id;
                            $ex_transaction->type = "credit";
                            $ex_transaction->sub_type = "Voucher";
                            $ex_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                            $ex_transaction->date = $request->receipt_date;
                            $ex_transaction->relation_id = $voucher->id;
                            $ex_transaction->sub_related_id = $detail->id;
                            $ex_transaction->relation_with = "Voucher";
                            $ex_transaction->save();
                        }
                    }
                }
            }else if($request->v_type == "Journal"){
                $voucher = new Voucher();
                $voucher->voucher_no= $this->crateInvoice('Journal');
                $voucher->v_type = $request->v_type;
                $voucher->voucher_date = $request->journal_date;
                // $voucher->fund_id = $request->fund;
                $voucher->voucher_amount = $request->total_amount_dr ?? 0;
                $voucher->ref =  $request->ref;
                $voucher->description =  $request->description;
                $voucher->created_by =  $user_id;
                $voucher->save();
                $j_i=0;
                if($request->ledgers){
                    foreach($request->dr_amount as $k=>$dr_a){
                        $detail = new VoucherDetail;
                        $detail->voucher_id = $voucher->id;
                        $detail->ledger_id = $request->ledgers[$k];
                        $detail->debit = $dr_a ?? 0;
                        $detail->credit = $request->cr_amount[$k] ?? 0;
                        $detail->save();
                        if($dr_a > 0){

                            $ex_transaction = new AccountTransaction();
                            $ex_transaction->amount = $dr_a ?? 0;
                            $ex_transaction->account_id =  $request->ledgers[$k] ?? 0;
                            $ex_transaction->type = "debit";
                            $ex_transaction->sub_type = "Voucher";
                            $ex_transaction->reason = $voucher->voucher_no ." Journal";
                            $ex_transaction->date =  $request->journal_date;
                            $ex_transaction->relation_id = $voucher->id;
                            $ex_transaction->sub_related_id = $detail->id;
                            $ex_transaction->relation_with = "Voucher";
                            $ex_transaction->save();

                            // $trans = new LedgerTransaction();
                            // $trans->trans_date =  $request->journal_date;
                            // $trans->trans_type = "Voucher";
                            // $trans->r_type = "Debit";
                            // $trans->r_id = $detail->id;
                            // $trans->voucher_id = $voucher->id;
                            // $trans->fund_id = $request->fund;
                            // $trans->ledger_id = $request->ledgers[$k];
                            // $trans->debit_amount = $dr_a;
                            // $trans->credit_amount = $request->cr_amount[$k];
                            // $trans->created_by =  $user_id;
                            // if($j_i == 0){
                            //     $trans->is_depend =  1;
                            // }

                            // $trans->save();
                        }else{
                            $transaction = new AccountTransaction();
                            $transaction->amount = $request->cr_amount[$k] ?? 0;
                            $transaction->account_id =  $request->ledgers[$k];
                            $transaction->type = "credit";
                            $transaction->sub_type = "Voucher";
                            $transaction->reason = $voucher->voucher_no ." Journal";
                            $transaction->date =  $request->journal_date;
                            $transaction->relation_id = $voucher->id;
                            $transaction->sub_related_id = $detail->id;
                            $transaction->relation_with = "Voucher";
                            $transaction->save();
                            // $trans = new LedgerTransaction();
                            // $trans->trans_date =  $request->journal_date;
                            // $trans->trans_type = "Voucher";
                            // $trans->r_type = "Credit";
                            // $trans->r_id = $detail->id;
                            // $trans->voucher_id = $voucher->id;
                            // $trans->fund_id = $request->fund;
                            // $trans->ledger_id = $request->ledgers[$k];
                            // $trans->debit_amount = $dr_a;
                            // $trans->credit_amount = $request->cr_amount[$k];
                            // $trans->created_by =  $user_id;
                            // if($j_i == 0){
                            //     $trans->is_depend =  1;
                            // }
                            // $trans->save();
                        }
                        $j_i++;
                    }
                }
            }
            else{
                $voucher = new Voucher();
                $voucher->voucher_no= $this->crateInvoice('Contra');
                $voucher->v_type = $request->v_type;
                $voucher->voucher_date = $request->trans_date;
                $voucher->trans_from = $request->trans_from;
                $voucher->trans_to = $request->trans_to;
                $voucher->voucher_amount = $request->trans_amount ?? 0;
                $voucher->ref =  $request->ref;
                $voucher->description =  $request->description;
                $voucher->created_by =  $user_id;
                $voucher->save();

                $transaction = new AccountTransaction();
                $transaction->amount = $request->trans_amount ?? 0;
                $transaction->account_id =  $request->trans_to;
                $transaction->type = "credit";
                $transaction->sub_type = "Voucher";
                $transaction->reason = $voucher->voucher_no ." Contra";
                $transaction->date =  $request->trans_date;
                $transaction->relation_id = $voucher->id;
                $transaction->relation_with = "Voucher";
                $transaction->save();

                $ex_transaction = new AccountTransaction();
                $ex_transaction->amount = $request->trans_amount ?? 0;
                $ex_transaction->account_id =  $request->trans_from;
                $ex_transaction->type = "debit";
                $ex_transaction->sub_type = "Voucher";
                $ex_transaction->reason = $voucher->voucher_no ." Contra";
                $ex_transaction->date =  $request->trans_date;
                $ex_transaction->relation_id = $voucher->id;
                $ex_transaction->relation_with = "Voucher";
                $ex_transaction->save();
            }
            DB::commit();
            return response()->json([
                'status'=>'yes',
                'msg'=>'Voucher Add Successfully'
            ]);
            // if($payment_setting->status != 1){
            //     return response()->json([
            //         'status'=>'yes',
            //         'msg'=>'Voucher Add Successfully'
            //     ]);
            // }else{
            //     $user = auth()->user();
            //     $res_pay = $this->pay($voucher,$payment_setting,$user->business,$request->v_type);
            //     return $res_pay;
            // }
           
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e);
            return response()->json([
                'status'=>'no',
                'msg'=>$e->getMessage()
            ]);
        }
    }
    function pay($payment,$payment_setting,$business,$v_type){
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
        $post_data['total_amount'] = $payment->voucher_amount; # You cant not pay less than 10
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
        $post_data['product_name'] = "Employee Loan";
        $post_data['product_category'] = "topup";
        $post_data['product_profile'] = "non-physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $payment->id;
        $post_data['value_b'] = $v_type;

        

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
    public function edit(Request $request,$id){
        try{
            $data['voucher'] = $voucher = Voucher::find($id);
            $html = "";
            if($voucher->v_type == "Debit Voucher"){
               return  view('Accounts.voucher.update.payment',$data);
            }
            else if($voucher->v_type == "Credit Voucher"){
               return view('Accounts.voucher.update.receipt',$data);
            }
            else if($voucher->v_type == "Journal"){
                return view('Accounts.voucher.update.journal',$data);
            }
            else if($voucher->v_type == "Contra"){
               return  view('Accounts.voucher.update.contra',$data);
            }else{
                return  view('Accounts.voucher.update.transfer',$data);
            }
            if($voucher->v_type == "Contra"){
                return response()->json([
                    'status'=>'yes',
                    'voucher'=>$voucher,
                    'details'=>$voucher?->details,
                    'from_name'=>$voucher->trans_f?->title,
                    'to_name'=>$voucher->trans_t?->title,
                    'html'=>$html
                ]);
            }else{
                return response()->json([
                    'status'=>'yes',
                    'voucher'=>$voucher,
                    'details'=>$voucher?->details,
                    'v_ledger_name'=>$voucher->balance_account?->account_name,
                    'fund_name'=>$voucher->method?->name,
                    'html'=>$html
                ]);
            }


        }catch(\Exception $e){
            return response()->json([
                'status'=>'no',
                'msg'=>$e->getMessage()
            ]);
        }
    }
    public function update(Request $request,$id)
    {
        // dd($request->all());
        if($request->v_type == "Debit Voucher"){
            $validator = Validator::make($request->all(), [
                'p_date' => 'required',
                'add_account' => 'required',
                'payment_method' => 'required',
            ]);
        }
        else if($request->v_type == "Credit Voucher"){
            $validator = Validator::make($request->all(), [
                'p_date' => 'required',
                'add_account' => 'required',
                'payment_method' => 'required',
            ]);
        }
        else if($request->v_type == "Journal"){
            $validator = Validator::make($request->all(), [
                'p_date' => 'required',
                // 'from_fund' => 'required',
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'trans_date' => 'required',
                'trans_from' => 'required',
                'trans_to' => 'required',
                'trans_amount' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        try{
            // $school = auth()->guard('school')->user()->school;

            DB::beginTransaction();
            if($request->v_type == "Debit Voucher"){
                $balance_account = BalanceAccount::find($request->add_account);
                $voucher = Voucher::find($id);
                $voucher->voucher_date = $request->p_date;
                $voucher->voucher_by = $request->add_account;
                $voucher->fund_id = $request->payment_method;
                $voucher->voucher_amount = $request->voucher_amount;
                $voucher->ref =  $request->ref;
                $voucher->description =  $request->description;
                $voucher->save();

                $account_transaction = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('sub_related_id',0)->first();
                if($account_transaction == null){
                    $account_transaction = New AccountTransaction;
                }

                $account_transaction->amount = $request->voucher_amount;
                $account_transaction->account_id = $balance_account->account_head_id;
                $account_transaction->type = "debit";
                $account_transaction->sub_type = "Voucher";
                $account_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                $account_transaction->date =  $request->p_date;
                $account_transaction->relation_id = $voucher->id;
                $account_transaction->relation_with = "Voucher";
                $account_transaction->save();


                if($request->old_ledgers){
                    foreach($request->old_ledgers as $k=>$a_id){
                        $detail = VoucherDetail::find($k);
                        $detail->voucher_id = $voucher->id;
                        $detail->ledger_id = $a_id;
                        $detail->debit = $request->old_amount[$k];
                        $detail->save();
                        $ex_transaction = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('sub_related_id', $detail->id)->first();

                        if($ex_transaction == null){
                            $ex_transaction = New AccountTransaction;
                        }
                        $ex_transaction->amount = $request->old_amount[$k];
                        $ex_transaction->account_id = $a_id;
                        $ex_transaction->type = "credit";
                        $ex_transaction->sub_type = "Voucher";
                        $ex_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                        $ex_transaction->date = $request->p_date;
                        $ex_transaction->relation_id = $voucher->id;
                        $ex_transaction->sub_related_id = $detail->id;
                        $ex_transaction->relation_with = "Voucher";
                        $ex_transaction->save();
                    }
                }
                if($request->del_ledgers){
                    foreach($request->del_ledgers as $k=>$a_id){
                        $detail = VoucherDetail::find($a_id);
                        $trans = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('sub_related_id', $detail->id)->first();
                        if($trans == null){
                            $trans->delete();
                        }
                        $detail->delete();
                    }
                }
                if($request->ledgers){
                    foreach($request->ledgers as $k=>$a_id){
                        $detail = new VoucherDetail;
                        $detail->voucher_id = $voucher->id;
                        $detail->ledger_id = $a_id;
                        $detail->debit = $request->amount[$k];
                        $detail->save();

                        $ex_transaction = new AccountTransaction();
                        $ex_transaction->amount = $request->amount[$k];
                        $ex_transaction->account_id = $a_id;
                        $ex_transaction->type = "credit";
                        $ex_transaction->sub_type = "Voucher";
                        $ex_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                        $ex_transaction->date = $request->p_date;
                        $ex_transaction->relation_id = $voucher->id;
                        $ex_transaction->sub_related_id = $detail->id;
                        $ex_transaction->relation_with = "Voucher";
                        $ex_transaction->save();
                    }
                }
            }else if($request->v_type == "Credit Voucher"){
                $balance_account = BalanceAccount::find($request->add_account);
                $voucher = Voucher::find($id);
                $voucher->voucher_date = $request->p_date;
                $voucher->voucher_by = $request->add_account;
                $voucher->fund_id = $request->payment_method;
                $voucher->voucher_amount = $request->voucher_amount;
                $voucher->ref =  $request->ref;
                $voucher->description =  $request->description;
                $voucher->save();

                $account_transaction = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('sub_related_id',0)->first();
                if($account_transaction == null){
                    $account_transaction = New AccountTransaction;
                }
                $account_transaction->amount = $request->voucher_amount;
                $account_transaction->account_id = $balance_account->account_head_id;
                $account_transaction->type = "credit";
                $account_transaction->sub_type = "Voucher";
                $account_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                $account_transaction->date =  $request->receipt_date;
                $account_transaction->relation_id = $voucher->id;
                $account_transaction->relation_with = "Voucher";
                $account_transaction->save();

                if($request->old_ledgers){
                    foreach($request->old_ledgers as $k=>$a_id){
                        $detail = VoucherDetail::find($k);
                        $detail->voucher_id = $voucher->id;
                        $detail->ledger_id = $a_id;
                        $detail->debit = $request->old_amount[$k];
                        $detail->save();

                       $ex_transaction = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('sub_related_id', $detail->id)->first();

                        if($ex_transaction == null){
                            $ex_transaction = New AccountTransaction;
                        }
                        $ex_transaction->amount = $request->old_amount[$k];
                        $ex_transaction->account_id = $a_id;
                        $ex_transaction->type = "debit";
                        $ex_transaction->sub_type = "Voucher";
                        $ex_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                        $ex_transaction->date = $request->receipt_date;
                        $ex_transaction->relation_id = $voucher->id;
                        $ex_transaction->sub_related_id = $detail->id;
                        $ex_transaction->relation_with = "Voucher";
                        $ex_transaction->save();
                    }
                }
                if($request->del_ledgers){
                    foreach($request->del_ledgers as $k=>$a_id){
                        $detail = VoucherDetail::find($a_id);
                        $trans = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('sub_related_id', $detail->id)->first();
                        if($trans == null){
                            $trans->delete();
                        }
                        $detail->delete();
                    }
                }
                if($request->ledgers){
                    foreach($request->ledgers as $k=>$a_id){
                        $detail = new VoucherDetail;
                        $detail->voucher_id = $voucher->id;
                        $detail->ledger_id = $a_id;
                        $detail->debit = $request->amount[$k];
                        $detail->save();

                        $ex_transaction = new AccountTransaction();
                        $ex_transaction->amount = $request->amount[$k];
                        $ex_transaction->account_id = $a_id;
                        $ex_transaction->type = "debit";
                        $ex_transaction->sub_type = "Voucher";
                        $ex_transaction->reason = $voucher->voucher_no ." Voucher Payment";
                        $ex_transaction->date = $request->receipt_date;
                        $ex_transaction->relation_id = $voucher->id;
                        $ex_transaction->sub_related_id = $detail->id;
                        $ex_transaction->relation_with = "Voucher";
                        $ex_transaction->save();
                    }
                }
            }
            else if($request->v_type == "Journal"){
                $voucher = Voucher::find($id);
                $voucher->voucher_date = $request->p_date;
                // $voucher->fund_id = $request->from_fund;
                $voucher->voucher_amount = $request->total_amount_dr;
                $voucher->ref =  $request->ref;
                $voucher->description =  $request->description;
                $voucher->save();
                $j_i = 0;
                if($request->old_ledgers){
                    foreach($request->dr_old_amount as $k=>$dr_a){
                        $detail = VoucherDetail::find($k);
                        $detail->voucher_id = $voucher->id;
                        $detail->ledger_id = $request->old_ledgers[$k];
                        $detail->debit = $dr_a;
                        $detail->credit = $request->cr_old_amount[$k];
                        $detail->save();
                        if($dr_a > 0){

                            $ex_transaction = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('sub_related_id', $detail->id)->first();

                            if($ex_transaction == null){
                                $ex_transaction = New AccountTransaction;
                            }
                            $ex_transaction->amount = $dr_a;
                            $ex_transaction->account_id =  $request->old_ledgers[$k];
                            $ex_transaction->type = "debit";
                            $ex_transaction->sub_type = "Voucher";
                            $ex_transaction->reason = $voucher->voucher_no ." Journal";
                            $ex_transaction->date =  $request->p_date;
                            $ex_transaction->relation_id = $voucher->id;
                            $ex_transaction->sub_related_id = $detail->id;
                            $ex_transaction->relation_with = "Voucher";
                            $ex_transaction->save();


                        }else{
                            $transaction = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('sub_related_id', $detail->id)->first();

                            if($transaction == null){
                                $transaction = New AccountTransaction;
                            }
                            $transaction->amount = $request->cr_old_amount[$k];
                            $transaction->account_id =  $request->old_ledgers[$k];
                            $transaction->type = "credit";
                            $transaction->sub_type = "Voucher";
                            $transaction->reason = $voucher->voucher_no ." Journal";
                            $transaction->date =  $request->p_date;
                            $transaction->relation_id = $voucher->id;
                            $transaction->sub_related_id = $detail->id;
                            $transaction->relation_with = "Voucher";
                            $transaction->save();
                        }
                        $j_i++;
                    }
                }

                if($request->del_ledgers){
                    foreach($request->del_ledgers as $k=>$a_id){
                        $detail = VoucherDetail::find($a_id);
                        $trans = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('sub_related_id', $detail->id)->first();

                        if($trans == null){
                            $trans->delete();
                        }
                        $detail->delete();
                    }
                }
                if($request->ledgers){
                    foreach($request->dr_amount as $k=>$dr_a){
                        $detail = new VoucherDetail;
                        $detail->voucher_id = $voucher->id;
                        $detail->ledger_id = $request->ledgers[$k];
                        $detail->debit = $dr_a;
                        $detail->credit = $request->cr_amount[$k];
                        $detail->save();
                        if($dr_a > 0){
                            $ex_transaction = new AccountTransaction();
                            $ex_transaction->amount = $dr_a;
                            $ex_transaction->account_id =  $request->ledgers[$k];
                            $ex_transaction->type = "debit";
                            $ex_transaction->sub_type = "Voucher";
                            $ex_transaction->reason = $voucher->voucher_no ." Journal";
                            $ex_transaction->date =  $request->p_date;
                            $ex_transaction->relation_id = $voucher->id;
                            $ex_transaction->sub_related_id = $detail->id;
                            $ex_transaction->relation_with = "Voucher";
                            $ex_transaction->save();
                        }else{
                            $transaction = new AccountTransaction();
                            $transaction->amount = $request->cr_amount[$k];
                            $transaction->account_id =  $request->ledgers[$k];
                            $transaction->type = "credit";
                            $transaction->sub_type = "Voucher";
                            $transaction->reason = $voucher->voucher_no ." Journal";
                            $transaction->date =  $request->p_date;
                            $transaction->relation_id = $voucher->id;
                            $transaction->sub_related_id = $detail->id;
                            $transaction->relation_with = "Voucher";
                            $transaction->save();
                        }
                        $j_i++;
                    }
                }
            }
            else{
                $voucher = Voucher::find($id);
                $voucher->v_type = $request->v_type;
                $voucher->voucher_date = $request->trans_date;
                $voucher->trans_from = $request->trans_from;
                $voucher->trans_to = $request->trans_to;
                $voucher->voucher_amount = $request->trans_amount;
                $voucher->ref =  $request->ref;
                $voucher->description =  $request->description;
                $voucher->save();

                $transaction = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('type', 'credit')->first();

                if($transaction == null){
                    $transaction = New AccountTransaction;
                }
                $transaction->amount = $request->trans_amount;
                $transaction->account_id =  $request->trans_to;
                $transaction->type = "credit";
                $transaction->sub_type = "Voucher";
                $transaction->reason = $voucher->voucher_no ." Contra";
                $transaction->date =  $request->trans_date;
                $transaction->relation_id = $voucher->id;
                $transaction->relation_with = "Voucher";
                $transaction->save();

                $ex_transaction = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->where('type', 'debit')->first();

                if($ex_transaction == null){
                    $ex_transaction = New AccountTransaction;
                }
                $ex_transaction->amount = $request->trans_amount;
                $ex_transaction->account_id =  $request->trans_from;
                $ex_transaction->type = "debit";
                $ex_transaction->sub_type = "Voucher";
                $ex_transaction->reason = $voucher->voucher_no ." Contra";
                $ex_transaction->date =  $request->trans_date;
                $ex_transaction->relation_id = $voucher->id;
                $ex_transaction->relation_with = "Voucher";
                $ex_transaction->save();
            }
            DB::commit();
            return response()->json([
                'status'=>'yes',
                'msg'=>'Voucher Updated Successfully'
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e);
            return response()->json([
                'status'=>'no',
                'msg'=>$e->getMessage()
            ]);
        }
    }
    public function destroy(Request $request)
    {
        //dd($request);
        try{
            $voucher =  Voucher::find($request->v_id);
            $details = $voucher->details;
            $trans_items = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->get();
            if($details->count() > 0){
                foreach($details as $detail){
                    $detail->delete();
                }
            }
            if($trans_items->count() > 0){
                foreach($trans_items as $trans_item){
                    $trans_item->delete();
                }
            }
            $voucher->delete();

            return response()->json([
                'status'=>'yes',
                'msg'=>'Voucher Deleted Successfully'
            ]);
        }catch(\Exception $e){
            //DB::rollBack();
            return response()->json([
                'status'=>'no',
                'msg'=>$e->getMessage()
            ]);
        }
    }

    function exportDownload(Request $request){
        $data['business'] = $business = auth()->user()->business;

        $data['voucher']=$voucher =  Voucher::find($request->voucher_id);
        $data['ledger_list']=$ledger_list = AccountTransaction::where('relation_id',$voucher->id)->where('relation_with','Voucher')->get();

        // $data['signature']=$signature = Signature::where('place_at','Student Fee Receipt Bottom')->get();

        $data['in_word']=$this->numToWordsRec($voucher->voucher_amount);



        $html= view('Accounts.voucher.voucher_pdf',$data);

        // return $html;
        $mpdf = new mPDF([
            'mode' => 'UTF-8',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' =>0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
            // 'orientation'=>'L'
        ]);
         //For Multilanguage Start
         $mpdf->autoScriptToLang = true;
         $mpdf->baseScript = 1;
         $mpdf->autoLangToFont = true;
         $mpdf->autoVietnamese = true;
         $mpdf->autoArabic = true;

         //For Multilanguage End
         $mpdf->setAutoTopMargin = 'stretch';
         $mpdf->setAutoBottomMargin = 'stretch';
         $mpdf->writeHTML($html);
         $name = 'voucher' . date('Y-m-d i:h:s');
         $mpdf->Output($name.'.pdf', 'D');


    }
    function numToWordsRec($number) {

        $words = array(
            0=>'',1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight',
            9 => 'Nine', 10 => 'Ten', 11 => 'Eleven',
            12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'seventy', 80 => 'Eighty',
            90 => 'Ninety'
        );


        if ($number < 20) {
            return $words[$number];
        }

        else if ($number < 100) {
            return $words[10 * floor($number / 10)] .
                ' ' . $words[$number % 10];
        }

        else if ($number < 1000) {
            return $words[floor($number / 100)] . ' Hundred '
                . $this->numToWordsRec($number % 100);
        }

        else if ($number < 1000000) {
            return $this->numToWordsRec(floor($number / 1000)) .
                ' Thousand ' . $this->numToWordsRec($number % 1000);
        }else{
            return $this->numToWordsRec(floor($number / 1000000)) .
            ' Million ' . $this->numToWordsRec($number % 1000000);
        }


    }
}
