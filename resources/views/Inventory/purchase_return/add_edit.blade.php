@extends('inc.master')
@section('head')

<title>Purchase Return</title>

@endsection
@section('content')
<style>
    .auto-search{
        width: 100%;
        display: block;
        position: relative;
    }
    .auto-search-product{
        width: 100%;
        display: block;
        position: relative;
    }
    .auto-search-container{
       position: absolute;
        background: #fff;
        z-index: 999;
        width: 100%;
        border: 1px solid #c3b9b9;
        box-shadow: 0px 0 2px 0px;

    }
    .auto-search-container ul{
        padding-inline-start: 0;
        padding: 10px;
        margin: 0;
    }
    .auto-search-container ul li:nth-child(1){
         border-top: 1px solid #d3cfcf;
    }
    .auto-search-container ul li{
        text-decoration: none;
        list-style: none;
        cursor: pointer;
        padding: 5px;
        border-bottom: 1px solid #d3cfcf;
    }
    .auto-search-container ul li:hover{
        background-color: #e9ecef;
    }
    .heading-elements a{
        color:#000!important;
    }
    .card a{
        color:#000!important;
    }
    .card{
        box-shadow: 0px 4px 25px 0px rgba(0, 0, 0, 0.1)!important;
    }
    .border {
        border: 1px solid #dee2e6!important;
    }
    . {
        height: calc(1em + 1rem + 2px);
        padding: 0.5rem 1.5rem;
        font-size: 0.7rem;
        line-height: 1;
        border-radius: 4px;
    }
    select.form-control{
        display: block;
        width: 100%;
        height: calc( 1em + 1.4rem + 1px) !important;
        padding: 0.7rem 0.7rem;
        font-size: 0.8rem;
        font-weight: 400;
        line-height: 1.25;
        color: #4e5154;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .toast-message{
        color:white;
    }
    .bootstrap-touchspin{
        flex-direction: column;
    }
    .bootstrap-touchspin input{
        width: 100%!important;
        margin: 1px;
    }
    .add-ajax-product input{
        padding: 0.2rem 0.2rem !important;
    }
    .select_qty{
        text-align: center;
    }
</style>
<div class="container" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;margin-top:0px;padding-bottom:30px;padding-left:10px;">
    <h4>Purchase Return</h4>
    <form action="{{ route('purchase_return.add_edit',$purchase_return->id) }}" method="post">
        @csrf
        <div class="card shadow border">
            <div class="card-header" style="padding:5px;">
                <h4 class="card-title">
                    <a data-action="collapse">Purchase Information</a>
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
                        <div class="col-md-6">
                            <p><strong>Purchase Reference :</strong> {{ $purchase_return->purchase->reference_no }}</p>
                            <p><strong>Date :</strong> {{ date('d/m/Y',strtotime($purchase_return->purchase->purchase_date)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Vendor :</strong> {{ $purchase_return->vendor?->name }}</p>
                            <p><strong>Branch :</strong> {{ $purchase_return->branch?->name }} </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row p-1">
                        <div class="col-md-3">
                            <label for=""><b>Return Date</b></label>
                            <input type="text" class=" form-control  datepicker {{ $errors->has('return_date') ? 'is-invalid' : '' }}" value="{{ old('return_date') ?? $purchase_return->return_date }}" name="return_date" autocomplete="off" required >
                             @if ($errors->has('return_date'))
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('return_date') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <label for=""><b>Return Reason *</b></label>
                            <input type="text" class=" form-control {{ $errors->has('return_reason') ? 'is-invalid' : '' }}" name="return_reason" autocomplete="off" required value="{{ old('return_reason') ?? $purchase_return->reason }}">
                             @if ($errors->has('return_reason'))
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('return_reason') }}</strong>
                            </span>
                            @endif
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
                                <th width="10%">Product</th>

                                <th width="10%">Unit Price</th>
                                <th width="10%">Purchase Qty</th>
                                <th width="6%">Return Qty</th>
                                <th width="10%">Price</th>
                                <th width="10%">Tax</th>
                                <th width="10%">Discount</th>
                            </tr>
                        </thead>
                        <tbody class="add-ajax-product">
                            @foreach ($purchase_return->purchase->items as $k=>$item)
                            @if($item->product)
                            <tr>
                                <td>{{ $item->product?->product_name }}
                                <input class="return_tax_input_{{ $item->id }}" type="hidden" name="return_tax[{{ $item->id }}]" value="0">
                                <input class="return_discount_input_{{ $item->id }}" type="hidden" name="return_discount[{{ $item->id }}]" value="0">
                                <input type="hidden" class="return_sub_total_input_{{ $item->id }}" name="return_sub_total[{{ $item->id }}]" value="0">
                                    <input type="hidden"  name="return_product[{{ $item->id }}]" value="{{ $item->product_id }}">

                                    <input type="hidden"  name="return_unit[{{ $item->id }}]" value="{{ $item->unit_id }}">
                                    <input type="hidden"  name="return_per_cost[{{ $item->id }}]" value="{{ $item->per_cost }}">
                                    <input type="hidden" name="return_tax_rate[{{ $item->id }}]" value="{{ $item->product->tax_id != 0 ? $item->product->tax->rate : 0 }}">
                                    <input type="hidden" name="return_discount_rate[{{ $item->id }}]" value="{{ $item->product->discount }}">
                                </td>

                                <td class="return_unit_price_{{ $item->id }}" u_price="{{ $item->per_cost }}">{{ $item->per_cost }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>
                                    @php
                                        $return_item = $purchase_return->items->where('product_id',$item->product_id)->where('color_id',$item->color_id)->first();
                                        if($return_item){
                                            $qty_count = $return_item->qty;
                                        }else{
                                            $qty_count=0;
                                        }
                                    @endphp
                                    <input data-id="{{ $item->id }}" class="cal_return return_qty_{{$item->id }}" type="number" name="return_qty[{{ $item->id }}]" value="{{ old('retunt_qty') ? old('retunt_qty')[$item->id] : $qty_count }}" max="{{ $item->qty }}">
                                </td>
                                <td class="return_sub_total_{{ $item->id }}">

                                    0.00
                                </td>
                                <td is_per = "{{ $item->product?->tax_id != 0 ? ($item->product->tax->rate_type == "Percentage" ? 1 : 0) : 0 }}" dis-per="{{ $item->product?->tax_id != 0 ? $item->product->tax->rate : 0 }}" class="return_tax_{{ $item->id }}">

                                    0.00
                                </td>
                                <td dis-per="{{ $item->product?->discount }}" is-per="{{ $item->product?->discount_type == "fixed" ? 0 : 1 }}" class="return_discount_{{ $item->id }}">

                                    0.00
                                </td>
                            </tr>
                            @endif
                           @endforeach

                        </tbody>
                        <tfoot class="tfoot active">
                            <input type="hidden" name="total_qty" id="total_qty_input">
                            <input type="hidden" name="total_price" id="total_price_input">
                            <input type="hidden" name="total_tax" id="total_tax_input">
                            <input type="hidden" name="sub_discount" id="sub_discount_input">
                             <input type="hidden" name="grand_total" id="grand_total_input">
                            <th colspan="3">Total</th>
                            <th id="total_qty">0</th>
                            <th id="total_price">0.00</th>
                            <th id="total_tax">0.00</th>
                            <th id="sub_discount">0.00</th>
                            {{-- <th><i class="dripicons-trash"></i></th> --}}
                        </tfoot>
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
                            <div class="form-group my-2">
                                <label class="col-md-8 text-right">
                                    <strong>Return Document</strong>
                                </label>
                                <input type="file" class="form-control" name="document">

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
                                                <option @if(old('payment_method',$purchase_return->payment_method) == $method->id) selected @endif value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach
                                        </Select>
                                        @if ($errors->has('payment_method'))
                                        <span class="invalid-feedback mb-0">
                                        <strong>{{ $errors->first('payment_method') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @if(array_search('accounts',load_pack_option()) != false)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-8 text-right">
                                            <strong>Account *</strong>
                                        </label>
                                        <input type="hidden" name="h_account" id="h_account" value="{{ old('h_account') }}">
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
                                @endif
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
                            {{-- <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right;">
                                    <strong>Shipping Cost</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" step="any"  id="sub_shipping_cost" name="shipping_cost" class="form-control" value="{{ old('shipping_cost') ?? 0 }}" />
                                </div>

                            </div> --}}
                            {{-- <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right;">
                                    <strong>Order Discount</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" step="any"  id="sub_order_discount" name="order_discount" class="form-control" value="{{ old('order_discount') ?? 0 }}" />
                                </div>

                            </div> --}}
                            <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right;">
                                    <strong>Grand Total</strong>
                                </label>
                                <div class="col-md-6">
                                    <input disabled type="number" id="grand_total"  class="form-control"/>
                                </div>

                            </div>
                            <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right;">
                                    <strong>Paid</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" id="paid_amount" name="paid_amount" class="form-control" value="{{ old('paid_amount') ?? 0 }}" />
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
                    <div class="form-group my-2" style="text-align: right">
                        <button type="submit" class="btn btn-primary" id="submit-btn">Submit</button>
                    </div>
                </div>


            </div>
        </div>
    </form>
</div>
@endsection
@section('script')


<script src="{{asset('public/assets/js/jquery.bootstrap-touchspin.js') }}"></script>
<script>



    $('.cal_return').on("keyup paste",function(){
        if(parseFloat(this.value) > parseFloat($(this).attr('max'))){
            alert('Qty has be less than equal '+this.value);
            this.value =  $(this).attr('max');
        }
        priceCalculation();
    });
     $('#paid_amount').on("keyup change",function(){
        priceCalculation();
    });
    $(".datepicker").flatpickr();

    $('a[data-action="collapse"]').on("click", function (e) {
      e.preventDefault();
      $(this)
        .closest(".card")
        .children(".card-content")
        .collapse("toggle");
      // Adding bottom padding on card collapse
      $(this)
        .closest(".card")
        .children(".card-header")
        .css("padding-bottom", "1.5rem");
      $(this)
        .closest(".card")
        .find('[data-action="collapse"]')
        .toggleClass("rotate");
    });





    priceCalculation();

   
    function priceCalculation(paid_o = 0){
        var p_total =0;
        var sub_tax=0;
        var sub_discount = 0;
        var total_qty = 0;
        $('.cal_return ').each(function(){
            var id = $(this).attr('data-id');
            var qty =$('.return_qty_'+id).val();

            var unit_price =$('.return_unit_price_'+id).attr('u_price');
            var sub_total = parseFloat(qty) * parseFloat(unit_price);
            $('.return_sub_total_'+id).text(sub_total.toFixed(2));
            $('.return_sub_total_input_'+id).val(sub_total.toFixed(2));
            if($('.return_tax_'+id).attr('is_per') == 1){
                var tax_per = $('.return_tax_'+id).attr('dis-per');
                var tax = sub_total * parseFloat(tax_per)/100;
                tax = parseFloat(tax); 
                $('.return_tax_'+id).text(tax.toFixed(2));
                $('.return_tax_input_'+id).val(tax.toFixed(2));
            }else{
                var tax = $('.return_tax_'+id).attr('dis-per');
                tax = parseFloat(tax); 
                $('.return_tax_'+id).text(tax.toFixed(2));
                $('.return_tax_input_'+id).val(tax.toFixed(2));
            }
            if($('.return_discount_'+id).attr('is_per') == 1){
                var discount_per = $('.return_tax_'+id).attr('dis-per');
                var discount = sub_total * parseFloat(discount_per)/100;
            }else{
                var discount = sub_total != 0? $('.return_tax_'+id).attr('dis-per') : 0;

            }
            discount = parseFloat(discount);
            $('.return_discount_'+id).text(discount.toFixed(2));
             $('.return_discount_input_'+id).val(discount.toFixed(2));
            total_qty += qty;
            p_total += sub_total;
            sub_tax += tax;
            sub_discount += discount;
        });
        $('#total_qty').text(total_qty);
        $('#total_price').text(p_total.toFixed(2));
        $('#total_tax').text(sub_tax.toFixed(2));
        $('#sub_discount').text(sub_discount.toFixed(2));

        $('#total_qty_input').val(total_qty);
        $('#total_price_input').val(p_total.toFixed(2));
        $('#total_tax_input').val(sub_tax.toFixed(2));
        $('#sub_discount_input').val(sub_discount.toFixed(2));

        $('#dis_total_tax').val(sub_tax.toFixed(2));
        var grand_total =parseFloat(sub_tax) + parseFloat(p_total)-parseFloat(sub_discount);
        $('#grand_total').val(grand_total.toFixed(2));
        $('#grand_total_input').val(grand_total.toFixed(2));
        if(paid_o == 0){
            @if(array_search('accounts',load_pack_option()) != false)
                if($('#payment_method').val() > 0  &&  $('#add_account').val() > 0){
                    $('#paid_amount').val(grand_total.toFixed(2));
                }else{
                    $('#paid_amount').val(0);
                }
            @else
                if($('#payment_method').val() > 0){
                    $('#paid_amount').val(grand_total.toFixed(2));
                }else{
                    $('#paid_amount').val(0);
                }
            @endif
        //    if($('#payment_method').val() > 0  &&  $('#add_account').val() > 0){
        //         $('#paid_amount').val(grand_total.toFixed(2));
        //     }else{
        //         $('#paid_amount').val(0);
        //     }
        }
        var paid_amount = $('#paid_amount').val() ?? 0;
        var due_amount = parseFloat(grand_total) - parseFloat(paid_amount);

        $('#due_amount').val(due_amount.toFixed(2));
    }






    @if(Session::has('cus_errors'))
        @foreach (Session::get('cus_errors') as $k=>$error)
            toastr.error("{{ $error }}");

            // console.log("{{ Session::get('errors')->color }}");
        @endforeach
    @endif

    $('#payment_method').on('change',function(){
        if(this.value == 1){
            $('.method_show').hide();
        }else{
            $('.method_show').show();
        }
        priceCalculation();
    });
    $('#add_account').select2({
        theme: "bootstrap-5",
        placeholder: 'Select Bank Account',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.balance_accounts')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    method_id:$('#payment_method').val(),
                    value: $.trim(params.term),
                };
            },
            processResults: function (response) {
                console.log(response);
                return {
                    results: response
                };
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        var data = e.params.data;
        $('#h_account').val(data.text);

    });
    @if(old('h_account'))
    var account_option = new Option("{{ old('h_account') }}","{{ old('account')}}", true, true);
    $('#add_account').append(account_option).trigger('change');
    @endif
</script>
@endsection
