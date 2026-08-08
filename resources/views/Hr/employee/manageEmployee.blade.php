@extends('inc.master')

@section('head')


<title>Manage Employee</title>
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
                <h5 style="font-size: 0.875rem; margin:0;">Employee</h5>
                <div class="d-flex" style="gap:10px;">
                    <a href="{{ route('addEmployee') }}" class="btn btn-primary float-right">Add Employee</a>
                </div>
            </div>
                
        </div>
        <div class="row" style="padding-top: 24px;">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                       <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="employeetable">
                            <thead>
                                <tr>
                                    <th scope="col">SN</th>

                                    <th scope="col">Image</th>
                                    <th scope="col">Employee ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Department Name</th>
                                    <th scope="col">Designation Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Basic Salary</th>
                                    <th scope="col">Join Date</th>
                                    <th scope="col">Action</th>

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
   
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function(){
    var datatable = $('#employeetable').DataTable({
        // 'pageLength': 2,
        "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('employee.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){
            data._token = "{{ csrf_token() }}";
            },
        },
        "columns": [
            { "data": "id"},
            { "data": "image"},
            { "data": "employee_code"},
            { "data": "employee_name"},
            { "data": "department"},
            { "data": "designation"},
            { "data": "email"},
            { "data": "mobile"},
            { "data": "salary"},
            { "data": "join_date"},
            { "data": "options"},
        ],
        "columnDefs": [ {
        "targets": 1,
        "orderable": false
        } , {
        "targets": 10,
        "orderable": false
        } ]

    });
{{-- $('#employeetable').DataTable(
    {
        responsive: true,
         "lengthMenu": [
            [ 50, 100, 150, 200, 250,500],
            [ '50', '100', '150', '200','250','500' ]
        ],
    }
); --}}
});
    $(document).on('click','.del_hr_data',function(){
        let id = $(this).attr('del_data');
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
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
		}).then(function (result) {
		    if (result.value) {
                window.location = action;
            }
        });
    });
</script>
@endsection
