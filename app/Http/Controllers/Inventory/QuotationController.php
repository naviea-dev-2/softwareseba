<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Quotation;
use Illuminate\Http\Request;
use App\Models\Inventory\Customer;
use App\Models\Inventory\ProductQuotation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(can_p('quotation.index') == false){
            return redirect()->route('dashboard');
        }
         $data['quotations'] = Quotation::orderBy('id','DESC')->get();
        return view ('Inventory.quotation.manage', $data );
    }
    function ajaxQuotation(Request $request){
        $columns = array(
           0 => 'quotations.id',
           1 => 'quotations.quotation_date',
           2 => 'quotations.reference_no',
           3 => 'customers.name',
           4 => 'quotations.grand_total',
           5 => 'quotations.paid_amount',
           7 => 'quotations.status',
           8 => 'quotations.payment_status',
           9 => 'options',
        );
        $totalData = Quotation::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $quotations = Quotation::leftjoin('customers','customers.id','quotations.customer_id');
        if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
            $quotations->where('branch_id',auth()->user()->branch_id);
        }
        if(!empty($search))
        {
            $quotations = $quotations->where("invoice_date","LIKE","%{$search}%");

        }
        $totalFiltered = $quotations->count();
        $quotations = $quotations->select('quotations.*','customers.name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($quotations))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('quotation.edit');
            $p_delete = can_p('quotation.delete');
            $p_view = can_p('quotation.view');
            foreach($quotations as $quotation)
            {
                $nestedData['id'] = $i++;

                $nestedData['date'] = date('Y-m-d', strtotime($quotation->quotation_date));
                $nestedData['reference'] = $quotation->reference_no;
                $nestedData['cus_name'] = $quotation->name;
                $nestedData['total'] = auth()->user()->currency_symbol . number_format($quotation->grand_total, 2);
                $status = '';
                if($quotation->status == 1){
                    $status = '<div class="badge bg-success">Recieved</div>';
                }else if($quotation->status == 2){
                    $status = '<div class="badge bg-secondary">Partial</div>';
                }else if($quotation->status == 3){
                    $status = '<div class="badge bg-danger">Pending</div>';
                }else{
                    $status = '<div class="badge bg-success">Ordered</div>';
                }
                $nestedData['status'] =$status;

                $nestedData['options'] = '<div class="btn-group"><button type="button" class="btn btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';
                if($p_view){
                    $nestedData['options'] .= ' <li><button data-id="'.$quotation->id.'" type="button" class="btn btn-link view"><i class="bx bx-show"></i>View</button> </li>';
                }
                if($p_edit){
                    $nestedData['options'] .= ' <li><a href="'. route('quotation.edit', $quotation->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';
                }


                if($p_delete){
                    $nestedData['options'] .= ' <li> <form action="'. route('quotation.delete',$quotation->id).'" method="post"> <input type="hidden" name="_token" value="'. csrf_token().'"><button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="bx bx-trash"></i>Delete</button></form></li>';
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
        if(can_p('quotation.create') == false){
            return redirect()->route('dashboard');
        }
         return view ('Inventory.quotation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(can_p('quotation.create') == false){
            return redirect()->route('dashboard');
        }
         $validator_arr=[];
        $validator_e_msgg_arr=[];
        $is_valid_product = 0;


        $validator_arr["unit.*"] =["required"];
        $validator_arr["product_id.0"] =["required"];
        $validator_e_msgg_arr["product_id.0"] = "Product is required";

        // $validator_arr["email"] = ["required"];
        // $validator_e_msgg_arr["email"] = "Customer Email is required";
        $validator_arr["mobile"] = ["required"];
        $validator_e_msgg_arr["mobile"] = "Customer Mobile is required";
        $validator = Validator::make($request->all(),
            $validator_arr,
            $validator_e_msgg_arr
            // "color.*"  => "required|string|distinct|min:3",
        );

        if ($validator->fails()) {
            return back()->with("errors",$validator->errors()->all())->withInput($request->all());
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
            $quotation = New Quotation;
            $quotation->reference_no = 'pr-' . date("Ymd") . '-'. date("his");
            $quotation->branch_id = $request->branch;
            $quotation->customer_id= $customer->id;
            $quotation->item = $request->item;
            $quotation->total_qty = $request->total_qty;
            $quotation->total_discount =  $request->total_discount;
            $quotation->total_cost = $request->total_cost;
            $quotation->order_discount = $request->order_discount;
            $quotation->shipping_cost = $request->shipping_cost;
            $quotation->total_tax = $request->total_tax;
            $quotation->paid_amount = $request->grand_total;
            $quotation->grand_total = $request->grand_total;
            $quotation->status = $request->status;
            $quotation->payment_status = 1;
            $quotation->note = $request->order_note;
            if ($request->quotation_date == null) {
                $quotation->quotation_date = now();
            } else {
                $invdate = Carbon::parse($request->quotation_date)->format('Y-m-d');
                $quotation->quotation_date = $invdate;
            }
            $quotation->save();
            foreach($request->product_id as $k=>$product_id) {
                if(null != $product_id){
                    $product_quotation = New ProductQuotation;
                    $product_quotation->quotation_id = $quotation->id;
                    $product_quotation->product_id = $product_id;
                    // $product_quotation->color_id = $request->color[$k] ?? 0;
                    // $product_quotation->size_id = $request->size[$k] ?? 0;
                    $product_quotation->unit_id = $request->unit[$k];
                    $product_quotation->qty = $request->qty[$k] ?? 0;
                    $product_quotation->tax = $request->tax[$k] ?? 0;
                    $product_quotation->per_cost = $request->per_cost[$k] ?? 0;
                    $product_quotation->total = $request->total[$k] ?? 0;
                    $product_quotation->discount = $request->discount[$k] ?? 0;
                    $product_quotation->save();
                }
            }
            DB::commit();
           // return redirect()->route('quotation.index')->with('success','Quotation Successfully Completed');
            $notification=array(
                'message'=>"Quotation Successfully Completed",
                'alert-type'=>'success'
            );
            return redirect()->route('quotation.print',$quotation->id)->with($notification);
            return redirect()->route('quotation.index')->with($notification);
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
    function printQuotation($id){
        $data['quotation']=Quotation::find($id);
        return view('Inventory.quotation.print_quotation',$data);
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
        if(can_p('quotation.edit') == false){
            return redirect()->route('dashboard');
        }
        $data['quotation'] =$quotation = Quotation::find($id);
        // dd($purchase->items);
        return view ('Inventory.quotation.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(can_p('quotation.edit') == false){
            return redirect()->route('dashboard');
        }
        //dd($request->all());
        $validator_arr=[];
        $validator_e_msgg_arr=[];
        $is_valid_product = 0;


        if($request->product_id || $request->old_product_id){

        }else{
            $validator_arr["product.0"] =["required"];
            $validator_e_msgg_arr["product.0"] = "Product is required";
        }
        if($request->old_product_id){
            $validator_arr["old_unit.*"] =["required"];
        }
        if($request->product_id){
            $validator_arr["unit.*"] =["required"];
        }
        $validator_arr["email"] = ["required"];
        $validator_e_msgg_arr["email"] = "Customer Email is required";
        $validator_arr["mobile"] = ["required"];
        $validator_e_msgg_arr["mobile"] = "Customer Mobile is required";
        $validator = Validator::make($request->all(),
            $validator_arr,
            $validator_e_msgg_arr
            // "color.*"  => "required|string|distinct|min:3",
        );

        if ($validator->fails()) {
            // dd($validator->errors());
            return back()->with("errors",$validator->errors()->all())->withInput($request->all());
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
            $quotation = Quotation::find($id);
            // $purchase->reference_no = 'pr-' . date("Ymd") . '-'. date("his");
            // $purchase->branch_id = $request->branch;
            $quotation->customer_id= $customer->id;
            $quotation->item = $request->item;
            $quotation->total_qty = $request->total_qty;
            $quotation->total_discount =  $request->total_discount;
            $quotation->total_cost = $request->total_cost;
            $quotation->order_discount = $request->order_discount;
            $quotation->shipping_cost = $request->shipping_cost;

            $quotation->paid_amount = $request->grand_total;
            $quotation->grand_total = $request->grand_total;
            $quotation->status = $request->status;
            $quotation->payment_status = 1;
            $quotation->total_tax = $request->total_tax;
            $quotation->note = $request->order_note;
            if ($request->quotation_date == null) {
                $quotation->quotation_date = now();
            } else {
                $invdate = Carbon::parse($request->quotation_date)->format('Y-m-d');
                $quotation->quotation_date = $invdate;
            }
            $quotation->save();
            //dd($request->color);
            if($request->old_product_id){
                // dd($request->old_product_id);
                foreach($request->old_product_id as $k=>$product_id) {

                    $product_quotation= ProductQuotation::find($k);
                    $product_quotation->quotation_id = $quotation->id;
                    $product_quotation->product_id = $product_id;
                    // $product_quotation->color_id = $request->old_color[$k] ?? 0;
                    // $product_quotation->size_id = $request->old_size[$k] ?? 0;
                    $product_quotation->unit_id = $request->old_unit[$k];
                    $product_quotation->qty = $request->old_qty[$k] ?? 0;
                    $product_quotation->per_cost = $request->old_per_cost[$k] ?? 0;
                    $product_quotation->tax = $request->tax[$k] ?? 0;
                    $product_quotation->total = $request->old_total[$k] ?? 0;
                    $product_quotation->discount = $request->old_discount[$k] ?? 0;
                    $product_quotation->save();

                }
            }
            if($request->delete_item){
                foreach($request->delete_item as $k=>$item) {
                    $product_invoice = ProductQuotation::find($item);
                    $product_invoice->delete();
                }
            }
            if($request->product_id){
                foreach($request->product_id as $k=>$product_id) {
                    if(null != $product_id){
                        $product_invoice = New ProductQuotation;
                        $product_invoice->quotation_id = $quotation->id;
                        $product_invoice->product_id = $product_id;
                        $product_invoice->color_id = $request->color[$k] ?? 0;
                        $product_invoice->size_id = $request->size[$k] ?? 0;
                        $product_invoice->unit_id = $request->unit[$k];
                        $product_invoice->tax = $request->tax[$k] ?? 0;
                        $product_invoice->qty = $request->qty[$k] ?? 0;
                        $product_invoice->per_cost = $request->per_cost[$k] ?? 0;
                        $product_invoice->total = $request->total[$k] ?? 0;
                        $product_invoice->discount = $request->discount[$k] ?? 0;
                        $product_invoice->save();
                    }
                }
            }
            DB::commit();

            $notification=array(
                'message'=>"Quotation Successfully Updated",
                'alert-type'=>'success'
            );

            return redirect()->route('quotation.index')->with($notification);
         }catch (\Exception $e){
            DB::rollBack();

            $notification=array(
                'message'=>"Something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    function quotationDetail($id){
        if(can_p('quotation.view') == false){
            return redirect()->route('dashboard');
        }
         $data['quotation'] = $quotation = Quotation::find($id);
        return view('Inventory.quotation.ajax-view-data', $data);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(can_p('quotation.delete') == false){
            return redirect()->route('dashboard');
        }
        $quotation = Quotation::find($id);
        // dd($purchase);
        foreach ($quotation->items as $item) {
            $item->delete();
        }
        $quotation->delete();
        return redirect()->route('quotation.index')->with('success','Quotation Successfully Delete');
    }
}
