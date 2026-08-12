<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Invoice;
use App\Models\Inventory\ProductInvoice;
use App\Models\Inventory\ProductInvoiceReturn;
use App\Models\Inventory\ProductPurchase;
use App\Models\Inventory\ProductPurchaseReturn;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\Stock;
use App\Models\PosSaleDetails;
use App\Models\DamageProductDetail;

use App\Models\DamageStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryReport extends Controller
{


    function damageProduct(Request $request){
        if(can_p('report.damage_product_report') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
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
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;

        $reports = DamageProductDetail::leftjoin("damage_products","damage_products.id","damage_product_details.damage_id")
        ->leftjoin("products","products.id","damage_product_details.product_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->whereBetween('damage_products.damage_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
       
       
      
        if(!empty($request->category)){
            $reports = $reports->where('products.category_id', $request->category);
        }
        if(!empty($request->product)){
            $reports = $reports->where('damage_product_details.product_id', $request->product);
        }
        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $reports =  $reports->select('product_name','product_code','damage_product_details.qty','damage_product_details.total','damage_products.damage_date','damage_products.damage_from','categories.name as cat_name');
        $data['per_page']=$per_page;
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.damage-product-report', $data);
    }
    function damageProductStock(Request $request){
        if(can_p('report.damage_product_stock') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");

        $reports = DamageStock::leftjoin("products","products.id","damage_stocks.product_id")
        ->leftjoin("categories","categories.id","products.category_id");
        // ->whereBetween('damage_products.damage_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
      
        if(!empty($request->category)){
            $reports = $reports->where('products.category_id', $request->category);
        }
        if(!empty($request->product)){
            $reports = $reports->where('damage_stocks.product_id', $request->product);
        }
        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $reports =  $reports->select('product_name','product_code','damage_stocks.*','categories.name as cat_name');
        $data['per_page']=$per_page;
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.damage-product-stock', $data);
    }
    function purchase(Request $request){
        if(can_p('report.purchase') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
        $reports = ProductPurchase::leftjoin("purchases","purchases.id","product_purchases.purchase_id")
            ->leftjoin("products","products.id","product_purchases.product_id")
            ->leftjoin("vendors","vendors.id","purchases.supplier_id")
            ->leftjoin("categories","categories.id","products.category_id")
            ->where('products.product_name','!=','initial')
            ->select('reference_no','purchase_date','product_name','product_code','product_purchases.qty','per_cost','product_purchases.discount as discount','total','vendors.name as vendor_name','categories.name as cat_name','purchases.status as status','payment_status');
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
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports=  $reports->where('purchases.business_id',auth()->user()->business->id);
            $reports = $reports->whereBetween('purchase_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        if(!empty($request->vendor)){
            $reports = $reports->where('purchases.supplier_id', $request->vendor);
        }
        if(!empty($request->category)){
            $reports = $reports->where('products.category_id', $request->category);
        }
        if(!empty($request->product)){
            $reports = $reports->where('product_purchases.product_id', $request->product);
        }
        // dd($reports->get());
        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.product-purchase', $data);

    }
    function purchaseReturn(Request $request){
        if(can_p('report.purchase_return') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
       $reports = ProductPurchaseReturn::leftjoin("purchase_returns","purchase_returns.id","product_purchase_returns.purchase_return_id")
        ->leftjoin("products","products.id","product_purchase_returns.product_id")
        ->leftjoin("vendors","vendors.id","purchase_returns.supplier_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->where('products.product_name','!=','initial')
         ->select('reference_no','return_date','product_name','product_code','product_purchase_returns.qty','per_cost','product_purchase_returns.discount as discount','total','vendors.name as vendor_name','categories.name as cat_name','purchase_returns.status as status','payment_status');
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
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports=  $reports->where('purchase_returns.business_id',auth()->user()->business->id);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('return_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        if(!empty($request->vendor)){
            $reports = $reports->where('purchase_returns.supplier_id', $request->vendor);
        }
        if(!empty($request->category)){
            $reports = $reports->where('products.category_id', $request->category);
        }
        if(!empty($request->product)){
            $reports = $reports->where('product_purchase_returns.product_id', $request->product);
        }
        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.product-purchase-return', $data);

    }
    function invoiceReturn(Request $request){
        if(can_p('report.sales_return') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
       $reports = ProductInvoiceReturn::leftjoin("invoice_returns","invoice_returns.id","product_invoice_returns.invoice_return_id")
        ->leftjoin("products","products.id","product_invoice_returns.product_id")
        ->leftjoin("customers","customers.id","invoice_returns.customer_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->where('products.product_name','!=','initial')
         ->select('reference_no','return_date','product_name','product_code','product_invoice_returns.qty','per_cost','product_invoice_returns.discount as discount','total','customers.name as customer_name','categories.name as cat_name','invoice_returns.status as status','payment_status');
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
        $reports=  $reports->where('invoice_returns.business_id',auth()->user()->business->id);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('return_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->customer)){
            $reports = $reports->where('invoice_returns.customer_id', $request->customer);
        }
        if(!empty($request->dsr)){
            $reports = $reports->where('invoice_returns.dsr_id', $request->dsr);
        }
        if(!empty($request->asr)){
            $reports = $reports->where('invoice_returns.asr_id', $request->asr);
        }
        if(!empty($request->driver)){
            $reports = $reports->where('invoice_returns.sld_id', $request->driver);
        }
        if(!empty($request->category)){
            $reports = $reports->where('products.category_id', $request->category);
        }
        if(!empty($request->product)){
            $reports = $reports->where('product_invoice_returns.product_id', $request->product);
        }
        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;

        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.product-invoice-return', $data);

    }
    function invoice(Request $request){
        if(can_p('report.invoice') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
       $reports = ProductInvoice::leftjoin("invoices","invoices.id","product_invoices.invoice_id")
        ->leftjoin("products","products.id","product_invoices.product_id")
        ->leftjoin("customers","customers.id","invoices.customer_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->where('products.product_name','!=','initial')
         ->select('reference_no','invoice_date','product_name','product_code','product_invoices.qty','per_cost','product_invoices.discount as discount','total','customers.name as customer_name','categories.name as cat_name','invoices.status as status','payment_status');
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
        $reports=  $reports->where('invoices.business_id',auth()->user()->business->id);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->customer)){
            $reports = $reports->where('invoices.customer_id', $request->customer);
        }
        if(!empty($request->dsr)){
            $reports = $reports->where('invoices.dsr_id', $request->dsr);
        }
        if(!empty($request->asr)){
            $reports = $reports->where('invoices.asr_id', $request->asr);
        }
        if(!empty($request->driver)){
            $reports = $reports->where('invoices.sld_id', $request->driver);
        }
        if(!empty($request->category)){
            $reports = $reports->where('products.category_id', $request->category);
        }
        if(!empty($request->product)){
            $reports = $reports->where('product_invoices.product_id', $request->product);
        }
        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        //dd($reports->paginate($per_page));
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.product-invoice', $data);

    }
    function productWiseProfit(Request $request){

        if(can_p('report.product_wise_profit') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
       $reports = ProductInvoice::leftjoin("invoices","invoices.id","product_invoices.invoice_id")
        ->leftjoin("products","products.id","product_invoices.product_id")
         ->select('product_name','product_invoices.purchase_price','product_invoices.qty','per_cost','total');
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

        $reports=  $reports->where('invoices.business_id',auth()->user()->business->id)->where('invoices.is_pos',0)->where('products.product_name','!=','initial');
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;

        if(!empty($request->category)){
            $reports = $reports->where('products.category_id', $request->category);
        }
        if(!empty($request->product)){
            $reports = $reports->where('product_invoices.product_id', $request->product);
        }
        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        //dd("ddd");
        $data['per_page']=$per_page;
        //dd($per_page);
        // dd($reports->paginate($per_page));
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.product-wise-profit', $data);

    }
    function posSale(Request $request){
        //dd(can_p('report.pos_sale'));
        if(can_p('report.pos_sale') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
        $reports = PosSaleDetails::leftjoin("pos_sales","pos_sales.id","pos_sale_details.sale_id")
        ->leftjoin("products","products.id","pos_sale_details.product_id")
        ->leftjoin("customers","customers.id","pos_sales.customer_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->where('products.product_name','!=','initial')
         ->select('reference_no','sale_date','product_name','product_code','pos_sale_details.qty','per_cost','tax','pos_sale_details.discount as discount','total','customers.name as customer_name','categories.name as cat_name','pos_sales.status as status','payment_status');
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
        $reports=  $reports->where('pos_sales.business_id',auth()->user()->business->id);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('sale_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->customer)){
            $reports = $reports->where('pos_sales.customer_id', $request->customer);
        }

        if(!empty($request->category)){
            $reports = $reports->where('products.category_id', $request->category);
        }
        if(!empty($request->product)){
            $reports = $reports->where('pos_sale_details.product_id', $request->product);
        }
        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        //dd($reports->paginate($per_page));
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.pos-sale', $data);

    }
    function stock(Request $request){
        if(can_p('report.stock') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
        $reports =Stock::leftjoin("product_variations","product_variations.product_id","stocks.product_id")
        ->leftjoin("products","stocks.product_id","products.id")
        // ->leftjoin("product_variations","product_variations.id","products.id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->leftjoin("brands","brands.id","products.brand_id")
        ->select(
                DB::raw('stocks.product_id,products.business_id,products.sale_price as s_price, products.product_name as product_name, products.product_code as product_code, products.category_id,products.brand_id,categories.name as category_name,brands.name as brand_name,IFNULL(product_variations.configurable_product_id,stocks.product_id)  as a_product_id'),
                DB::raw('sum(IFNULL(stocks.in_qty,0)) as inQty'),
                DB::raw('sum(IFNULL(stocks.out_qty,0)) as outQty'),
                DB::raw('sum(IFNULL(stocks.purchase_price,0)) as purchase_total'),
                DB::raw('sum(IFNULL(stocks.sale_price, 0)) as sale_total')
            )
        // ->select("product_invoices.qty as sale_qty","product_purchases.qty as purchase_qty");
        //  ->select('product_name','categories.name as cat_name',DB::raw('sum("product_invoices.qty") as sale_qty,sum("product_invoices.total") as sale_amount,sum("product_purchases.qty") as purchase_qty,sum("product_purchases.total") as purchase_amount'))

        ->groupBy("a_product_id");
        // if(!empty($request->from_date) && !empty($request->to_date)){
        //     $reports = $reports->whereBetween('purchase_date', [$request->from_date, $request->to_date]);
        // }
        // if(!empty($request->vendor)){
        //     $reports = $reports->where('invoices.customer_id', $request->vendor);
        // }

        // $reports=  $reports->where('products.business_id',auth()->user()->business->id);
        //dd($reports->get());
        // $reports = $reports->where('products.is_variant',0);
        if(!empty($request->category)){
            $reports = $reports->where('products.category_id', $request->category);
        }
        if(!empty($request->product)){
            $reports = $reports->where('stocks.product_id', $request->product);
        }
        if(!empty($request->brand)){
            $reports = $reports->where('products.brand_id', $request->brand);
        }
        if(!empty($request->manufacture)){
            $reports = $reports->where('products.manufacture_id', $request->manufacture);
        }
       // dd($reports->get());
        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.stock', $data);

    }
    function vendorDue(Request $request){
        if(can_p('report.vendor_due') == false){
            return redirect()->route('dashboard');
        }
         DB::statement("SET SQL_MODE=''");
        $reports = Purchase::leftjoin("vendors","vendors.id","purchases.supplier_id")
        ->leftJoin("purchase_returns","purchase_returns.purchase_id","purchases.id")
        ->select('purchases.reference_no','purchase_date','purchases.grand_total as total_amount','purchases.paid_amount as paid_amount','purchases.due_amount as due_amount','vendors.name as vendor_name',DB::raw('sum(IFNULL(purchase_returns.grand_total,0)) as total_return'))
        ->where('purchases.due_amount','>',0)
        ->where('purchases.is_ini_p',0)
        ->groupBy('purchases.id');
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
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('purchase_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        if(!empty($request->vendor)){
            $reports = $reports->where('purchases.supplier_id', $request->vendor);
        }


        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.vendor-due-report', $data);
    }
    function customerDue(Request $request){
        if(can_p('report.customer_due') == false){
            return redirect()->route('dashboard');
        }
         DB::statement("SET SQL_MODE=''");
        $reports = Invoice::leftjoin("customers","customers.id","invoices.customer_id")
        ->leftJoin("invoice_returns","invoice_returns.invoice_id","invoices.id")
        ->select('invoices.reference_no','invoice_date','invoices.grand_total as total_amount','invoices.paid_amount as paid_amount','invoices.due_amount as due_amount','customers.name as customer_name',DB::raw('sum(IFNULL(invoice_returns.grand_total,0)) as total_return'))
        ->where('invoices.due_amount','>',0)
        ->where('invoices.is_ini_p',0)
        ->groupBy('invoices.id');
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

        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        //if(!empty($request->from_date) && !empty($request->to_date)){
           $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        if(!empty($request->dsr)){
            $reports = $reports->where('invoices.dsr_id', $request->dsr);
        }
        if(!empty($request->customer)){
            $reports = $reports->where('invoices.customer_id', $request->customer);
        }
        //dd($reports->get());
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.customer-due-report', $data);
    }
    function saleDiscount(Request $request){
        if(can_p('report.sale_discount') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
       $reports = Invoice::leftjoin("customers","customers.id","invoices.customer_id")
       ->select('customers.name as customer_name','invoices.*');
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
        $reports=  $reports->where('invoices.business_id',auth()->user()->business->id)->where('invoices.is_ini_p',0);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->customer)){
            $reports = $reports->where('invoices.customer_id', $request->customer);
        }
        if(!empty($request->dsr)){
            $reports = $reports->where('invoices.dsr_id', $request->dsr);
        }
        if(!empty($request->asr)){
            $reports = $reports->where('invoices.asr_id', $request->asr);
        }
        if(!empty($request->driver)){
            $reports = $reports->where('invoices.sld_id', $request->driver);
        }

        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        //dd($reports->paginate($per_page));
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.sales-discount', $data);

    }
    function saleWiseProfit(Request $request){
        if(can_p('report.sale_wise_profit') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
       $reports = Invoice::leftjoin("customers","customers.id","invoices.customer_id")
       ->select('customers.name as customer_name','invoices.*');
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
        $reports=  $reports->where('invoices.business_id',auth()->user()->business->id)->where('invoices.is_ini_p',0);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->customer)){
            $reports = $reports->where('invoices.customer_id', $request->customer);
        }
        if(!empty($request->dsr)){
            $reports = $reports->where('invoices.dsr_id', $request->dsr);
        }
        if(!empty($request->asr)){
            $reports = $reports->where('invoices.asr_id', $request->asr);
        }
        if(!empty($request->driver)){
            $reports = $reports->where('invoices.sld_id', $request->driver);
        }

        // ->groupBy("product_purchases.product_id")
        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        //dd($reports->paginate($per_page));
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Inventory.sale-wise-profit', $data);

    }
}
