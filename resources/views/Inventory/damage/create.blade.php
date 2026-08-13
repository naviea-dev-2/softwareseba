@extends('inc.master')
@section('head')

<title>Create Damage</title>
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
    .select2-container--default .select2-selection--single .select2-selection__clear{
        height: 35px!important;
    }
</style>
@endsection
@section('content')

<div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;margin-top:0px; padding-bottom:30px;padding-left:10px;">
    <h4>Add New Damage</h4>
    <form action="{{ route('damage.store') }}" method="post">
        @csrf
        <div class="card shadow border">
            {{-- <div class="card-header p-1" style="padding:5px;">
                <h4 class="card-title pb-0 mb-0">
                    <a data-action="collapse">Billing Information</a>
                </h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                    <li><a data-action="collapse" class="rotate"><i class="fas fa-arrow-down"></i></a></li>
                    </ul>
                </div>
            </div> --}}
            <div class="card-content collapse show">
                <div class="card-body pt-3">

                    
                    <div class="row p-1">
                        <div class="col-md-6">
                            <label for=""><b>Damage From</b></label>
                            <input type="text" class=" form-control" name="damage_from" autocomplete="off" value="{{ old('damage_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Invoice Date</b></label>
                            <input type="text" class=" form-control datepicker" value="{{ date('Y-m-d') }}" name="invoice_date" autocomplete="off" name="{{ old('invoice_date') }}">
                        </div>
                        @if(auth()->user()->user_type == 0 || auth()->user()?->role?->is_admin == 1)
                        <div class="col-sm-3">
                            <label for=""><b>Branch</b></label>
                            <input type="hidden" name="h_branch" id="h_branch" value="{{  old('h_branch',auth()->user()?->branch?->name)  }}"/>
                            <select id="select_branch" class="form-control select_branch  {{ $errors->has('branch') ? 'is-invalid' : '' }}" name="branch">
                                <option value="">-- Select One --</option>


                            </select>
                             @if ($errors->has('branch'))
                            <span class="invalid-feedback mb-0">
                            <strong>{{ $errors->first('branch') }}</strong>
                            </span>
                            @endif
                        </div>
                        @else
                            <div class="col-sm-3">
                                <label for=""><b>Branch</b></label>
                                <input type="text" disabled value="{{auth()->user()?->branch?->name}}"/>

                                <input type="hidden" value="{{auth()->user()->branch_id}}" name="branch">
                            </div>
                        @endif
                       
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
                                <input type="text" form="form2" name="product_code_name" id="productcodeSearch" placeholder="Please type product code and select..." class="form-control" />
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
                                <th width="12%">Manufacture</th>
                                <th width="12%">Brand</th>
                                <th width="12%">Qty</th>
                                <th width="13%">Unit</th>
                                <th width="12%">Price</th>
                                <th width="12%">Total Price</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody class="add-ajax-product"></tbody>
                        <tfoot class="tfoot active" style="display:none;">
                            <tr>
                                <th colspan="3">Total</th>
                                <th id="total_qty">
                                    0
                                  
                                    
                                </th>
                                <th></th>
                                <th></th>
                                <th id="grand_price">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="container-fluid">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-8 text-right">
                                    <strong>Note</strong>
                                </label>
                                <textarea name="order_note" class="form-control" cols="30" rows="2">{{ old('order_note') }}</textarea>
                            </div>
                          
                        </div>

                        <div class="col-md-6">
                        </div>


                    </div>
                    <div class="form-group my-2" style="text-align: right;">
                        <button type="submit" class="btn btn-primary" id="submit-btn">Submit</button>
                    </div>
                </div>
                <input type="hidden" name="total_qty" id="total_qty_input"  value="0"/>
                <input type="hidden" name="grand_price" id="grand_price_input" value="0" />

                {{-- <div class="container-fluid">
                    <table class="table table-bordered table-condensed totals">
                        <td><strong>Items</strong>
                            <input type="hidden" name="item" id="item_input">
                            <input type="hidden" name="total_qty" id="total_qty_input">
                            <input type="hidden" name="is_ini_p" id="is_ini_p" value="0">
                            <span class="pull-right" id="item">0.00</span>
                        </td>
                        <td><strong>Total</strong>
                            <input type="hidden" name="total_cost" id="subtotal_input">
                            <input type="hidden" name="total_p_cost" id="p_subtotal_input">
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
                </div> --}}
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
   


    $(document).on('click',function(event) {
        
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
  
    $(".datepicker").flatpickr();
    @if(auth()->user()->user_type == 0 || auth()->user()?->role?->is_admin == 1)
    $('.select_branch').select2({
        placeholder: 'Select Branch',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-xl',
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
        console.log(data);
        $('#h_branch').val(data.text);

    });
    var branch_option = new Option("{{ old('h_branch',auth()->user()?->branch?->name) }}","{{ old('branch',auth()->user()->branch_id)}}", true, true);
    $('.select_branch').append(branch_option).trigger('change');
    @endif

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
        var total_qty =0;
        var total_per_cost =0;
        var grand_price =0;
        if($('.select_product_code ').length > 0){
            $('table.order-list tfoot').show();
        }else{
            $('table.order-list tfoot').hide();
        }
        
        $('.select_product_code ').each(function(){

            if($(this).attr('data-id') != 0){
                var row_id = $(this).attr('data-in');

                var qty = $('.select_qty_'+row_id).val();
                var price = $('.purchase_price_'+row_id).val();
                var total = (parseInt(qty) || 0) * (parseFloat(price) || 0);
                $('.total_price_'+row_id).val(total.toFixed(2));
                total_qty +=  parseInt(qty) || 0;
                total_per_cost +=  parseFloat(price) || 0;
                grand_price += total;
            }

        });
        $('#total_qty').html(total_qty);
        console.log(total_qty);
        $('#total_qty_input').val(total_qty);
        $('#grand_price').html(grand_price);
        $('#grand_price_input').val(grand_price);
        console.log(grand_price);
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
            url: "{{route('damage.get_product_by') }}",
            method: 'GET',
            data:{
                is_sale:1,
                qty:qty,
                old:0,
                id:id,
                row_id:row_no
            },
            success: function(data) {
                if(data.status == "no"){
                    toastr.warning(data.msg);
                    // Toast.fire({
                    //     icon: 'error',
                    //     title: data.msg
                    // });
                }else{
                    if($('.add-ajax-product').find('.pro-row-'+id).length == 0){
                        $('.add-ajax-product').append(data.data_view);

                        initalUnit(row_no);
                        touchQty(row_no);
                        $(".datepicker").flatpickr();
                        if(data.unit_id > 0){
                            var unit_option = new Option(data.unit_name, data.unit_id, true, true);
                            $('.select_unit_'+row_no).append(unit_option).trigger('change');
                        }
                    
                        priceCalculation();
                    }
                }

            }
        });


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
     @foreach (Session::get('cus_errors') as $error)
        toastr.error("{{ $error }}");
        console.log("{{ $error }}");
     @endforeach


    @endif
    $(document).on('click','.delete_item',function(){

        $(this).parent().parent().remove();
        priceCalculation();
    });
   
</script>
@endsection
