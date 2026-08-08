<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DamageProduct;
use App\Models\DamageProductDetail;
use App\Models\DamageStock;
use App\Models\Inventory\Stock;
use App\Models\Inventory\Product;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class DamageController extends Controller
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
        $acPayableHead = AccountHead::where("code",'5055')->first();
        if($acPayableHead == null){
            $acPayableHead = new AccountHead;
            $acPayableHead->code = '5055';
            $acPayableHead->title = "Damage Good";
            $acPayableHead->sys = 0;
            $acPayableHead->ac_type = 5;
            $acPayableHead->note = '';
            $acPayableHead->status = 1;
            $acPayableHead->save();
        }
    }
    public function index()
    {
        //dd($this->pack_option());
        if(can_p('damage.index') == false){
            return redirect()->route('dashboard');
        }
        return view('Inventory.damage.manage');
    }
    function ajaxPurchase(Request $request){
       
        $columns = array(
            0 => 'damage_products.id',
            1 => 'damage_products.damage_from',
            2 => 'damage_products.damage_date',
            3 => 'damage_products.total_qty',
            4 => 'damage_products.grand_total',
            5 => 'options',
        );
        

        $totalData = DamageProduct::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $invoices = DamageProduct::query();
                            // ->where('invoices.is_pos',0);
        if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
            $invoices->where('branch_id',auth()->user()->branch_id);
        }
        if(!empty($search))
        {
            $invoices = $invoices->where(function($q) use($search){
                $q->where("damage_products.damage_date","LIKE","%{$search}%");
            });



        }
        $totalFiltered = $invoices->count();
        $invoices = $invoices->select('damage_products.*')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($invoices))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('damage.edit');
            $p_delete = can_p('damage.delete');
            foreach($invoices as $invoice)
            {
                $nestedData['id'] = $i++;

                $nestedData['damage_date'] = date('Y-m-d', strtotime($invoice->damage_date));
                $nestedData['damage_from'] = $invoice->damage_from;
                $nestedData['qty'] = $invoice->total_qty;
                $nestedData['total'] = $invoice->grand_total;
          
               
                $nestedData['options'] = '<div class="btn-group"><button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';
                // if($p_edit){
                    $nestedData['options'] .= ' <li><a href="'. route('damage.edit', $invoice->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';
                // }
                // if($p_delete){
                    $nestedData['options'] .= ' <li> <form action="'. route('damage.delete',$invoice->id).'" method="post"><input type="hidden" name="_token" value="'. csrf_token().'"><button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="bx bx-trash"></i>Delete</button></form></li>';
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
    function productDetailsbyId(Request $request){

        $data['product']=$product = Product::where('id',$request->id)->first();
        if($request->is_sale == 1){
            if($product->qty <= 0 && isset($request->old) && $request->old == 0 && isset($request->is_free) == false){
                return response()->json(['status'=>'no','msg'=>'Out of Stock']);
            }
                 //return $request->id;
            $data['row_no'] =$request->row_id;
            // if($request->is_sale == 1){
                $data['is_sale'] = $request->is_sale;
            //}
            // $data['expire_p']=$expire_p = PExpire::where('product_id',$request->id)->where('qty','>',0)->orderBy('expire_date','asc')->first();
            // // dd($expire_p);

            if(isset($request->old) && $request->old != 0){
                $data['qty'] =$request->qty;
                $data['item_id'] = $request->old;
                
                $data['product_item'] = DamageProductDetail::where('id',$request->old)->first();
               

                $data_view= view ('Inventory.damage.ajax-product-edit-data',$data)->render();
            }else{
                $data['qty'] =$request->qty;
                $data_view= view ('Inventory.damage.ajax-product-data',$data)->render();
            }

            if($product->unit_id > 0){
                $unit_name = $product->unit?->name;
                $unit_id = $product->unit_id;
            }else{
                $unit_name = "";
                $unit_id = 0;
            }
            return response()->json(['status'=>'yes','data_view'=>$data_view,'p_data'=>$product,'unit_name'=>$unit_name,'unit_id'=>$unit_id]);
        }else{
                 //return $request->id;
            $data['row_no'] =$request->row_id;
            // if($request->is_sale == 1){
                $data['is_sale'] = $request->is_sale;
            //}
            if(isset($request->old) && $request->old != 0){
                $data['qty'] =$request->qty;
                $data['item_id'] = $request->old;
                if($request->type == 1){
                    $data['product_item'] = ProductInvoice::where('id',$request->old)->first();
                }else if($request->type == 2){
                    $data['product_item'] = ProductInvoiceReturn::where('id',$request->old)->first();
                }else if($request->type == 3){
                    $data['product_item'] = ProductPurchase::where('id',$request->old)->first();
                }else if($request->type == 2){
                    $data['product_item'] = ProductPurchaseReturn::where('id',$request->old)->first();
                }else if($request->type == 2){
                    $data['product_item'] = ProductQuotation::where('id',$request->old)->first();
                }

                $data_view= view ('Inventory.damage.ajax-product-edit-data',$data)->render();
            }else{
                $data['qty'] =$request->qty;
                $data_view= view ('Inventory.damage.ajax-product-data',$data)->render();
            }
            if($product->unit_id > 0){
                $unit_name = $product->unit?->name;
                $unit_id = $product->unit_id;
            }else{
                $unit_name = "";
                $unit_id = 0;
            }
            return response()->json(['data_view'=>$data_view,'p_data'=>$product,'unit_name'=>$unit_name,'unit_id'=>$unit_id]);
        }

    }
    function create(){
        if(can_p('damage.create') == false){
            return redirect()->route('dashboard');
        }
       
        return view ('Inventory.damage.create');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        if(can_p('damage.create') == false){
            return redirect()->route('dashboard');
        }
        $validator_arr=[];
        $validator_e_msgg_arr=[];
        $validator_arr["product_id.0"] =["required"];
        $validator_e_msgg_arr["product_id.0"] = "Product is required";
        $validator_arr["unit.*"] =["required"];
        if(array_search('accounts',$this->pack_option()) != false){
            $this->inital_account();
        }
        $validator = Validator::make($request->all(),
            $validator_arr,
            $validator_e_msgg_arr,
        );

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->with("cus_errors",$validator->errors()->all())->withInput($request->all());
        }
        try{
            DB::beginTransaction();
           

            $damage_product = New DamageProduct;
            $dm_date = now();;
            if ($request->invoice_date == null) {
                $dm_date = now();
            } else {
                $dm_date = Carbon::parse($request->invoice_date)->format('Y-m-d');
            }
            $damage_product->damage_date = $dm_date;
            $damage_product->damage_from = $request->damage_from ?? '';
            $damage_product->branch_id = $request->branch ?? 0;
            $damage_product->total_qty = $request->total_qty ?? 0;
            $damage_product->grand_total = round($request->grand_price,2) ?? 0;
            $damage_product->note = $request->order_note;
            $damage_product->save();

            foreach($request->product_id as $k=>$product_id) {
                if(null != $product_id){
                  
                    $dp_detail = New DamageProductDetail;
                    $dp_detail->branch_id = $request->branch ?? 0;
                    $dp_detail->damage_id = $damage_product->id;
                    $dp_detail->damage_date = $dm_date;
                    $dp_detail->product_id = $product_id;
                    $dp_detail->qty = $request->qty[$k] ?? 0;
                    $dp_detail->per_cost = round($request->per_cost[$k] ?? 0,2);
                    $dp_detail->total = round($request->total[$k] ?? 0,2);
                    $dp_detail->unit_id =  $request->unit[$k];
                    $dp_detail->save();

                    $d_stock = DamageStock::where('product_id',$product_id)->where('branch_id',$request->branch)->first();
                    if($d_stock == null){
                        $d_stock = new DamageStock;
                        $d_stock->product_id = $product_id;
                        $d_stock->unit_id = $request->unit[$k] ?? 0;
                        $d_stock->qty = $request->qty[$k] ?? 0;
                        $d_stock->branch_id = $request->branch ?? 0;
                        $d_stock->pur_ost = round($request->per_cost[$k] ?? 0,2);
                        $d_stock->total_cost = round($request->total[$k] ?? 0,2);
                        $d_stock->save();
                    }else{
                        $d_stock->unit_id = $request->unit[$k] ?? 0;
                        $d_stock->qty += $request->qty[$k] ?? 0;
                        $d_stock->pur_ost = round($request->per_cost[$k] ?? 0,2);
                        $d_stock->total_cost += round($request->total[$k] ?? 0,2);
                        $d_stock->save();
                    }
                    

                    $stock = new Stock;
                    $stock->damage_id = $damage_product->id;
                    $stock->product_damage_id = $dp_detail->id;
                    $stock->product_id = $product_id;
                    $stock->unit_id = $request->unit[$k] ?? 0;
                    $stock->out_qty = $request->qty[$k]?? 0;
                    $stock->sale_price = $request->total[$k] ?? 0;
                    $stock->inventory_type = 'Damage';
                    $stock->save();

                    $product_stock =  Product::find($product_id);
                    if($product_stock){
                        $product_stock->qty= $product_stock->qty - ($request->qty[$k]?? 0);
                        $product_stock->save();
                    }
                }
            }

            if(array_search('accounts',$this->pack_option()) != false){
                $purchaseHead = AccountHead::where("code",'5000')->first();

                $sc_trans = New AccountTransaction;
                $sc_trans->amount = round($request->grand_price,2);
                $sc_trans->account_id = $purchaseHead->id;
                $sc_trans->type = "credit";
                $sc_trans->sub_type = "Damage Product";
                $sc_trans->reason = "Damage Product";
                $sc_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                $sc_trans->relation_id = $damage_product->id;
                $sc_trans->relation_with = "Damage Product";
                $sc_trans->save();

                $purchaseCCHead = AccountHead::where("code",'5055')->first();

                $cc_trans = New AccountTransaction;
                $cc_trans->amount = round($request->grand_price,2);
                $cc_trans->account_id = $purchaseCCHead->id;
                $cc_trans->type = "debit";
                $cc_trans->sub_type = "Damage Product";
                $cc_trans->reason = "Damage Product";
                $cc_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                $cc_trans->relation_id = $damage_product->id;
                $cc_trans->relation_with = "Damage Product";
                $cc_trans->trans_id = $sc_trans->id;
                $cc_trans->save();
                $sc_trans->trans_id = $cc_trans->id;
                $sc_trans->save();
            }

            DB::commit();

            $notification=array(
                'message'=>"Damage Insert Successfully",
                'alert-type'=>'success'
            );

            return redirect()->route('damage.index')->with($notification);
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
    public function edit(string $id)
    {
        if(can_p('damage.edit') == false){
            return redirect()->route('dashboard');
        }
        $data['invoice'] =$invoice = DamageProduct::find($id);
        //    dd($invoice->items);
        return view ('Inventory.damage.edit', $data);
    }
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        if(can_p('damage.edit') == false){
            return redirect()->route('dashboard');
        }
        $validator_arr=[];
        $validator_e_msgg_arr=[];
        $validator_arr["damage_from"] =["required"];
        $validator_arr["branch"] =["required"];
        // $validator_arr["product_id.0"] =["required"];
        // $validator_e_msgg_arr["product_id.0"] = "Product is required";
        // $validator_arr["unit.*"] =["required"];
        if(array_search('accounts',$this->pack_option()) != false){
            $this->inital_account();
        }
        $validator = Validator::make($request->all(),
            $validator_arr,
            $validator_e_msgg_arr,
        );

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->with("cus_errors",$validator->errors()->all())->withInput($request->all());
        }

        try{
            DB::beginTransaction();
            $damage_product = DamageProduct::find($id);
            $dm_date = now();;
            if ($request->invoice_date == null) {
                $dm_date = now();
            } else {
                $dm_date = Carbon::parse($request->invoice_date)->format('Y-m-d');
            }
            $damage_product->damage_date = $dm_date;
            $damage_product->damage_from = $request->damage_from ?? '';
            $damage_product->branch_id = $request->branch ?? 0;
            $damage_product->total_qty = $request->total_qty ?? 0;
            $damage_product->grand_total = round($request->grand_price,2) ?? 0;
            $damage_product->note = $request->order_note;
            $damage_product->save();

           
            if($request->old_product_id){
                foreach($request->old_product_id as $k=>$product_id) {

                    $dp_detail = DamageProductDetail::find($k);
                    $old_qty = $dp_detail->qty;
                    $d_stock = DamageStock::where('product_id',$product_id)->where('branch_id',$request->branch)->first();
                    if($d_stock){
                        $d_stock->qty -= $old_qty;
                        $d_stock->pur_ost = round($request->per_cost[$k] ?? 0,2);
                        $d_stock->total_cost -= $dp_detail->total;
                        $d_stock->save();
                    }
                    if($d_stock){
                        $d_stock->unit_id = $request->old_unit[$k] ?? 0;
                        $d_stock->qty += $request->old_qty[$k] ?? 0;
                        $d_stock->pur_ost = round($request->old_per_cost[$k] ?? 0,2);
                        $d_stock->total_cost += round($request->old_total[$k] ?? 0,2);
                        $d_stock->save();
                    }
                    $dp_detail->branch_id = $request->branch ?? 0;
                    $dp_detail->damage_id = $damage_product->id;
                    $dp_detail->damage_date = $dm_date;
                    $dp_detail->product_id = $product_id;
                    $dp_detail->qty = $request->old_qty[$k] ?? 0;
                    $dp_detail->per_cost = round($request->old_per_cost[$k] ?? 0,2);
                    $dp_detail->total = round($request->old_total[$k] ?? 0,2);
                    $dp_detail->unit_id =  $request->old_unit[$k];
                    $dp_detail->save();

                    
                    $stock =  Stock::where('product_damage_id',$k)->first();
                    if($stock == null){
                        $stock =  new Stock;
                        $stock->damage_id = $damage_product->id;
                        $stock->product_damage_id = $dp_detail->id;
                    }
                    $stock->product_id = $product_id;
                    $stock->unit_id = $request->old_unit[$k] ?? 0;
                    $stock->out_qty = $request->old_qty[$k]?? 0;
                    $stock->sale_price = $request->old_total[$k] ?? 0;
                    $stock->inventory_type = 'Damage';
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
                    $stock =  Stock::where('product_damage_id',$item)->first();
                    $stock->delete();
                    $product_invoice = DamageProductDetail::find($item);

                    $d_stock = DamageStock::where('product_id',$product_invoic->product_id)->where('branch_id',$product_invoice->branch_id)->first();
                    if($d_stock){
                        $d_stock->qty -= $product_invoice->qty;
                        $d_stock->total_cost -= $product_invoice->total;
                        $d_stock->save();
                    }

                    $product_stock =  Product::find($product_invoic->product_id);
                    if($product_stock){
                        $product_stock->qty= $product_stock->qty + $product_invoice->qty;
                        $product_stock->save();
                    }
                    $stocks = Stock::where('product_damage_id',$product_invoice->id)->first();
                    if($stocks){
                        $stocks->delete();
                    }

                    $product_invoice->delete();
                }
            }
            if($request->product_id){
                foreach($request->product_id as $k=>$product_id) {
                    if(null != $product_id){
                      
                        $dp_detail = New DamageProductDetail;
                        $dp_detail->branch_id = $request->branch ?? 0;
                        $dp_detail->damage_id = $damage_product->id;
                        $dp_detail->damage_date = $dm_date;
                        $dp_detail->product_id = $product_id;
                        $dp_detail->qty = $request->qty[$k] ?? 0;
                        $dp_detail->per_cost = round($request->per_cost[$k] ?? 0,2);
                        $dp_detail->total = round($request->total[$k] ?? 0,2);
                        $dp_detail->unit_id =  $request->unit[$k];
                        $dp_detail->save();
                        $d_stock = DamageStock::where('product_id',$product_id)->where('branch_id',$request->branch)->first();
                        if($d_stock == null){
                            $d_stock = new DamageStock;
                            $d_stock->product_id = $product_id;
                            $d_stock->unit_id = $request->unit[$k] ?? 0;
                            $d_stock->qty = $request->qty[$k] ?? 0;
                            $d_stock->branch_id = $request->branch ?? 0;
                            $d_stock->pur_ost = round($request->per_cost[$k] ?? 0,2);
                            $d_stock->total_cost = round($request->total[$k] ?? 0,2);
                            $d_stock->save();
                        }else{
                            $d_stock->unit_id = $request->unit[$k] ?? 0;
                            $d_stock->qty += $request->qty[$k] ?? 0;
                            $d_stock->pur_ost = round($request->per_cost[$k] ?? 0,2);
                            $d_stock->total_cost += round($request->total[$k] ?? 0,2);
                            $d_stock->save();
                        }
                        $stock = new Stock;
                        $stock->damage_id = $damage_product->id;
                        $stock->product_damage_id = $dp_detail->id;
                        $stock->product_id = $product_id;
                        $stock->unit_id = $request->unit[$k] ?? 0;
                        $stock->out_qty = $request->qty[$k]?? 0;
                        $stock->sale_price = $request->total[$k] ?? 0;
                        $stock->inventory_type = 'Damage';
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
                $purchaseHead = AccountHead::where("code",'5000')->first();

                $sc_trans = AccountTransaction::where("relation_id",$damage_product->id)->where('relation_with','Damage Product')->where('account_id',$purchaseHead->id)->first();
                if($sc_trans == null){
                    $sc_trans = new AccountTransaction;
                }
                $sc_trans->amount = round($request->grand_price,2);
                $sc_trans->account_id = $purchaseHead->id;
                $sc_trans->type = "credit";
                $sc_trans->sub_type = "Damage Product";
                $sc_trans->reason = "Damage Product";
                $sc_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                $sc_trans->relation_id = $damage_product->id;
                $sc_trans->relation_with = "Damage Product";
                $sc_trans->save();

                $purchaseCCHead = AccountHead::where("code",'5055')->first();

                $cc_trans = AccountTransaction::where("relation_id",$damage_product->id)->where('relation_with','Damage Product')->where('account_id',$purchaseCCHead->id)->first();
                if($cc_trans == null){
                    $cc_trans = new AccountTransaction;
                }
                $cc_trans->amount = round($request->grand_price,2);
                $cc_trans->account_id = $purchaseCCHead->id;
                $cc_trans->type = "debit";
                $cc_trans->sub_type = "Damage Product";
                $cc_trans->reason = "Damage Product";
                $cc_trans->date = $request->invoice_date == null ?  date('Y-m-d') :  Carbon::parse($request->invoice_date)->format('Y-m-d');
                $cc_trans->relation_id = $damage_product->id;
                $cc_trans->relation_with = "Damage Product";
                $cc_trans->trans_id = $sc_trans->id;
                $cc_trans->save();
                $sc_trans->trans_id = $cc_trans->id;
                $sc_trans->save();

            }
            DB::commit();
            $notification=array(
                'message'=>"Damage Product Updated Successfully ",
                'alert-type'=>'success'
            );
            return redirect()->route('damage.index')->with($notification);
        }catch (\Exception $e){
        //    dd($e->getMessage());
            DB::rollBack();
            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            // dd($request->all());
            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    public function destroy(string $id)
    {
        if(can_p('damage.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $invoice = DamageProduct::find($id);
   
    
            foreach ($invoice->items as $item) {
                $product_stock =  Product::find($item->product_id);

                $d_stock = DamageStock::where('product_id',$item->product_id)->where('branch_id',$item->branch_id)->first();
                if($d_stock){
                    $d_stock->qty -= $item->qty;
                    $d_stock->total_cost -= $item->total;
                    $d_stock->save();
                }
                $stocks = Stock::where('product_damage_id',$item->id)->first();
                if($stocks){
                    $stocks->delete();
                }
                if($product_stock){
                    $product_stock->qty= $product_stock->qty + $item->qty;
                    $product_stock->save();
                }
                $item->delete();
                
            }
          
            $account_payments= AccountTransaction::where('relation_id', $id)->where('relation_with','Damage Product')->get();
            foreach ($account_payments as $item) {
                $item->delete();
            }
            
            DB::commit();
            $notification=array(
                'message'=>"Damage Product Delete Successfully",
                'alert-type'=>'success'
            );
            return redirect()->route('damage.index')->with($notification);
        }catch(\Exception $e){
            dd($e->getMessage());
            DB::rollBack();
             $notification=array(
                'message'=>"Can not delete this!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }

    }
}
