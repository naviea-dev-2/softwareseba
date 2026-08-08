@extends('admin.inc.master')

@section('head')


<title>Manage Order</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection
 @section('content')
        <div class="content-area">
            <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12 ">
                        <div class="row report-title">
                            <h4 class=""><b>Package Order Manage</b></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one my-4">
                    <div class="col-md-12 col-lg-12 col-sm-12">

                        
                        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                              <th>SN.</th>
                              <th>Business Name</th>
                              <th>Email</th>
                              <th>Phone</th>
                              <th>Package</th>
                              <th>End Date</th>
                              <th>Amount</th>
                              {{-- <th>Actions</th> --}}
                            </tr>
                          </thead>
                          <tbody>
                            

                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area End -->
    </div>
</div>
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>

<script>
     var datatable = $('#dataTable').DataTable({
       // 'pageLength': 2,
       "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('admin.package-order.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){
              data._token = "{{ csrf_token() }}";
            },
        },
        "columns": [
            { "data": "id"},
            { "data": "name"},
            { "data": "email"},
            { "data": "phone"},
            { "data": "package"},
            { "data": "end_date"},
            { "data": "amount"},
        ],
        "columnDefs": [ {
          "targets": 0,
          "orderable": false
          } ]

    });
$(document).on('click','.del_data_user',function(){
        let id = $(this).attr('data-id');
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
                window.location = "{{ url('admin/package/delete') }}/"+id;
            }
        });
    });

</script>
@endsection
