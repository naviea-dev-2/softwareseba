@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Leave Type</title>
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
            <h5 style="font-size: 0.875rem; margin:0;">Leave Type</h5>
            <div class="d-flex" style="gap:10px;">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalStore">
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
                        $p_edit = can_p('leaveType.edit');
                        $p_delete = can_p('leaveType.delete');
                    @endphp
                    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                            <th>SN.</th>
                            <th>Leave Code</th>
                            <th>Description</th>
                            <th>Total Day</th>
                            <th>Total Hour</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveTypes as $key=>$leaveType)
                            <tr class="{{$leaveType->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{$leaveType->leaveCode}}</td>
                                <td>{{$leaveType->description}}</td>
                                <td>{{$leaveType->day}}</td>
                                <td>{{$leaveType->hour}}</td>
                                <td>
                                    @if($p_edit)
                                        <a class="btn btn-primary" href="javascript:void(0)" data-token="{{csrf_token()}}" id="leaveTypeEdit" data-id="{{$leaveType->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                    @endif
                                    @if($p_delete)
                                        <a title="Delete" class="del_hr_data btn btn-danger" data-token="{{csrf_token()}}" data-action="{{route('leaveType.delete',$leaveType->id)}}" data-id="{{$leaveType->id}}"><i class="bx bx-trash"></i></a>
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
<!-- Modal -->
<div class="modal fade" id="exampleModalStore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Leave Type</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <!-- form here -->
                            <form method="POST" action="{{route('leaveType.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id" required>
                                    <div class="col-sm-3">
                                        <label for="">Leave Code*</label>
                                        <input type="text" class=" form-control"  name="leaveCode" placeholder="ML" autocomplete="off" required>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Description*</label>
                                        <input type="text" class=" form-control"  name="description" placeholder="Medical Leave" autocomplete="off" required>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Total Day</label>
                                        <input type="number" class=" form-control"  name="day" placeholder="10" autocomplete="off">
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Total Hour</label>
                                        <input type="number" class=" form-control"  name="hour" placeholder="45" autocomplete="off">
                                    </div>


                                    <div class="col-sm-3">
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
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Leave Type</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <!-- form here -->
                            <form method="POST" action="{{route('leaveType.store')}}" enctype="multipart/form-data" class="edit_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id" id="leaveTypeID" required>
                                    <div class="col-sm-3">
                                        <label for="">Leave Code*</label>
                                        <input type="text" class=" form-control"  name="leaveCode" placeholder="ML" id="leaveCode" autocomplete="off" required>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Description*</label>
                                        <input type="text" class=" form-control"  name="description" placeholder="Medical Leave" id="description" autocomplete="off" required>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Total Day</label>
                                        <input type="number" class=" form-control"  name="day" placeholder="10" id="day" autocomplete="off">
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Total Hour</label>
                                        <input type="number" class=" form-control"  name="hour" placeholder="45" id="hour" autocomplete="off" >
                                    </div>

                                    <div class="col-sm-3">
                                        <button class="btn btn-primary mt-4 "  type="submit">
                                            <i class="fa fa-save pr-2"></i>Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<!-- end modal for edit -->
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<!-- shift edit  -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#dataTable').DataTable();
        $(document).on('click','#leaveTypeEdit',function(){
         var id=$(this).attr('data-id');
         // var termID=$('#termID').val();
           // alert(id);
            $.ajax({
                url: "{{route('leaveType.edit') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    if(data.status == 1){
                        $('#leaveCode').val(data.leaveCode);
                        $('#leaveTypeID').val(data.leaveTypeID);
                        $('#description').val(data.description);
                        $('#day').val(data.day);
                        $('#hour').val(data.hour);
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
