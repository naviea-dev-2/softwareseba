<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Business;
use App\Models\PackageOrder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
class PackageController extends Controller
{
    function index(){
        return view('admin.package.index');
    }
    function indexOrder(){
        return view('admin.package.index_order');
    }
    function select2Package(Request $request){
        $packages = Package::where("name", "LIKE", "%$request->value%")->limit(10)->orderBy('id','desc')->get();
        foreach ($packages as $package) {
            $data[] = ['id' => $package->id, 'text' => $package->name];
        }
        return json_encode($data);
    }
    function ajaxPackage(Request $request){
        $columns = [
            'id',
            'name',
            'pack_type',
            'duration',
            'amount',
        ];

        $totalData = Package::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = Package::query();
        if(!empty($search)){

           $datalist =$datalist->where("name","LIKE","%{$search}%")
                    ->orWhere("amount","LIKE","%{$search}%")  
                    ->orWhere("duration","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
           $i = $start == 0 ? 1 : $start+1;
            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i++;
                $nestedData['name'] = $data_v->name;
                $nestedData['amount'] = $data_v->amount;
                $nestedData['type'] = $data_v->pack_type == "month" ? 'Monthly' : 'Yearly';
                $nestedData['duration'] = $data_v->duration.($data_v->pack_type == "month" ? ' Month' : ' Year');

                $nestedData['options'] = '<a class="btn btn-primary data_edit me-1" href="'.route('admin.package.edit',$data_v->id).'"><i class="bx bx-edit"></i></a>';
                $nestedData['options'] .= '<a href="#" data-id="'.$data_v->id.'" class="del_data_user btn btn-danger"><i class="bx bx-trash"></i></a>';
                
                $data[] = $nestedData;
            }
        }
        $json_data = [
           'draw' => intval($request->input('draw')),
           'recordsTotal' => intval($totalData),
           'recordsFiltered' => intval($totalFiltered),
           'data' => $data
        ];
   
        return response()->json($json_data);
    }
    function packageOrderAjax(Request $request){
       // dd("ss");
        $columns = [
            0 => 'id',
            1 => 'businesses.business_name',
            2 => 'businesses.email',
            3 => 'businesses.mobile_number',
            4 => 'packages.name',
            5 => 'package_orders.end_date',
            6 => 'package_orders.amount',
        ];

        $totalData = PackageOrder::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = PackageOrder::leftjoin('packages','packages.id','package_orders.package_id')
                ->leftjoin('businesses','businesses.id','package_orders.business_id');
        if(!empty($search)){

           $datalist =$datalist->where("businesses.business_name","LIKE","%{$search}%")
                    ->orWhere("businesses.email","LIKE","%{$search}%")  
                    ->orWhere("businesses.mobile_number","LIKE","%{$search}%") 
                    ->orWhere("packages.name","LIKE","%{$search}%")  
                    ->orWhere("package_orders.end_date","LIKE","%{$search}%")  
                    ->orWhere("package_orders.amount","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->select('package_orders.*','businesses.business_name','businesses.email','businesses.mobile_number','packages.name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
           $i = $start == 0 ? 1 : $start+1;
            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i++;
                $nestedData['name'] = $data_v->business_name;
                $nestedData['email'] = $data_v->email;
                $nestedData['phone'] = $data_v->mobile_number;
                $nestedData['package'] = $data_v->name;
                $nestedData['end_date'] =  date('Y-m-d', strtotime($data_v->end_date));
                $nestedData['amount'] = $data_v->amount;
                
                $data[] = $nestedData;
            }
        }
        $json_data = [
           'draw' => intval($request->input('draw')),
           'recordsTotal' => intval($totalData),
           'recordsFiltered' => intval($totalFiltered),
           'data' => $data
        ];
   
        return response()->json($json_data);
    }
    function create(){
        $data['package']=Package::findorNew(0);
        return  view('admin.package.create',$data);
    }
    function getEndDate($id){
        $package=Package::find($id);
        if($package->pack_type == "month"){
            $now_date = \Carbon\Carbon::now();
            $end_date = $now_date->addDays($package->duration*30);
        }else{
            $now_date = \Carbon\Carbon::now();
            $end_date = $now_date->addDays($package->duration*365);
        }
        return $end_date->format('Y-m-d');
    }
    function addNew($id,Request $request){
        $data['business'] = $business = Business::find($id);
        $data['order'] = $order = PackageOrder::find($business->order_id);
        return view('admin.package.add_new',$data);
    }
    function packOrder($id,Request $request){
       // dd("ss");
        $this->validate($request,[
            'package'=>'required',
            'end_date'=>'required',
        ]);
        try{
            DB::beginTransaction();
            $package=Package::find($request->package);
            //dd($package);
            $order = new PackageOrder;
            $order->package_id = $request->package;
            $order->end_date = $request->end_date;
            $order->amount = $package->amount;
            $order->business_id = $id;
            $order->save();
            $business = Business::find($id);
            $business->user_type = 1;
            $business->package_id = $request->package;
            $business->order_id = $order->id;
            $business->pack_end_date = $request->end_date;
            $business->save();
            DB::commit();
            $notification=array(
                'message'=>"Package Added Successfully!",
                'alert-type'=>'success'
            );
            return redirect()->route('admin.all_business')->with($notification);
 
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            $notification=array(
                    'message'=>"Something Went Wrong",
                    'alert-type'=>'error'
                );
            return redirect()->back()->with($notification)->withInput($request->all());
 
        }
    }
    function store(Request $request){
        //dd($request->all());
        $this->validate($request,[
           'name'=>'required',
           'amount'=>'required',
           'option'=>'required',
        ]);
       //dd($request->option);
       //dd(array_search('inventorys',$request->option));
       try{
           DB::beginTransaction();

           $package = new Package;
           $package->name = $request->name;
           $package->pack_type = $request->pack_type ?? 'month';
           $package->duration = $request->duration ?? 1;
           $package->amount = $request->amount;
           $package->description = $request->description;
           $package->pack_option = json_encode($request->option);
           $package->save();
           $notification=array(
                'message'=>"Created Success",
                'alert-type'=>'success'
            );
            DB::commit();
           return redirect()->route('admin.package.index')->with($notification);

        }catch(\Exception $e){
           DB::rollBack();
          // dd($e->getMessage());
           $notification=array(
                   'message'=>"Something Went Wrong",
                   'alert-type'=>'error'
               );
           return redirect()->back()->with($notification)->withInput($request->all());

        }
    }
    function edit($id){
        $data['package']=$package=Package::find($id);
        //dd(json_decode($package->pack_option,true));
        return  view('admin.package.edit',$data);
    }
    function update(Request $request, $id){
       // dd(json_encode($request->option));
        $this->validate($request,[
           'name'=>'required',
           'amount'=>'required',
           'option'=>'required',
       ]);
       try{
           DB::beginTransaction();

           $package = Package::find($id);
           $package->name = $request->name;
           $package->pack_type = $request->pack_type ?? 'month';
           $package->duration = $request->duration ?? 1;
           $package->amount = $request->amount;
           $package->description = $request->description;
           $package->pack_option = json_encode($request->option);
           $package->save();
           $notification=array(
                   'message'=>"Update Success",
                   'alert-type'=>'success'
               );
            DB::commit();
           return redirect()->route('admin.package.index')->with($notification);

       }catch(\Exception $e){
           DB::rollBack();
          // dd($e->getMessage());
           $notification=array(
                   'message'=>"Something Went Wrong",
                   'alert-type'=>'error'
               );
           return redirect()->back()->with($notification)->withInput($request->all());

       }
    }
    function delete($id){
        try{
            DB::beginTransaction();
            $package = Package::find($id);
            $package->delete();
            DB::commit();
            $notification=array(
                'message'=>"Delete Success",
                'alert-type'=>'success'
            );
        
            return redirect()->route('admin.package.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            if($e->getCode() == '23000'){
                $notification=array(
                    'message'=>"This data can not be delete",
                    'alert-type'=>'success'
                );
            
                return redirect()->route('admin.package.index')->with($notification);
            }else{
                $notification=array(
                    'message'=>$e->getMessage(),
                    'alert-type'=>'success'
                );
            
                return redirect()->route('admin.package.index')->with($notification);
            }
        }
        catch(\Error $e){
            DB::rollBack();
            $notification=array(
                'message'=>$e->getMessage(),
                'alert-type'=>'success'
            );
            return redirect()->route('admin.package.index')->with($notification);
        }
    }
}
