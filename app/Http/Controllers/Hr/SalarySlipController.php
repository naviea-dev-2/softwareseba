<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Employee;
use App\Models\Hr\SalaryHead;
use App\Models\Hr\PayrollAsign;
use App\Models\Hr\SalaryDetails;
use App\Models\Hr\SalarySheet;
use App\Models\Hr\SalaryPayment;
use App\Models\LedgerTransaction;
use App\Models\InvoiceNumber;
use App\Models\Hr\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use Carbon\Carbon;

class SalarySlipController extends Controller
{
    public function payslipIndex()
    {
        return view("Hr.payslip.index");
    }
    public function payslipPrint(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'ac_year' => 'required',
            'month'   => 'required',
        ], [
            'ac_year.required' => 'Please select year',
            'month.required'   => 'Please select month',
        ]);

        // Custom rule: at least one of search_name, search_s_code, teacher must be filled
        $validator->after(function ($validator) use ($request) {
            if (
                empty($request->search_name) &&
                empty($request->search_s_code) &&
                empty($request->teacher)
            ) {
                $validator->errors()->add(
                    'search_group',
                    'Please select teacher or enter name or code'
                );
            }
        });
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if($request->teacher){
            $teacher = Teacher::find($request->teacher);
        }else if($request->search_name){
            $teacher = Teacher::where("name",$request->search_name)->first();
        }
        else if($request->search_name){
            $teacher = Teacher::where("teacher_code",$request->search_s_code)->first();
        }else{
            $teacher = Teacher::find($request->teacher);
        }
        if($teacher){
            $data['salary_sheet']=$salary_sheet = SalarySheet::where("teacher_id",$teacher->id)
            ->where("salary_sheets.acy_id",$request->ac_year)
            ->where("salary_sheets.month",$request->month)
            ->first();
            // dd($salary_sheet);
            if($salary_sheet){
                $data['payroll_list'] =$payroll_list = SalaryDetails::leftJoin('salary_heads',"salary_heads.id","salary_details.head_id")
                ->where('salary_details.salary_id',$salary_sheet->id)
                ->select("salary_heads.name","salary_heads.type","salary_details.head_amount")
                ->get()
                ->groupBy("type");
                // dd($payroll_list);
                $data['teacher'] =$teacher;
                return view("Hr.payslip.print",$data);
            }else{
                return back()->withError('Salary sheet is not found');
            }
           

        }else{
            return back()->withError('Teacher is not found');
        }
        
       
    }
    public function payslipPdf(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'ac_year' => 'required',
            'month'   => 'required',
        ], [
            'ac_year.required' => 'Please select year',
            'month.required'   => 'Please select month',
        ]);

        // Custom rule: at least one of search_name, search_s_code, teacher must be filled
        $validator->after(function ($validator) use ($request) {
            if (
                empty($request->search_name) &&
                empty($request->search_s_code) &&
                empty($request->teacher)
            ) {
                $validator->errors()->add(
                    'search_group',
                    'Please select teacher or enter name or code'
                );
            }
        });
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if($request->teacher){
            $teacher = Teacher::find($request->teacher);
        }else if($request->search_name){
            $teacher = Teacher::where("name",$request->search_name)->first();
        }
        else if($request->search_name){
            $teacher = Teacher::where("teacher_code",$request->search_s_code)->first();
        }else{
            $teacher = Teacher::find($request->teacher);
        }
        if($teacher){
            $data['salary_sheet']=$salary_sheet = SalarySheet::where("teacher_id",$teacher->id)
            ->where("salary_sheets.acy_id",$request->ac_year)
            ->where("salary_sheets.month",$request->month)
            ->first();
            // dd($salary_sheet);
            if($salary_sheet){
                $data['payroll_list'] =$payroll_list = SalaryDetails::leftJoin('salary_heads',"salary_heads.id","salary_details.head_id")
                ->where('salary_details.salary_id',$salary_sheet->id)
                ->select("salary_heads.name","salary_heads.type","salary_details.head_amount")
                ->get()
                ->groupBy("type");
                // dd($payroll_list);
                $data['teacher'] =$teacher;
                $mpdf = new mPDF([
                    'mode' => 'UTF-8',
                    'margin_left' => 5,
                    'margin_right' => 5,
                    'margin_top' => 5,
                    'margin_bottom' => 0,
                    'margin_header' => 0,
                    'margin_footer' => 0,
                    // 'orientation'=>'L'
                ]);
                $html= view('Hr.payslip.pdf', $data);
                //For Multilanguage Start
                $mpdf->autoScriptToLang = true;
                $mpdf->baseScript = 1;
                $mpdf->autoLangToFont = true;
                $mpdf->autoVietnamese = true;
                $mpdf->autoArabic = true;

                //For Multilanguage End
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->setAutoBottomMargin = 'stretch';
                $mpdf->writeHTML($html);
                $name = 'payslip-pdf ' . date('Y-m-d i:h:s');
                $mpdf->Output($name.'.pdf', 'D');
                // return view("Hr.payslip.print",$data);
            }else{
                return back()->withError('Salary sheet is not found');
            }
           

        }else{
            return back()->withError('Teacher is not found');
        }
        
       
    }

    public function index()
    {
        return view("Hr.salary_slip.index");
    }

    function search(Request $request){
        try{
            $data['per_page']=$per_page=$request->per_page ?? 10;
            $data['select_student']=$request->select_student ?? [];
            $data['search_name']=$request->search_name ?? '';
            $data['chk_advance']=$request->chk_advance ?? 0;
            $data['chk_due']=$request->chk_due ?? 0;
            $data['search_s_code']=$request->search_s_code ?? '';
            $data['search_designation']=$request->search_designation ?? '';
            $s_ids = SalarySheet::where('salary_sheets.month',$request->month)->select('empID')->get()->pluck('empID');
            $employees = Employee::whereNotIn('employees.id',$s_ids)
            ->where('employees.status',1);
            
            if($request->search_s_code){
                $employees =  $employees->where('employees.employee_id','Like','%'.$request->search_s_code.'%');
            }
            if($request->search_name){
                $employees =  $employees->where('employees.employee_name','Like','%'.$request->search_name.'%');
            }

            // $data['head_arr']=$request->head_arr;
            $data['is_p']=$request->is_p;
            $data['employees']=$employees = $employees->select('employees.id','employees.designation_id','employees.employee_name','employees.employee_id','employees.salary','employees.net_salary','employees.total_advance','employees.total_due')
            ->paginate($per_page);

            $data['head_list'] =$head_list= SalaryHead::where('status',1)->get();
          
            $data['payroll_list'] =$payroll_list = PayrollAsign::whereIn('employee_id',$employees->pluck('id'))->get();
            // dd($payroll_list);
            if($head_list->count() == 0){
                return response()->json(['status'=>'no','msg'=>'Fee Head is not setup']);
            }
            $html =  view('Hr.salary_slip.ajax-employee-list',$data)->render();

            if($request->is_p == 0){
                return response()->json(['status'=>'yes','html'=>$html,'t_count'=>$employees->count()]);
            }else{
                $data['data_list']=$employees;
                $paginate =  view('Hr.payroll_assign.paginate',$data)->render();
                return response()->json(['status'=>'yes','html'=>$html,'paginate'=>$paginate,'t_count'=>$employees->count()]);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'no','err'=>$e->getMessage(),'msg'=>"Something went wrong!"]);
        }

    }
    public function store(Request $request)
    {
        dd($request->all());
        $validator = Validator::make($request->all(), [
            'year' => 'required',
            'month' => 'required',
            'teachers' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
    
        $month       = MonthManage::where('monthDate', $request->monthDate)->firstOrFail();
        $userId      = auth()->guard('school')->user()->id;
        $year        = substr($request->monthDate, 0, 4);
        $monthNum    = substr($request->monthDate, 5, 2);
        $daysInMonth = (int) $month->monthTotalDay;
        $lateRoll    = LateRoll::first();
        $lateRoll    = ($lateRoll && $lateRoll->late > 0) ? $lateRoll : null;
        $periodStart = Carbon::createFromFormat('Y-m-d', $request->monthDate.'-1')->startOfDay();
        $periodEnd   = Carbon::createFromFormat('Y-m-d', $request->monthDate.'-'.$daysInMonth)->startOfDay();
        $startStr    = $periodStart->toDateTimeString();
        $endStr      = $periodEnd->copy()->endOfDay()->toDateTimeString();

        $chkAdvance = (int) $request->chk_advance === 1;
        $chkDue     = (int) $request->chk_due === 1;
        $desIds      = $request->des_ids ?? [];
        $typeArr     = $request->type_arr ?? [];
        $payAdvances = $request->pay_advances ?? [];
        $payDues     = $request->pay_dues ?? [];
        $countFridays = function (Carbon $from, Carbon $to): int {
            $days = (int) $from->diffInDays($to) + 1;
            $firstFriday = (Carbon::FRIDAY - $from->dayOfWeek + 7) % 7;
            return $days > $firstFriday ? intdiv($days - 1 - $firstFriday, 7) + 1 : 0;
        };
        // Load employees and all related data in bulk
        $employeeIds = collect($request->employees)
            ->reject(fn ($id) => (int) $id === -1)
            ->unique()
            ->values();
        $employees = Employee::whereIn('id', $employeeIds)->get()->keyBy('id');
        abort_if($employees->count() !== $employeeIds->count(), 404, 'Employee not found');

        $attendance = Attendance::whereIn('empID', $employeeIds)
        ->whereBetween('dutyDate', [$startStr, $endStr])
        ->selectRaw("empID, COUNT(*) AS days, SUM(status = 'late') AS lates")
        ->groupBy('empID')
        ->get()
        ->keyBy('empID');

        $leaveDays = LeaveApplication::whereIn('empID', $employeeIds)
            ->where('status', 1)
            ->where('leavePartID', 'Full Day')
            ->where('fromDate', '>=', $startStr)
            ->where('toDate', '<=', $endStr)
            ->selectRaw('empID, SUM(leaveDay) AS days')
            ->groupBy('empID')
            ->pluck('days', 'empID');

        $holidays = Holiday::where('startDate', '>=', $startStr)
            ->where('endDate', '<=', $endStr)
            ->get(['startDate', 'day'])
            ->map(fn ($h) => [
                'start' => Carbon::parse($h->startDate)->startOfDay(),
                'day'   => (int) $h->day,
            ]);

        $payrollsByEmployee = PayrollAsign::whereIn('employee_id', $employeeIds)
            ->get()
            ->groupBy('employee_id');
        // Employees with no attendance this month are skipped
        $skipped  = $employeeIds->reject(fn ($id) => $attendance->has($id))->values();
        $eligible = $employeeIds->filter(fn ($id) => $attendance->has($id))->values();
        $now        = now()->toDateTimeString();
        $detailRows = [];
        $creditRows = [];
        $debitRows  = [];
        try {
            DB::beginTransaction();
            // Lock the invoice counter once for the whole run
            $invoice = InvoiceNumber::where('type', 'Salary')
                ->where('year', $year)
                ->where('month', $monthNum)
                ->where('business_id', $userId)
                ->lockForUpdate()
                ->first();
            if (! $invoice) {
                $invoice = InvoiceNumber::create([
                    'type'        => 'Salary',
                    'year'        => $year,
                    'month'       => $monthNum,
                    'business_id' => $userId,
                    'in_number'   => 0,
                ]);
            }
            $counter = (int) $invoice->in_number;
            foreach ($eligible as $employeeId) {
                $employee = $employees[$employeeId];
                $stats    = $attendance[$employeeId];
                $joinDate = Carbon::parse($employee->join_date)->startOfDay();

                $isFullMonth = $joinDate->lte($periodStart);
                $leave       = (float) ($leaveDays[$employeeId] ?? 0);
                $lateAbsent  = $lateRoll ? intdiv((int) $stats->lates, $lateRoll->late) * $lateRoll->absent : 0;
                $workedDays  = $stats->days + $leave - $lateAbsent;

                if ($isFullMonth) {
                    $workingDay  = $daysInMonth;
                    $holidayDays = (int) $month->holiday;
                } else {
                    $workingDay  = (int) $joinDate->diffInDays($periodEnd) + 1;
                    $holidayDays = $countFridays($joinDate, $periodEnd)
                        + $holidays->filter(fn ($h) => $h['start']->gte($joinDate))->sum('day');
                }

                $absentDays = $workingDay - ($workedDays + $holidayDays);

                $fine = 0;
                if ($absentDays > 0 && $daysInMonth > 0) {
                    $fine = min(round($employee->salary / $daysInMonth * $absentDays, 2), $employee->salary);
                }

                // Payroll heads
                $totalS   = 0;
                $headRows = [];
                foreach ($payrollsByEmployee->get($employeeId, collect()) as $payroll) {
                    $amount = $payroll->head_amount;
                    $headRows[] = ['head_id' => $payroll->head_id, 'head_amount' => $amount];

                    $type = $typeArr[$payroll->head_id] ?? null;
                    if ($type === 'Addition') {
                        $totalS += $amount;
                    } elseif ($type === 'Deduction') {
                        $totalS -= $amount;
                    }
                }

                $advance = $chkAdvance ? (float) ($payAdvances[$employeeId] ?? 0) : 0;
                $due     = $chkDue ? (float) ($payDues[$employeeId] ?? 0) : 0;
                $totalS  = $totalS - $fine - $advance + $due;

                // Salary sheet, saved once with final totals
                $counter++;
                $sheet = new SalarySheet;
                $sheet->invoice_code   = $year.str_pad($counter, 6, '0', STR_PAD_LEFT);
                $sheet->employee_id    = $employeeId;
                $sheet->month          = $request->monthDate;
                $sheet->designation_id = $desIds[$employeeId] ?? null;
                $sheet->absent_days    = $absentDays;
                $sheet->attendance_fine = $fine;
                if ($chkAdvance) {
                    $sheet->payable_advance = $advance;
                }
                if ($chkDue) {
                    $sheet->payable_due = $due;
                }
                $sheet->net_salary     = $totalS;
                $sheet->payable_amount = $totalS;
                $sheet->due_amount     = $totalS;
                $sheet->save();

                foreach ($headRows as $row) {
                    $detailRows[] = [
                        'salary_id'   => $sheet->id,
                        'teacher_id'  => $employeeId,
                        'head_id'     => $row['head_id'],
                        'head_amount' => $row['head_amount'],
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];
                }

                $base = [
                    'trans_date'  => $endStr,
                    'trans_type'  => 'Salary Due',
                    'r_id'        => $sheet->id,
                    'voucher_id'  => $sheet->id,
                    'fund_id'     => 0,
                    'created_by'  => $userId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
                $creditRows[] = $base + [
                    'r_type'        => 'Credit',
                    'ledger_id'     => $payrollMapping->salary_due_ledger_id,
                    'credit_amount' => $totalS,
                    'is_depend'     => 1,
                ];
                $debitRows[] = $base + [
                    'r_type'       => 'Debit',
                    'ledger_id'    => $payrollMapping->salary_ledger_id,
                    'debit_amount' => $totalS,
                ];

                // Single UPDATE per employee, with advance reset folded in
                Employee::whereKey($employeeId)->increment(
                    'total_due',
                    $totalS,
                    $chkAdvance ? ['total_advance' => 0] : []
                );
            }

            $invoice->in_number = $counter;
            $invoice->save();

            foreach (array_chunk($detailRows, 500) as $chunk) {
                SalaryDetails::insert($chunk);
            }
            foreach (array_chunk($creditRows, 500) as $chunk) {
                LedgerTransaction::insert($chunk);
            }
            foreach (array_chunk($debitRows, 500) as $chunk) {
                LedgerTransaction::insert($chunk);
            }
         // }

            // foreach ($request->employees as $employee_id) {
            //     if ($employee_id == -1) {
            //         continue;
            //     }
            //     $employee = Employee::findOrFail($employee_id);
            //     $hasAttendance = Attendance::where('empID', $employee_id)
            //         ->where('dutyDate', 'LIKE', $request->monthDate.'%')
            //         ->exists();

            //     if (! $hasAttendance) {
            //         $skipped[] = $employee_id;
            //         continue;
            //     }
            //      DB::transaction(function () use ($request, $employee, $employee_id, $month, $days_in_month, $lateRoll, $payrollMapping, $trans_date, $user_id, $year, $monthNum, $startDate, $endDate) {
            //         $joiningDate = Carbon::parse($employee->join_date)->startOfDay();
            //         $isFullMonth = $joiningDate->lte($startDate);
            //         $from        = $isFullMonth ? $startDate : $joiningDate;
            //         // Attendance and leave for the employee's period
            //         $leaveDay = LeaveApplication::where('empID', $employee_id)
            //             ->where('status', 1)
            //             ->where('leavePartID', 'Full Day')
            //             ->where('fromDate', '>=', $from->toDateString())
            //             ->where('toDate', '<=', $endDate->toDateString())
            //             ->sum('leaveDay');
            //         $attendance = Attendance::where('empID', $employee_id)->whereBetween('dutyDate', [$from->toDateString(), $endDate->toDateString()]);
            //         $late          = (clone $attendance)->where('status', 'late')->count();
            //         $empWorkingDay = (clone $attendance)->count();
            //         $lateAbsent = ($lateRoll && $lateRoll->late > 0) ? intdiv($late, $lateRoll->late) * $lateRoll->absent : 0;
            //         $totalWorkingDay = $empWorkingDay + $leaveDay - $lateAbsent;
            //         if ($isFullMonth) {
            //             $holidayDays = $month->holiday;
            //             $workingDay  = $month->monthTotalDay;
            //         } else {
            //             $fridays = 0;
            //             for ($d = $joiningDate->copy(); $d->lte($endDate); $d->addDay()) {
            //                 if ($d->dayOfWeek == Carbon::FRIDAY) {
            //                     $fridays++;
            //                 }
            //             }
            //             $holidayDays = $fridays + Holiday::where('startDate', '>=', $joiningDate->toDateString())
            //                 ->where('endDate', '<=', $endDate->toDateString())
            //                 ->sum('day');
            //             $workingDay = (int) $joiningDate->diffInDays($endDate) + 1;
            //         }
            //         $absent_days = $workingDay - ($totalWorkingDay + $holidayDays);
            //         $attendance_fine = 0;
            //         if ($absent_days > 0 && $days_in_month > 0) {
            //             $per_day_rate    = $employee->salary / $days_in_month;
            //             $attendance_fine = min(round($per_day_rate * $absent_days, 2), $employee->salary);
            //         }
            //         $invoice = InvoiceNumber::where('type', 'Salary')
            //             ->where('year', $year)
            //             ->where('month', $monthNum)
            //             ->where('business_id', $user_id)
            //             ->lockForUpdate()
            //             ->first();

            //         if (! $invoice) {
            //             $invoice = InvoiceNumber::create([
            //                 'type'        => 'Salary',
            //                 'year'        => $year,
            //                 'month'       => $monthNum,
            //                 'business_id' => $user_id,
            //                 'in_number'   => 0,
            //             ]);
            //         }
            //         $invoice->increment('in_number');
            //         $salary_sheet = new SalarySheet;
            //         $salary_sheet->invoice_code   = $year.str_pad($invoice->in_number, 6, '0', STR_PAD_LEFT);
            //         $salary_sheet->employee_id    = $employee_id;
            //         $salary_sheet->month          = $request->monthDate;
            //         $salary_sheet->designation_id = $request->des_ids[$employee_id];
            //         $salary_sheet->absent_days    = $absent_days;
            //         $salary_sheet->attendance_fine = $attendance_fine;
            //         $salary_sheet->save();

            //         // Payroll heads
            //         $total_s      = 0;
            //         $payroll_list = PayrollAsign::where('employee_id', $employee_id)->get();

            //         foreach ($payroll_list as $payroll) {
            //             $head_amount = $payroll->head_amount;

            //             $salary_details = new SalaryDetails;
            //             $salary_details->salary_id    = $salary_sheet->id;
            //             $salary_details->teacher_id   = $employee_id;
            //             $salary_details->head_id      = $payroll->head_id;
            //             $salary_details->head_amount  = $head_amount;
            //             $salary_details->save();

            //             $type = $request->type_arr[$payroll->head_id] ?? null;
            //             if ($type === 'Addition') {
            //                 $total_s += $head_amount;
            //             } elseif ($type === 'Deduction') {
            //                 $total_s -= $head_amount;
            //             }
            //         }

            //         // Fines, advances, dues
            //         $total_s -= $attendance_fine;

            //         if ($request->chk_advance == 1) {
            //             $advance = $request->pay_advances[$employee_id] ?? 0;
            //             $total_s -= $advance;
            //             $salary_sheet->payable_advance = $advance;
            //         }

            //         if ($request->chk_due == 1) {
            //             $due = $request->pay_dues[$employee_id] ?? 0;
            //             $total_s += $due;
            //             $salary_sheet->payable_due = $due;
            //         }

            //         $salary_sheet->net_salary     = $total_s;
            //         $salary_sheet->payable_amount = $total_s;
            //         $salary_sheet->due_amount     = $total_s;
            //         $salary_sheet->save();
            //         $employee->total_due += $total_s;
            //         if ($request->chk_advance == 1) {
            //             $employee->total_advance = 0;
            //         }
            //         $employee->save();
            //     });
            // }

            
            // $days_in_month =$month->monthTotalDay;
          
            // $user_id =  auth()->guard('school')->user()->id;
            // foreach($request->employees as $employee_id){
            //     if($employee_id != -1){
            //         $lastAttendance=Attendance::orderBy('dutyDate','DESC')->where('dutyDate','LIKE',$request->monthDate.'%')->first();
            //         if($lastAttendance == null){
            //             $notification=array(
            //                 'message'=>"Employee Has No Attendance",
            //                 'alert-type'=>'error'
            //             );
            //             return back()->with($notification);
            //         }
            //         $employee = Employee::find($employee_id);
                    
            //         $to =Carbon::createFromFormat('Y-m-d', $request->monthDate.'-'.$month->monthTotalDay);
            //         $from =Carbon::createFromFormat('Y-m-d', $employee->join_date);
            //         $dateDiff = $from->diffInDays($to);
            //         $dateDiff+=1;

            //         $startDate=$request->monthDate.'-1';
            //         $startDate=Carbon::createFromFormat('Y-m-d', $startDate);
            //         $endDate=$request->monthDate.'-'.$month->monthTotalDay;
            //         $endDate=Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
            //         $absent_days = 0;
            //         if($dateDiff>=$month->monthTotalDay){
            //             $leaveDay=LeaveApplication::where('fromDate','>=',$startDate)->where('toDate','<=',$endDate)->where('empID',$employee_id)->where('status',1)->where('leavePartID','Full Day')->sum('leaveDay');
            //             $late=Attendance::where('dutyDate','>=',$startDate)->where('dutyDate','<=',$endDate)->where('empID',$employee_id)->where('status','late')->count();
            //             $empWorkingDay=Attendance::where('dutyDate','>=',$startDate)->where('dutyDate','<=',$endDate)->where('empID',$employee_id)->count();
            //             $overTimeMinute=Attendance::where('dutyDate','>=',$startDate)->where('dutyDate','<=',$endDate)->where('empID',$employee_id)->sum('overtimeMiniute');
            //             $workingTime=Attendance::where('dutyDate','>=',$startDate)->where('dutyDate','<=',$endDate)->where('empID',$employee_id)->sum('workingMiniute');
            //             $workingDay=$month->monthTotalDay;
            //             $totalWorkingDay=($empWorkingDay+$leaveDay)-(intval($late/LateRoll::first()->late));
            //             $absent_days=$workingDay-($totalWorkingDay+$month->holiday);
            //         }else{
            //             $joiningDate = Carbon::parse($employee->join_date)->startOfDay();
            //             $workingDays = collect();
            //             for ($date = $joiningDate->copy(); $date->lte($endDate); $date->addDay()) {
            //                 if ($date->dayOfWeek == 5) {
            //                     $workingDays->push($date->toDateString());
            //                 }
            //             }
            //             $holiday=Holiday::where('startDate','>=',$joiningDate)->where('endDate','<=',$endDate)->sum('day');
            //             $total_holy_Day = $workingDays->count()+$holiday;
            //             $leaveDay=LeaveApplication::where('fromDate','>=',$joiningDate)->where('toDate','<=',$endDate)->where('empID',$employee_id)->where('status',1)->where('leavePartID','Full Day')->sum('leaveDay');
            //             $late=Attendance::where('dutyDate','>=',$joiningDate)->where('dutyDate','<=',$endDate)->where('empID',$employee_id)->where('status','late')->count();
            //             $empWorkingDay=Attendance::where('dutyDate','>=',$joiningDate)->where('dutyDate','<=',$endDate)->where('empID',$employee_id)->count();
            //             $overTimeMinute=Attendance::where('dutyDate','>=',$joiningDate)->where('dutyDate','<=',$endDate)->where('empID',$employee_id)->sum('overtimeMiniute');
            //             $workingTime=Attendance::where('dutyDate','>=',$joiningDate)->where('dutyDate','<=',$endDate)->where('empID',$employee_id)->sum('workingMiniute');
            //             $totalWorkingDay=($empWorkingDay+$leaveDay)-(intval($late/LateRoll::first()->late));
            //             $workingDay=$month->monthTotalDay;
            //             $absent_days=$workingDay-($totalWorkingDay+$total_holy_Day);
            //         }
            //         $attendance_fine = 0;    
            //         if($absent_days > 0){
            //             $per_day_rate = $days_in_month > 0 ? ($employee->salary / $days_in_month) : 0;
            //             $attendance_fine = min(round($per_day_rate * $absent_days, 2), $employee->salary);
            //         }
            //         $invoice_m= InvoiceNumber::where('type',"Salary")->where('year',date('Y'))->where('month',date('m'))->first();
            //         if($invoice_m){
            //             $invoice_m->in_number = $invoice_m->in_number+1;
            //             $invoice_m->save();
            //         }else{
            //             $invoice_m = new InvoiceNumber;
            //             $invoice_m->acy_id = $request->year;
            //             $invoice_m->in_number = 1;
            //             $invoice_m->type = 'Salary';
            //             $invoice_m->save();
            //         }
            //         $salary_sheet = new SalarySheet;
            //         $salary_sheet->invoice_code = date('Y').str_pad($invoice_m->in_number, 6, '0', STR_PAD_LEFT);
            //         $salary_sheet->employee_id = $employee_id;
            //         $salary_sheet->month=$request->monthDate;
            //         $salary_sheet->designation_id = $request->des_ids[$employee_id];
            //         $salary_sheet->absent_days = $absent_days;
            //         $salary_sheet->attendance_fine = $attendance_fine;
            //         $salary_sheet->save();

            //         $total_s = 0;
            //         $payroll_list = PayrollAsign::where('employee_id',$employee_id)->get();
            //         // dd($payroll_list);
            //         foreach($payroll_list as $payroll){
                       

            //             $salary_details = new SalaryDetails();
            //             $salary_details->salary_id = $salary_sheet->id;
            //             $salary_details->teacher_id = $employee_id;
            //             $salary_details->head_id = $payroll->head_id;
            //             $salary_details->head_amount = $head_amount;
            //             $salary_details->save();
            //             if(($request->type_arr[$payroll->head_id] ?? null) == "Addition"){
            //                 $total_s +=  $head_amount;
            //             }
            //             if(($request->type_arr[$payroll->head_id] ?? null) == "Deduction"){
            //                 $total_s -=  $head_amount;
            //             }
            //         }
            //         if($attendance_fine > 0){
            //             $total_s -= $attendance_fine;
            //         }
            //         if($request->chk_advance == 1){
            //             $total_s -= $request->pay_advances[$employee_id];
            //             $salary_sheet->payable_advance = $request->pay_advances[$employee_id];
            //         }
            //         if($request->chk_due == 1){
            //             $total_s += $request->pay_dues[$employee_id];
            //             $salary_sheet->payable_due = $request->pay_dues[$employee_id];
            //         }
            //         $salary_sheet->net_salary = $total_s;
            //         $salary_sheet->payable_amount = $total_s;
            //         $salary_sheet->due_amount =  $total_s;
            //         $salary_sheet->save();
                    
            //         $trans = new LedgerTransaction();
            //         $trans->trans_type = "Salary Due";
            //         $trans->r_type = "Credit";
            //         $trans->r_id = $salary_sheet->id;
            //         $trans->voucher_id = $salary_sheet->id;
            //         $trans->fund_id = 0;
            //         $trans->ledger_id = $payrollMapping->salary_due_ledger_id;
            //         $trans->trans_date =  $month_date ? $month_date : now();
            //         $trans->credit_amount = $total_s;
            //         $trans->created_by =  $user_id;
            //         $trans->is_depend =  1;
            //         $trans->save();

            //         $trans = new LedgerTransaction();
            //         $trans->trans_date =  $month_date ? $month_date : now();
            //         $trans->trans_type = "Salary Due";
            //         $trans->r_type = "Debit";
            //         $trans->r_id = $salary_sheet->id;
            //         $trans->voucher_id = $salary_sheet->id;
            //         $trans->fund_id = 0;
            //         $trans->ledger_id = $payrollMapping->salary_ledger_id;
            //         $trans->debit_amount = $total_s;
            //         $trans->created_by =  $user_id;
            //         $trans->save();

            //         $teacher = Teacher::find($t_id);
            //         // if($request->chk_due == 1){
            //             $teacher->total_due  += $total_s;
            //         // }
            //         if($request->chk_advance == 1){
            //             $teacher->total_advance  = 0;
            //         }
                    
            //         $teacher->save();
            //     }

            // }
            // DB::commit();
            // return response()->json([
            //     'status'=>'yes',
            //     'msg'=>'salary sheet created successfully'
            // ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status'=>'no',
                'err'=>$e->getMessage(),
                'msg'=>"Something went wrong!"
            ]);
        }
    }
    public function indexPayment()
    {
        return view("Hr.salary_payment.index");
    }
    function searchPayment(Request $request){
        try{
            $data['per_page']=$per_page=$request->per_page ?? 10;
            $data['select_student']=$request->select_student ?? [];
            $data['search_name']=$request->search_name ?? '';
            $data['search_s_code']=$request->search_s_code ?? '';
            $data['search_designation']=$request->search_designation ?? '';

            // $s_ids = SalarySheet::where('acy_id',$request->year)
            // ->where('salary_sheets.month',$request->month)->select('teacher_id')->get()->pluck('teacher_id');

            $teachers = Teacher::leftJoin('salary_sheets','salary_sheets.teacher_id','teachers.id')
            ->where('salary_sheets.acy_id',$request->year)
            ->where('salary_sheets.month',$request->month)
            ->where('teachers.status',1)
            ->where('salary_sheets.payment_status',0);

            if($request->search_s_code){
                $teachers =  $teachers->where('teachers.teacher_code','Like','%'.$request->search_s_code.'%');
            }
            if($request->search_name){
                $teachers =  $teachers->where('teachers.name','Like','%'.$request->search_name.'%');
            }

            $data['is_p']=$request->is_p;
            $data['paid_v']=$request->paid_v;
            $data['teachers']=$teachers = $teachers->select('salary_sheets.id','teachers.id as t_id','teachers.name','teachers.teacher_code','salary_sheets.net_salary','salary_sheets.due_amount','salary_sheets.payable_amount')->paginate($per_page);

            $data['head_list'] =$head_list= SalaryHead::where('status',1)->get();
            // dd($teachers->pluck('id'));
            $data['payroll_list'] =$payroll_list = SalaryDetails::whereIn('salary_id',$teachers->pluck('id'))->get();
            // dd($payroll_list);
            if($head_list->count() == 0){
                return response()->json(['status'=>'no','msg'=>'Head is not setup']);
            }
            $html =  view('Hr.salary_payment.ajax-teacher-list',$data)->render();

            if($request->is_p == 0){
                return response()->json(['status'=>'yes','html'=>$html,'t_count'=>$teachers->count()]);
            }else{
                $paginate =  view('school.student.paginate',$data)->render();
                return response()->json(['status'=>'yes','html'=>$html,'paginate'=>$paginate,'t_count'=>$teachers->count()]);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'no','msg'=>"Something went wrong!"]);
        }

    }
    public function storePayment(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'teachers' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        try{
            DB::beginTransaction();
            $payrollMapping = PayrollMapping::first();
            $user_id =  auth()->guard('school')->user()->id;
            if($payrollMapping){
                $total_fund=0;
                foreach($request->teachers as $t_id){
                    if($t_id != -1){
                        $salary_sheet = SalarySheet::find($t_id);
                        if($salary_sheet){
                            $teacher = Teacher::find($salary_sheet->teacher_id);
                            if($teacher){
                                $teacher->total_due -= $request->paid_v[$t_id];
                                $teacher->save();
                            }

                            $salary_sheet->due_amount = $salary_sheet->payable_amount- $request->paid_v[$t_id];
                            $salary_sheet->paid_amount = $request->paid_v[$t_id];
                            $salary_sheet->payment_status = $salary_sheet->due_amount <= 0 ? 1 : 0;
                            $salary_sheet->save();

                            $in_code = $this->crateInvoice('Payment');
                            $salaryPayment = new SalaryPayment;
                            $salaryPayment->acy_id = $salary_sheet->acy_id;
                            $salaryPayment->teacher_id = $salary_sheet->teacher_id;
                            $salaryPayment->salary_sheet_id = $salary_sheet->id;
                            $salaryPayment->invoice_code =  $in_code;
                            $salaryPayment->payment_date = $request->payment_date;
                            $salaryPayment->payment_type = 'Salary Payment';
                            $salaryPayment->paid_by = $request->paid_by;
                            $salaryPayment->paid_amount =$request->paid_v[$t_id];
                            $salaryPayment->save();

                            $total_fund += $request->paid_v[$t_id];

                            $fund_history = new FundHistory;
                            $fund_history->t_type = 'out';
                            $fund_history->voucher_date = $request->payment_date;
                            $fund_history->fund_id = $payrollMapping->fund_id;
                            $fund_history->voucher_id = $salaryPayment->id;
                            $fund_history->fund_out = $request->paid_v[$t_id];
                            $fund_history->created_by =  $user_id;
                            $fund_history->r_type =  'Salary Payment';
                            $fund_history->save();

                            $trans = new LedgerTransaction();
                            $trans->trans_type = "Salary Payment";
                            $trans->r_type = "Credit";
                            $trans->r_id = $salaryPayment->id;
                            $trans->voucher_id = $salaryPayment->id;
                            $trans->fund_id = $payrollMapping->fund_id;
                            $trans->ledger_id = $request->paid_by;
                            $trans->trans_date =  $request->payment_date;
                            $trans->credit_amount = $request->paid_v[$t_id];
                            $trans->created_by =  $user_id;
                            $trans->is_depend =  1;
                            $trans->save();

                            $trans = new LedgerTransaction();
                            $trans->trans_date =  $request->payment_date;
                            $trans->trans_type = "Salary Payment";
                            $trans->r_type = "Debit";
                            $trans->r_id = $salaryPayment->id;
                            $trans->voucher_id = $salaryPayment->id;
                            $trans->fund_id = $payrollMapping->fund_id;
                            $trans->ledger_id = $payrollMapping->salary_due_ledger_id;
                            $trans->debit_amount = $request->paid_v[$t_id];
                            $trans->created_by =  $user_id;
                            $trans->save();

                            
                        }

                    }

                }
                $fund = Fund::find($payrollMapping->fund_id);
                if($fund){
                    $fund->amount_out += $total_fund;
                    $fund->balance -= $total_fund;
                    $fund->save();
                }
            }else{
                return response()->json([
                    'status'=>'no',
                    'msg'=>"please setup payroll mapping"
                ]);
            }
            DB::commit();
            return response()->json([
                'status'=>'yes',
                'msg'=>'salary payment created successfully'
            ]);
        }catch(\Throwable $e){
            DB::rollBack();
           // dd($e);
            return response()->json([
                'status'=>'no',
                'err'=>$e->getMessage(),
                'msg'=>"Something went wrong!"
            ]);
        }
    }
    function crateInvoice($type){
        $year = date('Y');
        $month = date('m');
        $invoice_m= InvoiceNumber::where('type',$type)->where('year',$year)->where('month',$month)->first();
        if($invoice_m){
            $invoice_m->in_number = $invoice_m->in_number+1;
            $invoice_m->save();
        }else{
            $invoice_m = new InvoiceNumber;
            $invoice_m->year = $year;
            $invoice_m->month = $month;
            $invoice_m->in_number = 1;
            $invoice_m->type = $type;
            $invoice_m->save();
        }
        return $year.$month.str_pad($invoice_m->in_number, 6, '0', STR_PAD_LEFT);
    }
    function deletePayment(Request $request){
        $validator = Validator::make($request->all(), [
            'teachers' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        try{
            DB::beginTransaction();
            foreach ($request->teachers as $salarySheetId) {
                if ((int) $salarySheetId === -1) {
                    continue;
                }
                $salarySheet = SalarySheet::find($salarySheetId);
                if (!$salarySheet) {
                    continue;
                }

                $payments = SalaryPayment::where('salary_sheet_id', $salarySheet->id)
                ->where('payment_type', 'Salary Payment')
                ->get([
                    'id',
                    'paid_amount',
                ]);
                // if ($payments->isEmpty()) {
                //     $salarySheet->update([
                //         'due_amount'     => $salarySheet->payable_amount,
                //         'paid_amount'    => 0,
                //         'payment_status' => 0,
                //     ]);
                //     continue;
                // }
                $totalPaid = (float) $payments->sum('paid_amount');
                $paymentIds = $payments->pluck('id')->values();
                $history_list = FundHistory::where('r_type', 'Salary Payment')
                ->whereIn('voucher_id', $paymentIds)
                ->get();
                foreach($history_list as $history){
                    $fund = Fund::find($history->fund_id);
                    if($fund){
                        $fund->decrement('amount_out', $history->fund_out);
                        $fund->increment('balance', $history->fund_out);
                    }
                    $history->delete();
                }
                LedgerTransaction::where('trans_type', 'Salary Payment')
                ->whereIn('voucher_id', $paymentIds)
                ->delete();
                LedgerTransaction::where('trans_type', 'Salary Due')
                ->where('voucher_id', $salarySheet->id)
                ->delete();
                    
                SalaryPayment::whereIn('id', $paymentIds)->delete();
                SalaryDetails::where('salary_id', $salarySheet->id)->delete();
                $teacher = Teacher::find($salarySheet->teacher_id);
                if($teacher){
                    $t_due = $salarySheet->payable_amount - $totalPaid;
                    $teacher->total_due  -= $t_due;
                    $teacher->total_advance  += $salarySheet->payable_advance;
                    $teacher->save();
                }
                $salarySheet->delete();
                // if ($salarySheet->teacher_id && $totalPaid > 0) {
                //     Teacher::where('id', $salarySheet->teacher_id)->increment('total_due', $totalPaid);
                // }
                // $salarySheet->update([
                //     'due_amount'     => $salarySheet->payable_amount,
                //     'paid_amount'    => 0,
                //     'payment_status' => 0,
                // ]);
            }
            // foreach($request->teachers as $t_id){
            //     if($t_id != -1){
            //         $salary_sheet = SalarySheet::find($t_id);
            //         if($salary_sheet){
            //             $payments = SalaryPayment::where('salary_sheet_id', $salarySheet->id)
            //             ->where('payment_type', 'Salary Payment')
            //             ->get([
            //                 'id',
            //                 'paid_amount',
            //                 'fund_id',
            //             ]);
            //             if ($payments->isEmpty()) {
            //                 $salarySheet->update([
            //                     'due_amount'     => $salarySheet->payable_amount,
            //                     'paid_amount'    => 0,
            //                     'payment_status' => 0,
            //                 ]);

            //                 continue;
            //             }

            //             $payment_list = SalaryPayment::where('salary_sheet_id',$salary_sheet->id)->where('payment_type','Salary Payment')->get();
            //             $total_paid = 0;
            //             foreach($payment_list as $payment){
            //                 $total_paid += $payment->paid_amount;

            //                 $fund_history = FundHistory::where('r_type','Salary Payment')->where('voucher_id',$payment->id)->first();
            //                 if($fund_history){
            //                     $fund_history->delete();
            //                 }
            //                 $tran_list = LedgerTransaction::where('trans_type','Salary Payment')->where('voucher_id',$payment->id)->get();
            //                 foreach($tran_list as $tran){
            //                     $tran->delete();
            //                 }
            //                 $fund = Fund::find($payment->fund_id);
            //                 if($fund){
            //                     $fund->amount_out -= $payment->paid_amount;
            //                     $fund->balance += $payment->paid_amount;
            //                     $fund->save();
            //                 }
            //                 $payment->delete();
            //             }
            //             $teacher = Teacher::find($salary_sheet->teacher_id);
            //             if($teacher){
            //                 $teacher->total_due += $total_paid;
            //                 $teacher->save();
            //             }
            //             $salary_sheet->due_amount = $salary_sheet->payable_amount;
            //             $salary_sheet->paid_amount = 0;
            //             $salary_sheet->payment_status = 0;
            //             $salary_sheet->save();
            //         }

            //     }

            // }
            DB::commit();
            return response()->json([
                'status'=>'yes',
                'msg'=>'salary payment delete successfully'
            ]);
        }catch(\Throwable $e){
            DB::rollBack();
           // dd($e);
            return response()->json([
                'status'=>'no',
                'err'=>$e->getMessage(),
                'msg'=>"Something went wrong!"
            ]);
        }
    }

    function indexDue(){
        return view("Hr.due_payment.index");
    }
    function searchDue(Request $request){
        try{
           
            $teacher = Teacher::where('teacher_code',$request->hr_id)->first();
            if($teacher){
                if($teacher->total_due == 0){
                    return response()->json(['status'=>'no','msg'=>'Teacher has not due']);
                }
                $data['teacher']=$teacher;
                $html =  view('Hr.due_payment.ajax-due-payment',$data)->render();
                return response()->json(['status'=>'yes','html'=>$html]);
            }else{
                return response()->json(['status'=>'no','msg'=>'Teacher is not found']);
            }
           
        }catch(\Exception $e){
            return response()->json(['status'=>'no','msg'=>"Something went wrong!"]);
        }

    }
    public function storeDue(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required',
            'paid' => 'required',
            'payment_date' => 'required',
            'paid_by' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        try{
            DB::beginTransaction();
            $payrollMapping = PayrollMapping::first();
            $user_id =  auth()->guard('school')->user()->id;
            if($payrollMapping){
                $teacher = Teacher::find($request->teacher_id);
                $t_paid = $request->paid ?? 0;
                if($teacher){
                    $salary_sheets = SalarySheet::where('due_amount','>',0)->where('teacher_id',$request->teacher_id)->get();
                    $total_fund = 0;
                    foreach($salary_sheets as $salary_sheet){
                        if($t_paid > 0){
                            if($t_paid > $salary_sheet->due_amount){
                                $t_paid = $t_paid-$salary_sheet->due_amount;
                                $c_amount = $salary_sheet->due_amount;
                            }else{
                                $c_amount = $t_paid;
                                $t_paid =0; 
                            }

                            $in_code = $this->crateInvoice('Payment');
                            $salaryPayment = new SalaryPayment;
                            $salaryPayment->acy_id = $salary_sheet->acy_id;
                            $salaryPayment->teacher_id = $salary_sheet->teacher_id;
                            $salaryPayment->salary_sheet_id = $salary_sheet->id;
                            $salaryPayment->invoice_code =  $in_code;
                            $salaryPayment->payment_date = $request->payment_date;
                            $salaryPayment->payment_type = 'Salary Payment';
                            $salaryPayment->paid_by = $request->paid_by;
                            $salaryPayment->paid_amount =$c_amount;
                            $salaryPayment->save();

                            $fund_history = new FundHistory;
                            $fund_history->t_type = 'out';
                            $fund_history->voucher_date = $request->payment_date;
                            $fund_history->fund_id = $payrollMapping->fund_id;
                            $fund_history->voucher_id = $salaryPayment->id;
                            $fund_history->fund_out = $c_amount;
                            $fund_history->created_by =  $user_id;
                            $fund_history->r_type =  'Salary Payment';
                            $fund_history->save();
                            $total_fund += $c_amount;

                            $trans = new LedgerTransaction();
                            $trans->trans_type = "Salary Payment";
                            $trans->r_type = "Credit";
                            $trans->r_id = $salaryPayment->id;
                            $trans->voucher_id = $salaryPayment->id;
                            $trans->fund_id = $payrollMapping->fund_id;
                            $trans->ledger_id = $request->paid_by;
                            $trans->trans_date =  $request->payment_date;
                            $trans->credit_amount = $c_amount;
                            $trans->created_by =  $user_id;
                            $trans->is_depend =  1;
                            $trans->save();

                           
                            $trans = new LedgerTransaction();
                            $trans->trans_date =  $request->p_date;
                            $trans->trans_type = "Salary Payment";
                            $trans->r_type = "Debit";
                            $trans->r_id = $salaryPayment->id;
                            $trans->voucher_id = $salaryPayment->id;
                            $trans->fund_id = $payrollMapping->fund_id;
                            $trans->ledger_id = $payrollMapping->salary_due_ledger_id;
                            $trans->debit_amount = $c_amount;
                            $trans->created_by =  $user_id;
                            $trans->save();
                        }else{
                            break;
                        }
                    }
                    if($t_paid > 0){
                        $teacher->total_advance += $t_paid;
                    }
                    $teacher->total_due -= $request->paid;
                    $teacher->save();
                    DB::commit();
                    return response()->json([
                        'status'=>'yes',
                        'msg'=>'Due Collected successfully'
                    ]);
                }else{
                    return response()->json(['status'=>'no','msg'=>'Teacher is not found']);
                }
            }else{
               return response()->json(['status'=>'no','msg'=>"please setup payroll mapping"]); 
            }

            
           
        }catch(\Exception $e){
            DB::rollBack();
           // dd($e);
            return response()->json([
                'status'=>'no',
                'msg'=>"Something went wrong!"
            ]);
        }
    }

    function indexAdvance(){
        return view("Hr.advance_payment.index");
    }
    function searchAdvance(Request $request){
        try{
           
            $teacher = Teacher::where('teacher_code',$request->hr_id)->first();
            if($teacher){
                if($teacher->total_due > 0){
                    return response()->json(['status'=>'no','msg'=>'Teacher has due']);
                }
                $data['teacher']=$teacher;
                $html =  view('Hr.advance_payment.ajax-advance-payment',$data)->render();
                return response()->json(['status'=>'yes','html'=>$html]);
            }else{
                return response()->json(['status'=>'no','msg'=>'Teacher is not found']);
            }
           
        }catch(\Exception $e){
            return response()->json(['status'=>'no','msg'=>"Something went wrong!"]);
        }

    }
    public function storeAdvance(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required',
            'paid' => 'required',
            'payment_date' => 'required',
            'paid_by' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        try{
            DB::beginTransaction();
            $payrollMapping = PayrollMapping::first();
            $auth_user =  auth()->guard('school')->user();
            $user_id =  $auth_user->id;
            if($payrollMapping){
                $teacher = Teacher::find($request->teacher_id);
                
                $total_fund = 0;
                if($teacher){
                    $in_code = $this->crateInvoice('Payment');
                    $salaryPayment = new SalaryPayment;
                    $salaryPayment->acy_id = $auth_user->school->ac_year_id;
                    $salaryPayment->teacher_id = $teacher->id;
                    $salaryPayment->invoice_code =  $in_code;
                    $salaryPayment->payment_date = $request->payment_date;
                    $salaryPayment->payment_type = 'Salary Advance';
                    $salaryPayment->paid_by = $request->paid_by;
                    $salaryPayment->paid_amount = $request->paid;
                    $salaryPayment->save();

                    $fund_history = new FundHistory;
                    $fund_history->t_type = 'out';
                    $fund_history->voucher_date = $request->payment_date;
                    $fund_history->fund_id = $payrollMapping->fund_id;
                    $fund_history->voucher_id = $salaryPayment->id;
                    $fund_history->fund_out = $request->paid;
                    $fund_history->created_by =  $user_id;
                    $fund_history->r_type =  'Salary Advance';
                    $fund_history->save();
                    $total_fund += $request->paid;

                    $trans = new LedgerTransaction();
                    $trans->trans_type = "Salary Advance";
                    $trans->r_type = "Credit";
                    $trans->r_id = $salaryPayment->id;
                    $trans->voucher_id = $salaryPayment->id;
                    $trans->fund_id = $payrollMapping->fund_id;
                    $trans->ledger_id = $request->paid_by;
                    $trans->trans_date =  $request->payment_date;
                    $trans->credit_amount =$request->paid;
                    $trans->created_by =  $user_id;
                    $trans->is_depend =  1;
                    $trans->save();

                    $trans = new LedgerTransaction();
                    $trans->trans_date =  $request->payment_date;
                    $trans->trans_type = "Salary Advance";
                    $trans->r_type = "Debit";
                    $trans->r_id = $salaryPayment->id;
                    $trans->voucher_id = $salaryPayment->id;
                    $trans->fund_id = $payrollMapping->fund_id;
                    $trans->ledger_id = $payrollMapping->salary_advanced_ledger_id;
                    $trans->debit_amount =$request->paid;
                    $trans->created_by =  $user_id;
                    $trans->save();
                    $teacher->total_advance +=$request->paid; 
                    $teacher->save();

                    $fund = Fund::find($payrollMapping->fund_id);
                    if($fund){
                        $fund->amount_out += $total_fund;
                        $fund->balance -= $total_fund;
                        $fund->save();
                    }

                    DB::commit();
                    return response()->json([
                        'status'=>'yes',
                        'msg'=>'Advance Payment successfully'
                    ]);
                }else{
                    return response()->json(['status'=>'no','msg'=>'Teacher is not found']);
                }
            }else{
               return response()->json(['status'=>'no','msg'=>"please setup payroll mapping"]); 
            }   
           
        }catch(\Exception $e){
            DB::rollBack();
        //    dd($e);
            return response()->json([
                'status'=>'no',
                'err'=>$e->getMessage(),
                'msg'=>"Something went wrong!"
            ]);
        }
    }

    function indexAdvanceReturn(){
        return view("Hr.advance_return_payment.index");
    }
    function searchAdvanceReturn(Request $request){
        try{
           
            $teacher = Teacher::where('teacher_code',$request->hr_id)->first();
            if($teacher){
                if($teacher->total_advance == 0){
                    return response()->json(['status'=>'no','msg'=>'Teacher has not advance']);
                }
                $data['teacher']=$teacher;
                $html =  view('Hr.advance_return_payment.ajax-advance-payment',$data)->render();
                return response()->json(['status'=>'yes','html'=>$html]);
            }else{
                return response()->json(['status'=>'no','msg'=>'Data is not found']);
            }
           
        }catch(\Exception $e){
            return response()->json(['status'=>'no','msg'=>"Something went wrong!"]);
        }

    }
    public function storeAdvanceReturn(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required',
            'paid' => 'required',
            'payment_date' => 'required',
            'paid_by' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        try{
            DB::beginTransaction();
            $payrollMapping = PayrollMapping::first();
            $user_id =  auth()->guard('school')->user()->id;
            if($payrollMapping){
                $teacher = Teacher::find($request->teacher_id);
                $t_paid = $request->paid ?? 0;
                if($teacher){
                    $in_code = $this->crateInvoice('Receipt');
                    $salaryPayment = new SalaryPayment;
                    $salaryPayment->acy_id = $salary_sheet->acy_id;
                    $salaryPayment->teacher_id = $teacher->id;
                    // $salaryPayment->salary_sheet_id = $salary_sheet->id;
                    $salaryPayment->invoice_code =  $in_code;
                    $salaryPayment->paid_amount = $request->paid;
                    $salaryPayment->payment_date = $request->payment_date;
                    $salaryPayment->payment_type = 'Salary Advance Return';
                    $salaryPayment->paid_by = $request->paid_by;
                    $salaryPayment->save();

                    $fund_history = new FundHistory;
                    $fund_history->t_type = 'in';
                    $fund_history->voucher_date = $request->payment_date;
                    $fund_history->fund_id = $payrollMapping->fund_id;
                    $fund_history->voucher_id = $salaryPayment->id;
                    $fund_history->r_type =  'Salary Advance Return';
                    $fund_history->fund_in =$request->paid;
                    $fund_history->created_by =  $user_id;
                    $fund_history->save();

                    $trans = new LedgerTransaction();
                    $trans->trans_type = 'Salary Advance Return';
                    $trans->r_type = "Credit";
                    $trans->r_id = $salaryPayment->id;
                    $trans->voucher_id = $salaryPayment->id;
                    $trans->fund_id = $payrollMapping->fund_id;
                    $trans->ledger_id = $payrollMapping->salary_advanced_ledger_id;
                    $trans->trans_date =  $request->payment_date;
                    $trans->credit_amount =$request->paid;
                    $trans->created_by =  $user_id;
                    $trans->is_depend =  1;
                    $trans->save();

                    $trans = new LedgerTransaction();
                    $trans->trans_date =  $request->payment_date;
                    $trans->trans_type = 'Salary Advance Return';
                    $trans->r_type = "Debit";
                    $trans->r_id = $salaryPayment->id;
                    $trans->voucher_id = $salaryPayment->id;
                    $trans->fund_id = $payrollMapping->fund_id;
                    $trans->ledger_id = $request->paid_by;
                    $trans->debit_amount =$request->paid;
                    $trans->created_by =  $user_id;
                    $trans->save();
                    $teacher->total_advance -= $t_paid;
                    $teacher->save();
                    $fund = Fund::find($payrollMapping->fund_id);
                    if($fund){
                        $fund->amount_in += $request->paid;
                        $fund->balance += $request->paid;
                        $fund->save();
                    }
                    DB::commit();
                    return response()->json([
                        'status'=>'yes',
                        'msg'=>'Advance Payment successfully'
                    ]);
                }else{
                    return response()->json(['status'=>'no','msg'=>'Teacher is not found']);
                }
            }else{
               return response()->json(['status'=>'no','msg'=>"please setup payroll mapping"]); 
            }   
           
        }catch(\Exception $e){
            DB::rollBack();
            //    dd($e);
            return response()->json([
                'status'=>'no',
                'msg'=>"Something went wrong!"
            ]);
        }
    }
}
