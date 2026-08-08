<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaction Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Expense Report</h4>
                            <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                   <th>Sl.</th>
                                    <th>Date</th>
                                    <th>Expense</th>
                                    <th>Reason</th>
                                    <th>Payment Method</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1;$total=0; @endphp
                                @foreach($expense_list as $expense)
                                    @php
                                        $total += $expense->amount;
                                    @endphp
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{date('Y-m-d',strtotime($expense->date))}}</td>
                                        <td>{{$expense->name}}</td>
                                        <td>{{$expense->reason}}</td>
                                        <td>{{$expense->method_name}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($expense->amount,2)}}</td>
                                    
                                    </tr>

                                @endforeach
                                <tr>
                                <td colspan="5">Total</td>
                                <td>{{ auth()->user()->currency_symbol.round( $total,2) }}</td>
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
