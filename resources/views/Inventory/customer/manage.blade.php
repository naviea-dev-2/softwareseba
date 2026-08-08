@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Product</title>
<style>
    label{
        font-size: 1.2rem;
    }
    .dataTables_scrollBody{
        min-height: calc(100vh - 285px);
    }
</style>
@endsection
 @section('content')
        <div class="content-area">
            <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12 ">
                        <div class="row report-title">
                            <h4 class=""><b>Customer Manage</b></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one my-1">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        @if(can_p('customer.add'))
                        <!-- start modal -->
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="bx bx-plus"></i>
                        </button>
                        @endif
                        @php

                        @endphp
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Add Customer</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true" >&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   <!-- start form here -->
                                                    <form method="POST" action="{{route('customer.store')}}" enctype="multipart/form-data" class="add_data_form">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" value="0" name="id"  required>

                                                            <div class="col-sm-6">
                                                                <label for="">Customer Name</label>
                                                                <input type="text" class=" form-control form-control-sm" name="name" autocomplete="off" required>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Email</label>
                                                                <input type="text" class=" form-control form-control-sm" name="email" autocomplete="off" required>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Mobile</label>
                                                                <input type="number" class=" form-control form-control-sm" name="mobile" autocomplete="off">
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Adress</label>
                                                                <input type="text" class=" form-control form-control-sm" name="address" autocomplete="off">
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Country</label>
                                                                <select id="add_select_country" class="form-control add_select_country" name="country">
                                                                    <option value="">-- Select One --</option>


                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">State</label>
                                                                <select id="add_select_state" class="form-control add_select_state" name="state">
                                                                    <option value="">-- Select One --</option>


                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">city</label>
                                                                <select id="add_select_city"  class="form-control add_select_city" name="city">
                                                                      <option value="">-- Select One --</option>


                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Zip code</label>
                                                                <input type="text" class=" form-control form-control-sm" name="zip_code" autocomplete="off">
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Customer Image</label>
                                                                <div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 150px;">
                                                                    <img
                                                                    class="display-upload-img-add" style="width: 150px;height: 70px;" src="{{ asset("public/images/No-image.jpg")}}" alt="">
                                                                    <input type="file" name="image" class="form-control upload-img-add" placeholder="Enter Activity Image" style="position: absolute;top: 0;opacity: 0;height: 100%;">
                                                                    <span class="invalid-feedback mb-0">
                                                                    </span>
                                                                </div>
                                                            </div>





                                                            <div class="col-sm-3">
                                                                <br/>
                                                                <button class="btn btn-sm btn-primary mt-4 " type="submit">
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
                        <!-- Modal -->
                        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Edit Customer</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   <!-- start form here -->
                                                    <form method="POST" action="{{route('customer.store')}}" enctype="multipart/form-data" class="edit_data_form">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" value="0" name="id" id="edit_data_id" required>
                                                             <div class="col-sm-6">
                                                                <label for="">customer Name</label>
                                                                <input type="text" class=" form-control form-control-sm"
                                                                id="name"
                                                                name="name" autocomplete="off" required>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Email</label>
                                                                <input type="email" class=" form-control form-control-sm"
                                                                id="email" name="email" autocomplete="off" required>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Mobile</label>
                                                                <input type="number" class=" form-control form-control-sm" name="mobile"
                                                                id="mobile" autocomplete="off">
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Adress</label>
                                                                <input type="text" class=" form-control form-control-sm" name="address"
                                                                id="address" autocomplete="off" >
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Country</label>
                                                                <select class="form-control edit_select_country" name="country" id="country" >
                                                                    <option value="">-- Select One --</option>


                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">State</label>
                                                                <select class="form-control edit_select_state" name="state" id="state">
                                                                    <option value="">-- Select One --</option>


                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">city</label>
                                                                <select class="form-control edit_select_city" name="city" id="city">
                                                                   <option value="">-- Select One --</option>


                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Zip code</label>
                                                                <input type="text" class=" form-control form-control-sm" name="zip_code" autocomplete="off" id="zip_code">
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">Customer Image</label>
                                                                <div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 150px;">
                                                                    <img id="image_edit" class="display-upload-img" style="width: 150px;height: 70px;" id="edit_image_show" src="{{ asset("public/images/No-image.jpg")}}" alt="">
                                                                    <input type="file" name="image" class="form-control upload-img" placeholder="Enter Activity Image" style="position: absolute;top: 0;opacity: 0;height: 100%;">
                                                                    <span class="invalid-feedback mb-0">
                                                                </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <br/>
                                                                <button class="btn btn-sm btn-primary mt-4 " type="submit">
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


                        <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                <th>SN.</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Zip Code</th>
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
    var d_table = $('#dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            "url": "{{ route('customer.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){

            data._token = "{{ csrf_token() }}";

            },
        },
        "columns": [
            { "data": "id"},
            { "data": "img"},
            { "data": "name"},
            { "data": "email"},
            { "data": "mobile"},
            { "data": "address"},
            { "data": "cn_name"},
            { "data": "s_name"},
            { "data": "ct_name"},
            { "data": "zip_code"},
            { "data": "options"},
        ],
        "columnDefs": [ {
          "targets": 1,
          "orderable": false
        },{
          "targets": 10,
          "orderable": false
        } ]

    });
    select2Init('.add_select_country',$( '#exampleModal' ),'{{route('select2.countries')}}','Select Country');
    select2Init('.edit_select_country',$( '#updateModal' ),'{{route('select2.countries')}}','Select Country');

    select2Init('.add_select_state',$( '#exampleModal' ),'{{route('select2.states.bycountry')}}','Select State',"add_select_country");
    select2Init('.edit_select_state',$( '#updateModal' ),'{{route('select2.states.bycountry')}}','Select State',"country");

    select2Init('#add_select_city',$( '#exampleModal' ),'{{route('select2.cities.byState')}}','Select City',null,"add_select_state");
    select2Init('#city',$( '#updateModal' ),'{{route('select2.cities.byState')}}','Select City',null,"state");

    function select2Init(source,modal,url,place_holder,country=null,state=null){
        $(source).select2({
            theme: "bootstrap-5",
            placeholder:place_holder ,
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            containerCssClass: 'select-sm',
            dropdownParent:modal ,
            ajax: {
                url:url ,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    country_id:$("#"+country).val(),
                    state_id:$("#"+state).val(),
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
    // $('#add_select_state').select2({
    //   placeholder: 'Select State',
    //   allowClear: true,
    //   width:'100%',
    //   dropdownAutoWidth : true,
    //   containerCssClass: 'select-sm',
    //   ajax: {
    //     url: '{{route('select2.states.bycountry')}}',
    //     dataType: 'json',
    //     delay: 250,
    //     data: function (params) {
    //       return {
    //         country_id : $("#add_select_country").val(),
    //         value: $.trim(params.term),
    //       };
    //     },
    //     processResults: function (response) {
    //       return {
    //         results: response
    //       };
    //     },
    //     cache: true
    //   }
    // });
    // $('#add_select_city').select2({
    //   placeholder: 'Select City',
    //   allowClear: true,
    //   width:'100%',
    //   dropdownAutoWidth : true,
    //   containerCssClass: 'select-sm',
    //   ajax: {
    //     url: '{{route('select2.cities.byState')}}',
    //     dataType: 'json',
    //     delay: 250,
    //     data: function (params) {
    //       return {
    //         state_id : $("#add_select_state").val(),
    //         value: $.trim(params.term),
    //       };
    //     },
    //     processResults: function (response) {
    //       return {
    //         results: response
    //       };
    //     },
    //     cache: true
    //   }
    // });
    // $('#state').select2({
    //   placeholder: 'Select State',
    //   allowClear: true,
    //   width:'100%',
    //   dropdownAutoWidth : true,
    //   containerCssClass: 'select-sm',
    //   ajax: {
    //     url: '{{route('select2.states.bycountry')}}',
    //     dataType: 'json',
    //     delay: 250,
    //     data: function (params) {
    //       return {
    //         country_id : $("#country").val(),
    //         value: $.trim(params.term),
    //       };
    //     },
    //     processResults: function (response) {
    //       return {
    //         results: response
    //       };
    //     },
    //     cache: true
    //   }
    // });
    // $('#city').select2({
    //   placeholder: 'Select City',
    //   allowClear: true,
    //   width:'100%',
    //   dropdownAutoWidth : true,
    //   containerCssClass: 'select-sm',
    //   ajax: {
    //     url: '{{route('select2.cities.byState')}}',
    //     dataType: 'json',
    //     delay: 250,
    //     data: function (params) {
    //       return {
    //         state_id : $("#state").val(),
    //         value: $.trim(params.term),
    //       };
    //     },
    //     processResults: function (response) {
    //       return {
    //         results: response
    //       };
    //     },
    //     cache: true
    //   }
    // });

$(document).on('change','.upload-img-add',function(){
    var files = $(this).get(0).files;
    var reader = new FileReader();
    reader.readAsDataURL(files[0]);
    var arg=this;
    reader.addEventListener("load", function(e) {
        var image = e.target.result;
        $(arg).parent().find('.display-upload-img-add').attr('src', image);
    });
});
$(document).on('change','.upload-img',function(){
    var files = $(this).get(0).files;
    var reader = new FileReader();
    reader.readAsDataURL(files[0]);
    var arg=this;
    reader.addEventListener("load", function(e) {
        var image = e.target.result;
        $(arg).parent().find('.display-upload-img').attr('src', image);
    });
});
$(document).on('click','.data_edit',function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{route('customer.edit') }}?id=" + id,
        method: 'GET',
        success: function(data) {
            $('#edit_data_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#mobile').val(data.mobile);
            $('#address').val(data.address);
            $('#zip_code').val(data.zip_code);
            $('#image_edit').attr('src',data.image);
            if(data.country_id){
                var country_option = new Option(data.country_name, data.country_id, true, true);
                $('#country').append(country_option).trigger('change');
            }
            if(data.state_id){
                var state_option = new Option(data.state_name, data.state_id, true, true);
                $('#state').append(state_option).trigger('change');
            }
            if(data.city_id){
                var city_option = new Option(data.city_name, data.city_id, true, true);
                $('#city').append(city_option).trigger('change');
            }
            $('#edit_image_show').attr('src',data.image_show);
        }
    });
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
            window.location = "{{ url('customer-delete') }}/"+id;
        }
    });
});
//add data

$(".add_data_form").on('submit', function(){
    var form = $(this);
    var form_data  = new FormData($(".add_data_form")[0]);
    var action = form.attr('action');

    form.find('button[type=submit]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

    $.ajax({
        method:'POST',
        url:action,
        data:form_data,
        processData: false,
        contentType: false,
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
                $(".add_data_form")[0].reset();
                toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                {{-- location.reload(); --}}
                d_table.draw();
            }

        }
    }).then(function(){
        form.find('button[type=submit]').attr('disabled', false).html('Save');
    });
    return false;
});
//edit data

$(".edit_data_form").on('submit', function(){
    var form = $(this);
   var form_data  = new FormData($(".edit_data_form")[0]);
    var action = form.attr('action');

    form.find('button[type=submit]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

    $.ajax({
        method:'POST',
        url:action,
        data:form_data,
         processData: false,
        contentType: false,
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
                $(".edit_data_form")[0].reset();
                toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                {{-- location.reload(); --}}
                d_table.draw();
            }

        }
    }).then(function(){
        form.find('button[type=submit]').attr('disabled', false).html('Save');
    });
    return false;
});
</script>
@endsection
