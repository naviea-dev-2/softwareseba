<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Print</title>
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
          margin-top: 5px;
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
    <div class="container">
        @php
        $business = auth()->user()->business;
        @endphp
        <div style="display: flex;padding: 0 20px;align-items: center;justify-content:center;">
            <div style="width: 100px;height:50px;">
                <img style="width: 100%;height:100%;" src="{{ $business->business_logo_show }}" alt="{{ @$business->business_name }}">
            </div>
            <div style="text-align:left;width: calc(100% - 250px);padding-left: 15px;">
                <h2 style="padding:0;margin:0;font-weight:bold;">{{ $business->business_name }}</h1>
                <p style="padding:0;margin:0;font-weight:bold;">{{ $business->moible_number }}</p>
                <p style="padding:0;margin:0;font-weight:bold;">{{ $business->email }}</p>
            </div>
        </div>
        <hr style="background: #8d8484;height: 1px;margin: 10px 0;padding: 0;">
        <h4 style="padding: 0;margin:0;margin-bottom:10px;text-align:center;">Deposit Payment List</h4>
        @if(count($search_list) > 4)
            @php
                $width_per = 100/count($search_list);
            @endphp
            <table class="table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    @foreach ($search_list as $search)
                    <th style="width:{{ $width_per }}%">{{ $search['label'] }}</th>
                    @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($search_list as $search)
                        <td style="font-weight: bold;">{{ $search['val'] }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        @else
            @php
                $width_per = 100/(count($search_list) * 2);
            @endphp
            <table class="table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    @foreach ($search_list as $search)
                    <th style="width:{{ $width_per }}%;font-weight: 400;">{{ $search['label'] }}</th>
                    <th style="width:{{ $width_per }}%;font-weight: bold;">{{ $search['val'] }}</th>
                    @endforeach
                    </tr>
                </thead>

            </table>
        @endif
        <div class="product-details">
            <table class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="width:10%;">SL.</th>
                            <th style="width:18%;">Payment Date</th>
                            <th style="width:18%;">Land Name</th>
                            <th style="width:18%;">Payment Method</th>
                            <th style="width:18%;">Payment Status</th>
                            <th style="width:18%;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total=0; @endphp
                        @foreach($deposit_payments as $k=>$deposit_payment)
                            @php
                                $total += $deposit_payment->deposit_amount;
                            @endphp
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{ \Carbon\Carbon::parse($deposit_payment->payment_date)->format("Y-m-d") }}</td>
                                <td style="text-align:left;">{{$deposit_payment->p_name}}</td>
                                <td>{{$deposit_payment->m_name}}</td>
                                @if($deposit_payment->payment_status == 1)
                                    <td><div class="badge bg-success">Paid</div></td>
                                @else
                                    <td><div class="badge bg-danger">Not Paid</div></td>
                                @endif
                                <td style="text-align:right;">{{$deposit_payment->deposit_amount}}</td>

                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" style="text-align:right;font-weight:bold;">Total</td>
                            <td style="text-align:right;font-weight:bold;">{{$total}}</td>

                        </tr>
                    </tbody>
            </table>
        </div>
    </div>


</body>
</html>
