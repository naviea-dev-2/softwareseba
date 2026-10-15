@extends('inc.master')

@section('head')
<title>Sales Invoice - {{ $invoice->reference_no }}</title>
<style id="invoice-print-styles">
    .invoice-view {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 6px 32px;
        font-family: Arial, Helvetica, sans-serif;
        color: #101828;
        font-size: 13px;
    }

    .invoice-view * {
        box-sizing: border-box;
    }

    .invoice-page-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .invoice-page-title {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .invoice-page-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #2f6fed, #1d4ed8);
        border-radius: 12px;
        color: white;
        font-size: 23px;
    }

    .invoice-page-title h1 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        color: #101828;
    }

    .invoice-page-title p {
        margin: 3px 0 0;
        color: #667085;
        font-size: 12px;
    }

    .invoice-toolbar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .invoice-action {
        border: 1px solid #d9dee4;
        background: white;
        color: #344054;
        border-radius: 8px;
        padding: 9px 15px;
        cursor: pointer;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .invoice-action-primary {
        background: #2f6fed;
        border-color: #2f6fed;
        color: #fff;
    }

    .invoice-action:hover {
        text-decoration: none;
        filter: brightness(.96);
    }

    .invoice-paper {
        background: white;
        border: 1px solid #e6e8eb;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(16, 24, 40, .06);
        overflow: hidden;
    }

    .invoice-brand-header {
        padding: 25px 28px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        border-bottom: 1px solid #eef0f3;
    }

    .invoice-brand {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
    }

    .invoice-logo {
        width: 70px;
        height: 70px;
        object-fit: contain;
        border: 1px solid #e6e8eb;
        border-radius: 12px;
        padding: 5px;
        background: #fff;
    }

    .invoice-business-name {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 7px;
        color: #101828;
    }

    .invoice-business-details {
        font-size: 12px;
        color: #667085;
        line-height: 1.65;
        overflow-wrap: anywhere;
    }

    .invoice-document-title {
        text-align: right;
        flex-shrink: 0;
    }

    .invoice-document-title h2 {
        font-size: 23px;
        letter-spacing: 1.3px;
        color: #101828;
        margin: 0 0 8px;
        font-weight: 800;
    }

    .invoice-document-title .ref {
        font-size: 12px;
        color: #475467;
        overflow-wrap: anywhere;
    }

    .invoice-info {
        padding: 22px 28px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .invoice-info-card {
        border: 1px solid #e6e8eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .invoice-info-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 15px;
        border-bottom: 1px solid #eef0f3;
        background: #f8f9fb;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 700;
        color: #344054;
    }

    .invoice-info-body {
        padding: 14px 15px;
        min-height: 120px;
    }

    .invoice-customer-name {
        font-size: 15px;
        font-weight: 700;
        margin: 0 0 8px;
        color: #101828;
    }

    .invoice-detail {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin: 6px 0;
        line-height: 1.45;
    }

    .invoice-detail .key {
        color: #667085;
        flex-shrink: 0;
    }

    .invoice-detail .value {
        text-align: right;
        font-weight: 600;
        color: #344054;
        overflow-wrap: anywhere;
    }

    .invoice-info-body .customer-line {
        font-size: 12px;
        color: #475467;
        margin: 5px 0;
        overflow-wrap: anywhere;
    }

    .invoice-pill {
        display: inline-block;
        padding: 4px 9px;
        border-radius: 20px;
        background: #eef4ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 700;
    }

    .invoice-pill.success {
        background: #e7f6ee;
        color: #087c47;
    }

    .invoice-pill.warning {
        background: #fff5e6;
        color: #a75a08;
    }

    .invoice-pill.danger {
        background: #fef3f2;
        color: #b42318;
    }

    .invoice-section-title {
        padding: 13px 28px;
        display: flex;
        align-items: center;
        gap: 9px;
        border-top: 1px solid #eef0f3;
        font-size: 14px;
        font-weight: 700;
    }

    .invoice-table-wrap {
        padding: 0 28px 20px;
        overflow-x: auto;
    }

    .invoice-items {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
        font-size: 12px;
    }

    .invoice-items thead th {
        background: #101828;
        color: #e4e7ec;
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 700;
        padding: 12px 9px;
        text-align: left;
        white-space: nowrap;
    }

    .invoice-items thead th:first-child {
        border-radius: 7px 0 0 0;
    }

    .invoice-items thead th:last-child {
        border-radius: 0 7px 0 0;
    }

    .invoice-items tbody td {
        padding: 12px 9px;
        border-bottom: 1px solid #eef0f3;
        color: #344054;
        vertical-align: top;
        overflow-wrap: anywhere;
    }

    .invoice-items tbody tr:nth-child(even) {
        background: #fafbfc;
    }

    .invoice-items .number {
        text-align: right;
        white-space: nowrap;
    }

    .invoice-items .center {
        text-align: center;
    }

    .invoice-items .product {
        font-weight: 600;
        color: #101828;
    }

    .invoice-bottom {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 330px;
        gap: 20px;
        padding: 0 28px 26px;
        align-items: start;
    }

    .invoice-note {
        border: 1px solid #e6e8eb;
        border-radius: 12px;
        min-height: 110px;
        padding: 15px;
        color: #475467;
        overflow-wrap: anywhere;
    }

    .invoice-note h3 {
        font-size: 12px;
        color: #344054;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin: 0 0 10px;
        font-weight: 700;
    }

    .invoice-note p {
        font-size: 12px;
        line-height: 1.6;
        margin: 0;
        white-space: pre-wrap;
    }

    .invoice-summary {
        border: 1px solid #e6e8eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .invoice-summary-heading {
        padding: 12px 15px;
        background: #f8f9fb;
        border-bottom: 1px solid #eef0f3;
        font-weight: 700;
        font-size: 12px;
    }

    .invoice-summary-line {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 9px 15px;
        font-size: 12px;
        color: #475467;
    }

    .invoice-summary-line strong {
        font-weight: 700;
        color: #101828;
        white-space: nowrap;
    }

    .invoice-summary-line + .invoice-summary-line {
        border-top: 1px dashed #eef0f3;
    }

    .invoice-summary-line.grand {
        background: #101828;
        color: #e4e7ec;
        padding: 15px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .invoice-summary-line.grand strong {
        font-size: 18px;
        color: #fff;
    }

    .invoice-footer {
        padding: 14px 28px;
        border-top: 1px solid #eef0f3;
        color: #98a2b3;
        text-align: center;
        font-size: 11px;
    }

    @media (max-width: 700px) {
        .invoice-brand-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .invoice-document-title {
            text-align: left;
        }

        .invoice-info {
            grid-template-columns: 1fr;
        }

        .invoice-bottom {
            grid-template-columns: 1fr;
        }

        .invoice-brand-header, .invoice-info, .invoice-bottom {
            padding-left: 15px;
            padding-right: 15px;
        }

        .invoice-table-wrap {
            padding-left: 15px;
            padding-right: 15px;
        }

        .invoice-section-title {
            padding-left: 15px;
        }
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 11mm;
        }

        html, body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .invoice-view {
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 11px;
        }

        .invoice-page-head, .invoice-toolbar {
            display: none !important;
        }

        .invoice-paper {
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
        }

        .invoice-brand-header {
            padding: 0 0 13px !important;
        }

        .invoice-info {
            padding: 13px 0 !important;
            gap: 10px;
        }

        .invoice-info-body {
            min-height: 0;
        }

        .invoice-section-title {
            padding: 10px 0 !important;
        }

        .invoice-table-wrap {
            padding: 0 0 12px !important;
            overflow: visible !important;
        }

        .invoice-items {
            min-width: 0 !important;
            font-size: 9px !important;
            table-layout: fixed;
        }

        .invoice-items thead th {
            padding: 8px 4px !important;
            font-size: 8px !important;
            white-space: normal !important;
        }

        .invoice-items tbody td {
            padding: 7px 4px !important;
            word-break: break-word;
        }

        .invoice-items thead {
            display: table-header-group;
        }

        .invoice-items tr {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .invoice-bottom {
            padding: 0 0 12px !important;
            grid-template-columns: minmax(0, 1fr) 240px;
            gap: 12px;
            break-inside: avoid;
        }

        .invoice-footer {
            padding: 10px 0;
        }
    }
</style>
@endsection

@section('content')
    @php
        $currency = auth()->user()->currency_symbol ?? '৳';
        $business = auth()->user()->business;
        $p_total = $invoice->items->sum(fn ($item) => (float) ($item->total ?? 0));
        $shipping = (float) ($invoice->shipping_cost ?? 0);
        $tax = (float) ($invoice->total_tax ?? 0);
        $orderDiscount = (float) ($invoice->order_discount ?? 0);
        $grandTotal = $p_total + $tax + $shipping - $orderDiscount;
        $paidAmount = (float) ($invoice->paid_amount ?? 0);
        $dueAmount = (float) ($invoice->due_amount ?? 0);
        $money = fn ($amount) => $currency . number_format((float) $amount, 2);
        $saleStatus = match ((int) $invoice->status) {
            1 => 'Received',
            2 => 'Partial',
            3 => 'Pending',
            default => 'Ordered',
        };
        $paymentStatus = match ((int) $invoice->payment_status) {
            0 => 'Due',
            1 => 'Partial',
            default => 'Paid',
        };
    @endphp
    <div class="invoice-view">
        <div class="invoice-page-head">
            <div class="invoice-page-title">
                <div class="invoice-page-icon"><i class="bx bx-receipt"></i></div>
                <div>
                    <h1>Sales Invoice</h1>
                    <p>Invoice details and printable receipt</p>
                </div>
            </div>
            <div class="invoice-toolbar">
                <a href="{{ route('invoice.create') }}" class="invoice-action"><i class="bx bx-arrow-back"></i> Back</a>
                <button type="button" id="print-btn" class="invoice-action invoice-action-primary"><i class="bx bx-printer"></i> Print Invoice</button>
            </div>
        </div>

        <div class="invoice-paper" id="print-details">
            <div class="invoice-brand-header">
                <div class="invoice-brand">
                    @if($business?->business_logo_show)
                        <img class="invoice-logo" src="{{ $business->business_logo_show }}" alt="Business logo">
                    @endif
                    <div>
                        <h2 class="invoice-business-name">{{ $business?->business_name }}</h2>
                        <div class="invoice-business-details">
                            @if($business?->mobile_number)
                                <div>Phone: {{ $business->mobile_number }}</div>
                            @endif
                            @if($business?->email)
                                <div>Email: {{ $business->email }}</div>
                            @endif
                            @if($business?->country?->name)
                                <div>Location: {{ $business->country->name }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="invoice-document-title">
                    <h2>INVOICE</h2>
                    <div class="ref">Sales Reference # {{ $invoice->reference_no }}</div>
                    <div class="ref">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }}</div>
                </div>
            </div>

            <div class="invoice-info">
                <div class="invoice-info-card">
                    <div class="invoice-info-heading"><i class="bx bx-user"></i> Bill To / Customer</div>
                    <div class="invoice-info-body">
                        <h3 class="invoice-customer-name">{{ $invoice->customer?->name ?? 'Walk-in Customer' }}</h3>
                        @if($invoice->customer?->mobile)
                            <p class="customer-line"><strong>Mobile:</strong> {{ $invoice->customer->mobile }}</p>
                        @endif
                        @if($invoice->customer?->email)
                            <p class="customer-line"><strong>Email:</strong> {{ $invoice->customer->email }}</p>
                        @endif
                        @if($invoice->customer?->address)
                            <p class="customer-line"><strong>Address:</strong> {{ $invoice->customer->address }}</p>
                        @endif
                    </div>
                </div>
                <div class="invoice-info-card">
                    <div class="invoice-info-heading"><i class="bx bx-file"></i> Invoice Information</div>
                    <div class="invoice-info-body">
                        <div class="invoice-detail"><span class="key">Invoice Date</span><span class="value">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }}</span></div>
                        <div class="invoice-detail"><span class="key">Sales Status</span><span class="value"><span class="invoice-pill {{ (int) $invoice->status === 1 ? 'success' : 'warning' }}">{{ $saleStatus }}</span></span></div>
                        <div class="invoice-detail"><span class="key">Payment Status</span><span class="value"><span class="invoice-pill {{ (int) $invoice->payment_status === 0 ? 'danger' : ((int) $invoice->payment_status === 1 ? 'warning' : 'success') }}">{{ $paymentStatus }}</span></span></div>
                        <div class="invoice-detail"><span class="key">Payment Method</span><span class="value">{{ $invoice?->method?->name ?? '—' }}</span></div>
                    </div>
                </div>
            </div>

            <div class="invoice-section-title"><i class="bx bx-package"></i> Product Details</div>
            <div class="invoice-table-wrap">
                <table class="invoice-items">
                    <thead>
                        <tr>
                            <th style="width:4%">#</th>
                            <th style="width:22%">Product</th>
                            <th style="width:12%">Manufacture</th>
                            <th style="width:10%">Brand</th>
                            <th style="width:7%" class="center">Qty</th>
                            <th style="width:10%">Unit</th>
                            <th style="width:12%" class="number">Unit Cost</th>
                            <th style="width:11%" class="number">Discount</th>
                            <th style="width:12%" class="number">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $k => $item)
                            @php
                                $product = $item->product;
                                $originalProduct = $product?->OriginalProduct;
                            @endphp
                            <tr>
                                <td>{{ $k + 1 }}</td>
                                <td class="product">{{ $product?->product_name }}@if($product?->is_variant == 1) {{ $product?->variation_attributes }}@endif</td>
                                <td>{{ $originalProduct?->manufacture?->name }}</td>
                                <td>{{ $originalProduct?->brand?->name }}</td>
                                <td class="center">{{ $item->qty }}</td>
                                <td>{{ $item->unit?->name }}</td>
                                <td class="number">{{ $money($item->per_cost) }}</td>
                                <td class="number">{{ $money($item->discount) }}</td>
                                <td class="number"><strong>{{ $money($item->total) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="invoice-bottom">
                <div class="invoice-note">
                    <h3><i class="bx bx-note"></i> Order Note</h3>
                    <p>{{ $invoice->note ?: 'No additional notes.' }}</p>
                </div>
                <div class="invoice-summary">
                    <div class="invoice-summary-heading">Invoice Summary</div>
                    <div class="invoice-summary-line"><span>Sub Total</span><strong>{{ $money($p_total) }}</strong></div>
                    <div class="invoice-summary-line"><span>Shipping Cost</span><strong>{{ $money($shipping) }}</strong></div>
                    <div class="invoice-summary-line"><span>Tax</span><strong>{{ $money($tax) }}</strong></div>
                    <div class="invoice-summary-line"><span>Discount</span><strong>− {{ $money($orderDiscount) }}</strong></div>
                    <div class="invoice-summary-line grand"><span>Grand Total</span><strong>{{ $money($grandTotal) }}</strong></div>
                    <div class="invoice-summary-line"><span>Paid Amount</span><strong>{{ $money($paidAmount) }}</strong></div>
                    <div class="invoice-summary-line"><span>Due Amount</span><strong>{{ $money($dueAmount) }}</strong></div>
                </div>
            </div>
            <div class="invoice-footer">Thank you for your business.</div>
        </div>
    </div>
@endsection

@section('script')
<script>
(function () {
    'use strict';

    document.getElementById('print-btn')?.addEventListener('click', function () {
        const invoice = document.getElementById('print-details');
        const styles = document.getElementById('invoice-print-styles');
        if (!invoice || !styles) return;

        // Separate print document prevents master-layout sidebar/navbar from printing.
        const printWindow = window.open('', '_blank', 'width=1100,height=800');
        if (!printWindow) {
            alert('Please allow pop-ups to print the invoice.');
            return;
        }

        printWindow.document.open();
        printWindow.document.write(
            '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Sales Invoice</title><style>' +
            styles.textContent +
            '</style></head><body><div class="invoice-view">' +
            invoice.outerHTML +
            '</div></body></html>'
        );
        printWindow.document.close();

        printWindow.addEventListener('load', function () {
            const images = Array.from(printWindow.document.images);
            Promise.all(images.map(img => img.complete ? Promise.resolve() : new Promise(resolve => {
                img.onload = resolve;
                img.onerror = resolve;
            }))).then(function () {
                printWindow.focus();
                printWindow.print();
            });
        });
    });
})();
</script>
@endsection