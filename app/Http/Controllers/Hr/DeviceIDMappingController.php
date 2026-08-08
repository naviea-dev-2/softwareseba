<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceMapping;  
use App\Models\BusinessDevice;  
use App\Models\Hr\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class DeviceIDMappingController extends Controller
{
    function index(){
        // device_mappings
        return view("Hr.device_mapping.index");
    }
    function ajaxDeviceMapping(Request $request){
        $b_type_id = auth()->user()->business->business_type_id;
       
        $columns = array(
            0 => 'device_mappings.id',
            1 => 'employees.employee_id',
            2 => 'employees.employee_name',
            3 => 'designations.name',
            4 => 'departments.name',
            5=> 'device_mappings.device_id',
            6 => 'device_mappings.is_done',
            7 => 'options',
        );
      
        

        $totalData = DeviceMapping::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        
        $device_mappings = DeviceMapping::leftjoin('employees','employees.id','device_mappings.emp_id')
        ->leftjoin('designations','designations.id','employees.designation_id')
        ->leftjoin('departments','departments.id','employees.department_id');
        if(!empty($search))
        {
            $device_mappings = $device_mappings->where(function($q) use($search){
                $q->where("employees.employee_id","LIKE","%{$search}%")
                ->orWhere("employees.employee_name","LIKE","%{$search}%")
                ->orWhere("designations.name","LIKE","%{$search}%")
                ->orWhere("departments.name","LIKE","%{$search}%")
                ->orWhere("device_mappings.device_id","LIKE","%{$search}%");
            });



        }
        $totalFiltered = $device_mappings->count();
        $device_mappings = $device_mappings->select('device_mappings.*','employees.employee_id',"employees.employee_name","designations.name as des_name","departments.name as dept_name")->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        
        $data = array();
        if(!empty($device_mappings))
        {
            $i = $start == 0 ? 1 : $start+1;
            // $p_edit = can_p('invoice.edit');
            // $p_delete = can_p('invoice.delete');
            // $p_view = can_p('invoice.view');
            // $p_add_payment = can_p('invoice.add-payment');
            // $p_payment_show = can_p('invoice.payment_show');
            // $p_sales_return = can_p('invoice_return.add');
            // $p_print= can_p('invoice.print');
            foreach($device_mappings as $device_mapping)
            {
                $nestedData['id'] = $i++;
                $nestedData['emp_id'] = $device_mapping->employee_id;
                $nestedData['name'] = $device_mapping->employee_name;
                $nestedData['des'] = $device_mapping->des_name;
                $nestedData['dept'] = $device_mapping->dept_name;
                $nestedData['device_id'] = $device_mapping->device_id;

                $nestedData['is_conn'] = $device_mapping->is_done == 2 ? '<div class="badge bg-success">YES</div>' : '<div class="badge bg-danger">NO</div>';
                $nestedData['options'] = '<a href="javascript:void(0)" data_id="'. $device_mapping->id .'" class="btn btn-primary edit_data"><i class="bx bx-edit"></i></a>';
                $nestedData['options'] .= '<a del_data="'. $device_mapping->id .'" class="m-1 del_hr_data btn btn-danger "><i class="bx bx-trash"></i></a>';

                // $nestedData['options'] = ' <li><a href="'. route('invoice.edit', $device_mapping->id).'" class="btn btn-link"><i class="bx bx-edit"></i> Edit</a></li>';
                // $nestedData['options'] .= ' <li> <form action="'. route('invoice.delete',$device_mapping->id).'" method="post"><input type="hidden" name="_token" value="'. csrf_token().'"><button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="bx bx-trash"></i>Delete</button></form></li>';
                

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
    public function store(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'shift'=>'required',
            'employee'=>'required',
            'device_id'=>'required'
            ],[
            'shift.required'=> 'Shift is required',
            'employee.required'=> 'Employee is required',
            'device_id.required'=> 'Device ID is required',
        ]);
        if($validator->fails()){
            return response([
                'status' => "error",
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            $business_device = BusinessDevice::where('device_no',$request->device_id)->first();
            // dd($business_device);
            if($request->id==0){
                $chk_device = DeviceMapping::where("emp_id",$request->employee)->first();
                if($chk_device){
                    return response([
                        'status' => "no",
                        'msg' => 'Already Exists.',
                    ]);
                }
                $data=new DeviceMapping();
            }
            else{
                // $chk_device = DeviceMapping::where("emp_id",$request->employee)->where('id',"!=",$request->id)->first();
                // if($chk_device){
                //     return response([
                //         'status' => "no",
                //         'msg' => 'Already Exists.',
                //     ]);
                // }
                $data=DeviceMapping::find($request->id);
            }
            $data->emp_id=$request->employee;
            $data->device_sn=$business_device->sn;
            $data->device_id=$request->device_id;
            $data->is_done=$request->status;
            $data->save();

            DB::commit();
            if($request->id==0){
                return response([
                    'status' => "yes",
                    'msg' => 'Device ID add successfully.',
                ]);
            }else{
                return response([
                    'status' => "yes",
                    'msg' => 'Device ID update successfully.',
                ]);
            }
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            return response([
                'status' => "no",
                'msg' => 'Something went Wrong!',
            ]);
        }

        //  $notification=array(
        //     'message'=>"Save Success",
        //     'alert-type'=>'success'
        //  );

        // return redirect()->route('shiftManage.view')->with($notification);
    }
    function edit($id){
        $deviceMapping = DeviceMapping::with(['employee.shift'])->find($id);
        return response()->json(['status'=>'yes',"device_mapping"=>$deviceMapping]);
    }
    public function delete(Request $request){
        try{
            DB::beginTransaction();
            $data=DeviceMapping::find($request->id);
            $data->delete();
            DB::commit();
            return response()->json(['status'=>'yes',"msg"=>"Deleted successfully."]);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            return response([
                'status' => "no",
                'msg' => 'Something went Wrong!',
            ]);
        }

    }
    function dataPushAttendance($b_id,$m_id){
        \Log::info("push received:");
         return response('OK', 200)->header('Content-Type', 'text/plain');
        
    }
    function getUserRequest($b_id,$m_id){
         $device_mappings =DeviceMapping::leftJoin('employees',"employees.id","device_mappings.emp_id")
        ->where("device_mappings.business_id",$b_id)
        ->where("device_mappings.device_id",$m_id)
        ->where('device_mappings.is_done',0)
        ->select('employees.employee_id as userId',"employees.employee_name as name")->take(10)->get();
        $commands=[];
        foreach($device_mappings as $device_mapping){
            array_push($commands,"CMD=USER ADD PIN={$device_mapping->userId} Name={$device_mapping->name} Privilege=0 Password=");
        }
        \Log::info("ull received:");
         return response(implode("\n", $commands), 200)
                ->header('Content-Type', 'text/plain');
    }
    function getDataQueueData($b_id,$m_id){
        $device_mappings =DeviceMapping::leftJoin('employees',"employees.id","device_mappings.emp_id")
        ->where("device_mappings.business_id",$b_id)
        ->where("device_mappings.device_id",$m_id)
        ->where('device_mappings.is_done',0)
        ->select('employees.employee_id as userId',"employees.employee_name as name")->take(10)->get()->toArray();
        return $device_mappings;
    }
    function dataMarkDone($b_id,$u_id){
       
        $employee = Employee::where("employee_id",$u_id)->where("business_id",$b_id)->first();
        $deviceIdMapping = DeviceMapping::where("business_id",$b_id)->where("emp_id",$employee->id)->first();
        $deviceIdMapping->is_done = 1;
        $deviceIdMapping->save();
        
        
        return response()->json(['status'=>"ok"]);
    }
}