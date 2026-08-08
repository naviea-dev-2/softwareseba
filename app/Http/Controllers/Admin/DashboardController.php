<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Customer;
use App\Models\Admin;
use App\Models\Business;
use App\Models\Inventory\Invoice;
use App\Models\Inventory\InvoiceReturn;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductInvoice;
use App\Models\Inventory\Purchase;
use App\Models\SiteSetting;
use App\Models\SoftwareService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    function index(){
        // dd(auth()->check());
         DB::statement("SET SQL_MODE=''");

        // $data['total_purchase'] = Purchase::whereDate('purchase_date','>', Carbon::now()->subDays(6))->sum('grand_total');
        // $data['total_sale']=$t_sales = Invoice::whereDate('invoice_date','>', Carbon::now()->subDays(6))->sum('grand_total');
        // $t_sales_Return = InvoiceReturn::whereDate('return_date','>', Carbon::now()->subDays(6))->sum('grand_total');

        // $t_sales_count = Invoice::whereDate('invoice_date','>', Carbon::now()->subDays(6))->sum('total_qty');
        // $t_sales_Return_count = InvoiceReturn::whereDate('return_date','>', Carbon::now()->subDays(6))->sum('total_qty');
        // // dd(round($t_sales_Return_count/ $t_sales_count,4));
        // $data['return_percent'] = $t_sales_count > 0 ? (round($t_sales_Return_count/ $t_sales_count,4)) * 100 : 0;

        // $total_revenus=$t_sales -  $t_sales_Return;
        // $data['total_revenus'] = $total_revenus;
        // $revenue_days = [];
        // $revenue_days_price = [];
        // for($i=6;$i>=0;$i--){

        //     $d_date=Carbon::now()->subDays($i)->timezone('UTC');
        //     $revenue_days[]=substr($d_date->format('l'), 0, 3);
        //     $d_sales = Invoice::whereBetween('invoice_date',[$d_date->startOfDay()->format('Y-m-d H:i:s'),$d_date->endOfDay()->format('Y-m-d H:i:s')])->sum('grand_total');
        //     $d_sales_Return = InvoiceReturn::whereBetween('return_date',[$d_date->startOfDay()->format('Y-m-d H:i:s'),$d_date->endOfDay()->format('Y-m-d H:i:s')])->sum('grand_total');
        //     $revenue_days_price[] =$d_sales-$d_sales_Return;
        // }
       // dd($revenue_days);
        // $data['revenue_days'] = $revenue_days;
        // $data['revenue_days_price'] = $revenue_days_price;
        // $data['total_customer'] = Customer::count();
        // // dd(Carbon::now()->subDays(7));
        // $month=[1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec'];
        // $sales=[];
        // $purchases=[];
        // $invoice_payments_chart =Invoice::whereDate('invoice_date','>', Carbon::now()->subMonths(12))
        // ->select(
        //     DB::raw('MONTH(invoice_date) as month'),
        //     DB::raw('sum(grand_total) as total'),
        //     DB::raw('YEAR(invoice_date) as year')
        // )
        // ->groupBy('month')
        // ->get();
    //     $purchase_payments_chart =Purchase::whereDate('purchase_date','>', Carbon::now()->subMonths(12))
    //     ->select(
    //         DB::raw('MONTH(purchase_date) as month'),
    //         DB::raw('sum(grand_total) as total'),
    //         DB::raw('YEAR(purchase_date) as year')
    //     )
    //     ->groupBy('month')
    //     ->get();
    //     foreach ($month as $key => $value) {
    //         $invoice_data = collect($invoice_payments_chart)
    //         ->where('month', $key)
    //         ->first();
    //         $sales[] = $invoice_data ? round($invoice_data->total,2) : 0;
    //         $purchase_data = collect($purchase_payments_chart)
    //         ->where('month', $key)
    //         ->first();
    //         $purchases[] = $purchase_data ?  round($purchase_data->total,2) : 0;
    //     }

    //     $data['sales'] = $sales;
    //     $data['purchases'] = $purchases;
    //     $data['top_4_products']= $top_4_products = Product::take(4)->get();
    //     $top_4_products_data=[];
    //     foreach($top_4_products as $product){
    //         $tp = ProductInvoice::where('product_id', $product->id)->sum('qty');
    //         $top_4_products_data[]=$tp;

    //     }

    //     $data['top_4_products_data'] = $top_4_products_data;

    //     $recent_product_sales =  ProductInvoice::select(DB::raw('sum(total) as g_total'),'per_cost','product_id','invoice_id')->groupBy('invoice_id')->groupBy('product_id')->orderBy('invoice_id','desc')->take(6)->get();
    //     $data['recent_product_sales'] =$recent_product_sales;
    //     //  dd($recent_product_sales[0]->product);

    //     // $data['order_summary'] =['Received',"Pending","Ordered"];
    //     $order_summary_data = [];
    //     $order_summary_data[]= Invoice::where('status',1)->count();
    //     $order_summary_data[]= Invoice::where('status',3)->count();
    //     $order_summary_data[]= Invoice::where('status',4)->count();
    //     $data['order_summary_data']=$order_summary_data;

    //     //top selling category
    //     $top_selling_category = ProductInvoice::select(DB::raw('sum(product_invoices.total) as g_total'),'product_id','categories.id as cat_id')
    //     ->leftJoin('products','products.id','product_invoices.product_id')
    //     ->leftJoin('categories','categories.id','products.category_id')

    //     ->groupBy('categories.id')
    //     ->orderByRaw('SUM(product_invoices.total) DESC')
    //     ->take(2)
    //     ->get();
    //     $data['top_selling_category']=$top_selling_category;
    //    // dd($top_selling_category);
    //    $cat_data=[];
    //     foreach($top_selling_category as $tsc){
    //         $top_cat_now = Carbon::now();
    //        // dd($top_cat_now->startOfDay()->format('Y-m-d H:i:s'));
    //        $c_data=[];
    //         for($i=0;$i<5;$i++){
    //             $c_data[] =  ProductInvoice::leftJoin('invoices','invoices.id','product_invoices.invoice_id')
    //             ->leftJoin('products','products.id','product_invoices.product_id')
    //             ->leftJoin('categories','categories.id','products.category_id')
    //             ->where('categories.id',$tsc->cat_id)
    //             ->whereBetween('invoices.invoice_date',[$top_cat_now->startOfDay()->format('Y-m-d H:i:s'),$top_cat_now->endOfDay()->format('Y-m-d H:i:s')])
    //             ->groupBy('invoices.id')
    //             ->groupBy('products.id')
    //             ->get()->count();
    //             //print_r($top_cat_now);

    //             $top_cat_now =  $top_cat_now->subDays(1);
    //         }
    //          $cat_data[$tsc->cat_id]=array_reverse($c_data);

    //     }
    //    //  end top selling category
    //     $data['cat_data']=$cat_data;
        $data['total_users'] = Admin::count();
        $data['total_business'] = Business::count();
        return view("admin.dashboard",$data);
    }
    function siteSetting(){
        $data['site_setting'] = SiteSetting::FirstorNew();
        $data['software_services'] = SoftwareService::get();
        return view("admin.site_setting",$data);
    }
    function setSiteSetting(Request $request){
      // dd($request);
        $this->validate($request,[
            'header_logo'=>'image|mimes:jpeg,png,jpg,webp',
            'favicon'=>'image|mimes:jpeg,png,jpg,webp',
        ]);
        $site_setting = SiteSetting::first();
        if($site_setting == null){
            $site_setting = New SiteSetting;
        }
        $site_setting->company_name=$request->company_name;
        $site_setting->right_text=$request->right_text;
        $site_setting->email1=$request->email;
        $site_setting->phone1=$request->phone;
        $site_setting->company_establish_year=$request->company_establish_year;
        $site_setting->software_service_slogan=$request->software_service_slogan;

        $site_setting->facebook=$request->facebook;
        $site_setting->twitter=$request->twitter;
        $site_setting->instagram=$request->instagram;
        $site_setting->youtube=$request->youtube;
        $site_setting->linkedin=$request->linkedin;
        $site_setting->google=$request->google;

        if($request->hasFile('header_logo')){
            @unlink(public_path('upload/site_setting/'.$site_setting->header_image));
            $fileName = time().'_header-logo23.'.$request->header_logo->getClientOriginalExtension();
          //  dd( $fileName);
            $request->header_logo->move(public_path('upload/site_setting'), $fileName);

            $site_setting->header_image =$fileName;
        }
        if($request->hasFile('favicon')){
            @unlink(public_path('upload/site_setting/'.$site_setting->favicon));
            $fileName = time().'_favicon.'.$request->favicon->getClientOriginalExtension();
            $request->favicon->move(public_path('upload/site_setting'), $fileName);

            $site_setting->favicon =$fileName;
        }
        $site_setting->save();
        if($request->title){
            foreach($request->title as $key => $value){
                $software_service = New SoftwareService;
                $software_service->title= $value;
                $software_service->icon_class= $request->icon_class[$key];
                $software_service->url= $request->url[$key];
                $software_service->save();
            }
        }
          if($request->old_title){
            foreach($request->old_title as $key => $value){
                $software_service =  SoftwareService::find($key);
                $software_service->title= $value;
                $software_service->icon_class= $request->old_icon_class[$key];
                $software_service->url= $request->old_url[$key];
                $software_service->save();
            }
        }
        return back()->with("success", "Update Successfully!");
    }

    function serviceList(Request $request){
        $data['software_services'] = SoftwareService::get();
        return view('admin.theme_option.service_list', $data);
    }
    function serviceCreate(Request $request){
        return view('admin.theme_option.service_create');
    }
    function serviceStore(Request $request){
        //dd($request->all());
        $this->validate($request,[
            'title'=>'required',
            'url'=>'required',
            'logo'=>'required|image|mimes:jpeg,png,jpg,webp',
        ]);
        try{

            $software_service = New SoftwareService;
            $software_service->title= $request->title;
            $image=null;
            if($request->hasFile('logo')){
                $fileName = time().'_soft_service.'.$request->logo->getClientOriginalExtension();
                $request->logo->move(public_path('upload/soft_service'), $fileName);
                $image =$fileName;
            }
            $software_service->image= $image;
            $software_service->url= $request->url;
            $software_service->save();
            $notification=array(
                'message'=>"Save Successfully!",
                'alert-type'=>'success'
            );
            return redirect()->route('backend.service_list')->with($notification);
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
        }

    }
    function serviceEdit(Request $request,$id){
        $data['software_service'] = SoftwareService::find($id);
        return view('admin.theme_option.service_edit',$data);
    }
    function serviceUpdate (Request $request,$id){
        $this->validate($request,[
            'title'=>'required',
            'url'=>'required',
            // 'logo'=>'required',
        ]);
        try{
            $software_service =  SoftwareService::find($id);
            $software_service->title= $request->title;
            $image=null;
            // if($request->hasFile('logo')){
            //     $fileName = time().'_soft_service.'.$request->logo->getClientOriginalExtension();
            //     $request->logo->move(public_path('upload/soft_service'), $fileName);
            //     $image =$fileName;
            // }
            // $software_service->image= $image;
            $software_service->url= $request->url;
            $software_service->save();
            $notification=array(
                'message'=>"Update Success",
                'alert-type'=>'success'
            );
        
            return redirect()->route('backend.service_list')->with($notification);
        }catch(\Exception $e){
            // dd($e->getMessage());
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification)->withInput($request->all());
            //return back()->with("error", "Data Failed!")->withInput();
        }
    }
    function serviceDelete(Request $request,$id){
        try{
            $software_service =  SoftwareService::find($id);
            $software_service->delete();
            $notification=array(
                'message'=>"Delete Successfully",
                'alert-type'=>'success'
            );
            return redirect()->route('backend.service_list')->with($notification);
        }catch(\Exception $e){
            $notification=array(
                'message'=>$e->getMessage(),
                'alert-type'=>'success'
            );
        
            return  back()->with($notification);
            // return back()->with("error", "Data Failed!")->withInput();
        }
    }
}
