@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Account Head</title>
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
                <h5 style="font-size: 0.875rem; margin:0;">Account Head</h5>
                <div class="d-flex" style="gap:10px;">
                    <a href="{{ route('account_head.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus ml-0 mr-1"></i> Add Account Head
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
                                <th>SL.</th>
                                <th>Accounting Head </th>
                                <th>Accounting Type </th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @php
                                $p_edit = can_p('account_head.edit');
                                $p_delete = can_p('account_head.delete');
                            @endphp
                            @foreach($accounts_heads as $accounts_head)
                            <tr>
                                <td>{{$i}}</td>

                                <td>{{$accounts_head->title}}</td>
                                <td>
                                    @php
                                        if($accounts_head->ac_type == 1){
                                            
                                            $balance = \App\Models\Account\AccountTransaction::select(\DB::raw("SUM( IF(type='credit', -1*IFNULL(amount,0), IFNULL(amount,0)) ) as t_amount"))->where('account_id',$accounts_head->id)->get();
                                            
                                            $ac_text = "Asset";
                                        }else if($accounts_head->ac_type == 2){
                                            $balance = \App\Models\Account\AccountTransaction::select(\DB::raw("SUM( IF(type='credit', IFNULL(amount,0), -1*IFNULL(amount,0)) ) as t_amount"))->where('account_id',$accounts_head->id)->get();
                                            $ac_text ="Liability";
                                        }else if($accounts_head->ac_type == 3){
                                            $balance = \App\Models\Account\AccountTransaction::select(\DB::raw("SUM( IF(type='credit', IFNULL(amount,0), -1*IFNULL(amount,0)) ) as t_amount"))->where('account_id',$accounts_head->id)->get();
                                            $ac_text = "Equity";
                                        }else if($accounts_head->ac_type == 4){
                                            $balance = \App\Models\Account\AccountTransaction::select(\DB::raw("SUM( IF(type='credit', IFNULL(amount,0), -1*IFNULL(amount,0)) ) as t_amount"))->where('account_id',$accounts_head->id)->get();
                                            $ac_text = "Income";
                                        }else if($accounts_head->ac_type == 5){
                                            $balance = \App\Models\Account\AccountTransaction::select(\DB::raw("SUM( IF(type='credit', -1*IFNULL(amount,0), IFNULL(amount,0)) ) as t_amount"))->where('account_id',$accounts_head->id)->get();
                                            $ac_text = "Expense";

                                        }else if($accounts_head->ac_type == 6){
                                            $balance = \App\Models\Account\AccountTransaction::select(\DB::raw("SUM( IF(type='credit', -1*IFNULL(amount,0), IFNULL(amount,0)) ) as t_amount"))->where('account_id',$accounts_head->id)->get();
                                            $ac_text = "Dirct Expense";

                                        }else if($accounts_head->ac_type == 7){
                                            $balance = \App\Models\Account\AccountTransaction::select(\DB::raw("SUM( IF(type='credit', -1*IFNULL(amount,0), IFNULL(amount,0)) ) as t_amount"))->where('account_id',$accounts_head->id)->get();
                                            $ac_text = "Indirect Expense";
                                        }
                                    @endphp
                                    {{ $ac_text }}
                                </td>
                                <td>
                                    
                                    {{ auth()->user()->currency_symbol }}{{ round($balance[0]->t_amount ? $balance[0]->t_amount : 0,2) }}
                                </td>
                                <td>@php
                                    if($accounts_head->status == 1){
                                    echo  "<div class='badge text-success badge-shadow'>Active</div>";
                                    }else{
                                    echo  "<div class='badge text-danger badge-shadow'>Inactive</div>";
                                    }
                                    @endphp
                                </td>
                                <td class="d-flex">
                                    @if($accounts_head->sys != 0)
                                        @if($p_edit)
                                        <a class="btn btn-primary" href="{{ route('account_head.edit',$accounts_head) }}">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        @endif
                                        @if($p_delete)
                                        <a href="#" data-token="{{csrf_token()}}" data-action="{{ route('account_head.delete',$accounts_head->id) }}" data-id="{{$accounts_head->id}}" class="del_hr_data btn btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                        @endif
                                    @endif


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
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
    $('#dataTable').dataTable();
    $(document).on('click','.del_hr_data',function(){
        let id = $(this).attr('data-id');
        let action = $(this).attr('data-action');
        Swal.fire({
            title: '{{__("lang.are_you_sure")}}',
            text: '{{__("lang.you_wont_be_able_to_revert_this")}}',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{__("lang.yes_delete_it")}}',
            cancelButtonText: '{{__("lang.cancel")}}',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                window.location = action;
            }
        });
    });
</script>


@endsection
