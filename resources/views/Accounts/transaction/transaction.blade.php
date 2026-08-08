@extends('inc.master')

@section('head')

<title>Voucher Transaction</title>
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
                                <h3 class="card-title">Voucher Transaction</h3>
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
                                     <label for="">Voucher Type</label>
                                    <select id="v_type" name="v_type" class="form-control">
                                        <option value="">All</option>
                                        <option value="Debit Voucher">Debit Voucher</option>
                                        <option value="Credit Voucher">Credit Voucher</option>

                                        <option value="Journal">Journal</option>
                                        <option value="Contra">Contra</option>

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
                                    @if(can_p('transaction_pdf'))
                                    <form action="{{ route('transaction_pdf') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <input type="hidden" value="{{ $v_type }}" name="p_v_type" class="p_account">
                                        <button type="submit" class="btn btn-primary px-4 ms-2">PDF</button>
                                    </form>
                                    @endif
                                    @if(can_p('transaction_print'))
                                    <form action="{{ route('transaction_print') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <input type="hidden" value="{{ $v_type }}" name="p_v_type" class="p_account">
                                        <button type="submit" class="btn btn-success px-4 ms-2">Print</button>
                                    </form>
                                    @endif
                                    @if(can_p('transaction_excel'))
                                    <form action="{{ route('transaction_excel') }}" method="GET">
                                        <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                        <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                        <input type="hidden" value="{{ $v_type }}" name="p_v_type" class="p_account">
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
                                {{-- <div class="" style="text-align: center;padding:20px;">
                                    <label for="">Ledger Sheet - ( {{ $account->title }} )</label>
                                </div> --}}
                                <table id="dataTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Account Name</th>
                                        <th>Debit Amount</th>
                                        <th>Credit Amount</th>
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


                                        <td>{{ $transaction->l_name}}</td>
                                        <td>{{ $transaction->type == "debit" ? ( auth()->user()->currency_symbol.round($transaction->amount,2)) : '-' }}</td>
                                        <td>{{ $transaction->type == "credit" ? ( auth()->user()->currency_symbol.round($transaction->amount,2)) : '-' }}</td>
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach
                                    <tr>
                                        <td >Total</td>
                                        <td>{{ auth()->user()->currency_symbol.round( $total_dr,2) }}</td>
                                        <td>{{ auth()->user()->currency_symbol.round( $total_cr,2) }}</td>
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
