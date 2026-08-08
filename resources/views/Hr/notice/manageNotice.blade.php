
@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>All Notice</title>
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
            <h5 style="font-size: 0.875rem; margin:0;">Notice</h5>
            <div class="d-flex" style="gap:10px;">
                <a href="{{ route('addNotice') }}" class="btn btn-primary">
                    Add New Notice
                </a>
            </div>
        </div>
            
    </div>
    <div class="row" style="padding-top: 24px;">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table id="tableNotice" class="table">
                        <thead>
                            <tr>
                            <th scope="col">SN</th>
                            <th scope="col">Notice</th>
                            <th scope="col">Notice Name</th>
                            {{-- <th scope="col">Notice Details</th> --}}
                            <th scope="col">Status</th>
                            <th class="no-sort" scope="col" >Action</th>
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
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        console.log("hi");
         var datatable = $('#tableNotice').DataTable({
            "processing": true,
            "serverSide": true,
            "searching" : true,
            "lengthMenu": [
                [ 50, 100, 150, 200, 250],
                [ '50', '100', '150', '200','250' ]
            ],
            "ajax":{
                "url": "{{ route('notice.ajax_data_list') }}",
                "data": {"_token": "{{ csrf_token() }}"},
                "dataType": "json",
                "type": "POST",
                // "success":function(res){
                //     console.log(res);
                // }
            },
            "columns": [
                { "data": "id"},
                // { "data": "serial_no"},
                { "data": "type"},
                { "data": "name"},
                { "data": "status"},
                { "data": "options"}
            ],
            "order": [[ 0, "asc" ]],
            "columnDefs": [
                {"targets"  : 'no-sort',"orderable": false},
                // {"targets": [0],"className": 'd-none'},
                // {"targets": [1,2,3],"className": 'text-center'},
            ]
        });
        // $('#tableNotice').DataTable({
        //     "searching" : true,
        // });
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
            cancelButtonClass: 'btn btn-danger ms-2',
            buttonsStyling: false,
		}).then(function (result) {
		    if (result.value) {
                window.location = action;
            }
        });
    });
</script>
@endsection
