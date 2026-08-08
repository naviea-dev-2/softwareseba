@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Purchase Report</title>


@endsection
 @section('content')
        <div class="content-area">
            <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12 ">
                        <div class="row report-title">
                            <h4 class=""><b>Stock Report</b></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one my-4">
                    <div class="col-md-12 col-lg-12 col-sm-12">

                        <div class="search-Section">
                            <form action="">
                            <div class="row">

                                 <input type="hidden" name="per_page" id="h_per_page" value="{{ $per_page }}">
                                <div class="col-md-3">
                                    <label><b>Select Category</b></label>
                                    <select data-in="1" class="form-control" id="select_category" name="category">
                                        <option value="">Category</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label><b>Select Manufacture</b></label>
                                    <select data-in="1" class="form-control" id="select_manufacture" name="manufacture">
                                        <option value="">Manufacture</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label><b>Select Brand</b></label>
                                    <select data-in="1" class="form-control" id="select_brand" name="brand">
                                        <option value="">Brand</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label><b>Select Product</b></label>
                                    <select data-in="1" class="form-control" id="select_product" name="product">
                                        <option value="">Product</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label></label>
                                    <Button type="submit" class="btn btn-primary  mt-3">Search</Button>
                                </div>
                            </div>
                            </form>
                        </div>



                        <br/>
                        <div class="d-flex justify-content-between">
                            <div></div>
                            <div class="d-flex">
                                @if(can_p('stock_report_pdf'))
                                <form action="{{ route('stock_report_pdf') }}" method="GET">
                                    <input type="hidden" value="{{request()->category}}" name="p_category">
                                    <input type="hidden" value="{{request()->product}}" name="p_product">
                                    <input type="hidden" value="{{request()->brand}}" name="p_brand">
                                    <input type="hidden" value="{{request()->manufacture}}" name="p_manufacture">

                                    <button type="submit" class="btn btn-primary px-4 ms-2">PDF</button>
                                </form>
                                @endif
                                @if(can_p('stock_report_print'))
                                <form action="{{ route('stock_report_print') }}" method="GET">

                                    <input type="hidden" value="{{request()->category}}" name="p_category">
                                    <input type="hidden" value="{{request()->product}}" name="p_product">
                                    <input type="hidden" value="{{request()->brand}}" name="p_brand">
                                    <input type="hidden" value="{{request()->manufacture}}" name="p_manufacture">
                                    <button type="submit" class="btn btn-success px-4 ms-2">Print</button>
                                </form>
                                @endif
                                @if(can_p('stock_report_excel'))
                                <form action="{{ route('stock_report_excel') }}" method="GET">

                                    <input type="hidden" value="{{request()->category}}" name="p_category">
                                    <input type="hidden" value="{{request()->product}}" name="p_product">
                                    <input type="hidden" value="{{request()->brand}}" name="p_brand">
                                    <input type="hidden" value="{{request()->manufacture}}" name="p_manufacture">
                                    <button type="submit" class="btn btn-info px-4 ms-2">Excel</button>
                                </form>
                                @endif

                            </div>
                        </div>
                        <br/>
                        <table id="dataTable" class="purchase-list table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Product</th>
                                <th>Purchase Qty</th>
                                <th>Purchase Amount</th>
                                <th>Sale Qty</th>
                                <th>Sale Amount</th>
                                <th>Current Qty</th>
                                <th>Current Amount</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php
                                $row=0;
                                $total_inQty = 0;
                                $total_purchase = 0;
                                $total_outQty = 0;
                                $total_sale = 0;
                                $total_qty = 0;
                                $total_cur_amount = 0;
                            @endphp
                            @foreach($reports as $key=>$report)
                             @php
                                $row++;
                                $total_inQty += $report->inQty;
                                $total_purchase += $report->purchase_total;
                                $total_outQty += $report->outQty;
                                $total_sale += $report->sale_total;
                                $cur_qty = $report->inQty - $report->outQty;
                                $cur_amount = $cur_qty * $report->s_price;
                                $total_qty += $cur_qty;
                                $total_cur_amount += $cur_amount;

                            @endphp
                            <tr>
                                <td>{{$key+1}}</td>

                                <td>{{$report->category_name}}</td>
                                <td>{{$report->brand_name}}</td>
                                <td>{{$report->product_name}}</td>
                                <td>{{$report->inQty}}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{number_format($report->purchase_total, 2)}}</td>
                                <td>{{$report->outQty}}</td>

                                <td>{{ auth()->user()->currency_symbol }}{{number_format($report->sale_total, 2)}}</td>
                                <td>{{ $cur_qty }}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{number_format($cur_amount, 2)}}</td>




                            </tr>
                           @endforeach
                            @if($reports->count())
                            <tr>
                                <td colspan="4"><strong>Total</strong></td>
                                <td>{{ $total_inQty }}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{ round($total_purchase,2) }}</td>
                                <td>{{ $total_outQty }}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{ round($total_sale,2) }} </td>
                                <td>{{ $total_qty }}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{ round($total_cur_amount,2) }} </td>

                            </tr>
                            @endif
                          </tbody>
                        </table>
                        @if($reports->count() > 0)
                        <div class="dtable-footer my-4">
                            <div class="form-group d-flex align-items-center  display-per-page">
                                <label>Per Page </label>
                                <div>
                                    <select name="perPage" id="input_per_page" class="form-control ms-1">

                                        <option @if($per_page == 50) selected @endif value="50">50</option>
                                        <option @if($per_page == 100) selected @endif value="100">100</option>
                                        <option @if($per_page == 200) selected @endif value="200">200</option>
                                        <option @if($per_page == 500) selected @endif value="500">500</option>
                                    </select>
                                </div>
                            </div>
                            @if( $reports->lastPage() > 1)
                            <!-- pagination-start -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <li class="page-item">
                                        @php
                                            $pre_no =$reports->currentPage();
                                            if($pre_no != 1){
                                                $pre_no = $pre_no-1;
                                            }
                                            if(empty($_GET)){
                                                $url = $reports->url($pre_no).'&per_page='.$per_page;
                                            }else{
                                                $url =request()->fullUrl().'&page='.$pre_no;
                                            }

                                        @endphp
                                        <a class="page-link" href="{{ $url }}" aria-label="Previous">	<span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    @for($i=1;$i <= $reports->lastPage();$i++)
                                    <li class="page-item"><a class="page-link {{ $i == $reports->currentPage() ? 'active' : 0 }}" href="{{  empty($_GET) ?  ($reports->url($i).'&per_page='.$per_page) : (request()->fullUrl().'&page='.$i) }}">{{ $i }} </a>
                                    </li>
                                    @endfor

                                    <li class="page-item">
                                        @php
                                            $next_no =$reports->currentPage();
                                            $next_no =$reports->currentPage();
                                            if($next_no != 1){
                                                $next_no = $next_no+1;
                                            }
                                            if(empty($_GET)){
                                                $url = $reports->url($next_no).'&per_page='.$per_page;
                                            }else{
                                                $url =request()->fullUrl().'&page='.$next_no;
                                            }

                                        @endphp
                                        <a class="page-link" href="{{ $url }}" aria-label="Next">	<span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <!-- pagination-end -->
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area End -->
    </div>
</div>
@endsection
@section('script')
<script>
    $(".datepicker").flatpickr();

    $('#input_per_page').on('change',function(){
        $('#h_per_page').val(this.value);
        $('#search_btn').click();
    });
    $(document).find('#select_manufacture').select2({
        placeholder: 'Select Manufacture',
        theme: "bootstrap-5",
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.manufactures')}}',
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
        $('.p_manufacture').val(data.id);
    });
    $(document).find('#select_brand').select2({
        placeholder: 'Select Brand',
        theme: "bootstrap-5",
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.product.brands')}}',
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
        $('.p_brand').val(data.id);
    });
    $(document).find('#select_category').select2({
        placeholder: 'Select Category',
        theme: "bootstrap-5",
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
    }).on('select2:select', function (e) {
        var data = e.params.data;
        $('.p_category').val(data.id);

    });
    $(document).find('#select_product').select2({
        placeholder: 'Select Product',
        theme: "bootstrap-5",
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
                manufacture_id:$('#select_manufacture').val(),
                brand_id:$('#select_brand').val(),
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
        $('.p_product').val(data.id);

    });
    @if(request()->brand)
        @php
            $brand = \App\Models\Inventory\Brand::find(request()->brand);
        @endphp
        @if($brand)
            var brand_option = new Option("{{ $brand->name }}","{{ $brand->id }}", true, true);
            $('#select_brand').append(brand_option).trigger('change');
        @endif
    @endif
    @if(request()->manufacture)
        @php
            $manufacture = \App\Models\Inventory\Manufature::find(request()->manufacture);
        @endphp
        @if($manufacture)
            var manufacture_option = new Option("{{ $manufacture->name }}","{{ $manufacture->id }}", true, true);
            $('#select_manufacture').append(manufacture_option).trigger('change');
        @endif
    @endif
    @if(request()->category)
        @php
            $category = \App\Models\Inventory\Category::find(request()->category);
        @endphp
        @if($category)
            var category_option = new Option("{{ $category->name }}","{{ $category->id }}", true, true);
            $('#select_category').append(category_option).trigger('change');
        @endif
    @endif
    @if(request()->product)
        @php
            $product = \App\Models\Inventory\Product::find(request()->product);
        @endphp
        @if($product)
            var product_option = new Option("{{ $product->product_name }}","{{ $product->id }}", true, true);
            $('#select_product').append(product_option).trigger('change');
        @endif
    @endif
</script>
@endsection
