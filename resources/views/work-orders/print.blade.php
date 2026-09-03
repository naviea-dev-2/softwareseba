<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Order #{{ $workOrder->work_order_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            padding: 30px;
            color: #1a1a2e;
        }

        .print-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        /* ── HEADER ── */
        .header-section {
            background: linear-gradient(135deg, #0b2b4a 0%, #1a4a6e 60%, #2a5f8a 100%);
            padding: 30px 45px;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        .header-section::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 50%;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
            gap: 20px;
        }

        .company-section {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .company-logo {
            width: 65px;
            height: 65px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.15);
            flex-shrink: 0;
            overflow: hidden;
        }

        .company-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }

        .company-logo .placeholder {
            color: rgba(255, 255, 255, 0.5);
            font-size: 24px;
            font-weight: 700;
        }

        .company-details h1 {
            color: #ffffff;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .company-details .tagline {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            margin-top: 2px;
        }

        .company-details .contact-row {
            color: rgba(255, 255, 255, 0.6);
            font-size: 11px;
            margin-top: 4px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .company-details .contact-row span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .wo-badge {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 12px 28px;
            border-radius: 14px;
            text-align: center;
            min-width: 180px;
            flex-shrink: 0;
        }

        .wo-badge .label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .wo-badge .number {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .header-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
            gap: 12px;
        }

        .header-bottom .title {
            color: #ffffff;
            font-size: 17px;
            font-weight: 600;
        }

        .status-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge-status {
            padding: 5px 18px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-status.draft {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .badge-status.pending {
            background: #fbbf24;
            color: #1a1a2e;
        }

        .badge-status.in_progress {
            background: #60a5fa;
            color: #fff;
        }

        .badge-status.on_hold {
            background: #f59e0b;
            color: #1a1a2e;
        }

        .badge-status.completed {
            background: #34d399;
            color: #1a1a2e;
        }

        .badge-status.closed {
            background: #94a3b8;
            color: #fff;
        }

        .badge-status.cancelled {
            background: #f87171;
            color: #fff;
        }

        .badge-priority {
            padding: 5px 18px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-priority.low {
            background: #34d399;
            color: #1a1a2e;
        }

        .badge-priority.normal {
            background: #60a5fa;
            color: #fff;
        }

        .badge-priority.high {
            background: #fbbf24;
            color: #1a1a2e;
        }

        .badge-priority.urgent {
            background: #f87171;
            color: #fff;
        }

        /* ── BODY ── */
        .body-content {
            padding: 35px 45px;
        }

        /* ── Info Grid - FLEXIBLE LAYOUT ── */
        .info-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-grid .info-card {
            flex: 1 1 auto;
            min-width: 250px;
            max-width: 100%;
            background: #f8fafc;
            border-radius: 12px;
            padding: 18px 22px;
            border: 1px solid #e9edf2;
        }

        /* When content is long, make it full width */
        .info-grid .info-card.full-width {
            flex: 1 1 100%;
            max-width: 100%;
        }

        .info-card .card-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .label {
            font-size: 12px;
            color: #64748b;
            flex-shrink: 0;
            margin-right: 15px;
        }

        .info-row .value {
            font-size: 12px;
            color: #1a1a2e;
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }

        /* ── Customer Section - Full Width ── */
        .customer-section {
            flex: 1 1 100%;
            max-width: 100%;
            background: linear-gradient(135deg, #f0f7ff 0%, #f8fafc 100%);
            border: 2px solid #dbeafe;
            border-radius: 12px;
            padding: 20px 24px;
        }

        .customer-section .card-title {
            color: #1a5fb0;
        }

        .customer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 4px;
        }

        .customer-field .label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            display: block;
        }

        .customer-field .value {
            font-size: 13px;
            color: #1a1a2e;
            font-weight: 500;
            word-break: break-word;
        }

        .customer-field .value.highlight {
            font-weight: 700;
            color: #0b2b4a;
        }

        /* ── Description - Full Width ── */
        .description-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px 22px;
            margin-bottom: 25px;
            border: 1px solid #e9edf2;
            width: 100%;
        }

        .description-box .label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            display: block;
            margin-bottom: 6px;
        }

        .description-box .value {
            font-size: 13px;
            color: #1a1a2e;
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .description-box .value h1,
        .description-box .value h2,
        .description-box .value h3,
        .description-box .value h4 {
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
            color: #0b2b4a;
        }

        .description-box .value ul,
        .description-box .value ol {
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .description-box .value p {
            margin-bottom: 0.5rem;
        }

        /* ── Table Section - Full Width ── */
        .table-section {
            margin: 25px 0;
            width: 100%;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .table-header h3 {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #1a1a2e;
        }

        .table-header .count {
            font-size: 11px;
            color: #64748b;
            background: #f1f4f9;
            padding: 3px 14px;
            border-radius: 50px;
        }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e9edf2;
        }

        .items-table thead {
            background: #f1f4f9;
        }

        .items-table thead th {
            padding: 12px 14px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            border-bottom: 2px solid #d0d8e0;
        }

        .items-table thead th:last-child {
            text-align: right;
        }

        .items-table tbody td {
            padding: 12px 14px;
            font-size: 12px;
            color: #1a1a2e;
            border-bottom: 1px solid #e9edf2;
            vertical-align: middle;
            word-break: break-word;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .items-table tbody td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .item-type-badge {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 600;
        }

        .item-type-badge.product {
            background: #eef6ff;
            color: #1a5fb0;
        }

        .item-type-badge.service {
            background: #f0fdf4;
            color: #166534;
        }

        .item-type-badge.labor {
            background: #fef3c7;
            color: #92400e;
        }

        .item-type-badge.material {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .item-type-badge.expense {
            background: #fce4ec;
            color: #c62828;
        }

        .items-table tfoot {
            background: #f8fafc;
            font-weight: 700;
        }

        .items-table tfoot td {
            padding: 14px;
            font-size: 14px;
            border-top: 2px solid #d0d8e0;
        }

        .items-table tfoot td:last-child {
            font-size: 18px;
            color: #0b2b4a;
        }

        /* ── Notes Grid ── */
        .notes-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 25px;
        }

        .note-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 16px 20px;
            border: 1px solid #e9edf2;
            word-break: break-word;
        }

        .note-box .label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            display: block;
            margin-bottom: 4px;
        }

        .note-box .value {
            font-size: 12px;
            color: #1a1a2e;
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .note-box .value.empty {
            color: #94a3b8;
            font-style: italic;
        }

        /* ── Custom Fields ── */
        .custom-fields {
            margin-top: 25px;
        }

        .custom-fields .title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #1a1a2e;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9edf2;
        }

        .custom-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
        }

        .custom-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #e9edf2;
            word-break: break-word;
        }

        .custom-item .label {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            display: block;
        }

        .custom-item .value {
            font-size: 12px;
            color: #1a1a2e;
            font-weight: 500;
            margin-top: 2px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* ── FOOTER ── */
        .footer-section {
            background: #f8fafc;
            padding: 18px 45px;
            border-top: 1px solid #e9edf2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-section .left {
            font-size: 11px;
            color: #64748b;
        }

        .footer-section .left strong {
            color: #1a1a2e;
        }

        .footer-section .right {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-section .right .tax-info {
            font-size: 11px;
            color: #64748b;
        }

        .footer-section .right .footer-text {
            font-size: 12px;
            color: #1a1a2e;
            font-weight: 500;
        }

        .footer-section .right .powered {
            font-size: 10px;
            color: #94a3b8;
        }

        .footer-section .right .powered strong {
            color: #1a1a2e;
        }

        /* ── Print Styles ── */
        @media print {
            body {
                background: #ffffff;
                padding: 15px;
            }

            .print-wrapper {
                box-shadow: none;
                border-radius: 0;
            }

            .header-section {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .badge-status,
            .badge-priority,
            .items-table thead,
            .item-type-badge,
            .customer-section {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .footer-section {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
            }
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .header-section {
                padding: 20px;
            }

            .body-content {
                padding: 20px;
            }

            .header-top {
                flex-direction: column;
                align-items: stretch;
            }

            .company-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .wo-badge {
                align-self: flex-start;
                min-width: auto;
                width: 100%;
            }

            .header-bottom {
                flex-direction: column;
                align-items: flex-start;
            }

            .status-group {
                width: 100%;
            }

            .notes-grid {
                grid-template-columns: 1fr;
            }

            .custom-grid {
                grid-template-columns: 1fr 1fr;
            }

            .footer-section {
                flex-direction: column;
                text-align: center;
                padding: 15px 20px;
            }

            .footer-section .right {
                justify-content: center;
            }

            .customer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .info-grid .info-card {
                flex: 1 1 100%;
                min-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .custom-grid {
                grid-template-columns: 1fr;
            }

            .customer-grid {
                grid-template-columns: 1fr;
            }

            .company-details h1 {
                font-size: 20px;
            }

            .company-logo {
                width: 50px;
                height: 50px;
            }

            .items-table {
                font-size: 11px;
            }

            .items-table thead th,
            .items-table tbody td {
                padding: 8px 10px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }

            .info-row .value {
                text-align: left;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="print-wrapper">
        @php
        // Use custom company settings if available
        $company = isset($workOrder->company_settings) && !empty(array_filter($workOrder->company_settings))
        ? $workOrder->company_settings
        : ($business ?? []);

        // Set defaults
        $companyName = $company['name'] ?? ($business->business_name ?? config('app.name', 'Work Order System'));
        $companyLogo = $company['logo'] ?? null;
        $companyTagline = $company['tagline'] ?? ($business->tagline ?? 'Professional Work Order Management');
        $companyAddress = $company['address'] ?? ($business->address ?? '');
        $companyCity = $company['city'] ?? ($business->city ?? '');
        $companyState = $company['state'] ?? ($business->state ?? '');
        $companyZip = $company['zip'] ?? ($business->zip ?? '');
        $companyPhone = $company['phone'] ?? ($business->phone_number ?? $business->phone ?? '');
        $companyEmail = $company['email'] ?? ($business->email ?? '');
        $companyWebsite = $company['website'] ?? ($business->website ?? '');
        $companyTaxNumber = $company['tax_number'] ?? ($business->tax_number ?? '');
        $companyFooterText = $company['footer_text'] ?? 'Thank you for your business!';

        $logoPath = null;

        if ($companyLogo) {
            $logoPath = asset('public/upload/business/' . $companyLogo);
            //$logoPath = $companyLogo;
        } elseif (isset($business) && !empty($business->business_logo)) {
            $logoPath = asset('public/upload/business/' . $business->business_logo);
        } elseif (isset($business) && !empty($business->logo)) {
            $logoPath = asset('public/upload/business/' . $business->logo);
        }

        @endphp

        <div class="header-section">
            <div class="header-top">
                <div class="company-section">
                    <div class="company-logo">
                        @if($logoPath)
                        <img src="{{ $logoPath }}" alt="{{ $companyName }}" style="width:100px; height:auto">
                        @else
                        <div class="placeholder">{{ substr($companyName, 0, 2) }}</div>
                        @endif
                    </div>
                    <div class="company-details">
                        <h1>{{ $companyName }}</h1>
                        <div class="tagline">{{ $companyTagline }}</div>
                        <div class="contact-row">
                            @if(!empty($companyAddress))
                            <span>📍 {{ $companyAddress }}</span>
                            @endif
                            @if(!empty($companyCity) || !empty($companyState))
                            <span>{{ $companyCity }} {{ $companyState }} {{ $companyZip }}</span>
                            @endif
                            @if(!empty($companyPhone))
                            <span>📞 {{ $companyPhone }}</span>
                            @endif
                            @if(!empty($companyEmail))
                            <span>✉️ {{ $companyEmail }}</span>
                            @endif
                            @if(!empty($companyWebsite))
                            <span>🌐 {{ $companyWebsite }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="wo-badge">
                    <div class="label">Work Order</div>
                    <div class="number">#{{ $workOrder->work_order_no }}</div>
                </div>
            </div>

            <div class="header-bottom">
                <div class="title">{{ $workOrder->title }}</div>
                <div class="status-group">
                    <span class="badge-status {{ $workOrder->status }}">
                        {{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}
                    </span>
                    <span class="badge-priority {{ $workOrder->priority }}">
                        {{ ucfirst($workOrder->priority) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ── BODY ── --}}
        <div class="body-content">

            {{-- Info Grid - FLEXIBLE LAYOUT --}}
            <div class="info-grid">

                {{-- Order Details Card --}}
                <div class="info-card {{ strlen($workOrder->description ?? '') > 500 ? 'full-width' : '' }}">
                    <div class="card-title">📋 Order Details</div>
                    <div class="info-row">
                        <span class="label">Type</span>
                        <span class="value">{{ $workOrder->workOrderType->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Created</span>
                        <span class="value">{{ $workOrder->created_at->format('M d, Y \a\t H:i') }}</span>
                    </div>
                    @if($workOrder->reference_no)
                    <div class="info-row">
                        <span class="label">Reference #</span>
                        <span class="value">{{ $workOrder->reference_no }}</span>
                    </div>
                    @endif
                    @if($workOrder->assigned_to)
                    <div class="info-row">
                        <span class="label">Assigned To</span>
                        <span class="value">{{ $workOrder->assignee->name ?? 'N/A' }}</span>
                    </div>
                    @endif
                </div>

                {{-- Schedule & Cost Card --}}
                <div class="info-card {{ strlen($workOrder->description ?? '') > 500 ? 'full-width' : '' }}">
                    <div class="card-title">📅 Schedule &amp; Cost</div>
                    @if($workOrder->scheduled_at)
                    <div class="info-row">
                        <span class="label">Scheduled Start</span>
                        <span class="value">{{ date('M d, Y \a\t H:i', strtotime($workOrder->scheduled_at)) }}</span>
                    </div>
                    @endif
                    @if($workOrder->due_at)
                    <div class="info-row">
                        <span class="label">Due Date</span>
                        <span class="value">{{ date('M d, Y \a\t H:i', strtotime($workOrder->due_at)) }}</span>
                    </div>
                    @endif
                    @if($workOrder->estimated_hours > 0)
                    <div class="info-row">
                        <span class="label">Estimated Hours</span>
                        <span class="value">{{ number_format($workOrder->estimated_hours, 2) }} hrs</span>
                    </div>
                    @endif
                    @if($workOrder->estimated_cost > 0)
                    <div class="info-row">
                        <span class="label">Estimated Cost</span>
                        <span class="value">${{ number_format($workOrder->estimated_cost, 2) }}</span>
                    </div>
                    @endif
                </div>

                {{-- Customer Full Details - Always Full Width --}}
                @if($workOrder->customer)
                <div class="customer-section">
                    <div class="card-title">👤 Customer Information</div>
                    <div class="customer-grid">
                        <div class="customer-field">
                            <span class="label">Company Name</span>
                            <span class="value highlight">{{ $workOrder->customer->name }}</span>
                        </div>
                        @if($workOrder->customer->contact_person)
                        <div class="customer-field">
                            <span class="label">Contact Person</span>
                            <span class="value">{{ $workOrder->customer->contact_person }}</span>
                        </div>
                        @endif
                        @if($workOrder->customer->email)
                        <div class="customer-field">
                            <span class="label">Email</span>
                            <span class="value">{{ $workOrder->customer->email }}</span>
                        </div>
                        @endif
                        @if($workOrder->customer->phone)
                        <div class="customer-field">
                            <span class="label">Phone</span>
                            <span class="value">{{ $workOrder->customer->phone }}</span>
                        </div>
                        @endif
                        @if($workOrder->customer->address || $workOrder->customer->city)
                        <div class="customer-field" style="grid-column: 1 / -1;">
                            <span class="label">Address</span>
                            <span class="value">
                                {{ $workOrder->customer->address ?? '' }}
                                @if($workOrder->customer->city || $workOrder->customer->state)
                                {{ $workOrder->customer->city ?? '' }} {{ $workOrder->customer->state ?? '' }} {{ $workOrder->customer->zip ?? '' }}
                                @endif
                            </span>
                        </div>
                        @endif
                        @if($workOrder->customer->tax_number)
                        <div class="customer-field">
                            <span class="label">Tax / VAT</span>
                            <span class="value">{{ $workOrder->customer->tax_number }}</span>
                        </div>
                        @endif
                        @if($workOrder->customer->website)
                        <div class="customer-field">
                            <span class="label">Website</span>
                            <span class="value">{{ $workOrder->customer->website }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            {{-- Description - Full Width --}}
            @if($workOrder->description)
            <div class="description-box">
                <span class="label">📝 Description</span>
                <div class="value">{!! $workOrder->description !!}</div>
            </div>
            @endif

            {{-- Line Items - Full Width --}}
            @if($workOrder->items && $workOrder->items->count() > 0)
            <div class="table-section">
                <div class="table-header">
                    <h3>📦 Line Items</h3>
                    <span class="count">{{ $workOrder->items->count() }} item(s)</span>
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width:12%">Type</th>
                            <th style="width:38%">Description</th>
                            <th style="width:12%;text-align:center">Qty</th>
                            <th style="width:18%;text-align:right">Unit Cost</th>
                            <th style="width:20%;text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach($workOrder->items as $item)
                        @php $total = $item->quantity * $item->unit_cost; $grandTotal += $total; @endphp
                        <tr>
                            <td>
                                <span class="item-type-badge {{ $item->item_type }}">
                                    {{ ucfirst($item->item_type) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $item->description }}</strong>
                                @if($item->product)
                                <br><span style="font-size:10px;color:#64748b;">SKU: {{ $item->product->sku ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td style="text-align:center;font-weight:600;">{{ number_format($item->quantity, 2) }}</td>
                            <td style="text-align:right;">${{ number_format($item->unit_cost, 2) }}</td>
                            <td style="text-align:right;font-weight:600;">${{ number_format($total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align:right;font-size:13px;">
                                <strong>Grand Total</strong>
                            </td>
                            <td style="text-align:right;font-size:18px;font-weight:800;color:#0b2b4a;">
                                ${{ number_format($grandTotal, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif

            {{-- Notes Grid --}}
            @if($workOrder->instructions || $workOrder->internal_notes)
            <div class="notes-grid">
                @if($workOrder->instructions)
                <div class="note-box">
                    <span class="label">📋 Instructions</span>
                    <div class="value">{!! $workOrder->instructions !!}</div>
                </div>
                @endif
                @if($workOrder->internal_notes)
                <div class="note-box">
                    <span class="label">🔒 Internal Notes</span>
                    <div class="value">{!! $workOrder->internal_notes !!}</div>
                </div>
                @endif
            </div>
            @endif

            {{-- Custom Fields --}}
            @if($workOrder->meta && count($workOrder->meta) > 0)
            <div class="custom-fields">
                <div class="title">🔧 Custom Fields</div>
                <div class="custom-grid">
                    @foreach($workOrder->meta as $key => $value)
                    @if(!empty($value))
                    <div class="custom-item">
                        <span class="label">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                        <span class="value">{{ $value }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ── FOOTER ── --}}
        <div class="footer-section">
            <div class="left">
                <strong>Work Order #{{ $workOrder->work_order_no }}</strong>
                <span style="margin:0 6px;color:#d0d8e0;">|</span>
                Generated {{ now()->format('M d, Y \a\t H:i') }}
            </div>
            <div class="right">
                @if(!empty($companyTaxNumber))
                <div class="tax-info">Tax/VAT: {{ $companyTaxNumber }}</div>
                @endif
                <div class="footer-text">{{ $companyFooterText }}</div>
                <div class="powered">Powered by <strong>{{ config('app.name') }}</strong></div>
            </div>
        </div>

    </div>


    <script>
        window.print()
    </script>
</body>

</html>