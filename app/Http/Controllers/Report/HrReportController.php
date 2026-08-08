<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Hr\Attendance;
use App\Models\Hr\BonusPay;
use App\Models\Hr\EmpLoan;
use App\Models\Hr\LeaveApplication;
use App\Models\Hr\SalarySheet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HrReportController extends Controller
{
    function attendance(Request $request){
        if(can_p('report.attendance') == false){
            return redirect()->route('dashboard');
        }
        
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = Attendance::leftjoin('employees','employees.id','attendances.empID');
        $reports = $reports->whereBetween('attendances.dutyDate', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        if(!empty($request->department)){
            $reports = $reports->where('employees.department_id', $request->department);
        }
        if(!empty($request->designation)){
            $reports = $reports->where('employees.designation_id', $request->designation);
        }
        if(!empty($request->employee)){
            $reports = $reports->where('attendances.empID', $request->employee);
        }

        if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 10;
        }
        if(!empty($request->page)){
            $page = $request->page;
        }else{
            $page = 1;
        }
        $data['page']=$page;
        $data['per_page']=$per_page;
        // dd($reports->orderBy('attendances.dutyDate','asc')->get());
        //dd($per_page);
        $data['reports']= $reports->orderBy('attendances.dutyDate','asc')->paginate($per_page);
        return view('Reports.Hr.attendance', $data);
    }
    function salarySheet(Request $request){
        if(can_p('report.salary_sheet') == false){
            return redirect()->route('dashboard');
        }
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = SalarySheet::leftjoin('employees','employees.id','salary_sheets.empID')
        ->orderBy('salary_sheets.id','desc');
        $reports = $reports->whereBetween('salary_sheets.month', [$from_date, $to_date]);
        if(!empty($request->department)){
            $reports = $reports->where('employees.department_id', $request->department);
        }
        if(!empty($request->designation)){
            $reports = $reports->where('employees.designation_id', $request->designation);
        }
        if(!empty($request->employee)){
            $reports = $reports->where('salary_sheets.empID', $request->employee);
        }

       if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Hr.salary_sheet', $data);
    }
    function empLeave(Request $request){
        if(can_p('report.emp_leave') == false){
            return redirect()->route('dashboard');
        }

        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = LeaveApplication::leftjoin('employees','employees.id','leave_applications.empID')
        ->orderBy('leave_applications.id','desc');
        $reports = $reports->whereBetween('leave_applications.fromDate', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        if(!empty($request->department)){
            $reports = $reports->where('employees.department_id', $request->department);
        }
        if(!empty($request->designation)){
            $reports = $reports->where('employees.designation_id', $request->designation);
        }
        if(!empty($request->employee)){
            $reports = $reports->where('leave_applications.empID', $request->employee);
        }

       if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Hr.emp_leave', $data);
    }
    function empLoan(Request $request){
        if(can_p('report.emp_loan') == false){
            return redirect()->route('dashboard');
        }
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = EmpLoan::leftjoin('employees','employees.id','emp_loans.empID')
        ->orderBy('emp_loans.id','desc');
        $reports = $reports->whereBetween('emp_loans.loan_date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        if(!empty($request->department)){
            $reports = $reports->where('employees.department_id', $request->department);
        }
        if(!empty($request->designation)){
            $reports = $reports->where('employees.designation_id', $request->designation);
        }
        if(!empty($request->employee)){
            $reports = $reports->where('emp_loans.empID', $request->employee);
        }

       if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Hr.emp_loan', $data);
    }
    function empBonus(Request $request){
        if(can_p('report.emp_bonus') == false){
            return redirect()->route('dashboard');
        }
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $reports = BonusPay::leftjoin('employees','employees.id','bonus_pays.empID')
        ->orderBy('bonus_pays.id','desc');
        $reports = $reports->whereBetween('bonus_pays.paidDate', [$from_date, $to_date]);
        if(!empty($request->department)){
            $reports = $reports->where('employees.department_id', $request->department);
        }
        if(!empty($request->designation)){
            $reports = $reports->where('employees.designation_id', $request->designation);
        }
        if(!empty($request->employee)){
            $reports = $reports->where('bonus_pays.empID', $request->employee);
        }

       if(!empty($request->per_page)){
            $per_page = $request->per_page;
        }else{
            $per_page = 50;
        }
        $data['per_page']=$per_page;
        //dd($per_page);
        $data['reports']= $reports->paginate($per_page);
        return view('Reports.Hr.emp_bonus', $data);
    }

}
