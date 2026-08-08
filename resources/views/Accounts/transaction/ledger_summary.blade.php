@extends('inc.master')

@section('head')

<title>Ledger Summary</title>
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
                                <h3 class="card-title">Ledger Summary</h3>
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
                                <div class="col-md-4">
                                     <label for="">Account</label>
                                    <select id="account" name="ledger_account_id" class="form-control">
                                        <option value="">Select Account</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary mt-4">Search</button>
                                </div>
                            </div>
                            </form>
                            <div class="d-flex justify-content-between">
                                <div></div>
                                <div class="d-flex">
                                    @if(can_p('ledger_summary_pdf'))
                                    <form action="{{ route('ledger_summary_pdf') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <input type="hidden" value="{{ $account_id }}" name="p_account" class="p_account">
                                        <button type="submit" class="btn btn-primary px-4 ms-2">PDF</button>
                                    </form>
                                    @endif
                                    @if(can_p('ledger_summary_print'))
                                    <form action="{{ route('ledger_summary_print') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <input type="hidden" value="{{ $account_id }}" name="p_account" class="p_account">
                                        <button type="submit" class="btn btn-success px-4 ms-2">Print</button>
                                    </form>
                                    @endif
                                    @if(can_p('ledger_summary_excel'))
                                    <form action="{{ route('ledger_summary_excel') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <input type="hidden" value="{{ $account_id }}" name="p_account" class="p_account">
                                        <button type="submit" class="btn btn-info px-4 ms-2">Excel</button>
                                    </form>
                                    @endif
                                    {{-- <form action="{{ route('test_excel') }}" method="GET">

                                        <button type="submit" class="btn btn-info px-4 ms-2">Test Excel</button>
                                    </form> --}}

                                </div>
                            </div>
                            <!-- /.card-header -->
                            @if($transactions->count() > 0)
                            <div class="card-body">
                                <div class="" style="text-align: center;padding:20px;">
                                    <label for="">Ledger Sheet - ( {{ $account->title }} )</label>
                                </div>
                                <table id="dataTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reference No.</th>
                                        <th>Account Name</th>
                                        <th>Transaction Type</th>
                                        <th>Reason</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $i=1; $total_dr=0; $total_cr=0;@endphp
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        @if($transaction->type == "credit")
                                        @php
                                        $total_cr += $transaction->amount;
                                        @endphp

                                        @else
                                        @php
                                             $total_dr += $transaction->amount;
                                        @endphp

                                        @endif
                                        <td>{{$i}}</td>
                                        @if($transaction->sub_type == "Expense")
                                            <td>{{$transaction->expense?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Sales")
                                            <td>{{$transaction->invoice?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Sales Payment")
                                            <td>{{$transaction->invoice?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Purchase")
                                            <td>{{$transaction->purchase?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Purchase Payment")
                                            <td>{{$transaction->purchase?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Sales Return")
                                            <td>{{$transaction->invoice_return?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Sales Return Payment")
                                            <td>{{$transaction->invoice_return?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Purchase Return")
                                            <td>{{$transaction->purchase_return?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Purchase Return Payment")
                                            <td>{{$transaction->purchase_return?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Salary")
                                            <td>Salary</td>
                                            {{-- <td>{{$transaction->purchase?->reference_no}}</td> --}}
                                        @elseif($transaction->sub_type == "Salary Payment")
                                            <td>Salary Payment</td>
                                            {{-- <td>{{$transaction->purchase?->reference_no}}</td> --}}
                                        @elseif($transaction->sub_type == "Bonus")
                                            <td>{{$transaction->bonus?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Bonus Pay")
                                            <td>{{$transaction->bonus?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Employee Loan")
                                            <td>{{$transaction->emp_loan?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Employee Loan Pay")
                                            <td>{{$transaction->emp_loan?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Employee Loan Return")
                                            <td>{{$transaction->emp_loan?->reference_no}}</td>
                                        @elseif($transaction->sub_type == "Employee Loan Return")
                                            <td>{{$transaction->emp_loan?->reference_no}}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{$transaction->o_tranaction?->account?->title}}</td>
                                        <td>{{$transaction->sub_type}}</td>
                                        <td>{{$transaction->reason}}</td>
                                        <td>{{ $transaction->type == "debit" ? ( auth()->user()->currency_symbol.round($transaction->amount,2)) : '-' }}</td>
                                        <td>{{ $transaction->type == "credit" ? ( auth()->user()->currency_symbol.round($transaction->amount,2)) : '-' }}</td>
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="5">Total</td>
                                        <td>{{ auth()->user()->currency_symbol.round( $total_dr,2) }}</td>
                                        <td>{{ auth()->user()->currency_symbol.round( $total_cr,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                        @if( ($total_dr - $total_cr) > 0)
                                        <td>{{ auth()->user()->currency_symbol.round($total_dr - $total_cr,2) }}</td>
                                        <td>-</td>
                                        @else
                                         <td>--</td>
                                        <td>{{ auth()->user()->currency_symbol.round(-($total_dr - $total_cr),2) }}</td>
                                        @endif
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            @endif
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
    $('#account').select2({
        theme: "bootstrap-5",
        placeholder: 'Select Account',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.accounts')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    value: $.trim(params.term),
                };
            },
            processResults: function (response) {
                console.log(response);
                return {
                    results: response
                };
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        var data = e.params.data;
        $('.p_account').val(data.id);

    });
    @if(request()->ledger_account_id)
        @php
            $account = \App\Models\Account\AccountHead::find(request()->ledger_account_id);
        @endphp
        @if($account)
            var account_option = new Option("{{ $account->title }}","{{ $account->id }}", true, true);
            $('#account').append(account_option).trigger('change');
        @endif
    @endif
    //  $('#dataTable').dataTable();
</script>
@endsection
