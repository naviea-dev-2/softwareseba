@extends('inc.master')

@section('head')
    <link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

    <title>Device Mapping</title>
    <style>
        label{
            font-size: 1.2rem;
        }
        .flatpickr-calendar {
            z-index: 1056 !important; /* make sure calendar appears above modal */
        
        }
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
        .swal2-icon{
            width:35px!important;
            height:35px!important;
            margin:5px auto 5px!important;
            border: 2px solid #f27474!important;
        }
        .swal2-x-mark-line-left{
            left: 7px!important;
            top: 16px!important;
            width: 20px!important;
            height: 3px!important;
        }
        .swal2-x-mark-line-right{
            right: 8px!important;
            top: 16px!important;
            width: 20px!important;
            height: 3px!important;
        }
        .alert.alert-danger{
            padding: 5px;
            margin-bottom: 5px;
        }
        .swal2-html-container{
            padding-top:5px;
        }
    </style>
@endsection
@section('content')
 <div class="content-area">
        <div class="data-list">
            <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
                
                <div class="d-flex justify-content-between align-items-center">
                    <h5 style="font-size: 0.875rem; margin:0;">Device Mapping</h5>
                    <div class="d-flex" style="gap:10px;">
                        <button type="button" class="btn btn-primary" onClick="createData()">
                            Add Employee Mapping
                        </button>
                    </div>
                </div>
                    
            </div>
            <div class="row" style="padding-top: 24px;">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="mappingTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Department</th>
                                        <th>Device ID</th>
                                        <th>Connection</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" data-create" style="display:none;">
            <div class="row" style="padding-top: 24px;">
                <div class="col-md-8">
                    <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 style="font-size: 0.875rem; margin:0;">Create Employee Mapping</h5>
                            <div class="d-flex" style="gap:10px;">
                                <button type="button" class="btn btn-primary" onClick="listData()">
                                    List Employee Mapping
                                </button>
                            </div>
                        </div>
                            
                    </div>
                    <div  style="padding-top: 24px;">
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1" style="border: 1px solid;padding: 10px;">

                            <form method="POST"  enctype="multipart/form-data" id="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id" id="mapping_id">
                                    <div class="col-sm-4 mt-2">
                                        <label class=" form-control-label">Shift:</label>
                                        <select  class="form-control" name="shift" id="shift">
                                            <option value="">Select Shift</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mt-2">
                                        <label class=" form-control-label">Employee:</label>
                                        <select  class="form-control" name="employee" id="employee" required>
                                            <option value="">Select Employee</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4  mt-2">
                                        <label for="">Device ID*</label>
                                        <input type="number" class="form-control" id="device_id" name="device_id" autocomplete="off" required>  
                                    </div>
                                    <div class="col-sm-4 mt-2 status_op" style="display:none;">
                                        <label class=" form-control-label">Status:</label>
                                        <select class="form-control" name="status" id="status" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                            
                                        </select>
                                    </div>

                                    <div class="col-sm-12  mt-2 text-right" style="text-align: right;">
                                        <button class="btn btn-primary mt-4 " id="submit-form" type="button">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>   
           
        </div>
    </div>
    
@endsection

@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script>
    function createData(title="Create Employee Mapping"){
        $('.data-list').hide();
        $('.data-create').show();
        $("#p_title").text(title);
    }
    function listData(){
        $('#shift').val("").trigger('change');
        $('#employee').val("").trigger('change');
        $('#device_id').val("");
      
        $('.data-create').hide();
        $('.data-list').show();
    }
    var d_table = $('#mappingTable').DataTable({
        "order": [[0, 'desc']],
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            "url": "{{ route('hr.device_mapping.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){
                data._token = "{{ csrf_token() }}";
            },
        },
        "columns": [
            { "data": "id"},
            { "data": "emp_id"},
            { "data": "name"},
            { "data": "des"},
            { "data": "dept"},
            { "data": "device_id"},
            { "data": "is_conn"},
            { "data": "options"},
        ],
        "columnDefs": [ {
            "targets": 7,
            "orderable": false
        } ]

    });

    function select2Mapping(select_id,url,placeholder,con_id="shift",){
        $('#'+select_id).select2({
            placeholder: placeholder,
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    shift_id:$('#'+con_id).val(),
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
    }
    function selectOption(id,d_name,d_id){
        if(d_name){
            var data_option = new Option(d_name,d_id, true, true);
            $('#'+id).append(data_option).trigger('change');
        }
    }
    select2Mapping("shift",'{{route('select2.shift')}}','Select Shift');
    select2Mapping("employee",'{{route('select2.employee_s')}}','Select Teacher','shift');
    $(document).on('click','#submit-form',function(){
        event.preventDefault();
        if($('#shift').val() == ""){
            alert('please select shift');
            return;
        }
        if($('#employee').val() == ""){
            alert('please select employee');
            return;
        }
        if($('#device_id').val() == ""){
            alert('please enter device id');
            return;
        }
        // console.log($("#add_data_form").serialize());
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{route('hr.device_mapping.store') }}",
            // processData: false,
            // contentType: false,
            method: 'POST',
            data:$("#add_data_form").serialize(),
            beforeSend: function() {
                console.log('Sending request...');
                $('#submit-form').prop('disabled', true).text('Saving...');
            },
            success: function(res) {
                console.log(res);
                if(res.status == "error"){
                    // console.log(res);
                    var e_option = "";
                    Object.entries(res.errors).forEach(([field, messages]) => {
                        // console.log(field, messages[0]);
                        e_option += '<div class="alert alert-danger">'+messages+'</div>';
                    });
                   
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        html: e_option,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                else if(res.status == "yes"){
                    listData();

                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: res.msg,
                        showConfirmButton: false,
                        timer: 1500
                    });
                   d_table.draw();

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
                    title: e,
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            complete: function(xhr, status) {
                console.log('Request completed with status:', status);
                $('#submit-form').prop('disabled', false).text('Save');
            }
        });
    });
    $(document).on('click','.edit_data',function(){
        var id=$(this).attr('data_id');
        $.ajax({
            url: "{{url('device-mapping/edit') }}/" + id,
            method: 'GET',
            success: function(data) {
                console.log(data);
                if(data.status == "yes"){
                    $("#mapping_id").val(data.device_mapping.id);
                    $("#device_id").val(data.device_mapping.device_id);
                    $("#status").val(data.device_mapping.is_done);
                    $(".status_op").show();
                    if(data.device_mapping.employee){
                        selectOption('employee',data.device_mapping.employee.employee_name+" ("+data.device_mapping.employee.employee_id+")",data.device_mapping.emp_id);
                        if(data.device_mapping.employee.shift){
                            selectOption('shift',data.device_mapping.employee.shift.shiftName,data.device_mapping.employee.shift_id);
                        }
                    }
                    createData("Edit Employee Mapping");
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
        let id = $(this).attr('del_data');
        // console.log( $(this));hr.device_mapping.delete
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
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('hr.device_mapping.delete') }}",
                    // processData: false,
                    // contentType: false,
                    method: 'POST',
                    data:{id:id},
                    beforeSend: function() {
                        console.log('Sending request...');
                        // $('#submit-form').prop('disabled', true).text('Saving...');
                    },
                    success: function(res) {
                        console.log(res);
                        if(res.status == "yes"){
                             
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: res.msg,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            d_table.draw();

                        }else{
                            // toastr.error(res.msg, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
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
                            title: e,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    complete: function(xhr, status) {
                        console.log('Request completed with status:', status);
                        // $('#submit-form').prop('disabled', false).text('Save');
                    }
                });
            }
        });
    });
</script>
@endsection