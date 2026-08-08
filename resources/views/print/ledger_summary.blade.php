<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ledger Summary Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Ledger Summary</h4>
                            <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                   <th>#</th>
                                    <th>Reference No.</th>
                                    <th>Account Name</th>
                                    <th>Transaction Type</th>
                                    <th>Reason</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; $total_dr=0; $total_cr=0;@endphp
                                @foreach($transactions as $transaction)
                                <tr>
                                    @if($transaction->type == "credit")
                                    @php
                                    $total_cr += $transaction->amount;
                                    @endphp

                                    @else
                                    @php
                                            $total_dr += $transaction->amount;
                                    @endphp

                                    @endif
                                    <td>{{$i}}</td>
                                    @if($transaction->sub_type == "Expense")
                                        <td>{{$transaction->expense?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Sales")
                                        <td>{{$transaction->invoice?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Sales Payment")
                                        <td>{{$transaction->invoice?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Purchase")
                                        <td>{{$transaction->purchase?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Purchase Payment")
                                        <td>{{$transaction->purchase?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Sales Return")
                                        <td>{{$transaction->invoice_return?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Sales Return Payment")
                                        <td>{{$transaction->invoice_return?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Purchase Return")
                                        <td>{{$transaction->purchase_return?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Purchase Return Payment")
                                        <td>{{$transaction->purchase_return?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Salary")
                                        <td>Salary</td>
                                        {{-- <td>{{$transaction->purchase?->reference_no}}</td> --}}
                                    @elseif($transaction->sub_type == "Salary Payment")
                                        <td>Salary Payment</td>
                                        {{-- <td>{{$transaction->purchase?->reference_no}}</td> --}}
                                    @elseif($transaction->sub_type == "Bonus")
                                        <td>{{$transaction->bonus?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Bonus Pay")
                                        <td>{{$transaction->bonus?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Employee Loan")
                                        <td>{{$transaction->emp_loan?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Employee Loan Pay")
                                        <td>{{$transaction->emp_loan?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Employee Loan Return")
                                        <td>{{$transaction->emp_loan?->reference_no}}</td>
                                    @elseif($transaction->sub_type == "Employee Loan Return")
                                        <td>{{$transaction->emp_loan?->reference_no}}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    <td>{{$transaction->account?->title}}</td>
                                    <td>{{$transaction->sub_type}}</td>
                                    <td>{{$transaction->reason}}</td>
                                    <td>{{ $transaction->type == "debit" ? (auth()->user()->currency_symbol.' '.round($transaction->amount,2)) : '-' }}</td>
                                    <td>{{ $transaction->type == "credit" ? (auth()->user()->currency_symbol.' '.round($transaction->amount,2)) : '-' }}</td>
                                </tr>
                                @php $i++; @endphp
                                @endforeach
                                <tr>
                                    <td colspan="5">Total</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round( $total_dr,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round( $total_cr,2) }}</td>
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
