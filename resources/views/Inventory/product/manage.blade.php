@extends('inc.master')

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
                             @if(can_p('product.create'))
                            <a href="{{ route('product.create') }}" class="btn btn-primary" ><i class="bx bx-plus"></i></a>
                            @endif
                            @if(can_p('import.product'))
                            <a href="{{ route('import.product') }}" class="btn btn-primary" >Import Product</a>
                            @endif
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
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            {{-- @foreach($products as $key=>$product)
                            <tr class="{{$product->id}}">
                                <td>{{$key+1}}</td>
                                <td><img src="{{$product->image_show}}" style="height:50px;width:50px;"></td>
                                <td>{{$product->product_name}}</td>
                                <td>{{$product->product_code}}</td>
                                <td>{{$product->category?->name}}</td>
                                <td>{{$product->brand?->name}}</td>
                                <td>


                                        <a class="btn btn-primary data_edit" href="{{ route('product.edit',$product->id) }}">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <a href="#" data-token="{{csrf_token()}}" data-id="{{$product->id}}" class="del_data btn btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </a>


                                </td>
                            </tr>
                           @endforeach --}}

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
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            "url": "{{ route('ajax.products') }}",
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
                window.location = "{{ url('product-delete') }}/"+id;
            }
        });
    });

</script>
@endsection
