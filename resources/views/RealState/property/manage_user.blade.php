@extends('inc.master')

@section('head')


<title>Manage Property</title>
<style>
    label{
        font-size: 1.2rem;
    }
    .card {
        box-shadow: none!important;
        margin-bottom: 24px!important;
        transition: box-shadow 0.2s ease-in-out!important;
    }
    .card-header{
        border-bottom: 1px solid #eeeeee!important;
        padding:25px 25px!important;
    }
    .card-body {
        padding: 25px 25px!important;
    }
</style>
@endsection
 @section('content')
        <div class="content-area">
            <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
                
                <div class="d-flex justify-content-between align-items-center">
                    <h5 style="font-size: 0.875rem; margin:0;">Property</h5>
                    {{-- <div class="d-flex justify-content-between">
                        <a href="{{ route('property.create') }}" class="btn btn-primary" ><i class="bx bx-plus"></i> Add Property</a>
                    </div> --}}
                </div>
                   
            </div>

           
            <div class="row" style="padding-top: 24px;">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <table id="property_dataTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SN.</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Country</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Zipcode</th>
                                        <th>Address</th>
                                        {{-- <th>Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
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
    $('#property_dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            "url": "{{ route('property.ajax_user') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){
                data._token = "{{ csrf_token() }}";
            },
        },
        "columns": [
            { "data": "id"},
            { "data": "image"},
            { "data": "name"},
            { "data": "price"},
            { "data": "country"},
            { "data": "state"},
            { "data": "city"},
            { "data": "zipcode"},
            { "data": "address"},
            // { "data": "options"},
        ],
        "columnDefs": [ {
          "targets": 8,
          "orderable": false
        } ]

    });
   

</script>
@endsection
