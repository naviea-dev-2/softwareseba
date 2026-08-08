<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Wise Profit Report Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Product Wise Profit Report</h4>
                            <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                    <th width="5%">Sl.</th>
                                    <th width="15%">Product Name</th>
                                    <th width="10%">Sale</th>
                                    <th width="15%">Sale Unit Price</th>
                                    <th width="15%">Total SP</th>
                                    <th width="15%">Purchase Unit Price</th>
                                    <th width="15%">Total PR</th>
                                    <th width="10%">Profit</th>
                                </tr>
                            </thead>
                            @if($reports->count())
                            <tbody>
                                @php
                                    $total_qty = 0;
                                    $total_unit_sale = 0;
                                    $total_sale = 0;
                                    $total_unit_purchase = 0;
                                    $total_purchase = 0;
                                    $total_profit = 0;
                                @endphp
                                @foreach($reports as $key=>$report)
                                    @php

                                        $sub_sale_price = $report->per_cost * $report->qty;
                                        $sub_purchase_price = $report->purchase_price * $report->qty;
                                        $sub_profit = $sub_sale_price - $sub_purchase_price;

                                        $total_qty += $report->qty;
                                        $total_unit_sale += $report->per_cost;
                                        $total_unit_purchase += $report->purchase_price;
                                        $total_sale += $sub_sale_price;
                                        $total_purchase += $sub_purchase_price;
                                        $total_profit += $sub_profit;
                                    @endphp
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$report->product_name}}</td>
                                        <td>{{$report->qty}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->per_cost, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($sub_sale_price, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->purchase_price, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($sub_purchase_price, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($sub_profit, 2)}}</td>

                                    </tr>
                                @endforeach
                                @if($reports->count() > 0)
                                 <tr>
                                    <td colspan="2">Total</td>
                                    <td>{{ $total_qty }}</td>

                                    <td></td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_sale,2) }}</td>
                                    <td></td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_purchase,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_profit,2) }}</td>
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
