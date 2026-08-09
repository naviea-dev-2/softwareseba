<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use App\Models\Hr\Bank;
use App\Models\Inventory\Payment;
use App\Models\Inventory\Product;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\Vendor;
use App\Models\Inventory\ProductPurchase;
use App\Models\Inventory\Stock;
use App\Models\ProductStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
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
        $salesHead = AccountHead::where("code",'5000')->first();
       // dd($salesHead);
        if($salesHead == null){
            $salesHead = new AccountHead;
            $salesHead->title = "Purchase";
            $salesHead->code = '5000';
            $salesHead->sys = 0;
            $salesHead->ac_type = 5;
            $salesHead->note = '';
            $salesHead->status = 1;
            $salesHead->save();

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
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        if(can_p('purchase.index') == false){
            return redirect()->route('dashboard');
        }
        // $data['vendors']=Vendor::orderBy('id','DESC')->get();
        $purchases = Purchase::query();

        if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
            $purchases->where('branch_id',auth()->user()->branch_id);
        }
        $data['purchases'] = $purchases->orderBy('id','DESC')->get();
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view ('Inventory.purchase.manage', $data );
    }
    function ajaxPurchase(Request $request){
        $columns = array(
           0 => 'purchases.id',
           1 => 'purchases.purchase_date',
           2 => 'purchases.reference_no',
           3 => 'vendors.name',
           4 => 'purchases.grand_total',
           5 => 'purchases.paid_amount',
           7 => 'purchases.status',
           8 => 'purchases.payment_status',
           9 => 'invoices.payment_method',
            10 => 'invoices.bank_account_id',
            11 => 'options',
        );
        $totalData = Purchase::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $purchases = Purchase::leftjoin('vendors','vendors.id','purchases.supplier_id');
        if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
            $purchases->where('purchases.branch_id',auth()->user()->branch_id);
        }
        if(!empty($search))
        {
            $purchases = $purchases->where("purchases.purchase_date","LIKE","%{$search}%")
                                    ->orWhere("purchases.reference_no","LIKE","%{$search}%")
                                    ->orWhere("vendors.name","LIKE","%{$search}%");

        }
        $totalFiltered = $purchases->count();
        $purchases = $purchases->select('purchases.*','vendors.name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($purchases))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('purchase.edit');
            $p_delete = can_p('purchase.delete');
            $p_view = can_p('purchase.view');
            $p_add_payment = can_p('purchase.add-payment');
            $p_payment_show = can_p('purchase.payment_show');
            $p_purchase_return = can_p('purchase_return.add');
            foreach($purchases as $purchase)
            {
                $nestedData['id'] = $i++;

                $nestedData['date'] = date('Y-m-d', strtotime($purchase->purchase_date));
                $nestedData['reference'] = $purchase->reference_no;
                $nestedData['cus_name'] = $purchase->name;
                $nestedData['total'] = auth()->user()->currency_symbol . number_format($purchase->grand_total, 2);
                $nestedData['paid'] = auth()->user()->currency_symbol . number_format($purchase->paid_amount, 2);
                $nestedData['due'] = auth()->user()->currency_symbol . number_format($purchase->grand_total - $purchase->paid_amount, 2);
                $status = '';
                $nestedData['method'] =$purchase?->method?->name;
                $nestedData['account'] =$purchase?->account?->account_name;
                if($purchase->status == 1){
                    $status = '<div class="badge bg-success">Received</div>';
                }else if($purchase->status == 2){
                    $status = '<div class="badge bg-secondary">Partial</div>';
                }else if($purchase->status == 3){
                    $status = '<div class="badge bg-danger">Pending</div>';
                }else{
                    $status = '<div class="badge bg-success">Ordered</div>';
                }
                $nestedData['status'] =$status;
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
                    $nestedData['options'] .= ' <li><a href="'. route('purchase.edit', $purchase->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';
                }
                if($p_purchase_return){
                    $nestedData['options'] .= ' <li><a href="'. route('purchase_return.add', $purchase->id) .'" class="btn btn-link"><i class="bx bx-undo"></i> Purchase Return</a></li>';
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
                    $nestedData['options'] .= ' <li> <form action="'. route('purchase.delete',$purchase->id).'" method="post"> <input type="hidden" name="_token" value="'. csrf_token().'"><button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="bx bx-trash"></i>Delete</button></form></li>';
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
        if(can_p('purchase.create') == false){
            return redirect()->route('dashboard');
        }
       $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view ('Inventory.purchase.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if(can_p('purchase.create') == false){
            return redirect()->route('dashboard');
        }

        $validator_arr=[];
        $validator_e_msgg_arr=[];
        $is_valid_product = 0;



        // $validator_arr["mobile"] = ["required"];
        $validator_arr["name"] = ["required"];
        // $validator_arr["branch"] = ["required"];
        $validator_arr["unit.*"] =["required"];
        if(array_search('accounts',$this->pack_option()) != false){
            $this->inital_account();
            if($request->paid_amount > 0 || $request->payment_method != ''){
                $validator_arr["payment_method"] = ["required"];
                $validator_arr["account"] = ["required"];
            }
        }else{
            if($request->paid_amount > 0 || $request->payment_method != ''){
                $validator_arr["payment_method"] = ["required"];
            }
        }

        // $validator_e_msgg_arr["email"] = "Company Email is required";
        // $validator_arr["mobile"] = ["required"];
        // $validator_e_msgg_arr["mobile"] = "Company Mobile is required";
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
            $vendor = Vendor::where('email', $request->email)->first();
            if($vendor == null){
                $vendor = new Vendor();
                $vendor->email = $request->email;
                $vendor->name = $request->name;
                $vendor->mobile = $request->mobile;
                $vendor->address = $request->address;
                $vendor->save();
            }else{

                $vendor->email = $request->email;
                $vendor->name = $request->name;
                $vendor->mobile = $request->mobile;
                $vendor->address = $request->address;
                $vendor->save();
            }
            $purchase = New Purchase;
            $purchase->reference_no = 'pr-' . date("Ymd") . '-'. date("his");
            $purchase->branch_id = $request->branch ?? 0;
            $purchase->supplier_id= $vendor->id;
            $purchase->item = $request->item;

            $purchase->total_discount = round($request->total_discount,2);
            $purchase->total_cost = round($request->total_cost,2);
            $purchase->order_discount = round($request->order_discount,2);
            $purchase->shipping_cost = round($request->shipping_cost,2);
            $purchase->total_tax = round($request->total_tax,2);
            $purchase->paid_amount = round($request->paid_amount,2);
            $purchase->due_amount = round($request->grand_total,2) - round($request->paid_amount,2);
            $purchase->grand_total = round($request->grand_total,2);

            $purchase->total_qty = $request->total_qty;

            // $purchase->total_discount =  $request->total_discount;
            // $purchase->total_cost = $request->total_cost;
            // $purchase->order_discount = $request->order_discount;
            // $purchase->shipping_cost = $request->shipping_cost;
            // $purchase->total_tax = $request->total_tax;
            // $purchase->paid_amount = $request->paid_amount;
            // $purchase->due_amount = $request->grand_total - $request->paid_amount;
            // $purchase->grand_total = $request->grand_total;
            $purchase->status = $request->status;
            //dd($purchase->due_amount == 0);
            $purchase->is_ini_p= $request->is_ini_p ?? 0;
            if($request->payment_method != ''){
                if($purchase->due_amount == 0){
                    $purchase->payment_status = 2;
                }else{
                    $purchase->payment_status = 1;
                }
            }else{
                $purchase->payment_status = 0;
            }
            $file=$request->file('document');
            if($file){
                $filename=date('YmdHi')."_purchase_document".$file->getClientOriginalName();
                $file->move(public_path('upload/purchase'),$filename);
                $purchase->document=$filename;
            }
            $purchase->note = $request->order_note;
            if ($request->purchase_date == null) {
                $purchase->purchase_date = now();
            } else {
                $invdate = Carbon::parse($request->purchase_date)->format('Y-m-d');
                $purchase->purchase_date = $invdate;
            }
           // dd($purchase);
            $purchase->payment_method= $request->payment_method ?? 0;
            $purchase->bank_account_id= $request->account ?? 0;
            $purchase->save();
            foreach($request->product_id as $k=>$product_id) {
                if(null != $product_id){
                    $product_purchase = New ProductPurchase;
                    $product_purchase->purchase_id = $purchase->id;
                    $product_purchase->product_id = $product_id;

                    $product_purchase->unit_id = $request->unit[$k];
                    $product_purchase->qty = $request->qty[$k] ?? 0;
                    $product_purchase->tax = $request->tax[$k] ?? 0;
                    $product_purchase->per_cost = $request->per_cost[$k] ?? 0;
                    $product_purchase->total = $request->total[$k] ?? 0;
                    $product_purchase->discount = $request->discount[$k] ?? 0;
                    $product_purchase->save();
                    $stock = new Stock;
                    $stock->purchase_id = $purchase->id;
                    $stock->product_purchase_id = $product_purchase->id;
                    $stock->product_id = $product_id;
                    $stock->unit_id = $request->unit[$k] ?? 0;
                    $stock->in_qty = $request->qty[$k]?? 0;
                    $stock->purchase_price = $request->total[$k];
                    $stock->inventory_type = 'Purchase';
                    $stock->save();
                    $product_stock =  Product::find($product_id);
                    if($product_stock){
                        $product_stock->total_qty= $product_stock->qty + ($request->qty[$k]?? 0);
                        $product_stock->save();
                    }


                }
            }
            if(array_search('accounts',$this->pack_option()) != false){
                $purchaseHead = AccountHead::where("code",'5000')->first();

                $purchase_trans = New AccountTransaction;
                $purchase_trans->amount = $request->grand_total;
                $purchase_trans->account_id = $purchaseHead->id;
                $purchase_trans->type = "debit";
                $purchase_trans->sub_type = "Purchase";
                $purchase_trans->reason = "Purchase Product From Supplier";
                $purchase_trans->date = $request->purchase_date == null ?  date('Y-m-d') :  Carbon::parse($request->purchase_date)->format('Y-m-d');
                $purchase_trans->relation_id = $purchase->id;
                $purchase_trans->relation_with = "Purchase";
                $purchase_trans->save();

                if($request->payment_method == ''){
                    $acPayableHead = AccountHead::where("code",'2000')->first();

                    $acp_trans = New AccountTransaction;
                    $acp_trans->amount = $request->grand_total;
                    $acp_trans->account_id = $acPayableHead->id;
                    $acp_trans->type = "credit";
                    $acp_trans->sub_type = "Purchase";
                    $acp_trans->reason = "Purchase Product From Supplier With Due";
                    $acp_trans->date = $request->purchase_date == null ?  date('Y-m-d') :  Carbon::parse($request->purchase_date)->format('Y-m-d');
                    $acp_trans->relation_id = $purchase->id;
                    $acp_trans->relation_with = "Purchase";
                    $acp_trans->trans_id = $purchase_trans->id;
                    $acp_trans->save();
                    $purchase_trans->trans_id = $acp_trans->id;
                    $purchase_trans->save();
                }else{
                    $balance_account = BalanceAccount::find($request->account);
                    $payment = New Payment;
                    $payment->payment_method= $request->payment_method ?? 0;
                    $payment->bank_account_id= $request->account ?? 0;
                    //$payment->is_fst= 1;

                    // $payment->transaction_id= $sc_pay_transaction->id;
                    $payment->relation_id = $purchase->id;
                    $payment->relation_type = "Purchase Payment";
                    $payment->amount = $request->paid_amount;
                    $payment->date =  $request->purchase_date == null ?  date('Y-m-d') :  Carbon::parse($request->purchase_date)->format('Y-m-d');
                    $payment->note = $request->order_note;
                    $payment->save();

                    $pay_trans = New AccountTransaction;
                    $pay_trans->amount = $request->paid_amount;
                    $pay_trans->account_id = $balance_account->account_head_id;
                    $pay_trans->type = "credit";
                    $pay_trans->sub_type = "Purchase Payment";
                    $pay_trans->reason = "Purchase Payment for invoice #". $purchase->reference_no;
                    $pay_trans->date = $request->purchase_date == null ?  date('Y-m-d') :  Carbon::parse($request->purchase_date)->format('Y-m-d');
                    $pay_trans->relation_id = $purchase->id;
                    $pay_trans->payment_id = $payment->id;
                    $pay_trans->relation_with = "Purchase";
                    $pay_trans->trans_id = $purchase_trans->id;
                    $pay_trans->save();
                    $payment->transaction_id= $pay_trans->id;
                    $payment->save();
                    $purchase_trans->trans_id = $pay_trans->id;
                    $purchase_trans->save();

                    if($purchase->due_amount > 0){
                        $salesDueHead = AccountHead::where("code",'2000')->first();

                        $acp_trans = New AccountTransaction;
                        $acp_trans->amount =  $purchase->due_amount;
                        $acp_trans->account_id = $salesDueHead->id;
                        $acp_trans->type = "credit";
                        $acp_trans->sub_type = "Purchase";
                        $acp_trans->reason = "Purchase Product From Supplier With Due";
                        $acp_trans->date = $request->purchase_date == null ?  date('Y-m-d') :  Carbon::parse($request->purchase_date)->format('Y-m-d');
                        $acp_trans->relation_id = $purchase->id;
                        $acp_trans->relation_with = "Purchase";
                        $acp_trans->is_trans2 = 2;
                        $acp_trans->trans_id = $purchase_trans->id;
                        $acp_trans->save();
                        $purchase_trans->is_trans2 = 1;
                        $purchase_trans->trans2_id = $acp_trans->id;
                        $purchase_trans->save();
                    }

                }
            }
            DB::commit();

            $notification=array(
                'message'=>"Purchase Successfully Completed",
                'alert-type'=>'success'
            );
            return redirect()->route('purchase.print',$purchase->id)->with($notification);
            return redirect()->route('purchase.index')->with($notification);
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
    function printPurchase($id){
        $data['purchase']=Purchase::find($id);
        return view('Inventory.purchase.print_purchase',$data);
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
        if(can_p('purchase.edit') == false){
            return redirect()->route('dashboard');
        }
        $data['purchase'] =$purchase = Purchase::find($id);
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        // dd($purchase->items);
        return view ('Inventory.purchase.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(can_p('purchase.edit') == false){
            return redirect()->route('dashboard');
        }
        $validator_arr=[];
        $validator_e_msgg_arr=[];
        $is_valid_product = 0;
        if($request->old_product_id){
            $validator_arr["old_unit.*"] =["required"];
        }
        if($request->product_id){
            $validator_arr["unit.*"] =["required"];
        }

        if($request->product_id || $request->old_product_id){

        }else{

            $validator_arr["product.0"] =["required"];
            $validator_e_msgg_arr["product.0"] = "Product is required";
        }



        // $validator_arr["email"] = ["required"];
        // $validator_e_msgg_arr["email"] = "Company Email is required";
        // $validator_arr["mobile"] = ["required"];
        // $validator_e_msgg_arr["mobile"] = "Company Mobile is required";
        $validator = Validator::make($request->all(),
            $validator_arr,
            $validator_e_msgg_arr
            // "color.*"  => "required|string|distinct|min:3",
        );

        if ($validator->fails()) {
            // dd($validator->errors());
            return back()->with("errors",$validator->errors()->all());
        }


        try{
            DB::beginTransaction();
            $vendor = Vendor::where('email', $request->email)->first();
            if($vendor == null){
                $vendor = new Vendor();
                $vendor->email = $request->email;
                $vendor->name = $request->name;
                $vendor->mobile = $request->mobile;
                $vendor->address = $request->address;
                $vendor->save();
            }
            $purchase = Purchase::find($id);
            // $purchase->reference_no = 'pr-' . date("Ymd") . '-'. date("his");
            // $purchase->branch_id = $request->branch;
            $purchase->supplier_id= $vendor->id;
            $purchase->item = $request->item;
            $purchase->total_qty = $request->total_qty;
            $purchase->total_discount =  $request->total_discount;
            $purchase->total_cost = $request->total_cost;
            $purchase->order_discount = $request->order_discount;
            $purchase->shipping_cost = $request->shipping_cost;
            $purchase->total_tax = 0;
            $old_paid_amount = $purchase->paid_amount;
            $purchase->paid_amount =  $request->paid_amount;
            $purchase->due_amount = $request->grand_total - $request->paid_amount;
            $purchase->grand_total = $request->grand_total;
            $purchase->status = $request->status;
            if($request->payment_method != ''){
                if($purchase->due_amount == 0){
                    $purchase->payment_status = 2;
                }else{
                    $purchase->payment_status = 1;
                }

            }else{
                $purchase->payment_status = 0;
            }
            $file=$request->file('document');
            if($file){
                $filename=date('YmdHi')."_purchase_document".$file->getClientOriginalName();
                $file->move(public_path('upload/purchase'),$filename);
                $purchase->document=$filename;
            }
            $purchase->total_tax = $request->total_tax;
            $purchase->note = $request->order_note;
            if ($request->purchase_date == null) {
                $purchase->purchase_date = now();
            } else {
                $invdate = Carbon::parse($request->purchase_date)->format('Y-m-d');
                $purchase->purchase_date = $invdate;
            }
            $purchase->payment_method= $request->payment_method ?? 0;
            $purchase->bank_account_id= $request->account ?? 0;
            $purchase->is_ini_p= $request->is_ini_p ?? 0;
            $purchase->save();
            //dd($request->color);
            if($request->old_product_id){
                // dd($request->old_product_id);
                foreach($request->old_product_id as $k=>$product_id) {

                    $product_purchase = ProductPurchase::find($k);
                    $old_qty = $product_purchase->qty;

                    $product_purchase->purchase_id = $purchase->id;
                    $product_purchase->product_id = $product_id;
                    // $product_purchase->color_id = $request->old_color[$k] ?? 0;
                    // $product_purchase->size_id = $request->old_size[$k] ?? 0;
                    $product_purchase->unit_id = $request->old_unit[$k];
                    $product_purchase->qty = $request->old_qty[$k] ?? 0;
                    $product_purchase->per_cost = $request->old_per_cost[$k] ?? 0;
                    $product_purchase->tax = $request->tax[$k] ?? 0;
                    $product_purchase->total = $request->old_total[$k] ?? 0;
                    $product_purchase->discount = $request->old_discount[$k] ?? 0;
                    $product_purchase->save();
                    $stock =  Stock::where('product_purchase_id',$k)->first();
                    // $stock->purchase_id = $purchase->id;
                    // $stock->product_purchase_id = $product_purchase->id;
                    $stock->product_id = $product_id;
                    // $stock->color_id = $request->old_color[$k] ?? 0;
                    // $stock->size_id = $request->old_size[$k] ?? 0;
                    $stock->unit_id = $request->old_unit[$k] ?? 0;
                    $stock->in_qty = $request->old_qty[$k]?? 0;
                    $stock->purchase_price = $request->old_total[$k];
                    $stock->inventory_type = 'Purchase';
                    $stock->save();
                    $product_stock =  Product::find($product_id);
                    if($product_stock){
                        if($product_stock->qty > 0){
                            $product_stock->qty= $product_stock->qty - $old_qty + ($request->old_qty[$k]?? 0);
                        }else{
                            $product_stock->qty= $product_stock->qty + ($request->old_qty[$k]?? 0);
                        }
                        $product_stock->save();
                    }
                    $product_stock =  ProductStock::where('product_id',$product_id)->where('business_id', auth()->user()->business->id)->first();
                    //dd($product_stock);
                    if($product_stock->qty > 0){
                        $product_stock->qty= $product_stock->qty - $old_qty + ($request->old_qty[$k]?? 0);
                    }else{
                        $product_stock->qty= $product_stock->qty + ($request->old_qty[$k]?? 0);
                    }

                    $product_stock->save();

                }
            }
            if($request->delete_item){
                foreach($request->delete_item as $k=>$item) {
                    $stock =  Stock::where('product_purchase_id',$item)->first();
                    $stock->delete();
                    $product_purchase = ProductPurchase::find($item);
                    $product_stock =  Product::find($product_id);
                    if($product_stock){
                        $product_stock->qty= $product_stock->qty - $product_purchase->qty;
                        $product_stock->save();
                    }

                    $product_purchase->delete();
                }
            }
            if($request->product_id){
                foreach($request->product_id as $k=>$product_id) {
                    if(null != $product_id){
                        $product_purchase = New ProductPurchase;
                        $product_purchase->purchase_id = $purchase->id;
                        $product_purchase->product_id = $product_id;
                        // $product_purchase->color_id = $request->color[$k] ?? 0;
                        // $product_purchase->size_id = $request->size[$k] ?? 0;
                        $product_purchase->unit_id = $request->unit[$k];
                        $product_purchase->tax = $request->tax[$k] ?? 0;
                        $product_purchase->qty = $request->qty[$k] ?? 0;
                        $product_purchase->per_cost = $request->per_cost[$k] ?? 0;
                        $product_purchase->total = $request->total[$k] ?? 0;
                        $product_purchase->discount = $request->discount[$k] ?? 0;
                        $product_purchase->save();
                        $stock = new Stock;
                        $stock->purchase_id = $purchase->id;
                        $stock->product_purchase_id = $product_purchase->id;
                        $stock->product_id = $product_id;
                        // $stock->color_id = $request->color[$k] ?? 0;
                        // $stock->size_id = $request->size[$k] ?? 0;
                        $stock->unit_id = $request->unit[$k] ?? 0;
                        $stock->in_qty = $request->qty[$k]?? 0;
                        $stock->purchase_price = $request->total[$k];
                        $stock->inventory_type = 'Purchase';
                        $stock->save();
                        $product_stock =  Product::find($product_id);
                        if($product_stock){
                            $product_stock->qty=  $product_stock->qty + ($request->qty[$k]?? 0);
                            $product_stock->save();
                        }
                        $product_stock =  ProductStock::where('product_id',$product_id)->where('business_id', auth()->user()->business->id)->first();


                    }
                }
            }
            if(array_search('accounts',$this->pack_option()) != false){
                $purchaseHead = AccountHead::where("code",'5000')->first();


                $purchase_trans =  AccountTransaction::where("relation_id",$purchase->id)->where('relation_with','Purchase')->where('account_id',$purchaseHead->id)->first();
                if($purchase_trans == null){
                    $purchase_trans = New AccountTransaction;
                }
                $purchase_trans->amount = $request->grand_total;
                $purchase_trans->account_id = $purchaseHead->id;
                $purchase_trans->type = "debit";
                $purchase_trans->sub_type = "Purchase";
                $purchase_trans->reason = "Purchase Product From Supplier";
                $purchase_trans->date = $request->purchase_date == null ?  date('Y-m-d') :  Carbon::parse($request->purchase_date)->format('Y-m-d');
                $purchase_trans->relation_id = $purchase->id;
                $purchase_trans->relation_with = "Purchase";
                $purchase_trans->save();
                $acpHead = AccountHead::where("code",'2000')->first();

                $payment =  Payment::where("transaction_id",$purchase->trans_id)->first();

                if($payment == null){
                    $acp_trans =  AccountTransaction::find($purchase->trans_id);
                    if($acp_trans == null){
                        $acp_trans = new AccountTransaction;
                    }
                    $acp_trans->amount = $request->grand_total;
                    $acp_trans->account_id = $acpHead->id;
                    $acp_trans->type = "credit";
                    $acp_trans->sub_type = "Purchase";
                    $acp_trans->reason = "Purchase Product From Supplier With Due";
                    $acp_trans->date = $request->purchase_date == null ?  date('Y-m-d') :  Carbon::parse($request->purchase_date)->format('Y-m-d');
                    $acp_trans->relation_id = $purchase->id;
                    $acp_trans->relation_with = "Purchase";
                    $acp_trans->trans_id = $purchase_trans->id;
                    $acp_trans->save();
                    $purchase_trans->trans_id = $acp_trans->id;
                    $purchase_trans->save();
                }else{



                    if($purchase->due_amount > 0){


                        $acp_trans =  AccountTransaction::find($purchase->trans2_id);
                        if($acp_trans == null){
                            $acp_trans = new AccountTransaction;
                        }
                        $acp_trans->amount =  $purchase->due_amount;
                        $acp_trans->account_id = $acpHead->id;
                        $acp_trans->type = "credit";
                        $acp_trans->sub_type = "Purchase";
                        $acp_trans->reason = "Purchase Product From Supplier With Due";
                        $acp_trans->date = $request->purchase_date == null ?  date('Y-m-d') :  Carbon::parse($request->purchase_date)->format('Y-m-d');
                        $acp_trans->relation_id = $purchase->id;
                        $acp_trans->relation_with = "Purchase";
                        $acp_trans->is_trans2 = 2;
                        $acp_trans->trans_id = $purchase_trans->id;
                        $acp_trans->save();
                        $purchase_trans->is_trans2 = 1;
                        $purchase_trans->trans2_id = $acp_trans->id;
                        $purchase_trans->save();
                    }

                }
            }

            DB::commit();
            $notification=array(
                'message'=>"Purchase Successfully Upload",
                'alert-type'=>'success'
            );
            return redirect()->route('purchase.index')->with($notification);
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
        if(can_p('purchase.view') == false){
            return redirect()->route('dashboard');
        }
         $data['purchase'] = Purchase::find($id);
        return view ('Inventory.purchase.ajax-view-data', $data);
    }
    function paymentList($id){
        if(can_p('purchase.payment_show') == false){
            return redirect()->route('dashboard');
        }
         $data['purchase'] = Purchase::find($id);
         $data['payments'] = Payment::where('relation_id', $id)->where('relation_type','Purchase Payment')->get();
        return view ('Inventory.purchase.ajax-view-data-payment', $data);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(can_p('purchase.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $purchase = Purchase::find($id);
            // dd($purchase);
            foreach ($purchase->items as $item) {
                $product_stock =  Product::find($item->product_id);
                if($product_stock){
                    $product_stock->qty=  $product_stock->qty - $item->qty;
                    $product_stock->save();
                }
                $item->delete();
            }
            $payments =  Payment::where('relation_id', $id)->where('relation_type','Purchase Payment')->get();
            foreach ($payments as $item) {
                $item->delete();
            }
            $account_payments= AccountTransaction::where('relation_id', $id)->where('relation_with','Purchase')->get();
            foreach ($account_payments as $item) {
                $item->delete();
            }
            $stocks = Stock::where('purchase_id',$purchase->id)->get();
            foreach ($stocks as $item) {
                $item->delete();
            }
            $purchase->delete();
            DB::commit();
            $notification=array(
                'message'=>"Purchase Successfully Delete",
                'alert-type'=>'success'
            );
            return redirect()->route('purchase.index')->with($notification);
        }catch (\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }
    }
    function storePayment(Request $request){
        if(can_p('purchase.add-payment') == false){
            return redirect()->route('dashboard');
        }
        $validator = Validator::make($request->all(),[
            'payment_method'=>'required',
            'account'=>'required',
            'payment_date'=>'required',
            'amount'=>'required|numeric|min:0|max:'.$request->due_amount,
            "purchase_id" => 'required'
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
            $purchase = Purchase::find($request->purchase_id);
            $purchase->paid_amount = $purchase->paid_amount+$request->amount;
            $purchase->due_amount = $purchase->grand_total - $purchase->paid_amount;
            if($purchase->due_amount == 0){
                $purchase->payment_status = 2;
            }else{
                $purchase->payment_status = 1;
            }
            $purchase->save();

            $payment = New Payment;
            $payment->payment_method= $request->payment_method ?? 0;
            $payment->bank_account_id= $request->account ?? 0;
            // $payment->transaction_id= $sc_pay_transaction->id;
            $payment->relation_id = $purchase->id;
            $payment->relation_type = "Purchase Payment";
            $payment->amount = $request->amount;
            $payment->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $payment->note = $request->order_note;
            $payment->save();

            $salesDueHead = AccountHead::where("code",'2000')->first();

            $sc_due_transaction = New AccountTransaction;
            $sc_due_transaction->amount = $request->amount;
            $sc_due_transaction->account_id = $salesDueHead->id;
            $sc_due_transaction->type = "debit";
            $sc_due_transaction->sub_type = "Purchase Payment";
            $sc_due_transaction->reason =  "Purchase Payment For Invoice #".$purchase->reference_no;
            $sc_due_transaction->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $sc_due_transaction->relation_id = $purchase->id;
            $sc_due_transaction->relation_with = "Purchase";
            $sc_due_transaction->payment_id = $payment->id;
            $sc_due_transaction->save();


            $balance_account = BalanceAccount::find($request->account);

            $sc_pay_transaction = New AccountTransaction;
            $sc_pay_transaction->amount = $request->amount;
            $sc_pay_transaction->account_id = $balance_account->account_head_id;
            $sc_pay_transaction->type = "credit";
            $sc_pay_transaction->relation_with = "Purchase";
            $sc_pay_transaction->sub_type = "Purchase Payment";
            $sc_pay_transaction->reason = "Purchase Payment For Invoice #".$purchase->reference_no;
            $sc_pay_transaction->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
            $sc_pay_transaction->relation_id = $purchase->id;
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
}
