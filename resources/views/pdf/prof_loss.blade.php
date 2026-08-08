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
                    <h4 style="padding: 0;margin:0;margin-bottom:10px;">Proft & Loss</h4>
                    <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                    <br>
                </div>
                <div class="product-details">
                    <table class="table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Account Code</th>
                                <th>Type</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                           @php $i=1; $total_dr=0; $total_cr=0; @endphp
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{$transaction->account_name}}</td>
                                    <td>{{$transaction->account_code}}</td>
                                    <td>{{ $transaction->type == 4 ? "Income" : 'Expense' }}</td>
                                    @if($transaction->type == 4)
                                    @php
                                        $total_cr += ($transaction->b_acount)
                                    @endphp
                                    <td> -- </td>
                                    <td>{{ auth()->user()->currency_symbol.round($transaction->b_acount,2) }}</td>
                                    @else
                                    @php
                                        $total_dr += (-$transaction->b_acount)
                                    @endphp
                                    <td>{{  auth()->user()->currency_symbol.round(-$transaction->b_acount,2) }}</td>
                                    <td> -- </td>
                                    @endif
                                </tr>

                                @endforeach
                                @foreach($direct_tans as $transaction)
                                <tr>
                                    <td>{{$transaction->account_name}}</td>
                                    <td>{{$transaction->account_code}}</td>
                                    <td>{{ $transaction->type == 6 ? "Expense" : 'Income' }}</td>
                                    @if($transaction->type == 6)
                                        @php
                                            $total_dr += $transaction->b_acount
                                        @endphp
                                        <td>{{  auth()->user()->currency_symbol.round($transaction->b_acount,2) }}</td>
                                        <td> -- </td>
                                    @else
                                        @php
                                            $total_cr += $transaction->b_acount
                                        @endphp
                                        <td> -- </td>
                                        <td>{{ auth()->user()->currency_symbol.round($transaction->b_acount,2) }}</td>
                                    @endif
                                </tr>

                                @endforeach

                                @if($indirect_tans->count() > 0)

                                @foreach($indirect_tans as $transaction)
                                <tr>
                                    <td>{{$transaction->account_name}}</td>
                                    <td>{{$transaction->account_code}}</td>
                                    <td>{{ $transaction->type == 7 ? "Expense" : 'Income' }}</td>
                                    @if($transaction->type == 7)
                                        @php
                                            $total_dr += $transaction->b_acount
                                        @endphp
                                        <td>{{  auth()->user()->currency_symbol.round($transaction->b_acount,2) }}</td>
                                        <td> -- </td>
                                    @else
                                        @php
                                            $total_cr += $transaction->b_acount
                                        @endphp
                                        <td> -- </td>
                                        <td>{{ auth()->user()->currency_symbol.round($transaction->b_acount,2) }}</td>
                                    @endif
                                </tr>

                                @endforeach
                                @endif
                                <tr>
                                    <td colspan="3"><strong>Total Expense</strong></td>
                                    <td>{{  auth()->user()->currency_symbol }}<strong>{{ round( $total_dr,2) }}</strong></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><strong>Total Income</strong></td>
                                    <td></td>
                                    <td>{{  auth()->user()->currency_symbol }}<strong>{{ round( $total_cr,2) }}</strong></td>
                                </tr>
                                @if($total_dr > $total_cr)
                                <tr>
                                    <td colspan="3"><strong>Net Loss</strong></td>
                                    <td>{{  auth()->user()->currency_symbol }}<strong>{{ round($total_dr - $total_cr,2) }}</strong></td>
                                    <td></td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="3"><strong>Net Profit</strong></td>
                                    <td></td>
                                    <td>{{  auth()->user()->currency_symbol }}<strong>{{round($total_cr - $total_dr,2) }}</strong></td>
                                </tr>
                                @endif
                        </tbody>
                    </table>
                </div><!--/.product-details-->
            </div><!--/.content-->
        </div><!--/.content-wrapper-->
    </body>
</html>
