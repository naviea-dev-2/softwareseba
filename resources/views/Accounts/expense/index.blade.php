@extends('inc.master')

@section('head')

<title>Manage Expense</title>
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
                <h5 style="font-size: 0.875rem; margin:0;">Expense</h5>
                <div class="d-flex" style="gap:10px;">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>
            </div>
                
        </div>
        <div class="row" style="padding-top: 24px;">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>SN.</th>
                                <th>Category</th>
                                <th>Reason</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                {{-- <th>Payment Status</th> --}}
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $p_edit = can_p('expense.edit');
                                $p_delete = can_p('expense.delete');
                            @endphp
                            @foreach($expenses as $key=>$expense)
                            <tr class="{{$expense->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{$expense->category?->name}}</td>
                                <td>{{$expense->reason}}</td>
                                <td>{{date('Y-m-d',strtotime($expense->date))}}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{round($expense->amount,2)}}</td>
                                <td>{{$expense->method?->name}}</td>
                                {{-- <td>
                                    @if($expense->payment_status == 1)
                                        Paid
                                    @else
                                        UnPaid
                                    @endif
                                </td> --}}
                                <td>
                                    @if($p_edit)
                                    {{-- <a class="btn btn-primary data_edit" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#updateModal"
                                    data-id="{{$expense->id}}"
                                    >
                                        <i class="bx bx-edit"></i>
                                    </a> --}}
                                    @endif
                                    @if($p_delete)
                                    <a href="#" data-token="{{csrf_token()}}" data-action="{{ route('expense.delete',$expense->id) }}" data-id="{{$expense->id}}" class="del_hr_data btn btn-danger">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                    @endif

                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Accounts.expense.create')
    <!-- start update modal -->
    @include('Accounts.expense.edit')
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<!-- month manage Edit-->
<script type="text/javascript">

     $(document).ready(function(){
        $(".datepicker").flatpickr();
        $('#dataTable').dataTable();
        $('#add_payment_method').on('change',function(){
            var id = $(this).val();
            if(id != ""){
                $('.bank_show').show();
            }else{
                $('.bank_show').hide();
            }
        });
        $('#add_account').select2({
            theme: "bootstrap-5",
            placeholder: 'Select Bank Account',
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            containerCssClass: 'select-sm',
            ajax: {
                url: '{{route('select2.balance_accounts')}}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        method_id:$('#add_payment_method').val(),
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
        });
        $('#edit_account').select2({
            theme: "bootstrap-5",
            placeholder: 'Select Bank Account',
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            containerCssClass: 'select-sm',
            ajax: {
                url: '{{route('select2.balance_accounts')}}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        method_id:$('#payment_method').val(),
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
        });
        $(document).on('click','.data_edit',function(){
         var id=$(this).attr('data-id');
         // var termID=$('#termID').val();
          // alert(id);
            $.ajax({
                url: "{{route('expense.edit') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    if(data.status == 1){
                        // console.log(data);
                        // $('#fetchDepartment').html(data.html);
                        $('#reason').val(data.reason);
                        $('#category').val(data.category);
                        $('#date').val(data.date);
                        $('#amount').val(data.amount);
                        $('#payment_method').val(data.method);
                        var account_option = new Option(data.account_name,data.account_id, true, true);
                        $('#edit_account').append(account_option).trigger('change');
                        $('#edit_data').val(id);
                    }else{
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    
                }
            });
        });
    });
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
            cancelButtonClass: 'btn btn-danger ms-2',
            buttonsStyling: false,
		}).then(function (result) {
		    if (result.value) {
                window.location = action;
            }
        });
    });
    //add data

    $(".add_data_form").on('submit', function(){
        var form = $(this);
        var form_data = form.serialize();
        var action = form.attr('action');

        form.find('button[type=submit]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

        $.ajax({
            method:'POST',
            url:action,
            data:form_data,
            dataType:'json',
            success:function(response){
                console.log(response);
                if (0 == response.status) {
                    $('.is-invalid').each(function(){
                        $(this).removeClass('is-invalid');
                    });
                    $('.invalid-feedback').html('');
                    $.each(response.errors,  function(key, val){
                        if($('.add_data_form').find('input[name='+key+']').length > 0)
                        {

                             $('.add_data_form').find('input[name='+key+']').addClass('is-invalid');
                           $('.add_data_form').find('input[name='+key+']').siblings('.invalid-feedback').html(val);
                        }else{
                             $('.add_data_form').find('select[name='+key+']').addClass('is-invalid');
                            $('.add_data_form').find('select[name='+key+']').siblings('.invalid-feedback').html(val);
                        }
                    });
                    if(response.error){
                        toastr.error(response.error, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    }

                }
                if (1 == response.status) {
                    $('#exampleModal').modal('toggle');

                    toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    location.reload();
                }
                 if ("success" == response.status) {
                    window.location.href = response.data;
                }

            }
        }).then(function(){
            form.find('button[type=submit]').attr('disabled', false).html('Save');
        });
        return false;
    });
    //edit data

    $(".edit_data_form").on('submit', function(){
        var form = $(this);
        var form_data = form.serialize();
        var action = form.attr('action');

        form.find('button[type=submit]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

        $.ajax({
            method:'POST',
            url:action,
            data:form_data,
            dataType:'json',
            success:function(response){
                if (0 == response.status) {
                    $.each(response.errors,  function(key, val){

                        if($('.edit_data_form').find('input[name='+key+']').length > 0)
                        {

                             $('.edit_data_form').find('input[name='+key+']').addClass('is-invalid');
                            $('.edit_data_form').find('input[name='+key+']').siblings('.invalid-feedback').html(val);
                        }else{
                             $('.edit_data_form').find('select[name='+key+']').addClass('is-invalid');
                           $('.edit_data_form').find('select[name='+key+']').siblings('.invalid-feedback').html(val);
                        }


                    });
                    if(response.error){
                        toastr.error(response.error, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    }

                }
                if (1 == response.status) {
                    $('#updateModal').modal('toggle');

                    toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    location.reload();
                }

            }
        }).then(function(){
            form.find('button[type=submit]').attr('disabled', false).html('Save');
        });
        return false;
    });
</script>


@endsection
