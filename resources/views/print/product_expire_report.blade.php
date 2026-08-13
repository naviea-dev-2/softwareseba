<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Expire Report Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Product Expire Report</h4>

                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                    <th width="20%">Category</th>
                                    <th width="25%">Product Name</th>
                                    <th width="13%">Product Code</th>
                                    <th width="15%">Batch No.</th>
                                    <th width="15%">Expire Date</th>
                                    <th width="12%">Qty</th>
                                </tr>
                            </thead>
                            @if($reports->count())
                            <tbody>

                                @foreach($reports as $key=>$report)

                                <tr>
                                    <td>{{$report->cat_name}}</td>
                                    <td>{{$report->product_name}}</td>
                                    <td>{{$report->product_code}}</td>
                                    <td>{{$report->batch_no}}</td>
                                    <td>{{ date('Y-m-d', strtotime($report->expire_date)) }}</td>
                                    <td>{{$report->qty}}</td>

                                </tr>
                                @endforeach

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
