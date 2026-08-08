<center>
    <strong>
        <h4>{{auth()->user()->business->business_name}}</h4>
        <p>
            {{auth()->user()->business->address1}}<br/>
            Salary Slip
        </p>
    </strong>
</center>
<hr/>

<strong>
    Name: {{$employee->employee_name}}<br/>
    ID: {{$employee->employee_id}}<br/>
    Department: {{$employee->deptName}}<br/>
    Designation: {{$employee->desigName}}<br/>
    Salary Month: {{date('F, Y',strtotime($SalarySheet->month))}}<br/><br/>
</strong>

<div style="margin:0px auto">
   <table class="table table-striped table-responsive">
        <thead>
        <tr>
          <th colspan="2">Earnings</th>
          <th colspan="2">Deduction</th>
        </tr>
      </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td>{{auth()->user()->currency_symbol.' '.$SalarySheet->basicSalary}}</td>
                <td>Advanced</td>
                <td>{{auth()->user()->currency_symbol.' '.$SalarySheet->advanced}}</td>
            </tr>
    
            <tr>
                <td>House Rent</td>
                <td>{{auth()->user()->currency_symbol.' '.round($SalarySheet->houseRent,2)}}</td>
                <td>Tax</td>
                <td>{{auth()->user()->currency_symbol.' '.round($SalarySheet->tax,2)}}
                </td>
            </tr>
    
            <tr>
                <td>Medical Cost</td>
                <td>{{auth()->user()->currency_symbol.' '.round($SalarySheet->medicalCost,2)}}
                </td>
                <td>Provident Fund</td>
                <td>{{auth()->user()->currency_symbol.' '.round($SalarySheet->providentFound,2)}}
                </td>
            </tr>
    
            <tr>
                <td>Transport Cost</td>
                <td>{{auth()->user()->currency_symbol.' '.round($SalarySheet->transportCost,2)}}</td>
                <td> Absent Deduction</td>
                <td>{{auth()->user()->currency_symbol.' '.round($SalarySheet->absentDeduct,2)}}
                </td>
            </tr>
    
            <tr>
                <td>Overtime</td>
                <td colspan="3">{{auth()->user()->currency_symbol.' '.round(($SalarySheet->overtimeMiniute/60)*(($SalarySheet->overtime/100)*$SalarySheet->basicSalary),2)}}</td>
            </tr>
    
            <tr>
                <td>Net Salary </td>
                <td colspan="3">{{auth()->user()->currency_symbol.' '.round($SalarySheet->netSalary,2)}}</td>
            </tr>
            <tr>
                <td>Paid Salary </td>
                <td colspan="3">{{auth()->user()->currency_symbol.' '.round($SalarySheet->paidSalary,2)}}
                </td>
            </tr>
            <tr>
                <td>Paid Percent </td>
                <td colspan="3">{{round($SalarySheet->percentPaid,2).'%'}}</td>
            </tr>
    
        </tbody>
    </table>
</div>


@if($payments->count() > 0)
    Payments :<hr/>
    <div style="margin:0px auto">
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>SL.</th>
                    <th>Date</th>
                    <th>Payment Method</th>
                    <th>Bank Account</th>
                    <th>Paid Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $k=>$payment) 
                    <tr>
                        <td>{{($k+1)}}</td>
                        <td>{{date('Y-m-d',strtotime($payment->date))}}</td>
                        <td>{{$payment->method?->name}}</td>
                        <td>{{$payment->account?->account_name}}</td>
                        <td>{{auth()->user()->currency_symbol.' '.round($payment->amount,2)}}</td>
                    
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif