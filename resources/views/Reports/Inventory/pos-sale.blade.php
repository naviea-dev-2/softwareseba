@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>POS Sale Report</title>


@endsection
 @section('content')
        <div class="content-area">
            <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12 ">
                        <div class="row report-title">
                            <h4 class=""><b>POS Sale Report</b></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container" style="box-shadow: 0 0 2px gray;border-top:4px solid gray;">
                <div class="row row-card-one my-4">
                    <div class="col-md-12 col-lg-12 col-sm-12">

                        <div class="search-Section">
                            <form action="">
                                 <input type="hidden" name="per_page" id="h_per_page" value="{{ $per_page }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for=""><b>From Date</b></label>
                                    <input type="text" class=" form-control  datepicker" value="{{ $from_date }}" name="from_date" autocomplete="off" required>
                                </div>
                                <div class="col-md-3">
                                    <label for=""><b>To Date</b></label>
                                    <input type="text" class=" form-control  datepicker" value="{{ $to_date }}" name="to_date" autocomplete="off" required>
                                </div>
                                
                                <div class="col-sm-3">
                                    <label for=""><b>Customer</b></label>

                                    <select id="select_vendor" class="form-control " name="customer">
                                        <option value="">-- Select One --</option>


                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><b>Select Category</b></label>
                                    <select data-in="1" class="form-control" id="select_category" name="category">
                                        <option value="">Category</option>
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
                                @if(can_p('pos_sale_report_pdf'))
                                <form action="{{ route('pos_sale_report_pdf') }}" method="GET">
                                    <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                    <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                    <input type="hidden" value="{{request()->customer}}" name="p_customer" class="p_vendor">
                                    <input type="hidden" value="{{request()->category}}" name="p_category" class="p_category">
                                    <input type="hidden" value="{{request()->product}}" name="p_product" class="p_product">
                     
                                    <button type="submit" class="btn btn-primary px-4 ms-2">PDF</button>
                                </form>
                                @endif
                                @if(can_p('pos_sale_report_print'))
                                <form action="{{ route('pos_sale_report_print') }}" method="GET">
                                    <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                    <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                    <input type="hidden" value="{{request()->customer}}" name="p_customer" class="p_vendor">
                                    <input type="hidden" value="{{request()->category}}" name="p_category" class="p_category">
                                    <input type="hidden" value="{{request()->product}}" name="p_product" class="p_product">
                     
                                    <button type="submit" class="btn btn-success px-4 ms-2">Print</button>
                                </form>
                                @endif
                                @if(can_p('pos_sale_report_excel'))
                                <form action="{{ route('pos_sale_report_excel') }}" method="GET">
                                    <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                                    <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                                    <input type="hidden" value="{{request()->customer}}" name="p_customer" class="p_vendor">
                                    <input type="hidden" value="{{request()->category}}" name="p_category" class="p_category">
                                    <input type="hidden" value="{{request()->product}}" name="p_product" class="p_product">
          
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
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Customer</th>
                                {{-- <th>Brand</th> --}}
                                <th>Category</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>TAX</th>
                                <th>Amount</th>
                                 <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php
                                $total_qty = 0;
                                $total_per_cost = 0;
                                $total_discount = 0;
                                $total_tax = 0;
                                $total_sub = 0;
                            @endphp
                            @foreach($reports as $key=>$report)
                             @php
                                $total_qty += $report->qty;
                                $total_per_cost += $report->per_cost;
                                $total_discount += $report->discount;
                                $total_tax += $report->tax;
                                $total_sub += $report->total;
                            @endphp
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ date('Y-m-d', strtotime($report->sale_date)) }}</td>

                                <td>{{$report->reference_no}}</td>
                                <td>{{$report->customer_name}}</td>
                                <td>{{$report->cat_name}}</td>
                                <td>{{$report->product_name}}</td>
                                <td>{{$report->qty}}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{number_format($report->per_cost, 2)}}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{number_format($report->discount, 2)}}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{number_format($report->tax, 2)}}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{number_format($report->total, 2)}}</td>
                                <td>
                                    @if($report->status == 1)
                                       <div class="badge bg-secondary">Recieved</div>

                                    @elseif($report->status == 2)
                                    <div class="badge bg-secondary">Partial</div>

                                    @elseif($report->status == 3)
                                     <div class="badge bg-danger">Pending</div>

                                    @else
                                    <div class="badge bg-danger">Ordered</div>
                                    @endif
                                </td>
                                {{-- <td>
                                    @if($report->payment_status == 1)
                                        <div class="badge bg-danger">Due</div>
                                    @else
                                        <div class="badge bg-secondary">Paid</div>
                                    @endif
                                </td> --}}


                            </tr>
                            @endforeach
                            @if($reports->count())
                            <tr>
                                <td colspan="6"><strong>Total</strong></td>
                                <td>{{ $total_qty }}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{ round($total_per_cost,2) }}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{ round($total_discount,2) }}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{ round($total_tax,2) }}</td>
                                <td>{{ auth()->user()->currency_symbol }}{{ round($total_sub,2) }}</td>
                                <td></td>
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
     $('.from_date').on('change',function(){
        $('.p_form_date').val(this.value);
    });
    $('.to_date').on('change',function(){
        $('.p_to_date').val(this.value);
    });
    $('#input_per_page').on('change',function(){
        $('#h_per_page').val(this.value);
        $('#search_btn').click();
    });
    $(document).find('#select_vendor').select2({
        placeholder: 'Select Customer',
        theme: "bootstrap-5",
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.customer')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
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
        $('.p_customer').val(data.id);

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
    @if(request()->customer)
        @php
            $customer = \App\Models\Inventory\Customer::find(request()->customer);
        @endphp
        @if($customer)
            var customer_option = new Option("{{ $customer->name }}","{{ $customer->id }}", true, true);
            $('#select_vendor').append(customer_option).trigger('change');
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
    @if(auth()->user()->business->business_type_id == 15)
        @if(array_search('hr-payroll',load_pack_option()) != false) 
            $('#select_dsr').select2({
                placeholder: 'Select DSR',
                theme: "bootstrap-5",
                allowClear: true,
                width:'100%',
                dropdownAutoWidth : true,
                containerCssClass: 'select-sm',
                ajax: {
                    url: '{{route('select2.dsr_employee')}}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
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
            @if(request()->dsr)
                @php
                    $dsr = \App\Models\Hr\Employee::find(request()->dsr);
                @endphp
                @if($dsr)
                    var dsr_option = new Option("{{ $dsr->employee_name }}","{{ $dsr->id }}", true, true);
                    $('#select_dsr').append(dsr_option).trigger('change');
                @endif
            @endif
           

            $('#select_asr').select2({
                theme: "bootstrap-5",
                placeholder: 'Select ASR',
                allowClear: true,
                width:'100%',
                dropdownAutoWidth : true,
                containerCssClass: 'select-sm',
                ajax: {
                    url: '{{route('select2.asr_employee')}}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
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
            @if(request()->asr)
                @php
                    $asr = \App\Models\Hr\Employee::find(request()->asr);
                @endphp
                @if($asr)
                    var asr_option = new Option("{{ $asr->employee_name }}","{{ $asr->id }}", true, true);
                    $('#select_asr').append(asr_option).trigger('change');
                @endif
            @endif
           
            $('#select_driver').select2({
                theme: "bootstrap-5",
                placeholder: 'Select Driver',
                allowClear: true,
                width:'100%',
                dropdownAutoWidth : true,
                containerCssClass: 'select-sm',
                ajax: {
                    url: '{{route('select2.driver_employee')}}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
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
            @if(request()->driver)
                @php
                    $driver = \App\Models\Hr\Employee::find(request()->driver);
                @endphp
                @if($driver)
                    var driver_option = new Option("{{ $driver->employee_name }}","{{ $driver->id }}", true, true);
                    $('#select_driver').append(driver_option).trigger('change');
                @endif
            @endif
           
        @endif
    @endif
</script>
@endsection
