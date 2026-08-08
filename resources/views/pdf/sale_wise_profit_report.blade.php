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
                    <h4 style="padding: 0;margin:0;margin-bottom:10px;">Sale Wise Profit Report</h4>
                    <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                    <br>
                </div>
                <div class="product-details">
                    <table class="table" cellspacing="0" width="100%">
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
                    </table>
                </div><!--/.product-details-->
            </div><!--/.content-->
        </div><!--/.content-wrapper-->
    </body>
</html>
