@extends('inc.master')

@section('head')


<title>Manage Employee Bank</title>
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
                           <h4 class="my-2"><b>Employee Bank Account</b></h4>
                        </div>
                    </div>
                </div>
            </div>





            <div class="container">
                <div class="row row-card-one my-4">
                    <div class="col-md-12 col-lg-12 col-sm-12">

                         <!-- start insert modal -->
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="bx bx-plus"></i>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Employee Bank Account</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true" >&times;</span>
                                    </button>
                                  </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   <!-- form here -->
                                                    <form method="POST" action="{{route('empbankaccount.store')}}" enctype="multipart/form-data" class="add_data_form">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" value="0" name="id"  required>
                                                            <div class="col-sm-3">
                                                                <label for="">Department *</label>
                                                                <select class="form-control" id="deptID" name="deptID" required>
                                                                    <option value="">-- Select One --</option>
                                                                    @foreach($departments as $department)
                                                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                                                    @endforeach

                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label for="">Designation *</label>
                                                                <select class="form-control" id="desigID" name="desigID" required>
                                                                    <option value="">-- wait --</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Employee ID *</label>
                                                                <select class="form-control" id="empID" name="empID" required>
                                                                    <option value="">-- wait --</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <label for="">Bank *</label>
                                                                <select class="form-control" name="bankID" required>
                                                                    <option value="">-- Select One --</option>
                                                                    @foreach($banks as $bank)
                                                                    <option value="{{$bank->id}}">{{$bank->name}}</option>
                                                                    @endforeach

                                                                </select>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account's Name *</label>
                                                                <input type="text" class="form-control" name="acName" placeholder="Tania" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Branch Name *</label>
                                                                <input type="text" class="form-control" name="branchName" placeholder="Mymensingh" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account Type *</label>
                                                                <input type="text" class="form-control" name="acType" placeholder="Savings" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account Number *</label>
                                                                <input type="text" class="form-control" name="acNumber" placeholder="2131031251196" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Routing Number *</label>
                                                                <input type="text" class="form-control" name="routingNumber" placeholder="09033100" required />
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

                        <!-- start update modal -->

                        <!-- Modal -->
                        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Employee Bank Account</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true" >&times;</span>
                                    </button>
                                  </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   <!-- form here -->
                                                    <form method="POST" action="{{route('empbankaccount.store')}}" enctype="multipart/form-data" class="edit_data_form">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" value="0" name="id" id="bankaccountID" required>                                                   <div class="col-sm-3">
                                                                <label for="">Department *</label>
                                                                <select class="form-control" id="deptIDbank" name="deptID" required>
                                                                    <option value="">-- Select One --</option>
                                                                    @foreach($departments as $department)
                                                                    <option value="{{$department->id}}">{{$department->deptName}}</option>
                                                                    @endforeach

                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label for="">Designation *</label>
                                                                <select class="form-control" id="desigIDbank" name="desigID" required>
                                                                    <option value="">-- wait --</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Employee ID *</label>
                                                                <select class="form-control" id="empIDbank" name="empID" required>
                                                                    <option>-- wait --</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <label for="">Bank *</label>
                                                                <select class="form-control" id="bankID" name="bankID" required>
                                                                    <option value="">-- Select One --</option>
                                                                    @foreach($banks as $bank)
                                                                    <option value="{{$bank->id}}">{{$bank->bankName}}</option>
                                                                    @endforeach

                                                                </select>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account's Name *</label>
                                                                <input type="text" class="form-control" name="acName" id="acName" placeholder="Tania" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Branch Name *</label>
                                                                <input type="text" class="form-control" name="branchName" id="branchName" placeholder="Mymensingh" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account Type *</label>
                                                                <input type="text" class="form-control" name="acType" id="acType" placeholder="Savings" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account Number *</label>
                                                                <input type="text" class="form-control" name="acNumber" id="acNumber" placeholder="2131031251196" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Routing Number *</label>
                                                                <input type="text" class="form-control" name="routingNumber" id="routingNumber" placeholder="09033100" required />
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <button class="btn  btn-primary mt-4 " type="submit">
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
                        <br/><br/>

                        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                              <th>SN.</th>
                              <th>EmpID</th>
                              <th>Bank Name</th>
                              <th>Name</th>
                              <th>Branch</th>
                              <th>Type</th>
                              <th>Number</th>
                              <th>Routing Number</th>
                              <th>Actions</th>
                            </tr>
                          </thead>

                          <tbody>
                            @foreach($bankaccounts as $key=>$bankaccount)
                            <tr class="{{$bank->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{$bankaccount->employee?->employee_id}}</td>
                                <td>{{$bankaccount->bank?->name}}</td>
                                <td>{{$bankaccount->acName}}</td>
                                <td>{{$bankaccount->branchName}}</td>
                                <td>{{$bankaccount->acType}}</td>
                                <td>{{$bankaccount->acNumber}}</td>
                                <td>{{$bankaccount->routingNumber}}</td>
                                <td>
                                    <a class="btn btn-primary" href="javascript:void(0)"data-token="{{csrf_token()}}" id="empbankaccountEdit" data-id="{{$bankaccount->id}}" data-bs-toggle="modal" data-bs-target="#updateModal">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    <a class="del_hr_data btn btn-danger" title="Delete" href="#" id="delete" data-token="{{csrf_token()}}" data-id="{{$bankaccount->id}}"><i class="bx bx-trash"></i>
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

        <!-- Main Content Area End -->
    </div>
</div>
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<!-- bank account manage Edit-->
<script type="text/javascript">
     $(document).ready(function(){
        $('#dataTable').DataTable();
        $(document).on('change', '#deptID', function(){
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
                    $('#desigID').empty().append(option);
                }
            });
        });
        $(document).on('change', '#desigID', function(){
            var desigID = $(this).val();
            $.ajax({
                url: '{{ url("getEmployeeId1") }}',
                method: "POST",
                dataType: "JSON",
                data: {
                "_token": "{{ csrf_token() }}",
                "desigID": desigID
                },
                success: function (response) {
                    console.log(response);
                    var option = '';
                    option += '<option value="" disabled selected>-- Select One --</option>';
                    $.each(response, function (index, value) {
                        option += '<option value="' + value.id + '">' + value.employee_id + '</option>';
                    });
                    $('#empID').empty().append(option);
                }
            });
        });
        $(document).on('click','#empbankaccountEdit',function(){
         var id=$(this).attr('data-id');
           var termID=$('#termID').val();
           // alert(id);
            $.ajax({
                url: "{{route('empbankaccount.edit') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#bankID').html(data.html);
                    $('#bankaccountID').val(data.bankaccountID);
                    $('#acName').val(data.acName);
                    $('#branchName').val(data.branchName);
                    $('#acType').val(data.acType);
                    $('#acNumber').val(data.acNumber);
                    $('#routingNumber').val(data.routingNumber);
                    $('#empIDbank').html(data.empIDbank);
                    $('#desigIDbank').html(data.desigIDbank);
                    $('#deptIDbank').html(data.deptIDbank);
                }
            });
        });
    });
    $(document).on('click','.del_hr_data',function(){
        let id = $(this).attr('data-id');
        console.log( $(this));
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
                window.location = "{{ url('empbankaccount-delete') }}/"+id;
            }
        });
    });
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
                    $('#updateModal').modal('toggle');

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
