<div class="receipt-header receipt-header-mid print-only" style="padding:10px;">
    <h3 class="text-center">Purchase Return Invoice</h3>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 text-left">
            <div class="receipt-right">
                <h5>{{$purchase_return->vendor?->name}} </h5>
                <p style="margin: 0px;"><b>Mobile :</b> +{{$purchase_return->vendor?->mobile}}</p>
                <p style="margin: 0px;"><b>Email :</b> {{$purchase_return->vendor?->email}}</p>
                <p style="margin: 0px;"><b>Address :</b> {{$purchase_return->vendor?->address}}</p>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="receipt-left">
                <h6>Purchase Return Reference # {{$purchase_return->reference_no}}</h6>
                <p style="margin: 0px;"><b>Date :</b> {{\Carbon\Carbon::parse($purchase_return->return_date)->format('d M, Y')}}</p>
                <p style="margin: 0px;"><b>Status :</b> 
                    @if($purchase_return->status == 1)
                    Recieved
                    @elseif($purchase_return->status == 2)
                    Partial
                    @elseif($purchase_return->status == 3)
                    Pending
                    @else
                    Ordered
                    @endif
                </p>
                <p style="margin: 0px;"><b>Payment Status :</b> 
                    @if($purchase_return->payment_status == 0)
                    <span style="color:red;">Due</span>
                    @elseif($purchase_return->payment_status == 1)
                    Partial
                    @else
                    Paid
                    @endif
                </p>
                @if($purchase_return?->method)
                <p style="margin: 0px;"><b>Payment Method :</b> {{$purchase_return?->method?->name}} </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="purchase-content" class="modal-body no-print">

    <strong>Date: </strong>{{ date('d/m/Y', strtotime($purchase_return->return_date)) }}<br>
    <strong>Reference : </strong>{{ $purchase_return->reference_no }}<br>
    <strong>Status: </strong>
    @if($purchase_return->status == 1)
        <div class="badge bg-success">Recieved</div>

    @elseif($purchase_return->status == 2)
    <div class="badge bg-success">Partial</div>

    @elseif($purchase_return->status == 3)
        <div class="badge bg-danger">Pending</div>

    @else
        <div class="badge bg-danger">Ordered</div>
    @endif
    <br>
    <strong>Payment Status: </strong>
    @if($purchase_return->payment_status == 0)
        <div class="badge bg-danger">Due</div>

    @elseif($purchase_return->payment_status == 1)
    <div class="badge bg-info">Partial</div>

    @else
        <div class="badge bgt-success">Paid</div>
    @endif
    <br>
    @if($purchase_return?->method)
    <strong>Payment Method: </strong> {{$purchase_return?->method?->name}}<br>
    <strong>Account: </strong> {{$purchase_return?->account?->account_name}}
    <br>
    @endif
    <br><br>
    <div class="row">
        <div class="col-md-6">
            <strong>From:</strong><br>{{ $purchase_return->branch?->name }}<br>{{ $purchase_return->branch?->mobile }}<br>{{ $purchase_return->branch?->email }}</div>
        <div class="col-md-6">
            <div class="float-right">
                <strong>To:</strong><br>{{ $purchase_return->vendor?->name }}<br>{{ $purchase_return->vendor?->email }}<br>{{ $purchase_return->vendor?->mobile }}<br>{{ $purchase_return->vendor?->address }}<br>
            </div>
        </div>
    </div>
</div>
            <br>
<div class="container pt-0">
    <table class="table table-bordered product-purchase-list">
        <thead>
            <th>#</th>
            <th>Product</th>
            {{-- <th>Color</th>
            <th>Size</th> --}}
            <th>Qty</th>
            <th>Unit</th>
            <th>Unit Cost</th>
            <th>Discount</th>
            <th>Sub Total</th>
        </thead>
        {{-- {{ $purchase->items }} --}}
        <tbody>
            @php
                $p_total=0;
                $p_total_dis=0;
                $p_total_tax=0;
            @endphp
            @foreach ($purchase_return->items as $k=>$item)
            <tr>
                <td>{{ $k+1 }}</td>
                <td>{{ $item->product->product_name }}({{ $item->product->product_code }})</td>
             
                <td>{{ $item->qty }}</td>
                <td>{{ $item->unit?->name }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->per_cost,2) }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->discount,2) }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->total,2) }}</td>
            </tr>
             @php
                $p_total += $item->total;
                $p_total_dis += $item->discount;
                $p_total_tax += $item->tax;
            @endphp
            @endforeach
            <tr class="print-only">
                <td class="text-right" colspan="6">
                    <p>
                        <strong> Total: </strong>
                    </p>
                    {{-- <p>
                        <strong>Shipping Cost: </strong>
                    </p>
                    <p>
                        <strong>Tax: </strong>
                    </p>
                    <p>
                        <strong>Discount: </strong>
                    </p>
                    <p>
                        <strong>Total: </strong>
                    </p> --}}
                    <p>
                        <strong>Paid: </strong>
                    </p>
                    <p>
                        <strong>Due: </strong>
                    </p>
                </td>
                <td>
                <p>
                    <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($p_total,2) }}</strong>
                </p>
                {{-- <p>
                    <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($purchase->shipping_cost,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($purchase->total_tax,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($purchase->order_discount,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($p_total+ $purchase->total_tax+ $purchase->shipping_cost-$purchase->order_discount,2) }}</strong>
                </p> --}}
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($purchase_return->paid_amount,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($purchase_return->due_amount,2) }}</strong>
                </p>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="no-print">
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Sub Total</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($p_total,2) }}</label></div>
        </div>

        {{-- <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Tax</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ $purchase_return->order_tax }}</label></div>
        </div> --}}
        {{-- <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Discount</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ $purchase_return->order_discount }}</label></div>
        </div> --}}
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Total</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($p_total+ $p_total_tax-$p_total_dis,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Paid</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($purchase_return->paid_amount,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Due</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($purchase_return->due_amount,2) }}</label></div>
        </div>
    </div>
</div>
    <div id="purchase-footer" class="modal-body">
        <p><strong>Note:</strong> {{ $purchase_return->note }} </p>
        {{-- <strong>{{trans("file.Created By")}}:</strong><br>'+purchase[23]+'<br>'+purchase[24] --}}
    </div>
