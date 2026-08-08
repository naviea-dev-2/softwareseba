@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Attribute</title>
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
                            <h4 class=""><b>Attribute Manage</b></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one my-1">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        @if(can_p('attributes.add'))
                        <!-- start modal -->
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary add-attribute_set" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="bx bx-plus"></i>
                        </button>
                        @endif
                        @php
                            $p_edit = can_p('attributes.edit');
                            $p_delete = can_p('attributes.delete');
                        @endphp

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Add Attribute</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true" >&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   @include("Inventory.attributes.add_edit",['is_add'=>1])

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
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Edit Attribute</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                   <div class="modal-body">
                                       <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                    @include('Inventory.attributes.add_edit',['is_add'=>0])

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
                        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                              <th>SN.</th>
                              <th>Name</th>
                              <th>Order</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($attribute_sets as $key=>$attribute_set)
                            <tr class="{{$attribute_set->id}}">
                                <td>{{$key+1}}</td>

                                <td>{{$attribute_set->title}}</td>
                                <td>{{$attribute_set->order}}</td>
                                <td>
                                    @if($p_edit)
                                    <a class="btn btn-primary data_edit m-0" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#updateModal"
                                        data-id="{{$attribute_set->id}}"
                                        style="padding:5px">
                                            <i class="bx bx-edit m-0 b-0"></i>
                                    </a>
                                    @endif
                                    @if($p_delete)
                                    <a href="#" data-token="{{csrf_token()}}" data-id="{{$attribute_set->id}}" class="del_data btn btn-danger m-0" style="padding:5px">
                                        <i class="bx bx-trash m-0 b-0"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                           @endforeach

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
    $('#dataTable').DataTable();
    $(document).on('click','.add-attribute_set',function(){

        $('.add_data_form')[0].reset();
    });
    var row_no=1;
    $(document).on("click", ".add-new-attribute", function () {
        var is_add=$(this).attr('is_add');
        $.ajax({
            url: "{{ url('/') }}" + "/getNewAttributeData",
            data: { row_no: row_no, is_edit: 0 },

            success: function (data) {
                console.log(data);
                if(is_add == 1){
                    $("#ajax-add-attribute-new").append(data);
                }else{
                    $("#ajax-add-attribute-edit").append(data);
                }

                // $(document).find(".color-input").colorpicker({
                //     format: "hex", //format - hex | rgb | rgba.
                // });
                row_no++;
            },
        });
    });
    $('.data_edit').on('click',function(){
        var id = $(this).attr('data-id');

        $.ajax({
            type: "GET",
            url: "{{ url('/') }}" + "/getAttributesById",
            data: "id=" + id,
            success: function (response) {
                var data = response;
                console.log(data);
                $("#edit_data_id").val(data.attribute_set.id);
                $("#edit_name").val(data.attribute_set.title);
                $("#edit_order").val(data.attribute_set.order);

                $(document).find("#ajax-add-attribute-edit").html(data.attributes);
                // $(document).find(".color-input").colorpicker({
                //     format: "hex", //format - hex | rgb | rgba.
                // });
                // onEditPanel();
            },
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
            window.location = "{{ url('deleteAttributes') }}?id="+id;
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
            if (0 == response.msgType) {
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
            if ('success' == response.msgType) {
                $('#exampleModal').modal('toggle');

                toastr.success(response.msg, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                window.location = "{{ url('attributes-list') }}";
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
   console.log(form_data);
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
            if ('success' == response.msgType) {
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
$(document).on("click", ".remove-item", function () {
    $(this).parent().parent().remove();
});
$(document).on("click", ".old-remove-item", function () {
    $(this)
        .parent()
        .parent()
        .parent()
        .append(
            "<input type='hidden' name='del_attribute[]' value='" +
                $(this).attr("data-id") +
                "' >"
        );
    $(this).parent().parent().remove();
});
</script>
@endsection
