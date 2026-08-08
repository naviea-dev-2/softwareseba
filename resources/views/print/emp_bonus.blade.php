<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee Bonus Report Print</title>
    <style>
        table{
            width: 100%;
            margin-bottom: 1rem;
            vertical-align: top;
            border-color: #dee2e6;
            caption-side: bottom;
            border-collapse: collapse;
        }
        tbody, td, tfoot, th, thead, tr {
            border: 1px;
            border-color: inherit;
            border-style: solid;
        }
    </style>
    <script>
    window.print();
    window.onafterprint = back;

    function back() {
        window.close();
        window.history.back();
    }
</script>
</head>
<body>
<div class="row justify-content-center" id="printableArea">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">

                    <div class="account-main-title mb-5">
                        <div class="" style="text-align: center">
                            <h3 style="padding: 0;margin:0;">{{ auth()->user()->business->business_name }}</h3>
                            <h3 style="padding: 0;margin:0;">{{ auth()->user()->business->moible_number }}</h3>
                            <h3 style="padding: 0;margin:0;">{{ auth()->user()->business->email }}</h3>
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Employee Bonus Report</h4>
                            <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Occasion</th>
                                    <th>Paid Method</th>
                                    <th>Bank Account</th>
                                    <th>Bonus</th>
                                </tr>
                            </thead>
                            <tbody>
                               @php
                                    $total = 0;
                                @endphp
                                @foreach($reports as $key=>$report)
                                    @php
                                        $total += $report->bonusAmount;
                                    @endphp
                                    <tr>
                                        <td>{{$report->employee?->employee_name}}</td>
                                        <td>{{$report->paidDate}}</td>
                                        <td>{{$report->occation}}</td>
                                        <td>{{$report->method?->name}}</td>
                                        <td>{{@$report->bank_account->account_name}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->bonusAmount,2)}}</td>

                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5">Total</td>
                                    <td>{{ auth()->user()->currency_symbol }} {{ round($total,2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
