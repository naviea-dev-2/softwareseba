<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale Wise Profit Report Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Sale Wise Profit Report</h4>
                            <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                    <th width="5%">Sl.</th>
                                    <th width="10%">Date</th>
                                    <th width="10%">Invoice No.</th>
                                    <th width="10%">Customer</th>
                                    <th width="10%">Product Price</th>
                                    <th width="5%">Tax</th>
                                    <th width="10%">Shipping</th>
                                    <th width="10%">Discount</th>
                                    <th width="10%">Total Amount</th>
                                    <th width="10%">Purchase Price</th>
                                    <th width="10%">Profit</th>
                                </tr>
                            </thead>
                            @if($reports->count())
                            <tbody>
                                @php
                                    $total_sale = 0;
                                    $total_tax = 0;
                                    $total_shipping = 0;
                                    $total_discount = 0;
                                    $total_amount = 0;
                                    $total_purchase = 0;
                                    $total_profit = 0;
                                @endphp
                                @foreach($reports as $key=>$report)
                                @php

                                    $total_sale += $report->total_cost;
                                    $total_tax += $report->total_tax;
                                    $total_shipping += $report->shipping_cost;
                                    $total_discount += $report->order_discount;
                                    $total_amount += $report->grand_total;
                                    $total_purchase +=  $report->total_p_cost;
                                    $total_profit +=  $report->grand_total - $report->total_p_cost;
                                @endphp
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ date('Y-m-d', strtotime($report->invoice_date)) }}</td>
                                    <td>{{$report->reference_no}}</td>
                                    <td>{{$report->customer_name}}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{number_format($report->total_cost, 2)}}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{number_format($report->total_tax , 2)}}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{number_format($report->shipping_cost, 2)}}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{number_format($report->order_discount, 2)}}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{number_format($report->grand_total, 2)}}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{number_format($report->total_p_cost, 2)}}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{number_format($report->grand_total - $report->total_p_cost, 2)}}</td>

                                </tr>
                                @endforeach
                                @if($reports->count())
                                <tr>
                                    <td colspan="4"><strong>Total</strong></td>

                                    <td>{{ auth()->user()->currency_symbol }}{{ number_format($total_sale,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ number_format($total_tax,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ number_format($total_shipping,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ number_format($total_discount,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ number_format($total_amount,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ number_format($total_purchase,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ number_format($total_profit,2) }}</td>
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
