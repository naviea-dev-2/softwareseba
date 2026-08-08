<?php

namespace App\Http\Controllers\Report;

use App\Exports\AttendanceExport;
use App\Exports\BalanceSheetExport;
use App\Exports\BonusExport;
use App\Exports\CustomerDueExport;
use App\Exports\InvoiceReportExport;
use App\Exports\InvoiceReturnReportExport;
use App\Exports\LeaveExport;
use App\Exports\LoanExport;
use App\Exports\PosSaleReportExport;
use App\Exports\ProfLossExport;
use App\Exports\PurchaseReportExport;
use App\Exports\PurchaseReturnReportExport;
use App\Exports\SalaryExport;
use App\Exports\StockReportExport;
use App\Exports\LedgerSummaryExport;
use App\Exports\TransactionExport;
use App\Exports\TemplateProductExport;
use App\Exports\SaleWiseProfitReportExport;
use App\Exports\VendorDueExport;
use App\Exports\SaleDiscountReportExport;
use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Hr\Attendance;
use App\Models\Hr\BonusPay;
use App\Models\Hr\EmpLoan;
use App\Models\Hr\LeaveApplication;
use App\Models\Hr\SalarySheet;
use App\Models\Inventory\Invoice;
use Illuminate\Http\Request;
use App\Models\Inventory\ProductInvoice;
use App\Models\Inventory\ProductInvoiceReturn;
use App\Models\Inventory\ProductPurchase;
use App\Models\Inventory\ProductPurchaseReturn;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\Stock;
use App\Models\PosSaleDetails;
use Carbon\Carbon;
use App\Models\Hr\Employee;
use App\Models\Inventory\Customer;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DamageProductDetail;
use App\Models\DamageStock;
use App\Exports\DamageProductReportExport;
use App\Exports\DamageProductStockExport;
use App\Exports\ExpenseReportExport;
use App\Models\Account\Expense;
// use Maatwebsite\Excel\Excel;
class ReportExportController extends Controller
{
     function damageProductReport(Request $request){
       
        DB::statement("SET SQL_MODE=''");
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;

        $reports = DamageProductDetail::leftjoin("damage_products","damage_products.id","damage_product_details.damage_id")
        ->leftjoin("products","products.id","damage_product_details.product_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->whereBetween('damage_products.damage_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
       
        if(!empty($request->p_category)){
            $reports = $reports->where('products.category_id', $request->p_category);
        }
        if(!empty($request->p_product)){
            $reports = $reports->where('damage_product_details.product_id', $request->p_product);
        }
        
        $reports =  $reports->select('product_name','product_code','damage_product_details.qty','damage_product_details.total','damage_products.damage_date','damage_products.damage_from','categories.name as cat_name');
   
        $reports= $reports->get();
        $name = 'Damage Product Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new DamageProductReportExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function damageProductStock(Request $request){
       
        DB::statement("SET SQL_MODE=''");
        $reports = DamageStock::leftjoin("products","products.id","damage_stocks.product_id")
        ->leftjoin("categories","categories.id","products.category_id");
        
        if(!empty($request->p_category)){
            $reports = $reports->where('products.category_id', $request->p_category);
        }
        if(!empty($request->p_product)){
            $reports = $reports->where('damage_stocks.product_id', $request->p_product);
        }
     
        $reports =  $reports->select('product_name','product_code','damage_stocks.*','categories.name as cat_name');
        // dd($reports->get());
        $reports= $reports->get();
        $name = 'Damage Product Stock_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new DamageProductStockExport($reports, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
        
    }
    function purchaseReport(Request $request){

        DB::statement("SET SQL_MODE=''");
        $reports = ProductPurchase::leftjoin("purchases","purchases.id","product_purchases.purchase_id")
            ->leftjoin("products","products.id","product_purchases.product_id")
            ->leftjoin("vendors","vendors.id","purchases.supplier_id")
            ->leftjoin("categories","categories.id","products.category_id")
            ->where('products.product_name','!=','initial')
            ->select('reference_no','purchase_date','product_name','product_code','product_purchases.qty','per_cost','product_purchases.discount as discount','total','vendors.name as vendor_name','categories.name as cat_name','purchases.status as status','payment_status');
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports=  $reports->where('purchases.business_id',auth()->user()->business->id);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('purchase_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        if(!empty($request->p_vendor)){
            $reports = $reports->where('purchases.supplier_id', $request->p_vendor);
        }
        if(!empty($request->p_category)){
            $reports = $reports->where('products.category_id', $request->p_category);
        }
        if(!empty($request->p_product)){
            $reports = $reports->where('product_purchases.product_id', $request->p_product);
        }
        // dd($reports->get());
        // ->groupBy("product_purchases.product_id")
        $reports= $reports->get();
        $name = 'Purchase Product Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new PurchaseReportExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function purchaseReutnReport(Request $request){
       DB::statement("SET SQL_MODE=''");
       $reports = ProductPurchaseReturn::leftjoin("purchase_returns","purchase_returns.id","product_purchase_returns.purchase_return_id")
        ->leftjoin("products","products.id","product_purchase_returns.product_id")
        ->leftjoin("vendors","vendors.id","purchase_returns.supplier_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->where('products.product_name','!=','initial')
         ->select('reference_no','return_date','product_name','product_code','product_purchase_returns.qty','per_cost','product_purchase_returns.discount as discount','total','vendors.name as vendor_name','categories.name as cat_name','purchase_returns.status as status','payment_status');
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports=  $reports->where('purchase_returns.business_id',auth()->user()->business->id);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('return_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        if(!empty($request->p_vendor)){
            $reports = $reports->where('purchase_returns.supplier_id', $request->p_vendor);
        }
        if(!empty($request->p_category)){
            $reports = $reports->where('products.category_id', $request->p_category);
        }
        if(!empty($request->p_product)){
            $reports = $reports->where('product_purchase_returns.product_id', $request->p_product);
        }
        // dd($reports->get());
        // ->groupBy("product_purchases.product_id")
        $reports= $reports->get();
        $name = 'Purchase Return Product Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new PurchaseReturnReportExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function saleDiscount(Request $request){
        DB::statement("SET SQL_MODE=''");
        $reports = Invoice::leftjoin("customers","customers.id","invoices.customer_id")
        ->select('customers.name as customer_name','invoices.*');
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $reports=  $reports->where('invoices.business_id',auth()->user()->business->id)->where('invoices.is_ini_p',0);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->p_customer)){
            $reports = $reports->where('invoices.customer_id', $request->p_customer);
        }
        if(!empty($request->p_dsr)){
            $reports = $reports->where('invoices.dsr_id', $request->p_dsr);
        }
        if(!empty($request->p_asr)){
            $reports = $reports->where('invoices.asr_id', $request->p_asr);
        }
        if(!empty($request->p_driver)){
            $reports = $reports->where('invoices.sld_id', $request->p_driver);
        }

        $reports= $reports->get();
        $name = 'Sale Discount Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new SaleDiscountReportExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function saleWiseProfit(Request $request){
        DB::statement("SET SQL_MODE=''");
        $reports = Invoice::leftjoin("customers","customers.id","invoices.customer_id")
        ->select('customers.name as customer_name','invoices.*');
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $reports=  $reports->where('invoices.business_id',auth()->user()->business->id)->where('invoices.is_ini_p',0);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->p_customer)){
            $reports = $reports->where('invoices.customer_id', $request->p_customer);
        }
        if(!empty($request->p_dsr)){
            $reports = $reports->where('invoices.dsr_id', $request->p_dsr);
        }
        if(!empty($request->p_asr)){
            $reports = $reports->where('invoices.asr_id', $request->p_asr);
        }
        if(!empty($request->p_driver)){
            $reports = $reports->where('invoices.sld_id', $request->p_driver);
        }

        $reports= $reports->get();
        $name = 'Sale Wise Profit Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new SaleWiseProfitReportExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function invoiceReport(Request $request){
        DB::statement("SET SQL_MODE=''");
       $reports = ProductInvoice::leftjoin("invoices","invoices.id","product_invoices.invoice_id")
        ->leftjoin("products","products.id","product_invoices.product_id")
        ->leftjoin("customers","customers.id","invoices.customer_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->where('products.product_name','!=','initial')
         ->select('reference_no','invoice_date','product_name','product_code','product_invoices.qty','per_cost','product_invoices.discount as discount','total','customers.name as customer_name','categories.name as cat_name','invoices.status as status','payment_status');
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $reports=  $reports->where('invoices.business_id',auth()->user()->business->id);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->p_customer)){
            $reports = $reports->where('invoices.customer_id', $request->p_customer);
        }
        if(!empty($request->p_dsr)){
            $reports = $reports->where('invoices.dsr_id', $request->p_dsr);
        }
        if(!empty($request->p_asr)){
            $reports = $reports->where('invoices.asr_id', $request->p_asr);
        }
        if(!empty($request->p_driver)){
            $reports = $reports->where('invoices.sld_id', $request->p_driver);
        }
        if(!empty($request->p_category)){
            $reports = $reports->where('products.category_id', $request->p_category);
        }
        if(!empty($request->p_product)){
            $reports = $reports->where('product_invoices.product_id', $request->p_product);
        }
        // dd($reports->get());
        // ->groupBy("product_purchases.product_id")
        $reports= $reports->get();
        $name = 'Invoice Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new InvoiceReportExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function productWiseProfit(Request $request){
        DB::statement("SET SQL_MODE=''");
        $reports = ProductInvoice::leftjoin("invoices","invoices.id","product_invoices.invoice_id")
        ->leftjoin("products","products.id","product_invoices.product_id")
         ->select('product_name','product_invoices.purchase_price','product_invoices.qty','per_cost','total');

         if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $reports=  $reports->where('invoices.business_id',auth()->user()->business->id)->where('invoices.is_pos',0)->where('products.product_name','!=','initial');
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;


        if(!empty($request->p_category)){
            $reports = $reports->where('products.category_id', $request->p_category);
        }
        if(!empty($request->p_product)){
            $reports = $reports->where('product_invoices.product_id', $request->p_product);
        }
        // dd($reports->get());
        // ->groupBy("product_purchases.product_id")
        $reports= $reports->get();
        $name = 'Product Wise Profit Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new InvoiceReportExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function posSaleReport(Request $request){
        DB::statement("SET SQL_MODE=''");
        $reports = PosSaleDetails::leftjoin("pos_sales","pos_sales.id","pos_sale_details.sale_id")
        ->leftjoin("products","products.id","pos_sale_details.product_id")
        ->leftjoin("customers","customers.id","pos_sales.customer_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->where('products.product_name','!=','initial')
         ->select('reference_no','sale_date','product_name','product_code','pos_sale_details.qty','per_cost','tax','pos_sale_details.discount as discount','total','customers.name as customer_name','categories.name as cat_name','pos_sales.status as status','payment_status');
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $reports=  $reports->where('pos_sales.business_id',auth()->user()->business->id);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('sale_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->p_customer)){
            $reports = $reports->where('pos_sales.customer_id', $request->p_customer);
        }

        if(!empty($request->p_category)){
            $reports = $reports->where('products.category_id', $request->p_category);
        }
        if(!empty($request->p_product)){
            $reports = $reports->where('pos_sale_details.product_id', $request->p_product);
        }
        // dd($reports->get());
        // ->groupBy("product_purchases.product_id")
        $reports= $reports->get();
        $name = 'Pos Sale Excel Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new PosSaleReportExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function invoiceReturnReport(Request $request){
         DB::statement("SET SQL_MODE=''");
       $reports = ProductInvoiceReturn::leftjoin("invoice_returns","invoice_returns.id","product_invoice_returns.invoice_return_id")
        ->leftjoin("products","products.id","product_invoice_returns.product_id")
        ->leftjoin("customers","customers.id","invoice_returns.customer_id")
        ->leftjoin("categories","categories.id","products.category_id")
        ->where('products.product_name','!=','initial')
         ->select('reference_no','return_date','product_name','product_code','product_invoice_returns.qty','per_cost','product_invoice_returns.discount as discount','total','customers.name as customer_name','categories.name as cat_name','invoice_returns.status as status','payment_status');
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $reports=  $reports->where('invoice_returns.business_id',auth()->user()->business->id);
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('return_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if(!empty($request->p_customer)){
            $reports = $reports->where('invoice_returns.customer_id', $request->p_customer);
        }
        if(!empty($request->p_dsr)){
            $reports = $reports->where('invoice_returns.dsr_id', $request->p_dsr);
        }
        if(!empty($request->p_asr)){
            $reports = $reports->where('invoice_returns.asr_id', $request->p_asr);
        }
        if(!empty($request->p_driver)){
            $reports = $reports->where('invoice_returns.sld_id', $request->p_driver);
        }
        if(!empty($request->p_category)){
            $reports = $reports->where('products.category_id', $request->p_category);
        }
        if(!empty($request->p_product)){
            $reports = $reports->where('product_invoice_returns.product_id', $request->p_product);
        }
        // dd($reports->get());
        // ->groupBy("product_purchases.product_id")
        $reports= $reports->get();
        $name = 'Invoice Return Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new InvoiceReturnReportExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function stockReport(Request $request){
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
        // $reports = $reports->where('products.is_variant',0);
        if(!empty($request->p_category)){
            $reports = $reports->where('products.category_id', $request->p_category);
        }
        if(!empty($request->p_product)){
            $reports = $reports->where('stocks.product_id', $request->p_product);
        }
        if(!empty($request->p_brand)){
            $reports = $reports->where('products.brand_id', $request->p_brand);
        }
        if(!empty($request->p_manufacture)){
            $reports = $reports->where('products.manufacture_id', $request->p_manufacture);
        }
        $reports= $reports->get();
        $name = 'Stock Report_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new StockReportExport($reports, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function balanceSheet(Request $request){
         DB::statement("SET SQL_MODE=''");
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $profit_trans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[4,5])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
       // ->groupBy('account_transactions.account_id')
        ->get();
        $indirect_tans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[7])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        // ->groupBy('account_transactions.account_id')
        ->get();
        $direct_tans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[6])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        // ->groupBy('account_transactions.account_id')
        ->get();
        $transactions = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[1,2,3])
        ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->orderBy("account_heads.ac_type",'desc')

        ->get();
        //dd($transactions);
      //  dd(Carbon::parse($from_date)->startOfDay());

        $name = 'Balance Sheet_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new BalanceSheetExport($transactions,$profit_trans,$indirect_tans,$direct_tans, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function trailBalance(Request $request){
        DB::statement("SET SQL_MODE=''");
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $transactions = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereBetween('account_transactions.date',  [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->get();
        // dd($transactions);
        $name = 'Trail Balance_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new InvoiceReturnReportExport($transactions, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function profLoss(Request $request){
        DB::statement("SET SQL_MODE=''");
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $transactions = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[4,5])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->get();
        $indirect_tans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[7])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->get();
        $direct_tans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[6])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->get();
        //dd($transactions);
      //  dd(Carbon::parse($from_date)->startOfDay());
        $name = 'Profil and Loss_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new ProfLossExport($transactions, $direct_tans,$indirect_tans,$from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function ledgerSummary(Request $request){
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['transactions']= $transactions = AccountTransaction::where('account_id',$request->p_account)->whereBetween('date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])->get();
        $data['account'] = AccountHead::find($request->p_account);
        $name = 'Ledger Summary_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new LedgerSummaryExport($transactions, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function transaction(Request $request){
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['v_type']=$request->p_v_type;
        $data['transactions']=  $transactions = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->leftJoin('vouchers','vouchers.id','account_transactions.relation_id')
        ->where('account_transactions.relation_with','Voucher')
        ->whereBetween('vouchers.voucher_date',[Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        if($request->p_v_type){
            $transactions = $transactions->where('vouchers.v_type',$request->p_v_type);
        }
        // $total_ledgers = $reports->select(DB::raw('sum(ledger_transactions.debit_amount) as d_amount,sum(ledger_transactions.credit_amount) as c_amount'))->first();

        $transactions = $transactions->select('vouchers.*','account_heads.title as l_name','account_transactions.amount','account_transactions.type')
        ->get();
        $name = 'Transaction_ ' . date('Y-m-d i:h:s');
        $data = Excel::download(new TransactionExport($transactions, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function expenseReport(Request $request){
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $expense_list=Expense::leftJoin('expense_categories','expense_categories.id','expenses.category_id')
        ->leftJoin('payment_methods','payment_methods.id','expenses.method_id')
        ->whereBetween('expenses.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->select('expense_categories.name','expenses.reason',"expenses.date","expenses.amount",'payment_methods.name as method_name')
        ->get();
        $name = 'expense_report_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ExpenseReportExport($expense_list, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function attendance(Request $request){
        //dd($request->all());
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = Attendance::leftjoin('employees','employees.id','attendances.empID')
        ->orderBy('attendances.id','desc');
        $reports = $reports->whereBetween('attendances.dutyDate', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        if(!empty($request->p_department)){
            $reports = $reports->where('employees.department_id', $request->p_department);
        }
        if(!empty($request->p_designation)){
            $reports = $reports->where('employees.designation_id', $request->p_designation);
        }
        if(!empty($request->p_employee)){
            $reports = $reports->where('attendances.empID', $request->p_employee);
        }


        $reports= $reports->get();
        $name = 'Attendance Report_' . date('Y-m-d i:h:s');
        $data = Excel::download(new AttendanceExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function salarySheet(Request $request){

        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = SalarySheet::leftjoin('employees','employees.id','salary_sheets.empID')
        ->orderBy('salary_sheets.id','desc');
        $reports = $reports->whereBetween('salary_sheets.month', [$from_date, $to_date]);
         if(!empty($request->p_department)){
            $reports = $reports->where('employees.department_id', $request->p_department);
        }
        if(!empty($request->p_designation)){
            $reports = $reports->where('employees.designation_id', $request->p_designation);
        }
        if(!empty($request->p_employee)){
            $reports = $reports->where('salary_sheets.empID', $request->employee);
        }

        $reports= $reports->get();
        $name = 'Salary Report_' . date('Y-m-d i:h:s');
        $data = Excel::download(new SalaryExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function empLeave(Request $request){

        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = LeaveApplication::leftjoin('employees','employees.id','leave_applications.empID')
        ->orderBy('leave_applications.id','desc');
        $reports = $reports->whereBetween('leave_applications.fromDate', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        if(!empty($request->p_department)){
            $reports = $reports->where('employees.department_id', $request->p_department);
        }
        if(!empty($request->p_designation)){
            $reports = $reports->where('employees.designation_id', $request->p_designation);
        }
        if(!empty($request->p_employee)){
            $reports = $reports->where('leave_applications.empID', $request->employee);
        }

        $reports= $reports->get();
        $name = 'Salary Report_' . date('Y-m-d i:h:s');
        $data = Excel::download(new LeaveExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function empLoan(Request $request){

        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = EmpLoan::leftjoin('employees','employees.id','emp_loans.empID')
        ->orderBy('emp_loans.id','desc');
        $reports = $reports->whereBetween('emp_loans.loan_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
         if(!empty($request->p_department)){
            $reports = $reports->where('employees.department_id', $request->p_department);
        }
        if(!empty($request->p_designation)){
            $reports = $reports->where('employees.designation_id', $request->p_designation);
        }
        if(!empty($request->p_employee)){
            $reports = $reports->where('emp_loans.empID', $request->employee);
        }

        $reports= $reports->get();
        $name = 'Loan Report_' . date('Y-m-d i:h:s');
        $data = Excel::download(new LoanExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function empBonus(Request $request){

        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = BonusPay::leftjoin('employees','employees.id','bonus_pays.empID')
        ->orderBy('bonus_pays.id','desc');
        $reports = $reports->whereBetween('bonus_pays.paidDate', [$from_date, $to_date]);
         if(!empty($request->p_department)){
            $reports = $reports->where('employees.department_id', $request->p_department);
        }
        if(!empty($request->p_designation)){
            $reports = $reports->where('employees.designation_id', $request->p_designation);
        }
        if(!empty($request->p_employee)){
            $reports = $reports->where('bonus_pays.empID', $request->employee);
        }

         $reports= $reports->get();
        $name = 'Bonus Report_' . date('Y-m-d i:h:s');
        $data = Excel::download(new BonusExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    function vendorDue(Request $request){
         DB::statement("SET SQL_MODE=''");
        $reports = Purchase::leftjoin("vendors","vendors.id","purchases.supplier_id")
        ->leftJoin("purchase_returns","purchase_returns.purchase_id","purchases.id")
        ->select('purchases.reference_no','purchase_date','purchases.grand_total as total_amount','purchases.paid_amount as paid_amount','purchases.due_amount as due_amount','vendors.name as vendor_name',DB::raw('sum(IFNULL(purchase_returns.grand_total,0)) as total_return'))
        ->where('purchases.due_amount','>',0)
        ->where('purchases.is_ini_p',0)
        ->groupBy('purchases.id');
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('purchase_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        if(!empty($request->p_vendor)){
            $reports = $reports->where('purchases.supplier_id', $request->p_vendor);
        }
        $reports= $reports->get();
        $name = 'Vendor Due Report_' . date('Y-m-d i:h:s');
        $data = Excel::download(new VendorDueExport($reports, $from_date, $to_date, auth()->user()->business), $name . '.xlsx');
        ob_end_clean();
        return $data;


    }
    function customerDue(Request $request){
        DB::statement("SET SQL_MODE=''");
        $reports = Invoice::leftjoin("customers","customers.id","invoices.customer_id")
        ->leftJoin("invoice_returns","invoice_returns.invoice_id","invoices.id")
        ->select('invoices.reference_no','invoice_date','invoices.grand_total as total_amount','invoices.paid_amount as paid_amount','invoices.due_amount as due_amount','customers.name as customer_name',DB::raw('sum(IFNULL(invoice_returns.grand_total,0)) as total_return'))
        ->where('invoices.due_amount','>',0)
        ->where('invoices.is_ini_p',0)
        ->groupBy('invoices.id');
        if($request->p_from_date){
            $from_date = $request->p_from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->p_to_date){
            $to_date = $request->p_to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        //if(!empty($request->from_date) && !empty($request->to_date)){
            $reports = $reports->whereBetween('invoice_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        //}
        $data['customer']=null;
        $dsr=null;
        if(!empty($request->p_dsr)){
            $data['dsr']=  $dsr = Employee::find($request->p_dsr);
            $reports = $reports->where('invoices.dsr_id', $request->p_dsr);
        }

        // if(!empty($request->p_dsr)){
        //     $reports = $reports->where('invoices.dsr_id', $request->p_dsr);
        // }

        if(!empty($request->p_customer)){
            $reports = $reports->where('customers_id.customer_id', $request->p_customer);
        }
        $reports= $reports->get();
        $name = 'Customer Due Report_' . date('Y-m-d i:h:s');
        $data = Excel::download(new CustomerDueExport($reports, $from_date, $to_date, auth()->user()->business,$dsr), $name . '.xlsx');
        ob_end_clean();
        return $data;


    }
    function test_excel(){
    //     $extension = Excel::XLSX;
    //     $writeType = Excel::XLSX;
    //     $contentType =  ['Content-Type' => 'text/xlsx'];
    //     $fileName = 'template_products_import.' . $extension;
    //    // dd(new TemplateProductExport($extension));
    //      return (new TemplateProductExport($extension))->download($fileName, $writeType, $contentType);
    }
}
