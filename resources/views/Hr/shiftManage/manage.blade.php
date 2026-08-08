@extends('inc.master')

@section('head')
    <link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

    <title>Manage Shift</title>
    <style>
        label{
            font-size: 1.2rem;
        }
        .flatpickr-calendar {
            z-index: 1056 !important; /* make sure calendar appears above modal */
        
        }
        
    </style>
@endsection
@section('content')
    <div class="content-area">
        <div class="data-list">
            <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
                
                <div class="d-flex justify-content-between align-items-center">
                    <h5 style="font-size: 0.875rem; margin:0;">Shift</h5>
                    <div class="d-flex" style="gap:10px;">
                        <button type="button" class="btn btn-primary" onClick="createData()">
                        <i class="bx bx-plus"></i>
                        </button>
                    </div>
                </div>
                    
            </div>
            <div class="row" style="padding-top: 24px;">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $p_edit = can_p('shiftManage.edit');
                                $p_delete = can_p('shiftManage.delete');
                            @endphp
                            <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                    <th>SN.</th>
                                    <th>Shift Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shifts as $key=>$shift)
                                    <tr class="{{$shift->id}}">
                                        <td>{{$key+1}}</td>
                                        <td>{{$shift->shiftName}}</td>
                                        @php
                                            $st_arr=explode(":",$shift->startTime);
                                            if($st_arr[0] > 12){
                                                $st_time = $st_arr[0] - 12 .":".$st_arr[1]." PM";
                                            }else{
                                                $st_time =$shift->startTime.' AM';
                                            }
                                            $et_arr=explode(":",$shift->endTime);
                                            if($et_arr[0] > 12){
                                                $et_time = $et_arr[0] - 12 .":".$et_arr[1]." PM";
                                            }else{
                                                $et_time =$shift->endTime.' AM';
                                            }
                                        @endphp
                                        <td>{{$st_time}}</td>
                                        <td>{{$et_time}}</td>
                                        <td>
                                            @if($p_edit)
                                            <a class="btn btn-primary" href="javascript:void(0)" data-token="{{csrf_token()}}" id="shiftEdit" data-id="{{$shift->id}}">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            @endif
                                            @if($p_delete)
                                            <a title="Delete" class="del_hr_data btn btn-danger" data-action="{{route('shiftManage.delete',$shift->id)}}" data-token="{{csrf_token()}}" data-id="{{$shift->id}}"><i class="bx bx-trash"></i></a>
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
        <div class=" data-create" style="display:none;">
            <div class="row" style="padding-top: 24px;">
                <div class="col-md-8">
                    <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 style="font-size: 0.875rem; margin:0;">Add Shift</h5>
                            <div class="d-flex" style="gap:10px;">
                                <button type="button" class="btn btn-primary" onClick="listData()">
                                    List Shift
                                </button>
                            </div>
                        </div>
                            
                    </div>
                    <div  style="padding-top: 24px;">
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1" style="border: 1px solid;padding: 10px;">

                            <form method="POST" action="{{route('shiftManage.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id" id="shiftID" required>
                                    <div class="col-sm-3 mt-2">
                                        <label  for="">Shift Name*</label>
                                        <input type="text" class=" form-control" id="shiftName"  name="shiftName" autocomplete="off" required>
                                    </div>
                                    <div class="col-sm-3 mt-2">
                                        <label for="">Start Time*</label>
                                        <div style="position:relative;">
                                            <div id="startTime_text" style="background:#fff;padding: .375rem .75rem;border: var(--bs-border-width) solid var(--bs-border-color);"></div>
                                            <input style="position: absolute;top: 0;left: 0;opacity: 0;" type="text" class=" form-control" id="startTime" name="startTime" autocomplete="off" required>
                                            {{-- <input style="position: absolute;top: 0;left: 0;opacity: 0;" type="text" class=" form-control" id="startTime" name="startTime" autocomplete="off" required> --}}
                                        </div>
                                    </div>

                                    <div class="col-sm-3 mt-2">
                                        <label for="">End Time*</label>
                                        <div style="position:relative;">
                                            <div id="endTime_text" style="background:#fff;padding: .375rem .75rem;border: var(--bs-border-width) solid var(--bs-border-color);"></div>
                                            <input style="position: absolute;top: 0;left: 0;opacity: 0;" type="text" class=" form-control" id="endTime" name="endTime" autocomplete="off" required>
                                        </div>
                                    </div>
                                

                                    <div class="col-sm-3 mt-2">
                                        <button class="btn btn-primary mt-4 " type="submit">
                                            <i class="fa fa-save pr-2"></i>Save
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
<script>
    const date = new Date();
    const time24 = date.getHours().toString().padStart(2,'0') + ':' + date.getMinutes().toString().padStart(2,'0');
    function createData(title="Create Shift"){
        $('.data-list').hide();
        $('.data-create').show();
        $("#p_title").text(title);
    }
    function listData(){
        $('#shiftName').val("");
         $("#startTime_text").text(to12HourFormat(time24));
        $("#endTime_text").text(to12HourFormat(time24));
        $("#startTime").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: time24, // safer Laravel format
            allowInput: true,
            onChange: function(selectedDates, timeStr) {
                console.log("Time changed:", timeStr);
                // Example: auto-fill another field
                $("#startTime_text").text(to12HourFormat(timeStr));
            }
        });
        $("#endTime").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: time24, // safer Laravel format
            allowInput: true,
            onChange: function(selectedDates, timeStr) {
                console.log("Time changed:", timeStr);
                $("#endTime_text").text(to12HourFormat(timeStr));
            }
        });
        $('.data-create').hide();
        $('.data-list').show();
    }
   
    $("#startTime_text").text(to12HourFormat(time24));
    $("#endTime_text").text(to12HourFormat(time24));
    $("#startTime").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        defaultDate: time24, // safer Laravel format
        allowInput: true,
        onChange: function(selectedDates, timeStr) {
            console.log("Time changed:", timeStr);
            // Example: auto-fill another field
            $("#startTime_text").text(to12HourFormat(timeStr));
        }
    });
    $("#endTime").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        defaultDate: time24, // safer Laravel format
        allowInput: true,
        onChange: function(selectedDates, timeStr) {
            console.log("Time changed:", timeStr);
            $("#endTime_text").text(to12HourFormat(timeStr));
        }
    });
    // $('#exampleModalStore').on('shown.bs.modal', function() {

    //     // Destroy previous instances
    //     $(".fl-time").each(function() {
    //         if (this._flatpickr) this._flatpickr.destroy();
    //     });

    //     // Current time in 24-hour format
    //     const date = new Date();
    //     const time24 = date.getHours().toString().padStart(2,'0') + ':' + date.getMinutes().toString().padStart(2,'0');

    //     // Initialize Flatpickr
    //     $(".fl-time").flatpickr({
    //         enableTime: true,
    //         noCalendar: true,
    //         dateFormat: "H:i",   // 24-hour format
    //         defaultDate: time24, // pass 24-hour string
    //         allowInput: true,
    //         time_24hr: false     // optional: show AM/PM in picker
    //     });
    // })
    
    $('#dataTable').DataTable();
    function to12HourFormat(time24) {
        let [hours, minutes] = time24.split(":");
        hours = parseInt(hours);

        const suffix = hours >= 12 ? "PM" : "AM";
        hours = hours % 12 || 12;

        return `${hours}:${minutes} ${suffix}`;
    }
    $(document).on('click','#shiftEdit',function(){
        var id=$(this).attr('data-id');
        $.ajax({
            url: "{{route('shiftManage.edit') }}?id=" + id,
            method: 'GET',
            success: function(data) {
                console.log(data);
                if(data.status == "ok"){
                    $('#shiftID').val(id);
                    $('#shiftName').val(data.shiftName);
                    $('#startTime').val(data.startTime);
                    $('#endTime').val(data.endTime);
                    $('#startTime_text').text(data.st_time);
                    $('#endTime_text').text(data.et_time);
                    
                    // $('#exampleModal').modal('show');
                    $("#startTime").flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        onChange: function(selectedDates, timeStr) {
                            console.log("Time changed:", timeStr);
                            // Example: auto-fill another field
                            $("#startTime_text").text(to12HourFormat(timeStr));
                        }
                    });
                    $("#endTime").flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        onChange: function(selectedDates, timeStr) {
                            console.log("Time changed:", timeStr);
                            $("#endTime_text").text(to12HourFormat(timeStr));
                        }
                    });
                    createData("Edit Shift");
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
                if (1 == response.status) {
                    $('#exampleModalStore').modal('toggle');

                    toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    location.reload();
                }

            }
        }).then(function(){
            form.find('button[type=submit]').attr('disabled', false).html('Save');
        });
        return false;
    });
    //add data

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
                    $('#exampleModal').modal('toggle');

                    toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    location.reload();
                }

            }
        }).then(function(){
            form.find('button[type=submit]').attr('disabled', false).html('Save');
        });
        return false;
    })
</script>
@endsection
