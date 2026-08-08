@extends('inc.master')
@section('head')

<title>Create Purchase</title>

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
    .form-control-sm {
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
    .select2-container .select2-selection--single{
        height: 37px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 37px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 37px!important;
    }
</style>
<div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;margin-top:0px;padding-bottom:30px;padding-left:10px;">
    <h4>Add New Purchase Return</h4>
    <form action="{{ route('purchase_return.store') }}" method="post">
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
                        <div class="col-md-3">
                            <label for=""><b>Company Name</b></label>
                            <input type="text" class=" form-control vendor-auto-search @error('name')  is-invalid @enderror" name="name" autocomplete="off" required value="{{old('name')}}">
                            @error('name')
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Company Email</b></label>
                            <input type="email" class=" form-control vendor-auto-search {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" autocomplete="off" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Company Mobile</b></label>
                            <input type="number" class=" form-control vendor-auto-search {{ $errors->has('mobile') ? 'is-invalid' : '' }}" name="mobile" autocomplete="off" required value="{{ old('mobile') }}">
                            @if ($errors->has('mobile'))
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('mobile') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="col-md-3">
                            <label for=""><b>Company Address</b></label>
                            <input type="text" class=" form-control vendor-auto-search {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" autocomplete="off" required value="{{ old('address') }}">
                             @if ($errors->has('addres'))
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('address') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row p-1">
                        <div class="col-md-3">
                            <label for=""><b>Purchase Date</b></label>
                            <input type="text" class=" form-control form-control-sm datepicker {{ $errors->has('purchase_date') ? 'is-invalid' : '' }}" value="{{ date('d-m-Y') }}" name="purchase_date" autocomplete="off" required name="{{ old('purchase_date') }}">
                             @if ($errors->has('purchase_date'))
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('purchase_date') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-sm-3">
                            <label for=""><b>Branch</b></label>
                            <input type="hidden" name="h_branch" id="h_branch" value="{{  old('h_branch')  }}"/>
                            <select id="select_branch" class="form-control select_branch form-control-sm {{ $errors->has('branch') ? 'is-invalid' : '' }}" name="branch">
                                <option value="">-- Select One --</option>


                            </select>
                             @if ($errors->has('branch'))
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('branch') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-3">

                            <label><b>Purchase Status</b></label>
                            <select name="status" class="form-control mb-0 {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                <option @if(old('status') == 1) selected @endif value="1">Recieved</option>
                                <option @if(old('status') == 2) selected @endif value="2">Partial</option>
                                <option @if(old('status') == 3) selected @endif value="3">Pending</option>
                                <option @if(old('status') == 4) selected @endif value="4">Ordered</option>
                            </select>
                             @if ($errors->has('status'))
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('status') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>
                    <div class="row p-1">
                        <div class="col-md-3">
                             <label><b>Select Category</b></label>
                            <select data-in="1" class="form-control" id="select_category">
                                <option value="">Category</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label><b>Select Product</b></label>
                            <select data-in="1" class="form-control" id="select_product">
                                <option value="">Product</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label><b>Select Product By Search</b></label>
                            <div class="search-box input-group">
                                <button class="btn btn-secondary" type="button"><i class="bx bx-barcode"></i></button>
                                <input type="text" name="product_code_name" form="form2" id="productcodeSearch" placeholder="Please type product code and select..." class="form-control" />
                                <div style="    width: 100%;" id="auto-serach-res-f"></div>
                            </div>
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
                                <th width="20%">Product</th>
                                <th width="10%">Manufacture</th>
                                <th width="10%">Brand</th>
                                <th width="10%">Qty</th>
                                <th width="13%">Unit</th>
                                <th width="10%">Price</th>
                                <th width="6%">Tax</th>
                                <th width="10%">Discount</th>
                                <th width="10%">Total Price</th>
                                <th width="4%"></th>
                            </tr>
                        </thead>
                        <tbody class="add-ajax-product">
                            {{-- @if(old('product_id'))
                            @php
                                $row_no=1;
                            @endphp
                            @foreach (old('product_id') as $product_id)
                            @php
                                $product = \App\Models\Inventory\Product::find($product_id);
                                $p_price = $product->product_p_price[0];
                            @endphp
                            <tr id="row_{{ $row_no }}">

                                <td>
                                    <input type="hidden" name="product_id[]" class="select_product_id_{{ $row_no }}" value="{{ $product->id }}">
                                    <label data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="select_product_code_{{ $row_no }} select_product_code">{{ $product->product_code }}</label>
                                </td>
                                <td>
                                    <select data-in="{{ $row_no }}" class="form-control select_color_{{ $row_no }} select_color mb-0" name="color[]">
                                        <option value="">Color</option>
                                    </select>
                                </td>
                                <td>
                                    <select data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="form-control select_size select_size_{{ $row_no }} mb-0" name="size[]">
                                        <option value="">Size</option>
                                    </select>
                                </td>
                                <td>
                                    <input data-in="{{ $row_no }}" class="form-control touchspin form-control-sm select_qty_{{ $row_no }} select_qty" type="number" name="qty[]" value="1" placeholder="Qty">
                                </td>
                                <td>
                                    <select name="unit[]" data-in="{{ $row_no }}" class="form-control select_unit_{{ $row_no }} select_unit mb-0">
                                        <option value="">Unit</option>

                                    </select>
                                </td>
                                <td>
                                <input data-in="{{ $row_no }}" class="form-control form-control-sm purchase_price_{{ $row_no }} select_purchase_price" name="per_cost[]" type="text" placeholder="Price" value="{{ $p_price->purchase_price }}">
                                </td>
                                <td>
                                    @php
                                        if($product->discount_type == "fixed"){
                                            $dis = $product->discount;
                                        }else{
                                            $dis = $p_price->purchase_price * $product->discount/100;
                                        }
                                        $total = 1 * $p_price->purchase_price;
                                    @endphp
                                <input class="form-control form-control-sm discount_price_{{ $row_no }} select_discount_price" type="text" dis-per="{{ $product->discount }}" is-per="{{ $product->discount_type == "fixed" ? 0 : 1 }}" name="discount[]" value="{{ $dis }}" placeholder="Discount">
                                </td>
                                <td>
                                <input name="total[]" data-in="{{ $row_no }}" type="text" class="form-control form-control-sm total_price_{{ $row_no }} select_total_price" placeholder="Total" value="{{$total}}">
                                </td>
                                <td>
                                    <a class="delete_item" href="javascript:void(0);"><i style="color:red;" class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @php
                                $row_no++;
                            @endphp
                            @endforeach
                            @endif --}}

                        </tbody>
                        <tfoot class="tfoot active">
                            <th colspan="3">Total</th>
                            <th id="total_qty">0</th>
                            <th></th>
                            <th></th>
                            <th id="sub_tax_total">0.00</th>
                            <th id="sub_discount_total">0.00</th>
                            <th id="total">0.00</th>
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
                                    <strong>Purchase Document</strong>
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
                                @if(array_search('accounts',load_pack_option()) != false)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-8 text-right">
                                            <strong>Account *</strong>
                                        </label>
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
                            <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right;">
                                    <strong>Shipping Cost</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" step="any"  id="sub_shipping_cost" name="shipping_cost" class="form-control" value="{{ old('shipping_cost') ?? 0 }}" />
                                </div>

                            </div>
                            <div class="form-group row mt-1">
                                <label class="col-md-6 text-right" style="text-align: right;">
                                    <strong>Order Discount</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" step="any"  id="sub_order_discount" name="order_discount" class="form-control" value="{{ old('order_discount') ?? 0 }}" />
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
@section('script')


<script src="{{asset('public/assets/js/jquery.bootstrap-touchspin.js') }}"></script>
<script>
    var selected_vendor_id = 0;
    var selected_vendor_field = "";
    var selected_vendor_f_value = "";
    var selected_vendor_name = "";
    var selected_vendor_email = "";
    var selected_vendor_mobile= "";
    var selected_vendor_address = "";
    var auto_serach_modal=0;
    var p_row_no = 1;
    var auto_serach_product_modal=0;
    $('#productcodeSearch').on("keyup",function(){
        var arg=this;
        $('.auto-search-product').remove();
        if($(this).val() != ""){
            var curval = $(this).val().trim();
            var is_barcode = 0;
            if(curval.indexOf('nbr') !== -1){
                is_barcode = 1;
            }
            $.ajax({
                url: "{{route('auto-search.product') }}",
                method: 'GET',
                data:{
                    value:curval,
                    is_barcode:is_barcode
                },
                success: function(data) {
                    console.log(data);
                    if(data.status == "success"){
                        auto_serach_product_modal =1;
                        $('#auto-serach-res-f').html(data.data);
                    }else{
                        $('#auto-serach-res-f').html(data.data);  
                    }
                    if(is_barcode == 1){
                        $(arg).val('');
                        $('.auto-search-res-product').first().trigger('click');
                        $('.auto-search-product').slideUp();
                    }


                }
            });
        }else{


        }
    });
     $(document).on('click','.auto-search-res-product',function(){
         auto_serach_product_modal =0;
        var product = JSON.parse($(this).attr('data'));
        $('#productcodeSearch').val("");
        if($('.add-ajax-product').find('.pro-row-'+product.p_id).length == 0){
            FillProductOption(product.p_id,p_row_no);
            p_row_no++;
        }
        $('.auto-search-product').slideUp();
    });
    $('.vendor-auto-search').on('keyup',function(){
        var arg=this;
        $('.auto-search').remove();
        if($(this).val() != ""){
            $.ajax({
                url: "{{route('auto-search.vendor') }}",
                method: 'GET',
                data:{
                    field:$(this).attr('name'),
                    value:$(this).val().trim(),
                },
                success: function(data) {
                    if(data.status == "success"){
                        auto_serach_modal=1;
                        selected_vendor_field = $(arg).attr('name');
                        selected_vendor_f_value = data.f_data[selected_vendor_field];
                        // console.log(selected_vendor_f_value);

                        $(arg).after(data.data);
                        if(data.f_data){
                            if(selected_vendor_id != data.f_data.id){
                                selected_vendor_id = data.f_data.id;
                                selected_vendor_name = data.f_data.name;
                                selected_vendor_email = data.f_data.email;
                                selected_vendor_mobile= data.f_data.mobile;
                                selected_vendor_address = data.f_data.address;
                                // var old_sel_val = $('input[name="'+selected_vendor_field+'"]').val();
                                // $('input[name="name"]').val(data.f_data.name);
                                // $('input[name="email"]').val(data.f_data.email);
                                // $('input[name="mobile"]').val(data.f_data.mobile);
                                // $('input[name="address"]').val(data.f_data.address);
                                // $('input[name="'+selected_vendor_field+'"]').val(old_sel_val);
                            }

                        }
                    }else{
                        auto_serach_modal=1;
                        selected_vendor_id=0;
                        $(arg).after(data.data);
                        var old_val = arg.value;
                        // $('input[name="name"]').val('');
                        // $('input[name="email"]').val('');
                        // $('input[name="mobile"]').val('');
                        // $('input[name="address"]').val('');
                        // $(arg).val(old_val);
                    }


                }
            });
        }else{
            selected_vendor_id=0;
            // $('input[name="name"]').val('');
            // $('input[name="email"]').val('');
            // $('input[name="mobile"]').val('');
            // $('input[name="address"]').val('');

        }

    });
    $(document).on('click','.auto-search-res',function(){
        //console.log(JSON.parse($(this).attr('data')));
        auto_serach_modal =0;
        var vendor = JSON.parse($(this).attr('data'));
        $('input[name="name"]').val(vendor.name);
        $('input[name="email"]').val(vendor.email);
        $('input[name="mobile"]').val(vendor.mobile);
        $('input[name="address"]').val(vendor.address);
        $('.auto-search').slideUp();
    });

    $(document).on('click',function(event) {
        if(auto_serach_modal == 1){
            if (!$(event.target).closest(".auto-search").length) {
                if ($('.auto-search').is(":visible"))
                {
                    auto_serach_modal=0;
                    $('.auto-search').slideUp();
                }

                if(selected_vendor_id != 0){
                    $('input[name="name"]').val(selected_vendor_name);
                    $('input[name="email"]').val(selected_vendor_email);
                    $('input[name="mobile"]').val(selected_vendor_mobile);
                    $('input[name="address"]').val(selected_vendor_address);
                   // $('input[name="'+selected_vendor_field+'"]').val(selected_vendor_f_value);
                }
            }
        }
        console.log(auto_serach_modal);
        if(auto_serach_product_modal == 1){
            if (!$(event.target).closest(".auto-search-product").length) {
                if ($('.auto-search-product').is(":visible"))
                {
                    $('#productcodeSearch').val("");
                    auto_serach_product_modal=0;
                    $('.auto-search-product').slideUp();
                }
            }
        }

    });
    // $(document).on('click','*:not(.auto-search *)',function(){
    //     console.log(this);
    // });
    $(".datepicker").flatpickr();
    $('.select_branch').select2({
      placeholder: 'Select Branch',
      allowClear: true,
      width:'100%',
      dropdownAutoWidth : true,
      containerCssClass: 'select-sm',
      ajax: {
        url: '{{route('select2.branches')}}',
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
    }).on('select2:select', function (e) {
        var data = e.params.data;
        $('#h_branch').val(data.text);

    });
    var branch_option = new Option("{{ old('h_branch') }}","{{ old('branch')}}", true, true);
    $('.select_branch').append(branch_option).trigger('change');
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
    touchQty(1);
    function touchQty(i_val){
        $(document).find('.select_qty_'+i_val).TouchSpin({
            min: '1',
            max: 'atuo',
            step: 1,
            decimals: 2,
            forcestepdivisibility: 'none',
            buttondown_class: "btn btn-primary",
            buttonup_class: "btn btn-primary"
        });
    }



    $(document).find('#select_category').select2({
        placeholder: 'Select Category',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.product.categories')}}',
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
    $(document).find('#select_product').select2({
        placeholder: 'Select Product',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.products.by_category')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                cat_id:$('#select_category').val(),
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

    var product_price=[];
    $(document).on('change','.select_qty,.select_purchase_price',function(){
        priceCalculation();
    });
    $(document).on('keyup','.select_qty,.select_purchase_price,.select_discount_price,#sub_order_discount,#sub_shipping_cost,#due_amount',function(){
        priceCalculation();
    });
    $(document).on('keyup','#paid_amount',function(){

        priceCalculation(1);
    });
    function priceCalculation(paid_o = 0){
        var p_total =0;
        var total_qty =0;
        var sub_discount=0;
        var sub_tax=0;
        $('.select_product_code ').each(function(){
            if($(this).attr('data-id') != 0){
                var row_id = $(this).attr('data-in');

                var qty = $('.select_qty_'+row_id).val();
                var price = $('.purchase_price_'+row_id).val();
                var total = (parseInt(qty) || 0) * (parseFloat(price) || 0);
                var dis=0,tax=0;
                if($('.discount_price_'+row_id).attr('is-per') == 1){

                    dis = total * parseInt($('.discount_price_'+row_id).attr('dis-per'))/100;
                    dis = dis.toFixed(2);
                    $('.discount_price_'+row_id).val(dis);
                    // console.log(total * parseInt($('.discount_price_'+row_id).attr('dis-per'))/100);
                }else{
                    dis = parseFloat($('.discount_price_'+row_id).val()) || 0;
                }
                console.log(total);
                if($('.tax_'+row_id).attr('is_per') == 1){

                   tax = (total) * parseInt($('.tax_'+row_id).attr('dis-per'))/100;
                   tax = parseFloat(tax); 
                   tax = tax.toFixed(2);
                    $('.tax_'+row_id).val(tax);
                    // console.log(total * parseInt($('.discount_price_'+row_id).attr('dis-per'))/100);
                }else{
                   tax = parseFloat($('.tax_'+row_id).val()) || 0;
                }
                sub_discount += dis;
                sub_tax += tax;
                p_total += total;
                total_qty +=  parseInt(qty) || 0;
                $('.total_price_'+row_id).val(total);
            }

        });
        console.log("tax : "+ sub_tax);
        $('#sub_tax_total').html(sub_tax);
        $('#dis_total_tax').val(sub_tax);
        $('#sub_discount_total').html(sub_discount);
        $('#total_qty').html(total_qty);
         $('#total_qty_input').val(total_qty);
        $('#total').html(p_total);
        var total_items = $('table.order-list tbody tr:last').index();
        if(total_items < 0){
            total_items = 0;
        }else{
           total_items = total_items+1;
        }
        $('#item').html(total_items);
        $('#item_input').val(total_items);
        $('#subtotal').html(p_total);
        $('#subtotal_input').val(p_total);
        var total_discount = sub_discount+parseFloat($('#sub_order_discount').val());
        var shipping_cost= $('#sub_shipping_cost').val();
        var grand_total =parseFloat(sub_tax) + parseFloat(p_total)+(parseFloat(shipping_cost) || 0) - (parseFloat(total_discount) || 0);
        grand_total = grand_total.toFixed(2);
         if($('#payment_method').val() > 0  &&  $('#add_account').val() > 0){
            $('#paid_amount').val(grand_total.toFixed(2));
        }else{
            $('#paid_amount').val(0);
        }
        // if(paid_o == 0){
        //      $('#paid_amount').val(grand_total);
        // }
        var paid_amount = $('#paid_amount').val() ?? 0;
        var due_amount = parseFloat(grand_total) - parseFloat(paid_amount);
       due_amount =due_amount.toFixed(2);
        $('#due_amount').val(due_amount);
        $('#order_discount_input').val(total_discount);
        $('#order_discount').html(total_discount);
        $('#order_tax_input').val(sub_tax);
        $('#order_tax').html(sub_tax);
        $('#shipping_cost').html(shipping_cost);
        $('#grand_total_input').val(grand_total);
        $('#grand_total').html(grand_total);
    }

    $(document).on('change','#select_product',function(){
        if(this.value != 0){
            var id=$(this).val();
            FillProductOption(id,p_row_no);
            $(this).val(0).trigger('change');
            p_row_no++;
        }else{
            // console.log(this.value);
        }


        // $(this).val(0).trigger('change');
    });
    function FillProductOption(id,row_no,old=0,qty=1,unit_option=null){
         $.ajax({
            url: "{{route('get_product_details_by_id') }}",
            method: 'GET',
            data:{
                is_sale:0,
                qty:qty,
                old:0,
                id:id,
                row_id:row_no
            },
            success: function(data) {
                if($('.add-ajax-product').find('.pro-row-'+id).length == 0){
                    $('.add-ajax-product').append(data.data_view);
                    initalUnit(row_no);
                    touchQty(row_no);
                    if(data.unit_id > 0){
                        var unit_option = new Option(data.unit_name, data.unit_id, true, true);
                        $('.select_unit_'+row_no).append(unit_option).trigger('change');
                        if(unit_option){
                            $('.select_unit_'+row_no).append(unit_option).trigger('change');
                        }
                    }
                    //product_price[data.p_data.id] = data.p_data.product_p_price;
                // var p_price =  data.p_data.product_p_price[0];

                    // var unit_option = new Option(data.unit_name, data.unit_id, true, true);
                    // $('.select_unit_'+row_no).append(unit_option).trigger('change');

                    // if(unit_option){
                    //     $('.select_unit_'+row_no).append(unit_option).trigger('change');
                    // }
                    //p_row_no++;
                    priceCalculation();
                }
            }
        });

        // addAnotherRow(arg);
    }

    function addAnotherRow(arg){
        p_row_no++;
        var myvar = '<tr id="row_'+p_row_no+'">'+
            '<td><label data-in="'+p_row_no+'" data-id="0" class="select_product_code select_product_code_'+p_row_no+'">Code..</label><input type="hidden" name="product_id[]" class="select_product_id_'+p_row_no+'"></td>'+
        '                            <td>'+

        '                                <select data-in="'+p_row_no+'" class="form-control select_color_'+p_row_no+' select_color mb-0" name="color[]">'+
        '                                    <option value="">Color</option>'+
        '                                </select>'+
        '                            </td>'+
        '                            <td>'+
        '                                <select data-in="'+p_row_no+'" class="form-control select_size select_size_'+p_row_no+' mb-0" name="size[]">'+
        '                                    <option value="">Size</option>'+
        '                                </select>'+
        '                            </td>'+
        '                            <td>'+
        '                                <input data-in="'+p_row_no+'" class="form-control select_qty_'+p_row_no+' select_qty touchspin form-control-sm" name="qty[]" value="1" type="number" placeholder="Qty">'+
        '                            </td>'+
        '                            <td>'+
        '                                <select data-in="'+p_row_no+'" class="select_unit select_unit_'+p_row_no+' form-control mb-0" name="unit[]">'+
        '                                    <option>Unit</option>'+
        '                                    <option>Piece</option>'+
        '                                    <option>Pata</option>'+
        '                                    <option>Box</option>'+
        '                                    <option>Carton</option>'+
        ''+
        '                                </select>'+
        '                            </td>'+
        '                            <td>'+
        '                               <input data-in="'+p_row_no+'" class="form-control purchase_price_'+p_row_no+' select_purchase_price form-control-sm" name="per_cost[]" type="text" placeholder="Price">'+
        '                            </td>'+
        '                            <td>'+
        '                               <input class="form-control discount_price_'+p_row_no+' select_discount_price form-control-sm" type="text" placeholder="Discount" name="discount[]">'+
        '                            </td>'+
        '                            <td>'+
        '                               <input data-in="'+p_row_no+'" type="text" class="form-control total_price_'+p_row_no+' form-control-sm select_total_price" name="total[]" placeholder="Total">'+
        '                            </td>'+
        '                        </tr>';
        var curlastrow = $('#row_'+arg);
        $(curlastrow).after(myvar);

        touchQty(p_row_no);

    }
    @if(old('product_id'))
    @foreach (old('product_id') as $k=>$product_id)


        @php

            $unit=App\Models\Inventory\Unit::find(old('unit')[$k]);
        @endphp


        var unit_option = new Option("{{$unit?->name}}","{{$unit?->id}}", true, true);
        FillProductOption("{{$product_id}}",p_row_no,0,"{{ old('qty')[$k] }}",unit_option);

        p_row_no++;
    @endforeach
    @endif

    function initalUnit(r_no){
        $(document).find('.select_unit_'+r_no).select2({
            placeholder: 'Select Unit',
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            containerCssClass: 'select-sm',
            ajax: {
                url: '{{route('select2.product.units')}}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    p_id:$('.select_product_code_'+r_no).attr('data-id'),
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

    @if(Session::has('cus_errors'))
        @foreach (Session::get('cus_errors') as $k=>$error)
            toastr.error("{{ $error }}");

            // console.log("{{ Session::get('errors')->color }}");
        @endforeach
    @endif
    $(document).on('click','.delete_item',function(){
        $(this).parent().parent().remove();
        priceCalculation();
    });
    $('#payment_method').on('change',function(){
        if(this.value == 1){
            $('.method_show').hide();
        }else{
            $('.method_show').show();
        }
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
    });
    $(document).on('change','#add_account',function(){
        priceCalculation();
    });
</script>
@endsection
