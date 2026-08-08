@extends('inc.master')

@section('head')

<title>Expense Report</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->



    <!-- Main content -->
    <section class="content-head">
        <div class="container-fluid">
            <div class="row">
                <!-- right column -->
                <div class="col-md-12">
                    <!-- general form elements disabled -->
                    <div class="card card-warning">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Expense Report</h3>
                            </div>
                            <form action="">
                            <div class="row p-3">
                               <div class="col-md-4">
                                    <label for="">From Date</label>
                                    <input type="text" name="from_date" value="{{ $from_date }}" class="form-control datepicker">
                                </div>
                                <div class="col-md-4">
                                    <label for="">To Date</label>
                                    <input type="text" name="to_date" value="{{ $to_date }}" class="form-control datepicker">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary mt-4">Search</button>
                                </div>
                            </div>
                            </form>
                            <div class="d-flex justify-content-between">
                                <div></div>
                                <div class="d-flex">
                                    @if(can_p('expense_report_pdf'))
                                    <form action="{{ route('expense_report_pdf') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <button type="submit" class="btn btn-primary px-4 ms-2">PDF</button>
                                    </form>
                                    @endif
                                    @if(can_p('expense_report_print'))
                                    <form action="{{ route('expense_report_print') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <button type="submit" class="btn btn-success px-4 ms-2">Print</button>
                                    </form>
                                    @endif
                                    @if(can_p('expense_report_excel'))
                                    <form action="{{ route('expense_report_excel') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <button type="submit" class="btn btn-info px-4 ms-2">Excel</button>
                                    </form>
                                    @endif

                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="dataTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>

                                        <th>Sl.</th>
                                        <th>Date</th>
                                        <th>Expense</th>
                                        <th>Reason</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1;$total=0; @endphp
                                        @foreach($expense_list as $expense)
                                            @php
                                                $total += $expense->amount;
                                            @endphp
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{date('Y-m-d',strtotime($expense->date))}}</td>
                                                <td>{{$expense->name}}</td>
                                                <td>{{$expense->reason}}</td>
                                                <td>{{$expense->method_name}}</td>
                                                <td>{{ auth()->user()->currency_symbol }}{{round($expense->amount,2)}}</td>
                                            
                                            </tr>

                                        @endforeach
                                        <tr>
                                        <td colspan="5">Total</td>
                                        <td>{{ auth()->user()->currency_symbol.round( $total,2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card-header -->

                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->


@endsection
@section('script')
<script>
$(".datepicker").flatpickr();
 $('.from_date').on('change',function(){
    $('.p_form_date').val(this.value);
});
$('.to_date').on('change',function(){
    $('.p_to_date').val(this.value);
});
</script>
@endsection
