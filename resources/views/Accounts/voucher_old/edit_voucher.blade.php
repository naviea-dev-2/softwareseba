@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Edit Voucher</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12" id="edit-handler">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Voucher</h4>
                            <div class="card-header-action">
                                <a href="{{URL::to('admin/transaction')}}" class="btn btn-info">Transaction List</a>
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


                            @if(session()->has('success'))
                            <div class="alert alert-success">
                                <strong style="font-size: 18px">{{session()->get('success')}}</strong>
                            </div>
                            @endif



                            @if(session()->has('status_apprvoed'))
                            <div class="alert alert-warning">
                                <strong style="font-size: 18px">{{session()->get('status_apprvoed')}}</strong>
                            </div>
                            @endif


                            <?php
                                $currentDateTime = date('d/m/Y');
                                echo "Date: ".''."<b>".$currentDateTime;"</b>";
                                ?>
                                <hr>
                                <form action="{{route('transaction.update',$data->id)}}" method="post" enctype="multipart/form-data">
                                @method('patch')
                                @csrf

                                    <input type="text" name="transactions_type" value="{{$data->transactions_type}}">
                                    <input type="text" name="type" value="{{$data->type}}">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12 col-md-12 col-lg-12">
                                                    Voucher No#</label>
                                                <div class="col-sm-12 col-md-12">
                                                    <input type="text" class="form-control" name="voucher_no" value="{{$data->voucher_no}}" id="voucher_id" readonly="">
                                                    <span>e.g. CV0000001</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12 col-md-12 col-lg-12">
                                                    Received From</label>
                                                <div class="col-sm-12 col-md-12">
                                                    <input type="text" class="form-control" name="received_from"  id="received_from" value="{{$data->received_from}}">
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
                                                        <option value="{{$item->id}}" @php if ($data['account_head_id'] == $item->id) { echo "selected"; } @endphp>{{$item->title}}</option>
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
                                                            <option value="{{$item->id}}" @php if ($data['payment_method_id'] == $item->id) { echo "selected"; } @endphp>{{$item->name}}</option>
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
                                                        <option value="0">Select Account</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{$account->id}}"  @php if ($data['balance_account_id'] == $account->id) { echo "selected"; } @endphp>
                                                                {{ $account->account_name}} - {{$account->branch_name}} - {{$account->account_number}}
                                                            </option>
                                                        @endforeach
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
                                                           id="current_balance" value="{{$current_balance->balance}}" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    Date</label>
                                                <div class="col-sm-12 col-md-10">
                                                    <input type="date" class="form-control datepicker" name="date" value="{{$data->date}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    Note</label>
                                                <div class="col-sm-12 col-md-12">
                                                    <textarea class="form-control" name="note">{{$data->note}}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        @if($data->type == 'debit_voucher')
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-12">
                                                    Status</label>
                                                    <select class="custom-select" name="status">
                                                        <option value="1" @php if ($data['status'] == 1) { echo "selected"; } @endphp>Active</option>
                                                        <option value="0" @php if ($data['status'] == 0) { echo "selected"; } @endphp>Inactive</option>
                                                    </select>
                                            </div>
                                        </div>
                                        @endif

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
                                                            @foreach($dataItems as $dataItem)
                                                        <tr>
                                                            <td width="461px">
                                                            <select class="form-control" name="account_sub_head_id[]">
                                                                <option value="" selected="">Select</option>
                                                                @foreach($second_level as $item)
                                                                <option value="{{$item->id}}"
                                                            <option value="{{$account->id}}"  @php if ($dataItem['account_sub_head_id'] == $item->id) { echo "selected"; } @endphp>{{$item->account_title}}</option>
                                                                @endforeach
                                                            </select>
                                                            </td>
                                                            <td><input type="number" name="ac_code[]" class="form-control" value="{{$dataItem->ac_code}}"></td>
                                                            <td>
                                                                <input type="number" name="amount[]" onkeyup="updateBuyingPrice()" min="1"  style="text-align:right;" required="" class="form-control" value="{{$dataItem->amount}}">
                                                            </td>
                                                            <td>
                                                                <button id="add_row"  type="button" class="btn btn-success add_row btn-sm "><i class="bx bx-plus-circle"></i> </button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        <tr></tr>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-right">Total Amount</th>
                                                            <th> <input type="number" name="total" id="total" class="form-control" style="text-align:right;" readonly="" value="{{$data->total}}"> </th>
                                                            <th></th>
                                                        </tr>

                                                        @if($data->type == 'credit_voucher')
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-right">Pay Amount</th>
                                                            <th> <input type="number" name="pay_amount" id="pay_amount" onkeyup="PaymentHandler(this.value)" style="text-align:right;" class="form-control" value="{{$data->pay_amount}}"> </th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-right">Due Amount</th>
                                                            <th> <input type="number" name="due_amount" id="due_amount" style="text-align:right;" class="form-control" value="{{$data->due_amount}}"> </th>
                                                            <th></th>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th class="text-right"></th>
                                                            <th class="text-right"></th>
                                                            <th  class="text-right">
                                                                <button type="submit" class="btn btn-success">Update</button>
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

</div>
@endsection

@section('script')
    @include('layouts.admin.js_script_backend')
@endsection
