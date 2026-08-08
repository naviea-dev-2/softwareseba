<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use App\Models\Inventory\Invoice;
use App\Models\Inventory\InvoiceReturn;
use App\Models\Inventory\Payment;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductInvoiceReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Inventory\Stock;
use App\Models\ProductStock;

class InvoiceReturnController extends Controller
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
    function inital_account(){
        $salesReturnHead = AccountHead::where("code",'5001')->first();
        if($salesReturnHead == null){
            $salesReturnHead = new AccountHead;
            $salesReturnHead->title = "Sales Return";
            $salesReturnHead->code = '5001';
            $salesReturnHead->sys = 0;
            $salesReturnHead->ac_type = 5;
            $salesReturnHead->note = '';
            $salesReturnHead->status = 1;
            $salesReturnHead->save();
        }
        $acPayableHead = AccountHead::where("code",'2000')->first();
        if($acPayableHead == null){
            $acPayableHead = new AccountHead;
            $acPayableHead->code = '2000';
            $acPayableHead->title = "Account Payable";
            $acPayableHead->ac_type = 2;
            $acPayableHead->note = '';
            $acPayableHead->sys = 0;
            $acPayableHead->status = 1;
            $acPayableHead->save();
        }
        $acReceivaleHead = AccountHead::where("code",'1000')->first();
        if($acReceivaleHead == null){
            $acReceivaleHead = new AccountHead;
            $acReceivaleHead->code = '1000';
            $acReceivaleHead->title = "Account Receivable";
            $acReceivaleHead->ac_type = 1;
            $acReceivaleHead->note = '';
            $acReceivaleHead->sys = 0;
            $acReceivaleHead->status = 1;
            $acReceivaleHead->save();
        }
    }
    public function index()
    {
        if(can_p('invoice_return.index') == false){
            return redirect()->route('dashboard');
        }
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        //dd($invoice_returns);
        return view ('Inventory.invoice_return.manage', $data );
    }
    function ajaxInvoiceReturn(Request $request){
        if(auth()->user()->business->business_type_id == 15){
            $columns = array(
            0 => 'invoice_returns.id',
            1 => 'invoice_returns.return_date',
            2 => 'invoice_returns.reference_no',
            3 => 'invoice_returns.dsr_id',
            4 => 'customers.name',
            5 => 'invoice_returns.grand_total',
            6=> 'invoice_returns.paid_amount',
            7 => 'invoice_returns.due_amount',
            8 => 'invoice_returns.status',
            9 => 'invoice_returns.payment_status',
            10 => 'invoices.payment_method',
            11 => 'invoices.bank_account_id',
            12 => 'options',
            );
        }else{
            $columns = array(
                0 => 'invoice_returns.id',
                1 => 'invoice_returns.return_date',
                2 => 'invoice_returns.reference_no',
                3 => 'customers.name',
                4 => 'invoice_returns.grand_total',
                5 => 'invoice_returns.paid_amount',
                6 => 'invoice_returns.due_amount',
                7 => 'invoice_returns.status',
                8 => 'invoice_returns.payment_status',
                9 => 'invoices.payment_method',
                10 => 'invoices.bank_account_id',
                11 => 'options',
            );
        }
        $totalData = InvoiceReturn::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $invoices = InvoiceReturn::leftjoin('customers','customers.id','invoice_returns.customer_id');
        if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
            $invoices->where('branch_id',auth()->user()->branch_id);
        }
        if(!empty($search))
        {
            $invoices = $invoices->where("return_date","LIKE","%{$search}%");

        }
        $totalFiltered = $invoices->count();
        $invoices = $invoices->select('invoice_returns.*','customers.name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($invoices))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('invoice_return.add_edit');
            $p_delete = can_p('invoice_return.delete');
            $p_view = can_p('invoice_return.view');
            $p_add_payment = can_p('invoice_return.add-payment');
            $p_payment_show = can_p('invoice_return.payment_show');
            foreach($invoices as $invoice)
            {
                $nestedData['id'] = $i++;

                $nestedData['date'] = date('Y-m-d', strtotime($invoice->return_date));
                $nestedData['reference'] = $invoice->reference_no;
                $nestedData['cus_name'] = $invoice->customer?->name;
                $nestedData['dsr'] = $invoice->dsr?->employee_name;
                $nestedData['asr'] = $invoice->asr?->employee_name;
                $nestedData['driver'] = $invoice->driver?->employee_name;
                $nestedData['total'] = auth()->user()->currency_symbol . number_format($invoice->grand_total, 2);
                $nestedData['paid'] = auth()->user()->currency_symbol . number_format($invoice->paid_amount, 2);
                $nestedData['due'] = auth()->user()->currency_symbol . number_format($invoice->grand_total - $invoice->paid_amount, 2);
                $nestedData['method'] =$invoice?->method?->name;
                $nestedData['account'] =$invoice?->account?->account_name;
                $payment_status = '';
                if($invoice->payment_status == 0){
                    $payment_status = '<div class="badge bg-danger">Due</div>';
                }else if($invoice->payment_status == 1){
                    $payment_status = '<div class="badge bg-warning">Partial</div>';
                }else{
                    $payment_status = '<div class="badge bg-success">Paid</div>';
                }
                $nestedData['payment_status'] =$payment_status;
                $nestedData['options'] = '<div class="btn-group"><button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';
                if($p_view){
                    $nestedData['options'] .= ' <li><button data-id="'.$invoice->id.'" type="button" class="btn btn-link view"><i class="bx bx-show"></i>View</button> </li>';
                }
                if($p_edit){
                    if($invoice->invoice_id == 0){
                        $nestedData['options'] .= ' <li><a href="'. route('invoice_return.edit', $invoice->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';
                    }else{

                        $nestedData['options'] .= ' <li><a href="'. route('invoice_return.add_edit', $invoice->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';

                    }
                }
                if(array_search('accounts',$this->pack_option()) != false){
                    if($p_add_payment){
                        if($invoice->due_amount > 0){
                            $nestedData['options'] .= ' <li><button data-due="'. $invoice->due_amount .'" type="button" class="add-payment btn btn-link" data-id = "'. $invoice->id .'" data-bs-toggle="modal" data-bs-target="#add-payment"><i class="bx bx-plus"></i>Add Payment</button></li>';
                        }
                    }
                    if($p_payment_show){
                        if($invoice->payment_status != 0){
                            $nestedData['options'] .= ' <li><button type="button" class="payment_show btn btn-link" data-id = "'. $invoice->id .'"><i class="bx bx-money"></i> View Payment</button></li>';
                        }
                    }
                }
                if($p_delete){
                    $nestedData['options'] .= ' <li> <form action="'. route('invoice_return.delete',$invoice->id).'" method="post"><input type="hidden" name="_token" value="'. csrf_token().'"><button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="bx bx-trash"></i>Delete</button></form></li>';
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
    function addReturn(Request $request,$id){
        if(can_p('invoice_return.add') == false){
            return redirect()->route('dashboard');
        }
        $data['invoice'] = Invoice::find($id);
         $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view ('Inventory.invoice_return.add', $data);
    }
    function addReturnPost(Request $request,$id){
        if(can_p('invoice_return.add') == false){
            return redirect()->route('dashboard');
        }

       // dd($request->all());
        $validator_arr["return_unit.*"] =["required"];
        $validator_arr["return_date"] = ["required"];
        $validator_e_msgg_arr["return_date"] = "Return Date is required";
        $validator_arr["return_reason"] = ["required"];
        $validator_e_msgg_arr["return_reason"] = "Reason is required";
        $is_valid_qty = 1;
        foreach($request->return_qty as $r_qty){
            if($r_qty > 0){
                $is_valid_qty=0;
            }
        }
        if(array_search('accounts',$this->pack_option()) != false){
            $this->inital_account();
            if($request->paid_amount > 0){
                $validator_arr["payment_method"] = ["required"];
                $validator_arr["account"] = ["required"];
            }
        }else{
            if($request->paid_amount > 0){
                $validator_arr["payment_method"] = ["required"];
            }
        }

        if($is_valid_qty == 1){
            $validator_arr["return_quantity"] = ["required"];
            $validator_e_msgg_arr["return_quantity"] = "Return Qty is not Here";
        }

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
            $pre_invoice = Invoice::find($id);
            $invoice_return = New InvoiceReturn;
            $invoice_return->reference_no = 're-inv-' . date("Ymd") . '-'. date("his");
            $invoice_return->reason = $request->return_reason;

            $invoice_return->invoice_id = $pre_invoice->id;
            $invoice_return->branch_id = $pre_invoice->branch_id;
            $invoice_return->customer_id=$pre_invoice->customer_id;
            $invoice_return->dsr_id=$pre_invoice->dsr_id;
            $invoice_return->asr_id=$pre_invoice->asr_id;
            $invoice_return->sld_id=$pre_invoice->sld_id;
            $invoice_return->total_qty = $request->total_qty;
            $invoice_return->total_discount =  round($request->sub_discount,2);
            $invoice_return->total_cost = round($request->total_price,2);
            // $purchase->order_discount = $request->order_discount;
            // $purchase->shipping_cost = $request->shipping_cost;

            $invoice_return->total_tax = round($request->total_tax,2);
            $invoice_return->paid_amount = round($request->paid_amount,2);
            $invoice_return->due_amount = round($request->grand_total - $request->paid_amount,2);
            $invoice_return->grand_total = round($request->grand_total,2);
            $invoice_return->status = "Complete";
            $invoice_return->payment_method= $request->payment_method ?? 0;
            $invoice_return->bank_account_id= $request->account ?? 0;
            //if(array_search('accounts',$this->pack_option()) != false){
                if($request->payment_method != ''){
                    if($invoice_return->due_amount == 0){
                        $invoice_return->payment_status = 2;
                    }else{
                        $invoice_return->payment_status = 1;
                    }
                }else{
                    $invoice_return->payment_status = 0;
                }
           // }
            $file=$request->file('document');
            if($file){
                $filename=date('YmdHi')."_purchase_document".$file->getClientOriginalName();
                $file->move(public_path('upload/invoice_return'),$filename);
                $invoice_return->document=$filename;
            }
            $invoice_return->note = $request->order_note;
            if ($request->return_date == null) {
                $invoice_return->return_date = now();
            } else {
                $invdate = Carbon::parse($request->return_date)->format('Y-m-d');
                $invoice_return->return_date = $invdate;
            }
            //dd($purchase_return);
            $invoice_return->save();
            foreach($request->return_qty as $k=>$return_qty) {
                if(0 != $return_qty){

                    $product_invoice_return = New ProductInvoiceReturn;
                    $product_invoice_return->invoice_return_id = $invoice_return->id;
                    $product_invoice_return->product_id = $request->return_product[$k] ?? 0;
                    // $product_invoice_return->color_id = $request->return_color[$k] ?? 0;
                    // $product_invoice_return->size_id = $request->return_size[$k] ?? 0;
                    $product_invoice_return->unit_id = $request->return_unit[$k];
                    $product_invoice_return->qty = $return_qty;
                    $product_invoice_return->tax = round($request->return_tax[$k] ?? 0,2);
                    $product_invoice_return->tax_rate = $request->return_tax_rate[$k] ?? 0;
                    $product_invoice_return->per_cost = round($request->return_per_cost[$k] ?? 0,2);
                    $product_invoice_return->total = round($request->return_sub_total[$k] ?? 0,2);
                    $product_invoice_return->discount = round($request->return_discount[$k] ?? 0,2);
                     $product_invoice_return->discount_rate = $request->return_discount_rate[$k] ?? 0;
                    $product_invoice_return->save();
                    //dd($product_purchase_return);
                    $stock = new Stock;
                    $stock->invoice_return_id =$invoice_return->id;
                    $stock->product_invoice_return_id = $product_invoice_return->id;
                    $stock->product_id = $request->return_product[$k] ?? 0;
                    // $stock->color_id = $request->return_color[$k] ?? 0;
                    // $stock->size_id = $request->return_size[$k] ?? 0;
                    $stock->unit_id = $request->return_unit[$k] ?? 0;
                    $stock->out_qty = -$return_qty;
                    $stock->sale_price = -$request->return_sub_total[$k] ?? 0;
                    $stock->inventory_type = 'Sales Return';
                    $stock->save();
                    $product_stock =  Product::find($request->return_product[$k] ?? 0);
                    if($product_stock){
                        $product_stock->qty= $product_stock->qty + $return_qty;
                        $product_stock->save();
                    }

                }
            }
            if(array_search('accounts',$this->pack_option()) != false){
                $salesReturnHead = AccountHead::where("code",'5001')->first();

                $sr_trans = New AccountTransaction;
                $sr_trans->amount = round($request->grand_total,2);
                $sr_trans->account_id = $salesReturnHead->id;
                $sr_trans->type = "debit";
                $sr_trans->sub_type = "Sales Return";
                $sr_trans->reason = "Return Product From Customer ";
                $sr_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                $sr_trans->relation_id = $invoice_return->id;
                $sr_trans->relation_with = "Sales Return";
                $sr_trans->save();

                if($request->payment_method == ''){
                    if($pre_invoice->due_amount >= $invoice_return->due_amount){
                        $acReceivaleHead = AccountHead::where("code",'1000')->first();

                        $due_trans = New AccountTransaction;
                        $due_trans->amount = round($invoice_return->due_amount,2);
                        $due_trans->account_id = $acReceivaleHead->id;
                        $due_trans->type = "credit";
                        $due_trans->sub_type = "Sales Return";
                        $due_trans->reason = "Sales Return From Customer With Due";
                        $due_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                        $due_trans->relation_id = $invoice_return->id;
                        $due_trans->relation_with = "Sales Return";
                        $due_trans->trans_id = $sr_trans->id;
                        $due_trans->save();
                        $sr_trans->trans_id = $due_trans->id;
                        $sr_trans->save();
                    }else{
                        $due_advance = $invoice_return->due_amount-$pre_invoice->due_amount;
                        $acReceivaleHead = AccountHead::where("code",'1000')->first();


                        $sc_due_transaction = New AccountTransaction;
                        $sc_due_transaction->amount = $pre_invoice->due_amount;
                        $sc_due_transaction->account_id = $acReceivaleHead->id;
                        $sc_due_transaction->type = "credit";
                        $sc_due_transaction->sub_type = "Sales Return";
                        $sc_due_transaction->reason = "Sales Return From Customer With Due";
                        $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                        $sc_due_transaction->relation_id = $invoice_return->id;
                        $sc_due_transaction->relation_with = "Sales Return";
                        $sc_due_transaction->trans_id = $sr_trans->id;
                        $sc_due_transaction->save();
                        $sr_trans->trans_id = $sc_due_transaction->id;
                        $sr_trans->save();

                        $acpHead = AccountHead::where("code",'2000')->first();
                        $acr_trans = New AccountTransaction;
                        $acr_trans->amount = $due_advance;
                        $acr_trans->account_id = $acpHead->id;
                        $acr_trans->type = "credit";
                        $acr_trans->sub_type = "Sales Return";
                        $acr_trans->reason = "Sales Return From Customer With Due";
                        $acr_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                        $acr_trans->relation_id = $invoice_return->id;
                        $acr_trans->relation_with = "Sales Return";
                        $acr_trans->is_trans2 = 2;
                        $acr_trans->trans_id = $sr_trans->id;
                        $acr_trans->save();
                        $sr_trans->is_trans2 = 1;
                        $sr_trans->trans2_id = $acr_trans->id;
                        $sr_trans->save();
                    }
                }else{
                    $balance_account = BalanceAccount::find($request->account);
                    $payment = New Payment;
                    $payment->payment_method= $request->payment_method ?? 0;
                    $payment->bank_account_id= $request->account ?? 0;
                    // $payment->transaction_id= $sc_pay_transaction->id;
                    $payment->relation_id = $invoice_return->id;
                    $payment->relation_type = "Sales Return Payment";
                    $payment->amount = $request->paid_amount;
                    $payment->date = date('Y-m-d');
                    $payment->note = $request->order_note;
                    $payment->save();

                    $pay_trans = New AccountTransaction;
                    $pay_trans->amount = $request->paid_amount;
                    $pay_trans->account_id = $balance_account->account_head_id;
                    $pay_trans->type = "credit";
                    $pay_trans->sub_type = "Sales Return Payment";
                    $pay_trans->reason = "Sales Return Payment for invoice #". $invoice_return->reference_no;
                    $pay_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                    $pay_trans->relation_id = $invoice_return->id;
                    $pay_trans->payment_id = $payment->id;
                    $pay_trans->relation_with = "Sales Return";
                    $pay_trans->trans_id = $sr_trans->id;
                    $pay_trans->save();
                    $payment->transaction_id= $pay_trans->id;
                    $payment->save();
                    $sr_trans->trans_id = $pay_trans->id;
                    $sr_trans->save();
                    if($invoice_return->due_amount > 0){
                        if($pre_invoice->due_amount >= $invoice_return->due_amount){
                            $acpHead = AccountHead::where("code",'1000')->first();

                            $sc_due_transaction = New AccountTransaction;
                            $sc_due_transaction->amount = $invoice_return->due_amount;
                            $sc_due_transaction->account_id = $acpHead->id;
                            $sc_due_transaction->type = "credit";
                            $sc_due_transaction->sub_type = "Sales Return";
                            $sc_due_transaction->reason = "Sales Return From Customer With Due";
                            $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                            $sc_due_transaction->relation_id = $invoice_return->id;
                            $sc_due_transaction->relation_with = "Sales Return";
                            $sc_due_transaction->trans_id = $sr_trans->id;
                            $sc_due_transaction->is_trans2 = 2;
                            $sc_due_transaction->save();
                            $sr_trans->is_trans2 =1;
                            $sr_trans->trans2_id = $sc_due_transaction->id;
                            $sr_trans->save();
                        }else{
                            $due_advance = $invoice_return->due_amount-$pre_invoice->due_amount;

                            $acpHead = AccountHead::where("code",'1000')->first();

                            $sc_due_transaction = New AccountTransaction;
                            $sc_due_transaction->amount = $pre_invoice->due_amount;
                            $sc_due_transaction->account_id = $acpHead->id;
                            $sc_due_transaction->type = "credit";
                            $sc_due_transaction->sub_type = "Sales Return";
                            $sc_due_transaction->reason = "Sales Return From Customer With Due";
                            $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                            $sc_due_transaction->relation_id = $invoice_return->id;
                            $sc_due_transaction->relation_with = "Sales Return";
                            $sc_due_transaction->is_trans2 = 2;
                            $sc_due_transaction->trans_id = $sr_trans->id;
                            $sc_due_transaction->save();
                            $sr_trans->is_trans2 = 1;
                            $sr_trans->trans2_id = $sc_due_transaction->id;
                            $sr_trans->save();

                            $acReceivaleHead = AccountHead::where("code",'2000')->first();
                            $acr_trans = New AccountTransaction;
                            $acr_trans->amount = $due_advance;
                            $acr_trans->account_id = $acReceivaleHead->id;
                            $acr_trans->type = "credit";
                            $acr_trans->sub_type = "Sales Return";
                            $acr_trans->reason = "Sales Return From Customer With Due";
                            $acr_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                            $acr_trans->relation_id = $invoice_return->id;
                            $acr_trans->relation_with = "Sales Return";
                            $acr_trans->is_trans2 = 3;
                            $acr_trans->trans_id = $sr_trans->id;
                            $acr_trans->save();
                            $sr_trans->is_trans2 = 1;
                            $sr_trans->trans3_id = $acr_trans->id;
                            $sr_trans->save();
                        }
                    }


                }
            }
            DB::commit();
            $notification=array(
                'message'=>"Invoice Return Successfully Completed",
                'alert-type'=>'success'
            );
            return redirect()->route('invoice_return.print',$invoice_return->id)->with($notification);
            return redirect()->route('invoice_return.index')->with($notification);
         }catch (\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    function printInvoiceReturn($id){
        $data['invoice_return']=InvoiceReturn::find($id);
        return view('Inventory.invoice_return.print_invoice_return',$data);
    }
    function addReturnEdit(Request $request,$id){
        if(can_p('invoice_return.add_edit') == false){
            return redirect()->route('dashboard');
        }
        $data['invoice_return'] = InvoiceReturn::find($id);
         $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view ('Inventory.invoice_return.add_edit', $data);
    }
    function addReturnUpdate(Request $request,$id){
       // dd($request->all());
       if(can_p('invoice_return.add_edit') == false){
            return redirect()->route('dashboard');
        }
        $validator_arr["return_unit.*"] =["required"];
        $validator_arr["return_date"] = ["required"];
        $validator_e_msgg_arr["return_date"] = "Return Date is required";
        $validator_arr["return_reason"] = ["required"];
        $validator_e_msgg_arr["return_reason"] = "Reason is required";
        $is_valid_qty = 1;
        foreach($request->return_qty as $r_qty){
            if($r_qty > 0){
                $is_valid_qty=0;
            }
        }
        if($request->paid_amount > 0 || $request->payment_method != ''){
            $validator_arr["payment_method"] = ["required"];
            if(array_search('accounts',$this->pack_option()) != false){
                $validator_arr["account"] = ["required"];
            }
        }
        if($is_valid_qty == 1){
            $validator_arr["return_quantity"] = ["required"];
            $validator_e_msgg_arr["return_quantity"] = "Return Qty is not Here";
        }

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

            $invoice_return = InvoiceReturn::find($id);
            $pre_invoice = Invoice::find($invoice_return->invoice_id);
            $invoice_return->reference_no = 're-pr-' . date("Ymd") . '-'. date("his");
            $invoice_return->reason = $request->return_reason;
            $invoice_return->total_qty = $request->total_qty;
            $invoice_return->total_discount =  $request->sub_discount;
            $invoice_return->total_cost = $request->total_price;

            $invoice_return->branch_id = $pre_invoice->branch_id;
            $invoice_return->customer_id=$pre_invoice->customer_id;
            $invoice_return->dsr_id=$pre_invoice->dsr_id;
            $invoice_return->asr_id=$pre_invoice->asr_id;
            $invoice_return->sld_id=$pre_invoice->sld_id;
            // $purchase->order_discount = $request->order_discount;
            // $purchase->shipping_cost = $request->shipping_cost;

            $invoice_return->total_tax = $request->total_tax;
            $invoice_return->paid_amount = $request->paid_amount;
            $invoice_return->due_amount = $request->grand_total - $request->paid_amount;
            $invoice_return->grand_total = $request->grand_total;
            $invoice_return->payment_method= $request->payment_method ?? 0;
            $invoice_return->bank_account_id= $request->account ?? 0;
            $invoice_return->status = "Complete";
            if(array_search('accounts',$this->pack_option()) != false){
                if($request->payment_method != ''){
                    if($invoice_return->due_amount == 0){
                        $invoice_return->payment_status = 2;
                    }else{
                        $invoice_return->payment_status = 1;
                    }
                }else{
                    $invoice_return->payment_status = 0;
                }
            }else{
                if($request->payment_method != ''){
                    if($invoice_return->due_amount == 0){
                        $invoice_return->payment_status = 2;
                    }else{
                        $invoice_return->payment_status = 1;
                    }
                }else{
                    $invoice_return->payment_status = 0;
                }
                // $invoice_return->payment_status = $request->payment_status;
            }
            $file=$request->file('document');
            if($file){
                $filename=date('YmdHi')."_purchase_document".$file->getClientOriginalName();
                $file->move(public_path('upload/invoice_return'),$filename);
                $invoice_return->document=$filename;
            }
            $invoice_return->note = $request->order_note;
            if ($request->return_date == null) {
                $invoice_return->return_date = now();
            } else {
                $invdate = Carbon::parse($request->return_date)->format('Y-m-d');
                $invoice_return->return_date = $invdate;
            }
            //dd($purchase_return);
            $invoice_return->save();
            foreach($request->return_qty as $k=>$return_qty) {
                $product_invoice_return = ProductInvoiceReturn::where('product_id',$request->return_product[$k] ?? 0)->where('color_id',$request->return_color[$k] ?? 0)->where('size_id',$request->return_size[$k] ?? 0)->where('invoice_return_id',$invoice_return->id)->first();
                if($product_invoice_return == null){
                    $old_qty = 0;
                }else{
                    $old_qty = $product_invoice_return->qty;
                }
                if(0 != $return_qty){

                    if($product_invoice_return == null){
                        $product_invoice_return = New ProductInvoiceReturn;
                    }
                    $product_invoice_return->invoice_return_id = $invoice_return->id;
                    $product_invoice_return->product_id = $request->return_product[$k] ?? 0;
                    // $product_invoice_return->color_id = $request->return_color[$k] ?? 0;
                    // $product_invoice_return->size_id = $request->return_size[$k] ?? 0;
                    $product_invoice_return->unit_id = $request->return_unit[$k];
                    $product_invoice_return->qty = $return_qty;
                    $product_invoice_return->tax = $request->return_tax[$k] ?? 0;
                    $product_invoice_return->tax_rate = $request->return_tax_rate[$k] ?? 0;
                    $product_invoice_return->per_cost = $request->return_per_cost[$k] ?? 0;
                    $product_invoice_return->total = $request->return_sub_total[$k] ?? 0;
                    $product_invoice_return->discount = $request->return_discount[$k] ?? 0;
                     $product_invoice_return->discount_rate = $request->return_discount_rate[$k] ?? 0;
                    $product_invoice_return->save();
                    //dd($product_purchase_return);
                    $stock = Stock::where('product_invoice_return_id',$product_invoice_return->id)->first();
                    if($stock == null){
                        $stock = new Stock();
                    }

                    $stock->invoice_return_id =$invoice_return->id;
                    $stock->product_invoice_return_id = $product_invoice_return->id;
                    $stock->product_id = $request->return_product[$k] ?? 0;
                    // $stock->color_id = $request->return_color[$k] ?? 0;
                    // $stock->size_id = $request->return_size[$k] ?? 0;
                    $stock->unit_id = $request->return_unit[$k] ?? 0;
                    $stock->out_qty = -$return_qty;
                    $stock->sale_price = -$request->return_sub_total[$k] ?? 0;
                    $stock->inventory_type = 'Sales Return';
                    $stock->save();

                }else{

                    if($product_invoice_return){


                        $stock = Stock::where('product_invoice_return_id',$product_invoice_return->id)->first();
                        $stock->delete();
                        $product_invoice_return->delete();
                    }
                }
                $product_stock =  Product::find($request->return_product[$k] ?? 0);
                if($product_stock){
                    $product_stock->qty=  $product_stock->qty - $old_qty + $return_qty;
                    $product_stock->save();
                }


            }
            if(array_search('accounts',$this->pack_option()) != false){
                $purchasReturnHead = AccountHead::where("code",'5001')->first();

                $p_return_trans = AccountTransaction::where('relation_id',$invoice_return->id)->where('relation_with','Sales Return')->where('account_id',$purchasReturnHead->id)->first();
                if($p_return_trans == null){
                    $p_return_trans = New AccountTransaction;
                }
                $p_return_trans->amount = $request->grand_total;
                $p_return_trans->account_id = $purchasReturnHead->id;
                $p_return_trans->type = "debit";
                $p_return_trans->sub_type = "Sales Return";
                $p_return_trans->reason = "Return Product From Cutomer ";
                $p_return_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                $p_return_trans->relation_id = $invoice_return->id;
                $p_return_trans->relation_with = "Sales Return";
                $p_return_trans->save();
            }

                // if($request->payment_method == ''){
                //     if($pre_invoice->due_amount >= $invoice_return->due_amount){
                //         $acpHead = AccountHead::where("code",'1000')->first();

                //         $sc_due_transaction =  AccountTransaction::find( $p_return_trans->trans_id);
                //         $sc_due_transaction->amount = $invoice_return->due_amount;
                //         $sc_due_transaction->account_id = $acpHead->id;
                //         $sc_due_transaction->type = "debit";
                //         $sc_due_transaction->sub_type = "Sales Return";
                //         $sc_due_transaction->reason = "Sales Return From Customer With Due";
                //         $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //         $sc_due_transaction->relation_id = $invoice_return->id;
                //         $sc_due_transaction->relation_with = "Sales Return";
                //         $sc_due_transaction->trans_id = $p_return_trans->id;
                //         $sc_due_transaction->save();
                //         $p_return_trans->trans_id = $sc_due_transaction->id;
                //         $p_return_trans->save();
                //     }else{
                //         $due_advance = $invoice_return->due_amount-$pre_invoice->due_amount;

                //         $acpHead = AccountHead::where("code",'1000')->first();

                //         $sc_due_transaction =  AccountTransaction::find( $p_return_trans->trans_id);
                //         $sc_due_transaction->amount = $pre_invoice->due_amount;
                //         $sc_due_transaction->account_id = $acpHead->id;
                //         $sc_due_transaction->type = "debit";
                //         $sc_due_transaction->sub_type = "Sales Return";
                //         $sc_due_transaction->reason = "Sales Return From Customer With Due";
                //         $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //         $sc_due_transaction->relation_id = $invoice_return->id;
                //         $sc_due_transaction->relation_with = "Sales Return";
                //         $sc_due_transaction->trans_id = $p_return_trans->id;
                //         $sc_due_transaction->save();
                //         $p_return_trans->trans_id = $sc_due_transaction->id;
                //         $p_return_trans->save();

                //         $acReceivaleHead = AccountHead::where("code",'2000')->first();
                //         $acr_trans = AccountTransaction::find( $p_return_trans->trans2_id);
                //         $acr_trans->amount = $due_advance;
                //         $acr_trans->account_id = $acReceivaleHead->id;
                //         $acr_trans->type = "credit";
                //         $acr_trans->sub_type = "Sales Return";
                //         $acr_trans->reason = "Sales Return From Customer With Due";
                //         $acr_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //         $acr_trans->relation_id = $invoice_return->id;
                //         $acr_trans->relation_with = "Sales Return";
                //         $acr_trans->is_trans2 = 2;
                //         $acr_trans->trans_id = $p_return_trans->id;
                //         $acr_trans->save();
                //         $p_return_trans->is_trans2 = 1;
                //         $p_return_trans->trans2_id = $acr_trans->id;
                //         $p_return_trans->save();
                //     }


                // }else{
                //     $pay_trans = AccountTransaction::find( $p_return_trans->trans_id);
                //     $balance_account = BalanceAccount::find($request->account);
                //     $payment = Payment::where('transaction_id',$pay_trans->id);
                //     $payment->payment_method= $request->payment_method;
                //     $payment->bank_account_id= $request->account;
                //     // $payment->transaction_id= $sc_pay_transaction->id;
                //     $payment->relation_id = $invoice_return->id;
                //     $payment->relation_type = "Sales Return Payment";
                //     $payment->amount = $request->paid_amount;
                //     $payment->date = date('Y-m-d');
                //     $payment->note = $request->order_note;
                //     $payment->save();


                //     $pay_trans->amount = $request->paid_amount;
                //     $pay_trans->account_id = $balance_account->account_head_id;
                //     $pay_trans->type = "credit";
                //     $pay_trans->sub_type = "Sales Return Payment";
                //     $pay_trans->reason = "Sales Return Payment for invoice #". $invoice_return->reference_no;
                //     $pay_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //     $pay_trans->relation_id = $invoice_return->id;
                //     $pay_trans->payment_id = $payment->id;
                //     $pay_trans->relation_with = "Sales Return";
                //     $pay_trans->trans_id = $p_return_trans->id;
                //     $pay_trans->save();
                //     $payment->transaction_id= $pay_trans->id;
                //     $payment->save();
                //     $p_return_trans->trans_id = $pay_trans->id;
                //     $p_return_trans->save();


                //     if($invoice_return->due_amount > 0){
                //         if($pre_invoice->due_amount >= $invoice_return->due_amount){
                //             $acpHead = AccountHead::where("code",'1000')->first();

                //             $sc_due_transaction = AccountTransaction::find( $p_return_trans->trans2_id);
                //             $sc_due_transaction->amount = $invoice_return->due_amount;
                //             $sc_due_transaction->account_id = $acpHead->id;
                //             $sc_due_transaction->type = "debit";
                //             $sc_due_transaction->sub_type = "Sales Return";
                //             $sc_due_transaction->reason = "Sales Return From Customer With Due";
                //             $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //             $sc_due_transaction->relation_id = $invoice_return->id;
                //             $sc_due_transaction->relation_with = "Sales Return";
                //             $sc_due_transaction->trans_id = $p_return_trans->id;
                //             $sc_due_transaction->is_trans2 = 2;
                //             $sc_due_transaction->save();
                //             $p_return_trans->is_trans2 = 1;
                //             $p_return_trans->trans2_id = $sc_due_transaction->id;
                //             $p_return_trans->save();
                //         }else{
                //             $due_advance = $invoice_return->due_amount-$pre_invoice->due_amount;

                //             $acpHead = AccountHead::where("code",'2000')->first();

                //             $sc_due_transaction =  AccountTransaction::find( $p_return_trans->trans2_id);
                //             $sc_due_transaction->amount = $pre_invoice->due_amount;
                //             $sc_due_transaction->account_id = $acpHead->id;
                //             $sc_due_transaction->type = "debit";
                //             $sc_due_transaction->sub_type = "Sales Return";
                //             $sc_due_transaction->reason = "Sales Return From Customer With Due";
                //             $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //             $sc_due_transaction->relation_id = $invoice_return->id;
                //             $sc_due_transaction->relation_with = "Sales Return";
                //             $sc_due_transaction->trans_id = $p_return_trans->id;
                //             $sc_due_transaction->is_trans2 = 2;
                //             $sc_due_transaction->save();
                //             $p_return_trans->is_trans2 = 1;
                //             $p_return_trans->trans2_id = $sc_due_transaction->id;
                //             $p_return_trans->save();

                //             $acReceivaleHead = AccountHead::where("code",'2000')->first();
                //             $acr_trans = AccountTransaction::find( $p_return_trans->trans3_id);
                //             $acr_trans->amount = $due_advance;
                //             $acr_trans->account_id = $acReceivaleHead->id;
                //             $acr_trans->type = "credit";
                //             $acr_trans->sub_type = "Sales Return";
                //             $acr_trans->reason = "Sales Return From Customer With Due";
                //             $acr_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //             $acr_trans->relation_id = $invoice_return->id;
                //             $acr_trans->relation_with = "Sales Return";
                //             $acr_trans->trans_id = $p_return_trans->id;
                //             $acr_trans->is_trans2 = 3;
                //             $acr_trans->save();
                //             $p_return_trans->is_trans2 = 1;
                //             $p_return_trans->trans3_id = $acr_trans->id;
                //             $p_return_trans->save();
                //         }
                //     }

                // }
            DB::commit();
            $notification=array(
                'message'=>"Invoice Return Successfully Uploaded",
                'alert-type'=>'success'
            );
            return redirect()->route('invoice_return.index')->with($notification);
         }catch (\Exception $e){
            DB::rollBack();
              dd($e->getMessage());
            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    function invoiceReturnDetail($id){
        if(can_p('invoice_return.view') == false){
            return redirect()->route('dashboard');
        }
         $data['invoice_return'] = InvoiceReturn::find($id);
        return view ('Inventory.invoice_return.ajax-view-data', $data);
    }
    function paymentList($id){
        if(can_p('invoice_return.payment_show') == false){
            return redirect()->route('dashboard');
        }
         $data['invoice_return'] = InvoiceReturn::find($id);
         $data['payments'] = Payment::where('relation_id', $id)->where('relation_type','Sales Return Payment')->get();
        return view ('Inventory.invoice_return.ajax-view-data-payment', $data);
    }
    function storePayment(Request $request){
        if(can_p('invoice_return.add-payment') == false){
            return redirect()->route('dashboard');
        }
        $validator = Validator::make($request->all(),[
            'payment_method'=>'required',
            'account'=>'required',
            'payment_date'=>'required',
            'amount'=>'required|numeric|min:0|max:'.$request->due_amount,
            "invoice_return_id" => 'required'
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
            $invoice_return = InvoiceReturn::find($request->invoice_return_id);
            $invoice_return->paid_amount = $invoice_return->paid_amount+$request->amount;
            $invoice_return->due_amount = $invoice_return->grand_total - $invoice_return->paid_amount;
            if($invoice_return->due_amount == 0){
                $invoice_return->payment_status = 2;
            }else{
                $invoice_return->payment_status = 1;
            }

            $invoice_return->save();

            $payment = New Payment;
            $payment->payment_method= $request->payment_method ?? 0;
            $payment->bank_account_id= $request->account ?? 0;
            // $payment->transaction_id= $sc_pay_transaction->id;
            $payment->relation_id = $invoice_return->id;
            $payment->relation_type = "Sales Return Payment";
            $payment->amount = $request->amount;
            $payment->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $payment->note = $request->order_note;
            $payment->save();

            $salesDueHead = AccountHead::where("code",'2000')->first();

            $sc_due_transaction = New AccountTransaction;
            $sc_due_transaction->amount = $request->amount;
            $sc_due_transaction->account_id = $salesDueHead->id;
            $sc_due_transaction->type = "debit";
            $sc_due_transaction->sub_type = "Sales Return Payment";
            $sc_due_transaction->reason =  "Sales Return Payment For Invoice #".$invoice_return->reference_no;
            $sc_due_transaction->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $sc_due_transaction->relation_id = $invoice_return->id;
            $sc_due_transaction->relation_with = "Sales Return";
            $sc_due_transaction->payment_id = $payment->id;
            $sc_due_transaction->save();


            $balance_account = BalanceAccount::find($request->account);

            $sc_pay_transaction = New AccountTransaction;
            $sc_pay_transaction->amount = $request->amount;
            $sc_pay_transaction->account_id = $balance_account->account_head_id;
            $sc_pay_transaction->type = "credit";
            $sc_pay_transaction->relation_with = "Sales Return";
            $sc_pay_transaction->sub_type = "Sales Return Payment";
            $sc_pay_transaction->reason = "Sales Return Payment For Invoice #".$invoice_return->reference_no;
            $sc_pay_transaction->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $sc_pay_transaction->relation_id = $invoice_return->id;
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
    public function destroy(string $id)
    {
        if(can_p('invoice_return.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $invoice = InvoiceReturn::find($id);
            // dd($purchase);
            foreach ($invoice->items as $item) {
                $product_stock =  Product::find($item->product->id);
                if($product_stock){
                    $product_stock->qty=  $product_stock->qty - $item->qty;
                    $product_stock->save();
                }
                $item->delete();
            }
            $payments =  Payment::where('relation_id', $id)->where('relation_type','Sales Return Payment')->get();
            foreach ($payments as $item) {
                $item->delete();
            }
            $account_payments= AccountTransaction::where('relation_id', $id)->where('relation_with','Sales Return')->get();
            foreach ($account_payments as $item) {
                $item->delete();
            }
            $stocks = Stock::where('invoice_return_id',$invoice->id)->get();

            foreach ($stocks as $item) {
                $item->delete();
            }
             $invoice->delete();
            DB::commit();
            $notification=array(
                'message'=>"Invoice Return Successfully Delete",
                'alert-type'=>'success'
            );
            return redirect()->route('invoice_return.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
             $notification=array(
                'message'=>"Can not delete this!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }

    }
}
