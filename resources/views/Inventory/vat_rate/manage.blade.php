@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Tax</title>
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
                            <h4 class=""><b>Tax Manage</b> </h4>
                        </div>
                    </div>
                </div>
            </div>




            <div class="container my-0 py-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one py-4">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <!-- start modal -->
                        <!-- Button trigger modal -->
                        @if(can_p('tax.add'))
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="bx bx-plus"></i>
                        </button>
                        @endif

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Add Tax</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">

                            <form method="POST" action="{{route('tax.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">

                                    <div class="col-sm-6">
                                        <label for=""> Tax name *</label>
                                        <input type="hidden" value="0" name="id"  required>
                                        <input type="text" class=" form-control form-control-sm"  name="name" autocomplete="off" required>
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for=""> Short name </label>

                                        <input type="text" class=" form-control form-control-sm"  name="short_name" autocomplete="off">
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="">Rate Type</label>
                                        <select class="form-control select2-category" name="rate_type" required>
                                            <option value="Percentage">Percentage</option>
                                            <option value="Fixed">Fixed</option>
                                        </select>
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for=""> Rate </label>

                                        <input type="number" class=" form-control form-control-sm"  name="tax_rate" autocomplete="off">
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>
                                    {{-- <div class="col-sm-6">
                                        <label for=""> Tax Number </label>

                                        <input type="text" class=" form-control form-control-sm"  name="tax_number" autocomplete="off">
                                        <span class="invalid-feedback mb-0"></span>
                                    </div> --}}
                                     <div class="col-sm-6">
                                        <label for=""> Note </label>
                                        <textarea name="note" class=" form-control form-control-sm" cols="30" rows="2"></textarea>
                                        <span class="invalid-feedback mb-0"></span>
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
                        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Update Tax</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">

                        <form method="POST" action="{{route('tax.store')}}" enctype="multipart/form-data" class="edit_data_form">
                            @csrf
                            <div class="row">

                                <div class="col-sm-6">
                                    <label for=""> Tax name *</label>
                                    <input type="hidden" value="0" name="id"  required id="data_id">
                                    <input type="text" class=" form-control form-control-sm" id="name" name="name" autocomplete="off" required>
                                    <span class="invalid-feedback mb-0"></span>
                                </div>
                                <div class="col-sm-6">
                                    <label for=""> Short name </label>

                                    <input type="text" class=" form-control form-control-sm" id="short_name" name="short_name" autocomplete="off">
                                    <span class="invalid-feedback mb-0"></span>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Rate Type</label>
                                    <select id="rate_type" class="form-control select2-category" name="rate_type" required>
                                        <option value="Percentage">Percentage</option>
                                        <option value="Fixed">Fixed</option>
                                    </select>
                                    <span class="invalid-feedback mb-0"></span>
                                </div>
                                <div class="col-sm-6">
                                    <label for=""> Rate </label>
                                    <input type="number" class=" form-control form-control-sm" id="tax_rate" name="tax_rate" autocomplete="off">
                                    <span class="invalid-feedback mb-0"></span>
                                </div>
                                {{-- <div class="col-sm-6">
                                    <label for=""> Tax Number </label>

                                    <input type="text" class=" form-control form-control-sm" id="tax_number" name="tax_number" autocomplete="off">
                                    <span class="invalid-feedback mb-0"></span>
                                </div> --}}
                                <div class="col-sm-6">
                                    <label for=""> Note </label>
                                    <textarea id="note" name="note" class=" form-control form-control-sm" cols="30" rows="2"></textarea>
                                    <span class="invalid-feedback mb-0"></span>
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
                                    <th>Short Name</th>
                                    <th>Type</th>
                                    <th>Rate</th>
                                    {{-- <th>Tax Number</th> --}}
                                    <th>Note</th>
                                    <th>Actions</th>
                                </tr>
                              </thead>
                              <tbody id="filterItemShow">
                                @php
                                    $p_edit = can_p('tax.edit');
                                    $p_delete = can_p('tax.delete');
                                @endphp
                                @foreach($taxes as $key=>$tax)
                                <tr class="{{$tax->id}}">
                                    <td>{{$key+1}}</td>
                                    <td>{{$tax->name}}</td>
                                    <td>{{$tax->short_name}}</td>
                                    <td>{{$tax->rate_type}}</td>
                                    <td>{{$tax->rate}}</td>
                                    {{-- <td>{{$tax->tax_number}}</td> --}}
                                    <td>{{$tax->remarks}}</td>
                                    <td>
                                        @if($p_edit)
                                       <a class="btn btn-primary data_edit" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#updateModal"
                                        data-id="{{$tax->id}}"
                                        >
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        @endif
                                        @if($p_delete)
                                        {{-- <a href="#" data-token="{{csrf_token()}}" data-id="{{$tax->id}}" class="del_data btn btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </a> --}}
                                        @endif
                                    </td>
                                </tr>
                               @endforeach

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
    $('#dataTable').DataTable();

    $('.data_edit').on('click',function(){
        var id = $(this).attr('data-id');
        console.log(id);
        $.ajax({
            url: "{{route('tax.edit') }}?id=" + id,
            method: 'GET',
            success: function(data) {
                console.log(data);
                $('#name').val(data.name);
                $('#data_id').val(data.id);
                $('#short_name').val(data.short_name);
                $('#tax_type').val(data.tax_type);
                $('#tax_rate').val(data.tax_rate);
                $('#tax_number').val(data.tax_number);
                $('#note').val(data.note);
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
                window.location = "{{ url('tax-delete') }}/"+id;
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
                    $('#exampleModalStore').modal('toggle');

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
</script>
@endsection
