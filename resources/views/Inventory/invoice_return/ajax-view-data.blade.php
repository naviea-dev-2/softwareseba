<div class="receipt-header receipt-header-mid print-only" style="padding:10px;">
    <h3 class="text-center">Sales Return Invoice</h3>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 text-left">
            <div class="receipt-right">
                <h6> <b>Name :</b> {{$invoice_return->customer?->name}} </h6>
                <p style="margin: 0px;">  <b>Mobile :</b> +{{$invoice_return->customer?->mobile}} </p>
                <p style="margin: 0px;">  <b>Email :</b> {{$invoice_return->customer?->email}} </p>
                <p style="margin: 0px;">  <b>Address :</b> {{$invoice_return->customer?->address}} </p>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="receipt-left">
                <h6>Sales Return Reference # {{$invoice_return->reference_no}} </h6>
                <p style="margin: 0px;"> <b>Date :</b> {{\Carbon\Carbon::parse($invoice_return->invoice_date)->format('d M, Y')}} </p>
                <p style="margin: 0px;"><b>Sales Status :</b> 
                    @if($invoice_return->status == 1)
                    Recieved
                    @elseif($invoice_return->status == 2)
                    Partial
                    @elseif($invoice_return->status == 3)
                    Pending
                    @else
                    Ordered
                    @endif
                </p>
                <p style="margin: 0px;"><b>Payment Status :</b> 
                    @if($invoice_return->payment_status == 0)
                    <span style="color:red;">Due</span>
                    @elseif($invoice_return->payment_status == 1)
                    Partial
                    @else
                    Paid
                    @endif
                </p>
                <p style="margin: 0px;"><b>Payment Method :</b> {{$invoice_return?->method?->name}} </p>
            </div>
        </div>
    </div>
</div>

<div id="purchase-content" class="modal-body no-print">

    <strong>Date: </strong>{{ date('d/m/Y', strtotime($invoice_return->return_date)) }}<br>
    <strong>Reference : </strong>{{ $invoice_return->reference_no }}<br>
    <strong>Status: </strong>
    @if($invoice_return->status == 1)
        <div class="badge bg-success">Recieved</div>

    @elseif($invoice_return->status == 2)
    <div class="badge bg-info">Partial</div>

    @elseif($invoice_return->status == 3)
        <div class="badge bg-danger">Pending</div>

    @else
        <div class="badge bg-warning">Ordered</div>
    @endif
    <br>
    <strong>Payment Status: </strong>
    @if($invoice_return->payment_status == 0)
        <div class="badge bg-danger">Due</div>

    @elseif($invoice_return->payment_status == 1)
    <div class="badge bg-info">Partial</div>

    @else
        <div class="badge bg-success">Paid</div>
    @endif
    <br>
    @if($invoice_return?->method)
    <strong>Payment Method: </strong> {{$invoice_return?->method?->name}}<br>
    <strong>Account: </strong> {{$invoice_return?->account?->account_name}}
    <br>
    @endif
    <br><br>
    <div class="row">
        <div class="col-md-6">
            <strong>From:</strong><br>{{ $invoice_return->branch?->name }}<br>{{ $invoice_return->branch?->mobile }}<br>{{ $invoice_return->branch?->email }}
        </div>
        <div class="col-md-6">
            <div class="float-right">
                <strong>To:</strong><br>{{ $invoice_return->customer?->name }}<br>{{ $invoice_return->vendor?->email }}<br>{{ $invoice_return->vendor?->mobile }}<br>{{ $invoice_return->vendor?->address }}<br>
            </div>
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
                $p_total=0;
                $p_total_dis=0;
                $p_total_tax=0;
            @endphp
            @foreach ($invoice_return->items as $k=>$item)
                @php
                    $orginal_product = $item->product->OriginalProduct;
                @endphp
                <tr>
                    <td>{{ $k+1 }}</td>
                    <td>{{ $item->product?->product_name }}({{ $item->product?->product_code }})</td>
                    <td>{{ $orginal_product->manufacture?->name }}</td>
                    <td>{{ $orginal_product->brand?->name }}</td>
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
                <td class="text-right" colspan="8">
                    {{-- <p>
                        <strong>Sub Total: </strong>
                    </p> --}}
                
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
                    {{-- <p>
                        <strong><i class="fa fa-inr"></i> {{ auth()->user()->currency_symbol }}{{  round($p_total,2) }}</strong>
                    </p> --}}
                
                    <p>
                        <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($p_total,2) }}</strong>
                    </p>
                    <p>
                        <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($invoice_return->paid_amount,2) }}</strong>
                    </p>
                    <p>
                        <strong><i class="fa fa-inr"></i>{{ auth()->user()->currency_symbol }}{{  round($invoice_return->due_amount,2) }}</strong>
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
            <div style="width:20%;text-align:right;"><label for="">{{ $invoice_return->order_tax }}</label></div>
        </div> --}}
        {{-- <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Discount</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ $invoice_return->order_discount }}</label></div>
        </div> --}}
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Total</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($p_total+ $p_total_tax-$p_total_dis,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Paid</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($invoice_return->paid_amount,2) }}</label></div>
        </div>
        <div class="d-flex">
            <div style="width:80%;text-align:right;"><label for="">Due</label></div>
            <div style="width:20%;text-align:right;"><label for="">{{ auth()->user()->currency_symbol }}{{ round($invoice_return->due_amount,2) }}</label></div>
        </div>
    </div>
</div>
<div id="purchase-footer" class="modal-body">
    <p> <strong>Note:</strong> {{ $invoice_return->note }} </p>
    {{-- <strong>{{trans("file.Created By")}}:</strong><br>'+purchase[23]+'<br>'+purchase[24] --}}
</div>
