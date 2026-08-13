<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Inventory\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    function index(){
        if(can_p('bussiness.user.index') == false){
            return redirect()->route('dashboard');
        }
        
        return view('user.index');
    }
    function ajaxUser(Request $request){
        if(auth()->user()->business->business_type_id != 17){
            $columns = [
                'id',
                'name',
                'email',
                'mobile',
                'address',
                'branch_id',
                'role_id',
                'status',
            ];
        }else{
             $columns = [
                'id',
                'name',
                'email',
                'mobile',
                'address',
                'role_id',
                'status',
            ];
        }

        $totalData = User::where('business_id',auth()->user()->business_id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = User::where('business_id',auth()->user()->business_id);
        if(!empty($search)){

           $datalist =$datalist->where("name","LIKE","%{$search}%")
                    ->where("email","LIKE","%{$search}%")
                    ->where("mobile","LIKE","%{$search}%")
                    ->where("address","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('bussiness.user.edit');
            $p_delete = can_p('bussiness.user.delete');
            $p_change_password = can_p('bussiness.user.change_password');
            $p_change_status = can_p('bussiness.user.status');
            foreach ($datalist as $data_v) {
                $nestedData['id'] = $data_v->id;
                $nestedData['name'] = $data_v->name;
                $nestedData['email'] = $data_v->email;
                $nestedData['phone'] = $data_v->mobile;
                $nestedData['address'] = $data_v->address;
                $nestedData['branch'] = $data_v->branch_id == 0 ? '--' :$data_v->branch?->name;
                $nestedData['role'] = $data_v->user_type == 0 ? 'Super Admin' :$data_v->role?->name;
                if($p_change_status){
                    $nestedData['status'] = $data_v->status == 0 ?
                    '<a href="' . route('bussiness.user.status', $data_v->id) . '" class="data_status btn btn-sm btn-warning">Inactive</a>' :
                    '<a href="' . route('bussiness.user.status', $data_v->id) . '" class="data_status btn btn-sm btn-success">Active</a>';
                }else{
                    $nestedData['status'] = $data_v->status == 0 ? 'Inactive' : 'Active';
                }
               
                $nestedData['options']='';
                if($p_edit){
                    $nestedData['options'] .= '<a class="btn btn-primary data_edit me-1 mb-1" href="'.route('bussiness.user.edit',$data_v->id).'"><i class="bx bx-edit"></i></a>';
                }
                if($p_delete && $data_v->user_type != 0){
                    $nestedData['options'] .= '<a href="#" data-id="'.$data_v->id.'" class="del_data_user btn btn-danger mb-1"><i class="bx bx-trash"></i></a>';
                }
                if($p_change_password){
                    $nestedData['options'] .= '<a class="btn btn-info data_edit ms-1" href="'.route('bussiness.user.change_password',$data_v->id).'">Change Password</a>';
                }
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
        if(can_p('bussiness.user.add') == false){
            return redirect()->route('dashboard');
        }
        $data['user']=User::findorNew(0);
        $data['roles']=Role::get();
        $data['branches']=Branch::get();
        return  view('user.create',$data);
    }
    function store(Request $request){
        if(can_p('bussiness.user.add') == false){
            return redirect()->route('dashboard');
        }
        $this->validate($request,[
           'name'=>'required',
           'email'=>'required',
           'password'=>'required',
           'role'=>'required',
           'branch'=>'required',
           'business_logo'=>'image|mimes:jpeg,png,jpg,webp',
       ]);
       try{
           DB::beginTransaction();

           $user = new User;
           $file=$request->file('business_logo');
           if($file){
             
               $filename=date('YmdHi')."_business".$file->getClientOriginalName();
               $file->move(public_path('upload/business_user'),$filename);
               $user->image=$filename;
           }
           $user->business_id = auth()->user()->business_id;
           $user->name = $request->name;
           $user->name = $request->name;
           $user->mobile = $request->mobile;
           $user->email = $request->email;
           $user->address = $request->address;
           $user->role_id = $request->role ?? 0;
           $user->branch_id = $request->branch ?? 0;
           $user->status = 1;
           $user->user_type= 1;
           $user->password = Hash::make($request->password);
          
           $user->save();
           $notification=array(
                   'message'=>"Update Success",
                   'alert-type'=>'success'
               );
            DB::commit();
           return redirect()->route('bussiness.user.index')->with($notification);

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
    function edit($id){
        if(can_p('bussiness.user.edit') == false){
            return redirect()->route('dashboard');
        }
        $data['user']=User::find($id);
        $data['roles']=Role::get();
        $data['branches']=Branch::get();
        return  view('user.edit',$data);
    }
    function update(Request $request, $id){

        if($request->user_type == 0){
            $this->validate($request,[
                'name'=>'required',
                'email'=>'required',
                'branch'=>'nullable',
                'business_logo'=>'image|mimes:jpeg,png,jpg,webp',
            ]);
        }else{
            $this->validate($request,[
                'name'=>'required',
                'email'=>'required',
                'role'=>'nullable',
                'branch'=>'nullable',
                'business_logo'=>'image|mimes:jpeg,png,jpg,webp',
            ]);
        }
       

       try{
           DB::beginTransaction();

           $user = User::find($id);
           $file=$request->file('business_logo');
           if($file){
               @unlink(public_path('upload/business_user/'.$user->image));
               $filename=date('YmdHi')."_business".$file->getClientOriginalName();
               $file->move(public_path('upload/business_user'),$filename);
               $user->image=$filename;
           }
           $user->business_id = auth()->user()->business_id;
           $user->name = $request->name;
           $user->mobile = $request->mobile;
           $user->email = $request->email;
           $user->address = $request->address;
           $user->role_id = $request->role ?? 0;
           $user->branch_id = $request->branch ?? 0;
          

           $user->save();
           $notification=array(
                   'message'=>"Update Success",
                   'alert-type'=>'success'
               );
            DB::commit();
            if($user->business->business_type_id == 17){
                return back()->with($notification);
            }
            return redirect()->route('bussiness.user.index')->with($notification);

       }catch(\Exception $e){
           DB::rollBack();
           $notification=array(
                   'message'=>"Something Went Wrong",
                   'alert-type'=>'error'
               );
           return redirect()->back()->with($notification)->withInput($request->all());

       }
    }

    function ChangePass($id){
        if(can_p('bussiness.user.change_password') == false){
            return redirect()->route('dashboard');
        }
        $data['user']=User::find($id);
        $data['is_profile']=0;
        return  view('user.change_pass',$data);
    }
    function ChangePassP($id){
        $data['is_profile']=1;
        $data['user']=User::find($id);
        return  view('user.change_pass',$data);
    }
    function ChangePassPost(Request $request, $id){
        if(can_p('bussiness.user.change_password') == false){
            return redirect()->route('dashboard');
        }
        if($request->is_profile == 1){
            $this->validate($request,[
                'old_password'=>'required',
                'new_password'=>'required',
             ]);
        }else{
            $this->validate($request,[
                'new_password'=>'required',
             ]);
        }
        
       try{
           DB::beginTransaction();
            $user = User::find($id);
            if($request->is_profile == 1){
                if(Hash::check($request->old_password,$user->password)){
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                }else{
                    $notification=array(
                        'message'=>"Old Password does not match",
                        'alert-type'=>'error'
                    );
                    return redirect()->back()->with($notification)->withInput($request->all());
                }
            }else{
                $user->password = Hash::make($request->new_password);
                $user->save(); 
            }
           $notification=array(
                   'message'=>"Update Success",
                   'alert-type'=>'success'
               );
            DB::commit();
           return redirect()->route('bussiness.user.index')->with($notification);

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
    function updateStatus($id){
        if(can_p('bussiness.user.status') == false){
            return redirect()->route('dashboard');
        }
        $user = User::find($id);
        if($user->status == 1){
            $user->status =0;
        }else{
            $user->status =1; 
        }
        $user->save();
        $notification=array(
            'message'=>"Update Success",
            'alert-type'=>'success'
        );
    
        return redirect()->route('bussiness.user.index')->with($notification);
    }

    function delete($id){
        if(can_p('bussiness.user.delete') == false){
            return redirect()->route('dashboard');
        }
        $user = User::find($id);
        $user->delete();
        $notification=array(
            'message'=>"Delete Success",
            'alert-type'=>'success'
        );
    
        return redirect()->route('bussiness.user.index')->with($notification);
    }
}
