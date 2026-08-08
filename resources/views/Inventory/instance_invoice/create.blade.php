@extends('inc.master')
@section('head')

<title>Instance Invoice</title>
@section('content')
<div class="container" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;margin-top:0px; padding-bottom:30px;padding-left:10px;">
    <h4>Instance Invoice</h4>
    <form action="{{ route('invoice.store') }}" method="post">
        @csrf
        <div class="card shadow border">
            <div class="card-header" style="padding:5px;">
                <h4 class="card-title">
                    <a data-action="collapse">Billing Information</a>
                </h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                    <li><a data-action="collapse" class="rotate"><i class="fas fa-arrow-down"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body pt-0">

                    <div class="row p-1">
                        {{-- <div class="col-md-3">
                            <label for=""><b>Vendor</b></label>
                            <textarea class="form-control" name="vendor_info" id="" cols="30" rows="10">{{old('vendor_info')}}</textarea>
                        </div> --}}
                        <div class="col-md-12">
                            <label for=""><b>Customer</b></label>
                            <textarea class="form-control" name="customer_info" id="" cols="30" rows="2">{{old('customer_info')}}</textarea>
                        </div>

                    </div>
                    <div class="row p-1">
                        <div class="col-md-3">
                            <label for=""><b>Invoice Date</b></label>
                            <input type="text" class=" form-control datepicker" value="{{ date('Y-m-d') }}" name="invoice_date" autocomplete="off" required name="{{ old('invoice_date') }}">
                        </div>


                    </div>

                </div>
            </div>
        </div>
        <div class="card shadow border">
            <div class="card-content p-3">
                <div class="container-fluid">
                    <table class="order-list table-responsive table-bordered table-sm text-center nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="50%">Item</th>

                                <th width="10%">Qty</th>
                                <th width="10%">Rate</th>
                                <th width="6%">Tax</th>
                                <th width="10%">Discount</th>
                                <th width="10%">Total Price</th>
                                <th width="4%"></th>
                            </tr>
                        </thead>
                        <tbody class="add-ajax-product">


                        </tbody>
                        {{-- <tfoot class="tfoot active">
                            <th>Total</th>
                            <th id="total_qty">0</th>
                            <th></th>
                            <th></th>
                            <th id="sub_tax_total">0.00</th>
                            <th id="sub_discount_total">0.00</th>
                            <th id="total">0.00</th>
                            <th></th>
                        </tfoot> --}}
                    </table>
                </div>
                <div class="container-fluid">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-8 text-right">
                                    <strong>Order Note</strong>
                                </label>
                                <textarea name="order_note" class="form-control" cols="30" rows="2">{{ old('order_note') }}</textarea>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-8 text-right">
                                            <strong>Payment Method</strong>
                                        </label>
                                        <Select class="form-control {{ $errors->has('payment_method') ? 'is-invalid' : '' }}" name="payment_method" id="payment_method">
                                            <option value="">Select Method</option>
                                            @foreach ($methods as $method)
                                                <option @if(old('payment_method') == $method->id) selected @endif value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach
                                        </Select>
                                        @if ($errors->has('payment_method'))
                                        <span class="invalid-feedback mb-0">
                                        <strong>{{ $errors->first('payment_method') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-8 text-right">
                                            <strong>Account *</strong>
                                        </label>
                                        <input type="hidden" name="h_bank_account" id="h_bank_account">
                                        <Select class="form-control {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account" id="add_account">
                                            <option value="">Select Account</option>

                                        </Select>
                                        @if ($errors->has('account'))
                                        <span class="invalid-feedback mb-0">
                                        <strong>{{ $errors->first('account') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right;">
                                    <strong>Total Tax</strong>
                                </label>
                                <div class="col-md-6">
                                    <input disabled type="number" step="any" id="dis_total_tax" name="dis_total_tax" class="form-control" value="{{ old('dis_total_tax') ?? 0 }}" />
                                </div>

                            </div>
                            <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right">
                                    <strong>Shipping Cost</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" id="sub_shipping_cost" name="shipping_cost" class="form-control" value="{{ old('shipping_cost') ?? 0 }}" />
                                </div>

                            </div>
                            <div class="form-group row mt-1">
                                <label class="col-md-6" style="text-align: right">
                                    <strong>Order Discount</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" id="sub_order_discount" name="order_discount" class="form-control" value="{{ old('order_discount') ?? 0 }}" />
                                </div>

                            </div>
                            <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right;">
                                    <strong>Paid</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" step="any" id="paid_amount" name="paid_amount" class="form-control" value="{{ old('paid_amount') ?? 0 }}" />
                                </div>

                            </div>
                            <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right;">
                                    <strong>Due</strong>
                                </label>
                                <div class="col-md-6">
                                    <input disabled type="number" id="due_amount" name="due_amount" class="form-control" value="{{ old('due_amount') ?? 0 }}" />
                                </div>

                            </div>
                        </div>


                    </div>
                    <div class="form-group my-2" style="text-align: right;">
                        <button type="submit" class="btn btn-primary" id="submit-btn">Submit</button>
                    </div>
                </div>

                <div class="container-fluid">
                    <table class="table table-bordered table-condensed totals">
                        <td><strong>Items</strong>
                            <input type="hidden" name="item" id="item_input">
                            <input type="hidden" name="total_qty" id="total_qty_input">
                            <span class="pull-right" id="item">0.00</span>
                        </td>
                        <td><strong>Total</strong>
                            <input type="hidden" name="total_cost" id="subtotal_input">
                            <span class="pull-right" id="subtotal">0.00</span>
                        </td>
                        <td><strong>Order Tax</strong>
                            <input type="hidden" name="total_tax" id="order_tax_input">
                            <span class="pull-right" id="order_tax">0.00</span>
                        </td>
                        <td><strong>Discount</strong>
                             <input type="hidden" name="total_discount" id="order_discount_input">
                            <span class="pull-right" id="order_discount">0.00</span>
                        </td>
                        <td><strong>Shipping Cost</strong>
                            <span class="pull-right" id="shipping_cost">0.00</span>
                        </td>
                        <td><strong>Grand Total</strong>
                            <input type="hidden" name="grand_total" id="grand_total_input">
                            <span class="pull-right" id="grand_total">0.00</span>
                        </td>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
