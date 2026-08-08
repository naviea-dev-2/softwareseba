<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\LeavePart;
use App\Models\Hr\LeaveType;
use App\Models\HrPayroll\LeaveTagline;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\Employee;
use App\Models\Hr\LeaveApplication;
use App\User;
use Carbon\Carbon;

use Session;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class leaveApplicationController extends Controller
{
    public function view(){
        if(can_p('leaveApplication.view') == false){
            return redirect()->route('dashboard');
        }
        $data['leaveApplications']=LeaveApplication::orderBy('id','DESC')->get();
        //$data['empInfo']=Employee::where('id',Auth::user()->empID)->first();
        $data['leaveTypes']=LeaveType::orderBy('id','DESC')->get();
        $data['departments']=Department::orderBy('id','DESC')->get();
        return view ('Hr.leave.leaveApplication',$data);
    }
    function ajaxLeave(Request $request){
        $columns = [
            'leave_applications.id',
            'employees.employee_name',
            'leave_applications.leaveTypeID',
            'leave_applications.leavePartID',
            'leave_applications.fromDate',
            'leave_applications.toDate',
            'leave_applications.leaveDay',
            'leave_applications.status',
        ];

        $totalData = LeaveApplication::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = LeaveApplication::leftJoin('employees','employees.id','leave_applications.empID')
        ->leftJoin('leave_parts','leave_parts.id','leave_applications.leavePartID')
        ->leftJoin('leave_types','leave_types.id','leave_applications.leaveTypeID');
        if(!empty($search)){

           $datalist =$datalist->where("employees.employee_name","LIKE","%{$search}%")
                    ->orWhere("leave_types.leaveCode","LIKE","%{$search}%")
                    ->orWhere("leave_parts.levaePartName","LIKE","%{$search}%")
                    ->orWhere("leave_applications.fromDate","LIKE","%{$search}%")
                    ->orWhere("leave_applications.toDate","LIKE","%{$search}%")
                    ->orWhere("leave_applications.leaveDay","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->select('leave_applications.*','employees.employee_name','leave_types.leaveCode','leave_parts.levaePartName')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('leaveApplication.edit');
            $p_delete = can_p('leaveApplication.delete');
            $p_slip = can_p('salary.slip.fetch');

            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i++;
                $nestedData['e_name'] = $data_v->employee_name;
                $nestedData['leave_type'] = $data_v->leaveCode;
                $nestedData['leave_part'] = $data_v->levaePartName;
                $nestedData['from_date'] = date('F j, Y',strtotime($data_v->fromDate));
                $nestedData['to_date'] =date('F j, Y',strtotime($data_v->toDate));
                $nestedData['day'] = $data_v->leaveDay;

                $status = '';
                if($data_v->status == 0){
                    $status ='<span style="font-weight: bold;">Pending</span>';
                }else if($data_v->status == 1){
                    $status ='<span style="font-weight: bold;">Approved</span>';
                }else{
                    $status ='<span style="color:red;font-weight: bold;">Reject</span>';
                }
                $nestedData['status']=$status;

                $nestedData['options']='';
                if($p_edit){
                    if($data_v->status==0){
                        $nestedData['options'] .= '<button style="padding: 1.5em 5px 0.7em 10px;" class="btn btn-primary leaveApplicationEdit" data-token="'.csrf_token().'" data-id="'.$data_v->id.'" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="bx bx-edit"></i></button>';
                    }
                                    
                    // $nestedData['options'] .= '<a style="padding: 1.5em 5px 0.7em 10px;" class="btn btn-primary me-1 mb-1" href="javascript:void(0)" data-token="'.csrf_token().'" id="bonuspayEdit" data-id="'.$data_v->id.'"><i class="bx bx-edit"></i></a>';
                }
               
                if($p_delete){
                    $nestedData['options'] .= '<a style="padding: 1.5em 5px 0.7em 10px;" data-action="'.route('leaveApplication.delete',$data_v->id).'" data-id="'. $data_v->id .'" class="del_hr_data btn btn-danger" href="#"><i class="bx bx-trash"></i></a>';
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
    public function store(Request $request)
    {
        if(can_p('leaveApplication.add') == false){
            return redirect()->route('dashboard');
        }
        $validator = Validator::make($request->all(),[
            'empID'=>'required',
            'leaveTypeID'=>'required',
            'leavePartID'=>'required',
            'fromDate'=>'required',
            'toDate'=>'required',
            'purpose'=>'required',
        ],[
            'empID.required'=> 'Employee is required',
            'leaveTypeID.required'=> 'Department is required',
            'leavePartID.required'=> 'Designation is required',
            'fromDate.required'=> 'Employee is required',
            'toDate.required'=> 'Department is required',
            'purpose.required'=> 'Designation is required',
        ]);
        if ($validator->fails()) {
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }

        try{
            DB::beginTransaction();

            date_default_timezone_set('Asia/Dhaka');

            $levelPart=LeavePart::where('id',$request->leavePartID)->first();
            // calculate day of leave
            $to =Carbon::createFromFormat('Y-m-d', $request->toDate);
            $from =Carbon::createFromFormat('Y-m-d', $request->fromDate);
            $dateDiff = $to->diffInDays($from);
            $dateDiff+=1;
            $leaveDay=$dateDiff*$levelPart->day;
            $employee=Employee::where('id',$request->empID)->first();

            $data=new LeaveApplication();
            $data->empDeptID=$employee->department_id;
            $data->empDesigID=$employee->designation_id;
            $data->empID=$employee->id;
            $data->leaveTypeID=$request->leaveTypeID;
            $data->leavePartID=$request->leavePartID;
            $data->fromDate=$request->fromDate;
            $data->toDate=$request->toDate;
            $data->purpose=$request->purpose;
            // $data->address=$request->address;
            $data->leaveDay=$leaveDay;
            // $data->dcEmpDeptID=Department::where('id',$request->dcEmpDeptID)->first()->name;
            // $data->dcEmpDesigID=Designation::where('id',$request->dcEmpDesigID)->first()->name;
            // $data->dcEmpID=$request->dcEmpID;
            $data->status=0;
            $data->save();
            DB::commit();

            return response([
                        'status' => 1,
                        'success' => 'Save successfully.',
                    ]);

            //return redirect()->route('leaveApplication.view')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return response([
                'status' => 0,
                'data'=>$e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }

    }
    

    public function search(Request $request){
        date_default_timezone_set('Asia/Dhaka');

        $Fromdate=date("Y-m-d", strtotime($request->fDate));
        $Todate=date("Y-m-d", strtotime($request->tDate."+1 day"));

        if($request->leaveType==0){
            $data['leaveApplications']=LeaveApplication::where('fromDate','>=',$Fromdate)->where('toDate','<=',$Todate)->orderBy('id','DESC')->get();
        }
        else{
            $data['leaveApplications']=LeaveApplication::where('fromDate','>=',$Fromdate)->where('toDate','<=',$Todate)->where('leaveTypeID',$request->leaveType)->orderBy('id','DESC')->get();
        }

        // $data['empInfo']=Employee::where('empID',Auth::user()->empID)->first();
        $data['leaveTypes']=LeaveType::orderBy('id','DESC')->get();
        $data['departments']=Department::orderBy('id','DESC')->get();



        return view ('Hr.leave.leaveApplication',$data,['Fromdate'=>$Fromdate,'Todate'=>$Todate]);

    }
    public function update(Request $request){
        if(can_p('leaveApplication.edit') == false){
            return redirect()->route('dashboard');
        }
        //dd($request->all());
        $data=LeaveApplication::find($request->leaveApplicationID);
        if($data){
            $data->status=$request->status;
            $data->save();
        }

        $notification=array(
            'message'=>"Save Success",
            'alert-type'=>'success'
         );

        return redirect()->back()->with($notification);
    }
    public function leavePartID_callByLeaveTYpe(Request $request){

        if (!$request->id) {
           $html ='Sorry';
        } else {

           $leaveParts=DB::table('leave_taglines')
                         ->join('leave_parts','leave_taglines.leavePartID','leave_parts.id')
                         ->where('leave_taglines.leaveTypeID',$request->id)
                         ->select('leave_parts.*')
                         ->get();


           $html='<option>-- Select One --</option>';

            foreach($leaveParts as $leavePart){

                    $html.='<option value="'.$leavePart->id.'">'.$leavePart->levaePartName.'</option>';

            }
        }

        return response()->json(['html' => $html]);
    }
    public function singleView(Request $request){
        if(can_p('leaveApplication.edit') == false){
            return response()->json(['status'=>'no','msg' =>'Edit permission is not allowed']);
        }
        if (!$request->id) {
          
           return response()->json(['status'=>'no','msg' =>'Not Fount']);
        }
        else {

            $leaveApplication=LeaveApplication::where('id',$request->id)->first();

            $viewApplicationData='Name: '.Employee::where('id',$leaveApplication->empID)->first()->employee_name.'<br/><br/>ID: '.$leaveApplication->empID.'<br/><br/>Department: '.$leaveApplication->empDeptID.'<br/><br/>Designation: '.$leaveApplication->empDesigID.'<br/><br/> Leave Type: '.$leaveApplication->leaveTypeID.'<br/><br/> Leave Part: '.$leaveApplication->leavePartID.'<br/><br/> Day: '.$leaveApplication->leaveDay.'<br/><br/> leave Spend :'.LeaveApplication::where('empID',$leaveApplication->empID)->where('status',1)->where('leaveTypeID',$leaveApplication->leaveTypeID)->sum('leaveDay').'<br/><br/> Start Date :'.date('F j, Y',strtotime($leaveApplication->fromDate)).'<br/><br/> End Date: '.date('F j, Y',strtotime($leaveApplication->toDate)).'<br/><br/> Purpose: '.$leaveApplication->purpose.'<br/><br/>Address:'.$leaveApplication->address.'<br/><br/>Duty cover Employee Name: '.Employee::where('id',$leaveApplication->dcEmpID)->first()->employee_name.'<br/><br/>Duty cover Employee Id: '.$leaveApplication->dcEmpID.'<br/><br/> Duty cover Employee Department: '.$leaveApplication->dcEmpDeptID.'<br/><br/>Duty cover Employee Designation: '.$leaveApplication->dcEmpDesigID.'<br/><br/>';
        }

        return response()->json(['status'=>'ok','viewApplicationData' => $viewApplicationData,'leaveApplicationID'=>$request->id]);
    }
    function delete($id){
         if(can_p('leaveApplication.delete') == false){
            return redirect()->route('dashboard');
        }
        $data=LeaveApplication::find($id);
        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

        return redirect()->route('leaveApplication.view')->with($notification);
    }

}
