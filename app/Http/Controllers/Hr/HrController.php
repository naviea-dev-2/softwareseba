<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\BalanceAccount;
use App\Models\Account\PaymentMethod;
use App\Models\Hr\Absent;
use App\Models\Hr\Attendance;
use App\Models\Hr\Bank;
use App\Models\Hr\BankAccount;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\EmpBankAccount;
use App\Models\Hr\EmpLoan;
use App\Models\Hr\Employee;
use App\Models\Hr\Hrleave;
use App\Models\Hr\LateRoll;
use App\Models\Hr\LeaveApplication;
use App\Models\Hr\MonthManage;
use App\Models\Hr\Overtime;
use App\Models\Hr\Payment_range;
use App\Models\Hr\Payroll;
use App\Models\Hr\SalarySheet;
use App\Models\Hr\Holiday;
use App\Models\OnlinePaymentSetting;
use App\Models\AttendanceSetting;
use App\Models\Inventory\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Library\SslCommerz\SslCommerzNotification;
class HrController extends Controller
{
    public function agentHiring(){
        return view('Hr.agent.agenthiring');
    }
     public function getStockData(Request $req) {

        // $packageId = DB::table('cart')->pluck('package_id')->all();

        // ->where('status' , '0')

        $package_info = DB::table('package_info')
        ->get();

        $user = DB::table('user')->get();
        $packageList = DB::table('packagelisting')->get();
        $cart = DB::table('cart')->get();

        return view('Hr.stock.manageStock', compact('package_info' , 'user' , 'packageList' , 'cart'));

    }

    public function storeAgentData(Request $request){
        $agent =  DB::table('user')->insert([
            'name'=> $request->name,
            'email' =>$request->email,
            'phone'=>$request->phone,
            'role'=>'2',
            'password'=>md5($request->password)
        ]);

            $userData = DB::table('user')
            ->where('email' , $request->email)
            ->where('password' , md5($request->password))
            ->first();

        DB::table('agent_info')->insert([
            'userId'=>$userData->id,
            'license' =>$request->license,
            'education'=>$request->education,
            'specialist'=>$request->specialist
        ]);

        return redirect()->route('manageAgent');
    }

    public function manageAgent(){

        $agentUser = Db::table('user')->where('role','=','2')->get();

        $getAgentData = DB::table('agent_info')
        ->join('user','user.id','=','agent_info.userId')
        ->get();

        return view('Hr.agent.manageAgentinfo',compact('getAgentData'));
    }

    public function editAgentHiring($id){
        $agent_info = DB::table('agent_info')
        ->join('user','user.id','=','agent_info.userId')
        ->where('agent_id',$id)
        ->get();

        return view('Hr.agent.editAgenthiring',compact('agent_info'));

    }

    public function updateAgentInfo(Request $request, $id){

        $data = DB::table('agent_info')
        ->join('user','user.id','=','agent_info.userId')
        ->where('agent_id',$id)
        ->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'license' =>$request->license,
            'education'=>$request->education,
            'specialist'=>$request->specialist
        ]);

        // echo $data;

        return redirect()->route('manageAgent');
    }

    public function deleteAgentData($id){

        // echo $data;

        $td = DB::table('agent_info')->where('agent_id',$id)->first();

        $data = DB::table('user')
        ->where('id',$td->userId)
        ->delete();

        DB::table('agent_info')
        ->where('agent_id',$id)
        ->delete();


        return redirect()->route('manageAgent');

    }

    public function viewDepartment(){
        if(can_p('shiftManage.view') == false){
            return redirect()->route('dashboard');
        }
        $departmentData = Department::get();

        return view('Hr.department.allDepartment',compact('departmentData'));
    }

    public function addDepartment(){
        if(can_p('addDepartment') == false){
            return redirect()->route('dashboard');
        }
        return view('Hr.department.adddepartment');
    }

    public function storeDeptData(Request $request){
        if(can_p('addDepartment') == false){
            return redirect()->route('dashboard');
        }
        $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('departments')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business->id);
                }),
            ],
        ]);
        $department = new Department;
        $department->name = $request->name;
        $department->save();

        return redirect()->route('viewDepartment');
    }

    public function editDepartment($id){
        if(can_p('editDepartment') == false){
            return redirect()->route('dashboard');
        }
        $dept = Department::find($id);
        return view('Hr.department.editdepartment',compact('dept'));
    }

    public function updateDepartment(Request $request, $id){
        if(can_p('editDepartment') == false){
            return redirect()->route('dashboard');
        }
        $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('departments')->where(function ($query) use ($id) {
                    return $query->where('id', '!=', $id)
                        ->where('business_id', auth()->user()->business->id);
                }),
            ],
        ]);
        $department =  Department::find($id);
        $department->name = $request->name;
        $department->save();

        return redirect()->route('viewDepartment');
    }

    public function deleteDept($id){
        if(can_p('deleteDept') == false){
            return redirect()->route('dashboard');
        }
        Department::where('id',$id)->delete();
        return redirect()->route('viewDepartment');

    }
    function select2Department(Request $request){
        $datas = Department::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->name];
        }
        return json_encode($res);
    }
    public function viewDesignation(){
        if(can_p('viewDesignation') == false){
            return redirect()->route('dashboard');
        } 
        $designationData = Designation::get();

        return view('Hr.designation.allDesignation',compact('designationData'));
    }

    public function addDesignation(){
        if(can_p('addDesignation') == false){
            return redirect()->route('dashboard');
        }
        $departments = Department::get();
        return view('Hr.designation.adddesignation',compact('departments'));
    }

    public function storeDesgData(Request $request){
        if(can_p('addDesignation') == false){
            return redirect()->route('dashboard');
        }
        $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('designations')->where(function ($query) use($request){
                    return $query->where('business_id', auth()->user()->business->id)
                    ->where('department_id', $request->department_id);
                }),
            ],
             'department_id' => 'required'
        ]);
        $designation = New Designation;
        $designation->name = $request->name;
        $designation->department_id = $request->department_id;
        $designation->type = $request->type ?? 0;
        $designation->save();

        return redirect()->route('viewDesignation');
    }

    public function editDesignation($id){
        if(can_p('editDesignation') == false){
            return redirect()->route('dashboard');
        }
        $departments = DB::table('departments')->get();

        $designation = Designation::where('id',$id)->first();
        return view('Hr.designation.editdesignation',compact('designation','departments'));

    }

    public function updateDesignation(Request $request, $id)
    {
        if(can_p('editDesignation') == false){
            return redirect()->route('dashboard');
        }
         $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('designations')->where(function ($query) use ($id,$request) {
                    return $query->where('id', '!=', $id)
                        ->where('business_id', auth()->user()->business->id)
                        ->where('department_id', $request->department_id);
                }),
            ],
            'department_id' => 'required'
        ]);
        $designation =  Designation::find($id);
       // dd($designation);
        $designation->name = $request->name;
        $designation->department_id = $request->department_id;
        $designation->type = $request->type ?? 0;
        $designation->save();

        return redirect()->route('viewDesignation')->with('msg', 'Designation Updated Successfully');;
    }

    public function DeleteDesg($id){
        if(can_p('deleteDesg') == false){
            return redirect()->route('dashboard');
        }
        Designation::where('id',$id)->delete();
        return redirect()->route('viewDesignation');

    }
    function select2Designation(Request $request){
        $datas = Designation::select('id', 'name')->where('department_id',$request->dept_id)->where("name", "LIKE", "%$request->value%")->get();
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->name];
        }
        return json_encode($res);
    }
    function getEmpDesig(Request $request){
        $designation = DB::table('designations')
        ->where('department_id' , $request->data)
        ->get();

        foreach ($designation as $desg) {
            echo "<option value='$desg->id'>" . $desg->name . "</option>";
        }
    }
    function select2Employee(Request $request){
        $datas = Employee::select('id', 'employee_name',"mobile","employee_id")
        ->where(function($q) use($request){
            $q->where("employee_name", "LIKE", "%$request->value%")
            ->OrWhere("employee_id", "LIKE", "%$request->value%")
            ->OrWhere("mobile", "LIKE", "%$request->value%");
        })
        ->get();
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->employee_name.($data->employee_id ? '('.$data->employee_id.')' : '')];
        }
        return json_encode($res);
    }
    public function allEmployee(){
        if(can_p('allEmployee') == false){
            return redirect()->route('dashboard');
        }
        return view('Hr.employee.manageEmployee');
    }
    function ajaxEmployee(Request $request){
        $columns = [
            0=>'employees.id',
            0=>'employees.id',
            2=>'employees.employee_id',
            3=>'employees.employee_name',
            4=>'departments.name',
            5=>'designations.name',
            6=>'employees.email',
            7=>'employees.mobile',
            8=>'employees.salary',
            9=>'employees.join_date',
        ];

        $totalData = Employee::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = Employee::leftJoin('departments','departments.id','employees.department_id')
                            ->leftJoin('designations','designations.id','employees.designation_id');
        if(!empty($search)){

           $datalist =$datalist->where("employees.employee_name","LIKE","%{$search}%")
                    ->orWhere("departments.name","LIKE","%{$search}%")
                    ->orWhere("designations.name","LIKE","%{$search}%")
                    ->orWhere("employees.employee_id","LIKE","%{$search}%")
                    ->orWhere("employees.email","LIKE","%{$search}%")
                    ->orWhere("employees.mobile","LIKE","%{$search}%")
                    ->orWhere("employees.salary","LIKE","%{$search}%")
                    ->orWhere("employees.join_date","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->select('employees.*','departments.name as depart_name','designations.name as desi_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('editEmployee');
            $p_delete = can_p('deleteEmployee');

            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i;
                $nestedData['employee_name'] = $data_v->employee_name;
                $nestedData['employee_code'] = $data_v->employee_id;
                $nestedData['image'] = '<img src="'.$data_v->employee_image_show.'" style="height:50px;width:50px;">';
                $nestedData['department'] = $data_v->depart_name;
                $nestedData['designation'] = $data_v->desi_name;
                $nestedData['email'] =$data_v->email;
                $nestedData['mobile'] =$data_v->mobile;
                $nestedData['join_date'] =$data_v->join_date;
                $nestedData['salary'] =auth()->user()->currency_symbol.' '. round($data_v->salary,2);

                $nestedData['options']='';
                if($p_edit){
                    $nestedData['options'] .= '<a href="'.route('editEmployee',$data_v->id).'" class="btn btn-primary "><i class="bx bx-edit"></i></a>';
                }
               
                if($p_delete){
                    $nestedData['options'] .= '<a del_data="'. $data_v->id .'" data-action="'.route('deleteEmployee',$data_v->id).'" class="m-1 del_hr_data btn btn-danger "><i class="bx bx-trash"></i></a>';
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

    public function addEmployee(){
        if(can_p('addEmployee') == false){
            return redirect()->route('dashboard');
        }
        $departments = Department::get();
        return view('Hr.employee.addEmployee',compact('departments'));
    }

    public function storeEmployee(Request $request){
        if(can_p('addEmployee') == false){
            return redirect()->route('dashboard');
        }
        $this->validate($request,[
            'department'=>'required',
            'designation'=>'required',
            'empName'=>'required',
            'fName'=>'required',
            'mName'=>'required',
            'cAddress'=>'required',
            'pAddress'=>'required',
            'dob'=>'required',
            'nationality'=>'required',
            'religion'=>'required',
            'nid'=>'required',
            'maritalStatus'=>'required',
            'gender'=>'required',
            'mobile'=>'required',
            'email'=>'required',
            'empID'=>'required',
            'salary'=>'required',
            'joinDate'=>'required',
            'image'=>'image|mimes:jpeg,png,jpg,webp',
        ],
        [
            'department.required'=> 'Department is required',
            'designation.required'=> 'Designation is required',
            'empName.required'=> 'Employee Name is required',
            'cAddress.required'=> 'Current Address is required',
            'pAddress.required'=> 'Present Address is required',
            'dob.required'=> 'Date Of Birth is required',
            'nationality.required'=> 'Nationality is required',
            'nid.required'=> 'NID is required',
            'maritalStatus.required'=> 'Marital Status is required',
            'gender.required'=> 'Gender is required',
            'mobile.required'=> 'Mobile is required',
            'email.required'=> 'Email is required',
            'empID.required'=> 'Employee ID is required',
            'joinDate.required'=> 'Join Date is required',
        ]
        );
        try{
            DB::beginTransaction();
            // mail sent with login info to employee

            $employeePassword = Str::random(8);

            // $userData=User::where('email',$request->email)->first();
            // if(!$userData){
            //     $userData=new User();
            // }

            // $userData->fname=$request->empName;
            // $userData->email=$request->email;
            // $userData->password=$employeePassword;
            // $userData->type=20;
            // $userData->empID=$request->empID;
            // $userData->save();
           //if($userData->save()){
                // $projectDomainName= $request->getSchemeAndHttpHost();
                // $userData->notify(new SendLoginInfoToEmployee($projectDomainName,$request->email,$request->empName,$employeePassword));
           // }

            // end mail sent with login info to employee
            $data=new Employee();
            // $data->user_id=$userData->id;
             $file=$request->file('image');
            if($file){
                $filename=date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('upload/Employee_Image'),$filename);
                $data->employee_image=$filename;
            }
            $data->shift_id =$request->shift ?? 0;
            $data->department_id =$request->department;
            $data->designation_id =$request->designation;
            $data->employee_name =$request->empName;
            $data->father_name =$request->fName;
            $data->mother_name =$request->mName;
            $data->cAddress=$request->cAddress;
            $data->pAddress=$request->pAddress;
            $data->date_of_birth=$request->dob;
            $data->nationality=$request->nationality;
            $data->religion=$request->religion;
            $data->nid_number=$request->nid;
            $data->bloodGroup=$request->bloodGroup;
            $data->maritalStatus=$request->maritalStatus;
            $data->gender=$request->gender;
            $data->mobile=$request->mobile;
            $data->officePhone=$request->officePhone;
            $data->email=$request->email;
            $data->employee_id=$request->empID;
            $data->salary=$request->salary;
            $data->join_date=$request->joinDate;
            $data->rejineDate=$request->rejineDate;
            $data->note=$request->note;
            $data->save();
            DB::commit();
            $notification=array(
                'message'=>"Emplopyee Add successfull",
                'alert-type'=>'success'
            );

                return redirect()->route('allEmployee')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
             $notification=array(
                'message'=>"something went wrong!",
                'alert-type'=>'error'
            );
          return redirect()->route('allEmployee')->withInput($request->all())->with($notification);
        }

    }

    public function editEmployee($id){
        if(can_p('editEmployee') == false){
            return redirect()->route('dashboard');
        }
        $employeeData =Employee::where('id',$id)->first();
        //dd($employeeData);
        $departments = Department::get();

        $emp_designations = Designation::where('department_id',$employeeData->department_id)->get();
       // dd($emp_designations);
        return view('Hr.employee.editEmployee',compact('employeeData','departments','emp_designations'));
    }

    public function updateEmployee(Request $request, $id){
        if(can_p('editEmployee') == false){
            return redirect()->route('dashboard');
        }
        $this->validate($request,[
            'department'=>'required',
            'designation'=>'required',
            'empName'=>'required',
            'fName'=>'required',
            'mName'=>'required',
            'cAddress'=>'required',
            'pAddress'=>'required',
            'dob'=>'required',
            'nationality'=>'required',
            'religion'=>'required',
            'nid'=>'required',
            'maritalStatus'=>'required',
            'gender'=>'required',
            'mobile'=>'required',
            'email'=>'required',
            'empID'=>'required',
            'salary'=>'required',
            'joinDate'=>'required',
            'image'=>'image|mimes:jpeg,png,jpg,webp',
      ], [
            'department.required'=> 'Department is required',
            'designation.required'=> 'Designation is required',
            'empName.required'=> 'Employee Name is required',
            'cAddress.required'=> 'Current Address is required',
            'pAddress.required'=> 'Present Address is required',
            'dob.required'=> 'Date Of Birth is required',
            'nationality.required'=> 'Nationality is required',
            'nid.required'=> 'NID is required',
            'maritalStatus.required'=> 'Marital Status is required',
            'gender.required'=> 'Gender is required',
            'mobile.required'=> 'Mobile is required',
            'email.required'=> 'Email is required',
            'empID.required'=> 'Employee ID is required',
            'joinDate.required'=> 'Join Date is required',
        ]);
        try{
             DB::beginTransaction();
            $data= Employee::find($id);
           
            // $userData=User::find($data->user_id);
            // $userData->fname=$request->empName;
            // $userData->email=$request->email;
            // $userData->save();
            $file=$request->file('image');
            if($file){
                $filename=date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('upload/Employee_Image'),$filename);
                @unlink(public_path('upload/Employee_Image/'.$data->image));
                $data->employee_image=$filename;
            }
            // $data->user_id=$userData->id;
            $data->shift_id =$request->shift ?? 0;
            $data->department_id =$request->department;
            $data->designation_id =$request->designation;
            $data->employee_name =$request->empName;
            $data->father_name =$request->fName;
            $data->mother_name =$request->mName;
            $data->cAddress=$request->cAddress;
            $data->pAddress=$request->pAddress;
            $data->date_of_birth=$request->dob;
            $data->nationality=$request->nationality;
            $data->religion=$request->religion;
            $data->nid_number=$request->nid;
            $data->bloodGroup=$request->bloodGroup;
            $data->maritalStatus=$request->maritalStatus;
            $data->gender=$request->gender;
            $data->mobile=$request->mobile;
            $data->officePhone=$request->officePhone;
            $data->email=$request->email;
            $data->employee_id=$request->empID;
            $data->salary=$request->salary;
            $data->join_date=$request->joinDate;
            $data->rejineDate=$request->rejineDate;
            $data->note=$request->note;
            //  dd($data);
            $data->save();
            DB::commit();
            $notification=array(
                'message'=>"Emplopyee Update successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('allEmployee')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            $notification=array(
                'message'=>"something went wrong!",
                'alert-type'=>'error'
            );
            return back()->with($notification)->withInput(request()->all());
        }
    }

    public function deleteEmployee($id){
        if(can_p('deleteEmployee') == false){
            return redirect()->route('dashboard');
        }
        try{
            Employee::where('id',$id)->delete();
            $notification=array(
                'message'=>"Emplopyee Deleted successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('allEmployee')->with($notification);
        }catch(\Exception $e){
            $notification=array(
                'message'=>"something went wrong!",
                'alert-type'=>'error'
            );
            return redirect()->route('allEmployee')->with($notification);
        }

    }
     
    function select2ShiftEmployee(Request $request){
        $datas = Employee::select('id', 'employee_name',"employee_id")->where('shift_id',$request->shift_id)
        ->where(function($q) use($request){
            $q->where("employee_name", "LIKE", "%$request->value%")
            ->orWhere("employee_id", "LIKE", "%$request->value%");
        })
       
        ->get();
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->employee_name." (".$data->employee_id.")"];
        }
        return json_encode($res);
    }
    function select2DsrEmployee(Request $request){
        $designations = Designation::where('type',1)->get()->pluck('id');
        $datas = Employee::select('id', 'employee_name')->whereIn('designation_id',$designations)->where("employee_name", "LIKE", "%$request->value%")->get();
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->employee_name];
        }
        return json_encode($res);
    }
    function select2AsrEmployee(Request $request){
        $designations = Designation::where('type',2)->get()->pluck('id');
        $datas = Employee::select('id', 'employee_name')->whereIn('designation_id',$designations)->where("employee_name", "LIKE", "%$request->value%")->get();
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->employee_name];
        }
        return json_encode($res);
    }
    function select2DriverEmployee(Request $request){
        $designations = Designation::where('type',3)->get()->pluck('id');
        $datas = Employee::select('id', 'employee_name')->whereIn('designation_id',$designations)->where("employee_name", "LIKE", "%$request->value%")->get();
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->employee_name];
        }
        return json_encode($res);
    }
    public function addLeave(){

        $employee = Employee::get();

        return view('Hr.leave.add_leave',compact('employee'));
    }

    public function storeLeave(Request $request){
        $this->validate($request,[
            'empId' => 'required',
            'type'=> 'required',
        ]);
        $leave = Hrleave::create([
            'employee_id' => $request->empId,
            'leave_type'  => $request->type,
            'reason' => $request->reason,
            'status' => $request->status
        ]);


        return redirect()->route('manageLeave');
    }

    public function manageLeave(){
        $viewAll = Hrleave::get();

        // $employee = DB::table('employee_info')->get();
        return view('Hr.leave.manage_leave',compact('viewAll'));
    }

    public function editLeave($id){
        $editData = Hrleave::find($id);
 $employee = Employee::get();
        return view('Hr.leave.edit_leave',compact('editData','employee'));
    }

    public function updateLeave(Request $request, $id){
         $this->validate($request,[
            'empId' => 'required',
            'type'=> 'required',
        ]);
        $leave = Hrleave::where('id',$id)->update([
            'employee_id' => $request->empId,
            'leave_type'  => $request->type,
            'reason' => $request->reason,
            'status' => $request->status
        ]);


        return redirect()->route('manageLeave');
    }

    public function deleteLeave($id){
       Hrleave::where('id',$id)->delete();
        return redirect()->route('manageLeave');

    }


    public function addSalary(){
        if(can_p('addSalary') == false){
            return redirect()->route('dashboard');
        }
        $employee = Employee::get();
        return view('Hr.salary.add_salary',compact('employee'));
    }
    function inital_account(){
        $salesHead = AccountHead::where("title",'Salary')->first();
        if($salesHead == null){
            $salesHead = new AccountHead;
            $salesHead->title = "Salary";
            $salesHead->code = '7010';
            $salesHead->sys = 0;
            $salesHead->ac_type = 7;
            $salesHead->note = '';
            $salesHead->status = 1;
            $salesHead->save();

        }
        $acPayableHead = AccountHead::where("title",'Account Payable')->first();
        if($acPayableHead == null){
            $acPayableHead = new AccountHead;
            $acPayableHead->code = '2000';
            $acPayableHead->title = "Account Payable";
            $acPayableHead->ac_type = 2;
            $acPayableHead->note = '';
            $acPayableHead->sys = 0;
            $acPayableHead->status = 1;
            $acPayableHead->save();
        }
    }
    public function storeSalary(Request $request){
        // dd(can_p('addSalary'));
        if(can_p('addSalary') == false){
            $data["msg"] ='Add permision is not allowed';
            $data["error"] ='Add permision is not allowed';
            $data["status"] ="0";
            return response()->json($data);
        }
        $this->inital_account();
    //    dd($request);
        $this->validate($request,[
            'monthDate'=>'required',
            'empID'=>'required',
            // 'deptID'=>'required',
            // 'desigID'=>'required',
        ]);
        try{
            DB::beginTransaction();

            $checkHas=SalarySheet::where('empID',$request->empID)->where('month',$request->monthDate)->first();
            // dd($checkHas);
            if(!$checkHas){
                $employee=Employee::where('id',$request->empID)->first();
                $payroll=Payroll::first();
                $month=MonthManage::where('monthDate',$request->monthDate)->first();

                $lastAttendance=Attendance::orderBy('dutyDate','DESC')->where('dutyDate','LIKE',$request->monthDate.'%')->first();

                $absentRoll=Absent::first();
                $overTimeRoll=Overtime::first();
                // normal salary calculation
                $basicSalary=$employee->salary;
                
                $house_rent = round($basicSalary*($payroll->house_rent/100),2);
                $medical_cost = round($basicSalary*($payroll->medical_cost/100),2);
                $transport_cost = round($basicSalary*($payroll->transport_cost/100),2);
                $tax = round($basicSalary*($payroll->tax/100),2);
                $provident_fund = round($basicSalary*($payroll->provident_fund/100),2);
                
                $totalDaySalary=($basicSalary+$house_rent+$medical_cost+$transport_cost)-($tax+$provident_fund);


                if($month){
                    $oneDaySalary=$totalDaySalary/$month->monthTotalDay;
                }else{
                    $oneDaySalary=$totalDaySalary/30;
                }
                // dd($oneDaySalary);
                if($lastAttendance == null){
                    $notification=array(
                        'message'=>"Employee Has No Attendance",
                        'alert-type'=>'error'
                    );
                    return back()->with($notification);
                }
               // dd("ss");
                // join date difference     find
                
                $to =Carbon::createFromFormat('Y-m-d', $request->monthDate.'-'.$month->monthTotalDay);

                $from =Carbon::createFromFormat('Y-m-d', $employee->join_date);
                
                $dateDiff = $from->diffInDays($to);
                $dateDiff+=1;
                // dd($dateDiff);

                $startDate=$request->monthDate.'-1';
                $startDate=Carbon::createFromFormat('Y-m-d', $startDate);
                $endDate=$request->monthDate.'-'.$month->monthTotalDay;
                $endDate=Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

                // if($from->month == $to->month && $dateDiff < )
                if($dateDiff>=$month->monthTotalDay){
                   // dd("sss");
                    $leaveDay=LeaveApplication::where('fromDate','>=',$startDate)->where('toDate','<=',$endDate)->where('empID',$request->empID)->where('status',1)->where('leavePartID','Full Day')->sum('leaveDay');

                    $late=Attendance::where('dutyDate','>=',$startDate)->where('dutyDate','<=',$endDate)->where('empID',$request->empID)->where('status','late')->count();

                    $empWorkingDay=Attendance::where('dutyDate','>=',$startDate)->where('dutyDate','<=',$endDate)->where('empID',$request->empID)->count();

                    $overTimeMinute=Attendance::where('dutyDate','>=',$startDate)->where('dutyDate','<=',$endDate)->where('empID',$request->empID)->sum('overtimeMiniute');

                    $workingTime=Attendance::where('dutyDate','>=',$startDate)->where('dutyDate','<=',$endDate)->where('empID',$request->empID)->sum('workingMiniute');

                    $workingDay=$month->monthTotalDay;
                    $totalWorkingDay=($empWorkingDay+$leaveDay)-(intval($late/LateRoll::first()->late));
                    $absent=$workingDay-($totalWorkingDay+$month->holiday);
                }else{

                    $joiningDate = Carbon::parse($employee->join_date)->startOfDay();
                    $workingDays = collect();
                    for ($date = $joiningDate->copy(); $date->lte($endDate); $date->addDay()) {
                        if ($date->dayOfWeek == 5) {
                            $workingDays->push($date->toDateString());
                        }
                    }
                    $holiday=Holiday::where('startDate','>=',$joiningDate)->where('endDate','<=',$endDate)->sum('day');
                    $total_holy_Day = $workingDays->count()+$holiday;

                    $leaveDay=LeaveApplication::where('fromDate','>=',$joiningDate)->where('toDate','<=',$endDate)->where('empID',$request->empID)->where('status',1)->where('leavePartID','Full Day')->sum('leaveDay');


                    $late=Attendance::where('dutyDate','>=',$joiningDate)->where('dutyDate','<=',$endDate)->where('empID',$request->empID)->where('status','late')->count();

                    $empWorkingDay=Attendance::where('dutyDate','>=',$joiningDate)->where('dutyDate','<=',$endDate)->where('empID',$request->empID)->count();

                    $overTimeMinute=Attendance::where('dutyDate','>=',$joiningDate)->where('dutyDate','<=',$endDate)->where('empID',$request->empID)->sum('overtimeMiniute');

                    $workingTime=Attendance::where('dutyDate','>=',$joiningDate)->where('dutyDate','<=',$endDate)->where('empID',$request->empID)->sum('workingMiniute');


                    // dd($late);
                    $totalWorkingDay=($empWorkingDay+$leaveDay)-(intval($late/LateRoll::first()->late));
                    // dd($totalWorkingDay);
                     $workingDay=$month->monthTotalDay;
                    // dd($totalWorkingDay+$total_holy_Day);
                    $absent=$workingDay-($totalWorkingDay+$total_holy_Day);


                }
                // dd($absent);
                // salary Sheet Generate
               
                // if($absent>1){
                //     // dd($absentRoll);
                //     $deduct=($basicSalary*($absentRoll->first/100))+(($absent-1)*($basicSalary*($absentRoll->other/100)));

                // }
                // else if($absent==1){
                //     $deduct=$basicSalary*($absentRoll->first/100);
                // }
                // else{
                //     $deduct=0;
                // }
                // dd($absent);
                $deduct=$oneDaySalary * $absent;
                //  dd($absent);
                // calculation
                $overTimeTaka=($overTimeMinute/60)*($basicSalary*($overTimeRoll->amount/100));
                // $salaryEarn=$totalWorkingDay*$oneDaySalary;
                $netSalary=($totalDaySalary+$overTimeTaka)-$deduct;
                // dd($deduct);

                //dd( $overTimeTaka);
                $SalarySheet=new SalarySheet();
                $SalarySheet->month=$request->monthDate;
                // $SalarySheet->deptID=$request->deptID;

                // $SalarySheet->desigID=$request->desigID;
                $SalarySheet->empID=$request->empID;
                $SalarySheet->basicSalary=round($basicSalary,2);
       
                $SalarySheet->houseRent=$house_rent;

                $SalarySheet->medicalCost=$medical_cost;
                $SalarySheet->transportCost=$transport_cost;
                $SalarySheet->tax=$tax;
                $SalarySheet->providentFound=$provident_fund;


                $SalarySheet->overtime=round($overTimeRoll->amount,2);
                $SalarySheet->overtimeMiniute=$overTimeMinute;
                $SalarySheet->absentDay=$absent;
                $SalarySheet->absentDeductFirstDay=round($absentRoll->first,2);
                $SalarySheet->absentDeductOtherDay=round($absentRoll->other,2);

                $SalarySheet->absentDeduct=round($deduct,2);
                $SalarySheet->advanced=0;
                $SalarySheet->netSalary=round($netSalary,2);
                $SalarySheet->paidSalary = 0;
                $SalarySheet->due_amount = $SalarySheet->netSalary - $SalarySheet->paidSalary;
                $SalarySheet->save();
                $salaryHead = AccountHead::where("code",'7010')->first();

                $sal_trans = New AccountTransaction;
                $sal_trans->amount = round($netSalary,2);
                $sal_trans->account_id = $salaryHead->id;
                $sal_trans->type = "debit";
                $sal_trans->sub_type = "Salary";
                $sal_trans->reason = '#'.$employee->employee_id." Employee Salary";
                $sal_trans->date =  date('Y-m-d');
                $sal_trans->relation_id = $SalarySheet->id;
                $sal_trans->relation_with = "Salary";
                $sal_trans->save();

                $acPayableHead = AccountHead::where("code",'2000')->first();

                $acp_trans = New AccountTransaction;
                $acp_trans->amount = round($netSalary,2);
                $acp_trans->account_id = $acPayableHead->id;
                $acp_trans->type = "credit";
                $acp_trans->sub_type = "Salary";
                $acp_trans->reason = '#'.$employee->employee_id." Employee Salary with Due";
                $acp_trans->date =date('Y-m-d');
                $acp_trans->relation_id = $SalarySheet->id;
                $acp_trans->relation_with = "Salary";
                $acp_trans->trans_id = $sal_trans->id;
                $acp_trans->save();
                $sal_trans->trans_id = $acp_trans->id;
                $sal_trans->save();
            }

            DB::commit();
            $notification=array(
                'message'=>"Salary Add Successsfully",
                'alert-type'=>'success'
            );
            return redirect()->route('manageSalary')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            $notification=array(
                'message'=>"something went Wrong!",
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }

    }
    public function salarySlip(Request $request){
        if (!$request->id) {
           $html ='Sorry';
        } else {

        $data['SalarySheet']=$SalarySheet=SalarySheet::find($request->id);
        // dd($SalarySheet);
           // return $SalarySheet;
        $data['employee']=$employee=DB::table('employees')
        ->join('designations','employees.designation_id','designations.id')
        ->join('departments','employees.department_id','departments.id')
        ->where('employees.id',$SalarySheet->empID)
        ->select('employees.*','departments.name as deptName','designations.name as desigName')
        ->first();

          

            $data['payments'] = Payment::where('relation_id',$SalarySheet->id)->where("status",1)->where('relation_type',"Salary Payment")->get();
            
            $html =  view('Hr.salary.ajax-slip',$data)->render();
            

        }


        return response()->json(['html' => $html]);
    }
    public function manageSalary(){
        if(can_p('manageSalary') == false){
            return redirect()->route('dashboard');
        }
      
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        $data['payment_setting'] = OnlinePaymentSetting::firstOrNew();
        return view('Hr.salary.manage_salary',$data);
    }
    function ajaxSalary(Request $request){
        $columns = [
            'salary_sheets.id',
            'salary_sheets.month',
            'departments.name',
            'designations.name',
            'employees.employee_name',
            'salary_sheets.netSalary',
            'salary_sheets.paidSalary',
            'salary_sheets.due_amount',
            'salary_sheets.payment_status',
        ];

        $totalData = SalarySheet::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = SalarySheet::leftJoin('employees','employees.id','salary_sheets.empID')
                                ->leftJoin('departments','departments.id','employees.department_id')
                                ->leftJoin('designations','designations.id','employees.designation_id');
        if(!empty($search)){

           $datalist =$datalist->where("employees.employee_name","LIKE","%{$search}%")
                    ->where("departments.name","LIKE","%{$search}%")
                    ->where("designations.name","LIKE","%{$search}%")
                    ->where("salary_sheets.month","LIKE","%{$search}%")
                    ->where("salary_sheets.netSalary","LIKE","%{$search}%")
                    ->where("salary_sheets.paidSalary","LIKE","%{$search}%")
                    ->where("salary_sheets.due_amount","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->select('salary_sheets.*','employees.employee_name as e_name','departments.name as depart_name','designations.name as desi_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('salarySheet.update');
            $p_delete = can_p('deleteSalary');
            $p_slip = can_p('salary.slip.fetch');

            foreach ($datalist as $data_v) {
                $nestedData['id'] = $data_v->id;
                $nestedData['employee_name'] = $data_v->e_name;
                $nestedData['department'] = $data_v->depart_name;
                $nestedData['designation'] = $data_v->desi_name;
                $nestedData['month'] = date("M,Y",strtotime($data_v->month.'-01'));
                $nestedData['total_salary'] = auth()->user()->currency_symbol .' '.round($data_v->netSalary,2);
                $nestedData['paid_salary'] = auth()->user()->currency_symbol .' '.round($data_v->paidSalary,2);
                $nestedData['due_salary'] = auth()->user()->currency_symbol .' '.round($data_v->due_amount,2);
                
                $payment_status = '';
                if($data_v->payment_status == 0){
                    $payment_status ='<div class="badge bg-danger">Due</div>';
                }else if($data_v->payment_status == 1){
                    $payment_status ='<div class="badge bg-primary">Partial</div>';
                }else{
                    $payment_status ='<div class="badge bg-secondary">Paid</div>';
                }
                $nestedData['status']=$payment_status;

                $nestedData['options']='';
                if($data_v->payment_status != 2){
                    if($p_edit){
                        $nestedData['options'] .= ' <a style="padding: 0 2px;" class="btn btn-primary mb-1" href="javascript:void(0)" data-token="'.csrf_token().'" id="salarySheetEdit" data-id="'.$data_v->id.'" data-due="'. round($data_v->due_amount,2) .'" data-bs-toggle="modal" data-bs-target="#updateModal"> <i style="margin: 0;padding: 0;font-size: 16px;line-height: 18px;"  class="bx bx-money"></i></a>';
                    }
                }
                if($p_slip){
                    $nestedData['options'] .= '<a style="padding: 0 2px;" class="btn btn-info salarySlipFetch mx-1 my-1" href="javascript:void(0)" data-token="'.csrf_token().'" data-id="'.$data_v->id.'" data-bs-toggle="modal" data-bs-target="#salarySlip"><i style="margin: 0;padding: 0;font-size: 16px;line-height: 18px;" class="bx bx-show"></i></a>';
                }
                if($p_delete){
                    $nestedData['options'] .= '<a style="padding: 0 2px;" title="Delete" class="del_hr_data btn btn-danger" data-token="'.csrf_token().'" data-id="'.$data_v->id.'" data-action="'.route('deleteSalary',$data_v->id).'"><i style="margin: 0;padding: 0;font-size: 16px;line-height: 18px;" class="bx bx-trash"></i></a>';
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
    public function editSalary($id){
        if(can_p('salarySheet.update') == false){
            return redirect()->route('dashboard');
        }
        $salaryData = SalarySheet::find($id);
        $employee = Employee::get();
        return view('Hr.salary.edit_salary',compact('salaryData','employee'));
    }
    public function updateSalary(Request $request){
       // dd($request->all());
        if(can_p('salarySheet.update') == false){
            $data["msg"] ='Edit permision is not allowed';
            $data["error"] ='Edit permision is not allowed';
            $data["status"] ="0";
            return response()->json($data);
        }
        $payment_setting = OnlinePaymentSetting::firstOrNew();
        if($payment_setting->status != 1){
            if(array_search('accounts',load_pack_option()) != false){
                $validator = Validator::make($request->all(),[
                        'id'=>'required',
                        'paidDate'=>'required',
                        'payment_method'=>'required',
                        'paidSalary'=>'required|numeric|min:1|max:'.$request->due_amount,
                        'account'=>'required',

                ],[
                'paidSalary.min' => 'Amount Should be grater than 0',
                'paidSalary.max' => 'Amount Should be less or equal '.$request->due_amount,
                ]);
            }else{
                $validator = Validator::make($request->all(),[
                    'id'=>'required',
                    'paidDate'=>'required',
                    'payment_method'=>'required',
                    'paidSalary'=>'required|numeric|min:1|max:'.$request->due_amount,

                ],[
                'paidSalary.min' => 'Amount Should be grater than 0',
                'paidSalary.max' => 'Amount Should be less or equal '.$request->due_amount,
                ]);
            }
        }else{
             $validator = Validator::make($request->all(),[
                    'id'=>'required',
                    'paidDate'=>'required',
                    'paidSalary'=>'required|numeric|min:1|max:'.$request->due_amount,

                ],[
                'paidSalary.min' => 'Amount Should be grater than 0',
                'paidSalary.max' => 'Amount Should be less or equal '.$request->due_amount,
                ]);
        }
       
        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            if($payment_setting->status != 1){
                $SalarySheet=SalarySheet::find($request->id);
                // previous data clear
                // EmpLoan::where('salarySheetID',$request->id)->delete();
                $employee=Employee::where('id',$SalarySheet->empID)->first();
                //$NewEmployee->debit=$NewEmployee->debit-$SalarySheet->advanced;
                // $NewEmployee->save();

                $SalarySheet->paidSalary = $SalarySheet->paidSalary+$request->paidSalary;
                $SalarySheet->due_amount = $SalarySheet->netSalary - $SalarySheet->paidSalary;
                if($SalarySheet->due_amount == 0){
                    $SalarySheet->payment_status = 2;
                }else{
                    $SalarySheet->payment_status = 1;
                }
                $SalarySheet->save();
                $payment = New Payment;
                $payment->payment_method= $request->payment_method;
                $payment->bank_account_id= $request->account ?? 0;
                // $payment->transaction_id= $sc_pay_transaction->id;
                $payment->relation_id = $SalarySheet->id;
                $payment->relation_type = "Salary Payment";
                $payment->amount = $request->paidSalary;
                $payment->date = $request->paidDate == null ?  date('Y-m-d') :  Carbon::parse($request->paidDate)->format('Y-m-d');
                $payment->note = $request->order_note;
                $payment->save();
                if(array_search('accounts',load_pack_option()) != false){
                    $salesDueHead = AccountHead::where("title",'Salary')->first();

                    $sc_due_transaction = New AccountTransaction;
                    $sc_due_transaction->amount = $request->paidSalary;
                    $sc_due_transaction->account_id = $salesDueHead->id;
                    $sc_due_transaction->type = "debit";
                    $sc_due_transaction->sub_type = "Salary Payment";
                    $sc_due_transaction->reason =  "Salary Payment For Employee #".$employee->employee_id;
                    $sc_due_transaction->date = $request->payment_date == null ?  date('Y-m-d') :  Carbon::parse($request->payment_date)->format('Y-m-d');
                    $sc_due_transaction->relation_id = $SalarySheet->id;
                    $sc_due_transaction->relation_with = "Salary";
                    $sc_due_transaction->payment_id = $payment->id;
                    $sc_due_transaction->save();

                    $balance_account = BalanceAccount::find($request->account);

                    $sc_pay_transaction = New AccountTransaction;
                    $sc_pay_transaction->amount = $request->paidSalary;
                    $sc_pay_transaction->account_id = $balance_account->account_head_id;
                    $sc_pay_transaction->type = "credit";
                    $sc_pay_transaction->relation_with = "Salary";
                    $sc_pay_transaction->sub_type = "Salary Payment";
                    $sc_pay_transaction->reason = "Salary Payment For Employee #".$employee->employee_id;
                    $sc_pay_transaction->date = $request->paidDate == null ?  date('Y-m-d') :  Carbon::parse($request->paidDate)->format('Y-m-d');
                    $sc_pay_transaction->relation_id = $SalarySheet->id;
                    $sc_pay_transaction->payment_id = $payment->id;
                    $sc_pay_transaction->trans_id = $sc_due_transaction->id;
                    $sc_pay_transaction->save();
                    $sc_due_transaction->trans_id = $sc_pay_transaction->id;
                    $sc_due_transaction->save();
                }
                 DB::commit();
                return response([
                    'status' => 1,
                    'success' => 'Payment Paid successfully.',
                ]);
            }else{
                $user = auth()->user();
                $SalarySheet=SalarySheet::find($request->id);
                $payment = New Payment;
                $payment->relation_id = $SalarySheet->id;
                $payment->relation_type = "Salary Payment";
                $payment->amount = $request->paidSalary;
                $payment->date = $request->paidDate == null ?  date('Y-m-d') :  Carbon::parse($request->paidDate)->format('Y-m-d');
                $payment->note = $request->order_note;
                $payment->status = 0;
                $payment->save();
                // dd($payment);
                DB::commit();
                $res_pay = $this->pay($payment,$payment_setting,$user->business);
                return $res_pay;
            }
           
        }catch (\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'data'=> $e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }
    }
    function pay($payment,$payment_setting,$business){
        // $payment_setting = OnlinePaymentSetting::firstOrNew();
        $apiDomain = $payment_setting->mode == 0 ? "https://sandbox.sslcommerz.com" : "https://securepay.sslcommerz.com";
        config()->set([
            'sslcommerz.' . "apiCredentials" => [
                'store_id' => $payment_setting->store_id,
                'store_password' => $payment_setting->store_password,
            ],
            'sslcommerz.' . "apiDomain" => $apiDomain,
            'sslcommerz.' . "connect_from_localhost" => true,
        ]);

        // $member = Member::leftJoin("countries","countries.id","members.country_id")
        // ->leftJoin("states","states.id","members.state_id")
        // ->leftJoin("cities","cities.id","members.city_id")
        // ->select("members.*","countries.name as country_name","states.name as state_name" ,"cities.name as city_name")
        // ->where("members.id",$member_id)->first();
        if( $business->currency){
            $currency = $business->currency->name;
        }else{
            $currency = "BDT";
        }
        $post_data = array();
        $post_data['total_amount'] = $payment->amount; # You cant not pay less than 10
        $post_data['currency'] = $currency;
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        $post_data['multi_card_name'] = "visacard"; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $business->business_name;
        $post_data['cus_email'] =  $business->email ?  $business->email : "test@gmail.com";
        $post_data['cus_add1'] =  $business->address1;
        $post_data['cus_add2'] = $business->address2;
        $post_data['cus_city'] = $business->city?->name;
        $post_data['cus_state'] = $business->state?->name;
        $post_data['cus_postcode'] = $business->post_code;
        $post_data['cus_country'] = $business->country?->name;
        $post_data['cus_phone'] = $business->phone_number;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        // $post_data['ship_name'] = "Store Test";
        // $post_data['ship_add1'] = "Dhaka";
        // $post_data['ship_add2'] = "Dhaka";
        // $post_data['ship_city'] = "Dhaka";
        // $post_data['ship_state'] = "Dhaka";
        // $post_data['ship_postcode'] = "1000";
        // $post_data['ship_phone'] = "";
        // $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Employee Salary";
        $post_data['product_category'] = "topup";
        $post_data['product_profile'] = "non-physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $payment->id;
        $post_data['value_b'] = "Salary";

        

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        // $payment_options = $sslc->makePayment($post_data, 'hosted');
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            return $payment_options;
            print_r($payment_options);
            $payment_options = array();
        }
        return "";

    }
    public function deleteSalary($id){
        if(can_p('deleteSalary') == false){
            $data["msg"] ='Delete permision is not allowed';
            $data["error"] ='Delete permision is not allowed';
            $data["status"] ="0";
            return response()->json($data);
        }
        try{
           DB::beginTransaction();

           $SalarySheet=SalarySheet::find($id);
           $payments = Payment::where('relation_id',$SalarySheet->id)->where('relation_type',"Salary Payment")->get();
           foreach($payments as $payment){
            $payment->delete();
           }
           $trans = AccountTransaction::where('relation_id',$SalarySheet->id)->where('relation_with',"Salary")->get();
           foreach($trans as $transaction){
            $transaction->delete();
           }
           $SalarySheet->delete();
           DB::commit();
           return redirect()->route('manageSalary');
        }catch (\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'data'=> $e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }



    }
    public function SalarySheet() {

        $salaryData = DB::table('salary_manage')->get();
        return view('Hr.salary.salary_sheet',compact('salaryData'));

    }
    function empBankAccountByBankId(Request $request){
         if (!$request->id) {
           $html ='Sorry';
        } else {

           $Accounts=EmpBankAccount::where('bankID',$request->id)->get();
           $html='<option value="">-- Select One --</option>';

            foreach($Accounts as $Account){

                    $html.='<option value="'.$Account->id.'">'.$Account->acNumber.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }


    //--------------------Account-> Sales Methods------------------------//

    public function manageAccounts(){

        $cart = DB::table('cart')->get();
        $user = DB::table('user')->get();
        $pkg = DB::table('package_info')->get();
        return view('Hr.account.sale.manageAccounts' , compact('cart' , 'user' , 'pkg'));
    }


    public function manageAccountsSorting(Request $req) {

        $cart = DB::table('cart')->whereBetween('deal_date', [$req->min , $req->max])->get();
        $user = DB::table('user')->get();
        $pkg = DB::table('package_info')->get();
        return view('Hr.account.sale.loadManageAccounts' , compact('cart' , 'user' , 'pkg'));

    }

    //--------------------Account-> Due Methods------------------------//

    public function manageDue(){

        $cart = DB::table('cart')->get();
        $user = DB::table('user')->get();
        $pkg = DB::table('package_info')->get();
        return view('Hr.account.due.manageDue' , compact('cart' , 'user' , 'pkg'));
    }

    public function manageDueSorting(Request $req) {

        $cart = DB::table('cart')->whereBetween('deal_date', [$req->min , $req->max])->get();
        $user = DB::table('user')->get();
        $pkg = DB::table('package_info')->get();
        return view('Hr.account.due.loadManageDue' , compact('cart' , 'user' , 'pkg'));

    }
    //--------------------Account-> Stock Methods------------------------//

    public function getStock(Request $req) {

        $packageId = DB::table('cart')->pluck('package_id')->all();

        $package_info = DB::table('package_info')
        ->whereNotIn('id', $packageId)
        ->get();

        $user = DB::table('user')->get();
        $packageList = DB::table('packagelisting')->get();
        $cart = DB::table('cart')->get();

        return view('Hr.account.stock.manageStock', compact('package_info' , 'user' , 'packageList' , 'cart'));

    }

    public function manageStockSorting(Request $req) {

        $cart = DB::table('cart')->whereBetween('deal_date', [$req->min , $req->max])->get();
        $user = DB::table('user')->get();
        $pkg = DB::table('package_info')->get();
        return view('Hr.account.stock.loadManageStock' , compact('cart' , 'user' , 'pkg'));

    }

        //--------------------Account-> Profit Methods------------------------//

        public function manageProfit(){

            $cart = DB::table('cart')->get();
            $user = DB::table('user')->get();
            $pkg = DB::table('package_info')->get();
            return view('Hr.account.profit.manageProfit' , compact('cart' , 'user' , 'pkg'));
        }


        public function manageProfitSorting(Request $req) {

             $cart = DB::table('cart')->whereBetween('deal_date', [$req->min , $req->max])->get();
             $user = DB::table('user')->get();
            $pkg = DB::table('package_info')->get();
             return view('Hr.account.profit.loadManageProfit' , compact('cart' , 'user' , 'pkg'));

         }

             //--------------------Account-> Stock Out Methods------------------------//

    public function getStockOut(){

        $cart = DB::table('cart')->get();
        $user = DB::table('user')->get();
        $pkg = DB::table('package_info')->get();
        return view('Hr.account.stock.getStockOut' , compact('cart' , 'user' , 'pkg'));
    }


    public function manageStockOutSorting(Request $req) {

        $cart = DB::table('cart')->whereBetween('deal_date', [$req->min , $req->max])->get();
        $user = DB::table('user')->get();
        $pkg = DB::table('package_info')->get();
        return view('Hr.account.stock.loadManageStockOut' , compact('cart' , 'user' , 'pkg'));

    }

    //--------------------Account-> Service Expenses Methods------------------------//

    public function getServiceExpenses() {

        $services = DB::table('services')
        ->get();

        return view('Hr.account.expense.manage' , compact('services'));

    }

    public function manageExpensesSorting(Request $req) {

        $cart = DB::table('cart')->whereBetween('deal_date', [$req->min , $req->max])->get();
        $user = DB::table('user')->get();
        $pkg = DB::table('package_info')->get();
        return view('Hr.account.expense.loadManageExpenses' , compact('cart' , 'user' , 'pkg'));

    }

        //--------------------Account-> Loss Methods------------------------//

        public function manageloss() {

            // $loss = DB::table('')
            // ->get();

            return view('Hr.account.loss.manage');
            //, compact('loss')

        }

/**
         * Payroll
         */

        public function managePayroll(){
            if(can_p('managePayroll') == false){
                return redirect()->route('dashboard');
            }
            $viewAll = Payroll::get();
           return view('Hr.payroll.manage',compact('viewAll'));
        }

        public function addPayroll()
        {
            if(can_p('addPayroll') == false){
                return redirect()->route('dashboard');
            }
            return view('Hr.payroll.create');
        }

        public function storePayroll(Request $request)
        {
            // return $request;
            if(can_p('addPayroll') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "house_rent" => ["required"],
                "medical_cost" => ["required"],
                "provident_fund" => ["required"],
                "transport_cost" => ["required"],
                "tax" => ["required"]
            ]);
            $payroll = New Payroll;
            $payroll->house_rent = $request->house_rent;
            $payroll->medical_cost = $request->medical_cost;
            $payroll->transport_cost = $request->transport_cost;
            $payroll->provident_fund = $request->provident_fund;
            $payroll->tax = $request->tax;
            $payroll->save();
            $notification=array(
                'message'=>"Payroll Save successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('managePayroll')->with($notification);
        }

        public function deletePayroll($id)
        {
            if(can_p('deletePayroll') == false){
                return redirect()->route('dashboard');
            }
            Payroll::find($id)->delete();
                $notification=array(
                'message'=>"Payroll Save successfull",
                'alert-type'=>'success'
            );
                return redirect()->route('managePayroll')->with($notification);
        }


        public function editPayroll($id){
            if(can_p('editPayroll') == false){
                return redirect()->route('dashboard');
            }
            $p = DB::table('payrolls')->where('id',$id)->first();
            return view('Hr.payroll.edit',compact('p'));
        }

        public function updatePayroll(Request $request, $id){
            if(can_p('editPayroll') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "house_rent" => ["required"],
                "medical_cost" => ["required"],
                "provident_fund" => ["required"],
                "transport_cost" => ["required"],
                "tax" => ["required"]
            ]);
            $payroll =  Payroll::find($id);
            $payroll->house_rent = $request->house_rent;
            $payroll->medical_cost = $request->medical_cost;
            $payroll->transport_cost = $request->transport_cost;
            $payroll->provident_fund = $request->provident_fund;
            $payroll->tax = $request->tax;
            $payroll->save();

            $notification=array(
                    'message'=>"Payroll Update successfull",
                    'alert-type'=>'success'
            );
            return redirect()->route('managePayroll')->with($notification);
        }
        public function attendanceSetting(){
            if(can_p('attendance_setting') == false){
                return redirect()->route('dashboard');
            }
            $viewAll =AttendanceSetting::get();
            return view('Hr.attendance_setting.manage',compact('viewAll'));
        }
        public function attendanceSettingAdd()
        {
            if(can_p('attendance_setting.add') == false){
                return redirect()->route('dashboard');
            }
            return view('Hr.attendance_setting.create');
        }
        public function attendanceSettingStore(Request $request)
        {
            // dd($request->all());
            if(can_p('attendance_setting.add') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "delayTime" => ["required"],
                "entry_last_time" => ["required"],
            ]);
            $attendance_setting = New AttendanceSetting;
            $attendance_setting->delay_time = $request->delayTime;
            $attendance_setting->last_entry_time = $request->entry_last_time;
            $attendance_setting->save();
            $notification=array(
                'message'=>"Attendance Setting Saved successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('attendance_setting')->with($notification);
        }
        public function attendanceSettingEdit($id){
            if(can_p('attendance_setting.edit') == false){
                return redirect()->route('dashboard');
            }
            $p = AttendanceSetting::where('id',$id)->first();
            $st_arr=explode(":",$p->delay_time);
            if($st_arr[0] > 12){
                $d_time = $st_arr[0] - 12 .":".$st_arr[1]." PM";
            }else{
                $d_time =$p->delay_time.' AM';
            }
            $et_arr=explode(":",$p->last_entry_time);
            if($et_arr[0] > 12){
                $et_time = $et_arr[0] - 12 .":".$et_arr[1]." PM";
            }else{
                $et_time =$p->last_entry_time.' AM';
            }
            return view('Hr.attendance_setting.edit',compact('p','d_time','et_time'));
        }
        public function attendanceSettingUpdate(Request $request,$id)
        {
            // dd($request->all());
            if(can_p('attendance_setting.edit') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "delayTime" => ["required"],
                "entry_last_time" => ["required"],
            ]);
            $attendance_setting = AttendanceSetting::find($id);
            $attendance_setting->delay_time = $request->delayTime;
            $attendance_setting->last_entry_time = $request->entry_last_time;
            $attendance_setting->save();
            $notification=array(
                'message'=>"Attendance Setting Updated successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('attendance_setting')->with($notification);
        }
        /**
            * Absent
            */

        public function manageAbsent(){
            if(can_p('manageAbsent') == false){
                return redirect()->route('dashboard');
            }
            $viewAll =Absent::get();
            return view('Hr.absent.manage',compact('viewAll'));
        }

        public function addAbsent()
        {
            if(can_p('addAbsent') == false){
                return redirect()->route('dashboard');
            }
            return view('Hr.absent.create');
        }

        public function storeAbsent(Request $request)
        {
            if(can_p('addAbsent') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "first" => ["required"],
                "other" => ["required"],
            ]);
            $absent = New Absent;
            $absent->first = $request->first;
            $absent->other = $request->other;
            $absent->save();
            $notification=array(
                'message'=>"Absent Saved successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('manageAbsent')->with($notification);
        }

        public function deleteAbsent($id)
        {
            if(can_p('deleteAbsent') == false){
                return redirect()->route('dashboard');
            }
            DB::table('absents')->where('id',$id)->delete();
            $notification=array(
                'message'=>"Absent Delete successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('manageAbsent')->with($notification);
        }


        public function editAbsent($id){
            if(can_p('editAbsent') == false){
                return redirect()->route('dashboard');
            }
            $p = DB::table('absents')->where('id',$id)->first();
            return view('Hr.absent.edit',compact('p'));
        }

        public function updateAbsent(Request $request, $id){
            if(can_p('editAbsent') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "first" => ["required"],
                "other" => ["required"],
            ]);
            $absent =  Absent::find($id);
            $absent->first = $request->first;
            $absent->other = $request->other;
            $absent->save();
            $notification=array(
                'message'=>"Absent Saved successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('manageAbsent')->with($notification);
        }

        /**
            * LateRoll
            */

        public function manageLateRoll(){
            if(can_p('manageLateRoll') == false){
                return redirect()->route('dashboard');
            }
            $viewAll = LateRoll::get();
            return view('Hr.late_roll.manage',compact('viewAll'));
        }

        public function addLateRoll()
        {
            if(can_p('addLateRoll') == false){
                return redirect()->route('dashboard');
            }
            return view('Hr.late_roll.create');
        }

        public function storeLateRoll(Request $request)
        {
            if(can_p('addLateRoll') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "late" => ["required"],
                "absent" => ["required"],
            ]);

            $lateroll = New LateRoll;
            $lateroll->late = $request->late;
            $lateroll->absent = $request->absent;
            $lateroll->save();
            $notification=array(
                'message'=>"Late Roll Saved successfull",
                'alert-type'=>'success'
            );

            return redirect()->route('manageLateRoll')->with($notification);
        }

        public function deleteLateRoll($id)
        {
            if(can_p('deleteLateRoll') == false){
                return redirect()->route('dashboard');
            }
            DB::table('late_rolls')->where('id',$id)->delete();
            return back();
        }


        public function editLateRoll($id){
            if(can_p('editLateRoll') == false){
                return redirect()->route('dashboard');
            }
            $p = DB::table('late_rolls')->where('id',$id)->first();
            return view('Hr.late_roll.edit',compact('p'));
        }

        public function updateLateRoll(Request $request, $id){
            if(can_p('editLateRoll') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "late" => ["required"],
                "absent" => ["required"],
            ]);

            $lateroll =  LateRoll::find($id);
            $lateroll->late = $request->late;
            $lateroll->absent = $request->absent;
            $lateroll->save();
            $notification=array(
                'message'=>"Late Roll Update successfull",
                'alert-type'=>'success'
            );

            return redirect()->route('manageLateRoll')->with($notification);
        }



        /**
            * Overtime
            */

        public function manageOvertime(){
            if(can_p('manageOvertime') == false){
                return redirect()->route('dashboard');
            }
            $viewAll = Overtime::get();
            return view('Hr.overtime.manage',compact('viewAll'));
        }

        public function addOvertime()
        {
            if(can_p('addOvertime') == false){
                return redirect()->route('dashboard');
            }
            return view('Hr.overtime.create');
        }

        public function storeOvertime(Request $request)
        {
            if(can_p('addOvertime') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "hour" => ["required"],
                "amount" => ["required"],
            ]);
            $overtime = New Overtime;
            $overtime->hour = $request->hour;
            $overtime->amount = $request->amount;
            $overtime->save();
            $notification=array(
                'message'=>"Overtime Saved successfull",
                'alert-type'=>'success'
            );

            return redirect()->route('manageOvertime')->with($notification);
        }

        public function deleteOvertime($id)
        {
            if(can_p('deleteOvertime') == false){
                return redirect()->route('dashboard');
            }
            DB::table('overtimes')->where('id',$id)->delete();
            return back();
        }


        public function editOvertime($id){
            if(can_p('editOvertime') == false){
                return redirect()->route('dashboard');
            }
            $p = DB::table('overtimes')->where('id',$id)->first();
            return view('Hr.overtime.edit',compact('p'));
        }

        public function updateOvertime(Request $request, $id){
            if(can_p('editOvertime') == false){
                return redirect()->route('dashboard');
            }
            $request->validate([
                "hour" => ["required"],
                "amount" => ["required"],
            ]);
            $overtime =  Overtime::find($id);
            $overtime->hour = $request->hour;
            $overtime->amount = $request->amount;
            $overtime->save();
            $notification=array(
                'message'=>"OverTime Update successfull",
                'alert-type'=>'success'
            );

            return redirect()->route('manageOvertime')->with($notification);
        }


        /**
            * Payment Range
            */

        public function managePaymentRange(){
            if(can_p('managePaymentRange') == false){
                return redirect()->route('dashboard');
            }
            //dd("hi");
                $viewAll = Payment_range::get();
                $departments = Department::get();
                $designations = DB::table('designations')->get();
            //dd($departments);
            return view('Hr.payment.manage',compact('viewAll','designations','departments'));

        }



        public function storePaymentRange(Request $request)
        {
            if(can_p('paymentRange.add') == false){
                return response([
                    'status' => 0,
                    'error' => 'Add permission is not allowed',
                ]);
            }
            $validator = Validator::make($request->all(),[
                "max" => ["required"],
                "min" => ["required"],
                "department_id" => ["required"],
                "designation_id" => ["required"],
            ],[
                'max.required'=> 'Max is required',
                'min.required'=> 'Min is required',
                'department_id.required'=> 'Department is required',
                'designation_id.required'=> 'Designation is required',
            ]);
            if($validator->fails()){
                return response([
                    'status' => 0,
                    'errors' => $validator->errors()
                ]);
            }
            try{
                DB::beginTransaction();

                $payment_range = New Payment_range;
                $payment_range->department_id = $request->department_id;
                $payment_range->designation_id = $request->designation_id;
                $payment_range->minimum_amount = $request->min;
                $payment_range->maximum_amount = $request->max;
                $payment_range->save();
                DB::commit();
                return response([
                    'status' => 1,
                    'success' => 'Payment Range add successfully.',
                ]);

            }catch(\Exception $e){
                DB::rollBack();
                return response([
                    'status' => 0,
                    'error' => 'Something went Wrong!',
                ]);
            }
        }

        public function deletePaymentRange($id)
        {
            if(can_p('paymentRange.delete') == false){
                return redirect()->route('dashboard');
            }
            DB::table('payment_ranges')->where('id',$id)->delete();
            return back();
        }



        public function updatePaymentRange(Request $request, $id){
            if(can_p('paymentRange.edit') == false){
                return response([
                    'status' => 0,
                    'error' => 'Edit permission is not allowed',
                ]);
            }
            $validator = Validator::make($request->all(),[
                "max" => ["required"],
                "min" => ["required"],
                "department_id" => ["required"],
                "designation_id" => ["required"],
            ],[
                'max.required'=> 'Max is required',
                'min.required'=> 'Min is required',
                'department_id.required'=> 'Department is required',
                'designation_id.required'=> 'Designation is required',
            ]);
            if($validator->fails()){
                return response([
                    'status' => 0,
                    'errors' => $validator->errors()
                ]);
            }
            try{
                DB::beginTransaction();

                $payment_range = Payment_range ::find($id);
                $payment_range->department_id = $request->department_id;
                $payment_range->designation_id = $request->designation_id;
                $payment_range->minimum_amount = $request->min;
                $payment_range->maximum_amount = $request->max;
                    $payment_range->save();
                DB::commit();
                return response([
                    'status' => 1,
                    'success' => 'Payment Range add successfully.',
                ]);

            }catch(\Exception $e){
                DB::rollBack();
                return response([
                    'status' => 0,
                    'error' => 'Something went Wrong!',
                ]);
            }
        }

        /** Size **/

           public function manageSize(){

            $viewAll = DB::table('sizes')->orderBy('id','desc')->get();
               return view('Hr.size.manage',compact('viewAll'));
            }

           public function storeSize(Request $request){

               DB::table('sizes')->insert([
               'name' => $request->name,
               ]);
               return redirect()->route('manageSize');
           }

           public function deleteSize($id)
           {
               DB::table('sizes')->where('id',$id)->delete();
               return redirect()->route('manageSize');
           }
            public function updateSize(Request $request,$id){

                DB::table('sizes')->where('id',$id)->update([
               'name' => $request->name,
               ]);
               return redirect()->route('manageSize');
           }

            /**
            * Color
            */

           public function manageColor(){

            $viewAll = DB::table('colors')->orderBy('id','desc')->get();
               return view('Hr.color.manage',compact('viewAll'));
            }

           public function storeColor(Request $request){

               DB::table('colors')->insert([
               'name' => $request->name,
               ]);
               return redirect()->route('manageColor');
           }

           public function deleteColor($id)
           {
               DB::table('colors')->where('id',$id)->delete();
               return redirect()->route('manageColor');
           }
            public function updateColor(Request $request,$id){

                DB::table('colors')->where('id',$id)->update([
               'name' => $request->name,
               ]);
               return redirect()->route('manageColor');
           }

           /**
            * Brand
            */

           public function manageBrand(){

            $viewAll = DB::table('brands')->orderBy('id','desc')->get();
               return view('Hr.brand.manage',compact('viewAll'));
            }

           public function storeBrand(Request $request){

               DB::table('brands')->insert([
               'name' => $request->name,
               ]);
               return redirect()->route('manageBrand');
           }

           public function deleteBrand($id)
           {
               DB::table('brands')->where('id',$id)->delete();
               return redirect()->route('manageBrand');
           }
            public function updateBrand(Request $request,$id){

                DB::table('brands')->where('id',$id)->update([
               'name' => $request->name,
               ]);
               return redirect()->route('manageBrand');
           }

           /**
            * Category
            */

           public function manageCategory(){

            $viewAll = DB::table('categories')->orderBy('id','desc')->get();
               return view('Hr.category.manage',compact('viewAll'));
            }

           public function storeCategory(Request $request){

               DB::table('categories')->insert([
               'name' => $request->name,
               ]);
               return redirect()->route('manageCategory');
           }

           public function deleteCategory($id)
           {
               DB::table('categories')->where('id',$id)->delete();
               return redirect()->route('manageCategory');
           }
            public function updateCategory(Request $request,$id){

                DB::table('categories')->where('id',$id)->update([
               'name' => $request->name,
               ]);
               return redirect()->route('manageCategory');
           }

            /**
            * Bank
            */

           public function manageBank(){

            $viewAll = DB::table('banks')->orderBy('id','desc')->get();
               return view('Hr.bank.manage',compact('viewAll'));
            }

           public function storeBank(Request $request){

               DB::table('banks')->insertGetId([
               'name' => $request->name . " Bank",
               ]);
               return redirect()->route('manageBank');
           }

           public function deleteBank($id)
           {
               DB::table('banks')->where('id',$id)->delete();
               return redirect()->route('manageBank');
           }
            public function updateBank(Request $request,$id){

                DB::table('banks')->where('id',$id)->update([
               'name' => $request->name,
               ]);
               return redirect()->route('manageBank');
           }


            /**
            * Branch
            */

           public function manageBranch(){
           $banks = DB::table('banks')->orderBy('id','desc')->get();
            $viewAll = DB::table('branches')->orderBy('id','desc')->get();
               return view('Hr.branch.manage',compact('viewAll','banks'));
            }

           public function storeBranch(Request $request){


              $data = [];

              foreach($request->names as $name){
               $t = [
                   "name" => $name,
                   "bank_id" => $request->bank_id,
               ];

               array_push($data,$t);
              }

               DB::table('branches')->insert($data);
               return redirect()->route('manageBranch');
           }

           public function deleteBranch($id)
           {
               DB::table('branches')->where('id',$id)->delete();
               return redirect()->route('manageBranch');
           }
            public function updateBranch(Request $request,$id){

                DB::table('branches')->where('id',$id)->update([
               'name' => $request->name,
               'bank_id' => $request->bank_id,
               ]);
               return redirect()->route('manageBranch');
           }

            /**
            * Internet Bank
            */

           public function manageInternetBank(){

            $viewAll = DB::table('internet_banks')->orderBy('id','desc')->get();
               return view('Hr.internet_bank.manage',compact('viewAll'));
            }

           public function storeInternetBank(Request $request){

               DB::table('internet_banks')->insert([
               'name' => $request->name ,
               ]);
               return redirect()->route('manageInternetBank');
           }

           public function deleteInternetBank($id)
           {
               DB::table('internet_banks')->where('id',$id)->delete();
               return redirect()->route('manageInternetBank');
           }
            public function updateInternetBank(Request $request,$id){

                DB::table('internet_banks')->where('id',$id)->update([
               'name' => $request->name,
               ]);
               return redirect()->route('manageInternetBank');
           }

            /**
            * Mobile Bank
            */

           public function manageMobileBank(){

            $viewAll = DB::table('mobile_banks')->orderBy('id','desc')->get();
               return view('Hr.mobile_bank.manage',compact('viewAll'));
            }

           public function storeMobileBank(Request $request){

               DB::table('mobile_banks')->insert([
               'name' => $request->name ,
               ]);
               return redirect()->route('manageMobileBank');
           }

           public function deleteMobileBank($id)
           {
               DB::table('mobile_banks')->where('id',$id)->delete();
               return redirect()->route('manageMobileBank');
           }
            public function updateMobileBank(Request $request,$id){

                DB::table('mobile_banks')->where('id',$id)->update([
               'name' => $request->name,
               ]);
               return redirect()->route('manageMobileBank');
           }

   }









