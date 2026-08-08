<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use Illuminate\Http\Request;
use App\Models\Hr\Department;
use App\Models\Hr\Employee;
use App\Models\Hr\Bank;
use App\Models\Hr\BankAccount;
use App\Models\Hr\Payroll;
use App\Models\Hr\BonusPay;
use App\Models\Hr\Designation;
use App\Models\Hr\EmpBankAccount;
use App\Models\Hr\SalarySheet;
use App\Models\OnlinePaymentSetting;
use Carbon\Carbon;
use App\User;
use App\Library\SslCommerz\SslCommerzNotification;
use Session;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class bonuspayController extends Controller
{

    public function calculation(){
        $data['newEmployees']=DB::table('employees')
                              ->join('designations','employees.designation_id','designations.id')
                              ->join('departments','employees.department_id','departments.id')
                              ->where('employees.rejineDate',null)
                              ->select('employees.*','departments.name as deptName','designations.name as desigName')
                              ->orderBy('employees.id','DESC')
                              ->get();

        $data['payrollsetup']=Payroll::first();
        return view ('Hr.bonuspay.bonusCalculationView',$data);
    }

    public function view(){
        if(can_p('bonuspay.view') == false){
            return redirect()->route('dashboard');
        }
        
        $data['departments']=Department::orderBy('id','DESC')->get();
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        $data['payment_setting'] = OnlinePaymentSetting::firstOrNew();
        return view ('Hr.bonuspay.manage',$data);
    }
    function ajaxBonus(Request $request){
        $columns = [
            0=>'bonus_pays.id',
            1=>'departments.name',
            2=>'designations.name',
            3=>'employees.employee_name',
            4=>'employees.paidDate',
            5>'employees.occation',
            6=>'employees.bonusAmount',
            7=>'payment_methods.name',
            8=>'bonus_pays.payment_status',
        ];

        $totalData = BonusPay::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = BonusPay::leftJoin('employees','employees.id','bonus_pays.empID')
                            ->leftJoin('payment_methods','payment_methods.id','bonus_pays.paidMethod')
                            ->leftJoin('balance_accounts','balance_accounts.id','bonus_pays.balance_account_id')
                            ->leftJoin('departments','departments.id','employees.department_id')
                            ->leftJoin('designations','designations.id','employees.designation_id');
        if(!empty($search)){

           $datalist =$datalist->where("employees.employee_name","LIKE","%{$search}%")
                    ->orWhere("payment_methods.name","LIKE","%{$search}%")
                    ->orWhere("balance_accounts.account_name","LIKE","%{$search}%")
                    ->orWhere("departments.name","LIKE","%{$search}%")
                    ->orWhere("designations.name","LIKE","%{$search}%")
                    ->orWhere("bonus_pays.bonusAmount","LIKE","%{$search}%")
                    ->orWhere("bonus_pays.paidDate","LIKE","%{$search}%")
                    ->orWhere("bonus_pays.occation","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->select('bonus_pays.*','payment_methods.name as p_name','balance_accounts.account_name','employees.employee_name','departments.name as depart_name','designations.name as desi_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('bonuspay.edit');
            $p_delete = can_p('bonuspay.delete');

            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i++;
                $nestedData['employee_name'] = $data_v->employee_name;
               
                $nestedData['department'] = $data_v->depart_name;
                $nestedData['designation'] = $data_v->desi_name;
                $nestedData['date'] =$data_v->paidDate;
                $nestedData['occation'] =$data_v->occation;
                $nestedData['method_name'] =$data_v->p_name;
                // $nestedData['account_name'] =$data_v->account_name;
                $nestedData['bonus_amount'] =auth()->user()->currency_symbol.' '. round($data_v->bonusAmount,2);
                $payment_status = '';
                if($data_v->payment_status == 0){
                    $payment_status ='<div class="badge bg-danger">Due</div>';
                }else{
                    $payment_status ='<div class="badge bg-secondary">Paid</div>';
                }
                $nestedData['status']=$payment_status;

                $nestedData['options']='';
                // if($p_edit){
                //     $nestedData['options'] .= '<a class="btn btn-primary" href="javascript:void(0)" data-token="'.csrf_token().'" id="bonuspayEdit" data-id="'.$data_v->id.'"><i class="bx bx-edit"></i></a>';
                // }
               
                if($p_delete){
                    $nestedData['options'] .= '<a data-id="'. $data_v->id .'" data-action="'.route('bonuspay.delete',$data_v->id).'" class="del_hr_data btn btn-danger" href="#"><i class="bx bx-trash"></i></a>';
                }
                
                $data[] = $nestedData;
            }
        }
        $json_data = [
           'draw' => intval($request->input('draw')),
           'recordsTotal' => intval($totalData),
           'recordsFiltered' => intval($totalFiltered),
           'data' => $data
        ];
   
        return response()->json($json_data);
    }

    // public function search(Request $request){

    //     $data['bonuspays']=BonusPay::orderBy('id','DESC')->where('',)->get();
    //     $data['departments']=Department::orderBy('id','DESC')->get();
    //     $data['banks']=Bank::all();
    //     return view ('HRandPayroll.bonuspay.manage',$data);
    // }

    public function search(Request $request){

        if($request->occation=='all'){
            $data['bonuspays']=BonusPay::orderBy('id','DESC')->where('paidDate','LIKE',$request->year.'%')->get();
        }
        else{
            $data['bonuspays']=BonusPay::orderBy('id','DESC')->where('occation', $request->occation)->where('paidDate','LIKE',$request->year.'%')->get();
        }
        //dd($request->all());
        $data['departments']=Department::orderBy('id','DESC')->get();
      
        return view ('Hr.bonuspay.manage',$data);
    }
    function inital_account(){
        $salesHead = AccountHead::where("title",'Employee Bonus')->first();
        if($salesHead == null){
            $salesHead = new AccountHead;
            $salesHead->title = "Employee Bonus";
            $salesHead->code = '5013';
            $salesHead->sys = 0;
            $salesHead->ac_type = 5;
            $salesHead->note = '';
            $salesHead->status = 1;
            $salesHead->save();

        }

    }
    public function store(Request $request){
       
        if($request->id==0){
            if(can_p('bonuspay.add') == false){
                $data["msg"] ='Add permision is not allowed';
                $data["error"] ='Add permision is not allowed';
                $data["status"] ="0";
                return response()->json($data);
                // return redirect()->route('dashboard');
            }
        }else{
            if(can_p('bonuspay.edit') == false){
                $data["msg"] ='Edit permision is not allowed';
                $data["error"] ='Edit permision is not allowed';
                $data["status"] ="0";
                return response()->json($data);
                return redirect()->route('dashboard');
            }
        }
        $this->inital_account();
        $payment_setting = OnlinePaymentSetting::firstOrNew();
        if($payment_setting->status != 1){
            if(array_search('accounts',load_pack_option()) != false){
                $validator = Validator::make($request->all(),[
                    // 'deptID'=>'required',
                    // 'desigID'=>'required',
                    'empID'=>'required',
                    'paidDate'=>'required',
                    'occation'=>'required',
                    'bonusAmount'=>'required',
                    'payment_method'=>'required',
                    'account'=>'required',
        
                ],[
                    // 'deptID.required'=> 'Department is required',
                    // 'desigID.required'=> 'Designation is required',
                    'empID.required'=> 'Employee is required',
                    'paidDate.required'=> 'Paid Date is required',
                    'occation.required'=> 'Occation is required',
                    'bonusAmount.required'=> 'Bonus Amount is required',
                    'payment_method.required'=> 'Paid Method is required',
                    'account.required'=> 'Account is required',
                ]);
            }else{
                $validator = Validator::make($request->all(),[
                    // 'deptID'=>'required',
                    // 'desigID'=>'required',
                    'empID'=>'required',
                    'paidDate'=>'required',
                    'occation'=>'required',
                    'bonusAmount'=>'required',
                    'payment_method'=>'required',
                ],[
                    // 'deptID.required'=> 'Department is required',
                    // 'desigID.required'=> 'Designation is required',
                    'empID.required'=> 'Employee is required',
                    'paidDate.required'=> 'Paid Date is required',
                    'occation.required'=> 'Occation is required',
                    'bonusAmount.required'=> 'Bonus Amount is required',
                    'payment_method.required'=> 'Paid Method is required',
                ]);
            }
        }else{
            $validator = Validator::make($request->all(),[
                // 'deptID'=>'required',
                // 'desigID'=>'required',
                'empID'=>'required',
                'paidDate'=>'required',
                'occation'=>'required',
                'bonusAmount'=>'required',
            ],[
                // 'deptID.required'=> 'Department is required',
                // 'desigID.required'=> 'Designation is required',
                'empID.required'=> 'Employee is required',
                'paidDate.required'=> 'Paid Date is required',
                'occation.required'=> 'Occation is required',
                'bonusAmount.required'=> 'Bonus Amount is required',
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
            $employee = Employee::where('id',$request->empID)->first();
            $BonusPay=new BonusPay();
            $BonusPay->reference_no = 'bns-' . date("Ymd") . '-'. date("his");
            $BonusPay->deptID=$employee->department_id;
            $BonusPay->desigID=$employee->designation_id;
            $BonusPay->empID=$employee->id;
            $BonusPay->paidDate=$request->paidDate;
            $BonusPay->occation=$request->occation;
            $BonusPay->basicSalary= $employee->salary;
            $BonusPay->bonusPercent=Payroll::first()->bonus ?? 0;
            $BonusPay->bonusAmount=$request->bonusAmount;
            if($payment_setting->status != 1){
                $BonusPay->paidMethod=$request->payment_method;
                $BonusPay->balance_account_id=$request->account ?? 0;
            }else{
                $BonusPay->payment_status = 0;
            }
            $BonusPay->save();
            // if($payment_setting->status != 1){
            //     if(array_search('accounts',load_pack_option()) != false){
            //         $del_check = AccountTransaction::where('relation_id',$BonusPay->id)->where('relation_with','Bonus')->get();
            //         if($del_check){
            //             foreach($del_check as $del){
            //                 $del->delete();
            //             }

            //         }

            //         $balance_account = BalanceAccount::find($request->account);
            //         $account_transaction = AccountTransaction::where('relation_id',$BonusPay->id)->where('relation_with','Bonus')->where('account_id',$balance_account->account_head_id)->first();
            //         if($account_transaction == null){
            //             $account_transaction = New AccountTransaction;
            //         }
            //         $account_transaction->amount = $request->bonusAmount;
            //         $account_transaction->account_id = $balance_account->account_head_id;
            //         $account_transaction->type = "credit";
            //         $account_transaction->sub_type = "Bonus Pay";
            //         $account_transaction->reason = $BonusPay->reference_no ." Bonus Payment";
            //         $account_transaction->date = $request->paidDate;
            //         $account_transaction->relation_id = $BonusPay->id;
            //         $account_transaction->relation_with = "Bonus";
            //         $account_transaction->save();

            //         $cap_head =  AccountHead::where("title",'Employee Bonus')->first();
            //         $ex_transaction = AccountTransaction::where('relation_id',$BonusPay->id)->where('relation_with','Bonus')->where('account_id',$cap_head->id)->first();
            //         if($ex_transaction == null){
            //             $ex_transaction = New AccountTransaction;
            //         }
            //         $ex_transaction->amount = $request->bonusAmount;
            //         $ex_transaction->account_id = $cap_head->id;
            //         $ex_transaction->type = "debit";
            //         $ex_transaction->sub_type = "Bonus";
            //         $ex_transaction->reason = $BonusPay->reference_no ." Bonus Payment";
            //         $ex_transaction->date = $request->paidDate;
            //         $ex_transaction->relation_id = $BonusPay->id;
            //         $ex_transaction->relation_with = "Bonus";
            //         $ex_transaction->save();
            //     }
            // }
            DB::commit();
            return response([
                'status' => 1,
                'success' => 'Bonus add successfully.',
            ]);
            // if($payment_setting->status != 1){
            //     return response([
            //         'status' => 1,
            //         'success' => 'Bonus add successfully.',
            //     ]);
            // }else{
            //     $user = auth()->user();
            //     $res_pay = $this->pay($BonusPay,$payment_setting,$user->business);
            //     return $res_pay;
            // }
            
        }catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'data'=>$e->getMessage(),
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

        // $member = Member::leftJoin("countries","countries.id","members.country_id")
        // ->leftJoin("states","states.id","members.state_id")
        // ->leftJoin("cities","cities.id","members.city_id")
        // ->select("members.*","countries.name as country_name","states.name as state_name" ,"cities.name as city_name")
        // ->where("members.id",$member_id)->first();
        if( $business->currency){
            $currency = $business->currency->name;
        }else{
            $currency = "BDT";
        }
        $post_data = array();
        $post_data['total_amount'] = $payment->bonusAmount; # You cant not pay less than 10
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
        $post_data['product_name'] = "Employee Bonus";
        $post_data['product_category'] = "topup";
        $post_data['product_profile'] = "non-physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $payment->id;
        $post_data['value_b'] = "Employee Bonus";

        

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

    public function delete(Request $request,$id){
        if(can_p('bonuspay.delete') == false){
            return redirect()->route('dashboard');
        }
        $data=BonusPay::find($id);
        $del_check = AccountTransaction::where('relation_id',$data->id)->where('relation_with','Bonus')->get();
        if($del_check){
            foreach($del_check as $del){
                $del->delete();
            }

        }
        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

        return redirect()->route('bonuspay.view')->with($notification);

    }

    public function edit(Request $request){
        if(can_p('bonuspay.edit') == false){
            $data = array();
            $data["msg"] ='Edit permissionn is not allowed';
            $data["status"] ="no";
            return response()->json($data);
        }
        
        if (!$request->id) {
           $data = array();
           $data["msg"] ='Not Found!';
           $data["status"] ="no";
        } else {

           $bonuspay=BonusPay::find($request->id);
           $deghtml="";
            foreach(Designation::where('department_id',$bonuspay->deptID)->get() as $designation){
                $deghtml .= '<option value="'.$designation->id.'">'.$designation->name."</option>";
            }
            $emphtml="";
            foreach(Employee::where('designation_id',$bonuspay->desigID)->get() as $employee){
                $emphtml .= '<option value="'.$employee->id.'">'.$employee->employee_name."</option>";
            }
            $accounts="";

            foreach(BalanceAccount::where('method_id',$bonuspay->paidMethod)->get() as $bank_account){
                $accounts .= '<option value="'.$bank_account->id.'">'.$bank_account->account_name."</option>";
            }


            // $emphtml="";
            // foreach(Employee::where('designation_id',$bonuspay->desigID)->get() as $employee){
            //     $emphtml .= '<option value="'.$employee->id.'">'.$employee->employee_name."</option>";
            // }
            $data = array();
            $data["data"] =$bonuspay;
            $data["emphtml"] =$emphtml;
            $data["deghtml"] =$deghtml;
            $data["accounts"] =$accounts;
            $data["status"] ="ok";
         

        }

        return response()->json($data);
    }


}
