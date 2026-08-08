@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Bank</title>
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
                <h5 style="font-size: 0.875rem; margin:0;">Payment Method</h5>
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
                              <th>Icon</th>
                              <th>Method Name</th>
                              <th>Sorting</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php
                                $p_edit = can_p('payment_method.edit');
                                $p_delete = can_p('payment_method.delete');
                            @endphp
                            @foreach($methods as $key=>$method)
                            <tr class="{{$method->id}}">
                                <td>{{$key+1}}</td>
                                <td><img style="hieght:40px;width:40px" src="{{$method->image_show}}"></td>
                                <td>{{$method->name}}</td>
                                <td>{{$method->sorting}}</td>
                                <td>
                                    @if($p_edit)
                                    <a class="btn btn-primary data_edit" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#updateModal"
                                    data-id="{{$method->id}}"
                                    >
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    @endif
                                    @if($p_delete)
                                    <a href="#" data-token="{{csrf_token()}}" data-action="{{route('payment_method.delete',$method->id)}}" data-id="{{$method->id}}" class="del_hr_data btn btn-danger">
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
    @include('Accounts.payment_method.create')
    <!-- start update modal -->
    @include('Accounts.payment_method.edit')
       
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<!-- month manage Edit-->
<script type="text/javascript">
$(document).ready(function(){
    $('#dataTable').dataTable();
    $('#for_pos').on('change',function(){
        if($(this).val() == 1){
            $('.bank_show').show();
        }else{
            $('.bank_show').hide();
            $('#edit_account').val(0);
        }
    });
    $('#edit_for_pos').on('change',function(){
        if($(this).val() == 1){
            $('.edit_bank_show').show();
        }else{
            $('.edit_bank_show').hide();
            $('#edit_account').val(0);
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
        
        $.ajax({
            url: "{{route('payment_method.edit') }}?id=" + id,
            method: 'GET',
            success: function(data) {
                console.log(data);
                if(data.status == 1){
                     // $('#fetchDepartment').html(data.html);
                    $('#edit_id').val(data.id);
                    $('#name').val(data.name);
                    $('#sorting').val(data.sorting);
                    $('#edit_for_pos').val(data.for_pos);
                    if(data.for_pos == 1){
                        $('.edit_bank_show').show();
                    }else{
                        $('.edit_bank_show').hide();
                    }
                    $('.display-upload-img').attr('src',data.image_show);
                    var account_option = new Option(data.account_name,data.account_id, true, true);
                    $('#edit_account').append(account_option).trigger('change');
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

    $(document).on('click','.del_hr_data',function(){
        let id = $(this).attr('data-id');
        var action=$(this).attr('data-action');
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
    //add data

    $(".add_data_form").on('submit', function(){
        var form = $(this);
        var form_data = new FormData($(this)[0]);
        var action = form.attr('action');

        form.find('button[type=submit]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

        $.ajax({
            method:'POST',
            url:action,
            data:form_data,
            // dataType:'json',
            processData: false,
            contentType: false,
            cache: false,
            enctype: 'multipart/form-data',
            success:function(response){
                console.log(response);
                if (0 == response.status) {
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

            }
        }).then(function(){
            form.find('button[type=submit]').attr('disabled', false).html('Save');
        });
        return false;
    });
    //edit data

    $(".edit_data_form").on('submit', function(){
        event.preventDefault();
        var form = $(this);
        var form_data = new FormData($(this)[0]);
        //var form_data = form.serialize();
        var action = form.attr('action');

        form.find('button[type=submit]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

        $.ajax({
            method:'POST',
            url:action,
            data:form_data,
            //dataType:'json',
            processData: false,
            contentType: false,
            cache: false,
            enctype: 'multipart/form-data',
            success:function(response){
                if (0 == response.status) {
                    $.each(response.errors,  function(key, val){

                        if($('.edit_data_form').find('input[name='+key+']').length > 0)
                        {

                             $('.edit_data_form').find('input[name='+key+']').addClass('is-invalid');
                            $('.edit_data_form').find('input[name='+key+']').next().html(val);
                        }else{
                             $('.edit_data_form').find('select[name='+key+']').addClass('is-invalid');
                            $('.edit_data_form').find('select[name='+key+']').next().html(val);
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
});
$(document).on('change','.upload-img-add',function(){
    var files = $(this).get(0).files;
    var reader = new FileReader();
    reader.readAsDataURL(files[0]);
    var arg=this;
    reader.addEventListener("load", function(e) {
        var image = e.target.result;
        $(arg).parent().find('.display-upload-img-add').attr('src', image);
    });
});
$(document).on('change','.upload-img',function(){
    var files = $(this).get(0).files;
    var reader = new FileReader();
    reader.readAsDataURL(files[0]);
    var arg=this;
    reader.addEventListener("load", function(e) {
        var image = e.target.result;
        $(arg).parent().find('.display-upload-img').attr('src', image);
    });
});
</script>


@endsection
