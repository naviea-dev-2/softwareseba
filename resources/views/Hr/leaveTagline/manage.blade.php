@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Leave Tagline</title>
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
            <h5 style="font-size: 0.875rem; margin:0;">Leave Tagline</h5>
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
                        $p_edit = can_p('leaveTagline.edit');
                        $p_delete = can_p('leaveTagline.delete');
                    @endphp
                    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>SN.</th>
                                <th>Leave Code</th>
                                <th>Leave Description</th>
                                <th>Leave Type</th>
                                <th>Leave Type Day</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveTaglines as $key=>$leaveTagline)
                                <tr class="{{$leaveTagline->id}}">
                                    <td>{{$key+1}}</td>
                                    <td>{{$leaveTagline->leaveType?->leaveCode}}</td>
                                    <td>{{$leaveTagline->leaveType?->description}}</td>
                                    <td>{{$leaveTagline->leavePart?->levaePartName}}</td>
                                    <td>{{$leaveTagline->leavePart?->day}}</td>
                                    <td>
                                        @if($p_edit)
                                        <a class="btn btn-primary" href="javascript:void(0)" data-token="{{csrf_token()}}" id="leaveTaglineEdit" data-id="{{$leaveTagline->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        @endif
                                        @if($p_delete)
                                            <a title="Delete" class="del_hr_data btn btn-danger" data-action="{{route('leaveTagline.delete',$leaveTagline->id)}}" data-token="{{csrf_token()}}" data-id="{{$leaveTagline->id}}"><i class="bx bx-trash"></i></a>
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
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Leave Tagline</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <!-- form here -->
                            <form method="POST" action="{{route('leaveTagline.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id" required>
                                    <div class="col-sm-3">
                                        <label for="">Leave Type *</label>
                                        <select class="form-control" name="leaveTypeID" required>
                                            <option value="">-- Select One --</option>
                                            @foreach($leaveTypes as $leaveType)
                                            <option value="{{$leaveType->id}}">{{$leaveType->leaveCode}} - {{$leaveType->description}}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Leave Part *</label>
                                        <select class="form-control" name="leavePartID" required>
                                            <option value="">-- Select One --</option>
                                            @foreach($leaveParts as $leavePart)
                                            <option value="{{$leavePart->id}}">{{$leavePart->levaePartName}} - {{$leavePart->day}}</option>
                                            @endforeach

                                        </select>
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
<!-- end modal for insert-->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Leave Tagline</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <!-- form here -->
                            <form method="POST" action="{{route('leaveTagline.store')}}" enctype="multipart/form-data" class="edit_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id" id="leaveTaglinID" required>
                                    <div class="col-sm-3">
                                        <label for="">Leave Type *</label>
                                        <select class="form-control" id="leaveTypeID" name="leaveTypeID" required>
                                            <option value="">-- Select One --</option>
                                            @foreach($leaveTypes as $leaveType)
                                            <option value="{{$leaveType->id}}">{{$leaveType->leaveCode}} - {{$leaveType->description}}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Leave Part *</label>
                                        <select class="form-control" id="leavePartID" name="leavePartID" required>
                                            <option value="">-- Select One --</option>
                                            @foreach($leaveParts as $leavePart)
                                            <option value="{{$leavePart->id}}">{{$leavePart->levaePartName}} - {{$leavePart->day}}</option>
                                            @endforeach

                                        </select>
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
<!-- end modal for edit -->     
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<!-- shift edit  -->
<script type="text/javascript">
    $(document).ready(function(){
         $('#dataTable').DataTable();
        $(document).on('click','#leaveTaglineEdit',function(){
         var id=$(this).attr('data-id');
         // var termID=$('#termID').val();
           // alert(id);
            $.ajax({
                url: "{{route('leaveTagline.edit') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    if(data.status == 1){
                        $('#leavePartID').html(data.leavePartID);
                        $('#leaveTaglinID').val(data.leaveTaglinID);
                        $('#leaveTypeID').html(data.leaveTypeID);
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

            },
            complete:function(){
                form.find('button[type=submit]').attr('disabled', false).html('Save');
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
    });
</script>
@endsection
