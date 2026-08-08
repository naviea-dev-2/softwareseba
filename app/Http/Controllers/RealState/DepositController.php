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
use App\Exports\DepositPaymentExport;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view ('RealState.deposit.manage');
    }
    function ajaxDepositPayment(Request $request){
        $user = auth()->user();
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        // $from_date = $request->form_date;
        // $to_date = $request->to_date;
        $deposit_payments = DepositPayment::leftJoin("properties","properties.id","deposit_payments.land_plot_id")
        ->leftJoin("payment_methods","payment_methods.id","deposit_payments.payment_method_id")
        ->leftJoin("members","members.id","deposit_payments.member_id");
        if($from_date){
            $deposit_payments = $deposit_payments->whereBetween('deposit_payments.payment_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        }
        if($request->land){
            $deposit_payments = $deposit_payments->where('deposit_payments.land_plot_id', $request->land);
        }
        if($request->member){
            $deposit_payments = $deposit_payments->where('deposit_payments.member_id', $request->member);
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
        $deposit_payments = $deposit_payments->select("deposit_payments.*","properties.name as p_name","members.name as m_name","members.mobile as m_mobile","payment_methods.name as method_name")->orderBy('id','DESC')->paginate($per_page);

        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['land']=$request->land;
        $data['member']=$request->member;
        $data['payment_method']=$request->payment_method;
        $data['land_text']=$request->land_text;
        $data['member_text']=$request->member_text;
        $data['payment_method_text']=$request->payment_method_text;
        $data['deposit_payments'] =  $deposit_payments;
        $data['per_page']=$per_page;
        $html= view('RealState.deposit.ajax-deposit',$data)->render();
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
        ->leftJoin("members","members.id","deposit_payments.member_id");
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
        if($request->p_member){
            $arr_lable = [
                'label'=>'Member',
                'val'=>$request->p_member_text
            ];
            $search_list[]=  $arr_lable;
            $deposit_payments = $deposit_payments->where('deposit_payments.member_id', $request->p_member);
        }
        // dd($request->p_payment_method);
        if($request->p_payment_method){
            $arr_lable = [
                'label'=>'Payment Method',
                'val'=>$request->p_payment_method_text
            ];
            $search_list[]=  $arr_lable;
            $deposit_payments = $deposit_payments->where('deposit_payments.payment_method_id', $request->p_payment_method);
        }

        $deposit_payments = $deposit_payments->select("deposit_payments.*","properties.name as p_name","members.name as m_name","members.mobile as m_mobile","payment_methods.name as method_name")->orderBy('id','DESC')->get();

        if($request->type == "print"){
            $data['search_list']=$search_list;
            $data['deposit_payments']=$deposit_payments;
            return view('RealState.deposit.deposit-print', $data);
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
            $html= view('RealState.deposit.deposit-pdf', $data);
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
            $data = Excel::download(new DepositPaymentExport($deposit_payments,$search_list, $from_date, $to_date,$user->business), $name . '.xlsx');
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
        return view ('RealState.deposit.create',$data);
    }
    public function store(Request $request)
    {
        //dd($request->all());
        if($request->payment_method_status == 0){
            $this->validate($request,[
                'payment_date'=>['required'],
                'land_plot'=>['required'],
                'member'=>['required'],
                'deposit_amount'=>['required'],
                'payment_method'=>['required'],
                'account'=>['required'],
            ]);
        }else{
            $this->validate($request,[
                'payment_date'=>['required'],
                'land_plot'=>['required'],
                'member'=>['required'],
                'deposit_amount'=>['required'],
            ]);
        }
       

         try{
            DB::beginTransaction();
            
            if($request->payment_method_status == 0){
                $deposit_payment = new DepositPayment();
                $deposit_payment->payment_date = $request->payment_date;
                $deposit_payment->land_plot_id = $request->land_plot ?? 0;
                $deposit_payment->member_id = $request->member ?? 0;
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
                $sc_trans->relation_id = $deposit_payment->id;
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
                $pay_trans->relation_id = $deposit_payment->id;
                $pay_trans->relation_with = "Deposit Payment";
                $pay_trans->trans_id = $sc_trans->id;
                $pay_trans->save();

                $sc_trans->trans_id = $pay_trans->id;
                $sc_trans->save();
            }else{
                $deposit_payment = new DepositPayment();
                $deposit_payment->payment_date = $request->payment_date;
                $deposit_payment->land_plot_id = $request->land_plot ?? '';
                $deposit_payment->member_id = $request->member ?? 0;
                $deposit_payment->deposit_amount = $request->deposit_amount ?? 0;
                $deposit_payment->payment_status = 0;
                $deposit_payment->comments = $request->comment ?? '';
                $deposit_payment->save();
            }
            DB::commit();
            $user = auth()->user();
            $res_pay = $this->pay($deposit_payment,$request->member,$user->business);
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
    
    public function destroy(Request $request,$id)
    {
        
        try{
            DB::beginTransaction();
            $deposit_payment = DepositPayment::find($id);
            if($deposit_payment->payment_status == 0){
                $deposit_payment->delete();
                // $notification=array(
                //     'message'=>"Deposit Deleted successfully.",
                //     'alert-type'=>'success'
                // );
                
            }else{
                $trans = AccountTransaction::where("relation_id",$deposit_payment->id)->where("relation_with","Deposit Payment")->get();
                foreach($trans as $tran){
                    $tran->delete();
                }
                $deposit_payment->delete();
                // $notification=array(
                //     'message'=>"Deposit Deleted successfully.",
                //     'alert-type'=>'success'
                // );
            }
            DB::commit();
            $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('deposit.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );
            return redirect()->route('deposit.index')->with($notification);
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
        $post_data['value_b'] = "Deposit";

        

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
}