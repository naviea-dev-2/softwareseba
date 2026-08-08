<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Attendance;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\Employee;
use App\Models\Hr\Shift;
use App\Models\Hrleave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;

use Redirect;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AttendanceSetting;
class EmployeeController extends Controller
{
    public function applyLeave(){

        $employee = DB::table('employee_info')->get();
        // $category = DB::table('category')->get(['cat_id','categoryName']);

        return view('Hr.leave.apply_leave',compact('employee'));
    }
    function apiLoginUser(Request $request){
         $mobile = preg_replace('/^(?:\+?880|0)?/','+880',$request->UserId);
        
        $user = User::where('mobile',$mobile)->orWhere('email',$request->UserId)->first();
        if($user){
            if (auth()->attempt(['email' => $request->UserId, 'password' => $request->Password])){
                return response()->json(['status' => 'yes',"msg"=>"Login is successfull","user_id"=>$user->business->id]);
            }
            else if (auth()->attempt(['mobile' => $mobile, 'password' => $request->Password])){
               
                return response()->json(['status' => 'yes',"msg"=>"Login is successfull","user_id"=>$user->business->id]);
            }else{
                return response()->json(['status' => 'no',"msg"=>"Your User ID is incorrect."]);
            }
        }else{
            return response()->json(['status' => 'no',"msg"=>"Your User ID is incorrect."]);
        }
    }
    public function storeLeaveApplication(Request $request){

        DB::table('leave')->insert([
            'empId' => $request->empId,
            'type'  => $request->type,
            'part'  => $request->part,
            'reason' => $request->reason,
            'address' => $request->address,
            'department'=>$request->department,
            'designation'=>$request->designation,
            // 'status' => $request->status,
            'from' => $request->from,
            'to' => $request->to,
            'day' => $request->day
        ]);

        return redirect('/manageLeaveApplications');
    }

    public function manageLeaveApplications(){
        $viewAll = DB::table('leave')->get();
        $employee = DB::table('employee_info')->get();
        return view('Hr.leave.manage',compact('viewAll','employee'));
    }

    public function manageAttendance(){
        if(can_p('manageAttendance') == false){
            return redirect()->route('dashboard');
        }
       // $viewAll =Hrleave::get();
        $employees = Employee::get();
       
        $departments = Department::get();
        $shifts = Shift::get();
        return view('Hr.attendance.manage',compact('departments','employees','shifts'));
    }

   
    function ajaxAttendance(Request $request){
        $columns = [
            'attendances.id',
            'employees.employee_name',
            'attendances.dutyDate',
            'shift.shiftName',
            'attendances.inTime',
            'attendances.outTime',
            'attendances.workingMiniute',
            'attendances.lateMiniute',
            'attendances.overtimeMiniute',
            'attendances.status',
        ];

        $totalData = Attendance::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = Attendance::leftJoin('shift','shift.id','attendances.shiftID')
                                ->leftJoin('employees','employees.id','attendances.empID');
        if(!empty($search)){

           $datalist =$datalist->where("employees.employee_name","LIKE","%{$search}%")
                    ->orWhere("shift.shiftName","LIKE","%{$search}%")
                    ->orWhere("attendances.dutyDate","LIKE","%{$search}%")
                    ->orWhere("attendances.inTime","LIKE","%{$search}%")
                    ->orWhere("attendances.outTime","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->select('attendances.*','employees.employee_name as e_name','shift.shiftName as s_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_delete = can_p('deleteAttendance');
            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i;
                $nestedData['employee_name'] = $data_v->e_name;
                $nestedData['shift_name'] = $data_v->s_name;
                $nestedData['date'] = $data_v->dutyDate;
                $nestedData['in_time'] = date("g:i a",strtotime($data_v->inTime));
                $nestedData['out_time'] = $data_v->outTime !=null ? date("g:i a",strtotime($data_v->outTime)) : '--';
                $nestedData['working_time'] = intval($data_v->workingMiniute/60).' h : '.intval($data_v->workingMiniute%60).' min';
                $nestedData['late_time'] = intval($data_v->lateMiniute/60).' h : '.intval($data_v->lateMiniute%60).' min';
                $nestedData['over_time'] = intval($data_v->overtimeMiniute/60).' h : '.intval($data_v->overtimeMiniute%60).' min';
                $nestedData['status'] = $data_v->status;
                $nestedData['options']='';
                if($p_delete){
                    $nestedData['options'] .= '<a style="padding: 0 3px;" data-action="'.route('deleteAttendance',$data_v->id).'" class="btn btn-danger del_hr_data" data-id="'.$data_v->id.'"><i style="margin: 0;padding: 0;font-size: 16px;line-height: 18px;" class="bx bx-trash"></i></a>';
                }
                
                $data[] = $nestedData;
                $i++;
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
    function apiStoreAtteandance(Request $request,$b_id){
        try{
            DB::beginTransaction();
            $records = $request->all();
            // return response()->json($records);
            foreach ($records as $data) {
                $a_date = \Carbon\Carbon::parse($data['check_time'])->format('Y-m-d');
                $employee = Employee::where("employee_id",$data['user_id'])->where("business_id",$b_id)->first();
                $shift = $employee->shift;
                if($shift){
                    // $shift_start_time = $shift->startTime;
                    // $shift_end_time = $shift->endTime;
                    $attendance = Attendance::where("empID", $employee->id)
                    ->where("shiftID", $shift->id)
                    ->where("dutyDate", $a_date)
                    ->where("business_id",$b_id)->first();
                    if($attendance){

                        $startTime = Carbon::parse($attendance->inTime);
                        $endTime = Carbon::parse($data['check_time']);
                        $hour=$startTime->diff($endTime)->format('%H');
                        $min=$startTime->diff($endTime)->format('%I');
                        $totalWorkingMinnute=($hour*60)+$min;  

                        $shift_endTime = Carbon::parse($a_date.' '.$shift->endTime);
                        $shift_startTime = Carbon::parse($a_date.' '.$shift->startTime);
                        $eheck_over_time_diff = $endTime->diff($shift_endTime);
                        $overtimeMiniute = 0;
                        $out_status = 1;
                        if($eheck_over_time_diff->invert > 0){
                            $hour=$endTime->diff($shift_endTime)->format('%H');
                            $min=$endTime->diff($shift_endTime)->format('%I');
                            $overtimeMiniute=($hour*60)+$min;
                        }else{
                            $eheck_early_time_diff = $shift_endTime->diff($endTime);
                            if($eheck_early_time_diff->invert > 0){
                                $out_status=2;
                            }
                        }

                        if($attendance->lateMiniute > 0 && $overtimeMiniute > 0){
                            $overtimeMiniute = $overtimeMiniute-$attendance->lateMiniute;
                            if($overtimeMiniute < 0){
                                $overtimeMiniute = 0;
                            }
                        }

                        $shift_hour=$shift_startTime->diff($shift_endTime)->format('%H');
                        $shift_min=$shift_startTime->diff($shift_endTime)->format('%I');
                        $totalShitWorkingMinute = ($shift_hour*60)+$shift_min;

                        $attendance->outTime = $data['check_time'];
                        $attendance->workingMiniute = $totalWorkingMinnute;
                        $attendance->overtimeMiniute = $overtimeMiniute;
                        $attendance->out_status = $out_status;

                        if($totalShitWorkingMinute > $totalWorkingMinnute){
                            $attendance->status="Partial";
                        }else{
                            if($attendance->lateMiniute > 0){
                                $attendance->status="Late";
                            }else{
                               $attendance->status="Ok"; 
                            }
                        }
                        $attendance->totalShitWorkingMinute = $totalShitWorkingMinute;
                        $attendance->save();
                    }else{
                        $shiftstart_time = Carbon::parse($a_date.' '.$shift->startTime);
                        $startTime = Carbon::parse($data['check_time']);
                        $eheck_time_diff = $startTime->diff($shiftstart_time);
                        $in_status = 1;
                        $lateMiniute = 0;
                        if($eheck_time_diff->invert > 0){
                            $in_status=2;
                            $hour=$startTime->diff($shiftstart_time)->format('%H');
                            $min=$startTime->diff($shiftstart_time)->format('%I');
                            $lateMiniute=($hour*60)+$min;
                        }
                        $attendance = new Attendance;
                        $attendance->empID=$employee->id;
                        $attendance->shiftID=$shift->id;
                        $attendance->dutyDate=$a_date;
                        $attendance->business_id=$b_id;
                        $attendance->lateMiniute = $lateMiniute;
                        $attendance->in_status=$in_status;
                        $attendance->status = "Processing..";
                        $attendance->inTime = $data['check_time'];
                        $attendance->save();
                    }
                }

               
            }
            DB::commit();
            return response()->json(['status' => 'success',"msg"=>"sss"]);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e);
            return response()->json([
                'status'=>'no',
                'msg'=>$e->getMessage()
            ]);
        }
    }
    public function attendanceStoreIn(Request $request){
        if(can_p('attendanceStoreIn') == false){
            $data["msg"] ='Attendance In permision is not allowed';
            $data["error"] ='Attendance In permision is not allowed';
            $data["status"] ="0";
            return response()->json($data);
        }
        $validator = Validator::make($request->all(),[
            'empID'=>'required',
            'shiftID'=>'required',
            'dutyDate'=>'required',
            'inTime'=>'required',
        ]);
        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            $attendance = Attendance::where('dutyDate', date("Y-m-d", strtotime($request->dutyDate)))->where('empID',$request->empID)->first();
            if($attendance == null){
                $attendance = new Attendance();
            }
            $shift = Shift::find($request->shiftID);
            $check_in_time_pre = Carbon::parse($request->dutyDate.' '.$shift->startTime);
            $check_in_time_pre1 = Carbon::parse($request->dutyDate.' '.$shift->startTime);
            $emp_in_time = Carbon::parse($request->dutyDate.' '.$request->inTime);

            $attendance_setting = AttendanceSetting::first();
            if($attendance_setting){
                $check_in_time_pre = Carbon::parse($request->dutyDate.' '.$attendance_setting->delay_time);
            }
            
            $eheck_time_diff = $emp_in_time->diff($check_in_time_pre);
            $lateMiniute = 0;
            $in_status=1;
            $status = "Ok";
            if($eheck_time_diff->invert > 0){
                $in_status=2;
                $hour = $emp_in_time->diff($check_in_time_pre1)->format('%H');
                $min  = $emp_in_time->diff($check_in_time_pre1)->format('%I');
                $lateMiniute=($hour*60)+$min;
                $status = "Late";
            }
            $attendance->empID=$request->empID;
            $attendance->shiftID=$request->shiftID;
            $attendance->dutyDate=$request->dutyDate;
            $attendance->inTime= Carbon::parse($request->dutyDate.' '.$request->inTime);
            $attendance->workingMiniute=0;
            $attendance->lateMiniute=$lateMiniute;
            $attendance->in_status=$in_status;
            $attendance->overtimeMiniute=0;
            $attendance->status = $status;
            $attendance->save();
            
            DB::commit();
            return response([
                'status' => 1,
                'success' => 'Save successfully.',
            ]);
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
    public function attendanceStoreOut(Request $request){
        if(can_p('attendanceStoreOut') == false){
            $data["msg"] ='Attendance Out permision is not allowed';
            $data["error"] ='Attendance Out permision is not allowed';
            $data["status"] ="0";
            return response()->json($data);
        }
         $validator = Validator::make($request->all(),[
            'empID'=>'required',
            'outTime'=>'required',
            'dutyDate'=>'required',
        ]);
        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }

        try{
            DB::beginTransaction();
            $attendance=Attendance::where('dutyDate', date("Y-m-d", strtotime($request->dutyDate)))->where('empID',$request->empID)->first();
            if($attendance){
                // start find working minutes, late, overTime etc
                $startTime = Carbon::parse($attendance->inTime);
                $endTime = Carbon::parse($request->dutyDate.' '.$request->outTime);
                // $totalDuration =  $startTime->diff($endTime)->format('%H:%I:%S')." Minutes";
                // return response()->json(['data'=>$startTime->diff($endTime)]);
                $hour=$startTime->diff($endTime)->format('%H');
                $min=$startTime->diff($endTime)->format('%I');

                $totalWorkingMinnute=($hour*60)+$min;

                $shift = $attendance->shift;
                $shift_startTime = Carbon::parse($request->dutyDate.' '.$shift->startTime);
                $shift_endTime = Carbon::parse($request->dutyDate.' '.$shift->endTime);
                //return response()->json(['data'=>$endTime->diff($shift_endTime)]);
                $eheck_over_time_diff = $endTime->diff($shift_endTime);
                $overtimeMiniute = 0;
                $out_status = 1;
                if($eheck_over_time_diff->invert > 0){
                    $hour=$endTime->diff($shift_endTime)->format('%H');
                    $min=$endTime->diff($shift_endTime)->format('%I');
                    $overtimeMiniute=($hour*60)+$min;
                }else{
                    $eheck_early_time_diff = $shift_endTime->diff($endTime);
                    if($eheck_early_time_diff->invert > 0){
                        $out_status=2;
                    }
                }

                $shift_hour=$shift_startTime->diff($shift_endTime)->format('%H');
                $shift_min=$shift_startTime->diff($shift_endTime)->format('%I');
                $totalShitWorkingMinute = ($shift_hour*60)+$shift_min;

                // $data=  Attendance::find($hascheck->id);
                $attendance->empID=$request->empID;
                $attendance->dutyDate=$request->dutyDate;
                $attendance->outTime= Carbon::parse($request->dutyDate.' '.$request->outTime);
                $attendance->workingMiniute=$totalWorkingMinnute;

                if($attendance->lateMiniute > 0 && $overtimeMiniute > 0){
                    $overtimeMiniute = $overtimeMiniute-$attendance->lateMiniute;
                    if($overtimeMiniute < 0){
                        $overtimeMiniute = 0;
                    }
                }
                $attendance->overtimeMiniute=$overtimeMiniute;
                $attendance->out_status=$out_status;
                
                if($totalShitWorkingMinute > $totalWorkingMinnute){
                    if($attendance->lateMiniute > 0){
                        $attendance->status="Late";
                    }else{
                         $attendance->status="Partial";
                    }
                   
                }


                $attendance->totalShitWorkingMinute = $totalShitWorkingMinute;
                $attendance->save();


            }else{
                return response([
                    'status' => 0,
                    'error' => 'Attendance is not in for this date',
                ]);
            }

            DB::commit();
            return response([
                'status' => 1,
                'success' => 'Save successfully.',
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'data'=>$e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }
    }


    public function deleteAttendance($id){
        if(can_p('deleteAttendance') == false){
            return redirect()->route('dashboard');
        }
        Attendance::where('id',$id)->delete();
        return redirect()->route('manageAttendance');

    }
   
    public function getDesigName(Request $request)
    {
        $desigName =Designation::where("department_id",$request->deptID)->select('name', 'id')->get();
        return response()->json($desigName);
    }

    public function getEmployeeId(Request $request)
    {
        $empId = Employee::where("designation_id",$request->desigID)->select('employee_id', 'id')->get();
        return response()->json($empId);
    }

//     public function manageShift(){
//         // $employee = DB::table('employee_info')->get();
//         $shift = DB::table('shift')->get();
//         return view('Hr.shiftManage.manage',compact('shift'));
//     }

//     public function shiftManageStore(Request $request){
//         DB::table('shift')->insert([
//             'shiftName' =>$request->shiftName,
//             'startTime'=>$request->startTime,
//             'endTime'=>$request->endTime,
//         ]);

//         return redirect('/manageShift');
//     }

//      public function DeleteShift($id){
//         DB::table('shift')->where('id',$id)->delete();
//         return redirect('/manageShift');

//     }

//     public function editShift(Request $request, $id)
// {
//     $shift = DB::table('shift')->where('id', $id)->first();
//     return response()->json($shift);

// }

//     public function updateShift(Request $request, $id){
//     $id = $request->input('id');
//     $shiftName = $request->input('shiftName');
//     $startTime = $request->input('startTime');
//     $endTime = $request->input('endTime');

//     DB::table('shift')
//         ->where('id', $id)
//         ->update(['shiftName' => $shiftName, 'startTime' => $startTime, 'endTime' => $endTime]);

//         return redirect ('/manageShift')
//             ->with('msg', 'Employee Info updated Successfully');
// }


    public function viewAbsentRollSetup()
    {
        $absent = DB::table('absent')->get();

        return view('Hr.absentRollSetup.viewAbsentRollSetup',compact('absent'));
    }


    public function storeAbsentRollData(Request $request)
    {
        DB::table('absent')->insert([
            'firstAbsentAmount' =>$request->firstAbsentAmount,
            'otherAbsentAmount' =>$request->otherAbsentAmount,
        ]);

        return redirect('/viewAbsentRollSetup');
    }



    public function editAbsentRoll(Request $request, $id)
    {
    $absent = DB::table('absent')->where('id', $id)->first();
    return response()->json($absent);
    }

    public function updateAbsentRoll(Request $request, $id){
    $id = $request->input('id');
    $firstAbsentAmount = $request->input('firstAbsentAmount');
    $otherAbsentAmount = $request->input('otherAbsentAmount');

    DB::table('absent')
        ->where('id', $id)
        ->update(['firstAbsentAmount' => $firstAbsentAmount, 'otherAbsentAmount' => $otherAbsentAmount]);

        return redirect ('/viewAbsentRollSetup')
            ->with('msg', 'Absent Roll updated Successfully');
}

    public function manageAttendanceSorting(Request $req){
        $viewAll = DB::table('leave')->get();
        $employee = DB::table('employee_info')->get();
        $in = DB::table('attendanceIn')->whereBetween('dutyDate', [$req->min , $req->max])->get();
        $out = DB::table('attendanceOut')->get();

        return view('Hr.attendance.loadManageAttendance',compact('viewAll','employee','in','out'));
    }


}
