@extends('admin.inc.master')
@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage City</title>
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
                            <h4 class=""><b>City Manage</b> </h4>
                        </div>
                    </div>
                </div>
            </div>




            <div class="container my-0 py-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;margin-top:30px;">
                <div class="row row-card-one py-2">
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
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Add City</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true" >&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">

                                                    <form method="POST" action="{{route('admin.city.store')}}" enctype="multipart/form-data" class="add_data_form">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="">Country *</label>
                                                                <select id="add_select2_country" class="form-control select_country" name="country" required>
                                                                    <option value="">-- Select One --</option>

                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">State *</label>
                                                                <select id="add_select2_state" class="form-control select_state" name="state" required>
                                                                    <option value="">-- Select One --</option>

                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label for="">Name *</label>
                                                                <input type="hidden" value="0" name="id"  required>
                                                                <input type="text" class=" form-control form-control-sm"  name="name" autocomplete="off" required>
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
                        <div class="modal fade" id="updateModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Update City</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">

                                                    <form method="POST" action="{{route('admin.city.store')}}" enctype="multipart/form-data" class="edit_data_form">
                                                        @csrf
                                                        <div class="row">

                                                            <div class="col-sm-6">
                                                                <label for="">Country *</label>
                                                                <select class="form-control edit_select_country" name="country" id="country" required>
                                                                    <option value="">-- Select One --</option>

                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="">State *</label>
                                                                <select class="form-control edit_select_state" id="state" name="state" required>
                                                                    <option value="">-- Select One --</option>

                                                                </select>
                                                                <span class="invalid-feedback mb-0">
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label for="">Name *</label>
                                                                <input type="hidden" value="0" id="edit_data_id" name="id"  required>
                                                                <input type="text" class=" form-control form-control-sm"
                                                                id="name" name="name" autocomplete="off" required>
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



                      
                        <div class="container p-0">
                            <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                <tr>
                                  <th>SN.</th>
                                  <th>Name</th>
                                  
                                  <th>State</th>
                                  <th>Country</th>
                                  <th>Actions</th>
                                </tr>
                              </thead>
                              <tbody id="filterItemShow">

                              </tbody>
                               
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
    var datatable = $('#dataTable').DataTable({
       // 'pageLength': 2,
       "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('admin.city.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){
              data._token = "{{ csrf_token() }}";
            },
        },
        "columns": [
            { "data": "id"},
            { "data": "name"},
            { "data": "s_name"},
            { "data": "c_name"},
            { "data": "options"},
        ],
        "columnDefs": [ {
          "targets": 4,
          "orderable": false
          } ]

    });
    select2Init('#add_select2_country',$('#exampleModal'),'{{route('admin.select2.countries')}}','Select Country');
    select2Init('#country',$('#updateModal'),'{{route('admin.select2.countries')}}','Select Country');

    select2Init('#add_select2_state',$('#exampleModal'),'{{route('admin.select2.states.bycountry')}}','Select State','add_select2_country');
    select2Init('#state',$('#updateModal'),'{{route('admin.select2.states.bycountry')}}','Select State',"country");

    function select2Init(source,modal,url,place_holder,country=null,state_id=0){
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
                    state_id:state_id,
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

    $(document).on('click','.data_edit',function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: "{{route('admin.city.edit') }}?id=" + id,
            method: 'GET',
            success: function(data) {
                console.log(data);
                $('#name').val(data.name);

                $('#edit_data_id').val(data.id);
                var country_option = new Option(data.country_name, data.country_id, true, true);
                $('#country').append(country_option).trigger('change');
                var state_option = new Option(data.state_name, data.state_id, true, true);
                $('#state').append(state_option).trigger('change');
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
                window.location = "{{ url('admin/city-delete') }}/"+id;
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
