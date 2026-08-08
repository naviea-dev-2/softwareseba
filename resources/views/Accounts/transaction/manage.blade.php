@extends('inc.master')

@section('head')

<title>Manage Transaction</title>
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
                                <h3 class="card-title">Manage Transaction</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
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
                                    @php $i=1; @endphp
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{$i}}</td>
                                        @if($transaction->sub_type == "Expense")
                                        <td>{{$transaction->expense?->reference_no}}</td>
                                        @else
                                        <td>-</td>
                                        @endif
                                        <td>{{$transaction->account?->title}}</td>
                                        <td>{{$transaction->sub_type}}</td>
                                        <td>{{$transaction->reason}}</td>
                                        <td>{{ $transaction->type == "debit" ? $transaction->amount : '-' }}</td>
                                        <td>{{ $transaction->type == "credit" ? $transaction->amount : '-' }}</td>
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach
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
    $('#dataTable').dataTable();
    $('.from_date').on('change',function(){
        $('.p_form_date').val(this.value);
    });
    $('.to_date').on('change',function(){
        $('.p_to_date').val(this.value);
    });
</script>
@endsection
