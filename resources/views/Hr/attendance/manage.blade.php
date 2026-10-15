
@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Attendence Manage</title>
<style>
    label{
        font-size: 1.2rem;
    }
     .select2-container .select2-selection--single{
        height: 37px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 37px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 37px!important;
    }
</style>
@endsection
@section('content')
    <div class="content-area">
        <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
            
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="font-size: 0.875rem; margin:0;">Employee Attendances</h5>
                <div class="d-flex" style="gap:10px;">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inTimeModal">
                        In
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#outTimeModal">
                        Out
                    </button>
                </div>
            </div>
                
        </div>

        <div class="row" style="padding-top: 24px;">
            <div class="col-md-3">
    <label for="filter_in_from">Time In From</label>
    <input type="text" id="filter_in_from"
           class="form-control filter-time"
           placeholder="08:00">
</div>

<div class="col-md-3">
    <label for="filter_in_to">Time In To</label>
    <input type="text" id="filter_in_to"
           class="form-control filter-time"
           placeholder="09:00">
</div>

<div class="col-md-3">
    <label for="filter_out_from">Time Out From</label>
    <input type="text" id="filter_out_from"
           class="form-control filter-time"
           placeholder="17:00">
</div>

<div class="col-md-3">
    <label for="filter_out_to">Time Out To</label>
    <input type="text" id="filter_out_to"
           class="form-control filter-time"
           placeholder="18:00">
</div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="myTable">
                            <thead>
                            <tr>

                                <th>SN.</th>
                                <th>Name</th>
                                <th>Duty Date</th>
                                <th>Shift</th>
                                <th>Time-in</th>
                                <th>Time-out</th>
                                <th>Working Time</th>
                                <th>Late</th>
                                <th>Overtime</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="refarel_table">
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @include('Hr.attendance.attentedence_in')
    <!-- end modal inTime -->
    <!-- start modal outTime -->
    @include('Hr.attendance.attentedence_out')
    <!-- end modal outTime -->
    
@endsection
@section('script')

<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
    $(".datepicker").flatpickr();
    $(".datetimepicker").flatpickr({
        static: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
    $(document).ready(function() {
        var datatable = $('#myTable').DataTable({
            // 'pageLength': 2,
            "order": [[ 0, "desc" ]],
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": "{{ route('attendance.ajax') }}",
                "dataType": "json",
                "type": "POST",
                data: function(data){
                data._token = "{{ csrf_token() }}";
                },
            },
            "columns": [
                { "data": "id"},
                { "data": "employee_name"},
                { "data": "date"},
                { "data": "shift_name"},
                { "data": "in_time"},
                { "data": "out_time"},
                { "data": "working_time"},
                { "data": "late_time"},
                { "data": "over_time"},
                { "data": "status"},
                { "data": "options"},
            ],
            "columnDefs": [ {
            "targets": 9,
            "orderable": false
            } ]

        });
    });

    $('.employee_select2').select2({
        placeholder: 'Select Employee',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        dropdownParent: $('#inTimeModal'),
        ajax: {
            url: '{{route('select2.employee')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                value: $.trim(params.term),
            };
            },
            processResults: function (response) {

            return {
                results: response
            };
            },
            cache: true
        }
    });
    $('.employee2_select2').select2({
        placeholder: 'Select Employee',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
       
        dropdownParent: $('#outTimeModal'),
        ajax: {
            url: '{{route('select2.employee')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                value: $.trim(params.term),
            };
            },
            processResults: function (response) {

            return {
                results: response
            };
            },
            cache: true
        }
    });
  

</script>

<script>

    $(document).ready(function(){
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
        $(".in_data_form").on('submit', function(){
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
                        $.each(response.errors,  function(key, val){
                            if($('.in_data_form').find('input[name='+key+']').length > 0)
                            {

                                $('.in_data_form').find('input[name='+key+']').addClass('is-invalid');
                                if("inTime" == key){
                                    $('.out_data_form').find('input[name='+key+']').parent().siblings('.invalid-feedback').html(val);
                                }else{
                                    $('.out_data_form').find('input[name='+key+']').siblings('.invalid-feedback').html(val);
                                }
                            }else{
                                $('.in_data_form').find('select[name='+key+']').addClass('is-invalid');
                                if("inTime" == key){
                                    $('.out_data_form').find('input[name='+key+']').parent().siblings('.invalid-feedback').html(val);
                                }else{
                                    $('.out_data_form').find('input[name='+key+']').siblings('.invalid-feedback').html(val);
                                }
                            }
                        });
                        if(response.error){
                            toastr.error(response.error, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                        }

                    }
                    if (1 == response.status) {
                        $('#inTimeModal').modal('toggle');

                        toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                        location.reload();
                    }

                },
                error:function(e){
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: e,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                complete: function(xhr, status) {
                    form.find('button[type=submit]').attr('disabled', false).html('Save');
                    // $('#submit-form').prop('disabled', false).text('Save');
                }
            });
            return false;
        });
        $(".out_data_form").on('submit', function(){
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
                        $.each(response.errors,  function(key, val){
                            if($('.out_data_form').find('input[name='+key+']').length > 0)
                            {

                                $('.out_data_form').find('input[name='+key+']').addClass('is-invalid');
                                //console.log( $('.out_data_form').find('input[name='+key+']').parent().siblings('.'));
                                if("outTime" == key){
                                    $('.out_data_form').find('input[name='+key+']').parent().siblings('.invalid-feedback').html(val);
                                }else{
                                    $('.out_data_form').find('input[name='+key+']').siblings('.invalid-feedback').html(val);
                                }

                            }else{
                                $('.out_data_form').find('select[name='+key+']').addClass('is-invalid');
                                if("outTime" == key){
                                    $('.out_data_form').find('input[name='+key+']').parent().siblings('.invalid-feedback').html(val);
                                }else{
                                    $('.out_data_form').find('input[name='+key+']').siblings('.invalid-feedback').html(val);
                                }
                            }
                        });
                        if(response.error){
                            toastr.error(response.error, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                        }

                    }
                    if (1 == response.status) {
                        $('#outTimeModal').modal('toggle');

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
</script>


@endsection
