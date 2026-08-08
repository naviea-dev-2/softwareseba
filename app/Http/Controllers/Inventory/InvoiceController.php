<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use App\Models\Hr\Bank;
use App\Models\Inventory\Customer;
use App\Models\Inventory\Invoice;
use App\Models\Inventory\Payment;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductInvoice;
use App\Models\Inventory\Stock;
use App\Models\ProductStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{

    function pack_option(){
        $user = auth()->user();
        //dd($user);
        $arr_con = [];
        if($user->user_type == 0){
            //$arr_con = ['inventory','hr-payroll','accounts','general'];
            $au_business = $user->business;
            if($au_business->package){
                $arr_con = json_decode($au_business->package?->pack_option,true);
                array_push($arr_con,'general');
            }else{
                $arr_con = ['inventory','hr-payroll','accounts','general'];
            }

        }else{
            $au_business = $user->business;
            if($au_business->package){
                $arr_con = json_decode($au_business->package?->pack_option,true);
                array_push($arr_con,'general');
            }else{
                $arr_con = ['inventory','hr-payroll','accounts','general'];
            }
        }
        return $arr_con;
    }
    function init_account(){
        $salesHead = AccountHead::where("code",'4000')->first();
        if($salesHead == null){
            $salesHead = new AccountHead;
            $salesHead->title = "Sales";
            $salesHead->code = '4000';
            $salesHead->sys = 0;
            $salesHead->ac_type = 4;
            $salesHead->note = '';
            $salesHead->status = 1;
            $salesHead->save();
        }
        $acReceivableHead = AccountHead::where("code",'1000')->first();
        if($acReceivableHead == null){
            $acReceivableHead = new AccountHead;
            $acReceivableHead->code = '1000';
            $acReceivableHead->title = "Account Receivable";
            $acReceivableHead->ac_type = 1;
            $acReceivableHead->note = '';
            $acReceivableHead->sys = 0;
            $acReceivableHead->status = 1;
            $acReceivableHead->save();
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd($this->pack_option());
        if(can_p('invoice.index') == false){
            return redirect()->route('dashboard');
        }

        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();


        return view('Inventory.invoice.manage', $data );
    }
    function ajaxInvoice(Request $request){
        $b_type_id = auth()->user()->business->business_type_id;
        if($b_type_id == 15){
            $columns = array(
                0 => 'invoices.id',
                1 => 'invoices.is_pos',
                2 => 'invoices.invoice_date',
                3 => 'invoices.reference_no',
                4 => 'invoices.dsr_id',
                // 4 => 'invoices.asr_id',
                // 5 => 'invoices.sld_id',
                5 => 'customers.name',
                6=> 'invoices.grand_total',
                7 => 'invoices.paid_amount',
                8 => 'invoices.due_amount',
                9=> 'invoices.status',
                10 => 'invoices.payment_status',
                11 => 'invoices.payment_method',
                12 => 'invoices.bank_account_id',
                13 => 'options',
            );
        }else if($b_type_id == 16){
            $columns = array(
                0 => 'invoices.id',
                1 => 'invoices.is_pos',
                2 => 'invoices.invoice_date',
                3 => 'invoices.deadline_date',
                4 => 'customers.name',
                5=> 'invoices.grand_total',
                6 => 'invoices.due_amount',
                7=> 'invoices.status',
                8 => 'invoices.payment_status',
                9 => 'invoices.payment_method',
                10 => 'invoices.bank_account_id',
                11 => 'options',
            );
        }
        else{
            $columns = array(
                0 => 'invoices.id',
                1 => 'invoices.is_pos',
                2 => 'invoices.invoice_date',
                3 => 'invoices.reference_no',
                4 => 'customers.name',
                5 => 'invoices.grand_total',
                6 => 'invoices.paid_amount',
                7 => 'invoices.due_amount',
                8 => 'invoices.status',
                9 => 'invoices.payment_status',
                10 => 'invoices.payment_method',
                11 => 'invoices.bank_account_id',
                12 => 'options',
            );
        }

        $totalData = Invoice::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        if($b_type_id == 15){
            $invoices = Invoice::leftjoin('customers','customers.id','invoices.customer_id')
                                ->leftjoin('employees as dsr_e','dsr_e.id','invoices.dsr_id')
                                ->leftjoin('employees as asr_e','asr_e.id','invoices.asr_id')
                                ->leftjoin('employees as driver_e','driver_e.id','invoices.sld_id');
                                // ->where('invoices.is_pos',0);
            if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
                $invoices->where('branch_id',auth()->user()->branch_id);
            }
            if(!empty($search))
            {
                $invoices = $invoices->where(function($q) use($search){
                    $q->where("invoices.invoice_date","LIKE","%{$search}%")
                    ->orWhere("invoices.reference_no","LIKE","%{$search}%")
                    ->orWhere("customers.name","LIKE","%{$search}%")
                    ->orWhere("dsr_e.employee_name","LIKE","%{$search}%")
                    ->orWhere("asr_e.employee_name","LIKE","%{$search}%")
                    ->orWhere("driver_e.employee_name","LIKE","%{$search}%");
                });
    
    
    
            }
            $totalFiltered = $invoices->count();
            $invoices = $invoices->select('invoices.*','customers.name as cus_name','dsr_e.employee_name as dsr_name','asr_e.employee_name as asr_name','driver_e.employee_name as driver_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }else if($b_type_id == 16){
             $invoices = Invoice::leftjoin('customers','customers.id','invoices.customer_id');
             
            if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
                $invoices->where('branch_id',auth()->user()->branch_id);
            }
            if(!empty($search))
            {
                $invoices = $invoices->where(function($q) use($search){
                    $q->where("invoices.invoice_date","LIKE","%{$search}%")
                    ->orWhere("invoices.deadline_date","LIKE","%{$search}%")
                    ->orWhere("invoices.reference_no","LIKE","%{$search}%")
                    ->orWhere("customers.name","LIKE","%{$search}%");
                });
    
    
    
            }
            $totalFiltered = $invoices->count();
            $invoices = $invoices->select('invoices.*','customers.name as cus_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }else{
             $invoices = Invoice::leftjoin('customers','customers.id','invoices.customer_id');
            if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
                $invoices->where('branch_id',auth()->user()->branch_id);
            }
            if(!empty($search))
            {
                $invoices = $invoices->where(function($q) use($search){
                    $q->where("invoices.invoice_date","LIKE","%{$search}%")
                    ->orWhere("invoices.reference_no","LIKE","%{$search}%")
                    ->orWhere("customers.name","LIKE","%{$search}%");
                });
    
    
    
            }
            $totalFiltered = $invoices->count();
            $invoices = $invoices->select('invoices.*','customers.name as cus_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }
        $data = array();
        if(!empty($invoices))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('invoice.edit');
            $p_delete = can_p('invoice.delete');
            $p_view = can_p('invoice.view');
            $p_add_payment = can_p('invoice.add-payment');
            $p_payment_show = can_p('invoice.payment_show');
            $p_sales_return = can_p('invoice_return.add');
            $p_print= can_p('invoice.print');
            foreach($invoices as $invoice)
            {
                $nestedData['id'] = $i++;

                $nestedData['date'] = date('Y-m-d', strtotime($invoice->invoice_date));
                $nestedData['reference'] = $invoice->reference_no;
                $nestedData['cus_name'] = $invoice->cus_name;
                if($b_type_id == 15){
                    $nestedData['dsr'] = $invoice->dsr_name;
                    $nestedData['asr'] = $invoice->asr_name;
                    $nestedData['driver'] = $invoice->driver_name;
                }
                if($b_type_id == 16){
                    $nestedData['d_date'] = date('Y-m-d', strtotime($invoice->deadline_date));
                }
                $nestedData['is_pos'] = $invoice->is_pos == 0 ? "No" : "YES";
                $nestedData['total'] = auth()->user()->currency_symbol . number_format($invoice->grand_total, 2);
                $nestedData['paid'] = auth()->user()->currency_symbol . number_format($invoice->paid_amount, 2);
                $nestedData['due'] = auth()->user()->currency_symbol . number_format($invoice->grand_total - $invoice->paid_amount, 2);
                $status = '';
                if($invoice->status == 1){
                    $status = '<div class="badge bg-success">Received</div>';
                }else if($invoice->status == 2){
                    $status = '<div class="badge bg-secondary">Partial</div>';
                }else if($invoice->status == 3){
                    $status = '<div class="badge bg-danger">Pending</div>';
                }else{
                    $status = '<div class="badge bg-success">Ordered</div>';
                }
                $nestedData['status'] =$status;
                $payment_status = '';
                if($invoice->payment_status == 0){
                    $payment_status = '<div class="badge bg-danger">Due</div>';
                }else if($invoice->payment_status == 1){
                    $payment_status = '<div class="badge bg-warning">Partial</div>';
                }else{
                    $payment_status = '<div class="badge bg-success">Paid</div>';
                }
                $nestedData['payment_status'] =$payment_status;
                $nestedData['method'] =$invoice?->method?->name;
                $nestedData['account'] =$invoice?->account?->account_name;
                $nestedData['options'] = '<div class="btn-group"><button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';
                if($p_view){
                    $nestedData['options'] .= ' <li><button data-id="'.$invoice->id.'" type="button" class="btn btn-link view"><i class="bx bx-show"></i>View</button> </li>';
                }
                if($invoice->is_pos == 0){
                    if($p_edit){
                        $nestedData['options'] .= ' <li><a href="'. route('invoice.edit', $invoice->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';
                    }
                    if($p_sales_return){
                        $nestedData['options'] .= ' <li><a href="'. route('invoice_return.add', $invoice->id) .'" class="btn btn-link"><i class="bx bx-undo"></i> Sales Return</a></li>';
                    }
                    if(array_search('accounts',$this->pack_option()) != false){
                        if($p_add_payment){
                            if($invoice->due_amount > 0){
                                $nestedData['options'] .= '<li><button data-due="'. $invoice->due_amount .'" type="button" class="add-payment btn btn-link" data-id = "'. $invoice->id .'" data-bs-toggle="modal" data-bs-target="#add-payment"><i class="bx bx-plus"></i>Add Payment</button></li>';
                            }
                        }
                        if($p_payment_show){
                            if($invoice->payment_status != 0){
                                $nestedData['options'] .= ' <li><button type="button" class="payment_show btn btn-link" data-id = "'. $invoice->id .'"><i class="bx bx-money"></i> View Payment</button></li>';
                            }
                        }
                    }
                }


                if($p_delete){
                    $nestedData['options'] .= ' <li> <form action="'. route('invoice.delete',$invoice->id).'" method="post"><input type="hidden" name="_token" value="'. csrf_token().'"><button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="bx bx-trash"></i>Delete</button></form></li>';
                }

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(can_p('invoice.create') == false){
            return redirect()->route('dashboard');
        }
       $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view ('Inventory.invoice.create',$data);
    }
    public function createInstant()
    {
        if(can_p('invoice.create_instant') == false){
            return redirect()->route('dashboard');
        }
       $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view('Inventory.invoice.create_instant',$data);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(can_p('invoice.create') == false){
            return redirect()->route('dashboard');
        }
        //dd($request);
        $b_type_id = auth()->user()->business->business_type_id;
        $validator_arr=[];
        $validator_e_msgg_arr=[];
        $is_valid_product = 0;



        $validator_arr["product_id.0"] =["required"];
        $validator_e_msgg_arr["product_id.0"] = "Product is required";
        $validator_arr["unit.*"] =["required"];
        if($b_type_id == 15){
            if(array_search('hr-payroll',$this->pack_option()) != false){
                $validator_arr["dsr"] =["required"];
                $validator_e_msgg_arr["dsr"] = "DSR is required";
                // $validator_arr["asr"] =["required"];
                // $validator_e_msgg_arr["asr"] = "ASR is required";
                // $validator_arr["driver"] =["required"];
                // $validator_e_msgg_arr["driver"] = "Driver is required";
            }
        }
        if(array_search('accounts',$this->pack_option()) != false){
            $this->init_account();
            if($request->paid_amount > 0){
                $validator_arr["payment_method"] = ["required"];
                $validator_arr["account"] = ["required"];
            }
        }else{
            if($request->paid_amount > 0){
                $validator_arr["payment_method"] = ["required"];
            }
        }


        // $validator_arr["email"] = ["required"];
      //  $validator_arr["name"] = ["required"];
        //$validator_arr["branch"] = ["required"];
        // $validator_e_msgg_arr["email"] = "Customer Email is required";
       // $validator_arr["mobile"] = ["required"];
       // $validator_e_msgg_arr["mobile"] = "Customer Mobile is required";
        $validator = Validator::make($request->all(),
            $validator_arr,
            $validator_e_msgg_arr,
           // "color.*"  => "required|string|distinct|min:3",
        );

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->with("cus_errors",$validator->errors()->all())->withInput($request->all());
        }


        try{
            DB::beginTransaction();
            if($request->email){
                $customer = Customer::where('email', $request->email)->first();
                if($customer == null){
                    $customer = new Customer();
                    $customer->email = $request->email;
                    $customer->name = $request->name;
                    $customer->mobile = $request->mobile;
                    $customer->address = $request->address;
                    $customer->save();
                }else{

                    $customer->email = $request->email;
                    $customer->name = $request->name;
                    $customer->mobile = $request->mobile;
                    $customer->address = $request->address;
                    $customer->save();
                }
                $customer_id=$customer->id;
            }else{
                $customer_id=0;
            }

            $invoice = New Invoice;
            $invoice->reference_no = 'pr-' . date("Ymd") . '-'. date("his");
            $invoice->branch_id = $request->branch ?? 0;

            $invoice->dsr_id = $request->dsr ?? 0;
            $invoice->asr_id = $request->asr ?? 0;
            $invoice->sld_id = $request->driver ?? 0;

            $invoice->customer_id= $customer_id;
            $invoice->item = $request->item;
            $invoice->total_qty = $request->total_qty;
            $invoice->total_discount = round($request->total_discount,2);
            $invoice->total_cost = round($request->total_cost,2);
            $invoice->total_p_cost = round($request->total_p_cost,2);
            $invoice->order_discount = round($request->order_discount,2);
            $invoice->shipping_cost = round($request->shipping_cost,2);
            $invoice->total_tax = round($request->total_tax,2);
            $invoice->paid_amount = round($request->paid_amount,2);
            $invoice->due_amount = round($request->grand_total,2) - round($request->paid_amount,2);
            $invoice->grand_total = round($request->grand_total,2);
            $invoice->payment_method= $request->payment_method ?? 0;
            $invoice->bank_account_id= $request->account ?? 0;
            $invoice->is_ini_p= $request->is_ini_p ?? 0;
            $invoice->status = $request->status;
            if(array_search('accounts',$this->pack_option()) != false){
                if($request->payment_method != ''){
                    if($invoice->due_amount == 0){
                        $invoice->payment_status = 2;
                    }else{
                        $invoice->payment_status = 1;
                    }
                }else{
                    $invoice->payment_status = 0;
                }
            }else{
                if($request->payment_method != ''){
                    if($invoice->due_amount == 0){
                        $invoice->payment_status = 2;
                    }else{
                        $invoice->payment_status = 1;
                    }
                }else{
                    $invoice->payment_status = 0;
                }
                //$invoice->payment_status = $request->payment_status ?? 0;
            }
            $invoice->note = $request->order_note;
            if ($request->invoice_date == null) {
                $invoice->invoice_date = now();
            } else {
                $invdate = Carbon::parse($request->invoice_date)->format('Y-m-d');
                $invoice->invoice_date = $invdate;
            }
            if($b_type_id == 15){
                if ($request->deadline_date == null) {
                    $invoice->deadline_date = now();
                } else {
                    $invdate = Carbon::parse($request->deadline_date)->format('Y-m-d');
                    $invoice->deadline_date = $invdate;
                }
            }
            $invoice->save();
            foreach($request->product_id as $k=>$product_id) {
                if(null != $product_id){
                    $product_invoice = New ProductInvoice;
                    $product_invoice->invoice_id = $invoice->id;
                    $product_invoice->product_id = $product_id;
                    // $product_invoice->color_id = $request->color[$k] ?? 0;
                    // $product_invoice->size_id = $request->size[$k] ?? 0;
                    $product_invoice->unit_id = $request->unit[$k];
                    $product_invoice->qty = $request->qty[$k] ?? 0;
                    $product_invoice->tax = round($request->tax[$k] ?? 0,2);
                    $product_invoice->per_cost = round($request->per_cost[$k] ?? 0,2);
                    $product_invoice->purchase_price = round($request->purchase_price[$k] ?? 0,2);
                    $product_invoice->total = round($request->total[$k] ?? 0,2);
                    $product_invoice->total_purchase = round($request->total_purchase[$k] ?? 0,2);
                    $product_invoice->discount = round($request->discount[$k] ?? 0,2);
                    $product_invoice->save();
                    $stock = new Stock;
                    $stock->invoice_id = $invoice->id;
                    $stock->product_invoice_id = $product_invoice->id;
                    $stock->product_id = $product_id;
                    // $stock->color_id = $request->color[$k] ?? 0;
                    // $stock->size_id = $request->size[$k] ?? 0;
                    $stock->unit_id = $request->unit[$k] ?? 0;
                    $stock->out_qty = $request->qty[$k]?? 0;
                    $stock->sale_price = $request->total[$k] ?? 0;
                    $stock->inventory_type = 'Sales';
                    $stock->save();
                    $product_stock =  Product::find($product_id);
                    if($product_stock){
                        $product_stock->qty= $product_stock->qty - ($request->qty[$k]?? 0);
                        $product_stock->save();
                    }
                }
            }
            if(array_search('accounts',$this->pack_option()) != false){
                $salesHead = AccountHead::where("code",'4000')->first();

                $sc_trans = New AccountTransaction;
                $sc_trans->amount = round($request->grand_total,2);
                $sc_trans->account_id = $salesHead->id;
                $sc_trans->type = "credit";
                $sc_trans->sub_type = "Sales";
                $sc_trans->reason = "Sale Product To Customer";
                $sc_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                $sc_trans->relation_id = $invoice->id;
                $sc_trans->relation_with = "Sales";
                $sc_trans->save();
                if($request->payment_method == ''){
                    $acReceivableHead = AccountHead::where("code",'1000')->first();

                    $due_trans = New AccountTransaction;
                    $due_trans->amount = round($request->grand_total,2);
                    $due_trans->account_id = $acReceivableHead->id;
                    $due_trans->type = "debit";
                    $due_trans->sub_type = "Sales";
                    $due_trans->reason = "Sale Product To Customer With Due";
                    $due_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                    $due_trans->relation_id = $invoice->id;
                    $due_trans->relation_with = "Sales";
                    $due_trans->trans_id = $sc_trans->id;
                    $due_trans->save();
                    $sc_trans->trans_id = $due_trans->id;
                    $sc_trans->save();
                }else{

                    $balance_account = BalanceAccount::find($request->account);
                    $payment = New Payment;
                    $payment->payment_method= $request->payment_method ?? 0;
                    $payment->bank_account_id= $request->account ?? 0;
                    // $payment->transaction_id= $sc_pay_transaction->id;
                    $payment->relation_id = $invoice->id;
                    $payment->relation_type = "Sales Payment";
                    $payment->amount = round($request->paid_amount,2);
                    $payment->date = date('Y-m-d');
                    $payment->note = $request->order_note;
                    $payment->save();

                    $pay_trans = New AccountTransaction;
                    $pay_trans->amount = round($request->paid_amount,2);
                    $pay_trans->account_id = $balance_account->account_head_id;
                    $pay_trans->type = "debit";
                    $pay_trans->sub_type = "Sales";
                    $pay_trans->reason = "Sales Payment";
                    $pay_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                    $pay_trans->relation_id = $invoice->id;
                    $pay_trans->relation_with = "Sales";
                    $pay_trans->payment_id = $payment->id;
                    $pay_trans->trans_id = $sc_trans->id;
                    $pay_trans->save();
                    $payment->transaction_id= $pay_trans->id;
                    $payment->save();
                    $sc_trans->trans_id = $pay_trans->id;
                    $sc_trans->save();
                    if( $invoice->due_amount > 0){
                        $salesDueHead = AccountHead::where("code",'1000')->first();

                        $sc_due_transaction = New AccountTransaction;
                        $sc_due_transaction->amount = round($request->grand_total-$request->paid_amount,2);
                        $sc_due_transaction->account_id = $salesDueHead->id;
                        $sc_due_transaction->type = "debit";
                        $sc_due_transaction->sub_type = "Sales";
                        $sc_due_transaction->reason = "Sale Product To Customer With Due";
                        $sc_due_transaction->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                        $sc_due_transaction->relation_id = $invoice->id;
                        $sc_due_transaction->relation_with = "Sales";
                        $sc_due_transaction->is_trans2 = 2;
                        $sc_due_transaction->trans_id = $sc_trans->id;
                        $sc_due_transaction->save();
                        $sc_trans->is_trans2 = 1;
                        $sc_trans->trans2_id = $sc_due_transaction->id;
                        $sc_trans->save();
                    }


                }
            }

            DB::commit();

            $notification=array(
                'message'=>"Invoice Successfully Completed",
                'alert-type'=>'success'
            );

            return redirect()->route('invoice.print',$invoice->id)->with($notification);
            return redirect()->route('invoice.index')->with($notification);
        }catch (\Exception $e){
            DB::rollBack();
           // dd($e->getMessage());
            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    public function storeInstant(Request $request)
    {
        if(can_p('invoice.create_instant') == false){
            return redirect()->route('dashboard');
        }
        //dd($request);
        $b_type_id = auth()->user()->business->business_type_id;
        $validator_arr=[];
        $validator_e_msgg_arr=[];
        $is_valid_product = 0;



        $validator_arr["product_id.0"] =["required"];
        $validator_e_msgg_arr["product_id.0"] = "Product is required";
        $validator_arr["unit.*"] =["required"];
        if($b_type_id == 15){
            if(array_search('hr-payroll',$this->pack_option()) != false){
                $validator_arr["dsr"] =["required"];
                $validator_e_msgg_arr["dsr"] = "DSR is required";
                // $validator_arr["asr"] =["required"];
                // $validator_e_msgg_arr["asr"] = "ASR is required";
                // $validator_arr["driver"] =["required"];
                // $validator_e_msgg_arr["driver"] = "Driver is required";
            }
        }
        if(array_search('accounts',$this->pack_option()) != false){
            $this->init_account();
            if($request->paid_amount > 0){
                $validator_arr["payment_method"] = ["required"];
                $validator_arr["account"] = ["required"];
            }
        }else{
            if($request->paid_amount > 0){
                $validator_arr["payment_method"] = ["required"];
            }
        }


        // $validator_arr["email"] = ["required"];
      //  $validator_arr["name"] = ["required"];
        //$validator_arr["branch"] = ["required"];
        // $validator_e_msgg_arr["email"] = "Customer Email is required";
       // $validator_arr["mobile"] = ["required"];
       // $validator_e_msgg_arr["mobile"] = "Customer Mobile is required";
        $validator = Validator::make($request->all(),
            $validator_arr,
            $validator_e_msgg_arr,
           // "color.*"  => "required|string|distinct|min:3",
        );

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->with("cus_errors",$validator->errors()->all())->withInput($request->all());
        }


        try{
            DB::beginTransaction();

            $customer_id=0;


            $invoice = New Invoice;
            $invoice->reference_no = 'pr-' . date("Ymd") . '-'. date("his");
            $invoice->branch_id = $request->branch ?? 0;

            $invoice->dsr_id = $request->dsr ?? 0;
            $invoice->asr_id = $request->asr ?? 0;
            $invoice->sld_id = $request->driver ?? 0;

            $invoice->customer_id= $customer_id;
            $invoice->item = $request->item;
            $invoice->total_qty = $request->total_qty;
            $invoice->total_discount = round($request->total_discount,2);
            $invoice->total_cost = round($request->total_cost,2);
            $invoice->total_p_cost = round($request->total_p_cost,2);
            $invoice->order_discount = round($request->order_discount,2);
            $invoice->shipping_cost = round($request->shipping_cost,2);
            $invoice->total_tax = round($request->total_tax,2);
            $invoice->paid_amount = round($request->paid_amount,2);
            $invoice->due_amount = round($request->grand_total,2) - round($request->paid_amount,2);
            $invoice->grand_total = round($request->grand_total,2);
            $invoice->payment_method= $request->payment_method ?? 0;
            $invoice->bank_account_id= $request->account ?? 0;
            $invoice->is_ini_p= $request->is_ini_p ?? 0;
            $invoice->status = $request->status;
            if(array_search('accounts',$this->pack_option()) != false){
                if($request->payment_method != ''){
                    if($invoice->due_amount == 0){
                        $invoice->payment_status = 2;
                    }else{
                        $invoice->payment_status = 1;
                    }
                }else{
                    $invoice->payment_status = 0;
                }
            }else{
                if($request->payment_method != ''){
                    if($invoice->due_amount == 0){
                        $invoice->payment_status = 2;
                    }else{
                        $invoice->payment_status = 1;
                    }
                }else{
                    $invoice->payment_status = 0;
                }
                //$invoice->payment_status = $request->payment_status ?? 0;
            }
            $invoice->note = $request->order_note;
            if ($request->invoice_date == null) {
                $invoice->invoice_date = now();
            } else {
                $invdate = Carbon::parse($request->invoice_date)->format('Y-m-d');
                $invoice->invoice_date = $invdate;
            }
            if($b_type_id == 15){
                if ($request->deadline_date == null) {
                    $invoice->deadline_date = now();
                } else {
                    $invdate = Carbon::parse($request->deadline_date)->format('Y-m-d');
                    $invoice->deadline_date = $invdate;
                }
            }
            $invoice->save();
            foreach($request->product_id as $k=>$product_id) {
                if(null != $product_id){
                    $product_invoice = New ProductInvoice;
                    $product_invoice->invoice_id = $invoice->id;
                    $product_invoice->product_id = $product_id;
                    // $product_invoice->color_id = $request->color[$k] ?? 0;
                    // $product_invoice->size_id = $request->size[$k] ?? 0;
                    $product_invoice->unit_id = $request->unit[$k];
                    $product_invoice->qty = $request->qty[$k] ?? 0;
                    $product_invoice->tax = round($request->tax[$k] ?? 0,2);
                    $product_invoice->per_cost = round($request->per_cost[$k] ?? 0,2);
                    $product_invoice->total = round($request->total[$k] ?? 0,2);
                    $product_invoice->discount = round($request->discount[$k] ?? 0,2);
                    $product_invoice->save();
                    $stock = new Stock;
                    $stock->invoice_id = $invoice->id;
                    $stock->product_invoice_id = $product_invoice->id;
                    $stock->product_id = $product_id;
                    // $stock->color_id = $request->color[$k] ?? 0;
                    // $stock->size_id = $request->size[$k] ?? 0;
                    $stock->unit_id = $request->unit[$k] ?? 0;
                    $stock->out_qty = $request->qty[$k]?? 0;
                    $stock->sale_price = $request->total[$k] ?? 0;
                    $stock->inventory_type = 'Sales';
                    $stock->save();
                    $product_stock =  Product::find($product_id);
                    if($product_stock){
                        $product_stock->qty= $product_stock->qty - ($request->qty[$k]?? 0);
                        $product_stock->save();
                    }
                }
            }
            if(array_search('accounts',$this->pack_option()) != false){
                $salesHead = AccountHead::where("code",'4000')->first();

                $sc_trans = New AccountTransaction;
                $sc_trans->amount = round($request->grand_total,2);
                $sc_trans->account_id = $salesHead->id;
                $sc_trans->type = "credit";
                $sc_trans->sub_type = "Sales";
                $sc_trans->reason = "Sale Product To Customer";
                $sc_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                $sc_trans->relation_id = $invoice->id;
                $sc_trans->relation_with = "Sales";
                $sc_trans->save();
                if($request->payment_method == ''){
                    $acReceivableHead = AccountHead::where("code",'1000')->first();

                    $due_trans = New AccountTransaction;
                    $due_trans->amount = round($request->grand_total,2);
                    $due_trans->account_id = $acReceivableHead->id;
                    $due_trans->type = "debit";
                    $due_trans->sub_type = "Sales";
                    $due_trans->reason = "Sale Product To Customer With Due";
                    $due_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                    $due_trans->relation_id = $invoice->id;
                    $due_trans->relation_with = "Sales";
                    $due_trans->trans_id = $sc_trans->id;
                    $due_trans->save();
                    $sc_trans->trans_id = $due_trans->id;
                    $sc_trans->save();
                }else{

                    $balance_account = BalanceAccount::find($request->account);
                    $payment = New Payment;
                    $payment->payment_method= $request->payment_method ?? 0;
                    $payment->bank_account_id= $request->account ?? 0;
                    // $payment->transaction_id= $sc_pay_transaction->id;
                    $payment->relation_id = $invoice->id;
                    $payment->relation_type = "Sales Payment";
                    $payment->amount = round($request->paid_amount,2);
                    $payment->date = date('Y-m-d');
                    $payment->note = $request->order_note;
                    $payment->save();

                    $pay_trans = New AccountTransaction;
                    $pay_trans->amount = round($request->paid_amount,2);
                    $pay_trans->account_id = $balance_account->account_head_id;
                    $pay_trans->type = "debit";
                    $pay_trans->sub_type = "Sales";
                    $pay_trans->reason = "Sales Payment";
                    $pay_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                    $pay_trans->relation_id = $invoice->id;
                    $pay_trans->relation_with = "Sales";
                    $pay_trans->payment_id = $payment->id;
                    $pay_trans->trans_id = $sc_trans->id;
                    $pay_trans->save();
                    $payment->transaction_id= $pay_trans->id;
                    $payment->save();
                    $sc_trans->trans_id = $pay_trans->id;
                    $sc_trans->save();
                    if( $invoice->due_amount > 0){
                        $salesDueHead = AccountHead::where("code",'1000')->first();

                        $sc_due_transaction = New AccountTransaction;
                        $sc_due_transaction->amount = round($request->grand_total-$request->paid_amount,2);
                        $sc_due_transaction->account_id = $salesDueHead->id;
                        $sc_due_transaction->type = "debit";
                        $sc_due_transaction->sub_type = "Sales";
                        $sc_due_transaction->reason = "Sale Product To Customer With Due";
                        $sc_due_transaction->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                        $sc_due_transaction->relation_id = $invoice->id;
                        $sc_due_transaction->relation_with = "Sales";
                        $sc_due_transaction->is_trans2 = 2;
                        $sc_due_transaction->trans_id = $sc_trans->id;
                        $sc_due_transaction->save();
                        $sc_trans->is_trans2 = 1;
                        $sc_trans->trans2_id = $sc_due_transaction->id;
                        $sc_trans->save();
                    }


                }
            }

            DB::commit();

            $notification=array(
                'message'=>"Invoice Successfully Completed",
                'alert-type'=>'success'
            );

            return redirect()->route('invoice.print_instant',$invoice->id)->with($notification);
            return redirect()->route('invoice.index')->with($notification);
        }catch (\Exception $e){
            DB::rollBack();
           // dd($e->getMessage());
            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }

    function printInvoice($id){
        $data['invoice']=Invoice::find($id);
        return view('Inventory.invoice.print_invoice',$data);
    }
    function printInvoiceInstant($id){
        $data['invoice']=Invoice::find($id);
        return view('Inventory.invoice.print_invoice_instant',$data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(can_p('invoice.edit') == false){
            return redirect()->route('dashboard');
        }
        $data['invoice'] =$invoice = Invoice::find($id);
        // dd($invoice->items);
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        // dd($purchase->items);
        return view ('Inventory.invoice.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(can_p('invoice.edit') == false){
            return redirect()->route('dashboard');
        }
        $validator_arr=[];
        $validator_e_msgg_arr=[];
        $is_valid_product = 0;
        if(auth()->user()?->business?->business_type_id == 15){
            if(array_search('hr-payroll',$this->pack_option()) != false){
                $validator_arr["dsr"] =["required"];
                $validator_e_msgg_arr["dsr"] = "DSR is required";
                // $validator_arr["asr"] =["required"];
                // $validator_e_msgg_arr["asr"] = "ASR is required";
                // $validator_arr["driver"] =["required"];
                // $validator_e_msgg_arr["driver"] = "Driver is required";
            }
        }
        if(array_search('accounts',$this->pack_option()) != false){
            $this->init_account();
            if($request->paid_amount > 0){
                $validator_arr["payment_method"] = ["required"];
                $validator_arr["account"] = ["required"];
            }
        }else{
            if($request->paid_amount > 0){
                $validator_arr["payment_method"] = ["required"];
            }
        }

        $validator_arr["unit.*"] =["required"];
        if($request->product_id || $request->old_product_id){

        }else{

            $validator_arr["product.0"] =["required"];
            $validator_e_msgg_arr["product.0"] = "Product is required";
        }




        // $validator_arr["email"] = ["required"];
        // $validator_arr["name"] = ["required"];
        // $validator_arr["branch"] = ["required"];
        // $validator_e_msgg_arr["email"] = "Customer Email is required";
        // $validator_arr["mobile"] = ["required"];
        // $validator_e_msgg_arr["mobile"] = "Customer Mobile is required";
        $validator = Validator::make($request->all(),
            $validator_arr,
            $validator_e_msgg_arr
            // "color.*"  => "required|string|distinct|min:3",
        );
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->with("cus_errors",$validator->errors()->all())->withInput($request->all());
        }



        try{
            DB::beginTransaction();
            $customer = Customer::where('email', $request->email)->first();
            if($customer == null){
                $customer = new Customer();
                $customer->email = $request->email;
                $customer->name = $request->name;
                $customer->mobile = $request->mobile;
                $customer->address = $request->address;
                $customer->save();
            }
            $invoice = Invoice::find($id);
            // $purchase->reference_no = 'pr-' . date("Ymd") . '-'. date("his");
            // $purchase->branch_id = $request->branch;
            $invoice->dsr_id = $request->dsr ?? 0;
            $invoice->asr_id = $request->asr ?? 0;
            $invoice->sld_id = $request->driver ?? 0;
            $invoice->customer_id= $customer->id;
            $invoice->item = $request->item;
            $invoice->total_qty = $request->total_qty;
            $invoice->total_discount =  round($request->total_discount,2);
            $invoice->total_cost = round($request->total_cost,2);
            $invoice->total_p_cost = round($request->total_p_cost,2);
            $invoice->order_discount = round($request->order_discount,2);
            $invoice->shipping_cost = round($request->shipping_cost,2);
            $invoice->total_tax =round($request->total_tax,2);
             $old_paid_amount = round($invoice->paid_amount,2);
            $invoice->paid_amount =  round($request->paid_amount,2);
            $invoice->due_amount = round($request->grand_total,2) - round($request->paid_amount,2);
            $invoice->grand_total = round($request->grand_total,2);
            $invoice->status = $request->status;
            $invoice->payment_method= $request->payment_method ?? 0;
            $invoice->is_ini_p= $request->is_ini_p ?? 0;
            $invoice->bank_account_id= $request->account ?? 0;
            if(array_search('accounts',$this->pack_option()) != false){
                if($request->payment_method != ''){
                    if($invoice->due_amount == 0){
                        $invoice->payment_status = 2;
                    }else{
                        $invoice->payment_status = 1;
                    }
                }else{
                    $invoice->payment_status = 0;
                }
            }else{
                if($request->payment_method != ''){
                    if($invoice->due_amount == 0){
                        $invoice->payment_status = 2;
                    }else{
                        $invoice->payment_status = 1;
                    }
                }else{
                    $invoice->payment_status = 0;
                }
                //$invoice->payment_status = $request->payment_status ?? 0;
            }
            $invoice->total_tax = $request->total_tax;
            $invoice->note = $request->order_note;
            if ($request->invoice_date == null) {
                $invoice->invoice_date = now();
            } else {
                $invdate = Carbon::parse($request->invoice_date)->format('Y-m-d');
                $invoice->invoice_date = $invdate;
            }
            $invoice->save();
            //dd($request->color);
            if($request->old_product_id){
                // dd($request->old_product_id);
                foreach($request->old_product_id as $k=>$product_id) {

                    $product_invoice = ProductInvoice::find($k);
                    $old_qty = $product_invoice->qty;
                    $product_invoice->invoice_id = $invoice->id;
                    $product_invoice->product_id = $product_id;
                    // $product_invoice->color_id = $request->old_color[$k] ?? 0;
                    // $product_invoice->size_id = $request->old_size[$k] ?? 0;
                    $product_invoice->unit_id = $request->old_unit[$k];
                    $product_invoice->qty = $request->old_qty[$k] ?? 0;
                    $product_invoice->per_cost = round($request->old_per_cost[$k] ?? 0,2);
                    $product_invoice->purchase_price = round($request->old_purchase_price[$k] ?? 0,2);
                    $product_invoice->tax = round($request->tax[$k] ?? 0,2);
                    $product_invoice->total = round($request->old_total[$k] ?? 0,2);
                    $product_invoice->total_purchase = round($request->old_total_purchase[$k] ?? 0,2);
                    $product_invoice->discount = round($request->old_discount[$k] ?? 0,2);
                    $product_invoice->save();
                    $stock =  Stock::where('product_invoice_id',$k)->first();
                    // $stock->invoice_id = $invoice->id;
                    // $stock->product_invoice_id = $product_invoice->id;
                    $stock->product_id = $product_id;
                    // $stock->color_id = $request->old_color[$k] ?? 0;
                    // $stock->size_id = $request->old_size[$k] ?? 0;
                    $stock->unit_id = $request->old_unit[$k] ?? 0;
                    $stock->out_qty = $request->old_qty[$k]?? 0;
                    $stock->sale_price = $request->old_total[$k] ?? 0;
                    $stock->inventory_type = 'Sales';
                    $stock->save();


                    $product_stock =  Product::find($product_id);
                    if($product_stock){
                        $product_stock->qty= $product_stock->qty + $old_qty - ($request->old_qty[$k]?? 0);
                        $product_stock->save();
                    }

                }
            }
            if($request->delete_item){
                foreach($request->delete_item as $k=>$item) {
                    $stock =  Stock::where('product_invoice_id',$item)->first();
                    $stock->delete();
                    $product_invoice = ProductInvoice::find($item);

                    $product_stock =  Product::find($product_id);
                    if($product_stock){
                        $product_stock->qty= $product_stock->qty + $product_invoice->qty;
                        $product_stock->save();
                    }

                    $product_invoice->delete();
                }
            }
            if($request->product_id){
                foreach($request->product_id as $k=>$product_id) {
                    if(null != $product_id){
                        $product_invoice = New ProductInvoice;
                        $product_invoice->invoice_id = $invoice->id;
                        $product_invoice->product_id = $product_id;
                        // $product_invoice->color_id = $request->color[$k] ?? 0;
                        // $product_invoice->size_id = $request->size[$k] ?? 0;
                        $product_invoice->unit_id = $request->unit[$k];
                        $product_invoice->tax = round($request->tax[$k] ?? 0,2);
                        $product_invoice->qty = $request->qty[$k] ?? 0;
                        $product_invoice->per_cost = round($request->per_cost[$k] ?? 0,2);
                        $product_invoice->purchase_price = round($request->purchase_price[$k] ?? 0,2);
                        $product_invoice->total = round($request->total[$k] ?? 0,2);
                        $product_invoice->total_purchase = round($request->total_purchase[$k] ?? 0,2);
                        $product_invoice->discount = round($request->discount[$k] ?? 0,2);
                        $product_invoice->save();
                        $stock = new Stock;
                        $stock->invoice_id = $invoice->id;
                        $stock->product_invoice_id = $product_invoice->id;
                        $stock->product_id = $product_id;

                        // $stock->color_id = $request->color[$k] ?? 0;
                        // $stock->size_id = $request->size[$k] ?? 0;
                        $stock->unit_id = $request->unit[$k] ?? 0;
                        $stock->out_qty = $request->qty[$k]?? 0;
                        $stock->sale_price = $request->total[$k] ?? 0;
                        $stock->inventory_type = 'Sales';
                        $stock->save();
                        $product_stock =  Product::find($product_id);
                        if($product_stock){
                            $product_stock->qty= $product_stock->qty - ($request->qty[$k]?? 0);
                            $product_stock->save();
                        }


                    }
                }
            }
            if(array_search('accounts',$this->pack_option()) != false){
                $salesHead = AccountHead::where("code",'4000')->first();

                $sc_trans =  AccountTransaction::where("relation_id",$invoice->id)->where('relation_with','Sales')->where('account_id',$salesHead->id)->first();
                if($sc_trans == null){
                    $sc_trans = New AccountTransaction;
                }
                $sc_trans->amount = round($request->grand_total,2);
                $sc_trans->account_id = $salesHead->id;
                $sc_trans->type = "credit";
                $sc_trans->sub_type = "Sales";
                $sc_trans->reason = "Sale Product To Customer";
                $sc_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                $sc_trans->relation_id = $invoice->id;
                $sc_trans->relation_with = "Sales";
                $sc_trans->save();


                $payment = Payment::where("relation_id",$invoice->id)->where("relation_type",'Sales Payment')->first();
                if($payment){
                    if($request->payment_method == ''){
                        $pay_trans =  AccountTransaction::find($payment->transaction_id);
                        if($pay_trans){
                            $sc_due_transaction = AccountTransaction::find($sc_trans->trans2_id);
                            if($sc_due_transaction){
                                $sc_due_transaction->delete();
                            }
                            $pay_trans->delete();
                        }
                        $payment->delete();

                        $acReceivableHead = AccountHead::where("code",'1000')->first();
                        $due_trans = New AccountTransaction;
                        $due_trans->amount = round($request->grand_total,2);
                        $due_trans->account_id = $acReceivableHead->id;
                        $due_trans->type = "debit";
                        $due_trans->sub_type = "Sales";
                        $due_trans->reason = "Sale Product To Customer With Due";
                        $due_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                        $due_trans->relation_id = $invoice->id;
                        $due_trans->relation_with = "Sales";
                        $due_trans->trans_id = $sc_trans->id;
                        $due_trans->save();
                        $sc_trans->trans_id = $due_trans->id;
                        $sc_trans->save();
                    }else{
                        $balance_account = BalanceAccount::find($request->account);
                        $payment->payment_method= $request->payment_method ?? 0;
                        $payment->bank_account_id= $request->account ?? 0;
                        // $payment->transaction_id= $sc_pay_transaction->id;
                        $payment->relation_id = $invoice->id;
                        $payment->relation_type = "Sales Payment";
                        $payment->amount = round($request->paid_amount,2);
                        $payment->date = date('Y-m-d');
                        $payment->note = $request->order_note;
                        $payment->save();

                        $pay_trans = AccountTransaction::find($payment->transaction_id);
                        $pay_trans->amount = round($request->paid_amount,2);
                        $pay_trans->account_id = $balance_account->account_head_id;
                        $pay_trans->type = "debit";
                        $pay_trans->sub_type = "Sales";
                        $pay_trans->reason = "Sales Payment";
                        $pay_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                        $pay_trans->relation_id = $invoice->id;
                        $pay_trans->relation_with = "Sales";
                        $pay_trans->payment_id = $payment->id;
                        $pay_trans->trans_id = $sc_trans->id;
                        $pay_trans->save();
                        $payment->transaction_id= $pay_trans->id;
                        $payment->save();
                        $sc_trans->trans_id = $pay_trans->id;
                        $sc_trans->save();
                        if( $invoice->due_amount > 0){
                            $salesDueHead = AccountHead::where("code",'1000')->first();

                            $sc_due_transaction = AccountTransaction::find($sc_trans->trans2_id);
                            if($sc_due_transaction == null){
                                $sc_due_transaction = new AccountTransaction;
                            }
                            $sc_due_transaction->amount = round($request->grand_total-$request->paid_amount,2);
                            $sc_due_transaction->account_id = $salesDueHead->id;
                            $sc_due_transaction->type = "debit";
                            $sc_due_transaction->sub_type = "Sales";
                            $sc_due_transaction->reason = "Sale Product To Customer With Due";
                            $sc_due_transaction->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                            $sc_due_transaction->relation_id = $invoice->id;
                            $sc_due_transaction->relation_with = "Sales";
                            $sc_due_transaction->is_trans2 = 2;
                            $sc_due_transaction->trans_id = $sc_trans->id;
                            $sc_due_transaction->save();
                            $sc_trans->is_trans2 = 1;
                            $sc_trans->trans2_id = $sc_due_transaction->id;
                            $sc_trans->save();
                        }
                    }
                }else{
                    if($request->payment_method == ''){
                        $acReceivableHead = AccountHead::where("code",'1000')->first();
                        $due_trans =  AccountTransaction::find($sc_trans->trans_id);
                        if( $due_trans == null){
                            $due_trans = new AccountTransaction;
                        }
                        $due_trans->amount = round($request->grand_total,2);
                        $due_trans->account_id = $acReceivableHead->id;
                        $due_trans->type = "debit";
                        $due_trans->sub_type = "Sales";
                        $due_trans->reason = "Sale Product To Customer With Due";
                        $due_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                        $due_trans->relation_id = $invoice->id;
                        $due_trans->relation_with = "Sales";
                        $due_trans->trans_id = $sc_trans->id;
                        $due_trans->save();
                        $sc_trans->trans_id = $due_trans->id;
                        $sc_trans->save();
                    }else{
                        $balance_account = BalanceAccount::find($request->account);
                        $payment = New Payment;
                        $payment->payment_method= $request->payment_method ?? 0;
                        $payment->bank_account_id= $request->account ?? 0;
                        // $payment->transaction_id= $sc_pay_transaction->id;
                        $payment->relation_id = $invoice->id;
                        $payment->relation_type = "Sales Payment";
                        $payment->amount = round($request->paid_amount,2);
                        $payment->date = date('Y-m-d');
                        $payment->note = $request->order_note;
                        $payment->save();

                        $pay_trans = New AccountTransaction;
                        $pay_trans->amount = round($request->paid_amount,2);
                        $pay_trans->account_id = $balance_account->account_head_id;
                        $pay_trans->type = "debit";
                        $pay_trans->sub_type = "Sales";
                        $pay_trans->reason = "Sales Payment";
                        $pay_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                        $pay_trans->relation_id = $invoice->id;
                        $pay_trans->relation_with = "Sales";
                        $pay_trans->payment_id = $payment->id;
                        $pay_trans->trans_id = $sc_trans->id;
                        $pay_trans->save();
                        $payment->transaction_id= $pay_trans->id;
                        $payment->save();
                        $sc_trans->trans_id = $pay_trans->id;
                        $sc_trans->save();
                        if( $invoice->due_amount > 0){
                            $salesDueHead = AccountHead::where("code",'1000')->first();

                            $sc_due_transaction = New AccountTransaction;
                            $sc_due_transaction->amount = round($request->grand_total-$request->paid_amount,2);
                            $sc_due_transaction->account_id = $salesDueHead->id;
                            $sc_due_transaction->type = "debit";
                            $sc_due_transaction->sub_type = "Sales";
                            $sc_due_transaction->reason = "Sale Product To Customer With Due";
                            $sc_due_transaction->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                            $sc_due_transaction->relation_id = $invoice->id;
                            $sc_due_transaction->relation_with = "Sales";
                            $sc_due_transaction->is_trans2 = 2;
                            $sc_due_transaction->trans_id = $sc_trans->id;
                            $sc_due_transaction->save();
                            $sc_trans->is_trans2 = 1;
                            $sc_trans->trans2_id = $sc_due_transaction->id;
                            $sc_trans->save();
                        }
                    }
                }


            }
            DB::commit();
            $notification=array(
                'message'=>"Invoice Successfully Updated",
                'alert-type'=>'success'
            );
            return redirect()->route('invoice.index')->with($notification);
        }catch (\Exception $e){
           dd($e->getMessage());
            DB::rollBack();
            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    function invoiceDetail($id){
        if(can_p('invoice.view') == false){
            return redirect()->route('dashboard');
        }
         $data['invoice'] = Invoice::find($id);
        return view ('Inventory.invoice.ajax-view-data', $data);
    }
    function paymentList($id){
        if(can_p('invoice.payment_show') == false){
            return redirect()->route('dashboard');
        }
         $data['invoice'] = Invoice::find($id);
         $data['payments'] = Payment::where('relation_id', $id)->where('relation_type','Sales Payment')->get();
        //return $data;
        return view('Inventory.invoice.ajax-view-data-payment', $data);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(can_p('invoice.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $invoice = Invoice::find($id);
            $account_payments= AccountTransaction::where('relation_id', $id)->where('relation_with','Sales')->get();
           // dd($account_payments);
            // dd($purchase);
            foreach ($invoice->items as $item) {
                $product_stock =  Product::find($item->product_id);
                if($product_stock){
                    $product_stock->qty= $product_stock->qty + $item->qty;
                    $product_stock->save();
                }
                $item->delete();
            }
            $payments =  Payment::where('relation_id', $id)->where('relation_type','Invoice Payment')->get();
            foreach ($payments as $item) {
                $item->delete();
            }
            $account_payments= AccountTransaction::where('relation_id', $id)->where('relation_with','Sales')->get();
            foreach ($account_payments as $item) {
                $item->delete();
            }
            $stocks = Stock::where('invoice_id',$invoice->id)->get();

            foreach ($stocks as $item) {
                $item->delete();
            }
            $invoice->delete();
            DB::commit();
            $notification=array(
                'message'=>"Invoice Successfully Delete",
                'alert-type'=>'success'
            );
            return redirect()->route('invoice.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
             $notification=array(
                'message'=>"Can not delete this!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }

    }
    function storePayment(Request $request){
        if(can_p('invoice.add-payment') == false){
            return redirect()->route('dashboard');
        }
        $validator = Validator::make($request->all(),[
            'payment_method'=>'required',
            'account'=>'required',
            'payment_date'=>'required',
            'amount'=>'required|numeric|min:0|max:'.$request->due_amount,
            "invoice_id" => 'required'
        ],[
            'amount.min' => 'Amount Should be grater than 0',
            'amount.max' => 'Amount Should be less or equal '.$request->due_amount,
        ]);
        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
        //return $request->all();
        try{
            DB::beginTransaction();
            $invoice = Invoice::find($request->invoice_id);
            $invoice->paid_amount = round($invoice->paid_amount+$request->amount,2);
            $invoice->due_amount = round($invoice->grand_total - $invoice->paid_amount,2);
            if($invoice->due_amount == 0){
                $invoice->payment_status = 2;
            }else{
                $invoice->payment_status = 1;
            }
            $invoice->save();

            $payment = New Payment;
            $payment->payment_method= $request->payment_method ?? 0;
            $payment->bank_account_id= $request->account ?? 0;
            // $payment->transaction_id= $sc_pay_transaction->id;
            $payment->relation_id = $invoice->id;

            $payment->relation_type = "Sales Payment";
            $payment->amount = round($request->amount,2);
            $payment->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $payment->note = $request->payment_note;
            $payment->save();

            $salesDueHead = AccountHead::where("code",'1000')->first();

            $sc_due_transaction = New AccountTransaction;
            $sc_due_transaction->amount = round($request->amount,2);
            $sc_due_transaction->account_id = $salesDueHead->id;
            $sc_due_transaction->type = "credit";
            $sc_due_transaction->sub_type = "Sales Payment";
            $sc_due_transaction->reason =  "Sales Payment For Invoice #".$invoice->reference_no;
            $sc_due_transaction->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $sc_due_transaction->relation_id = $invoice->id;
            $sc_due_transaction->relation_with = "Invoice";
            $sc_due_transaction->payment_id = $payment->id;
            $sc_due_transaction->save();
            $balance_account = BalanceAccount::find($request->account);

            $sc_pay_transaction = New AccountTransaction;
            $sc_pay_transaction->amount = round($request->amount,2);
            $sc_pay_transaction->account_id = $balance_account->account_head_id;
            $sc_pay_transaction->type = "debit";
            $sc_pay_transaction->sub_type = "Sales Payment";
            $sc_pay_transaction->reason = "Sales Payment For Invoice #".$invoice->reference_no;
            $sc_pay_transaction->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $sc_pay_transaction->relation_id = $invoice->id;
            $sc_due_transaction->relation_with = "Invoice";
            $sc_pay_transaction->payment_id = $payment->id;
             $sc_pay_transaction->trans_id = $sc_due_transaction->id;
            $sc_pay_transaction->save();
            $sc_due_transaction->trans_id = $sc_pay_transaction->id;
            $sc_due_transaction->save();
            DB::commit();
            return response([
                    'status' => 1,
                    'success' => 'Payment Paid successfully.',
                ]);
        }catch (\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'data'=> $e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }



    }
    function deletePayment(Request $request){
        if(can_p('invoice.delete-payment') == false){
            return redirect()->route('dashboard');
        }
        $payment = Payment::find($request->payment_id);
    }
}
