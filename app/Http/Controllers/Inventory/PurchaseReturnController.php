<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use App\Models\Inventory\Payment;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductPurchase;
use App\Models\Inventory\ProductPurchaseReturn;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\PurchaseReturn;
use App\Models\Inventory\Stock;
use App\Models\ProductStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseReturnController extends Controller
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
        $purchasReturnHead = AccountHead::where("code",'4001')->first();
        if($purchasReturnHead == null){
            $purchasReturnHead = new AccountHead;
            $purchasReturnHead->title = "Purchase Return";
            $purchasReturnHead->code = '4001';
            $purchasReturnHead->sys = 0;
            $purchasReturnHead->ac_type = 4;
            $purchasReturnHead->note = '';
            $purchasReturnHead->status = 1;
            $purchasReturnHead->save();
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
        if(can_p('purchase_return.index') == false){
            return redirect()->route('dashboard');
        }
        $purchase_returns = PurchaseReturn::query();
        if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
            $purchase_returns->where('branch_id',auth()->user()->branch_id);
        }
        $data['purchase_returns'] = $purchase_returns->orderBy('id','DESC')->get();
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view ('Inventory.purchase_return.manage', $data );
    }
    function ajaxPurchaseReturn(Request $request){
        $columns = array(
           0 => 'purchase_returns.id',
           1 => 'purchase_returns.return_date',
           2 => 'purchase_returns.reference_no',
           3 => 'vendors.name',
           4 => 'purchase_returns.grand_total',
           5 => 'purchase_returns.paid_amount',
           7 => 'purchase_returns.status',
           8 => 'purchase_returns.payment_status',
           9 => 'purchase_returns.payment_method',
           10 => 'purchase_returns.bank_account_id',
           11 => 'options',
        );
        $totalData = PurchaseReturn::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $purchases = PurchaseReturn::leftjoin('vendors','vendors.id','purchase_returns.supplier_id');
        if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
            $purchases->where('purchase_returns.branch_id',auth()->user()->branch_id);
        }
        if(!empty($search))
        {
            $purchases = $purchases->where("purchase_returns.return_date","LIKE","%{$search}%");

        }
        $totalFiltered = $purchases->count();
        $purchases = $purchases->select('purchase_returns.*','vendors.name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($purchases))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('invoice.edit');
            $p_delete = can_p('invoice.delete');
            $p_view = can_p('invoice.view');
            $p_add_payment = can_p('invoice.add-payment');
            $p_payment_show = can_p('invoice.payment_show');
            $p_sales_return = can_p('invoice_return.add');
            foreach($purchases as $purchase)
            {
                $nestedData['id'] = $i++;

                $nestedData['date'] = date('Y-m-d', strtotime($purchase->return_date));
                $nestedData['reference'] = $purchase->reference_no;
                $nestedData['cus_name'] = $purchase->name;
                $nestedData['total'] = auth()->user()->currency_symbol . number_format($purchase->grand_total, 2);
                $nestedData['paid'] = auth()->user()->currency_symbol . number_format($purchase->paid_amount, 2);
                $nestedData['due'] = auth()->user()->currency_symbol . number_format($purchase->grand_total - $purchase->paid_amount, 2);
                $nestedData['method'] =$purchase?->method?->name;
                $nestedData['account'] =$purchase?->account?->account_name;
                $payment_status = '';
                if($purchase->payment_status == 0){
                    $payment_status = '<div class="badge bg-danger">Due</div>';
                }else if($purchase->payment_status == 1){
                    $payment_status = '<div class="badge bg-warning">Partial</div>';
                }else{
                    $payment_status = '<div class="badge bg-success">Paid</div>';
                }
                $nestedData['payment_status'] =$payment_status;
                $nestedData['options'] = '<div class="btn-group"><button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';
                if($p_view){
                    $nestedData['options'] .= ' <li><button data-id="'.$purchase->id.'" type="button" class="btn btn-link view"><i class="bx bx-show"></i>View</button> </li>';
                }
                if($p_edit){
                    if($purchase->purchase_id == 0){
                        $nestedData['options'] .= ' <li><a href="'. route('purchase_return.edit', $purchase->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';
                    }else{

                        $nestedData['options'] .= ' <li><a href="'. route('purchase_return.add_edit', $purchase->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';

                    }
                }

                if($p_add_payment){
                    if($purchase->due_amount > 0){
                        $nestedData['options'] .= ' <li><button data-due="'. $purchase->due_amount .'" type="button" class="add-payment btn btn-link" data-id = "'. $purchase->id .'" data-bs-toggle="modal" data-bs-target="#add-payment"><i class="bx bx-plus"></i>Add Payment</button></li>';
                    }
                }
                if($p_payment_show){
                    if($purchase->payment_status != 0){
                        $nestedData['options'] .= ' <li><button type="button" class="payment_show btn btn-link" data-id = "'. $purchase->id .'"><i class="bx bx-money"></i> View Payment</button></li>';
                    }
                }
                if($p_delete){
                    $nestedData['options'] .= ' <li> <form action="'. route('purchase_return.delete',$purchase->id).'" method="post"> <input type="hidden" name="_token" value="'. csrf_token().'"><button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="bx bx-trash"></i>Delete</button></form></li>';
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
        if(can_p('purchase_return.add') == false){
            return redirect()->route('dashboard');
        }
        $data['purchase'] =$purchase= Purchase::find($id);
       // dd($purchase->items);
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view ('Inventory.purchase_return.add', $data);
    }
    function addReturnPost(Request $request,$id){
        if(can_p('purchase_return.add') == false){
            return redirect()->route('dashboard');
        }

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
            $pre_purchase = Purchase::find($id);
            $purchase_return = New PurchaseReturn;
            $purchase_return->reference_no = 're-pr-' . date("Ymd") . '-'. date("his");
            $purchase_return->reason = $request->return_reason;
            $purchase_return->branch_id = $pre_purchase->branch_id;
            $purchase_return->purchase_id = $pre_purchase->id;
            $purchase_return->supplier_id=$pre_purchase->supplier_id;
            $purchase_return->total_qty = $request->total_qty;
            $purchase_return->total_discount =  $request->sub_discount;
            $purchase_return->total_cost = $request->total_price;
            // $purchase->order_discount = $request->order_discount;
            // $purchase->shipping_cost = $request->shipping_cost;

            $purchase_return->total_tax = $request->total_tax;
            $purchase_return->paid_amount = $request->paid_amount;
            $purchase_return->due_amount = $request->grand_total - $request->paid_amount;
            $purchase_return->grand_total = $request->grand_total;
            $purchase_return->status = "Complete";
            if($request->payment_method != ''){
                if($purchase_return->due_amount == 0){
                    $purchase_return->payment_status = 2;
                }else{
                    $purchase_return->payment_status = 1;
                }
            }else{
                $purchase_return->payment_status = 0;
            }
            $file=$request->file('document');
            if($file){
                $filename=date('YmdHi')."_purchase_document".$file->getClientOriginalName();
                $file->move(public_path('upload/purchase_return'),$filename);
                $purchase_return->document=$filename;
            }
            $purchase_return->note = $request->order_note;
            if ($request->return_date == null) {
                $purchase_return->return_date = now();
            } else {
                $invdate = Carbon::parse($request->return_date)->format('Y-m-d');
                $purchase_return->return_date = $invdate;
            }
            //dd($purchase_return);
            $purchase_return->payment_method= $request->payment_method ?? 0;
            $purchase_return->bank_account_id= $request->account ?? 0;
            $purchase_return->save();
            foreach($request->return_qty as $k=>$return_qty) {
                if(0 != $return_qty){

                    $product_purchase_return = New ProductPurchaseReturn;
                    $product_purchase_return->purchase_return_id = $purchase_return->id;
                    $product_purchase_return->product_id = $request->return_product[$k] ?? 0;
                    // $product_purchase_return->color_id = $request->return_color[$k] ?? 0;
                    // $product_purchase_return->size_id = $request->return_size[$k] ?? 0;
                    $product_purchase_return->unit_id = $request->return_unit[$k];
                    $product_purchase_return->qty = $return_qty;
                    $product_purchase_return->tax = $request->return_tax[$k] ?? 0;
                    $product_purchase_return->tax_rate = $request->return_tax_rate[$k] ?? 0;
                    $product_purchase_return->per_cost = $request->return_per_cost[$k] ?? 0;
                    $product_purchase_return->total = $request->return_sub_total[$k] ?? 0;
                    $product_purchase_return->discount = $request->return_discount[$k] ?? 0;
                     $product_purchase_return->discount_rate = $request->return_discount_rate[$k] ?? 0;
                    $product_purchase_return->save();
                    //dd($product_purchase_return);
                    $stock = new Stock;
                    $stock->purchase_return_id =$purchase_return->id;
                    $stock->product_purchase_return_id = $product_purchase_return->id;
                    $stock->product_id = $request->return_product[$k] ?? 0;
                    // $stock->color_id = $request->return_color[$k] ?? 0;
                    // $stock->size_id = $request->return_size[$k] ?? 0;
                    $stock->unit_id = $request->return_unit[$k] ?? 0;
                    $stock->in_qty = -$return_qty;
                    $stock->purchase_price = -$request->return_sub_total[$k] ?? 0;
                    $stock->inventory_type = 'Purchase Return';
                    $stock->save();
                    $product_stock =  Product::find( $request->return_product[$k] ?? 0);
                    if($product_stock){
                        $product_stock->qty=  $product_stock->qty - $return_qty;
                        $product_stock->save();
                    }
                }
            }
            if(array_search('accounts',$this->pack_option()) != false){
                $purchasReturnHead = AccountHead::where("code",'4001')->first();

                $p_return_trans = New AccountTransaction;
                $p_return_trans->amount = $request->grand_total;
                $p_return_trans->account_id = $purchasReturnHead->id;
                $p_return_trans->type = "credit";
                $p_return_trans->sub_type = "Purchase Return";
                $p_return_trans->reason = "Return Product To Supplier ";
                $p_return_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                $p_return_trans->relation_id = $purchase_return->id;
                $p_return_trans->relation_with = "Purchase Return";
                $p_return_trans->save();

                if($request->payment_method == ''){
                    if($pre_purchase->due_amount >= $purchase_return->due_amount){
                        $acpHead = AccountHead::where("code",'2000')->first();

                        $sc_due_transaction = New AccountTransaction;
                        $sc_due_transaction->amount = $purchase_return->due_amount;
                        $sc_due_transaction->account_id = $acpHead->id;
                        $sc_due_transaction->type = "debit";
                        $sc_due_transaction->sub_type = "Purchase Return";
                        $sc_due_transaction->reason = "Purchase Return To Supplier With Due";
                        $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                        $sc_due_transaction->relation_id = $purchase_return->id;
                        $sc_due_transaction->relation_with = "Purchase Return";
                        $sc_due_transaction->trans_id = $p_return_trans->id;
                        $sc_due_transaction->save();
                        $p_return_trans->trans_id = $sc_due_transaction->id;
                        $p_return_trans->save();
                    }else{
                        $due_advance = $purchase_return->due_amount-$pre_purchase->due_amount;

                        $acpHead = AccountHead::where("code",'2000')->first();

                        $sc_due_transaction = New AccountTransaction;
                        $sc_due_transaction->amount = $pre_purchase->due_amount;
                        $sc_due_transaction->account_id = $acpHead->id;
                        $sc_due_transaction->type = "debit";
                        $sc_due_transaction->sub_type = "Purchase Return";
                        $sc_due_transaction->reason = "Purchase Return To Supplier With Due";
                        $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                        $sc_due_transaction->relation_id = $purchase_return->id;
                        $sc_due_transaction->relation_with = "Purchase Return";
                        $sc_due_transaction->trans_id = $p_return_trans->id;
                        $sc_due_transaction->save();
                        $p_return_trans->trans_id = $sc_due_transaction->id;
                        $p_return_trans->save();

                        $acReceivaleHead = AccountHead::where("code",'1000')->first();
                        $acr_trans = New AccountTransaction;
                        $acr_trans->amount = $due_advance;
                        $acr_trans->account_id = $acReceivaleHead->id;
                        $acr_trans->type = "debit";
                        $acr_trans->sub_type = "Purchase Return";
                        $acr_trans->reason = "Purchase Return To Supplier With Due";
                        $acr_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                        $acr_trans->relation_id = $purchase_return->id;
                        $acr_trans->relation_with = "Purchase Return";
                        $acr_trans->is_trans2 = 2;
                        $acr_trans->trans_id = $p_return_trans->id;
                        $acr_trans->save();
                        $p_return_trans->is_trans2 = 1;
                        $p_return_trans->trans_id = $acr_trans->id;
                        $p_return_trans->save();
                    }


                }else{
                    $balance_account = BalanceAccount::find($request->account);
                    $payment = New Payment;
                    $payment->payment_method= $request->payment_method ?? 0;
                    $payment->bank_account_id= $request->account ?? 0;
                    // $payment->transaction_id= $sc_pay_transaction->id;
                    $payment->relation_id = $purchase_return->id;
                    $payment->relation_type = "Purchase Return Payment";
                    $payment->amount = $request->paid_amount;
                    $payment->date = date('Y-m-d');
                    $payment->note = $request->order_note;
                    $payment->save();

                    $pay_trans = New AccountTransaction;
                    $pay_trans->amount = $request->paid_amount;
                    $pay_trans->account_id = $balance_account->account_head_id;
                    $pay_trans->type = "debit";
                    $pay_trans->sub_type = "Purchase Return Payment";
                    $pay_trans->reason = "Purchase Return Payment for invoice #". $purchase_return->reference_no;
                    $pay_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                    $pay_trans->relation_id = $purchase_return->id;
                    $pay_trans->payment_id = $payment->id;
                    $pay_trans->relation_with = "Purchase Return";
                    $pay_trans->trans_id = $p_return_trans->id;
                    $pay_trans->save();
                    $payment->transaction_id= $pay_trans->id;
                    $payment->save();
                    $p_return_trans->trans_id = $pay_trans->id;
                    $p_return_trans->save();


                    if($purchase_return->due_amount > 0){
                        if($pre_purchase->due_amount >= $purchase_return->due_amount){
                            $acpHead = AccountHead::where("code",'2000')->first();

                            $sc_due_transaction = New AccountTransaction;
                            $sc_due_transaction->amount = $purchase_return->due_amount;
                            $sc_due_transaction->account_id = $acpHead->id;
                            $sc_due_transaction->type = "debit";
                            $sc_due_transaction->sub_type = "Purchase Return";
                            $sc_due_transaction->reason = "Purchase Return To Supplier With Due";
                            $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                            $sc_due_transaction->relation_id = $purchase_return->id;
                            $sc_due_transaction->relation_with = "Purchase Return";
                            $sc_due_transaction->trans_id = $p_return_trans->id;
                            $sc_due_transaction->save();
                            $p_return_trans->trans_id = $sc_due_transaction->id;
                            $p_return_trans->save();
                        }else{
                            $due_advance = $purchase_return->due_amount-$pre_purchase->due_amount;

                            $acpHead = AccountHead::where("code",'2000')->first();

                            $sc_due_transaction = New AccountTransaction;
                            $sc_due_transaction->amount = $pre_purchase->due_amount;
                            $sc_due_transaction->account_id = $acpHead->id;
                            $sc_due_transaction->type = "debit";
                            $sc_due_transaction->sub_type = "Purchase Return";
                            $sc_due_transaction->reason = "Purchase Return To Supplier With Due";
                            $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                            $sc_due_transaction->relation_id = $purchase_return->id;
                            $sc_due_transaction->relation_with = "Purchase Return";
                            $sc_due_transaction->trans_id = $p_return_trans->id;
                            $sc_due_transaction->save();
                            $p_return_trans->trans_id = $sc_due_transaction->id;
                            $p_return_trans->save();

                            $acReceivaleHead = AccountHead::where("code",'1000')->first();
                            $acr_trans = New AccountTransaction;
                            $acr_trans->amount = $due_advance;
                            $acr_trans->account_id = $acReceivaleHead->id;
                            $acr_trans->type = "credit";
                            $acr_trans->sub_type = "Purchase Return";
                            $acr_trans->reason = "Purchase Return To Supplier With Due";
                            $acr_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                            $acr_trans->relation_id = $purchase_return->id;
                            $acr_trans->relation_with = "Purchase Return";
                            $acr_trans->is_trans2 = 2;
                            $acr_trans->trans_id = $p_return_trans->id;
                            $acr_trans->save();
                            $p_return_trans->is_trans2 = 1;
                            $p_return_trans->trans_id = $acr_trans->id;
                            $p_return_trans->save();
                        }
                    }

                }
            }
            DB::commit();
            $notification=array(
                'message'=>"Purchase Return Successfully Completed",
                'alert-type'=>'success'
            );
            return redirect()->route('purchase_return.print',$purchase_return->id)->with($notification);
            return redirect()->route('purchase_return.index')->with($notification);
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
    function printPurchaseReturn($id){
        $data['purchase_return']=PurchaseReturn::find($id);
        return view('Inventory.purchase_return.print_purchase_return',$data);
    }
    function addReturnEdit(Request $request,$id){
        if(can_p('purchase_return.add_edit') == false){
            return redirect()->route('dashboard');
        }
        $data['purchase_return'] = PurchaseReturn::find($id);
         $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view ('Inventory.purchase_return.add_edit', $data);
    }
    function addReturnUpdate(Request $request,$id){
        if(can_p('purchase_return.add_edit') == false){
            return redirect()->route('dashboard');
        }
       // dd($request->all());
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
        if($request->paid_amount > 0){
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

            $purchase_return = PurchaseReturn::find($id);
            $pre_purchase = Purchase::find($purchase_return->purchase_id);
            $purchase_return->reference_no = 're-pr-' . date("Ymd") . '-'. date("his");
            $purchase_return->reason = $request->return_reason;
            $purchase_return->total_qty = $request->total_qty;
            $purchase_return->total_discount =  $request->sub_discount;
            $purchase_return->total_cost = $request->total_price;
            // $purchase->order_discount = $request->order_discount;
            // $purchase->shipping_cost = $request->shipping_cost;

            $purchase_return->total_tax = $request->total_tax;
            $purchase_return->paid_amount = $request->paid_amount;
            $purchase_return->due_amount = $request->grand_total - $request->paid_amount;
            $purchase_return->grand_total = $request->grand_total;
            $purchase_return->status = "Complete";
           // dd($request->all());
            if($request->payment_method != ''){
                if($purchase_return->due_amount == 0){
                    $purchase_return->payment_status = 2;
                }else{
                    $purchase_return->payment_status = 1;
                }
            }else{
                $purchase_return->payment_status = 0;
            }
            $file=$request->file('document');
            if($file){
                $filename=date('YmdHi')."_purchase_document".$file->getClientOriginalName();
                $file->move(public_path('upload/purchase_return'),$filename);
                $purchase_return->document=$filename;
            }
            $purchase_return->note = $request->order_note;
            if ($request->return_date == null) {
                $purchase_return->return_date = now();
            } else {
                $invdate = Carbon::parse($request->return_date)->format('Y-m-d');
                $purchase_return->return_date = $invdate;
            }
            //dd($purchase_return);
            $purchase_return->payment_method= $request->payment_method ?? 0;
            $purchase_return->bank_account_id= $request->account ?? 0;
            $purchase_return->save();
            foreach($request->return_qty as $k=>$return_qty) {
                $product_purchase_return = ProductPurchaseReturn::where('product_id',$request->return_product[$k] ?? 0)->where('color_id',$request->return_color[$k] ?? 0)->where('size_id',$request->return_size[$k] ?? 0)->first();
                if($product_purchase_return == null){
                    $old_qty = 0;
                }else{
                    $old_qty = $product_purchase_return->qty;
                }
                if(0 != $return_qty){

                    if($product_purchase_return == null){
                        $product_purchase_return = New ProductPurchaseReturn;
                    }
                    $product_purchase_return->purchase_return_id = $purchase_return->id;
                    $product_purchase_return->product_id = $request->return_product[$k] ?? 0;
                    $product_purchase_return->color_id = $request->return_color[$k] ?? 0;
                    $product_purchase_return->size_id = $request->return_size[$k] ?? 0;
                    $product_purchase_return->unit_id = $request->return_unit[$k];
                    $product_purchase_return->qty = $return_qty;
                    $product_purchase_return->tax = $request->return_tax[$k] ?? 0;
                    $product_purchase_return->tax_rate = $request->return_tax_rate[$k] ?? 0;
                    $product_purchase_return->per_cost = $request->return_per_cost[$k] ?? 0;
                    $product_purchase_return->total = $request->return_sub_total[$k] ?? 0;
                    $product_purchase_return->discount = $request->return_discount[$k] ?? 0;
                     $product_purchase_return->discount_rate = $request->return_discount_rate[$k] ?? 0;
                    $product_purchase_return->save();
                    //dd($product_purchase_return);
                    $stock = Stock::where('product_purchase_return_id',$product_purchase_return->id)->first();
                    if($stock == null){
                        $stock = new Stock;
                    }

                    $stock->purchase_return_id =$purchase_return->id;
                    $stock->product_purchase_return_id = $product_purchase_return->id;
                    $stock->product_id = $request->return_product[$k] ?? 0;
                    $stock->color_id = $request->return_color[$k] ?? 0;
                    $stock->size_id = $request->return_size[$k] ?? 0;
                    $stock->unit_id = $request->return_unit[$k] ?? 0;
                    $stock->out_qty = $return_qty;
                    $stock->purchase_price = -$request->return_sub_total[$k] ?? 0;
                    $stock->inventory_type = 'Purchase Return';
                    $stock->save();
                }else{

                    if($product_purchase_return){
                        $stock = Stock::where('product_purchase_return_id',$product_purchase_return->id)->first();
                        $stock->delete();
                        $product_purchase_return->delete();
                    }
                }
                $product_stock =  Product::find( $request->return_product[$k] ?? 0);
                if($product_stock){
                    $product_stock->qty=  $product_stock->qty + $old_qty - $return_qty;
                    $product_stock->save();
                }


            }
            if(array_search('accounts',$this->pack_option()) != false){
                $purchasReturnHead = AccountHead::where("code",'4001')->first();

                $p_return_trans = AccountTransaction::where('relation_id',$purchase_return->id)->where('relation_with','Purchase Return')->where('account_id',$purchasReturnHead->id)->first();
                if($p_return_trans == null){
                    $p_return_trans = New AccountTransaction;
                }
                $p_return_trans->amount = $request->grand_total;
                $p_return_trans->account_id = $purchasReturnHead->id;
                $p_return_trans->type = "credit";
                $p_return_trans->sub_type = "Purchase Return";
                $p_return_trans->reason = "Return Product To Supplier ";
                $p_return_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                $p_return_trans->relation_id = $purchase_return->id;
                $p_return_trans->relation_with = "Purchase Return";
                $p_return_trans->save();
            }

                // if($request->payment_method == ''){
                //     if($pre_purchase->due_amount >= $purchase_return->due_amount){
                //         $acpHead = AccountHead::where("code",'2000')->first();

                //         $sc_due_transaction =  AccountTransaction::find( $p_return_trans->trans_id);
                //         $sc_due_transaction->amount = $purchase_return->due_amount;
                //         $sc_due_transaction->account_id = $acpHead->id;
                //         $sc_due_transaction->type = "debit";
                //         $sc_due_transaction->sub_type = "Purchase Return";
                //         $sc_due_transaction->reason = "Purchase Return To Supplier With Due";
                //         $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //         $sc_due_transaction->relation_id = $purchase_return->id;
                //         $sc_due_transaction->relation_with = "Purchase Return";
                //         $sc_due_transaction->trans_id = $p_return_trans->id;
                //         $sc_due_transaction->save();
                //         $p_return_trans->trans_id = $sc_due_transaction->id;
                //         $p_return_trans->save();
                //     }else{
                //         $due_advance = $purchase_return->due_amount-$pre_purchase->due_amount;

                //         $acpHead = AccountHead::where("code",'2000')->first();

                //         $sc_due_transaction =  AccountTransaction::find( $p_return_trans->trans_id);
                //         $sc_due_transaction->amount = $pre_purchase->due_amount;
                //         $sc_due_transaction->account_id = $acpHead->id;
                //         $sc_due_transaction->type = "debit";
                //         $sc_due_transaction->sub_type = "Purchase Return";
                //         $sc_due_transaction->reason = "Purchase Return To Supplier With Due";
                //         $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //         $sc_due_transaction->relation_id = $purchase_return->id;
                //         $sc_due_transaction->relation_with = "Purchase Return";
                //         $sc_due_transaction->trans_id = $p_return_trans->id;
                //         $sc_due_transaction->save();
                //         $p_return_trans->trans_id = $sc_due_transaction->id;
                //         $p_return_trans->save();

                //         $acReceivaleHead = AccountHead::where("code",'1000')->first();
                //         $acr_trans = AccountTransaction::find( $p_return_trans->trans2_id);
                //         $acr_trans->amount = $due_advance;
                //         $acr_trans->account_id = $acReceivaleHead->id;
                //         $acr_trans->type = "credit";
                //         $acr_trans->sub_type = "Purchase Return";
                //         $acr_trans->reason = "Purchase Return To Supplier With Due";
                //         $acr_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //         $acr_trans->relation_id = $purchase_return->id;
                //         $acr_trans->relation_with = "Purchase Return";
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
                //     $payment->relation_id = $purchase_return->id;
                //     $payment->relation_type = "Purchase Return Payment";
                //     $payment->amount = $request->paid_amount;
                //     $payment->date = date('Y-m-d');
                //     $payment->note = $request->order_note;
                //     $payment->save();


                //     $pay_trans->amount = $request->paid_amount;
                //     $pay_trans->account_id = $balance_account->account_head_id;
                //     $pay_trans->type = "credit";
                //     $pay_trans->sub_type = "Purchase Return Payment";
                //     $pay_trans->reason = "Purchase Return Payment for invoice #". $purchase_return->reference_no;
                //     $pay_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //     $pay_trans->relation_id = $purchase_return->id;
                //     $pay_trans->payment_id = $payment->id;
                //     $pay_trans->relation_with = "Purchase Return";
                //     $pay_trans->trans_id = $p_return_trans->id;
                //     $pay_trans->save();
                //     $payment->transaction_id= $pay_trans->id;
                //     $payment->save();
                //     $p_return_trans->trans_id = $pay_trans->id;
                //     $p_return_trans->save();


                //     if($purchase_return->due_amount > 0){
                //         if($pre_purchase->due_amount >= $purchase_return->due_amount){
                //             $acpHead = AccountHead::where("code",'2000')->first();

                //             $sc_due_transaction = AccountTransaction::find( $p_return_trans->trans2_id);
                //             $sc_due_transaction->amount = $purchase_return->due_amount;
                //             $sc_due_transaction->account_id = $acpHead->id;
                //             $sc_due_transaction->type = "debit";
                //             $sc_due_transaction->sub_type = "Purchase Return";
                //             $sc_due_transaction->reason = "Purchase Return To Supplier With Due";
                //             $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //             $sc_due_transaction->relation_id = $purchase_return->id;
                //             $sc_due_transaction->relation_with = "Purchase Return";
                //             $sc_due_transaction->trans_id = $p_return_trans->id;
                //             $sc_due_transaction->is_trans2 = 2;
                //             $sc_due_transaction->save();
                //             $p_return_trans->is_trans2 = 1;
                //             $p_return_trans->trans2_id = $sc_due_transaction->id;
                //             $p_return_trans->save();
                //         }else{
                //             $due_advance = $purchase_return->due_amount-$pre_purchase->due_amount;

                //             $acpHead = AccountHead::where("code",'2000')->first();

                //             $sc_due_transaction =  AccountTransaction::find( $p_return_trans->trans2_id);
                //             $sc_due_transaction->amount = $pre_purchase->due_amount;
                //             $sc_due_transaction->account_id = $acpHead->id;
                //             $sc_due_transaction->type = "debit";
                //             $sc_due_transaction->sub_type = "Purchase Return";
                //             $sc_due_transaction->reason = "Purchase Return To Supplier With Due";
                //             $sc_due_transaction->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //             $sc_due_transaction->relation_id = $purchase_return->id;
                //             $sc_due_transaction->relation_with = "Purchase Return";
                //             $sc_due_transaction->trans_id = $p_return_trans->id;
                //             $sc_due_transaction->is_trans2 = 2;
                //             $sc_due_transaction->save();
                //             $p_return_trans->is_trans2 = 1;
                //             $p_return_trans->trans2_id = $sc_due_transaction->id;
                //             $p_return_trans->save();

                //             $acReceivaleHead = AccountHead::where("code",'1000')->first();
                //             $acr_trans = AccountTransaction::find( $p_return_trans->trans3_id);
                //             $acr_trans->amount = $due_advance;
                //             $acr_trans->account_id = $acReceivaleHead->id;
                //             $acr_trans->type = "credit";
                //             $acr_trans->sub_type = "Purchase Return";
                //             $acr_trans->reason = "Purchase Return To Supplier With Due";
                //             $acr_trans->date = $request->return_date == null ?  date('Y-m-d') :  Carbon::parse($request->return_date)->format('Y-m-d');
                //             $acr_trans->relation_id = $purchase_return->id;
                //             $acr_trans->relation_with = "Purchase Return";
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
                'message'=>"Purchase Return Successfully Completed",
                'alert-type'=>'success'
            );
            return redirect()->route('purchase_return.index')->with($notification);
         }catch (\Exception $e){
            DB::rollBack();

            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    function purchaseDetail($id){
        if(can_p('purchase_return.view') == false){
            return redirect()->route('dashboard');
        }
         $data['purchase_return'] = PurchaseReturn::find($id);
        return view ('Inventory.purchase_return.ajax-view-data', $data);
    }
    function paymentList($id){
        if(can_p('purchase_return.payment_show') == false){
            return redirect()->route('dashboard');
        }
         $data['purchase_return'] = PurchaseReturn::find($id);
         $data['payments'] = Payment::where('relation_id', $id)->where('relation_type','Purchase Return Payment')->get();
        return view ('Inventory.purchase_return.ajax-view-data-payment', $data);
    }
    function storePayment(Request $request){
        if(can_p('purchase_return.add-payment') == false){
            return redirect()->route('dashboard');
        }
        $validator = Validator::make($request->all(),[
            'payment_method'=>'required',
            'account'=>'required',
            'payment_date'=>'required',
            'amount'=>'required|numeric|min:0|max:'.$request->due_amount,
            "purchase_return_id" => 'required'
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
            $purchase_return = PurchaseReturn::find($request->purchase_return_id);
            $purchase_return->paid_amount = $purchase_return->paid_amount+$request->amount;
            $purchase_return->due_amount = $purchase_return->grand_total - $purchase_return->paid_amount;
            if($purchase_return->due_amount == 0){
                $purchase_return->payment_status = 2;
            }else{
                $purchase_return->payment_status = 1;
            }
            $purchase_return->save();

            $payment = New Payment;
            $payment->payment_method= $request->payment_method ?? 0;
            $payment->bank_account_id= $request->account ?? 0;
            // $payment->transaction_id= $sc_pay_transaction->id;
            $payment->relation_id = $purchase_return->id;
            $payment->relation_type = "Purchase Return Payment";
            $payment->amount = $request->amount;
            $payment->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $payment->note = $request->order_note;
            $payment->save();

            $salesDueHead = AccountHead::where("code",'2000')->first();

            $sc_due_transaction = New AccountTransaction;
            $sc_due_transaction->amount = $request->amount;
            $sc_due_transaction->account_id = $salesDueHead->id;
            $sc_due_transaction->type = "debit";
            $sc_due_transaction->sub_type = "Purchase Return Payment";
            $sc_due_transaction->reason =  "Purchase Return Payment For Invoice #".$purchase_return->reference_no;
            $sc_due_transaction->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $sc_due_transaction->relation_id = $purchase_return->id;
            $sc_due_transaction->relation_with = "Purchase Return";
            $sc_due_transaction->payment_id = $payment->id;
            $sc_due_transaction->save();


            $balance_account = BalanceAccount::find($request->account);

            $sc_pay_transaction = New AccountTransaction;
            $sc_pay_transaction->amount = $request->amount;
            $sc_pay_transaction->account_id = $balance_account->account_head_id;
            $sc_pay_transaction->type = "credit";
            $sc_pay_transaction->relation_with = "Purchase Return";
            $sc_pay_transaction->sub_type = "Purchase Return Payment";
            $sc_pay_transaction->reason = "Purchase Return Payment For Invoice #".$purchase_return->reference_no;
            $sc_pay_transaction->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $sc_pay_transaction->relation_id = $purchase_return->id;
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

        if(can_p('purchase_return.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $purchase = PurchaseReturn::find($id);
            // dd($purchase);
            foreach ($purchase->items as $item) {
                $product_stock =  Product::find($item->product_id);
                if($product_stock){
                    $product_stock->qty=  $product_stock->qty + $item->qty;
                    $product_stock->save();
                }
                $item->delete();
            }
            $payments =  Payment::where('relation_id', $id)->where('relation_type','Purchase Return Payment')->get();
            foreach ($payments as $item) {
                $item->delete();
            }
            $account_payments= AccountTransaction::where('relation_id', $id)->where('relation_with','Purchase Return')->get();
            foreach ($account_payments as $item) {
                $item->delete();
            }
            $stocks = Stock::where('purchase_return_id',$purchase->id)->get();
            foreach ($stocks as $item) {
                $item->delete();
            }
            $purchase->delete();
            DB::commit();
            $notification=array(
                'message'=>"Purchase Successfully Delete",
                'alert-type'=>'success'
            );
            return redirect()->route('purchase_return.index')->with($notification);
        }catch (\Exception $e){
            DB::rollBack();
            //dd($e->getMessage());
            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}
