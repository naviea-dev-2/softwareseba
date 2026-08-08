@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Transaction</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection
@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Manage Transaction Information</h4>
                        </div>

                            <div class="card-body">
                                <!-- <form action="" method="get">
                                    @csrf
                                     <div class="row">
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    <b> Balance Account <i class="text-danger">*</i> </b>
                                                </label>
                                                <div class="col-sm-12 col-md-9">
                                                    <select class="form-control" name="account_id" id="account_id">
                                                        <option value="">All</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{$account->id}}">
                                                                @php echo $account->account_name.'('.$account->branch_name.')'; @endphp
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    <b> From <i class="text-danger">*</i> </b>
                                                </label>
                                                <div class="col-sm-12 col-md-9">
                                                    <input type="date" class="form-control" name="from_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    <b> TO <i class="text-danger">*</i> </b>
                                                </label>
                                                <div class="col-sm-12 col-md-9">
                                                    <input type="date" class="form-control" name="to_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    <p></p>
                                                </label>
                                                <div class="col-sm-12 col-md-9">
                                                    <input type="submit" class="btn btn-success" value="Filter">
                                                </div>
                                            </div>
                                        </div>

                                </div>
                                </form> -->


                                     <div class="table-responsive">

                                    <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                                        <thead>
                                        <tr class="text-center">
                                            <th>Voucher No</th>
                                            <th>Received From</th>
                                            <th>Acc.</th>
                                            <th>Acc. No</th>
                                            <th>Note</th>
                                            <!-- <th>Old Balance</th> -->
                                            <th>Debit </th>
                                            <th>Credit </th>
                                            <th> Pay </th>
                                            <th> Due </th>
                                            <!-- <th> Discount </th>
                                            <th> Vat (%) </th> -->
                                            <th>Current Balance </th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <!-- <th></th> -->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $i=1;
                                            $total_debit = 0;
                                            $total_credit = 0;
                                        $total_due = 0;
                                        $total_pay = 0;
                                        $total_discount = 0;
                                        $total_vat = 0;
                                        @endphp
                                        @foreach($datas as $data)
                                            <tr class="text-center">
                                                <td>{{$data->voucher_no}}</td>
                                                <td>{{$data->received_from}}</td>
                                                <td>{{$data->balance_account->account_name ?? ''}}</td>
                                                <td>{{$data->balance_account->account_number ?? ''}}</td>
                                                <td>{{$data->note}}</td>
                                                <!-- <td class="text-center">{{$data->old_balance}}</td> -->
                                                <td class="text-center">
                                                    @if($data->transactions_type == "Debit")
                                                        {{$data->total?$data->total:'-'}}

                                                        @php
                                                             $total_debit = $total_debit+$data->total;
                                                        @endphp

                                                    @else
                                                        {{__('-')}}
                                                    @endif

                                                </td>
                                                <td class="text-center">
                                                    @if($data->transactions_type == "Credit")
                                                        {{$data->total?$data->total:'-'}}
                                                        @php
                                                            $total_credit = $total_credit+$data->total;
                                                        @endphp
                                                    @else
                                                        {{__('-')}}
                                                     @endif
                                                </td>
                                                <td class="text-center">

                                                    {{$data->pay_amount?$data->pay_amount:'-'}}
                                                    @php
                                                        $total_pay = $total_pay+$data->pay_amount;
                                                    @endphp

                                                </td>

                                                <td class="text-center">

                                                    {{$data->due_amount?$data->due_amount:'-'}}
                                                    @php
                                                        $total_due = $total_due+$data->due_amount;
                                                    @endphp

                                                </td>

                                                <!-- <td class="text-center">
                                                    {{$data->discount?$data->discount:0}}
                                                    @php $total_discount = $total_discount + $data->discount?$data->discount:0; @endphp
                                                </td>
                                                <td class="text-center">
                                                    {{$data->vat?$data->vat:0}}
                                                    @php
                                                        $vat = $data->vat?$data->vat:0;
                                                            $total_vat = $total_vat + ($vat/100)*$total_credit; @endphp
                                                </td> -->
                                                <td class="text-center">{{$data->current_balance}}</td>
                                                <td>{{$data->date}}</td>
                                                <td>@php
                                                    if($data->status == 1){
                                                    echo  "<div class='badge badge-success badge-shadow'>Approved</div>";
                                                    }else{
                                                    echo  "<div class='badge badge-danger badge-shadow'>Pending</div>";
                                                    }
                                                    @endphp
                                                </td>

                                                <td>

                                                    <a href="{{URL::to('admin/transaction/'.$data->id.'/edit')}}"><button type="submit" class="btn btn-primary btn-sm mx-1"><i class="bx bx-edit"></i>
                                                        </button></a>
                                                </td>
                                                <td>
                                                    <a type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="ViewTransac({{$data->id}})"
                                                    ><i class="bx bx-show"></i></a>
                                                </td>
                                            </tr>
                                            @php $i++; @endphp
                                        @endforeach

                                        </tbody>

                                        <tfoot>
                                            <tr class="text-center">
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th class="text-right"> Total - </th>
                                                <th>  {{$total_debit}} /-</th>
                                                <th>  {{$total_credit}} /- </th>
                                                <th>  {{$total_pay}} /-</th>
                                                <th>  {{$total_due}} /-</th>
                                                <!-- <th> {{$total_discount}} /-</th>
                                                <th> {{$total_vat}} /- </th> -->
                                                <th> </th>
                                            </tr>
                                        </tfoot>

                                    </table>

                                </div>

                            </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Large modal -->
<!--     <div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="CloseModelHandler()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id="ViewTransaction">

                        Content goes here....

                    </div>
                    <button onclick="PrintInvoice()" class="btn btn-info">Print</button>

                </div>
            </div>
        </div>
    </div> -->


    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title" id="myLargeModalLabel">Modal title</h5> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="CloseModelHandler()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id="ViewTransac">

                        Content goes here....

                    </div>
                    <button onclick="PrintInvoice()" class="btn btn-info">Print</button>

                </div>
            </div>
        </div>
    </div>

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
@endsection
@section('script')
<script type="text/javascript">


  $( document ).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
  });





    var token =  $("input[name=_token]").val();
    function ViewTransac(id){
        var datastr = "id=" + id + "&token=" + token;
        $.ajax({
            type: "post",
            url: "<?php echo route('admin/view-transac-modal'); ?>",
            data: datastr,
            cache: false,
            success: function(data) {
                // console.log(data.trans);
                // console.log(data.footer);
                // console.log(data.header);
                // console.log(data.str);

                $('#ViewTransac').html(data);
            },
            error: function(jqXHR, status, err) {
                alert(status);
                console.log(err);
            },
            complete: function() {
                // alert("Complete");
            }
        });
    }

        function PrintInvoice(){
            var printContents = document.getElementById("ViewTransac").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function CloseModelHandler(){
            // Get the modal
            var modal = document.getElementById("myModal");
            modal.classList.remove("show");
            location.reload();
        }



</script>
@endsection
