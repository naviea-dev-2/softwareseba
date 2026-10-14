@extends('inc.master')

@section('head')
<title>Print Purchase Invoice</title>
<style>
    body {
        background: #eef1f5;
    }

    .invoice-toolbar {
        max-width: 900px;
        margin: 20px auto 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 4px;
    }

    .invoice-toolbar .doc-title {
        font-size: 15px;
        font-weight: 600;
        color: #344054;
    }

    .btn-print-p {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        height: 38px;
        padding: 0 18px;
        border: none;
        border-radius: 9px;
        background: linear-gradient(135deg, #2f6fed, #1d4ed8);
        color: #fff;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 6px 14px rgba(47, 111, 237, .30);
        transition: .15s;
    }

    .btn-print-p:hover {
        filter: brightness(1.07);
        color: #fff;
    }

    /* ============ INVOICE PAPER ============ */
    .invoice-paper {
        max-width: 900px;
        margin: 14px auto 40px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(16, 24, 40, .10);
        overflow: hidden;
        color: #344054;
        font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    }

    /* Header band */
    .inv-header {
        background: #101828;
        padding: 26px 36px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .inv-brand {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .inv-brand img {
        width: 62px;
        height: 62px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, .25);
        background: #fff;
    }

    .inv-brand .b-name {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin: 0;
        letter-spacing: .3px;
    }

    .inv-brand .b-meta {
        font-size: 12px;
        color: #b6bfcc;
        margin: 3px 0 0;
    }

    .inv-brand .b-meta span {
        margin-right: 14px;
        white-space: nowrap;
    }

    .inv-brand .b-meta i {
        margin-right: 4px;
    }

    .inv-doc {
        text-align: right;
    }

    .inv-doc .doc-label {
        font-size: 11px;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #8b95a5;
        margin: 0 0 2px;
    }

    .inv-doc .doc-no {
        font-size: 21px;
        font-weight: 700;
        color: #fff;
        margin: 0;
        font-variant-numeric: tabular-nums;
    }

    .inv-doc .doc-date {
        font-size: 12.5px;
        color: #b6bfcc;
        margin: 4px 0 0;
    }

    /* Body */
    .inv-body {
        padding: 28px 36px 10px;
    }

    /* Parties */
    .inv-parties {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .party-box {
        flex: 1;
        min-width: 260px;
        background: #f8f9fb;
        border: 1px solid #eef0f3;
        border-radius: 12px;
        padding: 16px 18px;
    }

    .party-box .p-tag {
        display: inline-block;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: #2f6fed;
        background: #eef4ff;
        padding: 3px 10px;
        border-radius: 20px;
        margin-bottom: 8px;
    }

    .party-box .p-name {
        font-size: 15px;
        font-weight: 700;
        color: #101828;
        margin: 0 0 6px;
    }

    .party-box .p-line {
        font-size: 12.5px;
        color: #667085;
        margin: 2px 0;
    }

    .party-box .p-line i {
        width: 16px;
        color: #98a2b3;
    }

    /* Meta badges row */
    .inv-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 13px;
        border-radius: 20px;
        border: 1px solid #e3e6ea;
        background: #fff;
        color: #475467;
    }

    .meta-chip .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .chip-green .dot {
        background: #12a05c;
    }

    .chip-green {
        color: #12805c;
        background: #e7f6ee;
        border-color: #bfe8d2;
    }

    .chip-orange .dot {
        background: #ea7a1f;
    }

    .chip-orange {
        color: #b35c12;
        background: #fdf1e7;
        border-color: #f5dcc4;
    }

    .chip-gray .dot {
        background: #667085;
    }

    .chip-gray {
        color: #475467;
        background: #f2f4f7;
        border-color: #e3e6ea;
    }

    .chip-red .dot {
        background: #d92d20;
    }

    .chip-red {
        color: #b42318;
        background: #fef3f2;
        border-color: #f3d2cf;
    }

    .chip-blue .dot {
        background: #2f6fed;
    }

    .chip-blue {
        color: #1d4ed8;
        background: #eef4ff;
        border-color: #d3e1fd;
    }

    /* Items table */
    table.inv-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 22px;
    }

    table.inv-table thead th {
        background: #101828;
        color: #e4e7ec;
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        padding: 11px 12px;
        text-align: center;
        border: none;
    }

    table.inv-table thead th:first-child {
        border-radius: 10px 0 0 10px;
    }

    table.inv-table thead th:last-child {
        border-radius: 0 10px 10px 0;
    }

    table.inv-table tbody td {
        padding: 10px 12px;
        font-size: 13px;
        color: #344054;
        border-bottom: 1px solid #eef0f3;
        text-align: center;
        vertical-align: middle;
    }

    table.inv-table tbody td:nth-child(2) {
        text-align: left;
        font-weight: 500;
        color: #101828;
    }

    table.inv-table tbody tr:nth-child(even) td {
        background: #fafbfc;
    }

    table.inv-table .num {
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    /* Totals */
    .inv-bottom {
        display: flex;
        justify-content: space-between;
        gap: 28px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .inv-note {
        flex: 1;
        min-width: 260px;
    }

    .inv-note .n-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #667085;
        margin-bottom: 6px;
    }

    .inv-note .n-box {
        background: #fffbeb;
        border: 1px solid #fdecc8;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 12.5px;
        color: #6b5d3f;
        white-space: pre-wrap;
    }

    .inv-totals {
        width: 320px;
        max-width: 100%;
    }

    .inv-totals .t-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 4px;
        font-size: 13px;
        color: #475467;
        border-bottom: 1px dashed #e6e8eb;
    }

    .inv-totals .t-row .t-val {
        font-weight: 600;
        color: #101828;
        font-variant-numeric: tabular-nums;
    }

    .inv-totals .t-row.grand {
        background: #101828;
        border: none;
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 10px;
        color: #98a2b3;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .inv-totals .t-row.grand .t-val {
        color: #fff;
        font-size: 18px;
        font-weight: 700;
    }

    .inv-totals .t-row.paid .t-val {
        color: #12805c;
    }

    .inv-totals .t-row.due {
        border-bottom: none;
    }

    .inv-totals .t-row.due .t-val {
        color: #d92d20;
    }

    /* Signature & footer */
    .inv-sign {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin: 30px 0 8px;
        padding: 0 4px;
    }

    .sig-box {
        text-align: center;
        min-width: 200px;
    }

    .sig-line {
        border-top: 1.5px solid #98a2b3;
        margin-top: 40px;
        padding-top: 6px;
        font-size: 11.5px;
        color: #667085;
        font-weight: 600;
        letter-spacing: .5px;
    }

    .inv-footer-band {
        background: #f8f9fb;
        border-top: 1px solid #eef0f3;
        padding: 16px 36px;
        text-align: center;
    }

    .inv-footer-band p {
        margin: 0;
        font-size: 12px;
        color: #667085;
    }

    .inv-footer-band .thanks {
        font-weight: 600;
        color: #101828;
        font-size: 12.5px;
        margin-bottom: 3px;
    }

    @media (max-width: 767px) {

        .inv-header,
        .inv-body {
            padding-left: 18px;
            padding-right: 18px;
        }

        .inv-doc {
            text-align: left;
        }

        .table-scroll {
            overflow-x: auto;
        }
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            width: 100% !important;
            height: auto !important;
        }

        /* Hide everything, including the master layout */
        body * {
            visibility: hidden !important;
        }

        /* Show only the invoice and its children */
        #print-details,
        #print-details * {
            visibility: visible !important;
        }

        /* Move the invoice to the printable page origin */
        #print-details {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
            background: #fff !important;
        }

        .invoice-toolbar,
        #print-btn {
            display: none !important;
        }

        .content-area {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* Preserve invoice header and table background colors */
        #print-details,
        #print-details * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Prevent awkward page breaks */
        .inv-header,
        .inv-parties,
        .inv-bottom,
        .inv-sign,
        .inv-footer-band {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .inv-table thead {
            display: table-header-group;
        }

        .inv-table tr {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
</style>
@endsection

@section('content')
<div class="content-area">

    <div class="invoice-toolbar d-print-none">
        <span class="doc-title"><i class="bx bx-receipt me-1"></i> Purchase Invoice — Preview</span>
        <button id="print-btn" type="button" class="btn-print-p"><i class="bx bx-printer"></i> Print Invoice</button>
    </div>

    <div class="invoice-paper" id="print-details">

        {{-- ================= HEADER ================= --}}
        <div class="inv-header">
            <div class="inv-brand">
                <img src="{{ auth()->user()->business->business_logo_show }}" alt="logo">
                <div>
                    <p class="b-name">{{ auth()->user()->business->business_name }}</p>
                    <p class="b-meta">
                        <span><i class="fa fa-phone"></i>+{{ auth()->user()->business->mobile_number }}</span>
                        <span><i class="fa fa-envelope-o"></i>{{ auth()->user()->business->email }}</span>
                        <span><i class="fa fa-location-arrow"></i>{{ auth()->user()->business->country?->name }}</span>
                    </p>
                </div>
            </div>
            <div class="inv-doc">
                <p class="doc-label">Purchase Invoice</p>
                <p class="doc-no">#{{ $purchase->reference_no }}</p>
                <p class="doc-date">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</p>
            </div>
        </div>

        <div class="inv-body">

            {{-- ================= PARTIES ================= --}}
            <div class="inv-parties">
                <div class="party-box">
                    <span class="p-tag">Supplier / Bill To</span>
                    <p class="p-name">{{ $purchase->vendor?->name }}</p>
                    <p class="p-line"><i class="fa fa-phone"></i> +{{ $purchase->vendor?->mobile }}</p>
                    <p class="p-line"><i class="fa fa-envelope-o"></i> {{ $purchase->vendor?->email }}</p>
                    <p class="p-line"><i class="fa fa-map-marker"></i> {{ $purchase->vendor?->address }}</p>
                </div>
                <div class="party-box">
                    <span class="p-tag">Invoice Details</span>
                    <p class="p-line" style="margin-top:4px;"><strong style="color:#101828;">Reference:</strong> {{ $purchase->reference_no }}</p>
                    <p class="p-line"><strong style="color:#101828;">Date:</strong> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</p>
                    @if($purchase?->method)
                    <p class="p-line"><strong style="color:#101828;">Payment Method:</strong> {{ $purchase?->method?->name }}</p>
                    @endif
                    <div class="inv-meta" style="margin:10px 0 0;">
                        <span class="meta-chip
                            @if($purchase->status == 1) chip-green
                            @elseif($purchase->status == 2) chip-orange
                            @elseif($purchase->status == 3) chip-gray
                            @else chip-blue @endif">
                            <span class="dot"></span>
                            @if($purchase->status == 1) Received
                            @elseif($purchase->status == 2) Partial
                            @elseif($purchase->status == 3) Pending
                            @else Ordered @endif
                        </span>
                        <span class="meta-chip
                            @if($purchase->payment_status == 0) chip-red
                            @elseif($purchase->payment_status == 1) chip-orange
                            @else chip-green @endif">
                            <span class="dot"></span>
                            @if($purchase->payment_status == 0) Due
                            @elseif($purchase->payment_status == 1) Partial
                            @else Paid @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- ================= ITEMS ================= --}}
            @php
            $p_total = 0;
            @endphp
            <div class="table-scroll">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="27%">Product</th>
                            <th width="15%">Manufacture</th>
                            <th width="13%">Brand</th>
                            <th width="8%">Qty</th>
                            <th width="10%">Unit</th>
                            <th width="11%">Unit Cost</th>
                            <th width="10%">Discount</th>
                            <th width="12%">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->items as $k => $item)
                        @php
                        $orginal_product = $item->product->OriginalProduct;
                        $p_total += $item->total;
                        @endphp
                        <tr>
                            <td>{{ $k + 1 }}</td>
                            <td>
                                @if($item->product->is_variant == 1)
                                {{ $item->product->product_name . $item->product->variation_attributes }}
                                @else
                                {{ $item->product->product_name }}
                                @endif
                            </td>
                            <td>{{ $orginal_product?->manufacture?->name ?? '—' }}</td>
                            <td>{{ $orginal_product?->brand?->name ?? '—' }}</td>
                            <td class="num">{{ $item->qty }}</td>
                            <td>{{ $item->unit->name }}</td>
                            <td class="num">{{ auth()->user()->currency_symbol }}{{ number_format(round($item->per_cost, 2), 2) }}</td>
                            <td class="num">{{ auth()->user()->currency_symbol }}{{ number_format(round($item->discount, 2), 2) }}</td>
                            <td class="num"><strong>{{ auth()->user()->currency_symbol }}{{ number_format(round($item->total, 2), 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ================= NOTE + TOTALS ================= --}}
            <div class="inv-bottom">
                <div class="inv-note">
                    <p class="n-title">Order Note</p>
                    <div class="n-box">{{ $purchase->note ?: 'No note provided for this purchase.' }}</div>
                </div>
                <div class="inv-totals">
                    <div class="t-row"><span>Sub Total</span><span class="t-val">{{ auth()->user()->currency_symbol }}{{ number_format(round($p_total, 2), 2) }}</span></div>
                    <div class="t-row"><span>Tax</span><span class="t-val">{{ auth()->user()->currency_symbol }}{{ number_format(round($purchase->total_tax, 2), 2) }}</span></div>
                    <div class="t-row"><span>Shipping Cost</span><span class="t-val">{{ auth()->user()->currency_symbol }}{{ number_format(round($purchase->shipping_cost, 2), 2) }}</span></div>
                    <div class="t-row"><span>Discount</span><span class="t-val">-{{ auth()->user()->currency_symbol }}{{ number_format(round($purchase->order_discount, 2), 2) }}</span></div>
                    <div class="t-row grand"><span>Grand Total</span><span class="t-val">{{ auth()->user()->currency_symbol }}{{ number_format(round($p_total + $purchase->total_tax + $purchase->shipping_cost - $purchase->order_discount, 2), 2) }}</span></div>
                    <div class="t-row paid"><span>Paid</span><span class="t-val">{{ auth()->user()->currency_symbol }}{{ number_format(round($purchase->paid_amount, 2), 2) }}</span></div>
                    <div class="t-row due"><span>Due</span><span class="t-val">{{ auth()->user()->currency_symbol }}{{ number_format(round($purchase->due_amount, 2), 2) }}</span></div>
                </div>
            </div>

            {{-- ================= SIGNATURE ================= --}}
            <div class="inv-sign">
                <div class="sig-box">
                    <div class="sig-line">Supplier Signature</div>
                </div>
                <div class="sig-box">
                    <div class="sig-line">Authorized Signature</div>
                </div>
            </div>

        </div>

        {{-- ================= FOOTER ================= --}}
        <div class="inv-footer-band">
            <p class="thanks">Thank you for your business!</p>
            <p>Generated by {{ auth()->user()->business->business_name }} &bull; {{ \Carbon\Carbon::now()->format('d M, Y h:i A') }}</p>
        </div>

    </div>
</div>
@endsection

@section('script')
<script>
    $("#print-btn").on("click", function() {
        window.print();
    });
</script>
@endsection