<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Invoice</title>
    <style>
        @page { size: auto;  margin: 0mm; } @media print { body { -webkit-print-color-adjust: exact; } }
        body {
            background-color: rgb(239, 243, 247);
            display: flex;
            flex-direction: column;
            font-family: Poppins, Helvetica, sans-serif;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.313rem;
        }
        .print-data {
            padding-right: 35px;
            padding-bottom: 35px;
            padding-left: 35px;
            padding-top: 0px !important;
        }
        .text-black {
            --bs-text-opacity: 1;
            color: rgba(var(--bs-black-rgb), var(--bs-text-opacity)) !important;
        }

        .text-center {
            text-align: center !important;
        }

        .mb-4 {
            margin-bottom: 1rem !important;
        }

        .mt-4 {
            margin-top: 1rem !important;
        }
        .mt-3 {
            margin-top: 0.75rem !important;
        }
        .p-0 {
            padding: 0px !important;
        }
        img, svg {
            vertical-align: middle;
        }
        .product-border {
            border-bottom: 3px dotted rgba(0, 0, 0, 0.843) !important;
        }
        .fw-bold {
            font-weight: 500 !important;
        }
        .me-2 {
            margin-right: 0.5rem !important;
        }
        .justify-content-between {
            justify-content: space-between !important;
        }
        .border-0 {
            border: 0px !important;
        }
        .d-flex {
            display: flex !important;
        }
        .text-end {
            text-align: right !important;
        }
        .ms-auto {
            margin-left: auto !important;
        }
        table {
            border-collapse: collapse;
            caption-side: bottom;
        }
        .table {
            --bs-table-bg: transparent;
            --bs-table-accent-bg: transparent;
            --bs-table-striped-color: #6c757d;
            --bs-table-striped-bg: #fff;
            --bs-table-active-color: #6c757d;
            --bs-table-active-bg: rgba(6, 9, 23, .1);
            --bs-table-hover-color: #6c757d;
            --bs-table-hover-bg: rgba(6, 9, 23, .075);
            border-color: rgb(233, 236, 239);
            color: rgb(108, 117, 125);
            margin-bottom: 1rem;
            vertical-align: top;
            width: 100%;
        }
        th {
            font-weight: 500;
            text-align: -webkit-match-parent;
        }
        tbody, td, tfoot, th, thead, tr {
            border-width: 0px;
            border-style: solid;
            border-image: initial;
            border-color: inherit;
        }
        .table > thead {
            vertical-align: bottom;
        }
        .table > :not(caption) > * > * {
            vertical-align: middle !important;
        }
        .table > :not(caption) > * > * {
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
            padding: 0.5rem 1.313rem;
        }
        .print-data table tr th {
            font-size: 12px !important;
        }
        .table > thead > tr > th {
            background-color: rgb(248, 249, 250);
            font-weight: 400;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .print-data table tr th {
            font-size: 12px !important;
        }
        .print-data, .print-data table, .print-data table tr {
            margin: 0px !important;
        }
        .fw-bold {
            font-weight: 500 !important;
        }

        tbody {
            border-top: none !important;
        }
        .table > tbody {
            vertical-align: inherit;
        }
        .table > :not(:first-child) {
            border-top: 2px solid;
        }
        .table > :not(caption) > * > * {
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
            padding: 0.5rem 1.313rem;
        }
        .print-data table tr td {
            border: 0px;
            padding-bottom: 0px;
            padding-left: 0px;
            padding-right: 0px;
            font-size: 14px !important;
            margin: 0px !important;
        }
        tbody tr:last-child td {
            border-bottom: 0px;
        }
        .print-data table tr td {
            border: 0px;
            padding-bottom: 0px;
            padding-left: 0px;
            padding-right: 0px;
            font-size: 14px !important;
            margin: 0px !important;
        }
        @media (max-width: 925px) {
            .table > :not(caption) > * > * {
                padding: 0.5rem 0.313rem !important;
            }
        }

        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            font-weight: 500;
            line-height: 1.2;
            margin-bottom: 0.5rem;
            margin-top: 0px;
        }
        .h3, h3 {
            font-size: 1.125rem;
        }
        .text-center {
            text-align: center !important;
        }
        .d-block {
            display: block !important;
        }
    </style>
</head>
<body onload="window.print()">
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
            @php
                $product = $item?->product;
            @endphp
            <div>
                @if($product?->is_variant == 0)
                <div class="p-0">{{$product?->product_name}}
                    {!! $product?->variation_attributes2 !!}
                    @if($product?->product_code)
                    <span>({{$product?->product_code}})</span>
                    @endif
                </div>
                @else
                <div class="p-0">{{$product?->product_name}}
                    @if($product?->product_code)
                    <span>({{$product?->product_code}})</span>
                    @endif
                </div>
                @endif
                <div class="product-border">
                    <div class="border-0 d-flex justify-content-between">
                        <span class="text-black">{{$item->qty}} {{$item->unit?->name}} X $ {{$item->per_cost}}</span>
                        <span class="text-end">$ {{$item->total}}</span>
                    </div>
                </div>
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
</body>
</html>
