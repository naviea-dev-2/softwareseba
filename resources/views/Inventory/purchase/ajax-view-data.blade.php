<div class="receipt-header receipt-header-mid print-only" style="padding:10px;">
    <h3 class="text-center">Purchase Invoice</h3>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 text-left">
            <div class="receipt-right">
                <h5>{{$purchase->vendor?->name}} </h5>
                <p style="margin: 0px;"><b>Mobile :</b> +{{$purchase->vendor?->mobile}}</p>
                <p style="margin: 0px;"><b>Email :</b> {{$purchase->vendor?->email}}</p>
                <p style="margin: 0px;"><b>Address :</b> {{$purchase->vendor?->address}}</p>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="receipt-left">
                <h4>Purchase Reference # {{$purchase->reference_no}}</h4>
                <p style="margin: 0px;"><b>Date :</b> {{\Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y')}}</p>
                <p style="margin: 0px;"><b>Status :</b> 
                    @if($purchase->status == 1)
                    Recieved
                    @elseif($purchase->status == 2)
                    Partial
                    @elseif($purchase->status == 3)
                    Pending
                    @else
                    Ordered
                    @endif
                </p>
                <p style="margin: 0px;"><b>Payment Status :</b> 
                    @if($purchase->payment_status == 0)
                    <span style="color:red;">Due</span>
                    @elseif($purchase->payment_status == 1)
                    Partial
                    @else
                    Paid
                    @endif
                </p>
                @if($purchase?->method)
                <p style="margin: 0px;"><b>Payment Method :</b> {{$purchase?->method?->name}} </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="purchase-content" class="modal-body no-print">

    <strong>Date: </strong>{{ date('Y-m-d', strtotime($purchase->purchase_date)) }}<br>
    <strong>Reference : </strong>{{ $purchase->reference_no }}<br>
    <strong>Purchase Status: </strong>
    @if($purchase->status == 1)
        <div class="badge bg-success">Recieved</div>

    @elseif($purchase->status == 2)
    <div class="badge bg-success">Partial</div>

    @elseif($purchase->status == 3)
        <div class="badge bg-danger">Pending</div>

    @else
        <div class="badge bg-danger">Ordered</div>
    @endif
    <br>
    <strong>Payment Status: </strong>
    @if($purchase->payment_status == 0)
        <div class="badge bg-danger">Due</div>

    @elseif($purchase->payment_status == 1)
    <div class="badge bg-info">Partial</div>

    @else
        <div class="badge bgt-success">Paid</div>
    @endif
    <br>
    @if($purchase?->method)
    <strong>Payment Method: </strong> {{$purchase?->method?->name}}<br>
    <strong>Account: </strong> {{$purchase?->account?->account_name}}
    <br>
    @endif
    
   
    <div class="row">
        <div class="col-md-6">
            <strong>From:</strong><br>{{ $purchase->branch?->name }}<br>{{ $purchase->branch?->mobile }}<br>{{ $purchase->branch?->email }}</div>
        <div class="col-md-6">
            <div class="float-right">
                <strong>To:</strong><br>{{ $purchase->vendor?->name }}<br>{{ $purchase->vendor?->email }}<br>{{ $purchase->vendor?->mobile }}<br>{{ $purchase->vendor?->address }}<br>
            </div>
        </div>
    </div>
</div>
          
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
            @endphp
            @foreach ($purchase->items as $k=>$item)
            <tr>
                <td>{{ $k+1 }}</td>
                @if( $item->product->is_variant == 1)
                <td>{{ $item->product->product_name.$item->product->variation_attributes }}</td>
                @else
                <td>{{ $item->product?->product_name }}</td>
                @endif
                {{-- <td>{{ $item->color->name }}</td>
                <td>{{ $item->size->name }}</td> --}}
                <td>{{ $item->qty }}</td>
                <td>{{ $item->unit?->name }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->per_cost,2) }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->discount,2) }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->total,2) }}</td>
            </tr>
             @php
                $p_total += $item->total;
            @endphp
            @endforeach
            <tr class="print-only">
                <td class="text-right" colspan="6">
                    <p>
                        <strong>Sub Total: </strong>
                    </p>
                    <p>
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
                    </p>
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
                <p>
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
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($purchase->paid_amount,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($purchase->due_amount,2) }}</strong>
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
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Shipping Cost</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($purchase->shipping_cost,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Tax</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($purchase->total_tax ,2)}}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Discount</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($purchase->total_discount+$purchase->order_discount,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Total</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($p_total+ $purchase->total_tax+ $purchase->shipping_cost-$purchase->order_discount-$purchase->total_discount,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Paid</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($purchase->paid_amount,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Due</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($purchase->due_amount,2) }}</label></div>
        </div>
    </div>
</div>
<div id="purchase-footer" class="modal-body">
    <p><strong>Note:</strong> {{ $purchase->note }} </p>
    {{-- <strong>{{trans("file.Created By")}}:</strong><br>'+purchase[23]+'<br>'+purchase[24] --}}
</div>
