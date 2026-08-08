<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use Illuminate\Http\Request;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\NewEmployee;
use App\Models\Hr\Bank;
use App\Models\Hr\EmpBankAccount;
use App\Models\Hr\EmpLoan;
use App\Models\Hr\BankAccount;
use App\Models\Hr\Employee;
use App\Models\OnlinePaymentSetting;
use App\User;
use App\Library\SslCommerz\SslCommerzNotification;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\DB;

class emploanController extends Controller
{
    public function view(){
        if(can_p('emploan.view') == false){
            return redirect()->route('dashboard');
        }
        // $data['emploans']=Employee::join('designations','employees.designation_id','designations.id')
        //                       ->join('departments','employees.department_id','departments.id')
        //                       ->where('employees.loan_amount','>',0)
        //                       ->select('employees.*','departments.name as dept_name','designations.name as desi_name')
        //                       ->orderBy('employees.id','DESC')
        //                       ->get();


         $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        $data['departments']=Department::orderBy('id','DESC')->get();
        $data['payment_setting'] = OnlinePaymentSetting::firstOrNew();
        return view ('Hr.empLoan.manage',$data);
    }
    function ajaxLoan(Request $request){
        $columns = [
            0=>'emp_loans.id',
            1=>'departments.name',
            2=>'designations.name',
            3=>'employees.employee_name',
            4=>'emp_loans.loan_date',
            5=>'emp_loans.type',
            6=>'emp_loans.amount',
            7=>'payment_methods.name',
            8=>'emp_loans.payment_status',
        ];

        $totalData = EmpLoan::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = EmpLoan::leftJoin('employees','employees.id','emp_loans.empID')
                            ->leftJoin('payment_methods','payment_methods.id','emp_loans.method_id')
                            ->leftJoin('balance_accounts','balance_accounts.id','emp_loans.balance_account_id')
                            ->leftJoin('departments','departments.id','employees.department_id')
                            ->leftJoin('designations','designations.id','employees.designation_id');
        if(!empty($search)){

           $datalist =$datalist->where("employees.employee_name","LIKE","%{$search}%")
                    ->orWhere("payment_methods.name","LIKE","%{$search}%")
                    ->orWhere("balance_accounts.account_name","LIKE","%{$search}%")
                    ->orWhere("departments.name","LIKE","%{$search}%")
                    ->orWhere("designations.name","LIKE","%{$search}%")
                    ->orWhere("emp_loans.amount","LIKE","%{$search}%")
                    ->orWhere("emp_loans.loan_date","LIKE","%{$search}%")
                    ->orWhere("emp_loans.type","LIKE","%{$search}%");
        }


        $totalFiltered = $datalist->count();
        $datalist = $datalist->select('emp_loans.*','payment_methods.name as p_name','balance_accounts.account_name','employees.employee_name','departments.name as depart_name','designations.name as desi_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('emploan.edit');
            $p_delete = can_p('emploan.delete');

            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i++;
                $nestedData['employee_name'] = $data_v->employee_name;

                $nestedData['department'] = $data_v->depart_name;
                $nestedData['designation'] = $data_v->desi_name;
                $nestedData['date'] = date("d-m-Y",strtotime($data_v->loan_date));
                $nestedData['type'] =$data_v->type;
                $nestedData['method_name'] =$data_v->p_name;
                $nestedData['account_name'] =$data_v->account_name;
                $nestedData['amount'] =auth()->user()->currency_symbol.' '. round($data_v->amount,2);
                $payment_status = '';
                if($data_v->payment_status == 0){
                    $payment_status ='<div class="badge bg-danger">Due</div>';
                }else{
                    $payment_status ='<div class="badge bg-secondary">Paid</div>';
                }
                $nestedData['status']=$payment_status;
                $nestedData['options']='';
                // if($p_edit){
                //     $nestedData['options'] .= '<a class="btn btn-primary" href="javascript:void(0)" data-token="'.csrf_token().'" id="loanEdit" data-id="'.$data_v->id.'"><i class="bx bx-edit"></i></a>';
                // }

                if($p_delete){
                    $nestedData['options'] .= '<a data-id="'. $data_v->id .'" data-action="'.route('emploan.delete',$data_v->id).'" class="del_hr_data btn btn-danger" href="#"><i class="bx bx-trash"></i></a>';
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
    function inital_account(){
        $salesHead = AccountHead::where("title",'Employee Loan')->first();
        if($salesHead == null){
            $salesHead = new AccountHead;
            $salesHead->title = "Employee Loan";
            $salesHead->code = '5014';
            $salesHead->sys = 0;
            $salesHead->ac_type = 5;
            $salesHead->note = '';
            $salesHead->status = 1;
            $salesHead->save();

        }
        // $salesHead2 = AccountHead::where("code",'8004')->first();
        // if($salesHead2 == null){
        //     $salesHead2 = new AccountHead;
        //     $salesHead2->title = "Loan Return";
        //     $salesHead2->code = '8004';
        //     $salesHead2->sys = 0;
        //     $salesHead2->ac_type = 8;
        //     $salesHead2->note = '';
        //     $salesHead2->status = 1;
        //     $salesHead2->save();

        // }

    }
    public function store(Request $request)
    {
    
        if(can_p('emploan.add') == false){
            $data = array();
            $data["msg"] ='Add permissionn is not allowed';
            $data["error"] ='Add permissionn is not allowed';
            $data["status"] ="0";
            return response()->json($data);
        }
        $payment_setting = OnlinePaymentSetting::firstOrNew();
        $this->inital_account();
        if($payment_setting->status != 1){
            if(array_search('accounts',load_pack_option()) != false){
                $validator = Validator::make($request->all(),[
                    'empID'=>'required',
                    'amount'=>'required',
                    'type'=>'required',
                    'payment_method'=>'required',
                    'account'=>'required',


                ], [
                    'empID.required'=> 'Employee is required',
                    'type.required'=> 'Type is required',
                    'amount.required'=> 'Amount is required',
                    'payment_method.required'=> 'Method is required',
                    'account.required'=> 'Account is required',
                ]);
            }else{
                $validator = Validator::make($request->all(),[
                    'empID'=>'required',
                    'amount'=>'required',
                    'type'=>'required',
                    'payment_method'=>'required',


                ], [
                    'empID.required'=> 'Employee is required',
                    'type.required'=> 'Type is required',
                    'amount.required'=> 'Amount is required',
                    'payment_method.required'=> 'Method is required',
                ]);
            }
        }else{
            $validator = Validator::make($request->all(),[
                'empID'=>'required',
                'amount'=>'required',
                'type'=>'required',


            ], [
                'empID.required'=> 'Employee is required',
                'type.required'=> 'Type is required',
                'amount.required'=> 'Amount is required',
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
             $employee=Employee::where('id',$request->empID)->first();
            $data=new EmpLoan();
            $data->reference_no = 'emp-' . date("Ymd") . '-'. date("his");
            $data->empID=$request->empID;
            $data->loan_date=$request->paidDate;
            $data->deptID=$employee->department_id;
            $data->desigID=$employee->designation_id;
            $data->type=$request->type;
            if($payment_setting->status != 1){
                $data->method_id=$request->payment_method;
                $data->balance_account_id=$request->account ?? 0;
            }else{
                $data->payment_status = 0;
            }
            $data->amount=$request->amount;
           
            $data->save();
            // if($payment_setting->status != 1){
               
            //     if($request->type=='Loan'){
            //         $employee->loan_amount=$employee->loan_amount+$request->amount;
            //     }
            //     else{
            //         $employee->loan_amount=$employee->loan_amount-$request->amount;
            //     }
            //     $employee->save();
            //     if(array_search('accounts',load_pack_option()) != false){
            //         $del_check = AccountTransaction::where('relation_id',$data->id)->where('relation_with','Employee Loan')->get();
            //         if($del_check){
            //             foreach($del_check as $del){
            //                 $del->delete();
            //             }

            //         }
            //         if($request->type=='Loan'){
            //             $balance_account = BalanceAccount::find($request->account);
            //             $account_transaction = AccountTransaction::where('relation_id',$data->id)->where('relation_with','Employee Loan')->where('account_id',$balance_account->account_head_id)->first();
            //             if($account_transaction == null){
            //                 $account_transaction = New AccountTransaction;
            //             }
            //             $account_transaction->amount = $request->amount;
            //             $account_transaction->account_id = $balance_account->account_head_id;
            //             $account_transaction->type = "credit";
            //             $account_transaction->sub_type = "Employee Loan Pay";
            //             $account_transaction->reason = $data->reference_no ." Employee Loan Payment";
            //             $account_transaction->date = $request->paidDate;
            //             $account_transaction->relation_id = $data->id;
            //             $account_transaction->relation_with = "Employee Loan";
            //             $account_transaction->save();

            //             $cap_head =  AccountHead::where("title",'Employee Loan')->first();
            //             $ex_transaction = AccountTransaction::where('relation_id',$data->id)->where('relation_with','Employee Loan')->where('account_id',$cap_head->id)->first();
            //             if($ex_transaction == null){
            //                 $ex_transaction = New AccountTransaction;
            //             }
            //             $ex_transaction->amount = $request->amount;
            //             $ex_transaction->account_id = $cap_head->id;
            //             $ex_transaction->type = "debit";
            //             $ex_transaction->sub_type = "Employee Loan";
            //             $ex_transaction->reason = $data->reference_no ." Employee Loan Payment";
            //             $ex_transaction->date = $request->paidDate;
            //             $ex_transaction->relation_id = $data->id;
            //             $ex_transaction->relation_with = "Employee Loan";
            //             $ex_transaction->save();
            //         }else{
            //             $balance_account = BalanceAccount::find($request->account);
            //             $account_transaction = AccountTransaction::where('relation_id',$data->id)->where('relation_with','Employee Loan')->where('account_id',$balance_account->account_head_id)->first();
            //             if($account_transaction == null){
            //                 $account_transaction = New AccountTransaction;
            //             }
            //             $account_transaction->amount = $request->amount;
            //             $account_transaction->account_id = $balance_account->account_head_id;
            //             $account_transaction->type = "debit";
            //             $account_transaction->sub_type = "Employee Loan Return";
            //             $account_transaction->reason = $data->reference_no ." Employee Loan Return Payment";
            //             $account_transaction->date = $request->paidDate;
            //             $account_transaction->relation_id = $data->id;
            //             $account_transaction->relation_with = "Employee Loan";
            //             $account_transaction->save();

            //             $cap_head =  AccountHead::where("title",'Employee Loan')->first();
            //             $ex_transaction = AccountTransaction::where('relation_id',$data->id)->where('relation_with','Employee Loan')->where('account_id',$cap_head->id)->first();
            //             if($ex_transaction == null){
            //                 $ex_transaction = New AccountTransaction;
            //             }
            //             $ex_transaction->amount = $request->amount;
            //             $ex_transaction->account_id = $cap_head->id;
            //             $ex_transaction->type = "credit";
            //             $ex_transaction->sub_type = "Employee Loan Return";
            //             $ex_transaction->reason = $data->reference_no ." Employee Loan Return Payment";
            //             $ex_transaction->date = $request->paidDate;
            //             $ex_transaction->relation_id = $data->id;
            //             $ex_transaction->relation_with = "Employee Loan";
            //             $ex_transaction->save();
            //         }
            //     }
            // }
            DB::commit();
            return response([
                'status' => 1,
                'success' => 'Employee Loan add successfully.',
            ]);
            // if($payment_setting->status != 1){
            //     return response([
            //         'status' => 1,
            //         'success' => 'Employee Loan add successfully.',
            //     ]);
            // }else{
            //     $user = auth()->user();
            //     $res_pay = $this->pay($data,$payment_setting,$user->business,$request->type);
            //     return $res_pay;
            // }
            
        }catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'data'=> $e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }

        // $notification=array(
        //     'message'=>"Save Success",
        //     'alert-type'=>'success'
        // );

        // return redirect()->route('emploan.view')->with($notification);
    }
    function pay($payment,$payment_setting,$business,$type){
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
        $post_data['product_name'] = "Employee Loan";
        $post_data['product_category'] = "topup";
        $post_data['product_profile'] = "non-physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $payment->id;
        $post_data['value_b'] = "Employee Loan";
        $post_data['value_c'] = $type;

        

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
        if(can_p('emploan.delete') == false){
            return redirect()->route('dashboard');
        }
        $data=EmpLoan::find($id);
        $del_check = AccountTransaction::where('relation_id',$data->id)->where('relation_with','Employee Loan')->get();

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

        return redirect()->route('emploan.view')->with($notification);

    }



    public function edit(Request $request){
        if(can_p('emploan.edit') == false){
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

           $emp_loan=EmpLoan::find($request->id);
           $deghtml="";
            foreach(Designation::where('department_id',$emp_loan->deptID)->get() as $designation){
                $deghtml .= '<option value="'.$designation->id.'">'.$designation->name."</option>";
            }
            $emphtml="";
            foreach(Employee::where('designation_id',$emp_loan->desigID)->get() as $employee){
                $emphtml .= '<option value="'.$employee->id.'">'.$employee->employee_name."</option>";
            }
            $accounts="";

            foreach(BalanceAccount::where('method_id',$emp_loan->method_id)->get() as $bank_account){
                $accounts .= '<option value="'.$bank_account->id.'">'.$bank_account->account_name."</option>";
            }


            // $emphtml="";
            // foreach(Employee::where('designation_id',$bonuspay->desigID)->get() as $employee){
            //     $emphtml .= '<option value="'.$employee->id.'">'.$employee->employee_name."</option>";
            // }
            $data = array();
            $data["data"] =$emp_loan;
            $data["emphtml"] =$emphtml;
            $data["deghtml"] =$deghtml;
            $data["accounts"] =$accounts;
            $data["status"] ="ok";


        }

        return response()->json($data);
    }



    public function loanLegder(Request $request)
    {
        if (!$request->id) {
           $html ='Sorry';
        } else {

            $employee=DB::table('employees')
                              ->join('designations','employees.designation_id','designations.id')
                              ->join('departments','employees.department_id','departments.id')
                              ->where('employees.id',$request->id)
                              ->select('employees.*','departments.name as deptName','designations.name as desigName')
                              ->first();
            $loans=EmpLoan::where('empID',$request->id)->get();

            $html='<center><strong>Name: '.$employee->employee_name.'<br/>ID: '.$employee->employee_id.'<br/>Department: '.$employee->deptName.'<br/>Designation: '.$employee->desigName.'<br/><br/>'.'</strong></center>';


            $html.='<table id="dataTable" class="table table-striped table-bordered table-responsive" style="width:100%">
                          <thead>
                            <tr>
                              <th>SN.</th>
                              <th>Date</th>
                              <th>Method</th>
                              <th>Bank Account</th>
                              <th>Debit</th>
                              <th>Credit</th>
                              <th>Balance</th>
                              <th>Type</th>
                            </tr>
                          </thead>

                          <tbody>';

                                $debit=0;
                                $credit=0;
                                $balance=0;
                                $indexValue=1;;

                            foreach($loans as $emploan){
                                $dr=0;
                                $cr=0;
                                if( $emploan->type == "Loan"){
                                    $dr=$emploan->amount;
                                    $debit+=$emploan->amount;
                                    $balance=$balance+$debit;
                                }else{
                                    $cr=$emploan->amount;
                                    $credit+=$emploan->amount;
                                    $balance=$balance-$credit;
                                }



                               // return $emploan->method;
                             $html.='<tr><td>'.$indexValue++.'</td><td>'.date('Y-m-d',strtotime($emploan->loan_date)).'</td><td>'.$emploan?->method?->name.'</td><td>'.@$emploan?->bank_account?->account_name.'</td><td>'.auth()->user()->currency_symbol.' '.round($dr,2).'</td><td>'.auth()->user()->currency_symbol.' '.round($cr,2).'</td><td>'.auth()->user()->currency_symbol.' '.$balance.'</td><td>'.$emploan->type.'</td></tr>';

                            }

                    $html.='</tbody></table>';
        }


        return response()->json(['html' => $html]);

    }

    public function empbankaccount(Request $request){

        if (!$request->id) {
           $html ='Sorry';
        } else {

           $Accounts=EmpBankAccount::where('bankID',$request->id)->get();
           $html='<option>-- Select One --</option>';

            foreach($Accounts as $Account){

                    $html.='<option value="'.$Account->id.'">'.$Account->acNumber.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }


}
