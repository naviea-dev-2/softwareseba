@extends('admin.inc.master')

@section('head')


<title>Manage Product</title>
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
                            <h4 class=""><b>Product Manage</b></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one my-1">
                    <div class="col-md-12 col-lg-12 col-sm-12">

                        <!-- start modal -->
                        <!-- Button trigger modal -->
                        <div class="d-flex justify-content-between ">
                            
                            <a href="{{ route('admin.product.create') }}" class="btn btn-primary" ><i class="bx bx-plus"></i></a>
                           
                            <a href="{{ route('admin.import.product') }}" class="btn btn-primary" >Import Product</a>
                            
                        </div>

                        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="bx bx-plus"></i>
                        </button> --}}




                        <br/><br/>
                        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                              <th>SN.</th>
                              <th>Image</th>
                              <th>Name</th>
                              <th>Sku</th>
                              <th>Category</th>
                              <th>Brand</th>
                              <th>Business</th>
                              <th>Actions</th>
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
    $('#dataTable').DataTable({
        "order": [[0, 'desc']],
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('admin.ajax.products') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){

            data._token = "{{ csrf_token() }}";

            },
        },
        "columns": [
            { "data": "id"},
            { "data": "thumbnail"},
            { "data": "name"},
            { "data": "code"},
            { "data": "category_id"},
            { "data": "brand_id"},
            { "data": "business_id"},
            { "data": "options"},
        ],

    });
$(document).on('click','.del_data',function(){
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
                window.location = "{{ url('admin/product-delete') }}/"+id;
            }
        });
    });

</script>
@endsection
