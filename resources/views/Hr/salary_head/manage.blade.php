
@extends('inc.master')
@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Salary Head</title>
<style>
    .select2-container .select2-selection--single{
        height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear{
        height: 40px!important;
    }
    button.close {
        padding: 0;
        background-color: transparent;
        border: 0;
        -webkit-appearance: none;
    }
    .close {
        float: right;
        font-size: 1.3125rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: 0.5;
    }
</style>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        @include('Hr.salary_head.create')

        <div class="br-section-wrapper data-list pt-3">

            <h6 class="br-section-label text-center">All Salary Head</h6>
            
            <div style="text-align: right;" class="mb-2">
                <button type="button" class="btn btn-primary p-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i style="margin:0;" class="bx bx-plus"></i>Add Salary Head
                </button>
                {{-- <a style="margin-bottom: 20px" href="javascript:void(0);" class="btn btn-primary btn-sm btn-new">
                    <i class="fa fa-plus"></i> Add Salary Head
                </a> --}}
            </div>

            <div class="table-wrapper">
                <table id="data_table_list" class="table display responsive nowrap">
                    <thead>
                        <tr>
                            <th class="wd-10p">Id</th>
                            <th class="wd-15p">Name</th>
                            <th class="wd-15p">Type</th>
                            <th class="wd-15p">Sort</th>
                            <th class="wd-15p">Status</th>
                            <th class="wd-10p">Action</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div><!-- table-wrapper -->


        </div><!-- br-section-wrapper -->
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Update Salary Head</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="data-edit-res"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="datamodalshow" class="modal fade">
    <div class="modal-dialog modal-dialog-top" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body text-center py-4 px-4">
                <form id="data-form-delete" action="{{ route('school.salary_head.delete') }}" method="post">
                    @csrf
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="bx bx-x-circle"style="font-size:60px;line-height: 1 !important;display: inline-block !important;color: #dc3545"></i>
                    <h4 class="text-danger fw-semibold mb-2 mt-2">Are you sure! you want to delete this?</h4>
                    <input type="hidden" name="designation_id" id="modal_data_id">
                    <button type="submit" class="btn-delete btn btn-danger ms-2 text-white fs-6 text-uppercase py-2 px-3  fw-medium mb-2" style='font-family: "Montserrat", "Fira Sans", "Helvetica Neue", Arial, sans-serif'> yes</button>
                    <button type="button" class="btn btn-success fs-6 text-uppercase py-2 px-3   fw-medium mb-2" style='font-family: "Montserrat", "Fira Sans", "Helvetica Neue", Arial, sans-serif' data-bs-dismiss="modal" aria-label="Close"> No</button>
                </form>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->


@endsection



@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>

    var datatable = $('#data_table_list').DataTable({
       // 'pageLength': 2,
        "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('school.salary_head.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){
              data._token = "{{ csrf_token() }}";
            },
        },
        "columns": [
            { "data": "id"},
            { "data": "name"},
            { "data": "type"},
            { "data": "position"},
            { "data": "status"},
            { "data": "options"},
        ],
        "columnDefs": [ {
          "targets": 3,
          "orderable": false
        },{
          "targets": 5,
          "orderable": false
        } ]

    });
    $(document).ready(function() {
        $('.t-add').select2({
            dropdownParent: $('#exampleModal'),
            width:'100%',
            dropdownAutoWidth : true
        });
        $("#data-form-create").on('submit', function(){
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
                    if ('no' == response.status) {
                        $.each(response.errors,  function(key, val){
                            if($('.add_data_form').find('input[name='+key+']').length > 0)
                            {

                                $('.add_data_form').find('input[name='+key+']').addClass('is-invalid');
                                $('.add_data_form').find('input[name='+key+']').next().html(val);
                            }else{
                                $('.add_data_form').find('select[name='+key+']').addClass('is-invalid');
                                $('.add_data_form').find('select[name='+key+']').next().html(val);
                            }
                        });
                        if(response.error){
                            toastr.error(response.error, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                        }

                    }
                    if ('yes' == response.status) {
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
        $(document).on('click','.custom_data_edit',function(e){
            e.preventDefault();
            var url  = $(this).attr('href');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type : 'GET',
                url : url,
                success : function(res){
                    $('.data-edit-res').html(res);
                    $('.type-edit').select2({
                        dropdownParent: $('#updateModal'),
                        width:'100%',
                        dropdownAutoWidth : true
                    });
                    $('#updateModal').modal('show');
                },
                error:function(e){
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: e.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
        $(document).on('submit',"#data-form-update", function(){
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
        $(document).on('click','.dataDeleteModal',function(){
            var s_id = $(this).attr('value');
            $('#modal_data_id').val(s_id);
            $('#datamodalshow').modal('show');
        });
        var btnDeleteInFlight = false;
        $(document).on('click','.btn-delete',function(e){
            e.preventDefault();
            if(btnDeleteInFlight){
                return;
            }
            btnDeleteInFlight = true;
            $('.btn-delete').prop('disabled', true);
            var form_data = new FormData($('#data-form-delete')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : $('#data-form-delete').attr('action'),
                data : form_data,
                type : 'POST',
                contentType: false,
                cache: false,
                processData:false,
                success : function(res){
                    if(res.status == "yes"){
                        datatable.draw();
                        $('#data-form-delete')[0].reset();
                      
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });

                    }else{
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error:function(e){
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: e.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                complete:function(){
                    $('#datamodalshow').modal('hide');
                    btnDeleteInFlight = false;
                    $('.btn-delete').prop('disabled', false);
                }
            });
        });
    });
</script>

@endsection
