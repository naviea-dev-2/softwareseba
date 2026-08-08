<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Report Print</title>
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
                            <h4 style="padding: 0;margin:0;margin-bottom:10px;">Attendance Report</h4>
                            <Strong>Date : {{  $from_date . ' to ' . $to_date }}</Strong>
                            <br>
                        </div>

                        <table class="table table-flush mt-3" id="report-dataTable">
                            <thead>
                                <tr>
                                     <th>Employee</th>
                                    <th>Duty Date</th>
                                    <th>Shift</th>
                                    <th>Time-in</th>
                                    <th>Time-out</th>
                                    <th>Working Time</th>
                                    <th>Late</th>
                                    <th>Overtime</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $key=>$report)

                                    <tr>
                                        <td>{{$report->employee?->employee_name}}</td>
                                        <td>{{$report->dutyDate}}</td>
                                        <td>{{$report->shift->shiftName}}</td>
                                        <td>{{date("d-m-Y g:i a",strtotime($report->inTime))}}</td>
                                        <td>{{$report->outTime !=null ? date("d-m-Y g:i a",strtotime($report->outTime)) : '--'}}</td>
                                        <td>
                                            @php echo intval($report->workingMiniute/60).' h : '.intval($report->workingMiniute%60).' min'@endphp
                                        </td>
                                        <td>
                                            @php echo intval($report->lateMiniute/60).' h : '.intval($report->lateMiniute%60).' min'@endphp
                                        </td>
                                        <td>
                                            @php echo intval($report->overtimeMiniute/60).' h : '.intval($report->overtimeMiniute%60).' min'@endphp</td>
                                        <td>{{$report->status}}</td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
