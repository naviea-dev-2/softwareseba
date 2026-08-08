<div class="receipt-header receipt-header-mid print-only" style="padding:10px;">
    <h3 class="text-center">Quotation</h3>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 text-left">
            <div class="receipt-right">
                <h6> <b>Name :</b> {{$quotation->customer?->name}} </h6>
                <p style="margin: 0px;">  <b>Mobile :</b> +{{$quotation->customer?->mobile}} </p>
                <p style="margin: 0px;">  <b>Email :</b> {{$quotation->customer?->email}} </p>
                <p style="margin: 0px;">  <b>Address :</b> {{$quotation->customer?->address}} </p>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="receipt-left">
                <h6>Quotation Reference # {{$quotation->reference_no}} </h6>
                <p style="margin: 0px;"> <b>Date :</b> {{\Carbon\Carbon::parse($quotation->quotation_date)->format('d M, Y')}} </p>
            </div>
        </div>
    </div>
</div>

<div id="purchase-content" class="modal-body no-print">

    <strong>Date: </strong>{{ date('Y-m-d', strtotime($quotation->quotation_date)) }}<br>
    <strong>Reference : </strong>{{ $quotation->reference_no }}<br>
    <strong>Purchase Status: </strong>
    @if($quotation->status == 1)
        <div class="badge badge-success">Recieved</div>

    @elseif($quotation->status == 2)
    <div class="badge badge-success">Partial</div>

    @elseif($quotation->status == 3)
        <div class="badge badge-danger">Pending</div>

    @else
        <div class="badge badge-danger">Ordered</div>
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <strong>From:</strong><br>{{ $quotation->branch->name }}<br>{{ $quotation->branch->mobile }}<br>{{ $quotation->branch?->email }}</div>
        <div class="col-md-6">
            <div class="float-right">
                <strong>To:</strong><br>{{ $quotation->customer?->name }}<br>{{ $quotation->customer?->email }}<br>{{ $quotation->customer?->mobile }}<br>{{ $quotation->customer?->address }}<br>
            </div>
        </div>
    </div>
</div>
            
<div class="container pt-0">
    <table class="table table-bordered product-purchase-list">
        <thead>
            <th>#</th>
            <th>Product</th>

            <th>Qty</th>
            <th>Unit</th>
            <th>Unit Cost</th>
            <th>Discount</th>
            <th>Sub Total</th>
        </thead>
        {{-- {{ $purchase->items }} --}}
        <tbody>
            @php
                $p_total =0;
            @endphp
            @foreach ($quotation->items as $k=>$item)
            <tr>
                <td>{{ $k+1 }}</td>
                 @if( $item->product->is_variant == 1)
                <td>{{ $item->product->product_name.$item->product->variation_attributes }}</td>
                @else
                <td>{{ $item->product->product_name }}</td>
                @endif

                <td>{{ $item->qty }}</td>
                <td>{{ $item->unit->name }}</td>
                 <td>{{ auth()->user()->currency_symbol }}{{ round($item->per_cost,2) }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->discount,2
                 ) }}</td>
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
                </td>
                <td>
                <p>
                    <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($p_total,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($quotation->shipping_cost,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($quotation->total_tax,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($quotation->order_discount,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($p_total+ $quotation->total_tax+ $quotation->shipping_cost-$quotation->order_discount,2) }}</strong>
                </p>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="no-print">
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Sub Total</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($p_total,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Shipping Cost</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($quotation->shipping_cost,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Tax</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($quotation->total_tax,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Discount</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($quotation->order_discount,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Total</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($p_total+ $quotation->order_tax+ $quotation->shipping_cost-$quotation->order_discount,2) }}</label></div>
        </div>
    </div>
</div>
<div id="purchase-footer" class="modal-body">
    <p><strong>Note:</strong> {{ $quotation->note }} </p>
    {{-- <strong>{{trans("file.Created By")}}:</strong><br>'+purchase[23]+'<br>'+purchase[24] --}}
</div>
