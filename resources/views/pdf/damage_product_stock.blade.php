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
            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Damage Product Stock</h4>
          
            <br>
        </div>
        <div class="product-details">
            <table class="table" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th width="10%">Sl.</th>
                  <th width="30%">Category Name</th>
                  <th width="30%">Product Name</th>
                  <th width="10%">Qty</th>
                  <th width="10%">Unit Price</th>
                  <th width="10%">Total Price</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $total_qty = 0;
                    $total_grand = 0;
                @endphp
                @foreach($reports as $key=>$report)
                    @php
                        $total_qty += $report->qty;
                        $total_grand += $report->total_cost;
                    @endphp
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$report->cat_name}}</td>
                        <td>{{$report->product_name}}</td>
                        <td>{{$report->qty}}</td>
                        <td>{{ auth()->user()->currency_symbol }}{{number_format($report->total_cost/$report->qty, 2)}}</td>
                        <td>{{ auth()->user()->currency_symbol }}{{number_format($report->total_cost, 2)}}</td>

                    </tr>
                @endforeach
                @if($reports->count())
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td>{{ $total_qty }}</td>
                        <td></td>
                        <td>{{ auth()->user()->currency_symbol }}{{ round($total_grand,2) }}</td>
                    
                    </tr>
                @endif
              </tbody>
            </table>
        </div><!--/.product-details-->
      </div><!--/.content-->
    </div><!--/.content-wrapper-->
  </body>
</html>
