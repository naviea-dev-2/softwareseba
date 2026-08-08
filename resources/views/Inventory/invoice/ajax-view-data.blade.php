<div class="receipt-header receipt-header-mid print-only" style="padding:10px;">
    <h3 class="text-center">Sales Invoice</h3>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 text-left">
            <div class="receipt-right">
                <h6><b>Name:</b>{{$invoice->customer?->name}} </h6>
                <p style="margin: 0px;"><b>Mobile :</b> +{{$invoice->customer?->mobile}}</p>
                <p style="margin: 0px;"><b>Email :</b> {{$invoice->customer?->email}}</p>
                <p style="margin: 0px;"><b>Address :</b> {{$invoice->customer?->address}}</p>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="receipt-left">
                <h6>Sales Reference # {{$invoice->reference_no}}</h6>
                <p style="margin: 0px;"><b>Date :</b> {{\Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y')}}</p>
                <p style="margin: 0px;"><b>Sales Status :</b> 
                    @if($invoice->status == 1)
                    Recieved
                    @elseif($invoice->status == 2)
                    Partial
                    @elseif($invoice->status == 3)
                    Pending
                    @else
                    Ordered
                    @endif
                </p>
                <p style="margin: 0px;"><b>Payment Status :</b> 
                    @if($invoice->payment_status == 0)
                    <span style="color:red;">Due</span>
                    @elseif($invoice->payment_status == 1)
                    Partial
                    @else
                    Paid
                    @endif
                </p>
                <p style="margin: 0px;"><b>Payment Method :</b> {{$invoice?->method?->name}} </p>
                {{-- <p style="margin: 0px;"><b>Account :</b> {{$invoice?->account?->account_name}} </p> --}}
            </div>
        </div>
    </div>
</div>
<div id="purchase-content" class="modal-body no-print">

    <strong>Date: </strong>{{ date('Y-m-d', strtotime($invoice->invoice_date)) }}<br>
    <strong>Reference : </strong>{{ $invoice->reference_no }}<br>
    <strong>Sales Status: </strong>
    @if($invoice->status == 1)
        <div class="badge bg-success">Recieved</div>

    @elseif($invoice->status == 2)
    <div class="badge bg-info">Partial</div>

    @elseif($invoice->status == 3)
        <div class="badge bg-danger">Pending</div>

    @else
        <div class="badge bgt-warning">Ordered</div>
    @endif
    <br>
    <strong>Payment Status: </strong>
    @if($invoice->payment_status == 0)
        <div class="badge bg-danger">Due</div>

    @elseif($invoice->payment_status == 1)
    <div class="badge bg-info">Partial</div>

    @else
        <div class="badge bgt-success">Paid</div>
    @endif
    <br>
    @if($invoice?->method)
    <strong>Payment Method: </strong> {{$invoice?->method?->name}}<br>
    <strong>Account: </strong> {{$invoice?->account?->account_name}}
    <br>
    @endif
    <br>
    <div class="row">

        <div class="col-md-6">
            @if($invoice->branch)
            <strong>From:</strong><br>
            <strong>Name:</strong> {{ $invoice->branch?->name }}<br>
            <strong>Mobile:</strong>{{ $invoice->branch?->mobile }}<br>
            <strong>Email:</strong>{{ $invoice->branch?->email }}
            @endif
        </div>
        <div class="col-md-6">
            @if($invoice->customer)
            <div class="float-right">
                <strong>To:</strong><br>
                <strong>Name:</strong>{{ $invoice->customer?->name }}<br>
                <strong>Email:</strong>{{ $invoice->customer?->email }}<br>
                <strong>Mobile:</strong>{{ $invoice->customer?->mobile }}<br>
                <strong>Address:</strong>{{ $invoice->customer?->address }}<br>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="container pt-0">
    <table class="table table-bordered product-purchase-list">
        <thead>
            <th>#</th>
            <th>Product</th>
            <th>Manufacture</th>
            <th>Brand</th>

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
            @foreach ($invoice->items as $k=>$item)
            @php
                $orginal_product = $item->product->OriginalProduct;
            @endphp
            <tr>
                <td>{{ $k+1 }}</td>
                @if( $item->product->is_variant == 1)
                <td>{{ $item->product?->product_name.$item->product->variation_attributes }}</td>
                @else
                <td>{{ $item->product?->product_name }}</td>
                @endif
                <td>

                    {{ $orginal_product?->manufacture?->name }}
                </td>
                <td>
                {{ $orginal_product?->brand?->name }}
                </td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->unit?->name }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->per_cost,2) }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->discount,2
                 ) }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{ round($item->total,2) }}</td>
                @php
                    $p_total += $item->total;
                @endphp
            </tr>
            @endforeach
            <tr class="print-only">
                <td class="text-right" colspan="8">
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
                    <strong>Paid Amount: </strong>
                </p>
                <p>
                    <strong>Due Amount: </strong>
                </p>
                </td>
                <td>
                <p>
                    <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($p_total,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($invoice->shipping_cost,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($invoice->total_tax,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($invoice->order_discount,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($p_total+ $invoice->total_tax+ $invoice->shipping_cost-$invoice->order_discount,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($invoice->paid_amount,2) }}</strong>
                </p>
                <p>
                    <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($invoice->due_amount,2) }}</strong>
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
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($invoice->shipping_cost,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Tax</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($invoice->total_tax,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Discount</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($invoice->order_discount,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Total</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($p_total+ $invoice->total_tax+ $invoice->shipping_cost-$invoice->order_discount,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Paid Amount</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($invoice->paid_amount,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Due Amount</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{  round($invoice->due_amount,2) }}</label></div>
        </div>
    </div>
</div>
    <div id="purchase-footer" class="modal-body">
        <p><strong>Note:</strong> {{ $invoice->note }} </p>
        {{-- <strong>{{trans("file.Created By")}}:</strong><br>'+purchase[23]+'<br>'+purchase[24] --}}
    </div>
