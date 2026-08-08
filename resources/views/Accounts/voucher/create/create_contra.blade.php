
@extends('inc.master')
@section('head')
<title>Voucher Entry (Contra)</title>
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

                        <h6 class="br-section-label text-center mb-1">Voucher Entry (Contra)</h6>
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                            <form id="data-form-create" action="" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Transfer Date: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input type="text" name="p_date" id="p_date" class="form-control fl-datepicker"  required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Transfer From: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <select name="trans_from" id="trans_from" class="form-control">
                                                <option value="">Select Transfer From</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Transfer To: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <select name="trans_to" id="trans_to" class="form-control">
                                                <option value="">Select Transfer To</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Transfer Amount: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input type="number" name="trans_amount" id="trans_amount" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 mt-2">
                                        <label class="form-control-label">Ref.</label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input type="text" name="ref" id="ref" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-2">
                                        <label class="form-control-label">Description:</label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <textarea class="form-control" rows="4" name="description" id="description"></textarea>
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
            select2Exam('#trans_from','{{route('select2.ledger')}}?acc_c_id=[1]','Select Trans From');
            select2Exam('#trans_to','{{route('select2.ledger')}}?acc_c_id=[1]','Select Trans To');
            $(document).on('click','#cus-submit-btn',function(){
                event.preventDefault();

                if($('#p_date').val() == ""){
                    alert('please select date');
                    return;
                }

                if($('#trans_from').val() == ""){
                    alert('please select Transfer From');
                    return;
                }
                if($('#trans_to').val() == ""){
                    alert('please select Transfer From');
                    return;
                }
                if($('#trans_amount').val() == "" || $('#trans_amount').val() == ""){
                    alert('please Transfer Amount should be grater than zero');
                    return;
                }

                var data = new FormData();
                data.append( '_token',"{{ csrf_token() }}");
                data.append( 'v_type','Contra');
                data.append( 'trans_date',$("#p_date").val());
                data.append( 'trans_from',$("#trans_from").val());
                data.append( 'trans_to',$("#trans_to").val());
                data.append( 'trans_amount',$("#trans_amount").val());
                data.append( 'ref',$("#ref").val());
                data.append( 'description',$("#description").val());

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
