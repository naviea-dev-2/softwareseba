@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Bounus</title>
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
                <h5 style="font-size: 0.875rem; margin:0;">Bounus</h5>
                <div class="d-flex" style="gap:10px;">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertModal">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>
            </div>
                
        </div>
        <div class="row" style="padding-top: 24px;">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tableBonusPay" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Employee</th>
                                    <th>Paid Date</th>
                                    <th>Occasion</th>
                                    <th>Bonus</th>
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
    @include('Hr.bonuspay.add_modal')
    @include('Hr.bonuspay.edit_modal')



@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<!-- fetch ledger -->
<script type="text/javascript">
    $(document).ready(function() {
        var datatable = $('#tableBonusPay').DataTable({
            // 'pageLength': 2,
            "order": [[ 0, "desc" ]],
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": "{{ route('bonuspay.ajax') }}",
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
                { "data": "occation"},
                { "data": "bonus_amount"},
                { "data": "method_name"},
                { "data": "status"},
                { "data": "options"},
            ],
            "columnDefs": [ {
            "targets":9,
            "orderable": false
            } ]

        });
        $('.employee_select2').select2({
            placeholder: 'Select Employee',
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
        
            dropdownParent: $('#insertModal'),
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
       
    });
    $(document).ready(function(){
        $(document).on('click','#salarySlipFetch',function(){
         var id=$(this).attr('data-id');
           // var termID=$('#termID').val();
            alert(id);
            $.ajax({
                url: "{{route('salary.slip.fetch') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    $('#salarySlipView').html(data.html);
                }
            });
        });
    });
</script>
<!-- pay salary -->
<script type="text/javascript">
    $(document).ready(function(){
        $(".datepicker").flatpickr({
            defaultDate: new Date("{{ date('Y-m-d') }}"),
        });
        
        $(document).on('click','#bonuspayEdit',function(){

            var id=$(this).attr('data-id');
            // var termID=$('#termID').val();
                // alert(id);
            $('#bonuspayID').val(id);

            $.ajax({
                url: "{{route('bonuspay.edit') }}?id=" + id,
                method: 'GET',
                success: function(res) {
                    console.log(res);
                    if(res.status == 'ok'){
                        let data =res.data;
                        $('#bonuspayID').val(data.id);
                        $('#deptID2').val(data.deptID);
                        $('#desigID2').html(res.deghtml);
                        $('#empID2').html(res.emphtml);
                        $('#desigID2').val(data.desigID);
                        $('#empID2').val(data.empID);
                        $('#bonusAmount').val(data.bonusAmount);
                        $('#payment_method').val(data.paidMethod);
                        $('#paidDate').val(data.paidDate);
                        $('#occation').val(data.occation);
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


<script>
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

<script type="text/javascript">

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
                            $('.add_data_form').find('select[name='+key+']').siblings('.invalid-feedback').html(val);
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
                            $('.edit_data_form').find('select[name='+key+']').siblings('.invalid-feedback').html(val);
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
