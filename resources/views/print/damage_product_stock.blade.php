<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Damage Product Stock Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Damage Product Stock</h4>
                            
                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
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
                            @if($reports->count())
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
                            @endif
                        </table>
                    </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
