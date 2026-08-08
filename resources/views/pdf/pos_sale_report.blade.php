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
                    <h4 style="padding: 0;margin:0;margin-bottom:10px;">POS Sale Report</h4>
                    <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                    <br>
                </div>
                <div class="product-details">
                    <table class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="4%">Sl.</th>
                                    <th width="8%">Date</th>
                                    <th width="10%">Reference</th>
                                    <th width="10%">Customer</th>
                                    <th width="10%">Category</th>
                                    <th width="10%">Product</th>
                                    <th width="5%">Qty</th>
                                    <th width="8%">Unit Price</th>
                                    <th width="10%">Discount</th>
                                    <th width="10%">TAX</th>
                                    <th width="10%">Amount</th>
                                    <th width="5%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @php
                                    $row=0;
                                    $total_qty = 0;
                                    $total_per_cost = 0;
                                    $total_discount = 0;
                                    $total_tax = 0;
                                    $total_sub = 0;
                                @endphp
                                @foreach($reports as $key=>$report)
                                    @php
                                        $row++;
                                        $total_qty += $report->qty;
                                        $total_per_cost += $report->per_cost;
                                        $total_discount += $report->discount;
                                        $total_tax += $report->tax;
                                        $total_sub += $report->total;
                                    @endphp
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ date('Y-m-d', strtotime($report->sale_date)) }}</td>

                                        <td>{{$report->reference_no}}</td>
                                        <td>{{$report->customer_name}}</td>
                                        <td>{{$report->cat_name}}</td>
                                        <td>{{$report->product_name}}</td>
                                        <td>{{$report->qty}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->per_cost, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->discount, 2)}}</td>
                                        <td>{{ auth()->user()->currency_symbol }}{{round($report->tax, 2)}}</td>
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
                                    <td>{{ auth()->user()->currency_symbol }}{{  round($total_tax,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{  round($total_sub,2) }}</td>
                                    <td></td>
                                </tr>
                                @endif
                            </tbody>
                    </table>
                </div><!--/.product-details-->
            </div><!--/.content-->
        </div><!--/.content-wrapper-->
    </body>
</html>
