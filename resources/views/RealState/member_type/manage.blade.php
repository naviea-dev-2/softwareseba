@extends('inc.master')

@section('head')


<title>Manage Member Type</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection
@section('content')
    <div class="content-area">
        <div class="container pt-0">
            <div class="row row-card-one">
                <div class="col-sm-12 ">
                    <div class="row report-title">
                        <h4 class=" "><b>Member Type</b> </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
            <div class="row row-card-one my-1">
                <div class="col-md-12 col-lg-12 col-sm-12">

                    <!-- start modal -->
                  
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-amenity">
                        <i class="bx bx-plus"></i>
                    </button>
                    
                   

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Add Property Advantage</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row row-card-one">
                                            <div class="col-sm-12">

                                                <form method="POST" action="{{route('member_type.store')}}" enctype="multipart/form-data" class="add_data_form">
                                                    @csrf
                                                    <div class="row">

                                                        <div class="col-sm-3">
                                                            <label for="">Name *</label>
                                                           <input type="hidden" value="0" name="id" id="data_id" required>
                                                            <input type="text" class=" form-control form-control-sm"  id="name" name="name" autocomplete="off" required>
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
                  
                    <div class="container pt-0">
                        <table id="amenity_dataTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th style="width:25%">SN.</th>
                                <th style="width:50%">Name</th>
                                <th style="width:25%">Actions</th>
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
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
    var d_table = $('#amenity_dataTable').DataTable({
        "order": [[0, 'desc']],
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            "url": "{{ route('member_type.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){
                data._token = "{{ csrf_token() }}";
            },
        },
        "columns": [
            { "data": "id"},
            { "data": "name"},
            { "data": "options"},
        ],
        "columnDefs": [ {
            "targets": 2,
            "orderable": false
        } ]

    });
    $(document).on("click",'.btn-amenity',function(){
       
        $('#name').val("");
        $('#data_id').val(0);
        $("#exampleModal").modal("show");
           
    });
    $(document).on("click",'.amenity_data_edit',function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: "{{route('member_type.edit') }}?id=" + id,
            method: 'GET',
            success: function(data) {
                $('#name').val(data.name);
                $('#data_id').val(data.id);
                $("#exampleModal").modal("show");
            }
        });
    });
    $(document).on('click','.amenity_del_data',function(){
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
                window.location = "{{ url('member-type-delete') }}/"+id;
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
                    d_table.draw();
                    $('#name').val("");
                    $('#data_id').val(0);
                    $("#exampleModal").modal("hide");
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
                    d_table.draw();
                    $('#name').val("");
                    $('#data_id').val(0);
                    $("#exampleModal").modal("hide");
                }

            }
        }).then(function(){
            form.find('button[type=submit]').attr('disabled', false).html('Save');

        });
        return false;
    })
</script>
@endsection
