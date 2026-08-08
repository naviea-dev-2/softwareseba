@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Account Balance</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection
@section('content')
    <div class="content-area">
        <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
            
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="font-size: 0.875rem; margin:0;">Account Information</h5>
                <div class="d-flex" style="gap:10px;">
                    <a href="{{ route('balance_account.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus ml-0 mr-1"></i> Add Account Balance
                    </a>
                </div>
            </div>
                
        </div>
        <div class="row" style="padding-top: 24px;">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Method</th>
                                <th>Account Name</th>
                                <th>Bank Name </th>
                                <th>Branch </th>
                                <th>Account Number</th>
                                <th>Routing Number </th>
                                <th>Balance (BDT)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @php
                                $p_edit = can_p('balance_account.edit');
                                $p_delete = can_p('balance_account.delete');
                            @endphp
                            @foreach($accounts as $item)
                            <tr>
                                <td>{{$i}}</td>

                                <td>{{$item->paymentmethod->name ?? ''}}</td>

                                <td>{{$item->account_name}}</td>
                                <td>{{$item->bank_name}}</td>
                                <td>{{$item->branch_name}}</td>
                                <td>{{$item->account_number}}</td>
                                <td>{{$item->routing_number}}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{ round($item->balance,2)}} </td>

                                <td>@php
                                    if($item->status == 1){
                                    echo  "<div class='badge bg-success badge-shadow'>Active</div>";
                                    }else{
                                    echo  "<div class='badge bg-danger badge-shadow'>Inactive</div>";
                                    }
                                    @endphp
                                </td>
                                <td class="d-flex">
                                    @if($p_edit)
                                    <a href="{{route('balance_account.edit',$item->id)}}" title="Edit" style="float: left;margin-right: 10px;">
                                        <button type="submit" class="btn btn-primary btn-sm mx-1"><i class="bx bx-edit"></i>
                                        </button>
                                    </a>
                                    @endif

                                    <!-- <form action="{{URL::to('admin/balance_account/'.$item->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i></button>
                                    </form> -->

                                </td>
                            </tr>
                            @php $i++; @endphp
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
     $('#dataTable').dataTable();
</script>
@endsection
