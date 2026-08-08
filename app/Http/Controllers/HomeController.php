<?php

namespace App\Http\Controllers;

use App\Models\Inventory\Customer;
use App\Models\Inventory\Invoice;
use App\Models\Inventory\InvoiceReturn;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductInvoice;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\PurchaseReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        DB::statement("SET SQL_MODE=''");

        $data['total_purchase'] = Purchase::whereDate('purchase_date','>', Carbon::now()->subMonths(1))->sum('grand_total');
        // $data['total_purchase'] = Purchase::whereDate('purchase_date','>', Carbon::now()->subDays(6))->sum('grand_total');
        $data['total_purchase_return'] = PurchaseReturn::whereDate('return_date','>', Carbon::now()->subMonths(1))->sum('grand_total');
        $data['total_sale']=$t_sales = Invoice::whereDate('invoice_date','>', Carbon::now()->subMonths(1))->sum('grand_total');
        $data['total_sale_return']=$t_sales_Return = InvoiceReturn::whereDate('return_date','>', Carbon::now()->subMonths(1))->sum('grand_total');

        $t_sales_count = Invoice::whereDate('invoice_date','>', Carbon::now()->subMonths(1))->sum('total_qty');
        $t_sales_Return_count = InvoiceReturn::whereDate('return_date','>', Carbon::now()->subMonths(1))->sum('total_qty');
        // dd(round($t_sales_Return_count/ $t_sales_count,4));
        $data['return_percent'] = $t_sales_count > 0 ? (round($t_sales_Return_count/ $t_sales_count,4)) * 100 : 0;

        $total_revenus=$t_sales -  $t_sales_Return;
        $data['total_revenus'] = $total_revenus;
        $revenue_days = [];
        $revenue_days_price = [];
        for($i=6;$i>=0;$i--){

            $d_date=Carbon::now()->subDays($i)->timezone('UTC');
            $revenue_days[]=substr($d_date->format('l'), 0, 3);
            $d_sales = Invoice::whereBetween('invoice_date',[$d_date->startOfDay()->format('Y-m-d H:i:s'),$d_date->endOfDay()->format('Y-m-d H:i:s')])->sum('grand_total');
            $d_sales_Return = InvoiceReturn::whereBetween('return_date',[$d_date->startOfDay()->format('Y-m-d H:i:s'),$d_date->endOfDay()->format('Y-m-d H:i:s')])->sum('grand_total');
            $revenue_days_price[] =$d_sales-$d_sales_Return;
        }
       // dd($revenue_days);
        $data['revenue_days'] = $revenue_days;
        $data['revenue_days_price'] = $revenue_days_price;
        $data['total_customer'] = Customer::count();
        // dd(Carbon::now()->subDays(7));
        $month=[1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec'];
        $sales=[];
        $purchases=[];
        $invoice_payments_chart =Invoice::whereDate('invoice_date','>', Carbon::now()->subMonths(12))
        ->select(
            DB::raw('MONTH(invoice_date) as month'),
            DB::raw('sum(grand_total) as total'),
            DB::raw('YEAR(invoice_date) as year')
        )
        ->groupBy('month')
        ->get();
        $purchase_payments_chart =Purchase::whereDate('purchase_date','>', Carbon::now()->subMonths(12))
        ->select(
            DB::raw('MONTH(purchase_date) as month'),
            DB::raw('sum(grand_total) as total'),
            DB::raw('YEAR(purchase_date) as year')
        )
        ->groupBy('month')
        ->get();
        foreach ($month as $key => $value) {
            $invoice_data = collect($invoice_payments_chart)
            ->where('month', $key)
            ->first();
            $sales[] = $invoice_data ? round($invoice_data->total,2) : 0;
            $purchase_data = collect($purchase_payments_chart)
            ->where('month', $key)
            ->first();
            $purchases[] = $purchase_data ?  round($purchase_data->total,2) : 0;
        }

        $data['sales'] = $sales;
        $data['purchases'] = $purchases;
        $data['top_4_products']= $top_4_products =  ProductInvoice::select(DB::raw('sum(product_invoices.total) as g_total'),DB::raw('sum(product_invoices.qty) as total_qty'),'products.id as cat_id','products.product_name as product_name')
        ->leftJoin('products','products.id','product_invoices.product_id')

        ->groupBy('products.id')
        ->orderByRaw('count(products.id) DESC')
        ->take(4)
        ->get();
        // dd($top_4_products);

        // Product::take(4)->get();
        // $top_4_products_data=[];
        // foreach($top_4_products as $product){
        //     $tp = ProductInvoice::where('product_id', $product->id)->sum('qty');
        //     $top_4_products_data[]=$tp;

        // }

        // $data['top_4_products_data'] = $top_4_products_data;

        $recent_product_sales =  ProductInvoice::select(DB::raw('sum(total) as g_total'),'per_cost','product_id','invoice_id')->groupBy('invoice_id')->groupBy('product_id')->orderBy('invoice_id','desc')->take(6)->get();
        $data['recent_product_sales'] =$recent_product_sales;
        //  dd($recent_product_sales[0]->product);

        // $data['order_summary'] =['Received',"Pending","Ordered"];
        $order_summary_data = [];
        $order_summary_data[]= Invoice::where('status',1)->count();
        $order_summary_data[]= Invoice::where('status',3)->count();
        $order_summary_data[]= Invoice::where('status',4)->count();
        $data['order_summary_data']=$order_summary_data;

        //top selling category
        $top_selling_category = ProductInvoice::select(DB::raw('sum(product_invoices.total) as g_total'),'product_id','categories.id as cat_id','categories.name as cat_name')
        ->leftJoin('products','products.id','product_invoices.product_id')
        ->leftJoin('categories','categories.id','products.category_id')

        ->groupBy('categories.id')
        ->orderByRaw('SUM(product_invoices.total) DESC')
        ->take(2)
        ->get();
        $data['top_selling_category']=$top_selling_category;
       // dd($top_selling_category);
       $cat_data=[];
        foreach($top_selling_category as $tsc){
           // dd($tsc->product);
            $top_cat_now = Carbon::now();
           // dd($top_cat_now->startOfDay()->format('Y-m-d H:i:s'));
           $c_data=[];
            for($i=0;$i<5;$i++){
                $c_data[] =  ProductInvoice::leftJoin('invoices','invoices.id','product_invoices.invoice_id')
                ->leftJoin('products','products.id','product_invoices.product_id')
                ->leftJoin('categories','categories.id','products.category_id')
                ->where('categories.id',$tsc->cat_id)
                ->whereBetween('invoices.invoice_date',[$top_cat_now->startOfDay()->format('Y-m-d H:i:s'),$top_cat_now->endOfDay()->format('Y-m-d H:i:s')])
                ->groupBy('invoices.id')
                ->groupBy('products.id')
                ->get()->count();
                //print_r($top_cat_now);

                $top_cat_now =  $top_cat_now->subDays(1);
            }
             $cat_data[$tsc->cat_id]=array_reverse($c_data);

        }
       //  end top selling category
       //dd($cat_data);
        $data['cat_data']=$cat_data;
       return view('dashboard',$data);
    }
}
