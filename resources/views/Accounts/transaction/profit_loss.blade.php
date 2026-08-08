@extends('inc.master')

@section('head')

<title>Profit & Loss</title>
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
                                <h3 class="card-title">Profit & Loss</h3>
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
                                    @if(can_p('prof_loss_pdf'))
                                    <form action="{{ route('prof_loss_pdf') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <button type="submit" class="btn btn-primary px-4 ms-2">PDF</button>
                                    </form>
                                    @endif
                                    @if(can_p('prof_loss_print'))
                                    <form action="{{ route('prof_loss_print') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <button type="submit" class="btn btn-success px-4 ms-2">Print</button>
                                    </form>
                                    @endif
                                    @if(can_p('prof_loss_excel'))
                                    <form action="{{ route('prof_loss_excel') }}" method="GET">
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
                                                $total_cr += $transaction->b_acount
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
                                        {{-- <tr>
                                            <td colspan="5"><strong><u>Indirect Expense</u></strong></td>

                                        </tr> --}}
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
                                            <td><strong>{{ auth()->user()->currency_symbol.round( $total_dr,2) }}</strong></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Total Income</strong></td>
                                            <td></td>
                                            <td><strong>{{ auth()->user()->currency_symbol.round( $total_cr,2) }}</strong></td>
                                        </tr>
                                        @if($total_dr > $total_cr)
                                        <tr>
                                            <td colspan="3"><strong>Net Loss</strong></td>
                                            <td><strong>{{ auth()->user()->currency_symbol.round($total_dr - $total_cr,2) }}</strong></td>
                                            <td></td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="3"><strong>Net Profit</strong></td>
                                            <td></td>
                                            <td><strong>{{ auth()->user()->currency_symbol.round($total_cr - $total_dr,2) }}</strong></td>
                                        </tr>
                                        @endif
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
