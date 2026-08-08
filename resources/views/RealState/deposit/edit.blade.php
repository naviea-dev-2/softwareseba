
@extends('inc.master')
@section('head')
<title>Edit Deposit</title>
<style>
  .hidden {
      display: none;
  }
    .select2-container .select2-selection--single{
        height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear{
        height: 40px!important;
    }
</style>
@endsection

@section('content')
<div class="br-mainpanel">
    <div class="br-pagebody">
        <div class="br-section-wrapper pt-4">
            <div class="row">
                <div class="col-md-8">

                        <h6 class="br-section-label text-center mb-1">Edit Deposit</h6>
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                            <form id="data-form-create" action="" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Payment Date: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input type="text" placeholder="Payment Date" name="p_date" id="p_date" class="form-control fl-datepicker"  required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Land Plot: <span class="tx-danger">*</span></label>
                                        <select name="land_plot" class="form-control" id="land_plot">
                                            <option value=""> Select Land Plot</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Member: <span class="tx-danger">*</span></label>
                                        <select name="member" class="form-control" id="member">
                                            <option value=""> Select Member</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Deposit Amount</label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input type="text" placeholder="Deposit Amount" name="deposit_amount" id="deposit_amount" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Payment Method: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <select name="payment_method" id="payment_method" class="form-control">
                                                <option value="">Select Payment Method</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Account: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <select name="account" id="account" class="form-control">
                                                <option value="">Select Account</option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="col-sm-12 mt-2">
                                        <label class="form-control-label">Comments:</label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <textarea class="form-control" rows="2" name="description" id="description"></textarea>
                                        </div>
                                    </div>
                                  
                                </div>



                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right" style="text-align: right;">
                                    {{-- <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-cancel" >Cancel</a> --}}
                                    <button type="btn" class="btn btn-info" id="cus-submit-btn">Save</button>
                                    </div>
                                </div>
                            </form>

                        </div>


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
        $(document).ready(function() {
            $(".fl-datepicker").flatpickr({
                defaultDate: new Date("{{ date('Y-m-d') }}"),
            });
            function select2Exam(id,url,placeholder="",id1="id"){
                $(id).select2({
                    placeholder: placeholder,
                    allowClear: true,
                    width:'100%',
                    dropdownAutoWidth : true,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                        return {
                            value: $.trim(params.term),
                            method_id:$('#'+id1).val(),
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
            }
            function selectOption(id,d_name,d_id){
                if(d_name){
                    var data_option = new Option(d_name,d_id, true, true);
                    $('#'+id).append(data_option).trigger('change');
                }
            }
            select2Exam('#land_plot','{{route('select2.property')}}','Select Land Plot');
            select2Exam('#member','{{route('select2.member')}}','Select Member');
            select2Exam('#payment_method','{{route('select2.payment_methods')}}','Select Method');
            select2Exam('#account','{{route('select2.balance_accounts')}}','Select Account','payment_method');
           
           
            $(document).on('click','#cus-submit-btn',function(){
                event.preventDefault();

                if($('#p_date').val() == ""){
                    alert('please select date');
                    return;
                }
                if($('#payment_by').val() == ""){
                    alert('please select receipt by');
                    return;
                }
                if($('#from_fund').val() == ""){
                    alert('please select Receipt fund');
                    return;
                }
                var pr = 1
                var pr_status = 1;
                $('.a-payment').each(function(){
                    if($(this).val() == ""){
                        pr_status=0;
                        alert('please row#'+pr+' select Reeeipt for');
                        return;
                    }
                    pr++;
                });
                if(pr_status == 0){
                    return ;
                }
                $('.a-amount').each(function(){
                    if($(this).val() == "" || $(this).val() == 0){
                        pr_status=0;
                        alert('please row#'+pr+' fill amount');
                        return;
                    }
                    pr++;
                });
                if(pr_status == 0){
                    return ;
                }

                var data = new FormData();
                data.append( '_token',"{{ csrf_token() }}");
                data.append( 'v_type','Credit Voucher');
                data.append( 'receipt_date',$("#p_date").val());
                data.append( 'add_account',$("#add_account").val());
                data.append( 'payment_method',$("#payment_method").val());
                data.append( 'ref',$("#ref").val());
                data.append( 'description',$("#description").val());
                data.append( 'voucher_amount',$("#total_amount_input").val());

                $('.a-payment').each(function(){
                    data.append( 'ledgers[]',$(this).val());
                });
                $('.a-amount').each(function(){
                    data.append( 'amount[]',$(this).val());
                });
                $.ajax({
                    url: "{{ route('account.voucher.store') }}",
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    data:data,
                    success: function(res) {
                        console.log(res);
                        if(res.status == "error"){
                            console.log(res);
                        }
                        else if(res.status == "yes"){

                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: res.msg,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            window.location.reload();

                        }else{
                            Swal.fire({
                                position: "top-end",
                                icon: "error",
                                title: res.msg,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error:function(e){
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: e,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
        });

    </script>

@endsection
