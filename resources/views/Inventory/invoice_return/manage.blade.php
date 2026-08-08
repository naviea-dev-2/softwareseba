@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Sales Return</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
<style>
    .edit-options i {
        font-size: 16px;
        margin-right: 5px;
        vertical-align: middle;
        width: 20px;
    }
    .dropdown-menu.edit-options li a,
    .dropdown-menu.edit-options li .btn-link {
        color: #7c5cc4;
        display: block;
        text-align: left;
        text-decoration: none;
        width: 100%;
    }

    .dropdown-menu.edit-options li a:hover,
    .dropdown-menu.edit-options li .btn-link:hover {
        background-color: #f8f8f8;
        color: #7c5cc4
    }
</style>
<style>
    .print-only{
        display: none;
    }

    @media print {
        .no-print {
            display: none;
        }

        .print-only{
            display: block;
        }
    }
    .dataTables_scrollBody{
        min-height: calc(100vh - 285px);
    }
</style>
@endsection
 @section('content')
        <div class="content-area">
            <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12 ">
                        <div class="row report-title">
                            <h4 class=""><b>Sales Return</b></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one my-1">
                    <div class="col-md-12 col-lg-12 col-sm-12 py-2">

                        <!-- start modal -->
                        <!-- Button trigger modal -->
                        {{-- <a class="btn btn-primary " href="{{ route('purchase_return.create') }}"><i class="fa fa-plus"></i> New Purchase Return</a> --}}
                        {{-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
                          <i class="fas fa-plus"></i> New Purchase
                        </button> --}}
                         @php

                            $p_print= can_p('invoice.print');
                        @endphp
                        @include('Inventory.invoice_return.show-invoice-details')
                        @include('Inventory.invoice_return.add-modal-payment')
                        @include('Inventory.invoice_return.show-payment-modal')
                        <div style="display: none;">
                            @include('Inventory.invoice_return.payment_print')
                        </div>
                        <table id="dataTable" class="purchase-list table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                            <th>Sl.</th>
                            <th>Date</th>
                            <th>Reference</th>
                            @if(auth()->user()->business->business_type_id == 15)
                            <th>DSR</th>
                            {{-- <th>ASR</th>
                            <th>Sales Driver</th> --}}
                            @endif
                            <th>Customer</th>

                            <th>Grand Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Payment Status</th>
                            <th>Payment Method</th>
                            <th>Account</th>
                            <th class="not-exported"></th>
                            </tr>
                          </thead>
                          <tbody>


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

<script>
    @if(auth()->user()->business->business_type_id == 15)
    $('#dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            "url": "{{ route('invoice_return.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){

            data._token = "{{ csrf_token() }}";

            },
        },
        "columns": [
            { "data": "id"},
            { "data": "date"},
            { "data": "reference"},
            { "data": "dsr"},
            // { "data": "asr"},
            // { "data": "driver"},
            { "data": "cus_name"},
            { "data": "total"},
            { "data": "paid"},
            { "data": "due"},
            { "data": "payment_status"},
            { "data": "method"},
            { "data": "account"},
            { "data": "options"},
        ],
        "columnDefs": [ {
          "targets": 7,
          "orderable": false
        },{
          "targets": 9,
          "orderable": false
        } ]

    });
    @else
    $('#dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('invoice_return.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){

            data._token = "{{ csrf_token() }}";

            },
        },
        "columns": [
            { "data": "id"},
            { "data": "date"},
            { "data": "reference"},
            { "data": "cus_name"},
            { "data": "total"},
            { "data": "paid"},
            { "data": "due"},
            { "data": "payment_status"},
            { "data": "method"},
            { "data": "account"},
            { "data": "options"},
        ],
        "columnDefs": [ {
          "targets": 6,
          "orderable": false
        },{
          "targets": 8,
          "orderable": false
        } ]

    });
    @endif
    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }
    $(document).on('click','.view',function(){
        console.log(this);
        var id = $(this).attr('data-id');
        $.ajax({
            url: "{{url('invoice-return-detail') }}/"+id,
            method: 'GET',

            success: function(data) {
                $('#view-ajax-data').html(data);
                $('#purchase-details').modal('show');
            }
        });
    });
    $(document).on('click','.add-payment',function(){
        $('.add_payment_data_form')[0].reset();
        $('#add_account').trigger('change');
        var id = $(this).attr('data-id');
        $('#pay_invoice_id').val(id);
        $('#add_amount').attr('placeholder','Due: '+$(this).attr('data-due'));
        $('#pay_due_amount').val($(this).attr('data-due'));
    });

    $("#print-btn").on("click", function(){
          var divToPrint=document.getElementById('purchase-details');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} .modal-content{width:  1000px!important;max-width: 1000px; } .no-print {display: none;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
        //   newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},1000);
    });
    $(document).on("click",'.print-btn-payment', function(){
        var payment = JSON.parse($(this).attr('data-payment'));
        // console.log(payment)
        // console.log(payment.b)
        $('#payment_print_customer_name').html(payment.c_name);
        $('#payment_print_mobile').html(payment.c_mobile);
        $('#payment_print_email').html(payment.c_email);
        $('#payment_print_address').html(payment.c_address);
        $('#payment_print_invoice_code').html(payment.code);
        $('#payment_print_date').html(payment.d);
        $('#payment_print_method').html(payment.m);
        $('#payment_print_account').html(payment.a);
        $('#payment_print_amount').html(payment.b);
        $('#payment_print_note').html(payment.note);
        var divToPrint=document.getElementById('payment-print-details');
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} .modal-content{width:  1000px!important;max-width: 1000px; } }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
        //   newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},500);
    });
    $('#payment_method').on('change',function(){
        if(this.value == 1){
            $('.method_show').hide();
        }else{
            $('.method_show').show();
        }
    });
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
    //add data

    $(".add_payment_data_form").on('submit', function(){
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
                        if($('.add_payment_data_form').find('input[name='+key+']').length > 0)
                        {

                             $('.add_payment_data_form').find('input[name='+key+']').addClass('is-invalid');
                            $('.add_payment_data_form').find('input[name='+key+']').siblings('.invalid-feedback').html(val);
                        }else{
                             $('.add_payment_data_form').find('select[name='+key+']').addClass('is-invalid');
                            $('.add_payment_data_form').find('select[name='+key+']').siblings('.invalid-feedback').html(val);
                        }
                    });
                    if(response.error){
                        toastr.error(response.error, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    }

                }
                if (1 == response.status) {
                    $('#add-payment').modal('toggle');

                    toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                    location.reload();
                }

            }
        }).then(function(){
            form.find('button[type=submit]').attr('disabled', false).html('Save');
        });
        return false;
    });
     $(document).on('click','.payment_show',function(){

        var id = $(this).attr('data-id');
        $.ajax({
            url: "{{url('invoice-return-payment-show') }}/"+id,
            method: 'GET',

            success: function(data) {
                $('#view-ajax-data-payments').html(data);
                $('#show_payments').modal('show');
            }
        });
    });
</script>
@endsection
