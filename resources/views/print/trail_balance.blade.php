<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trail Balance Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Trail Balance</h4>
                            <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                    <th>Account Name</th>
                                    <th>Account Code</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total_dr=0; $total_cr=0;@endphp
                                @foreach($transactions as $transaction)
                                <tr>


                                    <td>{{$transaction->account_name}}</td>
                                    <td>{{$transaction->account_code}}</td>
                                    @if($transaction->type == 1 || $transaction->type == 5)
                                    @php
                                        $total_dr += (-$transaction->b_acount);
                                    @endphp
                                    <td>{{ auth()->user()->currency_symbol }}{{ round(-$transaction->b_acount,2) }}</td>
                                    <td> -- </td>
                                    @else
                                   
                                    @php
                                        $total_cr += ($transaction->b_acount);
                                    @endphp
                                    <td> -- </td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($transaction->b_acount,2) }}</td>
                                    @endif
                                </tr>

                                @endforeach
                                <tr>
                                    <td><b>Total</b></td>
                                    <td></td>
                                    <td><b>{{ auth()->user()->currency_symbol }}{{ round($total_dr,2) }}</b></td>
                                    <td><b>{{ auth()->user()->currency_symbol }}{{ round($total_cr,2) }}</b></td>
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
