<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Category;
use App\Models\Inventory\Customer;
use App\Models\Inventory\Payment;
use App\Models\Inventory\Product;
use App\Models\Inventory\Stock;
use App\Models\Inventory\Tax;
use App\Models\PosSale;
use App\Models\PosSaleDetails;
use App\Models\ProductStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Inventory\ProductInvoice;
use App\Models\Inventory\Invoice;
class PosController extends Controller
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
    function index(){
        if(can_p('pos.sale.index') == false){
            return redirect()->route('dashboard');
        }

        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();


        return view ('Inventory.pos.manage', $data );
    }
    function ajaxPos(Request $request){

        $columns = array(
            0 => 'invoices.id',
            1 => 'invoices.sale_date',
            2 => 'invoices.reference_no',
            3 => 'customers.name',
            4 => 'invoices.grand_total',
            5 => 'invoices.paid_amount',
            6 => 'invoices.due_amount',
            7 => 'invoices.status',
            8 => 'invoices.payment_status',
        );
        $totalData = Invoice::where('invoices.is_pos',1)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $invoices = Invoice::leftjoin('customers','customers.id','invoices.customer_id')
                            ->where('invoices.is_pos',1);
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


        // $invoices = PosSale::leftjoin('customers','customers.id','pos_sales.customer_id')->;
        // if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
        //     $invoices->where('branch_id',auth()->user()->branch_id);
        // }
        // if(!empty($search))
        // {
        //     $invoices = $invoices->where("pos_sales.invoice_date","LIKE","%{$search}%")
        //                 ->orWhere("pos_sales.reference_no","LIKE","%{$search}%")
        //                 ->orWhere("customers.name","LIKE","%{$search}%");

        // }
        // $totalFiltered = $invoices->count();
        // $invoices = $invoices->select('pos_sales.*','customers.name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($invoices))
        {
            $i = $start == 0 ? 1 : $start+1;
            // $p_edit = can_p('invoice.edit');
            // $p_delete = can_p('invoice.delete');
            // $p_view = can_p('invoice.view');
            // $p_add_payment = can_p('invoice.add-payment');
            // $p_payment_show = can_p('invoice.payment_show');
            // $p_sales_return = can_p('invoice_return.add');
            //$p_print= can_p('invoice.print');
            foreach($invoices as $invoice)
            {
                $nestedData['id'] = $i++;

                $nestedData['date'] = date('Y-m-d', strtotime($invoice->invoice_date));
                $nestedData['reference'] = $invoice->reference_no;
                $nestedData['cus_name'] = $invoice->cus_name;
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
                $nestedData['options'] = '<div class="btn-group"><button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';
                // if($p_view){
                     $nestedData['options'] .= ' <li><button data-id="'.$invoice->id.'" type="button" class="btn btn-link view"><i class="bx bx-show"></i>View</button> </li>';
                // }
                // if($p_edit){
                //     $nestedData['options'] .= ' <li><a href="'. route('invoice.edit', $invoice->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';
                // }
                // if($p_sales_return){
                //     $nestedData['options'] .= ' <li><a href="'. route('invoice_return.add', $invoice->id) .'" class="btn btn-link"><i class="bx bx-undo"></i> Sales Return</a></li>';
                // }
                // if(array_search('accounts',$this->pack_option()) != false){
                //     if($p_add_payment){
                //         if($invoice->due_amount > 0){
                //             $nestedData['options'] .= '<li><button data-due="'. $invoice->due_amount .'" type="button" class="add-payment btn btn-link" data-id = "'. $invoice->id .'" data-bs-toggle="modal" data-bs-target="#add-payment"><i class="bx bx-plus"></i>Add Payment</button></li>';
                //         }
                //     }
                //     if($p_payment_show){
                //         if($invoice->payment_status != 0){
                //             $nestedData['options'] .= ' <li><button type="button" class="payment_show btn btn-link" data-id = "'. $invoice->id .'"><i class="bx bx-money"></i> View Payment</button></li>';
                //         }
                //     }
                // }
                // if($p_delete){
                //     $nestedData['options'] .= ' <li> <form action="'. route('invoice.delete',$invoice->id).'" method="post"><input type="hidden" name="_token" value="'. csrf_token().'"><button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="bx bx-trash"></i>Delete</button></form></li>';
                // }

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
    function create(){
        $this->init_account();
        $data['categories']=$categories = Category::where('business_id',auth()->user()->business->id)->orderBy('id','DESC')->get();
        $data['brands']=$brands = Brand::where('business_id',auth()->user()->business->id)->orderBy('id','DESC')->get();
        $data['products']=$products = Product::where('is_variant',0)->where('business_id',auth()->user()->business->id)->paginate(20);
        $data['methods']=PaymentMethod::where('for_pos',1)->orderBy('sorting','asc')->get();
        //dd($products);
        return view('Inventory.pos.create',$data);
    }
    function searchProduct(Request $request){

        $products = Product::where('is_variant',0)->where('business_id',auth()->user()->business->id);
        if($request->category_id){
            $products = $products->where('category_id',$request->category_id);
        }
        if($request->brand_id){
            $products = $products->where('brand_id',$request->brand_id);
        }
        if($request->search){
            if($request->is_barcode){
                //dd("ss");
                $p_arr = explode('-',$request->search);
                if(isset($p_arr[1])){
                    $products =$products->where('id',$p_arr[1]);
                }else{
                    $products = $products->where('id',$request->search);
                }
            }else{
                //dd("ssdf");
                $search = $request->search;
                $products = $products->where(function($query) use ($search){
                    $query->where('product_name', 'like', '%'.$search.'%');
                    $query->where('product_code', 'like','%'.$search.'%');
                });

                //dd($products->get());
            }
        }
        if($request->current_product_con){
            if($request->current_product_con == "category_id"){
                $products = $products->where('category_id',$request->current_product_con_val);
            }
            if($request->current_product_con == "brand_id"){
                $products = $products->where('brand_id',$request->current_product_con_val);
            }
        }
        $products = $products->paginate(20);
        //return $products;
        $data['products']=$products;
        //dd($products);
        if($request->search){
            if($request->is_barcode){
                if($products->count() > 0){
                    return response()->json(['data'=>view('Inventory.pos.search-product-list',$data)->render(),'barcode_res'=>true]);
                }else{
                    return response()->json(['barcode_res'=>false]);
                }
            }else{
                return response()->json(['data'=>view('Inventory.pos.search-product-list',$data)->render()]);
            }
        }else{
            if($request->current_product_con){
                return response()->json(['data'=>view('Inventory.pos.product-list',$data)->render()]);
            }else{
                return response()->json(['data'=>view('Inventory.pos.product-list',$data)->render(),'pagination'=>view('Inventory.pos.custom-pagination',$data)->render()]);
            }

        }

    }
    function searchCustomer(Request $request){
        $search = $request->search;
        $customers = Customer::query();
        $customers = $customers->where("customers.name","LIKE","%{$search}%")
                        ->orWhere("customers.mobile","LIKE","%{$search}%");

        $data['customers'] = $customers->select('customers.*')->paginate(10);
        return response()->json(['data'=>view('Inventory.pos.search-customer-list',$data)->render()]);
    }
    function productDetails(Request $request){
        $product = Product::find($request->id);
       // $product_stock = $product->product_stock;
        $data['unit_price']=$unit_price =  $product->sale_price;
        $data['purchase_price']=  $product->purchase_price;
        $data['stock']= $product->qty;
        $data['discount_type']=$dis_type =$product->discount_type ?? 'percent';
        $data['discount']=$dis= $product->discount ?? 0;
        $data['tax_id']=$tax_id= $product->tax_id ?? 0;
        $data['unit_id']=$unit_id= $product->unit_id ?? 0;
        $data['taxes']=Tax::get();
        $data['qty']=1;
        $data['product'] = $product;
        $tax = Tax::find($tax_id);
        $data['tax_r']= 0;
        if($tax){
            if($tax->rate_type == "Percentage"){
                $data['tax_r']= $unit_price * $tax->rate/100;
            }else{
                $data['tax_r']= $tax->rate;
            }

        }
        if($dis_type == 'percent'){
            $data['p_discount']= $unit_price *  $dis/100;
        }else{
            $data['p_discount']= $dis;
        }

        return response()->json(['data'=>view('Inventory.pos.cart-product',$data)->render(),'product'=>$product]);
    }
    function addCustomer(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'required',
            'mobile'=> 'required',
            'email'=>[
                'required',
                'email',
                Rule::unique('customers')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business->id);
                }),
            ],
        ]);
        if($validator->fails()){
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            $data=new Customer();
            $data->name=$request->name;
            $data->email=$request->email ?? '';
            $data->mobile=$request->mobile ?? '';
            $data->address=$request->address ?? '';
            $data->country_id=$request->country ?? 0;
            $data->state_id=$request->state ?? 0;
            $data->city_id=$request->city ?? 0;
            $data->zip_code=$request->zip_code ?? '';
            $data->save();
            DB::commit();
            return response([
                'status' => 1,
                'data'=>$data,
                'message' => 'Save successfully.',
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'message'=> $e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }
    }
    function salePos(Request $request){
     //dd($request->all());

        $validator_arr["qty"] =["required"];
        $validator_e_msgg_arr["qty"] = "Product is required";
        $validator = Validator::make($request->all(),
            $validator_arr,
            $validator_e_msgg_arr,
        // "color.*"  => "required|string|distinct|min:3",
        );

        if ($validator->fails()) {
            $notification=array(
                'errors'=>$validator->errors()->all(),
                'status'=>'errors'
            );
            return response()->json($notification);
            return back()->withErrors($validator->errors())->with("cus_errors",$validator->errors()->all())->withInput($request->all());
        }
        try{
            DB::beginTransaction();
            $invoice = New Invoice;
            $invoice->reference_no = 'pos-' . date("Ymd") . '-'. date("his");
            $invoice->branch_id = $request->branch ?? 0;

            $invoice->customer_id= $request->customer_id ?? 0;
            $invoice->item = $request->item ?? 0;
            $invoice->total_qty = $request->total_qty;
            $invoice->total_discount = round($request->total_discount,2);
            $invoice->total_cost = round($request->sub_total,2);
            // $invoice->order_discount = round($request->order_discount,2);
            // $invoice->shipping_cost = round($request->shipping_cost,2);
            $invoice->total_tax = round($request->total_tax,2);
            $invoice->paid_amount = round($request->grand_total,2);

            $invoice->receive_amount = round($request->receive_amount,2);
            $invoice->change_amount = round($request->change_amount,2);

            $invoice->due_amount = 0;
            $invoice->grand_total = round($request->grand_total,2);
            $invoice->payment_method= $request->payment_method ?? 0;
            $invoice->bank_account_id= $request->account ?? 0;
            $invoice->is_pos= 1;
            $invoice->status = 1;
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


            $invoice->invoice_date = now();
            // $invoice->sale_date = now();
            $invoice->save();
            foreach($request->qty as $product_id=>$qty) {
                if(null != $product_id){
                    $product_invoice = New ProductInvoice;
                    $product_invoice->invoice_id = $invoice->id;
                    $product_invoice->product_id = $product_id;
                    $product_invoice->unit_id = $request->unit[$product_id];
                    $product_invoice->qty = $qty ?? 0;
                    $product_invoice->tax = round($request->tax[$product_id] ?? 0,2);
                    $product_invoice->per_cost = round($request->price[$product_id] ?? 0,2);
                    $product_invoice->purchase_price = round($request->purchase_price[$product_id] ?? 0,2);
                    $product_invoice->total = round((($request->price[$product_id] * $qty) ?? 0),2);
                    $product_invoice->total_purchase = round((($request->purchase_price[$product_id] * $qty) ?? 0),2);
                    $product_invoice->discount = round($request->discount[$product_id] ?? 0,2);
                    $product_invoice->is_pos= 1;
                    $product_invoice->save();

                    $stock = new Stock;
                    $stock->invoice_id = $invoice->id;
                    $stock->product_invoice_id = $product_invoice->id;
                    $stock->product_id = $product_id;
                    $stock->unit_id = $request->unit[$product_id] ?? 0;
                    $stock->out_qty = $qty ?? 0;
                    $stock->sale_price =round((($request->price[$product_id] * $qty) ?? 0),2);
                    $stock->inventory_type = 'Sales';
                    $stock->save();

                    $product_stock =  Product::find($product_id);
                    if($product_stock){
                        $product_stock->qty= $product_stock->qty - ($qty ?? 0);
                        $product_stock->save();
                    }
                }
            }

            if(array_search('accounts',$this->pack_option()) != false){
                $salesHead = AccountHead::where("code",'4000')->first();

                $sc_trans = New AccountTransaction();
                $sc_trans->amount = round($request->grand_total,2);
                $sc_trans->account_id = $salesHead->id;
                $sc_trans->type = "credit";
                $sc_trans->sub_type = "Pos Sales";
                $sc_trans->reason = "Pos Sale Product To Customer";
                $sc_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                $sc_trans->relation_id = $invoice->id;
                $sc_trans->relation_with = "Pos Sales";
                $sc_trans->save();
                if($request->payment_method == ''){
                    $acReceivableHead = AccountHead::where("code",'1000')->first();

                    $due_trans = New AccountTransaction;
                    $due_trans->amount = round($request->grand_total,2);
                    $due_trans->account_id = $acReceivableHead->id;
                    $due_trans->type = "debit";
                    $due_trans->sub_type = "Pos Sales";
                    $due_trans->reason = "Pos Sale Product To Customer With Due";
                    $due_trans->date = date('Y-m-d');
                    $due_trans->relation_id = $invoice->id;
                    $due_trans->relation_with = "Pos Sales";
                    $due_trans->trans_id = $sc_trans->id;
                    $due_trans->save();
                    $sc_trans->trans_id = $due_trans->id;
                    $sc_trans->save();
                }else{

                    $balance_account = BalanceAccount::find($request->account);
                    $payment = New Payment();
                    $payment->payment_method= $request->payment_method ?? 0;
                    $payment->bank_account_id= $request->account ?? 0;
                    // $payment->transaction_id= $sc_pay_transaction->id;
                    $payment->relation_id = $invoice->id;
                    $payment->relation_type = "Pos Sales Payment";
                    $payment->amount = round($invoice->paid_amount,2);
                    $payment->date = date('Y-m-d');
                    $payment->note = $request->order_note;
                    $payment->save();

                    $pay_trans = New AccountTransaction;
                    $pay_trans->amount = round($invoice->paid_amount,2);
                    $pay_trans->account_id = $balance_account->account_head_id;
                    $pay_trans->type = "debit";
                    $pay_trans->sub_type = "Pos Sales";
                    $pay_trans->reason = "Pos Sales Payment";
                    $pay_trans->date = date('Y-m-d');
                    $pay_trans->relation_id = $invoice->id;
                    $pay_trans->relation_with = "Pos Sales";
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
                        $sc_due_transaction->amount = round($request->grand_total-$invoice->paid_amount,2);
                        $sc_due_transaction->account_id = $salesDueHead->id;
                        $sc_due_transaction->type = "debit";
                        $sc_due_transaction->sub_type = "Pos Sales";
                        $sc_due_transaction->reason = "Pos Sale Product To Customer With Due";
                        $sc_due_transaction->date =  date('Y-m-d') ;
                        $sc_due_transaction->relation_id = $invoice->id;
                        $sc_due_transaction->relation_with = "Pos Sales";
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
            if($request->type =="Sales"){
                $data['pos_sale'] = $invoice;
                $notification=array(
                    'message'=>"Sale Successfully Completed",
                    'data'=>$invoice,
                    'invoice'=>view('Inventory.pos.invoice',$data)->render(),
                    'status'=>'success'
                );
            }else{
                $notification=array(
                    'message'=>"Sale Successfully Completed",
                    'data'=>$invoice,
                    'status'=>'success'
                );
            }

            return response()->json($notification);

        }catch (\Exception $e){
            DB::rollBack();
           // dd($e->getMessage());
            $notification=array(
                // 'message'=>"Something went Wrong!",
                'message'=>$e->getMessage(),
                'status'=>'error'
            );
            return response()->json($notification);
            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    function saleInvoice(Request $request,$id){
        $data['pos_sale'] = Invoice::find($id);
        return view('Inventory.pos.invoice',$data);
    }
    function salePrint(Request $request,$id){
        $data['pos_sale'] = Invoice::find($id);
        return view('Inventory.pos.invoice',$data);
    }
    function saleDedtails($id){
        $data['pos_sale'] = Invoice::find($id);
        return view('Inventory.pos.ajax-details',$data);
    }
}
