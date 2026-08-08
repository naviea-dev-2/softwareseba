@extends('inc.master')

@section('head')
<title>Print</title>
<style>
   body{
background:#eee;
margin-top:20px;
}
.text-danger strong {
        	color: #9f181c;
		}
		.receipt-main {
			background: #ffffff none repeat scroll 0 0;
			/* border-bottom: 12px solid #333333; */
			/* border-top: 12px solid #9f181c; */
			/* margin-top: 50px; */
			/* margin-bottom: 50px; */
			padding: 40px 30px !important;
			position: relative;
			box-shadow: 0 1px 21px #acacac;
			color: #333333;
			font-family: open sans;
		}
		.receipt-main p {
			color: #333333;
			font-family: open sans;
			line-height: 1.42857;
		}
		.receipt-footer h1 {
			font-size: 15px;
			font-weight: 400 !important;
			margin: 0 !important;
		}
		.receipt-main::after {
			background: #414143 none repeat scroll 0 0;
			content: "";
			height: 5px;
			left: 0;
			position: absolute;
			right: 0;
			top: -13px;
		}
		.receipt-main thead {
			background: #414143 none repeat scroll 0 0;
		}
		.receipt-main thead th {
			/* color:#fff; */
		}
        /* .receipt-right{
            text-align: center;
        }
		.receipt-right h5 {
			font-size: 16px;
			font-weight: bold;
			margin: 0 0 7px 0;
		}
		.receipt-right p {
			font-size: 12px;
			margin: 0px;
		}
		.receipt-right p i {
			text-align: center;
			width: 18px;
		} */
		.receipt-main td {
			padding: 9px 20px !important;
		}
		.receipt-main th {
			padding: 13px 20px !important;
		}
		.receipt-main td {
			font-size: 13px;
			font-weight: initial !important;
		}
		.receipt-main td p:last-child {
			margin: 0;
			padding: 0;
		}	
		.receipt-main td h2 {
			font-size: 20px;
			font-weight: 900;
			margin: 0;
			text-transform: uppercase;
		}
		.receipt-header-mid .receipt-left h1 {
			font-weight: 100;
			margin: 34px 0 0;
			text-align: right;
			text-transform: uppercase;
		}
		.receipt-header-mid {
			/* margin: 24px 0; */
			overflow: hidden;
		}
		
		#container {
			background-color: #dcdcdc;
		}
</style>

@endsection
 @section('content')
    <div class="content-area">
       
        <div class="row">
            
            <div class="receipt-main" id="print-details">
               
                <div class="receipt-header">
                    <div class="receipt-left">
                        <img class="img-responsive" alt="iamgurdeeposahan" src="{{auth()->user()->business->business_logo_show}}" style="left: 10px;top: 5px;position: absolute;width: 71px;height: 71px; border-radius: 43px;">
                    </div>
                    <div class="receipt-right" style="text-align: center;">
                        <h6 style="font-size: 16px;font-weight: bold;margin: 0 0 7px 0;">{{ auth()->user()->business->business_name }}</h6>
                        <p style="font-size: 12px;margin: 0px;">+{{ auth()->user()->business->mobile_number }}<i class="fa fa-phone"></i></p>
                        <p style="font-size: 12px;margin: 0px;">{{ auth()->user()->business->email }}<i class="fa fa-envelope-o"></i></p>
                        <p style="font-size: 12px;margin: 0px;">{{ auth()->user()->business->country?->name }} <i class="fa fa-location-arrow"></i></p>
                    </div>
                   
                </div>
                
                <h3 class="text-center">Sales Invoice</h3>
                <div class="receipt-header receipt-header-mid">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 text-left">
                            <div class="receipt-right">
                                <h6>{{$invoice->customer?->name}} </h6>
                                <p style="margin: 0px;"><b>Mobile :</b> +{{$invoice->customer?->mobile}}</p>
                                <p style="margin: 0px;"><b>Email :</b> {{$invoice->customer?->email}}</p>
                                <p style="margin: 0px;"><b>Address :</b> {{$invoice->customer?->address}}</p>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="receipt-left">
                                <h6 style="margin: 0px;"> Sales Reference # {{$invoice->reference_no}}</h6>
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
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Manufacture</th>
                                <th>Brand</th>

                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Unit Cost</th>
                                <th>Discount</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
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
                                    <td>{{ $item->product->product_name.$item->product->variation_attributes }}</td>
                                    @else
                                    <td>{{ $item->product->product_name }}</td>
                                    @endif
                                    <td>
                    
                                        {{ $orginal_product?->manufacture?->name }}
                                    </td>
                                    <td>
                                    {{ $orginal_product?->brand?->name }}
                                    </td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->unit->name }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($item->per_cost,2) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($item->discount,2
                                    ) }}</td>
                                    <td>{{ auth()->user()->currency_symbol }}{{ round($item->total,2) }}</td>
                                    @php
                                        $p_total += $item->total;
                                    @endphp
                                </tr>
                            @endforeach
                            <tr>
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
                            {{-- <tr>
                                
                                <td class="text-right"><h2><strong>Total: </strong></h2></td>
                                <td class="text-left text-danger"><h2><strong><i class="fa fa-inr"></i> 31.566/-</strong></h2></td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
                
                <div class="row">
                    <div class="receipt-header receipt-header-mid receipt-footer">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-left">
                            <div class="receipt-right">
                                <p><b>Note :</b>{{$invoice->note}}</p>
                                
                            </div>
                        </div>
                        {{-- <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="receipt-left">
                                <h1>Stamp</h1>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <div class="btn-action">
                    <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="bx bx-printer"></i> Print </button>
                    <a href="{{route('invoice.create')}}" style="border:1px solid #c4c4c4" class="btn btn-default btn-sm d-print-none">Back</a>
                </div>
                
            </div>    
        </div>
          
    </div>

@endsection
@section('script')
<script>
    $("#print-btn").on("click", function(){
          var divToPrint=document.getElementById('print-details');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} .modal-content{width:  1000px!important;max-width: 1000px; } }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
        //   newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},500);
    });
</script>
@endsection
