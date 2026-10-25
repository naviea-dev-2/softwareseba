<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Employee;
use App\Models\Hr\SalaryHead;
use App\Models\Hr\PayrollAsign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PayrollAssignController extends Controller
{
    public function index()
    {
        return view("Hr.payroll_assign.index");
    }

    function search(Request $request){
        try{
            $data['per_page']=$per_page=$request->per_page ?? 10;
            $data['select_student']=$request->select_student ?? [];
            $data['search_name']=$request->search_name ?? '';
            $data['search_s_code']=$request->search_s_code ?? '';
            $data['search_designation']=$request->search_designation ?? '';
            $employees = Employee::leftJoin('designations','designations.id','employees.designation_id')
            ->where('employees.status',1);

           
            if($request->department){
                $employees =  $employees->where('employees.department_id',$request->department);
            }
            if($request->designation){
                $employees =  $employees->where('employees.designation_id',$request->designation);
            }
            if($request->gender){
                $employees =  $employees->where('employees.gender',$request->gender);
            }
            if($request->religion){
                $employees =  $employees->where('employees.religion',$request->religion);
            }

            if($request->search_f_code){
                $employees =  $employees->where('employees.employee_id','Like','%'.$request->search_f_code.'%');
            }
            if($request->search_f_name){
                $employees =  $employees->where('employees.employee_name','Like','%'.$request->search_f_name.'%');
            }
            if($request->search_designation){
                $employees =  $employees->where('designations.name',$request->search_designation);
            }
            // dd($teachers->get());

            $data['head_arr']=$request->head_arr;
            $data['is_p']=$request->is_p;
            $data['employees']=$employees = $employees->select('employees.*')->paginate($per_page);

            $data['head_list'] =$head_list= SalaryHead::where('status',1)->get();
            $data['payroll_list'] =$payroll_list = PayrollAsign::whereIn('employee_id',$employees->pluck('id'))->get();
            // dd($payroll_list);
            if($head_list->count() == 0){
                return response()->json(['status'=>'no','msg'=>'Fee Head is not setup']);
            }
            $html =  view('Hr.payroll_assign.ajax-teacher-list',$data)->render();

            if($request->is_p == 0){
                return response()->json(['status'=>'yes','html'=>$html,'t_count'=>$employees->count()]);
            }else{
                $data['data_list'] = $employees;
                $paginate =  view('Hr.payroll_assign.paginate',$data)->render();
                return response()->json(['status'=>'yes','html'=>$html,'paginate'=>$paginate,'t_count'=>$employees->count()]);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'no','err'=>$e->getMessage(),'msg'=>"Something went wrong!"]);
        }

    }
    public function store(Request $request)
    {
    //   dd($request->all());

        $validator = Validator::make($request->all(), [
            'employees' => 'required',
            'head_arr' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        try{
            DB::beginTransaction();

            foreach($request->employees as $employee_id){
                if($employee_id != -1){

                    $total_s = 0;
                    foreach($request->head_arr[$employee_id] as $id=>$amount){
                        $payroll_asign = PayrollAsign::where('employee_id',$employee_id)->where('head_id',$id)->first();
                        if($payroll_asign == null){
                            $payroll_asign = New PayrollAsign;
                        }
                        // dd($payroll_asign);
                        $payroll_asign->employee_id = $employee_id;
                        $payroll_asign->head_id = $id;
                        $payroll_asign->head_amount = $amount;
                        $payroll_asign->save();
                        if($request->type_arr[$id] == "Addition"){
                            $total_s +=  $amount;
                        }
                        if($request->type_arr[$id] == "Deduction"){
                            $total_s -=  $amount;
                        }
                    }
                    $employee = Employee::find($employee_id);
                    $employee->net_salary = $total_s;
                    $employee->save();
                }

            }
            DB::commit();
            return response()->json([
                'status'=>'yes',
                'msg'=>'Payroll Assign Successfully'
            ]);
        }catch(\Exception $e){
            DB::rollBack();
           // dd($e);
            return response()->json([
                'status'=>'no',
                'err'=>$e->getMessage(),
                'msg'=>"Something went wrong!"
            ]);
        }
    }
   
}
