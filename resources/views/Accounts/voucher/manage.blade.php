
@extends('inc.master')
@section('head')
<title>All Voucher</title>
<style>
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
    <div class="content-area">
        <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
            
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="font-size: 0.875rem; margin:0;">All Voucher</h5>
                <div class="d-flex" style="gap:10px;">
                   
                </div>
            </div>
                
        </div>
        <div class="row" style="padding-top: 24px;">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table id="data_table_list" class="table display responsive nowrap">
                            <thead>
                                <tr>
                                    <th class="wd-10p">ID</th>
                                    <th class="wd-15p">Type</th>
                                    <th class="wd-15p">Date</th>
                                    <th class="wd-15p">Voucher No.</th>
                                    <th class="wd-15p">Voucher Amount</th>
                                    <th class="wd-10p">Action</th>
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
    <div id="datamodalshow" class="modal fade">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body tx-center pd-y-20 pd-x-20">
                    <form id="data-form-delete" action="{{ route('account.voucher.delete') }}" method="post">
                        @csrf
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <i class="icon icon ion-ios-close-outline tx-60 tx-danger lh-1 mg-t-20 d-inline-block"></i>
                        <h4 class="tx-danger  tx-semibold mg-b-20 mt-2">Are you sure! you want to delete this?</h4>
                        <input type="hidden" name="examination_id" id="modal_data_id">
                        <button type="submit" class="btn-delete btn btn-danger mr-2 text-white tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium mg-b-20"> yes</button>
                        <button type="button" class="btn btn-success tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium mg-b-20" data-bs-dismiss="modal" aria-label="Close"> No</button>
                    </form>
                </div><!-- modal-body -->
            </div><!-- modal-content -->
        </div><!-- modal-dialog -->
    </div><!-- modal -->
    
@endsection



@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
    var datatable = $('#data_table_list').DataTable({
        // 'pageLength': 2,
        "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('account.voucher.ajax') }}",
            "dataType": "json",
            "type": "POST",
            data: function(data){
                data._token = "{{ csrf_token() }}";
            },
        },
        "columns": [
            { "data": "id"},
            { "data": "type"},
            { "data": "v_date"},
            { "data": "voucher_no"},
            { "data": "v_amount"},
            { "data": "options"},
        ],
        "columnDefs": [ {
        "targets": 5,
        "orderable": false
        } ]

    });
    $(document).ready(function() {

        $('#search-list-btn').on('click',function(){
            datatable.draw();
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
        function selectOption(id,d_name,d_id,type=0){
            if(d_name){
                var data_option = new Option(d_name,d_id, true, true);
                if(type == 1){
                    $(id).append(data_option).trigger('change');
                }else{
                    $('#'+id).append(data_option).trigger('change');
                }

            }
        }

        $(document).on('click', '.add_row', function(){
            addRow();
        });
        function addRow(){
            let jsValue = `<tr>
                            <td style="padding: 5px;">
                                <select name="ledgers[]" class="form-control a-payment" >
                                    <option value="">Select Ledger</option>
                                </select>
                            </td>
                            <td style="padding: 5px;">
                                <input type="number" name="amount[]" value="0" class="form-control a-amount check_amount">
                            </td>
                            <td style="padding: 5px;vertical-align:middle;text-align:center;">
                                <div>
                                    <button type="button" class="btn btn-success add_row btn-sm "><i class="bx bx-plus-circle"></i> </button>
                                    <button type="button" class="btn btn-danger del_row btn-sm "><i class="bx bx-trash"></i> </button>
                                </div>

                            </td>
                        </tr>`;
            $('#item_table').append(jsValue);
            select2Exam('.a-payment','{{route('select2.ledger')}}?acc_c_id=[1,3,4,5,6,7,10,11]','Select Ledger');
        }
        $(document).on('click', '.del_row', function(){
            $(this).closest('tr').remove();
            if($('#item_table').find('tr').length == 0){
                addRow();
            }
            calAmount();
        });
        $(document).on('click', '.old_del_row', function(){
            var data_id = $(this).attr('data-id');
            $('#item_table').append('<input type="hidden" class="del_ledger" name="del_ledgers[]" value="'+data_id+'">')
            $(this).closest('tr').remove();
            if($('#item_table').find('tr').length == 0){
                addRow();
            }
            calAmount();
        });
        $(document).on('keyup','.check_amount',function(){
            calAmount();
        });
        function calAmount(){
            var total_amount = 0;
            $('.check_amount').each(function(){
                if($(this).val() > 0){
                    total_amount += parseFloat(this.value);
                }
            });
            $('#total_amount_input').val(total_amount);
            $('#total_amount').html(total_amount);
        }


        $(document).on('click', '.del_row2', function(){
            $(this).closest('tr').remove();
            if($('#item_table').find('tr').length == 0){
                addRow();
            }
            calAmount2();
        });
        $(document).on('click', '.old_del_row2', function(){
            var data_id = $(this).attr('data-id');
            $('#item_table').append('<input type="hidden" class="del_ledger" name="del_ledgers[]" value="'+data_id+'">')
            $(this).closest('tr').remove();
            if($('#item_table').find('tr').length == 0){
                addRow();
            }
            calAmount2();
        });
        $(document).on('keyup','.check_dr_amount',function(){
            calAmount2();
        });
        $(document).on('keyup','.check_cr_amount',function(){
            calAmount2();
        });
        function calAmount2(){
            var dr_total_amount = 0;
            $('.check_dr_amount').each(function(){
                if($(this).val() > 0){
                    dr_total_amount += parseFloat(this.value);
                    $(this).parent().parent().find('.check_cr_amount').attr('disabled',true);
                }else{
                    if($(this).parent().parent().find('.check_cr_amount:disabled').length > 0){
                        $(this).parent().parent().find('.check_cr_amount').attr('disabled',false);
                    }

                }
            });
            $('#total_amount_dr_input').val(dr_total_amount);
            $('#total_amount_dr').html(dr_total_amount);
            // $(this).parent().parent().find('.cr-amount').attr('disabled',true);

            var cr_total_amount = 0;
            $('.check_cr_amount').each(function(){

                if($(this).val() > 0){
                    cr_total_amount += parseFloat(this.value);
                    $(this).parent().parent().find('.check_dr_amount').attr('disabled',true);
                }else{
                    // console.log($(this).parent().parent().find('.dr-amount:disabled'));
                    if($(this).parent().parent().find('.check_dr_amountt:disabled').length > 0){
                        $(this).parent().parent().find('.check_dr_amount').attr('disabled',false);
                    }
                }
            });
            $('#total_amount_cr_input').val(cr_total_amount);
            $('#total_amount_cr').html(cr_total_amount);
            // $(this).parent().parent().find('.dr-amount').attr('disabled',true);
        }
        var voucher_type = "Payment";
        $(document).on('click','.cus_data_edit',function(e){
            e.preventDefault();
            var url  = $(this).attr('href');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type : 'GET',
                url : url,
                success : function(res){
                    console.log(res);
                    voucher_type = res.voucher.v_type;
                    $('.data-edit-res').html(res.html);
                    if(voucher_type == "Debit Voucher"){
                        select2Exam('.a-old_payment','{{route('select2.ledger')}}?acc_c_id=[5,6,7]','Select Ledger');
                        select2Exam('#payment_method','{{route('select2.payment_methods')}}','Select Method');
                        select2Exam('#add_account','{{route('select2.balance_accounts')}}','Select Account','payment_method');
                        selectOption('payment_method',res.fund_name,res.voucher.fund_id);
                        selectOption('add_account',res.v_ledger_name,res.voucher.voucher_by);
                        if(res.details){
                            res.details.forEach(element => {
                                console.log(element.ledger);
                                selectOption('p_ledger_'+element.id,element.ledger.title,element.ledger_id);
                            });
                        }
                    }else if(voucher_type == "Credit Voucher"){
                        select2Exam('.a-old_payment','{{route('select2.ledger')}}?acc_c_id=[4]','Select Ledger');
                        select2Exam('#payment_method','{{route('select2.payment_methods')}}','Select Method');
                        select2Exam('#add_account','{{route('select2.balance_accounts')}}','Select Account','payment_method');
                        selectOption('payment_method',res.fund_name,res.voucher.fund_id);
                        selectOption('add_account',res.v_ledger_name,res.voucher.voucher_by);
                        if(res.details){
                            res.details.forEach(element => {
                                console.log(element);
                                selectOption('p_ledger_'+element.id,element.ledger.title,element.ledger_id);
                            });
                        }
                    }else if(voucher_type == "Contra"){
                        select2Exam('#trans_from','{{route('select2.ledger')}}?acc_c_id=[1]','Select Trans From');
                        select2Exam('#trans_to','{{route('select2.ledger')}}?acc_c_id=[1]','Select Trans To');
                        selectOption('trans_from',res.from_name,res.voucher.trans_from);
                        selectOption('trans_to',res.to_name,res.voucher.trans_to);
                    }
                    else{
                        select2Exam('.a-payment','{{route('select2.ledger')}}?acc_c_id=[1,2,3,4,5,6,7,8,9,10,11,12]','Select Ledger');
                        if(res.details){
                            res.details.forEach(element => {
                                console.log(element);
                                selectOption('p_ledger_'+element.id,element.ledger.title,element.ledger_id);
                            });
                        }
                    }



                    $(".fl-datepicker").flatpickr();

                    $('.data-list').hide();
                    $('.data-update').show();
                },
                error:function(e){
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: e.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
        $(document).on('click','.del_hr_data',function(e){
            e.preventDefault();
            let id = $(this).attr('data-id');
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
                    var data = new FormData();
                    data.append( '_token',"{{ csrf_token() }}");
                    data.append( 'v_id',id);
                    $.ajax({
                        url: "{{ route('account.voucher.delete') }}",
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
                                datatable.draw();

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
                }
            });
            // $('#datamodalshow').modal('show');

        });
        $(document).on('click','#cus-submit-btn',function(){
            event.preventDefault();

            if($('#p_date').val() == ""){
                alert('please select date');
                return;
            }
            console.log(voucher_type);

            var pr = 1
            var pr_status = 1;

            if(voucher_type != "Contra")
            {
                if(voucher_type != "Journal"){
                    if($('#payment_by').val() == ""){
                        alert('please select payment by');
                        return;
                    }
                }

                if($('#from_fund').val() == ""){
                    alert('please select fund from');
                    return;
                }
                $('.check_ledger').each(function(){
                    console.log($(this).val());
                    if($(this).val() == ""){
                        console.log("ss");
                        pr_status=0;
                        alert('please row#'+pr+' select Payment for');
                        return;
                    }
                    pr++;
                });
                if(pr_status == 0){
                    return ;
                }

                if(voucher_type == "Journal"){
                    var pr = 1;
                    $('.check_dr_amount').each(function(){
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
                    $('.check_cr_amount').each(function(){
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
                }else{
                    var pr = 1
                    $('.check_amount').each(function(){
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
                }
            }else{
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
            }
            var data = new FormData();
            data.append( '_token',"{{ csrf_token() }}");
            data.append( 'v_type',voucher_type);
            if(voucher_type == "Contra"){
                data.append( 'trans_date',$("#p_date").val());
                data.append( 'trans_from',$("#trans_from").val());
                data.append( 'trans_to',$("#trans_to").val());
                data.append( 'trans_amount',$("#trans_amount").val());
                data.append( 'ref',$("#ref").val());
                data.append( 'description',$("#description").val());
            }else{
                data.append( 'p_date',$("#p_date").val());
                data.append( 'add_account',$("#add_account").val());
                data.append( 'payment_method',$("#payment_method").val());
                data.append( 'ref',$("#ref").val());
                data.append( 'description',$("#description").val());
                data.append( 'voucher_amount',$("#total_amount_input").val());

                data.append( 'total_amount_dr',$("#total_amount_dr_input").val());
                data.append( 'total_amount_cr',$("#total_amount_cr_input").val());
                $('.a-old_payment').each(function(){
                    data.append( 'old_ledgers['+$(this).attr('data-id')+']',$(this).val());
                });
                $('.del_ledger').each(function(){
                    data.append( 'del_ledgers[]',$(this).val());
                });
                if(voucher_type == "Journal"){
                    $('.dr-old_amount').each(function(){
                        data.append( 'dr_old_amount['+$(this).attr('data-id')+']',$(this).val());
                    });
                    $('.cr-old_amount').each(function(){
                        data.append( 'cr_old_amount['+$(this).attr('data-id')+']',$(this).val());
                    });

                    $('.dr-amount').each(function(){
                        data.append( 'dr_amount[]',$(this).val());
                    });
                    $('.cr-amount').each(function(){
                        data.append( 'cr_amount[]',$(this).val());
                    });
                }else{
                    $('.a-old_amount').each(function(){
                        data.append( 'old_amount['+$(this).attr('data-id')+']',$(this).val());
                    });
                    $('.a-amount').each(function(){
                        data.append( 'amount[]',$(this).val());
                    });
                }
                $('.a-payment').each(function(){
                    data.append( 'ledgers[]',$(this).val());
                });
            }

            var url = $('#data-form-create').attr('action');
            $.ajax({
                url: url,
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
                        $('.data-edit-res').html('');

                        $('.data-update').hide();
                        $('.data-list').show();
                        datatable.draw();

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

        $(document).on('click','.download-voucher',function(){
            event.preventDefault();
            var data = new FormData();
            data.append( '_token',"{{ csrf_token() }}");
            data.append( 'voucher_id',$(this).attr('value'));
            var arg = this;
            $.ajax({
                url: "{{ route('account.voucher.download') }}",
                method: "POST",
                cache: !1,
                processData: !1,
                contentType: !1,
                data:data,
                xhrFields: { responseType: "blob" },
                beforeSend: function () {
                    $(arg).html('<i class="fas fa-spinner fa-spin"></i> <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" style="color: white; font-size: 1.5em;"><path d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4zm-132.9 88.7L299.3 420.7c-6.2 6.2-16.4 6.2-22.6 0L171.3 315.3c-10.1-10.1-2.9-27.3 11.3-27.3H248V176c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16v112h65.4c14.2 0 21.4 17.2 11.3 27.3z"></path></svg>');
                },
                success: function (res) {

                    console.log(res);
                    var t = document.createElement("a"),
                    r = window.URL.createObjectURL(res);
                    (t.href = r),
                        (t.download = "voucher.pdf"),
                        document.body.append(t),
                        t.click(),
                        t.remove(),
                        window.URL.revokeObjectURL(r);


                },
                error: function (e) {
                    console.log(e);
                    // Botble.handleError(e);
                },
                complete: function () {
                    setTimeout(function () {
                        $(arg).html('<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" style="color: white; font-size: 1.5em;"><path d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4zm-132.9 88.7L299.3 420.7c-6.2 6.2-16.4 6.2-22.6 0L171.3 315.3c-10.1-10.1-2.9-27.3 11.3-27.3H248V176c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16v112h65.4c14.2 0 21.4 17.2 11.3 27.3z"></path></svg>');
                    }, 1000);
                },
            });
        });
    });
</script>

@endsection
