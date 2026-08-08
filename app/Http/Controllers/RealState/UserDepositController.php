<?php

namespace App\Http\Controllers\RealState;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Property;
use App\Models\DepositPayment;
use App\Models\Member;
use App\Models\Account\AccountHead;
use App\Models\Account\PaymentMethod;
use App\Models\Account\AccountTransaction;
use App\Models\OnlinePaymentSetting;
use App\Library\SslCommerz\SslCommerzNotification;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserDepositPaymentExport;
use App\Models\Inventory\Payment;
use App\Models\Hr\SalarySheet;
use App\Models\Hr\BonusPay;
use App\Models\Hr\EmpLoan;
use App\Models\Account\Expense;
use App\Models\Account\Voucher;
use App\Models\Account\VoucherDetail;
class UserDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view ('RealState.user_deposit.manage');
    }
    function ajaxDepositPayment(Request $request){
        $user = auth()->user();
        $from_date = $request->form_date;
        $to_date = $request->to_date;
        $deposit_payments = DepositPayment::leftJoin("properties","properties.id","deposit_payments.land_plot_id")
        ->leftJoin("payment_methods","payment_methods.id","deposit_payments.payment_method_id")
        ->where("deposit_payments.member_id",$user->member_id);
        if($from_date){
            $deposit_payments = $deposit_payments->whereBetween('deposit_payments.payment_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        }
        if($request->land){
            $deposit_payments = $deposit_payments->where('deposit_payments.land_plot_id', $request->land);
        }
        if($request->payment_method){
            $deposit_payments = $deposit_payments->where('deposit_payments.payment_method_id', $request->payment_method);
        }

        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 10;
        }
        // dd($per_page);
        $deposit_payments = $deposit_payments->select("deposit_payments.*","properties.name as p_name","payment_methods.name as m_name")->orderBy('id','DESC')->paginate($per_page);

        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['land']=$request->land;
        $data['payment_method']=$request->payment_method;
        $data['land_text']=$request->land_text;
        $data['payment_method_text']=$request->payment_method_text;
        $data['deposit_payments'] =  $deposit_payments;
        $data['per_page']=$per_page;
        $html= view('RealState.user_deposit.ajax-user-deposit',$data)->render();
        return response()->json([
            'status'=>'yes',
            'html'=>$html
        ]);

    }
    function export(Request $request){
        $user = auth()->user();
         $search_list=[];
        //  dd($request->all());
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $arr_lable = [
            'label'=>'From Date',
            'val'=>$from_date
        ];
        $search_list[]=  $arr_lable;
        $arr_lable = [
            'label'=>'To Date',
            'val'=>$to_date
        ];
        $search_list[]=  $arr_lable;

        $deposit_payments = DepositPayment::leftJoin("properties","properties.id","deposit_payments.land_plot_id")
        ->leftJoin("payment_methods","payment_methods.id","deposit_payments.payment_method_id")
        ->where("deposit_payments.member_id",$user->member_id);
        if($from_date){
            $deposit_payments = $deposit_payments->whereBetween('deposit_payments.payment_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        }
        if($request->p_land){
            $arr_lable = [
                'label'=>'Land',
                'val'=>$request->p_land_text
            ];
            $search_list[]=  $arr_lable;
            $deposit_payments = $deposit_payments->where('deposit_payments.land_plot_id', $request->p_land);
        }
        
        if($request->p_payment_method){
            $arr_lable = [
                'label'=>'Payment Method',
                'val'=>$request->p_payment_method_text
            ];
            $search_list[]=  $arr_lable;
            $deposit_payments = $deposit_payments->where('deposit_payments.payment_method_id', $request->p_payment_method);
        }
        $deposit_payments = $deposit_payments->select("deposit_payments.*","properties.name as p_name","payment_methods.name as m_name")->orderBy('id','DESC')->get();

        if($request->type == "print"){
            $data['search_list']=$search_list;
            $data['deposit_payments']=$deposit_payments;
            return view('RealState.user_deposit.user-deposit-print', $data);
        }else if($request->type == "pdf"){
            $data['search_list']=$search_list;
            $data['deposit_payments']=$deposit_payments;
            $mpdf = new mPDF([
                'mode' => 'UTF-8',
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 5,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0,
            ]);
            $html= view('RealState.user_deposit.user-deposit-pdf', $data);
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
            $name = 'deposit_payment_' . date('Y-m-d i:h:s');
            $mpdf->Output($name.'.pdf', 'D');
        }else{
            $name = 'deposit_payment_ ' . date('Y-m-d i:h:s');
            $data = Excel::download(new UserDepositPaymentExport($deposit_payments,$search_list, $from_date, $to_date,$user->business), $name . '.xlsx');
            ob_end_clean();
            return $data;
        }
    }
    function init_account(){
        $salesHead = AccountHead::where("title",'Deposit Payment')->first();
        if($salesHead == null){
            $salesHead = new AccountHead;
            $salesHead->title = "Deposit Payment";
            $salesHead->code = '4505';
            $salesHead->sys = 0;
            $salesHead->ac_type = 4;
            $salesHead->note = '';
            $salesHead->status = 1;
            $salesHead->save();
        }
    }
    public function create()
    {
        $this->init_account();
        $data['payment_setting'] = OnlinePaymentSetting::firstOrNew();
        return view ('RealState.user_deposit.create',$data);
    }
    public function store(Request $request)
    {
        
        //  dd( config());
        //dd($request->all());
        if($request->payment_method_status == 0){
            $this->validate($request,[
                'payment_date'=>['required'],
                'land_plot'=>['required'],
                'deposit_amount'=>['required'],
                'payment_method'=>['required'],
                'account'=>['required'],
            ]);
        }else{
            $this->validate($request,[
                'payment_date'=>['required'],
                'land_plot'=>['required'],
                'deposit_amount'=>['required'],
            ]);
        }
        

        try{
            DB::beginTransaction();
            $user = auth()->user();
           
            if($request->payment_method_status == 0){
                $deposit_payment = new DepositPayment();
                $deposit_payment->payment_date = $request->payment_date;
                $deposit_payment->land_plot_id = $request->land_plot ?? '';
                $deposit_payment->member_id = $user->member_id ?? 0;
                $deposit_payment->deposit_amount = $request->deposit_amount ?? 0;
                $deposit_payment->payment_status = 1;
                $deposit_payment->comments = $request->comment ?? '';
                $deposit_payment->payment_method_id = $request->payment_method ?? 0;
                $deposit_payment->account_id = $request->account ?? 0;
                $deposit_payment->save();

                $salesHead = AccountHead::where("title",'Deposit Payment')->first();

                $sc_trans = New AccountTransaction;
                $sc_trans->amount = round($request->deposit_amount,2);
                $sc_trans->account_id = $salesHead->id;
                $sc_trans->type = "credit";
                $sc_trans->sub_type = "Deposit Payment";
                $sc_trans->reason = "Deposit pay from member";
                $sc_trans->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
                $sc_trans->relation_id = $data->id;
                $sc_trans->relation_with = "Deposit Payment";
                $sc_trans->save();

                $balance_account = BalanceAccount::find($request->account);
                $pay_trans = New AccountTransaction;
                $pay_trans->amount = round($request->deposit_amount,2);
                $pay_trans->account_id = $balance_account->account_head_id;
                $pay_trans->type = "debit";
                $pay_trans->sub_type = "Deposit Payment";
                $pay_trans->reason = "Sales Payment";
                $pay_trans->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
                $pay_trans->relation_id = $data->id;
                $pay_trans->relation_with = "Deposit Payment";
                $pay_trans->trans_id = $sc_trans->id;
                $pay_trans->save();

                $sc_trans->trans_id = $pay_trans->id;
                $sc_trans->save();
            }else{
                $deposit_payment = new DepositPayment();
                $deposit_payment->payment_date = $request->payment_date;
                $deposit_payment->land_plot_id = $request->land_plot ?? '';
                $deposit_payment->member_id = $user->member_id ?? 0;
                $deposit_payment->deposit_amount = $request->deposit_amount ?? 0;
                $deposit_payment->payment_status = 0;
                $deposit_payment->comments = $request->comment ?? '';
                $deposit_payment->save();
            }

           

            DB::commit();
           
            $res_pay = $this->pay($deposit_payment,$user->member_id,$user->business);
            if($res_pay != ""){
                $notification=array(
                    'message'=>$res_pay,
                    'alert-type'=>'error'
                );

                return redirect()->back()->with($notification)->withInput($request->all());
            }
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            $notification=array(
                'message'=>"Save Failed",
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    
    function destroy($id){
        $deposit_payment = DepositPayment::find($id);
        if($deposit_payment->payment_status == 0){
            $deposit_payment->delete();
            $notification=array(
                'message'=>"Deposit Deleted successfully.",
                'alert-type'=>'success'
            );
            return redirect()->route("user_deposit.index")->with($notification);
        }else{
            $notification=array(
                'message'=>"Deposit Payment Status  is paid",
                'alert-type'=>'error'
            );
            return redirect()->route("user_deposit.index")->with($notification);
        }
    }
    function pay($deposit_payment,$member_id,$business){
        $payment_setting = OnlinePaymentSetting::firstOrNew();

        $payment_setting = OnlinePaymentSetting::firstOrNew();
        $apiDomain = $payment_setting->mode == 0 ? "https://sandbox.sslcommerz.com" : "https://securepay.sslcommerz.com";
        config()->set([
            'sslcommerz.' . "apiCredentials" => [
                'store_id' => $payment_setting->store_id,
                'store_password' => $payment_setting->store_password,
            ],
            'sslcommerz.' . "apiDomain" => $apiDomain,
            'sslcommerz.' . "connect_from_localhost" => true,
        ]);

        $member = Member::leftJoin("countries","countries.id","members.country_id")
        ->leftJoin("states","states.id","members.state_id")
        ->leftJoin("cities","cities.id","members.city_id")
        ->select("members.*","countries.name as country_name","states.name as state_name" ,"cities.name as city_name")
        ->where("members.id",$member_id)->first();
        if( $business->currency){
            $currency = $business->currency->name;
        }else{
            $currency = "BDT";
        }
        $post_data = array();
        $post_data['total_amount'] = $deposit_payment->deposit_amount; # You cant not pay less than 10
        $post_data['currency'] = $currency;
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        $post_data['multi_card_name'] = "visacard"; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $member->name;
        $post_data['cus_email'] =  $member->email ?  $member->email : "test@gmail.com";
        $post_data['cus_add1'] =  $member->address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = $member->city_name;
        $post_data['cus_state'] = $member->state_name;
        $post_data['cus_postcode'] = $member->zipcode;
        $post_data['cus_country'] = $member->country_name;
        $post_data['cus_phone'] = $member->mobile;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        // $post_data['ship_name'] = "Store Test";
        // $post_data['ship_add1'] = "Dhaka";
        // $post_data['ship_add2'] = "Dhaka";
        // $post_data['ship_city'] = "Dhaka";
        // $post_data['ship_state'] = "Dhaka";
        // $post_data['ship_postcode'] = "1000";
        // $post_data['ship_phone'] = "";
        // $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Deposit Amount";
        $post_data['product_category'] = "topup";
        $post_data['product_profile'] = "non-physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $deposit_payment->id;
        $post_data['value_b'] = "User Deposit";

        

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            return $payment_options;
            print_r($payment_options);
            $payment_options = array();
        }
        return "";

    }
   
    public function success(Request $request)
    {
        //   dd($request->all());
        try{
            DB::beginTransaction();
            
           
            if($request->card_brand == "VISA" || $request->card_brand == "Mastercard" || $request->card_brand == "American Express"){
                $card_type = "Bank";
                $payment_method =  PaymentMethod::where("name",$card_type)->first();
                if($payment_method == null){
                    $payment_method = new PaymentMethod();
                    $payment_method->name = $card_type;
                    $payment_method->save();
                }
                $pay_head = AccountHead::where("title",$card_type)->first();
                if($pay_head == null){
                    $pay_head = new AccountHead();
                    $pay_head->title = $card_type;
                    $pay_head->sys = 0;
                    $pay_head->ac_type = 1;
                    $pay_head->note = '';
                    $pay_head->status = 1;
                    $pay_head->save();
                }
            }else{
                $card_type = $request->card_issuer;
                $payment_method =  PaymentMethod::where("name",$card_type)->first();
                if($payment_method == null){
                    $payment_method = new PaymentMethod();
                    $payment_method->name = $card_type;
                    $payment_method->save();
                }
                
                $pay_head = AccountHead::where("title",$card_type)->first();
                if($pay_head == null){
                     $pay_head = new AccountHead();
                    $pay_head->title = $card_type;
                    $pay_head->sys = 0;
                    $pay_head->ac_type = 1;
                    $pay_head->note = '';
                    $pay_head->status = 1;
                    $pay_head->save();
                }
                
            }
            if($request->value_b == "Debit Voucher"){
                $voucher=Voucher::find($request->value_a);
                $voucher->status = 1;
                $voucher->voucher_by = $pay_head->id;
                $voucher->fund_id = $payment_method->id;
                $voucher->save();

                $p_date = $voucher->voucher_date == null ?  date('Y-m-d') :  Carbon::parse($voucher->voucher_date)->format('Y-m-d');
                $acc_amount = round($voucher->voucher_amount,2);
                $ref_id = $voucher->id;
                
                $ref_type = "Voucher";
                $ref_with = "Voucher";
                $ref_des = $voucher->voucher_no ." Voucher Payment";

                $sc_trans = New AccountTransaction;
                $sc_trans->amount = $acc_amount;
                $sc_trans->account_id = $pay_head->id;
                $sc_trans->type = "credit";
                $sc_trans->sub_type = $ref_type;
                $sc_trans->reason = $ref_des;
                $sc_trans->date = $p_date;
                $sc_trans->relation_id = $ref_id;
                $sc_trans->relation_with = $ref_with;
                $sc_trans->save();
                foreach($voucher->details as $detail){
                    $pay_trans = New AccountTransaction;
                    $pay_trans->amount = $detail->debit;
                    $pay_trans->account_id = $detail->ledger_id;
                    $pay_trans->type = "debit";
                    $pay_trans->sub_type = $ref_type;
                    $pay_trans->reason =  $ref_des;
                    $pay_trans->date = $p_date;
                    $pay_trans->relation_id = $voucher->id;
                    $pay_trans->sub_related_id = $detail->id;
                    $pay_trans->relation_with = $ref_with;
                    $pay_trans->trans_id = $sc_trans->id;
                    $pay_trans->save();
                }
                
            }
            else if($request->value_b == "Credit Voucher"){
                $voucher=Voucher::find($request->value_a);
                $voucher->status = 1;
                $voucher->voucher_by = $pay_head->id;
                $voucher->fund_id = $payment_method->id;
                $voucher->save();

                $p_date = $voucher->voucher_date == null ?  date('Y-m-d') :  Carbon::parse($voucher->voucher_date)->format('Y-m-d');
                $acc_amount = round($voucher->voucher_amount,2);
                $ref_id = $voucher->id;
                $ref_type = "Voucher";
                $ref_with = "Voucher";
                $ref_des = $voucher->voucher_no ." Voucher Payment";

                $sc_trans = New AccountTransaction;
                $sc_trans->amount = $acc_amount;
                $sc_trans->account_id = $pay_head->id;
                $sc_trans->type = "debit";
                $sc_trans->sub_type = $ref_type;
                $sc_trans->reason = $ref_des;
                $sc_trans->date = $p_date;
                $sc_trans->relation_id = $ref_id;
                $sc_trans->relation_with = $ref_with;
                $sc_trans->save();
                foreach($voucher->details as $detail){
                    $pay_trans = New AccountTransaction;
                    $pay_trans->amount = $detail->debit;
                    $pay_trans->account_id = $detail->ledger_id;
                    $pay_trans->type = "credit";
                    $pay_trans->sub_type = $ref_type;
                    $pay_trans->reason =  $ref_des;
                    $pay_trans->date = $p_date;
                    $pay_trans->relation_id = $voucher->id;
                    $pay_trans->sub_related_id = $detail->id;
                    $pay_trans->relation_with = $ref_with;
                    $pay_trans->trans_id = $sc_trans->id;
                    $pay_trans->save();
                }
                
            }
            else{
                if($request->value_b == "Deposit" || $request->value_b == "User Deposit"){
                    $deposit_payment = DepositPayment::find($request->value_a);
                    $deposit_payment->payment_status = 1;
                    $deposit_payment->account_id = $pay_head->id;
                    $deposit_payment->payment_method_id = $payment_method->id;
                    $deposit_payment->save();
                    $p_date = $deposit_payment->payment_date == null ?  date('Y-m-d') :  Carbon::parse($deposit_payment->payment_date)->format('Y-m-d');
                    $acc_amount = round($deposit_payment->deposit_amount,2);
                    $ref_id = $deposit_payment->id;
                    $ref_type = "Deposit Payment";
                    $ref_with = "Deposit Payment";
                    $ref_des = "Deposit pay from member";
                    $credit_head = AccountHead::where("title",'Deposit Payment')->first();
                    $debit_head = $pay_head;
                }else if($request->value_b == "Salary"){
                    $payment = Payment::find($request->value_a);
                    // dd($payment);
                    $payment->status = 1;
                    $payment->bank_account_id = $pay_head->id;
                    $payment->payment_method = $payment_method->id;
                    $payment->save();
                    $SalarySheet=SalarySheet::find($payment->relation_id);
                    $SalarySheet->paidSalary = $SalarySheet->paidSalary+round($payment->amount,2);
                    $SalarySheet->due_amount = $SalarySheet->netSalary - $SalarySheet->paidSalary;
                    if($SalarySheet->due_amount == 0){
                        $SalarySheet->payment_status = 2;
                    }else{
                        $SalarySheet->payment_status = 1;
                    }
                    $SalarySheet->save();
                    $p_date = $payment->date == null ?  date('Y-m-d') :  Carbon::parse($payment->date)->format('Y-m-d');
                    $acc_amount = round($payment->amount,2);
                    $ref_id = $payment->id;
                    $ref_type = "Salary Payment";
                    $ref_with = "Salary";
                    $ref_des = "Salary Payment For Employee ";
                    $debit_head = AccountHead::where("title",'Salary')->first();
                    $credit_head = $pay_head;
                }
                else if($request->value_b == "Employee Bonus"){
                    $bonusPay=BonusPay::find($request->value_a);
                    $bonusPay->payment_status = 1;
                    $bonusPay->balance_account_id = $pay_head->id;
                    $bonusPay->paidMethod = $payment_method->id;
                    $bonusPay->save();

                    $p_date = $bonusPay->paidDate == null ?  date('Y-m-d') :  Carbon::parse($bonusPay->paidDate)->format('Y-m-d');
                    $acc_amount = round($bonusPay->bonusAmount,2);
                    $ref_id = $bonusPay->id;
                    $ref_type = "Bonus Pay";
                    $ref_with = "Bonus";
                    $ref_des = $bonusPay->reference_no ." Bonus Payment";
                    $debit_head = AccountHead::where("title",'Employee Bonus')->first();
                    $credit_head = $pay_head;
                }
                else if($request->value_b == "Employee Loan"){
                    $empLoan=EmpLoan::find($request->value_a);
                    $empLoan->payment_status = 1;
                    $empLoan->balance_account_id = $pay_head->id;
                    $empLoan->method_id = $payment_method->id;
                    $empLoan->save();

                    $p_date = $empLoan->paidDate == null ?  date('Y-m-d') :  Carbon::parse($empLoan->paidDate)->format('Y-m-d');
                    $acc_amount = round($empLoan->amount,2);
                    $ref_id = $empLoan->id;
                    if($request->value_c == "Loan"){
                        $ref_type = "Employee Loan Pay";
                        $ref_with = "Employee Loan";
                        $ref_des = $empLoan->reference_no ." Employee Loan Payment";
                        $debit_head = AccountHead::where("title",'Employee Loan')->first();
                        $credit_head = $pay_head;
                    }else{
                        $ref_type = "Employee Loan Return";
                        $ref_with = "Employee Loan";
                        $ref_des = $empLoan->reference_no ." Employee Loan Return Payment";
                        $credit_head = AccountHead::where("title",'Employee Bonus')->first();
                        $debit_head = $pay_head;
                    }
                    
                }
                else if($request->value_b == "Expense"){
                    $expense=Expense::find($request->value_a);
                    $expense->payment_status = 1;
                    $expense->balance_account_id = $pay_head->id;
                    $expense->method_id = $payment_method->id;
                    $expense->save();

                    $p_date = $expense->date == null ?  date('Y-m-d') :  Carbon::parse($expense->date)->format('Y-m-d');
                    $acc_amount = round($expense->amount,2);
                    $ref_id = $expense->id;
                    
                    $ref_type = "Expense";
                    $ref_with = "Expense";
                    $ref_des = $expense->reference_no ." Expense Payment";
                    $debit_head = AccountHead::where('expense_id',$expense->category_id)->first();
                    $credit_head = $pay_head; 
                }
                $sc_trans = New AccountTransaction;
                $sc_trans->amount = $acc_amount;
                $sc_trans->account_id = $credit_head->id;
                $sc_trans->type = "credit";
                $sc_trans->sub_type = $ref_type;
                $sc_trans->reason = $ref_des;
                $sc_trans->date = $p_date;
                $sc_trans->relation_id = $ref_id;
                $sc_trans->relation_with = $ref_with;
                $sc_trans->save();
                
                $pay_trans = New AccountTransaction;
                $pay_trans->amount = $acc_amount;
                $pay_trans->account_id = $debit_head->id;
                $pay_trans->type = "debit";
                $pay_trans->sub_type = $ref_type;
                $pay_trans->reason =  $ref_des;
                $pay_trans->date = $p_date;
                $pay_trans->relation_id = $ref_id;
                $pay_trans->relation_with = $ref_with;
                $pay_trans->trans_id = $sc_trans->id;
                $pay_trans->save();

                $sc_trans->trans_id = $pay_trans->id;
                $sc_trans->save();
            }
            

           
            DB::commit();
            $notification=array(
                'message'=>"Payment successfully.",
                'alert-type'=>'success'
            );
            if($request->value_b == "Deposit"){
                return redirect()->route("deposit.index")->with($notification);
            }else if($request->value_b == "User Deposit"){
                return redirect()->route("user_deposit.index")->with($notification);
            }
            else if($request->value_b == "Salary"){
                return redirect()->route("manageSalary")->with($notification);
            }
            else if($request->value_b == "Employee Bonus"){
                return redirect()->route("bonuspay.view")->with($notification);
            }
            else if($request->value_b == "Employee Loan"){
                return redirect()->route("emploan.view")->with($notification);
            }
            else if($request->value_b == "Expense"){
                return redirect()->route("expense.index")->with($notification);
            }
            else if($request->value_b == "Debit Voucher"){
                return redirect()->route("debit_vouchar.create")->with($notification);
            }
            else if($request->value_b == "Credit Voucher"){
                return redirect()->route("credit_vouchar.create")->with($notification);
            }
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            $notification=array(
                'message'=>"Something Went Wrong!",
                'alert-type'=>'error'
            );
            if($request->value_b == "User Deposit"){
                $deposit_payment = DepositPayment::find($request->value_a);
                $deposit_payment->delete();
                return redirect()->route("user_deposit.create")->with($notification);
            }else if($request->value_b == "Deposit"){
                $deposit_payment = DepositPayment::find($request->value_a);
                $deposit_payment->delete();
                return redirect()->route("deposit.create")->with($notification);
            }else if($request->value_b == "Salary"){
                $deposit_payment = Payment::find($request->value_a);
                $deposit_payment->delete();
                return redirect()->route("manageSalary")->with($notification);
            }
            else if($request->value_b == "Employee Bonus"){
                $deposit_payment = BonusPay::find($request->value_a);
                $deposit_payment->delete();
                return redirect()->route("bonuspay.view")->with($notification);
            }
            else if($request->value_b == "Employee Loan"){
                $deposit_payment = EmpLoan::find($request->value_a);
                $deposit_payment->delete();
                return redirect()->route("emploan.view")->with($notification);
            } 
            else if($request->value_b == "Expense"){
                $deposit_payment = Expense::find($request->value_a);
                $deposit_payment->delete();
                return redirect()->route("expense.index")->with($notification);
            }
            else if($request->value_b == "Debit Voucher"){
                $voucher = Voucher::find($request->value_a);
                foreach($voucher->details as $detail){
                    $detail->delete();
                }
                $voucher->delete();
                return redirect()->route("debit_vouchar.create")->with($notification);
            }
            else if($request->value_b == "Credit Voucher"){
                $voucher = Voucher::find($request->value_a);
                foreach($voucher->details as $detail){
                    $detail->delete();
                }
                $voucher->delete();
                return redirect()->route("credit_vouchar.create")->with($notification);
            }
        
        }
    }
    public function fail(Request $request)
    {
        $notification=array(
            'message'=>"Payment Canceled",
            'alert-type'=>'failed'
        );
        if($request->value_b == "User Deposit"){
            $deposit_payment = DepositPayment::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("user_deposit.create")->with($notification);
        }else if($request->value_b == "Deposit"){
            $deposit_payment = DepositPayment::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("deposit.create")->with($notification);
        }else if($request->value_b == "Salary"){
            $deposit_payment = Payment::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("manageSalary")->with($notification);
        }
        else if($request->value_b == "Employee Bonus"){
            $deposit_payment = BonusPay::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("bonuspay.view")->with($notification);
        }
        else if($request->value_b == "Employee Loan"){
            $deposit_payment = EmpLoan::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("emploan.view")->with($notification);
        }
        else if($request->value_b == "Expense"){
            $deposit_payment = Expense::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("expense.index")->with($notification);
        }
        else if($request->value_b == "Debit Voucher"){
            $voucher = Voucher::find($request->value_a);
            foreach($voucher->details as $detail){
                $detail->delete();
            }
            $voucher->delete();
            return redirect()->route("debit_vouchar.create")->with($notification);
        }
        else if($request->value_b == "Credit Voucher"){
            $voucher = Voucher::find($request->value_a);
            foreach($voucher->details as $detail){
                $detail->delete();
            }
            $voucher->delete();
            return redirect()->route("credit_vouchar.create")->with($notification);
        }
        
    }
    public function cancel(Request $request)
    {
        $notification=array(
            'message'=>"Payment Canceled",
            'alert-type'=>'error'
        );
        if($request->value_b == "User Deposit"){
            $deposit_payment = DepositPayment::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("user_deposit.create")->with($notification);
        }else if($request->value_b == "Deposit"){
            $deposit_payment = DepositPayment::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("deposit.create")->with($notification);
        }else if($request->value_b == "Salary"){
            $deposit_payment = Payment::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("manageSalary")->with($notification);
        }
        else if($request->value_b == "Employee Bonus"){
            $deposit_payment = BonusPay::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("bonuspay.view")->with($notification);
        }
        else if($request->value_b == "Employee Loan"){
            $deposit_payment = EmpLoan::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("emploan.view")->with($notification);
        }
        else if($request->value_b == "Expense"){
            $deposit_payment = Expense::find($request->value_a);
            $deposit_payment->delete();
            return redirect()->route("expense.index")->with($notification);
        }
        else if($request->value_b == "Debit Voucher"){
            $voucher = Voucher::find($request->value_a);
            foreach($voucher->details as $detail){
                $detail->delete();
            }
            $voucher->delete();
            return redirect()->route("debit_vouchar.create")->with($notification);
        }
        else if($request->value_b == "Credit Voucher"){
            $voucher = Voucher::find($request->value_a);
            foreach($voucher->details as $detail){
                $detail->delete();
            }
            $voucher->delete();
            return redirect()->route("credit_vouchar.create")->with($notification);
        }
    }
}