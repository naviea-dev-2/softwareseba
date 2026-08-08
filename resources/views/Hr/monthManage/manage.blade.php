@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Month</title>
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
            <h5 style="font-size: 0.875rem; margin:0;">Month Setup</h5>
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
                            <th>Month</th>
                            <th>Total Day</th>
                            <th>Total Working Day</th>
                            <th>Total Holiday</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthManages as $key=>$monthManage)
                            <tr class="{{$monthManage->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{$monthManage->monthDate}}</td>
                                <td>{{$monthManage->monthTotalDay}}</td>
                                <td>{{$monthManage->workingDay}}</td>
                                <td>{{$monthManage->holiday}}</td>
                                <td>
                                    <a class="btn btn-primary" href="javascript:void(0)" data-token="{{csrf_token()}}" id="monthManageEdit" data-id="{{$monthManage->id}}" data-bs-toggle="modal" data-bs-target="#updateModal">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    <a title="Delete" class="del_hr_data btn btn-danger" data-token="{{csrf_token()}}" data-action="{{route('monthManage.delete',$monthManage->id)}}" data-id="{{$monthManage->id}}"><i class="bx bx-trash"></i></a>


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
@include('Hr.monthManage.add_modal')
@include('Hr.monthManage.edit_modal')
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<link href="{{ asset('public/assets/css') }}/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/bootstrap-datepicker.min.js"></script>
<!-- month manage Edit-->
<script type="text/javascript">
     $(document).ready(function(){
         $('#dataTable').DataTable();
         var variableName = "{{ date('Y-m') }}";
         const myInput = document.querySelector(".datetimepicker");
       flatpickr(document.querySelector(".datetimepicker"), {
            altInput:true,
            defaultDate: new Date(variableName),
            plugins: [
                new monthSelectPlugin({
                shorthand: true, //defaults to false
                dateFormat: "Y-m", //defaults to "F Y"
                altFormat: "Y-m", //defaults to "F Y"
                theme: "dark" // defaults to "light"
                })
            ]
        });
        var variableName1="{{ date('Y-m') }}";
        function edit_datpicker(variableName1){
            var flstt= flatpickr(document.querySelector(".datetimepicker1"), {
             altInput:true,
             defaultDate: new Date(variableName1),
            plugins: [
                new monthSelectPlugin({
                shorthand: true, //defaults to false
                dateFormat: "Y-m", //defaults to "F Y"
                altFormat: "Y-m", //defaults to "F Y"
                theme: "dark" // defaults to "light"
                })
            ]
        });
        }

        //  $(".datetimepicker").flatpickr({
        //     altInput:true,
        //     defaultDate: new Date(variableName),
        //     plugins: [
        //         new monthSelectPlugin({
        //         shorthand: true, //defaults to false
        //         dateFormat: "Y-m", //defaults to "F Y"
        //         altFormat: "Y-m", //defaults to "F Y"
        //         theme: "dark" // defaults to "light"
        //         })
        //     ]
        // });
        $(document).on('click','#monthManageEdit',function(){
         var id=$(this).attr('data-id');
         // var termID=$('#termID').val();
          // alert(id);
            $.ajax({
                url: "{{route('monthManage.edit') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    if(data.status == 1){
                        // $('#fetchDepartment').html(data.html);
                        $('#monthManageID').val(data.monthManageID);
                        $('#monthDate').val(data.monthDate);
                        edit_datpicker(data.monthDate);
                        //    variableName1 = data.monthDate;
                        //     flstt.setDate(data.monthDate+'-01');
                        //     flstt.redraw();
                        //     console.log(flstt);
                        $('#monthTotalDay').val(data.monthTotalDay);
                        $('#workingDay').val(data.workingDay);
                        $('#holiday').val(data.holiday);
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
</script>

<script>


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
                    $('#updateModal').modal('toggle');

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
