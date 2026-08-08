<!DOCTYPE html>
  <html>
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Reports</title>
      <style type="text/css">
        *,
        *:after,
        *:before {
          box-sizing: inherit;
          font-family: Open Sans, sans-serif !important;
        }

        html {
          box-sizing: border-box;
          font-family: Open Sans, sans-serif !important;
        }

        body {
          font-size: 15px;
          font-weight: 300;
          letter-spacing: 0.01em;
          line-height: 1.6;
          color: #2c2c2c;
          font-family: Open Sans, sans-serif !important;
        }

        p{
          margin: 0;
          padding: 0;
          display: block;
        }

        table {
          border-spacing: 0;
          width: 100%;
        }

        .content-wrapper, .content{
          width: 100%;
          height: 100%;
          overflow: hidden;
        }

        .invoice-header-left{
          width: 50%;
          margin: 0;
          padding: 0;
          float: left;
        }

        .invoice-header-right{
          width: 50%;
          margin: 0;
          padding: 0;
          float: left;
          text-align: right;
        }

        .invoice-logo{
          width: 100%;
          margin-bottom: 50px;
          overflow: hidden;
        }

        .product-details{
          width: 100%;
          margin-top: 30px;
          margin-bottom: 15px;
          overflow: hidden;
        }

        .table{
          height: 100%;
          width: 100%;
          margin: 0;
          padding: 0;
        }

        .table tr th, .table tr td{
          text-align: center;
          padding: 5px;
          border: 1px solid #ddd;
          font-size: 12px;
          vertical-align: middle;
        }

        .table.table-borderless tr th, .table.table-borderless tr td{
          border: none;
          vertical-align: middle;
        }

        .product-image{
          width: 50px;
          height: 40px;
          margin: 0;
          padding: 0;
        }

        .footer{
          position: fixed;
          left: 0;
          bottom: 0;
          width: 100%;
          text-align: center;
        }
      </style>
    </head>
    <body>
        <div class="content-wrapper">
            <div class="content">
                <div class="" style="text-align: center">
                    <h3 style="padding: 0;margin:0;">{{ auth()->user()->business->business_name }}</h3>
                    <h3 style="padding: 0;margin:0;">{{ auth()->user()->business->moible_number }}</h3>
                    <h3 style="padding: 0;margin:0;">{{ auth()->user()->business->email }}</h3>
                    <h4 style="padding: 0;margin:0;margin-bottom:10px;">Salary Report</h4>
                    <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                    <br>
                </div>
                <div class="product-details">
                    <table class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                     <th>Month</th>
                                    <th>Employee Name</th>
                                    <th>Employee Id</th>
                                    <th>Net Salary</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @php
                                    $t_nets=0;
                                    $t_paids=0;
                                    $t_dues=0;
                                @endphp
                                @foreach($reports as $key=>$report)
                                    @php
                                        $t_nets+= $report->netSalary;
                                        $t_paids+= $report->paidSalary;
                                        $t_dues+= $report->due_amount;
                                    @endphp
                                    <tr>
                                        @php
                                        $month = date("M,Y",strtotime($report->month.'-01'))
                                        @endphp
                                        <td>{{ $month }}</td>

                                        <td>{{ $report->employee?->employee_name }}</td>
                                        <td>{{ $report->employee?->employee_id }}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->netSalary,2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->paidSalary,2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->due_amount,2)}}</td>
                                        <td>
                                            @if($report->payment_status == 0)
                                                <div class="badge bg-danger">Due</div>
                                            @elseif($report->payment_status == 1)
                                                <div class="badge bg-secondary">Partial</div>
                                            @else
                                                <div class="badge bg-primary">Paid</div>
                                            @endif
                                        </td>


                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3">Total</td>
                                    <td>{{ round($t_nets,2) }}</td>
                                    <td>{{ round($t_paids,2) }}</td>
                                    <td>{{ round($t_dues,2) }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                    </table>
                </div><!--/.product-details-->
            </div><!--/.content-->
        </div><!--/.content-wrapper-->
    </body>
</html>
