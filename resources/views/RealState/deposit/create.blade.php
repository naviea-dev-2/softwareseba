
@extends('inc.master')
@section('head')
<title>Add Deposit</title>
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

                        <h6 class="br-section-label text-center mb-1">Add Deposit</h6>
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                            <form id="data-form-create" action="" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Payment Date: <span class="tx-danger">*</span></label>
                                        <input type="text" value="{{ old("payment_date") }}" placeholder="Payment Date" name="p_date" id="p_date" class="form-control fl-datepicker @error('payment_date') is-invalid @enderror"  required>
                                        @error('payment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Land Plot: <span class="tx-danger">*</span></label>
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
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Member: <span class="tx-danger">*</span></label>
                                        <input type="hidden" name="member_h" id="member_h" value="{{ old("member_h") }}"/>
                                        <select name="member" class="form-control @error('member') is-invalid @enderror" id="member">
                                            <option value=""> Select Member</option>
                                            @if(old("member"))
                                                <option value="{{ old("member") }}" selected>{{ old("member_h") }}</option>
                                            @endif
                                        </select>
                                        @error('member')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Deposit Amount</label>
                                        <input type="text" value="{{ old("deposit_amount") }}" placeholder="Deposit Amount" name="deposit_amount" id="deposit_amount" class="form-control @error('deposit_amount') is-invalid @enderror">
                                        @error('deposit_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="payment_method_status" value="{{ $payment_setting->status }}"/>
                                    @if($payment_setting->status == 0)
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Payment Method: <span class="tx-danger">*</span></label>
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
                                        <label class="form-control-label">Account: <span class="tx-danger">*</span></label>
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
            function select2Deposit(id,url,placeholder="",id1="id"){
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
            select2Deposit('#member','{{route('select2.member')}}','Select Member');
            select2Deposit('#payment_method','{{route('select2.payment_methods')}}','Select Method');
            select2Deposit('#account','{{route('select2.balance_accounts')}}','Select Account','payment_method');
           
           
        });

    </script>

@endsection
