@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Quotation</title>
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
        <div class="content-area" >
            <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12 ">
                        <div class="row report-title">
                            <h4 class=""><b>Quotation Manage</b></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one my-1">
                    <div class="col-md-12 col-lg-12 col-sm-12">

                        <!-- start modal -->
                        @if(can_p('quotation.create'))
                        <!-- Button trigger modal -->
                        <a class="btn btn-primary " href="{{ route('quotation.create') }}"><i class="bx bx-plus"></i> New Quotation</a>
                        @endif
                        @php

                            $p_print = can_p('quotation.print');
                        @endphp

                        @include('Inventory.quotation.show-quotation-details')

                        <br/><br/>
                        <table id="dataTable" class="purchase-list table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Grand Total</th>
                                {{-- <th>Paid</th>
                                <th>Due</th> --}}
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
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<script>
    $('#dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            "url": "{{ route('quotation.ajax') }}",
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
            { "data": "status"},
            { "data": "total"},

            { "data": "options"},
        ],
        "columnDefs": [ {
          "targets": 6,
          "orderable": false
        } ]

    });
    var public_key = "pk_test_51NywwbGC0MqY8SyQxWc6N78yUS69yZSujr0EAO2MrhMPkC0XINjbB3sicAgLkNSxU5YQr4X9h8J96DA2hlKOKSLq00NO7V3hsS";
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
            url: "{{url('quotation-detail') }}/"+id,
            method: 'GET',

            success: function(data) {
                console.log(data);
                $('#view-ajax-data').html(data);
                $('#purchase-details').modal('show');
            }
        });
    });
    $(document).on('click','.add-payment',function(){
        var rowindex = $(this).closest('tr').index();

        var purchase_id = $(this).data('id').toString();
        var balance = $('table.purchase-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(8)').text();
        $("#cheque").hide();
        $(".card-element").hide();
        $('select[name="paid_by_id"]').val(1);
        $('input[name="amount"]').val(balance);
        $('input[name="balance"]').val(balance);
        $('input[name="paying_amount"]').val(balance);
        $('input[name="purchase_id"]').val(purchase_id);
         console.log( $('table.purchase-list tbody tr:nth-child(' + (rowindex + 1) + ')'));
    })
    $('select[name="paid_by_id"]').on("change", function() {
        var id = $('select[name="paid_by_id"]').val();
        $('input[name="cheque_no"]').attr('required', false);
        $(".payment-form").off("submit");
        if (id == 3) {
            $.getScript( "public/stripe/index.js" );
            $(".card-element").show();
            $("#cheque").hide();
        } else if (id == 4) {
            $("#cheque").show();
            $(".card-element").hide();
            $('input[name="cheque_no"]').attr('required', true);
        } else {
            $(".card-element").hide();
            $("#cheque").hide();
        }
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
</script>
@endsection
