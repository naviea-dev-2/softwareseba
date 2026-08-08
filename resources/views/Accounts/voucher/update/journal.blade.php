
@extends('inc.master')
@section('head')
<title>Voucher Entry (Journal)</title>
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
        <div class="br-section-wrapper data-update pt-4">
            <div class="row">
                <div class="col-md-10">
                    <h6 class="br-section-label text-center mb-1">Edit Voucher Entry (Journal)</h6>
                    <div id="create_errors"></div>

                    <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                        <form id="data-form-create" action="{{ route('account.voucher.update',$voucher->id) }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-sm-6 mt-2">
                                    <div class="">
                                        <label class="form-control-label">Journal Date: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input type="text" name="p_date" id="p_date" class="form-control fl-datepicker"  value="{{ $voucher->voucher_date }}" required>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <label class="form-control-label">Ref.: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input type="text" name="ref" id="ref" class="form-control" value="{{ $voucher->ref }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <label class="form-control-label">Description: <span class="tx-danger">*</span></label>
                                    <div class="mg-t-10 mg-sm-t-0">
                                        <textarea class="form-control" rows="4" name="description">{{ $voucher->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <table class="table table-bordered" >
                                        <thead class="thead-colored thead-light ">
                                            <tr>
                                                <th style="background-color: #e9ecef;">Account Ledger</th>
                                                <th style="background-color: #e9ecef;">Debit</th>
                                                <th style="background-color: #e9ecef;">Credit</th>
                                                <th style="background-color: #e9ecef;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="item_table">
                                            @foreach ($voucher->details as $detail)
                                            <tr>
                                                <td style="padding: 5px;">
                                                    <select name="old_ledgers[{{ $detail->id }}]" data-id="{{ $detail->id }}" id="p_ledger_{{ $detail->id }}" class="form-control a-old_payment check_ledger">
                                                        <option value=""> Select Ledger</option>
                                                    </select>
                                                </td>
                                                <td style="padding: 5px;">
                                                    <input type="number" name="dr_old_amount[]" data-id="{{ $detail->id }}" @if($detail->debit == 0) disabled @endif value="{{ $detail->debit }}" class="form-control dr-old_amount check_dr_amount">
                                                </td>
                                                <td style="padding: 5px;">
                                                    <input type="number" name="cr_old_amount[]"data-id="{{ $detail->id }}" @if($detail->credit == 0) disabled @endif value="{{ $detail->credit }}" class="form-control cr-old_amount check_cr_amount">
                                                </td>
                                                <td style="padding: 5px;vertical-align:middle;text-align:center;">
                                                    <div>
                                                        <button type="button" class="btn btn-success add_row btn-sm "><i class="bx bx-plus-circle"></i> </button>
                                                        <button type="button" data-id="{{ $detail->id }}" class="btn btn-danger old_del_row btn-sm "><i class="bx bx-trash"></i> </button>
                                                    </div>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="thead-colored thead-info ">
                                            <input type="hidden" id="total_amount_dr_input" name="total_amount_dr_input" value="{{ $voucher->voucher_amount }}">
                                            <input type="hidden" id="total_amount_cr_input" name="total_amount_cr_input" value="{{ $voucher->voucher_amount }}">
                                            <tr>
                                                <th style="background-color: #17a2b8;text-align: right;color:white;font-weight:bold;font-size:15px;">Total</th>
                                                <th style="background-color: #17a2b8;text-align: right;color:white;font-weight:bold;font-size:15px;" id="total_amount_dr">{{ $voucher->voucher_amount }}</th>
                                                <th style="background-color: #17a2b8;text-align: right;color:white;font-weight:bold;font-size:15px;" id="total_amount_cr">{{ $voucher->voucher_amount }}</th>
                                                <th style="background-color: #17a2b8;"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
            function select2Exam(id,url,placeholder=""){
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
            select2Exam('.a-payment','{{route('select2.ledger')}}?acc_c_id=[1,2,3,4,5,6,7,8,9,10,11,12]','Select Ledger');


            $(document).on('click', '.add_row', function(){
                addRow();
            });
            var row=1;
            function addRow(){
                console.log("sss");
                let jsValue = `<tr>
                                <td style="padding: 5px;">
                                    <select name="account[]" class="form-control a-payment" >
                                        <option value=""> Select Payment Type</option>
                                    </select>
                                </td>
                                <td style="padding: 5px;">
                                    <input type="number" name="dr_amount[]" value="0" class="form-control dr-amount">
                                </td>
                                 <td style="padding: 5px;">
                                    <input type="number" name="cr_amount[]" value="0" class="form-control cr-amount">
                                </td>
                                <td style="padding: 5px;vertical-align:middle;text-align:center;">
                                    <div>
                                        <button type="button" class="btn btn-success add_row btn-sm "><i class="bx bx-plus-circle"></i> </button>
                                        <button type="button" class="btn btn-danger del_row btn-sm "><i class="bx bx-trash"></i> </button>
                                    </div>

                                </td>
                            </tr>`;
                $('#item_table').append(jsValue);
                select2Exam('.a-payment','{{route('select2.ledger')}}?acc_c_id=[1,2,3,4,5,6,7,8,9,10,11,12]','Select Ledger');
            }
            $(document).on('click', '.del_row', function(){
                $(this).closest('tr').remove();
                if($('#item_table').find('tr').length == 0){
                    addRow();
                }
                calAmount();
            });
            $(document).on('keyup','.dr-amount',function(){
                calAmount();
            });
            $(document).on('keyup','.cr-amount',function(){
                calAmount();
            });
            function calAmount(){
                var dr_total_amount = 0;
                $('.dr-amount').each(function(){
                    if($(this).val() > 0){
                        dr_total_amount += parseFloat(this.value);
                        $(this).parent().parent().find('.cr-amount').attr('disabled',true);
                    }else{
                        if($(this).parent().parent().find('.cr-amount:disabled').length > 0){
                            $(this).parent().parent().find('.cr-amount').attr('disabled',false);
                        }

                    }
                });
                $('#total_amount_dr_input').val(dr_total_amount);
                $('#total_amount_dr').html(dr_total_amount);
                // $(this).parent().parent().find('.cr-amount').attr('disabled',true);

                var cr_total_amount = 0;
                $('.cr-amount').each(function(){

                    if($(this).val() > 0){
                        cr_total_amount += parseFloat(this.value);
                        $(this).parent().parent().find('.dr-amount').attr('disabled',true);
                    }else{
                        // console.log($(this).parent().parent().find('.dr-amount:disabled'));
                        if($(this).parent().parent().find('.dr-amount:disabled').length > 0){
                            $(this).parent().parent().find('.dr-amount').attr('disabled',false);
                        }
                    }
                });
                $('#total_amount_cr_input').val(cr_total_amount);
                $('#total_amount_cr').html(cr_total_amount);
                // $(this).parent().parent().find('.dr-amount').attr('disabled',true);
            }
            $(document).on('click','#cus-submit-btn',function(){
                event.preventDefault();

                if($('#p_date').val() == ""){
                    alert('please select date');
                    return;
                }


                var pr = 1;
                var pr_status = 1;
                $('.a-payment').each(function(){

                    if($(this).val() == ""){
                        pr_status=0;
                        alert('please row#'+pr+' select Account Ledger');
                        return;
                    }


                    pr++;
                });
                if(pr_status == 0){
                    return ;
                }
                var pr = 1;
                $('.dr-amount').each(function(){
                    if($(this).is(':disabled') == false){
                        if($(this).val() == "" || $(this).val() == 0){
                            pr_status=0;
                            alert('please row#'+pr+' fill dr amount');
                            return;
                        }
                    }
                    pr++;
                });
                if(pr_status == 0){
                    return ;
                }
                var pr = 1;
                $('.cr-amount').each(function(){
                    if($(this).is(':disabled') == false){
                        if($(this).val() == "" || $(this).val() == 0){
                            pr_status=0;
                            alert('please row#'+pr+' fill cr amount');
                            return;
                        }
                    }
                    pr++;
                });
                if(pr_status == 0){
                    return ;
                }
                if($('#total_amount_cr_input').val() != $('#total_amount_dr_input').val()){
                    alert('Debit & Credit is not equal');
                    return;
                }

                var data = new FormData();
                data.append( '_token',"{{ csrf_token() }}");
                data.append( 'v_type','Journal');
                data.append( 'p_date',$("#p_date").val());
                data.append( 'fund',$("#from_fund").val());
                data.append( 'ref',$("#ref").val());
                data.append( 'description',$("#description").val());
                data.append( 'total_amount_dr',$("#total_amount_dr_input").val());
                data.append( 'total_amount_cr',$("#total_amount_cr_input").val());
                $('.a-payment').each(function(){
                    data.append( 'ledgers[]',$(this).val());
                });
                $('.dr-amount').each(function(){
                    data.append( 'dr_amount[]',$(this).val());
                });
                $('.cr-amount').each(function(){
                    data.append( 'cr_amount[]',$(this).val());
                });
                $.ajax({
                    url: $('#data-form-create').attr('action'),
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

