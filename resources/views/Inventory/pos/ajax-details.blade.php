<div class="print-data">
    <div class="mt-4 mb-4 text-black text-center">
        <img src="{{auth()->user()->business->business_logo_show}}" alt="" width="100px">
    </div>
    <div class="mt-4 mb-4 text-black text-center" style="font-size: 24px; font-weight: 600;">
        <h6 style="font-size: 16px;font-weight: bold;margin: 0 0 7px 0;">{{ auth()->user()->business->business_name }}</h6>
        <p style="font-size: 12px;margin: 0px;">+{{ auth()->user()->business->mobile_number }}<i class="fa fa-phone"></i></p>
        <p style="font-size: 12px;margin: 0px;">{{ auth()->user()->business->email }}<i class="fa fa-envelope-o"></i></p>
        <p style="font-size: 12px;margin: 0px;">{{ auth()->user()->business->country?->name }} <i class="fa fa-location-arrow"></i></p>
    </div>
    <section class="product-border">
        <div style="margin-bottom: 4px;">
            <span class="fw-bold me-2">Date:</span><span>{{date('Y-m-d', strtotime($pos_sale->invoice_date))}}</span>
        </div>
        <div style="margin-bottom: 4px;">
            <span class="fw-bold me-2">Address:</span><span>{{$pos_sale->customer?->address}}</span>
        </div>
        <div style="margin-bottom: 4px;">
            <span class="fw-bold me-2">Phone:</span><span>{{$pos_sale->customer?->mobile}}</span>
        </div>
        <div>
            <span class="fw-bold me-2">Customer:</span><span>{{$pos_sale->customer?->name}}</span>
        </div>
    </section>
    <section class="mt-3">
        @foreach ($pos_sale->items as $item)
        <div>
            @if($item->product?->is_variant == 0)
            <div class="p-0">{{$item->product?->product_name}}
                {!! $item->product?->variation_attributes2 !!}
                @if($item->product?->product_code)
                <span>({{$item->product?->product_code}})</span>
                @endif
            </div>
            @else
            <div class="p-0">{{$item->product?->product_name}}
                @if($item->product?->product_code)
                <span>({{$item->product?->product_code}})</span>
                @endif
            </div>
            <div class="product-border">
                <div class="border-0 d-flex justify-content-between">
                    <span class="text-black">{{$item->qty}} {{$item->unit?->name}} X $ {{$item->per_cost}}</span>
                    <span class="text-end">$ {{$item->total}}</span>
                </div>
            </div>
            @endif
        </div>
        @endforeach

    </section>
    <section class="mt-3 product-border">
        <div class="d-flex">
            <div style="font-weight: 500; color: rgb(0, 0, 0);">Total Amount:</div>
            <div class="text-end ms-auto">$ {{$pos_sale->total_cost}}</div>
        </div>
        <div class="d-flex">
            <div style="font-weight: 500; color: rgb(0, 0, 0);">Order Tax: </div>
            <div class="text-end ms-auto">$ {{$pos_sale->total_tax}}</div>
        </div>
        <div class="d-flex">
            <div style="font-weight: 500; color: rgb(0, 0, 0);">Discount:</div>
            <div class="text-end ms-auto">${{$pos_sale->total_discount}}</div>
        </div>
        <div class="d-flex">
            <div style="font-weight: 500; color: rgb(0, 0, 0);">Grand Total:</div>
            <div class="text-end ms-auto">$ {{$pos_sale->grand_total}}</div>
        </div>
    </section>
    <table class="table">
        <thead>
            <tr>
                <th class="fw-bold" style="text-align: start; padding: 8px 15px; color: rgb(0, 0, 0);">Paid By</th>
                <th class="fw-bold" style="text-align: center; padding: 8px 15px; color: rgb(0, 0, 0);">Amount</th>
                <th class="fw-bold" style="text-align: end; padding: 8px 15px; color: rgb(0, 0, 0);">Change Return</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 8px 15px; color: rgb(0, 0, 0);">{{$pos_sale->pay_method?->name}}</td>
                <td style="text-align: center; padding: 8px 15px; color: rgb(0, 0, 0);">$ {{$pos_sale->grand_total}}</td>
                <td style="text-align: end; padding: 8px 15px; color: rgb(0, 0, 0);">$ {{$pos_sale->change_amount}}</td>
            </tr>
        </tbody>
    </table>
    <h3 style="text-align: center; color: rgb(0, 0, 0);">Thank you</h3>
    {{-- <div class="text-center d-block">
        <img height="25" width="100" class="" src="https://infypos-demo.nyc3.digitaloceanspaces.com/sales/barcode-SA_11142685.png" alt="SA_11142685">
        <span class="d-block" style="color: rgb(0, 0, 0);">SA_11142685</span>
    </div> --}}
</div>
