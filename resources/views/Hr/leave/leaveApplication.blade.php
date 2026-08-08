@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title> Leave Application</title>
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
    .select2-container--default .select2-selection--single .select2-selection__clear{
        height: 37px!important;
    }
   
</style>
 @section('content')
<div class="content-area">
    <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
        
        <div class="d-flex justify-content-between align-items-center">
            <h5 style="font-size: 0.875rem; margin:0;">Leave Application</h5>
            <div class="d-flex" style="gap:10px;">
                <button type="button" class="btn btn-primary p-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i style="margin:0;" class="bx bx-plus"></i>
                </button>
            </div>
        </div>
            
    </div>
    <div class="row" style="padding-top: 24px;">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-layout form-layout-4 p-0">
                        <form method="POST" id="search_form" action="{{route('leaveApplication.search')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-2">

                                
                                <div class="col-sm-3">
                                    <label for="">Employee ID *</label>
                                    <select class="form-control employee_select2" id="empID2" name="empID" required>
                                        <option>-- wait --</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label for="">Leave Type </label>
                                    <select class="form-control" id="leaveType" name="leaveType" >
                                        <option value="0">-- Select One --</option>
                                        @foreach($leaveTypes as $leaveType)
                                        <option value="{{$leaveType->description}}">{{$leaveType->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="birthdaytime">From Date *</label>
                                    <input type="date" class=" form-control"  name="fDate" required>

                                </div>
                                <div class="col-sm-3">
                                    <label for="">To Date *</label>
                                    <input type="date" class=" form-control" name="tDate" required>
                                </div>
                                <div class="col-sm-3">
                                    <button class="btn btn-danger mt-4">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                    </div><!-- form-layout -->
                    @if(@$Fromdate)
                        <center style="color: red;font-size: 18px">
                        <strong>From: </strong>{{date('F j,Y',strtotime($Fromdate))}}&nbsp;&nbsp;&nbsp;&nbsp;
                        <strong>To: </strong>{{date('F j,Y',strtotime($Todate."-1 day"))}}
                        </center> <br/>
                    @endif
                    @php
                        $p_edit = can_p('leaveApplication.edit');
                    @endphp
                    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                            <th>SN.</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Leave Part</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Leave Application</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <!-- form here -->
                            <form method="POST" action="{{route('leaveApplication.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    

                                    <div class="col-sm-6 mt-2">
                                        <label for="">Employee ID *</label>
                                        <select class="form-control" id="empID" name="empID" required>
                                            <option value="">-- wait --</option>
                                        </select>
                                    </div>


                                    <div class="col-sm-3 mt-2">
                                        <label for="">Leave Type *</label>
                                        <select class="form-control" id="leaveTypeID" name="leaveTypeID" required>
                                            <option value="">-- Select One --</option>
                                            @foreach($leaveTypes as $leaveType)
                                            <option value="{{$leaveType->id}}">{{$leaveType->leaveCode}} - {{$leaveType->description}}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-sm-3 mt-2">
                                        <label for="">Leave Part *</label>
                                        <select class="form-control" id="leavePartID" name="leavePartID" required>
                                            <option value="">-- Wait --</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-3 mt-2">
                                    <label for="birthdaytime">From Date:</label>
                                    <input type="date" class=" form-control datepicker" id="birthdaytime" name="fromDate">

                                    </div>
                                    <div class="col-sm-3 mt-2">
                                    <label for="">To Date:</label>
                                    <input type="date" class=" form-control datepicker" id="birthdaytime" name="toDate">

                                    </div>

                                    <div class="col-sm-12 mt-2">
                                        <label for="">Purpose *</label>
                                        <textarea class="form-control" name="purpose" id="purpose" cols="10" rows="2" required></textarea>
                                    </div>

                                    {{-- <div class="col-sm-6 mt-2">
                                        <label for="">Address *</label>
                                        <textarea class="form-control" name="address" id="address" cols="10"  rows="2" required></textarea>
                                        <br/>
                                    </div> --}}



                                    {{-- <div class="col-sm-6">
                                        <label for="">Duty Cover Employee Department *</label>
                                        <select class="form-control" id="deptID3" name="dcEmpDeptID" required>
                                            <option value="">-- Select One --</option>
                                            @foreach($departments as $department)
                                            <option value="{{$department->id}}">{{$department->name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="">Duty Cover Employee Designation *</label>
                                        <select class="form-control" id="desigID3" name="dcEmpDesigID" required>
                                            <option value="">-- wait --</option>
                                        </select>
                                    </div> --}}

                                    {{-- <div class="col-sm-6">
                                        <label for="">Duty Cover Employee ID *</label>
                                        <select class="form-control" id="empID3" name="dcEmpID" required>
                                            <option value="">-- wait --</option>
                                        </select>
                                    </div> --}}

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
<!-- end modal -->
<!-- view Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="viewModalLabel" style="color:white;line-height:18px;">Approved Leave Application</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" >&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <span id="viewApplicationData">oi</span>
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
<!-- end view modal -->
<!-- update status Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="updateModalLabel" style="color:white;line-height:18px;">Pending Leave Application</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <span id="statusViewApplicationData">oi</span>
                        <div class="col-sm-12">
                            <form method="POST" action="{{route('leave.application.update')}}" enctype="multipart/form-data" class="edit_data_form">
                                @csrf
                                <div class="row">

                                    <div class="col-sm-6">
                                        <input type="hidden" value="" id="leaveApplicationID" name="leaveApplicationID">
                                        <input type="radio" name="status" value="1" checked>Approved &nbsp;&nbsp;
                                        <input type="radio" name="status" value="-1">Reject
                                        <br/>
                                            <button class="btn btn-primary mt-4 ">
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
<!-- endu update status modal -->     
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<!-- leave part fetch  by leave type-->
<script type="text/javascript">
    $(document).ready(function(){
       
        var datatable = $('#dataTable').DataTable({
            // 'pageLength': 2,
            "order": [[ 0, "desc" ]],
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": "{{ route('leaveApplication.ajax') }}",
                "dataType": "json",
                "type": "POST",
                data: function(data){
                data._token = "{{ csrf_token() }}";
                },
            },
            "columns": [
                { "data": "id"},
                { "data": "e_name"},
                { "data": "leave_type"},
                { "data": "leave_part"},
                { "data": "from_date"},
                { "data": "to_date"},
                { "data": "day"},
                { "data": "status"},
                { "data": "options"},
            ],
            "columnDefs": [{
            "targets": 8,
            "orderable": false
            } ]

        });
        $(".datepicker").flatpickr();
        function func2(selector,placeholder,parent,url){
            $(selector).select2({
                placeholder: placeholder,
                allowClear: true,
                width:'100%',
                dropdownAutoWidth : true,
            
                dropdownParent: parent,
                ajax: {
                    url: url,
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
        }
        func2('#empID2','Select Employee',$('#search_form'),'{{route('select2.employee')}}');
        func2('#empID','Select Employee',$('#exampleModal'),'{{route('select2.employee')}}');
       
       
   
        

        $("#leaveTypeID").change(function(){
            var id=$(this).val();

          // alert(id);
            $.ajax({
                url: "{{route('leavePartID.callByLeaveTYpe') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    $('#leavePartID').html(data.html);
                }
            });
        });
        $(document).on('click','.leaveApplicationView',function(){
            var id=$(this).attr('data-id');
            // var termID=$('#termID').val();
            // alert(id);
            $.ajax({
                url: "{{route('leave.application.single.view') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    $('#statusViewApplicationData').html(data.viewApplicationData);
                    $('#leaveApplicationID').val(data.leaveApplicationID);
                }
            });
        });
         $(document).on('click','.leaveApplicationEdit',function(){
            var id=$(this).attr('data-id');
            // var termID=$('#termID').val();
            // alert(id);
            $.ajax({
                url: "{{route('leave.application.single.view') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    if(data.status == "ok"){
                        $('#statusViewApplicationData').html(data.viewApplicationData);
                        $('#leaveApplicationID').val(data.leaveApplicationID);
                    }
                   
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
    });
</script>

@endsection
