<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice Report Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Invoice Report</h4>
                            <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                    <th width="5%">Sl.</th>
                                    <th width="10%">Date</th>
                                    <th width="10%">Reference</th>
                                    <th width="10%">Customer</th>
                                    <th width="10%">Category</th>
                                    <th width="10%">Product</th>
                                    <th width="5%">Qty</th>
                                    <th width="10%">Unit Price</th>
                                    <th width="10%">Discount</th>
                                    <th width="10%">Amount</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>
                            @if($reports->count())
                            <tbody>
                                @php
                                    $row=0;
                                    $total_qty = 0;
                                    $total_per_cost = 0;
                                    $total_discount = 0;
                                    $total_sub = 0;
                                @endphp
                                @foreach($reports as $key=>$report)
                                    @php
                                        $row++;
                                        $total_qty += $report->qty;
                                        $total_per_cost += $report->per_cost;
                                        $total_discount += $report->discount;
                                        $total_sub += $report->total;
                                    @endphp
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ date('Y-m-d', strtotime($report->invoice_date)) }}</td>

                                        <td>{{$report->reference_no}}</td>
                                        <td>{{$report->customer_name}}</td>
                                        <td>{{$report->cat_name}}</td>
                                        <td>{{$report->product_name}}</td>
                                        <td>{{$report->qty}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->per_cost, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->discount, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->total, 2)}}</td>
                                        <td>
                                            @if($report->status == 1)
                                            <div class="badge bg-secondary">Recieved</div>

                                            @elseif($report->status == 2)
                                            <div class="badge bg-secondary">Partial</div>

                                            @elseif($report->status == 3)
                                            <div class="badge bg-danger">Pending</div>

                                            @else
                                            <div class="badge bg-danger">Ordered</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if($reports->count() > 0)
                                 <tr>
                                    <td colspan="6">Total</td>
                                    <td>{{ $total_qty }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_per_cost/$row,2) }} <p>(Avg.)</p></td>
                                    <td>{{ auth()->user()->currency_symbol }}{{  round($total_discount,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{  round($total_sub,2) }}</td>
                                    <td></td>
                                </tr>
                                @endif
                            </tbody>
                            @endif
                        </table>
                    </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
