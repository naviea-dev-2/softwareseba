@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Branch</title>
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
                            <h4 class=""><b>Branch Manage</b> </h4>
                        </div>
                    </div>
                </div>
            </div>




            <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one py-4">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <!-- start modal -->
                        <!-- Button trigger modal -->
                        @if(can_p('branch.add'))
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="bx bx-plus"></i>
                        </button>
                        @endif

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;color:#fff;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:#fff;">Add Branch</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">

                    <form method="POST" action="{{route('branch.store')}}" enctype="multipart/form-data" class="add_data_form">
                        @csrf
                        <div class="row">

                            <div class="col-sm-3">
                                <label for=""> Branch name *</label>
                                <input type="hidden" value="0" name="id"  required>
                                <input type="text" class=" form-control form-control-sm"  name="name" autocomplete="off" required>
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for=""> Branch Email *</label>

                                <input type="text" class=" form-control form-control-sm"  name="email" autocomplete="off" required>
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for=""> Branch Mobile *</label>

                                <input type="number" class=" form-control form-control-sm"  name="mobile" autocomplete="off" required>
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for=""> Branch address *</label>

                                <input type="text" class=" form-control form-control-sm"  name="address" autocomplete="off" required>
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for=""> Branch Type *</label>
                                <select name="type" class="form-control form-control-sm"  autocomplete="off">
                                    <option value="0">Secondary</option>
                                    <option value="1">Primary</option>
                                </select>

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

                         <!-- start modal -->
                        <!-- Button trigger modal -->


                        <!-- Modal -->
                        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Update Branch</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">

                    <form method="POST" action="{{route('branch.store')}}" enctype="multipart/form-data" class="edit_data_form">
                        @csrf
                        <div class="row">

                            <div class="col-sm-3">
                                <label for=""> Branch name *</label>
                                <input type="hidden" value="0" name="id" id="data_id" required>
                                <input type="text" class=" form-control form-control-sm" id="name" name="name" autocomplete="off" required>
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for=""> Branch Email *</label>

                                <input type="email" class=" form-control form-control-sm" id="email"  name="email" autocomplete="off" required>
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for=""> Branch Mobile *</label>

                                <input id="mobile" type="number" class=" form-control form-control-sm"  name="mobile" autocomplete="off" required>
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for=""> Branch address *</label>

                                <input id="address" type="text" class=" form-control form-control-sm"  name="address" autocomplete="off" required>
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for=""> Branch Type *</label>
                                <select name="type" class="form-control form-control-sm" id="type" autocomplete="off">
                                    <option value="0">Secondary</option>
                                    <option value="1">Primary</option>
                                </select>

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



                        <!-- <input type="text" class=" form-control form-control-sm" autocomplete="off"  style="float:right;max-width: 200px;" id="searchItemCall" placeholder="Search..." required> -->
                        <div class="container p-0">
                            <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                <tr>
                                  <th>SN.</th>
                                  <th>Name</th>
                                  <th>Email</th>
                                  <th>Mobile</th>
                                  <th>Address</th>
                                  <th>Type</th>
                                  <th>Actions</th>
                                </tr>
                              </thead>
                              <tbody id="filterItemShow">
                                @php
                                    $p_edit = can_p('branch.edit');
                                    $p_delete = can_p('branch.delete');
                                @endphp
                                @foreach($branches as $key=>$branch)
                                <tr class="{{$branch->id}}">
                                   <td>{{$key+1}}</td>
                                   <td>{{$branch->name}}</td>
                                   <td>{{$branch->email}}</td>
                                   <td>{{$branch->mobile}}</td>
                                   <td>{{$branch->address}}</td>
                                    <td>{{$branch->is_primary == 0 ? "Secondary": "Primary"}}</td>
                                   <td>
                                        @if($p_edit)
                                        <a class="btn btn-primary data_edit" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#updateModal"
                                        data-id="{{$branch->id}}"
                                        >
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        @endif
                                        @if($p_delete)
                                        <a href="#" data-token="{{csrf_token()}}" data-id="{{$branch->id}}" class="del_data btn btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </a>
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
        $.ajax({
            url: "{{route('branch.edit') }}?id=" + id,
            method: 'GET',
            success: function(data) {
                // $('#fetchDepartment').html(data.html);
                $('#name').val(data.name);
                $('#data_id').val(data.id);
                $('#email').val(data.email);
                $('#mobile').val(data.mobile);
                $('#address').val(data.address);
                $('#type').val(data.type);
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
                window.location = "{{ url('branch-delete') }}/"+id;
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
