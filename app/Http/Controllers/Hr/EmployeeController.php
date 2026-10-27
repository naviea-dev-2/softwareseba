<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Attendance;
use App\Models\Hr\Setting\Department;
use App\Models\Hr\Setting\Designation;
use App\Models\Hr\Employee;
use App\Models\Hr\Setting\Shift;
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
use Illuminate\Support\Str;

class EmployeeController extends Controller
{

   public function index(){
        if(can_p('employee.index') == false){
            return redirect()->route('dashboard');
        }
        return view('Hr.employee.manageEmployee');
    }
    
    
    public function ajaxEmployee(Request $request)
    {
        // DataTable column ordering
        $columns = [
            0 => 'employees.id',
            2 => 'employees.employee_id',
            3 => 'employees.employee_name',
            4 => 'departments.name',
            5 => 'designations.name',
            6 => 'employees.email',
            7 => 'employees.mobile',
            8 => 'employees.salary',
            9 => 'employees.join_date',
        ];

        // Total counts for tabs
        $counts = Employee::query()
            ->selectRaw('COUNT(*) as all_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END), 0) as active_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END), 0) as inactive_count')
            ->first();

        $totalData = (int) $counts->all_count;

        // Base query
        $datalist = Employee::query()
            ->leftJoin(
                'departments',
                'departments.id',
                '=',
                'employees.department_id'
            )
            ->leftJoin(
                'designations',
                'designations.id',
                '=',
                'employees.designation_id'
            );

        // Filter by selected tab: 1 = Active, 0 = Inactive
        $status = $request->input('status');

        if (in_array((string) $status, ['0', '1'], true)) {
            $datalist->where('employees.status', (int) $status);
        }

        // DataTable search
        $search = trim((string) $request->input('search.value', ''));

        if ($search !== '') {
            $datalist->where(function ($query) use ($search) {
                $query->where(
                    'employees.employee_name',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'departments.name',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'designations.name',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'employees.employee_id',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'employees.email',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'employees.mobile',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'employees.salary',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'employees.join_date',
                    'LIKE',
                    "%{$search}%"
                );
            });
        }

        // Total records after status and search filters
        $totalFiltered = (clone $datalist)->count('employees.id');

        // Safe ordering
        $orderColumnIndex = (int) $request->input('order.0.column', 0);

        $order = $columns[$orderColumnIndex] ?? 'employees.id';

        $dir = strtolower($request->input('order.0.dir', 'desc')) === 'asc'
            ? 'asc'
            : 'desc';

        // Pagination
        $start = max(0, (int) $request->input('start', 0));
        $limit = (int) $request->input('length', 10);

        $datalist->select(
            'employees.*',
            'departments.name as depart_name',
            'designations.name as desi_name'
        );

        $datalist->orderBy($order, $dir);

        if ($limit > 0) {
            $datalist->offset($start)->limit($limit);
        }

        $employees = $datalist->get();

        $data = [];

        $p_edit = can_p('editEmployee');
        $p_delete = can_p('deleteEmployee');

        $i = $start + 1;

        foreach ($employees as $employee) {
            $nestedData = [];

            $nestedData['id'] = $i;
            $nestedData['employee_name'] = $employee->employee_name;
            $nestedData['employee_code'] = $employee->employee_id;

            $nestedData['image'] = '<img src="' .
                e($employee->employee_image_show) .
                '" style="height:50px;width:50px;object-fit:cover;">';

            $nestedData['department'] = $employee->depart_name ?? '';
            $nestedData['designation'] = $employee->desi_name ?? '';
            $nestedData['email'] = $employee->email;
            $nestedData['mobile'] = $employee->mobile;
            $nestedData['join_date'] = $employee->join_date;
            
            $nestedData['status'] = (int) $employee->status === 1
                ? '<span class="badge text-bg-success">Active</span>'
                : '<span class="badge  text-bg-danger">Inactive</span>';

            $nestedData['salary'] = auth()->user()->currency_symbol .
                ' ' . round((float) $employee->salary, 2);

            $nestedData['options'] = '';
            if($p_edit){
                $nestedData['options'] .= '<a href="'.route('employee.edit',$employee->id).'" class="btn btn-primary "><i class="bx bx-edit" style="margin: -10px;"></i></a>';
            }
            
            if($p_delete){
                $nestedData['options'] .= '<a del_data="'. $employee->id .'" data-action="'.route('employee.delete',$employee->id).'" class="m-1 del_hr_data btn btn-danger "><i class="bx bx-trash" style="margin: -10px;"></i></a>';
            }    
            // if ($p_edit) {
            //     $nestedData['options'] .=
            //         '<a href="' . route('editEmployee', $employee->id) .
            //         '" class="btn btn-primary">' .
            //         '<i class="bx bx-edit"></i></a>';
            // }

            // if ($p_delete) {
            //     $nestedData['options'] .=
            //         '<a del_data="' . $employee->id .
            //         '" data-action="' . route('deleteEmployee', $employee->id) .
            //         '" class="m-1 del_hr_data btn btn-danger">' .
            //         '<i class="bx bx-trash"></i></a>';
            // }
            $statusButtonClass = (int) $employee->status === 1
                ? 'btn-warning'
                : 'btn-success';

            $statusButtonText = (int) $employee->status === 1
                ? 'Deactivate'
                : 'Activate';

            $nestedData['options'] .= '
                <button type="button"
                    class="btn ' . $statusButtonClass . ' btn-sm change-employee-status"
                    data-id="' . $employee->id . '"
                    data-status="' . $employee->status . '">
                    ' . $statusButtonText . '
                </button>
            ';

            $data[] = $nestedData;
            $i++;
        }

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,

            // Employee tab counts
            'counts' => [
                'all' => $totalData,
                'active' => (int) $counts->active_count,
                'inactive' => (int) $counts->inactive_count,
            ],
        ]);
    }

    public function changeEmployeeStatus(Request $request)
    {
        if (!can_p('editEmployee')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to change employee status.',
            ], 403);
        }

        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:employees,id'],
            'status' => ['required', 'in:0,1'],
        ]);

        $employee = Employee::findOrFail($validated['id']);

        // Prevent stale requests from changing an unexpected status.
        if ((int) $employee->status !== (int) $validated['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Employee status has already changed. Please refresh.',
            ], 409);
        }

        $newStatus = (int) $employee->status === 1 ? 0 : 1;

        $employee->status = $newStatus;
        $employee->save();

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => $newStatus === 1
                ? 'Employee activated successfully.'
                : 'Employee deactivated successfully.',
        ]);
    }


    public function create(){
        if(can_p('addEmployee') == false){
            return redirect()->route('dashboard');
        }
        $departments = Department::get();
        return view('Hr.employee.addEmployee',compact('departments'));
    }

    public function store(Request $request){
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

                return redirect()->route('employee.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
             $notification=array(
                'message'=>"something went wrong!",
                'alert-type'=>'error'
            );
          return redirect()->route('employee.index')->withInput($request->all())->with($notification);
        }

    }

    public function edit($id){
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

    public function update(Request $request, $id){
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
            return redirect()->route('employee.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            $notification=array(
                'message'=>"something went wrong!",
                'alert-type'=>'error'
            );
            return back()->with($notification)->withInput(request()->all());
        }
    }

    public function destroy($id){
        if(can_p('deleteEmployee') == false){
            return redirect()->route('dashboard');
        }
        try{
            Employee::where('id',$id)->delete();
            $notification=array(
                'message'=>"Emplopyee Deleted successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('employee.index')->with($notification);
        }catch(\Exception $e){
            $notification=array(
                'message'=>"something went wrong!",
                'alert-type'=>'error'
            );
            return redirect()->route('employee.index')->with($notification);
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

}
