@extends('inc.master')

@section('head')


<title>Manage Employee Loan</title>
<style>
    label{
        font-size: 1.2rem;
    }
     .select2-container .select2-selection--single{
        height: 37px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 37px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 37px!important;
    }
</style>

@endsection
@section('content')
    <div class="content-area">
        <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
            
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="font-size: 0.875rem; margin:0;">Employee Loan</h5>
                <div class="d-flex" style="gap:10px;">
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bx bx-plus"></i>
                    </button>
                </div>
            </div>
                
        </div>
        <div class="row" style="padding-top: 24px;">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                <th>SN.</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Employee</th>
                                    <th>Paid Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Paid Method</th>
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
    <!-- start loan view -->
    <div class="modal fade" id="loanLedger" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg ledger" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                <h4 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Loan ledger</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row row-card-one">
                            <div class="col-sm-12">
                                <div id="loanLedgerView" style="overflow-x: auto;"></div>
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
    <!-- end loan view -->
    @include('Hr.empLoan.add_modal')
    <!-- Modal -->
    @include('Hr.empLoan.edit_modal')
    <!-- end modal -->
        
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<!-- bank account manage Edit-->
<script type="text/javascript">
    $(document).ready(function(){
        var datatable = $('#dataTable').DataTable({
            // 'pageLength': 2,
            "order": [[ 0, "desc" ]],
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": "{{ route('emploan.ajax') }}",
                "dataType": "json",
                "type": "POST",
                data: function(data){
                data._token = "{{ csrf_token() }}";
                },
            },
            "columns": [
                { "data": "id"},
                { "data": "department"},
                { "data": "designation"},
                { "data": "employee_name"},
             
                { "data": "date"},
                { "data": "type"},
                { "data": "amount"},
                { "data": "method_name"},
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
        $('.employee_select2').select2({
            placeholder: 'Select Employee',
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
        
            dropdownParent: $('#addModal'),
            ajax: {
                url: '{{route('select2.employee')}}',
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
       
   
        $(document).on('click','#loanEdit',function(){

            var id=$(this).attr('data-id');
            // var termID=$('#termID').val();
                // alert(id);
            //$('#bonuspayID').val(id);

            $.ajax({
                url: "{{route('emploan.edit') }}?id=" + id,
                method: 'GET',
                success: function(res) {
                    console.log(res);
                    if(res.status == 'ok'){
                        let data =res.data;
                        $('#loanID').val(data.id);
                        $('#deptID2').val(data.deptID);
                        $('#desigID2').html(res.deghtml);
                        $('#empID2').html(res.emphtml);
                        $('#desigID2').val(data.desigID);
                        $('#empID2').val(data.empID);
                        $('#loanAmount').val(data.amount);
                        $('#payment_method').val(data.method_id);
                        $('#paidDate').val(data.paidDate);
                        $('#edit_type').val(data.type);
                        $('#edit_account').html(res.accounts);
                        $('#edit_account').val(data.balance_account_id);

                        $(".datepicker").flatpickr();
                        $('#updateModal').modal('show');
                    }else{
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    

                }
            });

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
    });
</script>
<!-- fetch ledger -->
<script type="text/javascript">
    $(document).ready(function(){
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
        $(document).on('click','#loanLedgerFetech',function(){
            var id=$(this).attr('data-id');
           // var termID=$('#termID').val();
            // alert(id);
            $.ajax({
                url: "{{route('emploan.loanLegder') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    $('#loanLedgerView').html(data.html);
                }
            });
        });
    });
</script>



<!-- get emp bankAccout by bank -->
<script type="text/javascript">
     $(document).ready(function(){
        $("#empBankID").change(function(){
         var id=$(this).val();

          // alert(id);
            $.ajax({
                url: "{{route('empbankaccount.callByBankID') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    $('#empBankACID').html(data.html);
                }
            });
        });
    });
</script>

<!-- get com bankAccout by bank -->
<script type="text/javascript">
     $(document).ready(function(){
        $("#comBankID").change(function(){
         var id=$(this).val();

          // alert(id);
            $.ajax({
                url: "{{route('combankaccount.callByBankID') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    $('#comBankACID').html(data.html);
                }
            });
        });
    });
</script>

<!-- show bank depend on cash or bank method -->
<script type="text/javascript">
     $(document).ready(function(){
        $("#method").change(function(){
            var id=$(this).val();
            if(id=='Bank'){
                $('.showBankInfo').show();
            }
            else{
                $('.showBankInfo').hide();
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
                if ("success" == response.status) {
                    window.location.href = response.data;
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
    });
    $('#add_account').select2({
        theme: "bootstrap-5",
        placeholder: 'Select Bank Account',
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
                    method_id:$('#add_payment_method').val(),
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
    $('#edit_account').select2({
        theme: "bootstrap-5",
        placeholder: 'Select Bank Account',
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
</script>

@endsection
