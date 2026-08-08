@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Salary</title>
<style>
    label{
        font-size: 1.2rem;
    }
    .odd td{
        padding: 0;
        vertical-align: middle;
    }
    .even td{
        padding: 0;
        vertical-align: middle;
    }
</style>
@endsection


@section('content')
<div class="content-area">
    <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
        
        <div class="d-flex justify-content-between align-items-center">
            <h5 style="font-size: 0.875rem; margin:0;">Salary</h5>
            <div class="d-flex" style="gap:10px;">
                <a href="{{ route('addSalary') }}" class="btn btn-primary float-right">Add Salary</a>
            </div>
        </div>
            
    </div>
    <div class="row" style="padding-top: 24px;">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="tableSalary">
                        <thead>
                            <tr>
                                <th>SN.</th>
                                <th scope="col">Month</th>
                                <th scope="col">Department</th>
                                <th scope="col">Designation</th>
                                <th scope="col">Employee Name</th>
                                <th>Net Salary</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- start salary slip -->
<div class="modal fade" id="salarySlip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ledger" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Salary Slip</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" >&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <span id="salarySlipView"></span>
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
<!-- end salary slip -->
<!-- Modal Salary Pay -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Salary Pay</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" >&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <form method="POST" action="{{route('salarySheet.update')}}" enctype="multipart/form-data" class="pay_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="" name="id" id="salarySheet" required>
                                    <input type="hidden" name="due_amount" id="due_amount_edit">


                                    <div class="col-sm-3">
                                        <label for="">Paid Date *</label>
                                        <input type="date"  class="form-control datepicker" name="paidDate" value="0" required/>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">Pay Amount *</label>
                                        <input type="number" step="any" class="form-control " id="paidSalary" name="paidSalary" placeholder="12000" required/>
                                    </div>
                                    @if($payment_setting->status != 1)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-8 text-right">
                                                <strong>Payment Method</strong>
                                            </label>
                                            <Select class="form-control {{ $errors->has('payment_method') ? 'is-invalid' : '' }}" name="payment_method" id="payment_method">
                                                <option value="">Select Method</option>
                                                @foreach ($methods as $method)
                                                    <option @if(old('payment_method') == $method->id) selected @endif value="{{ $method->id }}">{{ $method->name }}</option>
                                                @endforeach
                                            </Select>
                                            @if ($errors->has('payment_method'))
                                            <span class="invalid-feedback mb-0">
                                            <strong>{{ $errors->first('payment_method') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if(array_search('accounts',load_pack_option()) != false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-8 text-right">
                                                <strong>Account *</strong>
                                            </label>
                                            <Select class="form-control {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account" id="add_account">
                                                <option value="">Select Account</option>

                                            </Select>
                                            @if ($errors->has('account'))
                                            <span class="invalid-feedback mb-0">
                                            <strong>{{ $errors->first('account') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @endif

                                    <div class="col-sm-3">
                                        <button class="btn btn-sm btn-primary mt-4 " type="submit">
                                            <i class="fa fa-save pr-2"></i>Pay
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
<!-- end modal  Salary Pay -->

@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>

$(document).ready(function(){
    var datatable = $('#tableSalary').DataTable({
        // 'pageLength': 2,
        "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('salary.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){
            data._token = "{{ csrf_token() }}";
            },
        },
        "columns": [
            { "data": "id"},
            { "data": "month"},
            { "data": "department"},
            { "data": "designation"},
            { "data": "employee_name"},
            { "data": "total_salary"},
            { "data": "paid_salary"},
            { "data": "due_salary"},
            { "data": "status"},
            { "data": "options"},
        ],
        "columnDefs": [ {
        "targets": 9,
        "orderable": false
        } ]

    });
    $(".datepicker").flatpickr({
        defaultDate: new Date("{{ date('Y-m-d') }}"),
    });
});
 $(document).on('click','#salarySheetEdit',function(){
    var id=$(this).attr('data-id');
    // var termID=$('#termID').val();
    // alert(id);
    console.log($(this).attr('data-due'));
    $('#due_amount_edit').val($(this).attr('data-due'));
    $('#paidSalary').val($(this).attr('data-due'));
    $('#salarySheet').val(id);
});
$(document).on('click','.salarySlipFetch',function(){
    var id=$(this).attr('data-id');
    // var termID=$('#termID').val();
    // alert(id);
    $.ajax({
        url: "{{route('salary.slip.fetch') }}?id=" + id,
        method: 'GET',
        success: function(data) {
            console.log(data);
            $('#salarySlipView').html(data.html);
        }
    });
});
@if(array_search('accounts',load_pack_option()) != false)
$('#add_account').select2({
    theme: "bootstrap-5",
    placeholder: 'Select Size',
    allowClear: true,
    width:'100%',
    dropdownAutoWidth : true,
    containerCssClass: 'select-sm',
    ajax: {
        url: '{{route('select2.balance_accounts')}}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                method_id:$('#payment_method').val(),
                value: $.trim(params.term),
            };
        },
        processResults: function (response) {
            console.log(response);
            return {
                results: response
            };
        },
        cache: true
    }
});
@endif
$(".pay_data_form").on('submit', function(){
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
                    if($('.pay_data_form').find('input[name='+key+']').length > 0)
                    {

                        $('.pay_data_form').find('input[name='+key+']').addClass('is-invalid');
                        $('.pay_data_form').find('input[name='+key+']').next().html(val);
                    }else{
                        $('.pay_data_form').find('select[name='+key+']').addClass('is-invalid');
                        // $('.pay_data_form').find('select[name='+key+']').next().html(val);
                    }
                });
                if(response.error){
                    toastr.error(response.error, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                }

            }
            if (1 == response.status) {
                $('#updateModal').modal('toggle');

                toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                datatable.draw();
            }
            if ("success" == response.status) {
               window.location.href = response.data;
            }

        }
    }).then(function(){
        form.find('button[type=submit]').attr('disabled', false).html('Save');
    });
    return false;
});
 $(document).on('click','.del_hr_data',function(){
    let id = $(this).attr('data-id');
    let action = $(this).attr('data-action');
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
            window.location = action;
        }
    });
});
</script>
@endsection
