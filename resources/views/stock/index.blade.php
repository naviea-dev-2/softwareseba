@extends('inc.master')

@section('head')
    <title>Stock & Inventory</title>
    <style>
        .nav-tabs .nav-link { font-weight: 500; color: #6c757d; border: none; border-bottom: 2px solid transparent; padding: .75rem 1rem; }
        .nav-tabs .nav-link.active { color: #0d6efd; border-bottom: 2px solid #0d6efd; background: transparent; }
        .nav-tabs .nav-link:hover { border-color: #dee2e6; }
        .stat-card { transition: transform .15s; }
        .stat-card:hover { transform: translateY(-2px); }
        .table-sm td, .table-sm th { vertical-align: middle; font-size: 0.875rem; }
        .badge-pill { font-size: 0.75rem; padding: 0.35em 0.65em; border-radius: 50rem; }
    </style>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1"><b>Stock & Inventory</b></h4>
                <small class="text-muted">Manage stock across warehouses, track movements, reservations and transfers.</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('stock.adjust') }}" class="btn btn-outline-primary">
                    <i class="bx bx-transfer"></i> Adjust Stock
                </a>
                <a href="{{ route('stock.transfers.create') }}" class="btn btn-outline-secondary">
                    <i class="bx bx-transfer-alt"></i> Transfer
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total SKUs</div>
                        <h3 class="fw-bold text-primary mt-2 mb-0">{{ number_format($totalSkus) }}</h3>
                        <small class="text-muted">Across all warehouses</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Low Stock</div>
                        <h3 class="fw-bold text-warning mt-2 mb-0">{{ number_format($lowStock) }}</h3>
                        <small class="text-muted">Below reorder point</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Reserved Stock</div>
                        <h3 class="fw-bold text-info mt-2 mb-0">{{ number_format($reservedStock) }}</h3>
                        <small class="text-muted">For pending orders</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Pending Transfers</div>
                        <h3 class="fw-bold text-success mt-2 mb-0">{{ number_format($pendingTransfers) }}</h3>
                        <small class="text-muted">Pending / in transit</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Critical Stock</div>
                        <h3 class="fw-bold text-danger mt-2 mb-0">{{ number_format($criticalStock) }}</h3>
                        <small class="text-muted">Zero or negative</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Movements</div>
                        <h3 class="fw-bold text-secondary mt-2 mb-0">{{ number_format($totalMovements) }}</h3>
                        <small class="text-muted">This month</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs Card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-3">
                <ul class="nav nav-tabs card-header-tabs" id="stockTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="ledger-tab" data-bs-toggle="tab" data-bs-target="#ledger" type="button" role="tab">
                            <i class="bx bx-list-ul"></i> Stock Ledger
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reservations-tab" data-bs-toggle="tab" data-bs-target="#reservations" type="button" role="tab">
                            <i class="bx bx-package"></i> Reservations
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="movements-tab" data-bs-toggle="tab" data-bs-target="#movements" type="button" role="tab">
                            <i class="bx bx-history"></i> Movements
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="transfers-tab" data-bs-toggle="tab" data-bs-target="#transfers" type="button" role="tab">
                            <i class="bx bx-transfer-alt"></i> Transfers
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="warehouses-tab" data-bs-toggle="tab" data-bs-target="#warehouses" type="button" role="tab">
                            <i class="bx bx-buildings"></i> Warehouses
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                            <i class="bx bx-bar-chart"></i> Analytics
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="stockTabContent">

                    {{-- === TAB 1: Stock Ledger === --}}
                    <div class="tab-pane fade show active" id="ledger" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold">Current Stock Balances</h6>
                            <a href="{{ route('stock.ledger') }}" class="btn btn-sm btn-primary">View Full Ledger</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Warehouse</th>
                                        <th class="text-end">Available</th>
                                        <th class="text-end">Reserved</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ledger as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $item->product->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $item->product->sku ?? '' }}</small>
                                        </td>
                                        <td>{{ $item->warehouse->name ?? 'N/A' }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->available_qty, 2) }}</td>
                                        <td class="text-end text-muted">{{ number_format($item->reserved_qty, 2) }}</td>
                                        <td class="text-center">
                                            @php
                                                $avail = $item->available_qty;
                                                if ($avail <= 0) { $cls = 'bg-dark'; $txt = 'Out of Stock'; }
                                                elseif ($avail <= $item->critical_level) { $cls = 'bg-danger'; $txt = 'Critical'; }
                                                elseif ($avail <= $item->reorder_point) { $cls = 'bg-warning text-dark'; $txt = 'Low'; }
                                                else { $cls = 'bg-success'; $txt = 'Normal'; }
                                            @endphp
                                            <span class="badge {{ $cls }} badge-pill">{{ $txt }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No stock records found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- === TAB 2: Reservations === --}}
                    <div class="tab-pane fade" id="reservations" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold">Stock Reservations</h6>
                            <a href="{{ route('stock.reservations') }}" class="btn btn-sm btn-primary">Manage Reservations</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Warehouse</th>
                                        <th class="text-end">Qty</th>
                                        <th>Reference</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reservations as $r)
                                    <tr>
                                        <td>{{ $r->product->name ?? 'N/A' }}</td>
                                        <td>{{ $r->warehouse->name ?? 'N/A' }}</td>
                                        <td class="text-end">{{ number_format($r->quantity, 2) }}</td>
                                        <td><small>{{ $r->reference ?? '-' }}</small></td>
                                        <td>
                                            @php
                                                $badge = match($r->status ?? '') {
                                                    'pending', 'reserved' => 'bg-warning text-dark',
                                                    'fulfilled' => 'bg-success',
                                                    'cancelled' => 'bg-secondary',
                                                    default => 'bg-info'
                                                };
                                            @endphp
                                            <span class="badge {{ $badge }} badge-pill text-capitalize">{{ $r->status ?? 'N/A' }}</span>
                                        </td>
                                        <td><small>{{ $r->created_at?->format('M d, Y') }}</small></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No reservations found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- === TAB 3: Movements === --}}
                    <div class="tab-pane fade" id="movements" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold">Recent Movements</h6>
                            <a href="{{ route('stock.movements') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th class="text-end">Qty</th>
                                        <th>Reason</th>
                                        <th>By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($movements as $m)
                                    <tr>
                                        <td><small>{{ $m->created_at?->format('M d, H:i') }}</small></td>
                                        <td>{{ $m->product->name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $type = $m->type ?? 'adjustment';
                                                $tBadge = match(true) {
                                                    str_contains($type, 'in') => 'bg-success',
                                                    str_contains($type, 'out') => 'bg-danger',
                                                    str_contains($type, 'transfer') => 'bg-info',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $tBadge }} badge-pill text-capitalize">{{ str_replace('_', ' ', $type) }}</span>
                                        </td>
                                        <td class="text-end">{{ number_format($m->quantity, 2) }}</td>
                                        <td><small>{{ \Illuminate\Support\Str::limit($m->reason, 35) }}</small></td>
                                        <td><small>{{ $m->user->name ?? 'System' }}</small></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No movements this month.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- === TAB 4: Transfers === --}}
                    <div class="tab-pane fade" id="transfers" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold">Warehouse Transfers</h6>
                            <a href="{{ route('stock.transfers') }}" class="btn btn-sm btn-primary">All Transfers</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ref #</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transfers as $t)
                                    <tr>
                                        <td><small class="text-muted">#{{ $t->id }}</small></td>
                                        <td>{{ $t->fromWarehouse->name ?? 'N/A' }}</td>
                                        <td>{{ $t->toWarehouse->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($t->items && $t->items->count())
                                                {{ $t->items->first()->product->name ?? 'N/A' }}
                                                @if($t->items->count() > 1)
                                                    <small class="text-muted">+{{ $t->items->count() - 1 }}</small>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $s = $t->status ?? 'pending';
                                                $sBadge = match($s) {
                                                    'pending' => 'bg-warning text-dark',
                                                    'approved' => 'bg-info',
                                                    'shipped', 'in_transit' => 'bg-primary',
                                                    'received' => 'bg-success',
                                                    'cancelled' => 'bg-secondary',
                                                    default => 'bg-light text-dark'
                                                };
                                            @endphp
                                            <span class="badge {{ $sBadge }} badge-pill text-capitalize">{{ str_replace('_', ' ', $s) }}</span>
                                        </td>
                                        <td><small>{{ $t->created_at?->format('M d, Y') }}</small></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No transfers found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- === TAB 5: Warehouses === --}}
                    <div class="tab-pane fade" id="warehouses" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold">Warehouse Overview</h6>
                            <a href="{{ route('stock.warehouses.index') }}" class="btn btn-sm btn-primary">Manage Warehouses</a>
                        </div>
                        <div class="row g-3">
                            @forelse($warehouses as $wh)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $wh->name }}</h6>
                                                <small class="text-muted">{{ $wh->location ?? 'No location' }}</small>
                                            </div>
                                            <span class="badge {{ $wh->is_active ? 'bg-success' : 'bg-secondary' }} badge-pill">
                                                {{ $wh->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        <div class="row mt-3 text-center">
                                            <div class="col-6 border-end">
                                                <div class="text-muted small">SKU Count</div>
                                                <div class="fw-bold">{{ $wh->skus_count ?? 0 }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">Total Qty</div>
                                                <div class="fw-bold text-primary">{{ number_format($wh->total_qty ?? 0) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center text-muted py-4">No warehouses configured.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- === TAB 6: Analytics === --}}
                    <div class="tab-pane fade" id="analytics" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold">Top Moving Products (This Month)</h6>
                            <a href="{{ route('stock.analytics') }}" class="btn btn-sm btn-primary">Full Analytics</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th class="text-end">Total Moved</th>
                                        <th style="width:40%">Activity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $idx => $tp)
                                    @php $max = $topProducts->first()->total_moved ?: 1; @endphp
                                    <tr>
                                        <td class="text-muted">{{ $idx + 1 }}</td>
                                        <td>{{ $tp->product->name ?? 'N/A' }}</td>
                                        <td class="text-end fw-bold">{{ number_format($tp->total_moved, 2) }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-primary" style="width: {{ min(100, ($tp->total_moved / $max) * 100) }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">No movement data this month.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection