@extends('inc.master')
@section('head')

<title>Edit Quotation</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
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
<div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;margin-top:0px; padding-bottom:30px;padding-left:10px;">
    <h4>Update Quotation</h4>
    <form action="{{ route('quotation.update',$quotation->id) }}" method="post">
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
                            <label for=""><b>Customer Name</b></label>
                            <input value="{{ old('name') ?? $quotation?->customer?->name }}" type="text" class=" form-control  vendor-auto-search" name="name" autocomplete="off" required>

                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Customer Email</b></label>
                            <input value="{{ old('email') ?? $quotation->customer?->email }}" type="email" class=" form-control  vendor-auto-search" name="email" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Customer Mobile</b></label>
                            <input value="{{ old('mobile') ?? $quotation->customer?->mobile }}" type="number" class=" form-control  vendor-auto-search" name="mobile" autocomplete="off" required>
                        </div>

                        <div class="col-md-3">
                            <label for=""><b>Customer Address</b></label>
                            <input value="{{ old('address') ?? $quotation->customer?->address }}" type="text" class=" form-control  vendor-auto-search" name="address" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row p-1">
                        <div class="col-md-3">
                            <label for=""><b>Quotation Date</b></label>
                            <input type="text" class=" form-control  datepicker" value="{{ date('Y-m-d') }}" value="{{ old('quotation_date') ?? $quotation->quotation_date }}" name="quotation_date" autocomplete="off" required>
                        </div>
                        {{-- <div class="col-sm-3">
                            <label for=""><b>Branch</b></label>
                            <select id="select_branch" class="form-control select_branch " name="branch">
                                <option value="">-- Select One --</option>


                            </select>
                        </div> --}}
                        <div class="col-md-3">

                            <label><b>Quotation Status</b></label>
                            <select name="status" class="form-control  mb-0">
                                @if(old('status') != null)
                                    <option @if(old('status') == 1) selected  @endif value="1">Recieved</option>
                                    <option  @if(old('status') == 2) selected @endif value="2">Partial</option>
                                    <option  @if(old('status') == 3) selected @endif value="3">Pending</option>
                                    <option  @if(old('status') == 4) selected @endif value="4">Ordered</option>
                                @else
                                <option @if($quotation->status == 1) selected @endif value="1">Recieved</option>
                                <option  @if($quotation->status == 2) selected @endif value="2">Partial</option>
                                <option  @if($quotation->status == 3) selected @endif value="3">Pending</option>
                                <option  @if($quotation->status == 4) selected @endif value="4">Ordered</option>
                                @endif
                            </select>

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
                                <textarea name="order_note" class="form-control" cols="30" rows="2">{{ old('note') ?? $quotation->note }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group row">
                                <label class="col-md-6 text-right">
                                    <strong>Shipping Cost</strong>
                                </label>
                                <div class="col-md-6">
                                    <input value="{{  old('shipping_cost') ?? $quotation->shipping_cost}}" type="number" id="sub_shipping_cost" name="shipping_cost" class="form-control" value="0" />
                                </div>

                            </div>
                            <div class="form-group row mt-1">
                                <label class="col-md-6 text-right">
                                    <strong>Order Discount</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" id="sub_order_discount" name="order_discount" value="{{ old('order_discount') ?? $quotation->order_discount}}" class="form-control" />
                                </div>

                            </div>
                        </div>


                    </div>
                    <div class="form-group my-2" style="text-align:right;">
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
        console.log(p_row_no);
        if($('.add-ajax-product').find('.pro-row-'+product.p_id).length == 0){
            FillProductOption(product.p_id,p_row_no);
        }
        $('.auto-search-product').slideUp();
    });
    $('.vendor-auto-search').on('keyup',function(){
        var arg=this;
        $('.auto-search').remove();
        if($(this).val() != ""){
            $.ajax({
                url: "{{route('auto-search.customer') }}",
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
    });
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
    $(document).on('keyup','.select_qty,.select_purchase_price,.select_discount_price,#sub_order_discount,#sub_shipping_cost',function(){
        priceCalculation();
    });
    function priceCalculation(){
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
                    // dis = dis.toFixed(2);
                    $('.discount_price_'+row_id).val(dis.toFixed(2));

                    // console.log(total * parseInt($('.discount_price_'+row_id).attr('dis-per'))/100);
                }else{
                    dis = parseFloat($('.discount_price_'+row_id).val()) || 0;
                }

                if($('.tax_'+row_id).attr('is_per') == 1){

                   tax = (total) * parseInt($('.tax_'+row_id).attr('dis-per'))/100;
                    $('.tax_'+row_id).val(tax.toFixed(2));
                    // tax = tax.toFixed(2);
                    // console.log(total * parseInt($('.discount_price_'+row_id).attr('dis-per'))/100);
                }else{
                   tax = parseFloat($('.tax_'+row_id).val()) || 0;
                }
                sub_discount += dis;
                sub_tax += tax;
                p_total += total;
                total_qty +=  parseInt(qty) || 0;
                $('.total_price_'+row_id).val(total.toFixed(2));
            }

        });
         $('#sub_tax_total').html(sub_tax.toFixed(2));
        $('#sub_discount_total').html(sub_discount.toFixed(2));
        $('#total_qty').html(total_qty);
         $('#total_qty_input').val(total_qty);
        $('#total').html(p_total.toFixed(2));
        var total_items = $('table.order-list tbody tr:last').index();
        total_items = parseInt(total_items)+1;
        $('#item').html(total_items);
        $('#item_input').val(total_items);
        $('#subtotal').html(p_total.toFixed(2));
        $('#subtotal_input').val(p_total.toFixed(2));
        var total_discount = sub_discount+parseFloat($('#sub_order_discount').val());
        var shipping_cost= $('#sub_shipping_cost').val();
        var grand_total =parseFloat(sub_tax) + parseFloat(p_total)+(parseFloat(shipping_cost) || 0) - (parseFloat(total_discount) || 0);
        // grand_total = grand_total.toFixed(2);
        $('#order_discount_input').val(total_discount.toFixed(2));
        $('#order_discount').html(total_discount.toFixed(2));
        $('#order_tax_input').val(sub_tax.toFixed(2));
        $('#order_tax').html(sub_tax.toFixed(2));
        shipping_cost= parseFloat(shipping_cost);
        $('#shipping_cost').html(shipping_cost.toFixed(2));
        $('#grand_total_input').val(grand_total.toFixed(2));
        $('#grand_total').html(grand_total.toFixed(2));
    }

    $(document).on('change','#select_product',function(){
        if(this.value != 0){
            var id=$(this).val();
            FillProductOption(id,p_row_no);
            $(this).val(0).trigger('change');
        }else{
            // console.log(this.value);
        }


        // $(this).val(0).trigger('change');
    });
    function FillProductOption(id,row_no,old=0,color_option=null,qty=1){
        console.log("row _ "+old);
         $.ajax({
            url: "{{route('get_product_details_by_id') }}",
            method: 'GET',
            data:{
                old:old,
                type:5,
                qty:qty,
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
                    // var unit_option = new Option(data.unit_name, data.unit_id, true, true);
                    // $('.select_unit_'+row_no).append(unit_option).trigger('change');

                    // if(unit_option){
                    //     $('.select_unit_'+row_no).append(unit_option).trigger('change');
                    // }
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
        '                                <input data-in="'+p_row_no+'" class="form-control select_qty_'+p_row_no+' select_qty touchspin " name="qty[]" value="1" type="number" placeholder="Qty">'+
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
        '                               <input data-in="'+p_row_no+'" class="form-control purchase_price_'+p_row_no+' select_purchase_price " name="per_cost[]" type="text" placeholder="Price">'+
        '                            </td>'+
        '                            <td>'+
        '                               <input class="form-control discount_price_'+p_row_no+' select_discount_price " type="text" placeholder="Discount" name="discount">'+
        '                            </td>'+
        '                            <td>'+
        '                               <input data-in="'+p_row_no+'" type="text" class="form-control total_price_'+p_row_no+'  select_total_price" name="total[]" placeholder="Total">'+
        '                            </td>'+
        '                        </tr>';
        var curlastrow = $('#row_'+arg);
        $(curlastrow).after(myvar);
        initalColor(p_row_no);
        initalSize(p_row_no);
        touchQty(p_row_no);

    }
    @if(old('product_id'))

        @foreach (old('product_id') as $k=>$product_id)


            @php
                $unit=App\Models\Inventory\Unit::find(old('unit')[$k]);
            @endphp
            var unit_option = new Option("{{$unit?->name}}","{{$unit?->id}}", true, true);
            FillProductOption("{{$product_id}}",p_row_no,0,color_option,size_option,unit_option,"{{ old('qty')[$k] }}");
            p_row_no++;
        @endforeach
    @else
    @if($quotation->items->count() > 0)
        @foreach($quotation->items as $k=>$item)
            var unit_option = new Option("{{$item->unit?->name}}","{{$item->unit?->id}}", true, true);
            FillProductOption("{{$item->product_id}}",p_row_no,"{{ $item->id }}",unit_option,"{{ $item->qty }}");
             p_row_no++;
        @endforeach

    @endif
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
     @if(Session::has('errors'))
     @foreach (Session::get('errors') as $error)
        toastr.error("{{ $error }}");
        console.log("{{ $error }}");
     @endforeach

    @endif

    $(document).on('click','.old_delete_item',function(){
        // if(this)
         $(this).parent().parent().parent().append("<input type='hidden' name='delete_item[]' value='"+$(this).attr('data-id')+"'>");
        $(this).parent().parent().remove();
        priceCalculation();
    });
    $(document).on('click','.delete_item',function(){
        $(this).parent().parent().remove();
        priceCalculation();
    });
</script>
@endsection
