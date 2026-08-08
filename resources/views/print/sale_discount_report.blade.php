<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale Discount Report Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Sale Discount Report</h4>
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
                                    <th width="13%">Product Discount</th>
                                    <th width="13%">Invoice Discount</th>
                                    <th width="13%">Total Discount</th>
                                    <th width="13%">Sales Price</th>
                                    <th width="13%">Total Amount</th>
                                </tr>
                            </thead>
                            @if($reports->count())
                            <tbody>
                                @php
                                    $total_sale = 0;
                                    $total_p_discount = 0;
                                    $total_o_discount = 0;
                                    $total_discount = 0;
                                    $total_sale_amount = 0;
                                @endphp
                                @foreach($reports as $key=>$report)
                                    @php
                                        $total_sale += $report->grand_total;
                                        $total_p_discount += $report->total_discount-$report->order_discount;
                                        $total_o_discount += $report->order_discount;
                                        $total_discount += $report->total_discount;
                                        $total_sale_amount += $report->total_cost;
                                    @endphp
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ date('Y-m-d', strtotime($report->invoice_date)) }}</td>

                                        <td>{{$report->reference_no}}</td>
                                        <td>{{$report->customer_name}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{number_format($report->total_discount-$report->order_discount, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{number_format($report->order_discount, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{number_format($report->total_discount, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{number_format($report->grand_total, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{number_format($report->total_cost, 2)}}</td>
                                    </tr>
                                @endforeach
                                @if($reports->count() > 0)
                                <tr>
                                    <td colspan="4"><strong>Total</strong></td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_p_discount,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_o_discount,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_discount,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_sale,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_sale_amount,2) }}</td>
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
