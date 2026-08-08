@extends('inc.master')

@section('head')

<title>Balance Sheet</title>
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
                                <h3 class="card-title">Balance Sheet</h3>
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
                                    @if(can_p('balance_sheet_pdf'))
                                    <form action="{{ route('balance_sheet_pdf') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <button type="submit" class="btn btn-primary px-4 ms-2">PDF</button>
                                    </form>
                                    @endif
                                    @if(can_p('balance_sheet_print'))
                                    <form action="{{ route('balance_sheet_print') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <button type="submit" class="btn btn-success px-4 ms-2">Print</button>
                                    </form>
                                    @endif
                                    @if(can_p('balance_sheet_excel'))
                                    <form action="{{ route('balance_sheet_excel') }}" method="GET">
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

                                        <th>Asset</th>
                                        <th>Ammount</th>
                                        <th>Liability & Equity</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $profit = 0;
                                            $total_a=0;  $total_l=0;
                                            if($profit_trans){
                                                $profit += $profit_trans[0]->b_acount;
                                            }
                                            if($indirect_tans){
                                                $profit += $indirect_tans[0]->b_acount;
                                            }
                                            if($direct_tans){
                                                $profit += $direct_tans[0]->b_acount;
                                            }
                                        @endphp
                                        @if($profit != 0)
                                            
                                            @if($profit > 0)
                                                @php
                                                    $total_l = $profit; 
                                                @endphp
                                                <tr>
                                                <td></td>
                                                <td></td>
                                                <td>Net Profit</td>
                                                <td>{{auth()->user()->currency_symbol.round($profit,2)}}</td>
                                                </tr>
                                            @else
                                                @php
                                                    $total_a = -$profit;
                                                @endphp
                                                <tr>
                                                <td>Net Loss</td>
                                                <td>{{auth()->user()->currency_symbol.round(-$profit,2)}}</td>
                                                <td></td>
                                                <td></td>
                                                </tr>
                                            @endif
                                            
                                        @endif
                                       
                                        
                                        @php
                                            // $total_a=0;  $total_l=0;
                                        @endphp
                                        @foreach($transactions as $transaction)
                                        <tr>
                                        @if($transaction->type == 1)
                                        @php
                                            $total_a += (-$transaction->b_acount);
                                        @endphp
                                            <td>{{$transaction->account_name}}</td>
                                            <td>{{auth()->user()->currency_symbol.round(-$transaction->b_acount,2)}}</td>
                                            <td></td>
                                            <td></td>
                                        @else
                                        @php
                                            $total_l += $transaction->b_acount;
                                        @endphp
                                        <td></td>
                                            <td></td>
                                            <td>{{$transaction->account_name}}</td>
                                            <td>{{auth()->user()->currency_symbol.round($transaction->b_acount,2)}}</td>
                                        @endif


                                        </tr>

                                        @endforeach
                                        <tr>
                                            <td><b>Total</b></td>
                                            <td><b>{{ auth()->user()->currency_symbol.round($total_a,2) }}</b></td>
                                            <td></td>
                                            <td><b>{{ auth()->user()->currency_symbol.round($total_l,2) }}</b></td>
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
