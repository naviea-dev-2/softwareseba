@if($deposit_payments->count() > 0)

    <div class="d-flex justify-content-between">
    
        <div class="d-flex">

            <form action="{{ route('user_deposit.list.export') }}" method="GET">
                <input type="hidden" value="pdf" name="type"/>
                <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                <input type="hidden" value="{{ $land }}" name="p_land" class="p_land">
                <input type="hidden" value="{{ $payment_method }}" name="p_payment_method" class="p_payment_method">
                <input type="hidden" value="{{ $land_text }}" name="p_land_text" class="p_land_text">
                <input type="hidden" value="{{ $payment_method_text }}" name="p_payment_method_text" class="p_payment_method_text">

                <input type="hidden" value="pdf" name="type"/>

                <button type="submit" class="btn btn-primary px-4 ms-2" style="padding: 5px;">PDF</button>
            </form>

            <form action="{{ route('user_deposit.list.export') }}" target="__blank" method="GET">
                <input type="hidden" value="print" name="type"/>
                <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                <input type="hidden" value="{{ $land }}" name="p_land" class="p_land">
                <input type="hidden" value="{{ $payment_method }}" name="p_payment_method" class="p_payment_method">
                <input type="hidden" value="{{ $land_text }}" name="p_land_text" class="p_land_text">
                <input type="hidden" value="{{ $payment_method_text }}" name="p_payment_method_text" class="p_payment_method_text">
                <input type="hidden" value="print" name="type"/>


                <button type="submit" class="btn btn-success px-4 ms-2" style="padding: 5px;">Print</button>
            </form>

            <form action="{{ route('user_deposit.list.export') }}" method="GET">
                <input type="hidden" value="excel" name="type"/>
                <input type="hidden" value="{{ $from_date }}" name="p_from_date" class="p_from_date">
                <input type="hidden" value="{{ $to_date }}" name="p_to_date" class="p_to_date">
                <input type="hidden" value="{{ $land }}" name="p_land" class="p_land">
                <input type="hidden" value="{{ $payment_method }}" name="p_payment_method" class="p_payment_method">
                <input type="hidden" value="{{ $land_text }}" name="p_land_text" class="p_land_text">
                <input type="hidden" value="{{ $payment_method_text }}" name="p_payment_method_text" class="p_payment_method_text">
                <input type="hidden" value="excel" name="type"/>

                <button type="submit" class="btn btn-info px-4 ms-2" style="padding: 5px;">Excel</button>
            </form>


        </div>
    </div>
    <br/>
    <div class="responsive-table-div">
        <table id="dataTable" class="purchase-list table table-striped table-bordered" style="width:100%;border: 1px solid #dee2e6;">
            <thead>
                <tr>
                    <th style="width:10%;">SL.</th>
                    <th style="width:10%;">Payment Date</th>
                    <th style="width:10%;">Land Name</th>
                    <th style="width:10%;">Payment Method</th>
                    <th style="width:10%;">Payment Status</th>
                    <th style="width:10%;">Amount</th>
                    <th style="width:10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $current_page = $deposit_payments->currentPage();
                    $last_page = $deposit_payments->lastPage();
                @endphp
                @foreach($deposit_payments as $k=>$deposit_payment)
                
                    <tr>
                        <td>{{ ($k+1) * $current_page }}</td>
                        <td>{{ \Carbon\Carbon::parse($deposit_payment->payment_date)->format("Y-m-d") }}</td>
                        <td>{{$deposit_payment->p_name}}</td>
                        <td>{{$deposit_payment->m_name}}</td>
                        @if($deposit_payment->payment_status == 1)
                            <td><div class="badge bg-success">Paid</div></td>
                        @else
                            <td><div class="badge bg-danger">Not Paid</div></td>
                        @endif
                        <td>{{$deposit_payment->deposit_amount}}</td>
                        
                        @if($deposit_payment->payment_status == 0)
                            <td><a href="javascript:void(0)" data-id="{{ $deposit_payment->id }}" class="btn btn-danger property_del_data"><i class="bx bx-trash"></i></a></td>
                        @else
                            <td></td>
                        @endif

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="dtable-footer my-4">
        <div class="form-group d-flex align-items-center  display-per-page">
            <label>Per Page </label>
            <div>
                <select name="perPage" id="input_per_page" class="form-control ms-1">

                    <option @if($per_page == 10) selected @endif value="10">10</option>
                    <option @if($per_page == 50) selected @endif value="50">50</option>
                    <option @if($per_page == 100) selected @endif value="100">100</option>
                    <option @if($per_page == 200) selected @endif value="200">200</option>
                    <option @if($per_page == 500) selected @endif value="500">500</option>
                </select>
            </div>
        </div>
        @if( $last_page > 1)
        <!-- pagination-start -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    @php
                        $pre_no =$current_page;
                        $last_no =$last_page;
                        if($pre_no != 1){
                            $pre_no = $pre_no-1;
                        }
                        if(empty($_GET)){
                            $url = $deposit_payments->url($pre_no).'&per_page='.$per_page;
                        }else{
                            $url =request()->fullUrl().'&page='.$pre_no;
                        }

                    @endphp
                    <a class="page-link" last_page="{{  $last_no }}" href="{{ $url }}" aria-label="Previous">	<span aria-hidden="true">«</span>
                    </a>
                </li>
                @for($i=1;$i <= $last_page;$i++)
                <li class="page-item"><a class="page-link select_page_{{ $i }} {{ $i == $current_page ? 'active' : 0 }}" href="{{  empty($_GET) ?  ($deposit_payments->url($i).'&per_page='.$per_page) : (request()->fullUrl().'&page='.$i) }}">{{ $i }} </a>
                </li>
                @endfor

                <li class="page-item">
                    @php
                        $next_no =$current_page;

                        if($next_no != $last_no){
                            $next_no = $next_no+1;
                        }
                        if(empty($_GET)){
                            $url = $deposit_payments->url($next_no).'&per_page='.$per_page;
                        }else{
                            $url =request()->fullUrl().'&page='.$next_no;
                        }

                    @endphp
                    <a class="page-link" last_page="{{  $last_no }}" href="{{ $url }}" aria-label="Next">	<span aria-hidden="true">»</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- pagination-end -->
        @endif
    </div>
@else
    <p style="color:red;text-align:center">Data not Found</p>
@endif
