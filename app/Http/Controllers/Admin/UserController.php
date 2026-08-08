<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    function index(){
        return view('admin.user.index');
    }
    function ajaxUser(Request $request){
        $columns = [
            'id',
            'name',
            'email',
            'mobile',
            'address',
            'status',
        ];

        $totalData = Admin::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = Admin::query();
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
            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i++;
                $nestedData['name'] = $data_v->name;
                $nestedData['email'] = $data_v->email;
                $nestedData['phone'] = $data_v->mobile;
                $nestedData['address'] = $data_v->address;
                $nestedData['status'] = $data_v->status == 0 ?
                   '<a href="' . route('admin.user.status', $data_v->id) . '" class="data_status btn btn-sm btn-warning">Inactive</a>' :
                   '<a href="' . route('admin.user.status', $data_v->id) . '" class="data_status btn btn-sm btn-success">Active</a>';
               
                
                $nestedData['options'] = '<a class="btn btn-primary data_edit me-1" href="'.route('admin.user.edit',$data_v->id).'"><i class="bx bx-edit"></i></a>';
                $nestedData['options'] .= '<a href="#" data-id="'.$data_v->id.'" class="del_data_user btn btn-danger"><i class="bx bx-trash"></i></a>';
                $nestedData['options'] .= '<a class="btn btn-info data_edit ms-1" href="'.route('admin.user.change_password',$data_v->id).'">Change Password</a>';
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
        $data['user']=Admin::findorNew(0);
        return  view('admin.user.create',$data);
    }
    function store(Request $request){
        $this->validate($request,[
           'name'=>'required',
           'email'=>'required',
           'password'=>'required',
           'business_logo'=>'image|mimes:jpeg,png,jpg,webp',
       ]);
      
       try{
           DB::beginTransaction();

           $user = new Admin;
           $file=$request->file('business_logo');
           if($file){
             
               $filename=date('YmdHi')."_business".$file->getClientOriginalName();
               $file->move(public_path('upload/admin_user'),$filename);
               $user->image=$filename;
           }
           $user->name = $request->name;
           $user->name = $request->name;
           $user->mobile = $request->mobile;
           $user->email = $request->email;
           $user->address = $request->address;
           $user->status = 1;
           $user->password = Hash::make($request->password);
          
           $user->save();
           $notification=array(
                   'message'=>"Update Success",
                   'alert-type'=>'success'
               );
            DB::commit();
           return redirect()->route('admin.user.index')->with($notification);

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
        $data['user']=Admin::find($id);
        return  view('admin.user.edit',$data);
    }
    function update(Request $request, $id){
        $this->validate($request,[
           'name'=>'required',
           'email'=>'required',
           'business_logo'=>'image|mimes:jpeg,png,jpg,webp',
       ]);
       try{
           DB::beginTransaction();

           $user = Admin::find($id);
           $file=$request->file('business_logo');
           if($file){
               @unlink(public_path('upload/admin_user/'.$user->image));
               $filename=date('YmdHi')."_business".$file->getClientOriginalName();
               $file->move(public_path('upload/admin_user'),$filename);
               $user->image=$filename;
           }
           $user->name = $request->name;
           $user->mobile = $request->mobile;
           $user->email = $request->email;
           $user->address = $request->address;
          
           $user->save();
           $notification=array(
                   'message'=>"Update Success",
                   'alert-type'=>'success'
               );
            DB::commit();
           return redirect()->route('admin.user.index')->with($notification);

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

    function ChangePass($id){
        $data['user']=Admin::find($id);
        $data['is_profile']=0;
        return  view('admin.user.change_pass',$data);
    }
    function ChangePassP($id){
        $data['is_profile']=1;
        $data['user']=Admin::find($id);
        return  view('admin.user.change_pass',$data);
    }
    function ChangePassPost(Request $request, $id){
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
            $user = Admin::find($id);
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
           return redirect()->route('admin.user.index')->with($notification);

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
    function updateStatus($id){
        $user = Admin::find($id);
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
    
        return redirect()->route('admin.user.index')->with($notification);
    }

    function delete($id){
        $user = Admin::find($id);
        $user->delete();
        $notification=array(
            'message'=>"Delete Success",
            'alert-type'=>'success'
        );
    
        return redirect()->route('admin.user.index')->with($notification);
    }
}
