@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Payment Range</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection


@section('content')
<div class="content-area">
    <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
        
        <div class="d-flex justify-content-between align-items-center">
            <h5 style="font-size: 0.875rem; margin:0;">Paymnent Range</h5>
            <div class="d-flex" style="gap:10px;">
                <a href="{{ route('addPayroll') }}" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bx bx-plus"></i></a>
            </div>
        </div>
            
    </div>
    <div class="row" style="padding-top: 24px;">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="tablePaymentRange">
                        <thead>
                            <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Department</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Minimum Amount</th>
                            <th scope="col">Maximum Amount</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($viewAll as $i=>$data)
                                <tr>
                                <th scope="row">{{ $i+1 }}</th>

                                <td>{{$data->department?->name}}</td>
                                <td>{{$data->designation?->name}}</td>
                                <td>BDT {{$data->minimum_amount}}</td>
                                <td>BDT {{$data->maximum_amount}}</td>
                                <td>
                                    <a class="btn btn-primary" href="{{ url('editPayroll') }}/{{ $data->id }}" data-bs-toggle="modal" data-bs-target="#editModal_{{$data->id}}"
                                    >
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <a href="#" del_data="{{ $data->id }}" class="del_hr_data btn btn-danger">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </td>
                                </tr>
                            @endforeach



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                <h5 class="modal-title" id="addModalLabel" style="color:white;line-height:18px;">Payment Range</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">

                            <!-- form here -->
                            <form method="POST" action="{{route('storePaymentRange')}}" class="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id" required="">
                                    <div class="col-sm-3">
                                        <label for="">Department *</label>
                                        <select class="form-control" id="deptID2"
                                        name="department_id" required="">
                                            <option value="">-- Select One --</option>



                                            @foreach($departments as $deparment)
                                                <option value="{{$deparment->id}}">{{$deparment->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">Designation *</label>
                                        <select class="form-control" id="desigID2" name="designation_id" required=""><option valid-feedback >-- Select One --</option></select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="">Minimum Payment *</label>
                                        <input type="number" step="any" class=" form-control" name="min" autocomplete="off" required="">
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="">Maximum Payment *</label>
                                        <input type="number" step="any" class=" form-control" name="max" autocomplete="off" required="">
                                    </div>
                                    <div class="col-sm-12 text-center mt-1 ">
                                        <button class="btn btn-primary">
                                            <i class="fa fa-save pr-2"></i>Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@forEach($viewAll as $p)

    <!-- edit  Modal -->
    <div class="modal fade" id="editModal_{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                <h5 class="modal-title" id="addModalLabel" style="color:white;line-height:18px;">Update Payment Range</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" >×</span>
                </button>
                </div>
            <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row row-card-one">
                            <div class="col-sm-12">

                                <!-- form here -->
                                <form method="POST" action="{{route('updatePaymentRange',$p->id)}}" class="edit_data_form">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" value="0" name="id" required="">
                                        <div class="col-sm-3">
                                            <label for="">Department *</label>
                                            <select class="form-control" id="edit_deptID2" name="department_id" required="">
                                                <option value="">-- Select One --</option>
                                                @foreach($departments as $deparment)

                                                <option value="{{$deparment->id}}"
                                                @if($deparment->id == $p->department_id)
                                                selected
                                                @endif
                                                >{{$deparment->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="">Designation *</label>
                                            <select class="form-control" id="edit_desigID2" name="designation_id" required="">
                                            <option value="">-- Select One --</option>
                                                @foreach($designations as $d)
                                                <option value="{{$d->id}}"
                                                @if($d->id == $p->designation_id)
                                                selected
                                                @endif
                                                >{{$d->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="">Minimum Payment *</label>
                                            <input type="number" step="any" class=" form-control" name="min" autocomplete="off" required="" value="{{$p->minimum_amount}}">
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="">Maximum Payment *</label>
                                            <input type="number" step="any" value="{{$p->maximum_amount}}" class=" form-control" name="max" autocomplete="off" required="">
                                        </div>
                                        <div class="col-sm-12 text-center mt-1">
                                            <button class="btn btn-primary ">
                                                <i class="fa fa-save pr-2"></i>Update
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function(){
$('#tablePaymentRange').DataTable();
});
</script>





<script>
$(document).on('change', '#deptID2', function(){
    var deptID = $(this).val();
    $.ajax({
        url: '{{ url("getDesigName1") }}',
        method: "POST",
        dataType: "JSON",
        data: {
        "_token": "{{ csrf_token() }}",
        "deptID": deptID
        },
        success: function (response) {
            console.log(response);
            var option = '';
            option += '<option value="" disabled selected>-- Select One --</option>';
            $.each(response, function (index, value) {
                option += '<option value="' + value.id + '">' + value.name + '</option>';
            });
            $('#desigID2').empty().append(option);
        }
    });
});
    $('body').on("change",'#edit_deptID2',function(){
        let id = $(this).val()

        let url = "{{ url('designations-by-dept') }}/" + id
         axios.get(url)
            .then(res => {
                let html = '';
                html += '<option value="">-- Select One --</option>'
                res.data.forEach(element => {
                    html += "<option value=" + element.id + ">" + element.name + "</option>"
                });
                 $('#edit_desigID2').html(html);
            });
    })

</script>
<script>

    // $('body').on("change",'#deptID2',function(){
    //     let id = $(this).val()

    //     let url = "{{ url('designations-by-dept') }}/" + id
    //      axios.get(url)
    //         .then(res => {
    //             let html = '';
    //             html += '<option value="">-- Select One --</option>'
    //             res.data.forEach(element => {
    //                 html += "<option value=" + element.id + ">" + element.name + "</option>"
    //             });
    //              $('#desigID2').html(html);
    //         });
    // })
    $(document).on('click','.del_hr_data',function(){
        let id = $(this).attr('del_data');
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
                window.location = "{{ url('deletePaymentRange') }}/"+id;
            }
        });
    })
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
                    $('#insertModal').modal('toggle');

                    toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    location.reload();
                }

            }
        }).then(function(){
            form.find('button[type=submit]').attr('disabled', false).html('Save');
        });
        return false;
    });
    //add data

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
                    $('#insertModal').modal('toggle');

                    toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    location.reload();
                }

            }
        }).then(function(){
            form.find('button[type=submit]').attr('disabled', false).html('Save');
        });
        return false;
    })
</script>
@endsection
