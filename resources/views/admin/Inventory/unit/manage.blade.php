@extends('admin.inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Unit</title>
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
                            <h4 class=" "><b>Unit Manage</b> </h4>
                        </div>
                    </div>
                </div>
            </div>




            <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one my-1">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <!-- start modal -->
                        
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="bx bx-plus"></i>
                        </button>
                      

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Add Unit</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">

                            <form method="POST" action="{{route('admin.unit.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    {{-- <div class="col-sm-6">
                                        <label for="">Category</label>
                                        <select class="form-select add-select2-category" name="category" required>
                                            <option>-- Select One --</option>

                                        </select>
                                    </div> --}}
                                    <div class="col-sm-6">
                                        <label for=""> Unit name *</label>
                                        <input type="hidden" value="0" name="id"  required>
                                        <input type="text" class=" form-control"  name="name" autocomplete="off" required>
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for=""> Symbol *</label>

                                        <input type="text" class=" form-control"  name="symbol" autocomplete="off" required>
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>
                                    <div class="col-sm-3" id="exampleModalSelect" style="position: relative;">
                                        <label for=""> Business *</label>
                                        <Select name="business_id" id="business_id" class="form-control select2">
                                        
                                        </Select>
                                        <span class="invalid-feedback mb-0">
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
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

                         <!-- start modal -->
                        <!-- Button trigger modal -->


                        <!-- Modal -->
                        <div class="modal fade" id="updateModal" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Update Unit</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">

                                    <form method="POST" action="{{route('admin.unit.store')}}" enctype="multipart/form-data" class="edit_data_form">
                                        @csrf
                                        <div class="row">
                                            {{-- <div class="col-sm-6">
                                                <label for="">Category</label>
                                                <select id="category" class="form-control edit-select2-category" name="category" required>
                                                    <option>-- Select One --</option>

                                                </select>
                                            </div> --}}
                                            <div class="col-sm-6">
                                                <label for=""> Unit name *</label>
                                                <input type="hidden" value="0" name="id" id="data_id" required>
                                                <input id="name" type="text" class=" form-control" id="name" name="name" autocomplete="off" required>
                                                <span class="invalid-feedback mb-0"></span>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for=""> Symbol *</label>

                                                <input type="text" class=" form-control"  name="symbol" id="symbol" autocomplete="off" required>
                                                <span class="invalid-feedback mb-0"></span>
                                            </div>
                                            <div class="col-sm-3" id="updateModalSelect"  style="position: relative;">
                                                <label for="">Business *</label>
                                                <Select id="edit_business_id" name="business_id" class="form-control select2">
                                               
                                                </Select>
                                                <span class="invalid-feedback mb-0">
                                                </span>
                                            </div>
                                            <div class="col-sm-3">
                                                <button class="btn btn-sm btn-primary mt-4" type="submit">
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



                       
                        <div class="container pt-0">
                            <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                <tr>
                                  <th>SN.</th>
                                  {{-- <th>Category</th> --}}
                                  <th>Unit Name</th>
                                  <th>Symbol</th>
                                  <th>Business</th>
                                  <th>Actions</th>
                                </tr>
                              </thead>
                              <tbody id="filterItemShow">
                               

                              </tbody>
                                <tfoot>
                                    <tr>
                                        <!-- <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th> -->

                                    </tr>
                                </tfoot>
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
    $('#dataTable').DataTable({
        "order": [[0, 'desc']],
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('admin.unit.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){

            data._token = "{{ csrf_token() }}";

            },
        },
        "columns": [
            { "data": "id"},
            { "data": "name"},
            { "data": "symbol"},
            { "data": "business_name"},
            { "data": "options"},
        ],

    });
    $('.add-select2-category').select2({
         theme: "bootstrap-5",
        placeholder: 'Select Category',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        dropdownParent: $( '#exampleModal' ),
        ajax: {
            url: '{{route('admin.select2.product.categories')}}',
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
     $('.edit-select2-category').select2({
         theme: "bootstrap-5",
        placeholder: 'Select Category',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        dropdownParent: $( '#updateModal' ),
        ajax: {
            url: '{{route('admin.select2.product.categories')}}',
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

    $('#business_id').select2({
        theme: "bootstrap-5",
        placeholder: 'Select Business User',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        dropdownParent:$( '#exampleModalSelect' ) ,
        ajax: {
            url: '{{route('admin.select2.businesses')}}',
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
    $('#edit_business_id').select2({
        theme: "bootstrap-5",
        placeholder:'Select Business User',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        dropdownParent:$( '#updateModalSelect' ) ,
        ajax: {
            url: '{{route('admin.select2.businesses')}}',
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
    $(document).on('click','.data_edit',function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: "{{route('admin.unit.edit') }}?id=" + id,
            method: 'GET',
            success: function(data) {
                // $('#fetchDepartment').html(data.html);

                var category_option = new Option(data.category_name, data.category_id, true, true);
                $('#category').append(category_option).trigger('change');
                $('#name').val(data.name);
                $('#symbol').val(data.symbol);
                // $('#business_type').val(data.business_type_id);
                $('#data_id').val(data.id);
                if(data.business_id != 0){
                    var branch_option = new Option(data.business_name,data.business_id, true, true);
                    $('#edit_business_id').append(branch_option).trigger('change');
                }
                $('#updateModal').modal('show');
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
                window.location = "{{ url('admin/unit-delete') }}/"+id;
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
    //edit data

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
                    $('#updateModal').modal('toggle');

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
