<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Business;
use App\Models\Permission;
use App\Models\PackageOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BusinessController extends Controller
{
    function index(Request $request){
        // $permission = Permission::find(236);
        // $permission->section = 'hr-payroll';
        // $permission->save();
        // foreach($permission->child_menus as $ch){
        //     $ch->section = 'hr-payroll';
        //     $ch->save();
        //     if($ch->child_menus->count() > 0){
        //         foreach($ch->child_menus as $ch1){
        //             $ch1->section = 'hr-payroll';
        //             $ch1->save();
        //             if($ch1->child_menus->count() > 0){
        //                 foreach($ch1->child_menus as $ch2){
        //                     $ch2->section = 'hr-payroll';
        //                     $ch2->save();
        //                     if($ch2->child_menus->count() > 0){
        //                         foreach($ch2->child_menus as $ch3){
        //                             $ch3->section = 'hr-payroll';
        //                             $ch3->save();
        //                             if($ch3->child_menus->count() > 0){
        //                                 foreach($ch3->child_menus as $ch4){
        //                                     $ch4->section = 'hr-payroll';
        //                                     $ch4->save();
        //                                     if($ch4->child_menus->count() > 0){
        //                                         foreach($ch4->child_menus as $ch5){
        //                                             $ch5->section = 'hr-payroll';
        //                                             $ch5->save();
        //                                         }
        //                                     }
        //                                 }
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        $data['businesses']=Business::orderBy('id','DESC')->get();
        return view('admin.business.index',$data);
    }
    function ajaxBusiness(Request $request){
        $columns = [
            'id',
            'logo',
            'business_name',
            'email',
            'mobile_number',
            'business_type_id',
            'user_type',
            'package_id',
            'pack_end_date',
        ];

        $totalData = Business::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = Business::query();
        if(!empty($search)){

           $datalist =$datalist->where("business_name","LIKE","%{$search}%")
                    ->orwhere("email","LIKE","%{$search}%")
                    ->orwhere("mobile_number","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        // dd($datalist);

        $data = array();
        $types = [
            ''=>'Select Business Type',
           '1'=>'Clothing & Brand',
           '2'=>'Super Shop',
           '3'=>'Cosmetices Shop',
           '4'=>'Jewellery Shop',
           '5'=>'Pharmacy Shop',
           '6'=>'Mobile Shop',
           '7'=>'Glossary Shop',
           '8'=>'Agro Farm',
           '9'=>'Ecommerce & F-commerce',
           '10'=>'Restaurant',
           '11'=>'Electric & Electronics',
           '12'=>'Trading & Traders',
           '13'=>'Book Shop',
           '14'=>'Computer Shop',
           '15'=>'Dealership',
           '16'=>'Software Company',
           '17'=>'Bangladesh Principal Association',
           '18'=>'Food Products Industry ',
            '19'=>'Constructions Company',
            '20'=>'Realestate Company',
       ];
        if(!empty($datalist))
        {
           $i = $start == 0 ? 1 : $start+1;
            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i++;
                $nestedData['name'] = $data_v->business_name;
                $nestedData['email'] = $data_v->email;
                $nestedData['phone'] = $data_v->mobile_number;
                $nestedData['logo'] = '<img src="'.$data_v->business_logo_show.'" style="height:50px;width:50px;">';
                $nestedData['business_type'] = $types[$data_v->business_type_id];
                if($data_v->user_type == 0){
                    $nestedData['user_type'] = 'Free';
                }else{
                    if($data_v->package){
                        if(\Carbon\Carbon::now()->lte($data_v->pack_end_date)){
                            $nestedData['user_type'] = 'Paid';
                        }else{
                            $nestedData['user_type'] = 'Expire';
                        }
                    }else{
                        $nestedData['user_type'] = 'Not Package Found';
                    }
                }
                $nestedData['package'] = $data_v->package?->name;
                $nestedData['pack_end_date'] = $data_v->package ? date('Y-m-d', strtotime($data_v->pack_end_date)) : '--';
                
                $nestedData['options'] = '<a class="btn btn-primary data_edit me-1 mb-1" href="'.route('admin.edit_business',$data_v->id).'"><i class="bx bx-edit"></i></a>';
                $nestedData['options'] .= '<a href="#" data-id="'.$data_v->id.'" class="del_data btn btn-danger mb-1 me-1"><i class="bx bx-trash"></i></a>';
                $user =  User::where('business_id',$data_v->id)->where('user_type',0)->first();
                if($user){
                    $nestedData['options'] .= '<a class="btn btn-info data_edit me-1" href="'.route('admin.business.change_password',$user->id).'">Change Password</a>';
                }
                $nestedData['options'] .= '<a href="'.route('admin.package.add_pack',$data_v->id).'" class="me-1 btn btn-success mb-1">Add Package</a>';
                
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
        $id=0;
        $data['business'] =  Business::findorNew($id);
        return view('admin.business.create',$data);
    }
    function select2BusinessList(Request $request){
        $brands = Business::select('id', 'business_name')->where("business_name", "LIKE", "%$request->value%")->get();
        foreach ($brands as $brand) {
            $data[] = ['id' => $brand->id, 'text' => $brand->business_name];
        }
        return json_encode($data);
    }
    function store(Request $request){
        $this->validate($request,[
            'business_name'=>'required',
            'email_address'=>'required',
            'password'=>'required',
            'organization_type_id'=>'required',
            'business_type_id'=>'required',
            'business_logo'=>'image|mimes:jpeg,png,jpg,webp',
        ],
        [
           'organization_type_id.required' =>'Organization Type is Required',
           'business_type_id.required' =>'Buisness Type is Required',
        ]);
        try{
            DB::beginTransaction();
            $business = New Business;
            $file=$request->file('business_logo');
            if($file){
                @unlink(public_path('upload/business/'.$business->business_logo));
                $filename=date('YmdHi')."_business".$file->getClientOriginalName();
                $file->move(public_path('upload/business'),$filename);
                $business->business_logo=$filename;
            }
            $business->business_name = $request->business_name;
            $business->mobile_number = $request->mobile_phone;
            $business->phone_number = $request->phone_number;
            $business->email = $request->email_address;
            $business->fax= $request->fax;
            $business->website= $request->website;
            $business->business_type_id = $request->business_type_id ?? 0;
            $business->oranization_id = $request->organization_type_id ?? 0;
            $business->currency_id = $request->currency ?? 0;
            $business->timezone_id = $request->timezone_id ?? 0;
            $business->country_id = $request->country_id ?? 0;
            $business->state_id = $request->state_id ?? 0;
            $business->city_id = $request->city_id ?? 0;
            $business->user_type = $request->user_type ?? 0;
            
            $business->save();
            $user = new User;
            $user->name = $request->business_name;
            $user->email = $request->email_address;
            $user->mobile = $request->mobile_phone;
            $user->business_id= $business->id;
            $user->user_type= 0;
            $user->password = Hash::make($request->password);
            $user->status = 1;

            $user->save();

            if($request->user_type == 1){
                $package=Package::find($request->package);
                $order = new PackageOrder;
                $order->package_id = $request->package;
                $order->end_date = $request->end_date;
                $order->amount = $package->amount;
                $order->business_id = $business->id;
                $order->save();

                $business->package_id = $request->package;
                $business->order_id = $order->id;
                $business->pack_end_date = $request->end_date;
                $business->save();
            }
            $notification=array(
                    'message'=>"Save Success",
                    'alert-type'=>'success'
                );
            

            DB::commit();
            return redirect()->route('admin.all_business')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                    'message'=>"Something Went Wrong",
                    'alert-type'=>'error'
                );
            return redirect()->back()->with($notification)->withInput($request->all());

        }
    }
    function edit($id){
        $data['business'] = Business::find($id);
        return view('admin.business.edit',$data);
    }
    function update(Request $request, $id){
         $this->validate($request,[
            'business_name'=>'required',
            'email_address'=>'required',
            'organization_type_id'=>'required',
            'business_type_id'=>'required',
            'business_logo'=>'image|mimes:jpeg,png,jpg,webp',
        ],
        [
           'organization_type_id.required' =>'Organization Type is Required',
           'business_type_id.required' =>'Buisness Type is Required',
        ]);
        try{
            DB::beginTransaction();

            $business = Business::find($id);
            $file=$request->file('business_logo');
            if($file){
                @unlink(public_path('upload/business/'.$business->business_logo));
                $filename=date('YmdHi')."_business".$file->getClientOriginalName();
                $file->move(public_path('upload/business'),$filename);
                $business->business_logo=$filename;
            }
            $business->business_name = $request->business_name;

            $business->mobile_number = $request->mobile_phone;
            $business->phone_number = $request->phone_number;
            $business->email = $request->email_address;
            $business->fax= $request->fax;
            $business->website= $request->website;
            $business->business_type_id = $request->business_type_id ?? 0;
            $business->oranization_id = $request->organization_type_id ?? 0;
            $business->currency_id = $request->currency ?? 0;
            $business->timezone_id = $request->timezone_id ?? 0;
            $business->country_id = $request->country_id ?? 0;
            $business->state_id = $request->state_id ?? 0;
            $business->city_id = $request->city_id ?? 0;
           // $business->user_type = $request->user_type ?? 0;
            $business->start_date = $request->start_date;
            $business->save();

            $user =  User::where('business_id',$business->id)->where('user_type',0)->first();
            if($user == null){
                $user = new User;
                $user->password = Hash::make(123456789);
            }
            $user->name = $request->business_name;
            $user->email = $request->email_address;
            $user->mobile = $request->mobile_phone;
            $user->business_id= $business->id;
            $user->user_type= 0;
            
            $user->status = 1;
            $user->save();
            $notification=array(
                    'message'=>"Save Success",
                    'alert-type'=>'success'
                );
            DB::commit();
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

    function delete($id){
        $business = Business::find($id);
        @unlink(public_path('upload/business/'.$business->business_logo));
        $users =  User::where('business_id',$business->id)->get();
        foreach($users as $user){
            $user->delete();
        }
        $business->delete();
        $notification=array(
            'message'=>"Delete Success",
            'alert-type'=>'success'
        );
    
        return redirect()->route('admin.all_business')->with($notification);
    }
    function ChangePass($id){
        $data['user']=User::find($id);
        return  view('admin.business.change_pass',$data);
    }
    function ChangePassPost(Request $request, $id){
       
        $this->validate($request,[
            'new_password'=>'required',
        ]);
       try{
            DB::beginTransaction();
            $user = User::find($id);
            $user->password = Hash::make($request->new_password);
            $user->save(); 
            
           $notification=array(
                   'message'=>"Change Password Success",
                   'alert-type'=>'success'
               );
            DB::commit();
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
}
