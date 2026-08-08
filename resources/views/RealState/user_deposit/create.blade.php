
@extends('inc.master')
@section('head')
<title>Add Deposit</title>
<style>
  
</style>
@endsection

@section('content')
<div class="br-mainpanel">
    <div class="br-pagebody">
        <div class="br-section-wrapper pt-4">
            <div class="row">
                <div class="col-md-8">

                        <h6 class="br-section-label text-center mb-1">Add Deposit</h6>
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                            <form id="data-form-create" action="{{ route("user_deposit.store") }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-label">Payment Date: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input value="{{ old("payment_date") }}" type="text" placeholder="Payment Date" name="payment_date" id="p_date" class="form-control fl-datepicker @error('payment_date') is-invalid @enderror"  required>
                                        </div>
                                        @error('payment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-label">Land Plot: <span class="tx-danger">*</span></label>
                                        <input type="hidden" name="land_plot_h" id="land_plot_h" value="{{ old("land_plot_h") }}"/>
                                        <select name="land_plot" class="form-control @error('land_plot') is-invalid @enderror" id="land_plot">
                                            <option value=""> Select Land Plot</option>
                                            @if(old("land_plot"))
                                                <option value="{{ old("land_plot") }}" selected>{{ old("land_plot_h") }}</option>
                                            @endif
                                        </select>
                                        @error('land_plot')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Member: <span class="tx-danger">*</span></label>
                                        <select name="member" class="form-control" id="member">
                                            <option value=""> Select Member</option>
                                        </select>
                                    </div> --}}
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-label">Deposit Amount</label>
                                        
                                        <input value="{{ old("deposit_amount") }}" type="text" placeholder="Deposit Amount" name="deposit_amount" id="deposit_amount" class="form-control @error('deposit_amount') is-invalid @enderror">
                                        
                                        @error('deposit_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="payment_method_status" value="{{ $payment_setting->status }}"/>
                                    @if($payment_setting->status == 0)
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-label">Payment Method: <span class="tx-danger">*</span></label>
                                       
                                        <input type="hidden" name="payment_method_h" id="payment_method_h" value="{{ old("payment_method_h") }}"/>
                                        <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
                                            <option value="">Select Payment Method</option>
                                            @if(old("payment_method"))
                                                <option value="{{ old("payment_method") }}" selected>{{ old("payment_method_h") }}</option>
                                            @endif
                                        </select>
                                        
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                        <input type="hidden" name="account_h" id="account_h" value="{{ old("account_h") }}"/>
                                        <select name="account" id="account" class="form-control @error('account') is-invalid @enderror">
                                            <option value="">Select Account</option>
                                            @if(old("account"))
                                                <option value="{{ old("account") }}" selected>{{ old("account_h") }}</option>
                                            @endif
                                        </select>
                                        
                                        @error('account')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endif
                                     <div class="col-sm-12 mt-2">
                                        <label class="form-label">Comments:</label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <textarea class="form-control" rows="2" name="comment" id="comment">{{ old("comment") }}</textarea>
                                        </div>
                                    </div>
                                  
                                </div>



                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right" style="text-align: right;">
                                        {{-- <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-cancel" >Cancel</a> --}}
                                        <button class="btn btn-info" id="cus-submit-btn">Save</button>
                                        {{-- <button type="button" class="btn btn-primary btn-lg btn-block" id="sslczPayBtn"
                                                token="{{ csrf_token() }}"
                                                postdata=""
                                                order="If you already have the transaction generated for current order"
                                                endpoint="{{ route('user_deposit.pay-via-ajax') }}"> Pay Now
                                        </button> --}}
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

    <script>
        $(document).ready(function() {
            // var obj = {};
            // obj.cus_name = $('#customer_name').val();
            // obj.cus_phone = $('#mobile').val();
            // obj.cus_email = $('#email').val();
            // obj.cus_addr1 = $('#address').val();
            // $('#sslczPayBtn').prop('postdata', obj);
            $(".fl-datepicker").flatpickr({
                defaultDate: new Date("{{ date('Y-m-d') }}"),
            });
            function select2Deposit(id,url,placeholder="",id1="id"){
                $(id).select2({
                    theme: "bootstrap-5",
                    placeholder:placeholder ,
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
                }).on('select2:select', function (e) {
                    var data = e.params.data;
                    $(id+"_h").val(data.text);
                });
            }
            function selectOption(id,d_name,d_id){
                if(d_name){
                    var data_option = new Option(d_name,d_id, true, true);
                    $('#'+id).append(data_option).trigger('change');
                }
            }
            select2Deposit('#land_plot','{{route('select2.property')}}','Select Land Plot');
            select2Deposit('#payment_method','{{route('select2.payment_methods')}}','Select Method');
            select2Deposit('#account','{{route('select2.balance_accounts')}}','Select Account','payment_method');
           
            // (function (window, document) {
            //     var loader = function () {
            //         var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            //         script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
            //         tag.parentNode.insertBefore(script, tag);
            //     };

            //     window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
            // })(window, document);
           
            // $(document).on('click','#cus-submit-btn',function(){
            //     event.preventDefault();

            //     if($('#p_date').val() == ""){
            //         alert('please select date');
            //         return;
            //     }
            //     if($('#payment_by').val() == ""){
            //         alert('please select receipt by');
            //         return;
            //     }
            //     if($('#from_fund').val() == ""){
            //         alert('please select Receipt fund');
            //         return;
            //     }
            //     var pr = 1
            //     var pr_status = 1;
            //     $('.a-payment').each(function(){
            //         if($(this).val() == ""){
            //             pr_status=0;
            //             alert('please row#'+pr+' select Reeeipt for');
            //             return;
            //         }
            //         pr++;
            //     });
            //     if(pr_status == 0){
            //         return ;
            //     }
            //     $('.a-amount').each(function(){
            //         if($(this).val() == "" || $(this).val() == 0){
            //             pr_status=0;
            //             alert('please row#'+pr+' fill amount');
            //             return;
            //         }
            //         pr++;
            //     });
            //     if(pr_status == 0){
            //         return ;
            //     }

            //     var data = new FormData();
            //     data.append( '_token',"{{ csrf_token() }}");
            //     data.append( 'v_type','Credit Voucher');
            //     data.append( 'receipt_date',$("#p_date").val());
            //     data.append( 'add_account',$("#add_account").val());
            //     data.append( 'payment_method',$("#payment_method").val());
            //     data.append( 'ref',$("#ref").val());
            //     data.append( 'description',$("#description").val());
            //     data.append( 'voucher_amount',$("#total_amount_input").val());

            //     $('.a-payment').each(function(){
            //         data.append( 'ledgers[]',$(this).val());
            //     });
            //     $('.a-amount').each(function(){
            //         data.append( 'amount[]',$(this).val());
            //     });
            //     $.ajax({
            //         url: "{{ route('account.voucher.store') }}",
            //         processData: false,
            //         contentType: false,
            //         method: 'POST',
            //         data:data,
            //         success: function(res) {
            //             console.log(res);
            //             if(res.status == "error"){
            //                 console.log(res);
            //             }
            //             else if(res.status == "yes"){

            //                 Swal.fire({
            //                     position: "top-end",
            //                     icon: "success",
            //                     title: res.msg,
            //                     showConfirmButton: false,
            //                     timer: 1500
            //                 });
            //                 window.location.reload();

            //             }else{
            //                 Swal.fire({
            //                     position: "top-end",
            //                     icon: "error",
            //                     title: res.msg,
            //                     showConfirmButton: false,
            //                     timer: 1500
            //                 });
            //             }
            //         },
            //         error:function(e){
            //             Swal.fire({
            //                 position: "top-end",
            //                 icon: "error",
            //                 title: e,
            //                 showConfirmButton: false,
            //                 timer: 1500
            //             });
            //         }
            //     });
            // });
        });

    </script>

@endsection
