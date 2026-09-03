@extends('inc.master')

@section('head')
    <title>WO {{ $workOrder->work_order_no }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        /* ── General ── */
        .content-area {
            padding: 1.5rem 0;
        }

        /* ── Header Section ── */
        .wo-header {
            background: linear-gradient(135deg, #0b2b4a 0%, #1a4a6e 60%, #2a5f8a 100%);
            border-radius: 16px;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .wo-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }

        .wo-header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.02);
            border-radius: 50%;
        }

        .wo-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .wo-header-top .wo-number {
            color: #ffffff;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .wo-header-top .wo-number small {
            font-size: 0.9rem;
            font-weight: 400;
            color: rgba(255,255,255,0.6);
            display: block;
            margin-top: 2px;
        }

        .wo-header-top .wo-title {
            color: #ffffff;
            font-size: 1.3rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .badge-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .badge-status {
            padding: 0.35rem 1.2rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-status.draft { background: rgba(255,255,255,0.2); color: #fff; }
        .badge-status.pending { background: #fbbf24; color: #1a1a2e; }
        .badge-status.in_progress { background: #60a5fa; color: #fff; }
        .badge-status.on_hold { background: #f59e0b; color: #1a1a2e; }
        .badge-status.completed { background: #34d399; color: #1a1a2e; }
        .badge-status.closed { background: #94a3b8; color: #fff; }
        .badge-status.cancelled { background: #f87171; color: #fff; }

        .badge-priority {
            padding: 0.35rem 1.2rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-priority.low { background: #34d399; color: #1a1a2e; }
        .badge-priority.normal { background: #60a5fa; color: #fff; }
        .badge-priority.high { background: #fbbf24; color: #1a1a2e; }
        .badge-priority.urgent { background: #f87171; color: #fff; animation: pulse 2s infinite; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .badge-overdue {
            background: #dc3545;
            color: #fff;
            padding: 0.35rem 1.2rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: pulse 1.5s infinite;
        }

        .wo-header-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .wo-header-actions .btn {
            border-radius: 50px;
            font-size: 0.8rem;
            padding: 0.4rem 1.2rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .wo-header-actions .btn-light {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
        }

        .wo-header-actions .btn-light:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        .wo-header-actions .btn-primary {
            background: #0d6efd;
            border: none;
        }

        .wo-header-actions .btn-success {
            background: #198754;
            border: none;
        }

        .wo-header-actions .btn-warning {
            background: #ffc107;
            border: none;
            color: #1a1a2e;
        }

        .wo-header-actions .btn-danger {
            background: #dc3545;
            border: none;
        }

        .wo-header-actions .btn-outline-light {
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
        }

        .wo-header-actions .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
        }

        /* ── Cards ── */
        .card-modern {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e9edf2;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            transition: all 0.2s;
            overflow: hidden;
        }

        .card-modern:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }

        .card-modern .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e9edf2;
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
        }

        .card-modern .card-header i {
            margin-right: 0.5rem;
            color: #0d6efd;
        }

        .card-modern .card-body {
            padding: 1.5rem;
        }

        /* ── Detail Grid ── */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .detail-item .label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            display: block;
        }

        .detail-item .value {
            font-size: 0.95rem;
            color: #1a1a2e;
            font-weight: 500;
            margin-top: 2px;
        }

        .detail-item .value .text-muted {
            font-weight: 400;
            font-size: 0.8rem;
        }

        /* ── Description ── */
        .description-content {
            color: #334155;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .description-content h1, 
        .description-content h2, 
        .description-content h3, 
        .description-content h4 {
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            color: #0b2b4a;
        }

        .description-content ul, 
        .description-content ol {
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .description-content p {
            margin-bottom: 0.5rem;
        }

        /* ── Progress Bar ── */
        .progress-modern {
            height: 8px;
            border-radius: 50px;
            background: #e9edf2;
            overflow: hidden;
            position: relative;
        }

        .progress-modern .progress-bar {
            border-radius: 50px;
            transition: width 0.6s ease;
        }

        .progress-modern .progress-label {
            position: absolute;
            right: 0;
            top: -18px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* ── Timeline ── */
        .timeline-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f4f9;
        }

        .timeline-item:last-child {
            border-bottom: none;
        }

        .timeline-item .label {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .timeline-item .value {
            font-size: 0.85rem;
            font-weight: 500;
            color: #1a1a2e;
        }

        .timeline-item .value.text-danger {
            color: #dc3545 !important;
            font-weight: 600;
        }

        /* ── Financial Cards ── */
        .stat-card {
            text-align: center;
            padding: 1rem;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e9edf2;
        }

        .stat-card .label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            font-weight: 600;
        }

        .stat-card .value {
            font-size: 1.1rem;
            font-weight: 700;
            margin-top: 4px;
        }

        .stat-card .value.text-success { color: #198754; }
        .stat-card .value.text-danger { color: #dc3545; }
        .stat-card .value.text-primary { color: #0d6efd; }

        /* ── Items Table ── */
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .items-table thead th {
            background: #f8fafc;
            padding: 0.75rem 1rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 700;
            border-bottom: 2px solid #e9edf2;
        }

        .items-table tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f4f9;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .items-table tbody tr:hover {
            background: #f8fafc;
        }

        .item-type-badge {
            display: inline-block;
            padding: 0.15rem 0.75rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .item-type-badge.product { background: #eef6ff; color: #1a5fb0; }
        .item-type-badge.service { background: #f0fdf4; color: #166534; }
        .item-type-badge.labor { background: #fef3c7; color: #92400e; }
        .item-type-badge.material { background: #f3e8ff; color: #6b21a8; }
        .item-type-badge.expense { background: #fce4ec; color: #c62828; }

        /* ── Alerts ── */
        .alert-modern {
            border-radius: 12px;
            padding: 1rem 1.5rem;
            border: none;
        }

        .alert-modern.alert-info {
            background: #eef6ff;
            color: #1a5fb0;
        }

        .alert-modern.alert-light {
            background: #f8fafc;
            border: 1px solid #e9edf2;
        }

        .alert-modern.alert-success {
            background: #f0fdf4;
            color: #166534;
        }

        .alert-modern .alert-title {
            font-weight: 600;
            display: block;
            margin-bottom: 0.25rem;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .wo-header {
                padding: 1.5rem;
            }
            .wo-header-top {
                flex-direction: column;
            }
            .wo-header-actions {
                width: 100%;
                justify-content: flex-start;
            }
            .wo-header-actions .btn {
                flex: 1;
                text-align: center;
                min-width: auto;
            }
            .detail-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .wo-header {
                padding: 1rem;
            }
            .wo-header-top .wo-number {
                font-size: 1.3rem;
            }
            .wo-header-top .wo-title {
                font-size: 1rem;
            }
            .detail-grid {
                grid-template-columns: 1fr;
            }
            .card-modern .card-body {
                padding: 1rem;
            }
            .stat-card .value {
                font-size: 0.95rem;
            }
        }

        /* ── Print Styles ── */
        @media print {
            .wo-header-actions,
            .btn,
            form {
                display: none !important;
            }
            .wo-header {
                background: #0b2b4a !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-status,
            .badge-priority,
            .badge-overdue {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .card-modern {
                break-inside: avoid;
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
        }
    </style>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">

        {{-- ── HEADER ── --}}
        <div class="wo-header">
            <div class="wo-header-top">
                <div>
                    <div class="wo-number">
                        #{{ $workOrder->work_order_no }}
                        <small>{{ $workOrder->workOrderType->name ?? ucfirst($workOrder->work_order_type) }}</small>
                    </div>
                    <div class="wo-title">{{ $workOrder->title }}</div>
                </div>
                <div class="badge-group">
                    {!! $workOrder->status_badge !!}
                    {!! $workOrder->priority_badge !!}
                    @if($workOrder->is_overdue)
                        <span class="badge-overdue"><i class="bx bx-time-five"></i> OVERDUE</span>
                    @endif
                </div>
            </div>

            <div class="wo-header-top mt-3">
                <div></div>
                <div class="wo-header-actions">
                    @if($workOrder->canStart())
                        <form action="{{ route('work-orders.start', $workOrder) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-success"><i class="bx bx-play"></i> Start</button>
                        </form>
                    @endif

                    @if($workOrder->status == 'in_progress')
                        <form action="{{ route('work-orders.hold', $workOrder) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-warning"><i class="bx bx-pause"></i> Hold</button>
                        </form>
                    @endif

                    @if($workOrder->status == 'on_hold')
                        <form action="{{ route('work-orders.resume', $workOrder) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-info"><i class="bx bx-play-circle"></i> Resume</button>
                        </form>
                    @endif

                    @if($workOrder->canComplete())
                        <form action="{{ route('work-orders.complete', $workOrder) }}" method="POST" class="d-inline-flex gap-2 align-items-center">
                            @csrf @method('PATCH')
                            <input type="text" name="completion_notes" placeholder="Completion notes..." class="form-control form-control-sm" style="width:160px;border-radius:50px;">
                            <button class="btn btn-success"><i class="bx bx-check"></i> Complete</button>
                        </form>
                    @endif

                    @if($workOrder->canCancel())
                        <form action="{{ route('work-orders.cancel', $workOrder) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-danger"><i class="bx bx-x"></i> Cancel</button>
                        </form>
                    @endif

                    @if($workOrder->status == 'completed')
                        <form action="{{ route('work-orders.close', $workOrder) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-outline-light"><i class="bx bx-check-double"></i> Close</button>
                        </form>
                    @endif

                    @if($workOrder->canReopen())
                        <form action="{{ route('work-orders.reopen', $workOrder) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-outline-light"><i class="bx bx-refresh"></i> Reopen</button>
                        </form>
                    @endif

                    <a href="{{ route('work-orders.print', $workOrder) }}" class="btn btn-light" target="_blank">
                        <i class="bx bx-printer"></i>
                    </a>
                    <a href="{{ route('work-orders.download', $workOrder) }}" class="btn btn-light">
                        <i class="bx bx-download"></i>
                    </a>
                    <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        {{-- ── MAIN CONTENT ── --}}
        <div class="row g-4">

            {{-- Left Column --}}
            <div class="col-lg-8">

                {{-- Description --}}
                @if($workOrder->description)
                <div class="card-modern mb-4">
                    <div class="card-header"><i class="bx bx-detail"></i> Description</div>
                    <div class="card-body description-content">
                        {!! $workOrder->description !!}
                    </div>
                </div>
                @endif

                {{-- Details Grid --}}
                <div class="card-modern mb-4">
                    <div class="card-header"><i class="bx bx-info-circle"></i> Details</div>
                    <div class="card-body">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="label">Customer</span>
                                <span class="value">{{ $workOrder->customer->name ?? '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Vendor</span>
                                <span class="value">{{ $workOrder->vendor->name ?? '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Assigned To</span>
                                <span class="value">{{ $workOrder->assignee->name ?? 'Unassigned' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Warehouse</span>
                                <span class="value">{{ $workOrder->warehouse->name ?? '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Reference</span>
                                <span class="value">{{ $workOrder->reference_no ?: '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Created By</span>
                                <span class="value">
                                    {{ $workOrder->creator->name ?? '-' }}
                                    <span class="text-muted">{{ $workOrder->created_at?->format('M d, Y') }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Custom Sections --}}
                @if($workOrder->meta && $workOrder->workOrderType)
                    @foreach($workOrder->workOrderType->getCustomSections() ?? [] as $section)
                        @php
                            $hasValues = false;
                            foreach($section['fields'] as $f) {
                                if (!empty($workOrder->meta[$f['name']] ?? null)) {
                                    $hasValues = true;
                                    break;
                                }
                            }
                        @endphp
                        @if($hasValues)
                        <div class="card-modern mb-4">
                            <div class="card-header"><i class="bx bx-extension"></i> {{ $section['title'] }}</div>
                            <div class="card-body">
                                <div class="detail-grid">
                                    @foreach($section['fields'] as $f)
                                        @php $val = $workOrder->meta[$f['name']] ?? null; @endphp
                                        @if(!empty($val))
                                        <div class="detail-item">
                                            <span class="label">{{ $f['label'] }}</span>
                                            <span class="value">
                                                @if($f['type'] === 'select-product' && $val)
                                                    {{ $workOrder->items->firstWhere('product_id', $val)?->product->name ?? $val }}
                                                @elseif($f['type'] === 'select' && isset($f['options']))
                                                    {{ in_array($val, $f['options']) ? $val : '-' }}
                                                @else
                                                    {{ $val }}
                                                @endif
                                            </span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif

                {{-- Instructions & Notes --}}
                @if($workOrder->instructions)
                    <div class="alert-modern alert-info mb-3">
                        <span class="alert-title"><i class="bx bx-list-check"></i> Instructions</span>
                        {!! $workOrder->instructions !!}
                    </div>
                @endif

                @if($workOrder->internal_notes)
                    <div class="alert-modern alert-light mb-3">
                        <span class="alert-title"><i class="bx bx-lock-alt"></i> Internal Notes</span>
                        {!! $workOrder->internal_notes !!}
                    </div>
                @endif

                @if($workOrder->completion_notes)
                    <div class="alert-modern alert-success mb-3">
                        <span class="alert-title"><i class="bx bx-check-circle"></i> Completion Notes</span>
                        {{ $workOrder->completion_notes }}
                    </div>
                @endif

                {{-- Line Items --}}
                @if($workOrder->items->count())
                <div class="card-modern">
                    <div class="card-header">
                        <i class="bx bx-list-ul"></i>
                        {{ $workOrder->workOrderType?->lineItemLabel() ?? 'Items' }}
                        <span class="badge bg-light text-dark ms-2">{{ $workOrder->items->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th style="text-align:right">Qty</th>
                                        <th style="text-align:right">Consumed</th>
                                        <th style="text-align:right">Unit Cost</th>
                                        <th style="text-align:right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp
                                    @foreach($workOrder->items as $item)
                                    @php $grandTotal += $item->total_cost; @endphp
                                    <tr>
                                        <td><span class="item-type-badge {{ $item->item_type }}">{{ $item->item_type }}</span></td>
                                        <td>
                                            @if($item->product)
                                                <strong>{{ $item->product->name }}</strong>
                                                <span class="text-muted d-block small">{{ $item->description }}</span>
                                            @else
                                                {{ $item->description }}
                                            @endif
                                        </td>
                                        <td style="text-align:right;font-weight:600;">{{ number_format($item->quantity, 2) }}</td>
                                        <td style="text-align:right;color:#198754;">{{ number_format($item->consumed_qty ?? 0, 2) }}</td>
                                        <td style="text-align:right;">${{ number_format($item->unit_cost, 2) }}</td>
                                        <td style="text-align:right;font-weight:600;">${{ number_format($item->total_cost, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="background:#f8fafc;font-weight:700;">
                                    <tr>
                                        <td colspan="5" style="text-align:right;padding:0.75rem 1rem;">Grand Total</td>
                                        <td style="text-align:right;padding:0.75rem 1rem;color:#0b2b4a;font-size:1.1rem;">
                                            ${{ number_format($grandTotal, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Progress Update (for active statuses) --}}
                @if(in_array($workOrder->status, ['in_progress','on_hold']))
                <div class="card-modern mt-4">
                    <div class="card-header"><i class="bx bx-trending-up"></i> Update Progress</div>
                    <div class="card-body">
                        <form action="{{ route('work-orders.progress', $workOrder) }}" method="POST" class="row g-3 align-items-end">
                            @csrf @method('PATCH')
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Progress</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="range" name="progress" class="form-range flex-grow-1" min="0" max="100" value="{{ $workOrder->progress }}" 
                                           oninput="document.getElementById('progVal').textContent = this.value + '%'">
                                    <span id="progVal" class="fw-bold text-primary" style="min-width:50px;">{{ $workOrder->progress }}%</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Actual Hours</label>
                                <input type="number" step="0.01" name="actual_hours" class="form-control" value="{{ $workOrder->actual_hours }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Actual Cost</label>
                                <input type="number" step="0.01" name="actual_cost" class="form-control" value="{{ $workOrder->actual_cost }}">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100"><i class="bx bx-save"></i> Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

            </div>

            {{-- Right Column --}}
            <div class="col-lg-4">

                {{-- Progress --}}
                <div class="card-modern mb-4">
                    <div class="card-header"><i class="bx bx-graph"></i> Progress</div>
                    <div class="card-body">
                        <div class="progress-modern mb-2" style="height:12px;">
                            <div class="progress-bar bg-{{ $workOrder->progress >= 100 ? 'success' : ($workOrder->progress >= 50 ? 'primary' : 'warning') }}" 
                                 style="width: {{ $workOrder->progress }}%;">
                            </div>
                            <span class="progress-label">{{ $workOrder->progress }}%</span>
                        </div>
                        <div class="text-center text-muted small">Overall Completion</div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="card-modern mb-4">
                    <div class="card-header"><i class="bx bx-calendar"></i> Timeline</div>
                    <div class="card-body">
                        <div class="timeline-item">
                            <span class="label">Scheduled</span>
                            <span class="value">{{ $workOrder->scheduled_at?->format('M d, Y H:i') ?? '-' }}</span>
                        </div>
                        <div class="timeline-item">
                            <span class="label">Due</span>
                            <span class="value {{ $workOrder->is_overdue ? 'text-danger' : '' }}">
                                {{ $workOrder->due_at?->format('M d, Y H:i') ?? '-' }}
                                @if($workOrder->is_overdue) <i class="bx bx-alarm-exclamation"></i> @endif
                            </span>
                        </div>
                        <div class="timeline-item">
                            <span class="label">Started</span>
                            <span class="value">{{ $workOrder->started_at?->format('M d, Y H:i') ?? '-' }}</span>
                        </div>
                        <div class="timeline-item">
                            <span class="label">Completed</span>
                            <span class="value">{{ $workOrder->completed_at?->format('M d, Y H:i') ?? '-' }}</span>
                        </div>
                        <div class="timeline-item">
                            <span class="label">Last Updated</span>
                            <span class="value">{{ $workOrder->updated_at?->diffForHumans() ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Financial & Time --}}
                <div class="card-modern mb-4">
                    <div class="card-header"><i class="bx bx-money"></i> Financial &amp; Time</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="label">Est. Cost</div>
                                    <div class="value text-primary">${{ number_format($workOrder->estimated_cost, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="label">Actual Cost</div>
                                    <div class="value {{ $workOrder->actual_cost > $workOrder->estimated_cost ? 'text-danger' : 'text-success' }}">
                                        ${{ number_format($workOrder->actual_cost, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="label">Est. Hours</div>
                                    <div class="value text-primary">{{ number_format($workOrder->estimated_hours, 2) }}h</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="label">Actual Hours</div>
                                    <div class="value {{ $workOrder->actual_hours > $workOrder->estimated_hours ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($workOrder->actual_hours, 2) }}h
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card-modern">
                    <div class="card-header"><i class="bx bx-cog"></i> Quick Actions</div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('work-orders.print', $workOrder) }}" class="btn btn-outline-primary" target="_blank">
                                <i class="bx bx-printer"></i> Print Work Order
                            </a>
                            <a href="{{ route('work-orders.download', $workOrder) }}" class="btn btn-outline-success">
                                <i class="bx bx-download"></i> Download PDF
                            </a>
                            <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn btn-outline-warning">
                                <i class="bx bx-edit"></i> Edit Work Order
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection