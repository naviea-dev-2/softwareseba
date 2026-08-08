<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(can_p('bussiness.role.index') == false){
            return redirect()->route('dashboard');
        }
        return view('role.index');
    }
    function ajaxRole(Request $request){
       // dd($request->all());
        $columns = [
            'id',
            'name',
        ];

        $totalData = Role::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = Role::query();
        if(!empty($search)){
           $datalist =$datalist->where("name","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('bussiness.role.edit');
            $p_delete = can_p('bussiness.role.delete');
            foreach ($datalist as $data_v) {
                $nestedData['id'] = $data_v->id;
                $nestedData['name'] = $data_v->name;
                if($p_edit){
                    $nestedData['options'] = '<a class="btn btn-primary data_edit me-1" href="'.route('bussiness.role.edit',$data_v->id).'"><i class="bx bx-edit"></i></a>';
                }
                if($p_delete){
                    $nestedData['options'] .= '<a href="#" data-id="'.$data_v->id.'" class="del_data_user btn btn-danger"><i class="bx bx-trash"></i></a>';
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(can_p('bussiness.role.add') == false){
            return redirect()->route('dashboard');
        }
        $data['role']=Role::findorNew(0);
        $data['menus']=Permission::where('is_caption','!=',0)->get();;
        return  view('role.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    function store(Request $request){
        $this->validate($request,[
           'name'=>'required',
       ]);
       try{
             DB::beginTransaction();

            $role = new Role;
            $role->name = $request->name;
            $role->save();
            foreach($request->menu_permission as $m_permission){
               $role_permission= new RolePermission;
               $role_permission->role_id= $role->id;    
               $role_permission->permission_id= $m_permission;   
               $role_permission->save(); 
            }
            $notification=array(
                'message'=>"Created Success",
                'alert-type'=>'success'
            );
            DB::commit();
           return redirect()->route('bussiness.role.index')->with($notification);

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
        if(can_p('bussiness.role.edit') == false){
            return redirect()->route('dashboard');
        }
        $data['role']=Role::find($id);
        $data['menus']=Permission::where('is_caption','!=',0)->get();;
        return  view('role.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    function update(Request $request, $id){
        if(can_p('bussiness.role.edit') == false){
            return redirect()->route('dashboard');
        }
        //dd($request->all());
        $this->validate($request,[
           'name'=>'required',
       ]);
       try{
            DB::beginTransaction();

            $role = Role::find($id);
            $role->name = $request->name;
            
            $role->save();
            if($request->menu_permission){
                foreach($request->menu_permission as $m_permission){
                    $role_permission= new RolePermission;
                    $role_permission->role_id= $role->id;    
                    $role_permission->permission_id= $m_permission;   
                    $role_permission->save(); 
                }
            }
            if($request->delete_menu){
                foreach($request->delete_menu as $delete_menu){
                    $role_permission= RolePermission::where('role_id',$role->id)->where('permission_id',$delete_menu)->first();
                    if($role_permission){
                        $role_permission->delete(); 
                    }
                    
                }
            }
           
            $notification=array(
                   'message'=>"Update Success",
                   'alert-type'=>'success'
               );
            DB::commit();
           return redirect()->route('bussiness.role.index')->with($notification);

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

    /**
     * Remove the specified resource from storage.
     */
    function delete($id){
        if(can_p('bussiness.role.delete') == false){
            return redirect()->route('dashboard');
        }
        $role = Role::find($id);
        $role->delete();
        $notification=array(
            'message'=>"Delete Success",
            'alert-type'=>'success'
        );
    
        return redirect()->route('bussiness.role.index')->with($notification);
    }
}
