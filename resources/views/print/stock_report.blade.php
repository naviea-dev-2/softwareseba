<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Return Report Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Stock Report</h4>

                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                    <th width="5%">Sl.</th>
                                    <th width="15%">Category</th>
                                    <th width="10%">Brand</th>
                                    <th width="20%">Product</th>
                                    <th width="10%">Purchase Qty</th>
                                    <th width="10%">Purchase Amount</th>
                                    <th width="10%">Sale Qty</th>
                                    <th width="10%">Sale Amount</th>
                                    <th width="10%">Current Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $row=0;
                                    $total_inQty = 0;
                                    $total_purchase = 0;
                                    $total_outQty = 0;
                                    $total_sale = 0;
                                    $total_qty = 0;
                                @endphp
                                @foreach($reports as $key=>$report)
                                @php
                                    $row++;
                                    $total_inQty += $report->inQty;
                                    $total_purchase += $report->purchase_total;
                                    $total_outQty += $report->outQty;
                                    $total_sale += $report->sale_total;
                                    $total_qty += ($report->inQty - $report->outQty);
                                @endphp
                                <tr>
                                    <td>{{$key+1}}</td>

                                    <td>{{$report->category_name}}</td>
                                    <td>{{$report->brand_name}}</td>
                                    <td>{{$report->product_name}}</td>
                                    <td>{{$report->inQty}}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{number_format($report->purchase_total, 2)}}</td>
                                    <td>{{$report->outQty}}</td>

                                    <td>{{ auth()->user()->currency_symbol }}{{number_format($report->sale_total, 2)}}</td>
                                    <td>{{$report->inQty - $report->outQty}}</td>




                                </tr>
                                @endforeach
                                @if($reports->count())
                                <tr>
                                    <td colspan="4"><strong>Total</strong></td>
                                    <td>{{ $total_inQty }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_purchase,2) }}</td>
                                    <td>{{ $total_outQty }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($total_sale,2) }} </td>
                                    <td>{{ $total_qty }}</td>

                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
