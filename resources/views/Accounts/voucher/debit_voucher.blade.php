@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Debit Voucher</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection
@section('content')

    <section class="section  mt-5">
        <div class="section-body">
            <div class="row">
                <div class="col-md-12" id="edit-handler">
                    <div class="card">
                        <div class="card-header">
                            <h4>Debit Voucher</h4>
                            <div class="card-header-action">
                                <a href="{{URL::to('transaction')}}" class="btn btn-info">Transaction List</a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session()->has('insuficient_balance'))
                            <div class="alert alert-danger">
                                <strong style="font-size: 18px">{{session()->get('insuficient_balance')}}</strong>
                            </div>
                            @endif

                            @if(session()->has('success'))
                            <div class="alert alert-success">
                                <strong style="font-size: 18px">{{session()->get('success')}}</strong>
                            </div>
                            @endif


                            <?php
	                            $currentDateTime = date('d/m/Y');
	                            echo "Date: ".''."<b>".$currentDateTime;"</b>";
                                ?>
                                <hr>
                                <form action="{{route('transaction.store')}}" method="post">
                                    @csrf


                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12 col-md-12 col-lg-12">
                                                    Voucher No#</label>
                                                <div class="col-sm-12 col-md-12">
                                                    <input type="text" class="form-control" name="voucher_no" value="{{$memo_no?$memo_no:''}}" id="voucher_id">
                                                    <span>e.g. DV0000001</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12 col-md-12 col-lg-12">
                                                    Received From</label>
                                                <div class="col-sm-12 col-md-12">
                                                    <input type="text" class="form-control" name="received_from" value="" id="received_from">
                                                </div>
                                            </div>
                                        </div>
                                         <div class="col-xl-12 col-md-12">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12 col-md-12 col-lg-12">
                                                    Credit hand For Transaction</label>
                                                <div class="col-sm-12 col-md-12">
                                                    <select class="form-control" name="account_head_id">
                                                    	@foreach($account_heads as $item)
                                                    	<option value="{{$item->id}}">{{$item->title}}</option>
                                                    	@endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    <b>Payment Method <span class="text-danger">*</span></b>
                                                </label>
                                                <div class="col-sm-12">
                                                    <select class="form-control" name="payment_method_id" id="method_id"
                                                        onchange="getBalanceAccountHandler(this.value)" required>
                                                        <option value="">Selcet Payment Method</option>
                                                        @foreach($methods as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    <b> Balance Account </b>
                                                </label>
                                                <div class="col-sm-12 col-md-9">
                                                    <select class="form-control" name="balance_account_id" id="account_number"
                                                            onchange="getCurrentBalance(this.value)">
                                                       <!--  <option value="0">Select Account</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{$account->id}}">
                                                                 @php echo $account->account_name.'('.$account->branch_name.')'; @endphp
                                                            </option>
                                                        @endforeach -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    <b> Current Balance</b>
                                                </label>
                                                <div class="col-sm-12 col-md-9">
                                                    <input type="text" class="form-control" name="current_balance"
                                                           id="current_balance" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    Date</label>
                                                <div class="col-sm-12 col-md-10">
                                                    <input type="date" class="form-control datepicker" name="date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    Note</label>
                                                <div class="col-sm-12 col-md-12">
                                                    <textarea class="form-control" name="note"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">

                                                </label>
                                                <div class="col-sm-12 col-md-12 table-responsive">
                                                    <table class="table" id="item_table">
                                                        <thead>
                                                        <tr>
                                                            <th>Account Name</th>
                                                            <th>A/C. Code No.</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td width="461px">
		                                                    <select class="form-control" name="account_sub_head_id[]">
                                                                <option value="" selected="">Select</option>
		                                                    	@foreach($second_level as $item)
		                                                    	<option value="{{$item->id}}">{{$item->account_title}}</option>
		                                                    	@endforeach
		                                                    </select>
                                                            </td>
                                                            <td><input type="number" name="ac_code[]" class="form-control"></td>
                                                            <td>
                                                                <input type="number" name="amount[]" onkeyup="updateBuyingPrice()" min="1"  style="text-align:right;" required="" class="form-control">
                                                            </td>
                                                            <td>
                                                                <button id="add_row"  type="button" class="btn btn-success add_row btn-sm "><i class="bx bx-plus-circle"></i> </button>
                                                            </td>
                                                        </tr>
                                                        <tr></tr>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-right">Total Amount</th>
                                                            <th> <input type="number" name="total" id="total" class="form-control" style="text-align:right;" readonly=""> </th>
                                                            <th></th>
                                                        </tr>
                                                        <!-- <tr>
                                                        	<th></th>
                                                            <th class="text-right">Pay Amount</th>
                                                            <th> <input type="number" name="pay_amount" id="pay_amount" onkeyup="PaymentHandler(this.value)" style="text-align:right;" class="form-control"> </th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                        	<th></th>
                                                            <th class="text-right">Due Amount</th>
                                                            <th> <input type="number" name="due_amount" id="due_amount" style="text-align:right;" class="form-control"> </th>
                                                            <th></th>
                                                        </tr> -->
                                                        <tr>
                                                            <th class="text-right"></th>
                                                            <th class="text-right"></th>
                                                            <th  class="text-right">
                                                                <button type="submit" class="btn btn-success">Save</button>
                                                            </th>
                                                            <th>

                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{{--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
@endsection
@section('script')
<script>



  $( document ).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
  });




  $(document).ready(function() {
      $('.js-example-basic-multiple').select2({
          placeholder: 'Select '
      });
  });



   // getBalanceAccountHandler
  var token =  $("input[name=_token]").val();
  function getBalanceAccountHandler(val){
    var datastr = "method_id=" + val  + "&token="+token;
    $.ajax({
      type: "post",
      url: "<?php echo route('admin/get-balance-account');?>",
      data:datastr,
      cache:false,
      beforeSend: function() {
          // setting a timeout
      },
      success:function (data) {
        $('#account_number').html(data);

      },
      error: function (jqXHR, status, err) {
        alert(status);
        console.log(err);
      },
      complete: function () {
        // alert("Complete");
      }
    });
  }


   // getCurrentBalance
  var token =  $("input[name=_token]").val();
  function getCurrentBalance(val){
    var datastr = "account_number=" + val  + "&token="+token;
    $.ajax({
      type: "post",
      url: "<?php echo route('admin/get-balance');?>",
      data:datastr,
      cache:false,
      beforeSend: function() {
          // setting a timeout
      },
      success:function (data) {

        $('#current_balance').val(data.balance);

      },
      error: function (jqXHR, status, err) {
        alert(status);
        console.log(err);
      },
      complete: function () {
        // alert("Complete");
      }
    });
  }


    $(document).on('click', '.add_row', function(){
        var html = '';
        html += '<tr>'
            +'<td width="461px">'
            +'<select class="form-control selectric" name="account_sub_head_id[]"'
            +'  id="category_id" onchange="getUtility(this.value)">'
             +' <option value="" selected>Select</option>'
            +' @foreach ($second_level as $data)'
             +' <option value="{{$data->id}}">{{$data->account_title}}</option>'
           +'  @endforeach'
           +'  </select>'
           +' </td>'
            +'<td> <input type="number" name="ac_code[]" style="text-align:right;"  min="1" onkeyup="updateBuyingPrice()" class="form-control"></td>'
         +'<td> <input type="number" name="amount[]"  required="" style="text-align:right;"  min="1" onkeyup="updateBuyingPrice()" class="form-control"></td>'
         +'<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="bx bx-minus-circle"></span></button></td></tr>';
        $('#item_table').prepend(html);
    });



    $(document).on('click', '.remove', function(){
        $(this).closest('tr').remove();
        updateTotal();
        updateQty();
    });



    function updateBuyingPrice() {
        var total = 0;//

        var qty = $('input[name="amount[]"]');
        var values = [];
        for(var i = 0; i < qty.length; ++i) {
            values.push(parseFloat(qty[i].value));
        }
        total = values.reduce(function(previousValue, currentValue, index, array){
            return previousValue + currentValue;
        });
        document.getElementById("total").value = total;
    }


    function PaymentHandler(val){
        var total = document.getElementById("total").value;
        var due = total-val;
        if(due>0){
            document.getElementById("due_amount").value = due;
        }else{
              document.getElementById("due_amount").value = 0;
        }
    }


</script>

@endsection

