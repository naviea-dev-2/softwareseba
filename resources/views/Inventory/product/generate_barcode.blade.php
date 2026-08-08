@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Generate Barcode</title>
<style>
    label{
       font-size: 1rem;
    }
    .error-show .select2-container--bootstrap-5 .select2-selection{
        border: 1px solid red;
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
</style>
@endsection
@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="row row-card-one">
            <div class="col-sm-12 ">
                <div class="row report-title">
                    <h4 class=""><b>Generate Barcode</b></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="container pt-0" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">

        <div class="row row-card-one">
            <div class="col-sm-12">
                <!-- start form here -->
                <form id="generate_barcode_form" method="POST" target="_blank" action="{{route('product.generate_barcode')}}" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mt-2">
                                <div class="card-body">
                                    <label><b>Add Product*</b></label>
                                    <div class="search-box input-group">
                                        <button class="btn btn-secondary" type="button"><i class="bx bx-barcode"></i></button>
                                        <input type="text" form="form2" name="product_code_name" id="productcodeSearch" placeholder="Please type product code or name and select..." class="form-control" />
                                        <div style="    width: 100%;" id="auto-serach-res-f"></div>
                                    </div>
                                </div>
                                <table class="table table-hover order-list">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Qunatity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="add_product_res">

                                        @if ($bar_product)
                                        @if($bar_product->variations->count())
                                        <tr class="cart-product-{{$bar_product->id}}">
                                            <td>{{$bar_product->product_name}}(all)</td>
                                            <td>{{$bar_product->product_code}}</td>
                                            <td><input name="products[{{$bar_product->id}}]" type="number" value="1" class="form-control"/></td>
                                            <td><a class="delete_item" href="javascript:void(0);"><i style="color:red;" class="bx bx-trash"></i></a></td>
                                        </tr>
                                        @else
                                        <tr class="cart-product-{{$bar_product->id}}">
                                            <td>{{$bar_product->product_name}}</td>
                                            <td>{{$bar_product->product_code}}</td>
                                            <td><input name="products[{{$bar_product->id}}]" type="number" value="1" class="form-control"/></td>
                                            <td><a class="delete_item" href="javascript:void(0);"><i style="color:red;" class="bx bx-trash"></i></a></td>
                                        </tr>
                                        @endif
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div style="align-items: center;display: flex;gap: 5px;" class="mt-3">
                                        <input id="name_check" class="m-menu" type="checkbox" checked value="name" name="name_check" >
                                        <label for="name_check"><strong>Name</strong></label>
                                    </div>
                                    <div style="align-items: center;display: flex;gap: 5px;" class="mt-3">
                                        <input id="code_check" class="m-menu" type="checkbox" checked value="code" name="code_check" >
                                        <label for="code_check"><strong>Code</strong></label>
                                    </div>
                                    <div style="align-items: center;display: flex;gap: 5px;" class="mt-3">
                                        <input id="price_check" class="m-menu" type="checkbox" checked value="price" name="price_check" >
                                        <label for="price_check"><strong>Price</strong></label>
                                    </div>
                                    <div style="align-items: center;display: flex;gap: 5px;" class="mt-3">
                                        <input id="dis_price_check" class="m-menu" type="checkbox" value="dis_price" name="dis_price_check" >
                                        <label for="dis_price_check"><strong>Discount Price</strong></label>
                                    </div>
                                    <div class="">
                                        <label for=""><b>Size</b> *</label>
                                        <select class="form-control" name="size">
                                            {{-- <option value="">-- Select One --</option> --}}
                                            <option value="row">Row</option>
                                            <option value="single">Single</option>
                                        </select>
                                    </div>
                                    <div class="mt-2">
                                        <label for=""><strong>Box Size</strong></label>
                                        <input class="form-control" class="m-menu" type="number" value="150" name="box_width" >

                                    </div>
                                    <div class="row mt-1">

                                        {{-- <div class="col-sm-3">
                                            <br/>
                                            <button class="btn btn-sm btn-primary">
                                                <i class="fa fa-save pr-2"></i>Preview
                                            </button>
                                        </div> --}}
                                        {{-- <div class="col-sm-3"> --}}

                                            <button class="btn btn-sm btn-primary mt-2">
                                                <i class="fa fa-save pr-2"></i>Print
                                            </button>
                                        {{-- </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </form>

            </div>
        </div>
    </div>








</div>
@endsection
@section('script')


<script>
    var p_row_no = 0;
    @if ($bar_product)
    p_row_no = 1;
    @endif
    var auto_serach_product_modal=0;
    var is_barcode = 0;
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
                    console.log('is_barcode',is_barcode);
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
        if($('#add_product_res').find('.cart-product-'+product.p_id).length == 0){
            var res =`<tr class="cart-product-${product.p_id}">
                    <td>${product.v_name}</td>
                    <td>${product.code}</td>
                    <td><input name="products[${product.p_id}]" type="number" value="1" class="form-control"/></td>
                    <td><a class="delete_item" href="javascript:void(0);"><i style="color:red;" class="bx bx-trash"></i></a></td>
                </tr>`;
            $('#add_product_res').append(res);
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
    $(document).on('click','.delete_item',function(){
        $(this).parent().parent().remove();
        p_row_no--;
    });
    $("#generate_barcode_form").on('submit', function(){
        if(p_row_no == 0){
            alert('please add Product');
            return false;
        }

    });
</script>
@endsection
