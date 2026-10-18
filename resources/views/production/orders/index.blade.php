@extends('inc.master')

@section('head')
<title>Production Order</title>
@endsection
@section('content')
@php
    $pageOrders = $orders->getCollection();
    $statusStyles = [
        'draft' => 'bg-secondary-subtle text-secondary',
        'pending' => 'bg-warning-subtle text-warning-emphasis',
        'approved' => 'bg-info-subtle text-info-emphasis',
        'in_progress' => 'bg-primary-subtle text-primary',
        'completed' => 'bg-success-subtle text-success',
        'cancelled' => 'bg-danger-subtle text-danger',
    ];
@endphp
<div class="production-page">
    <div class="production-container">
        <div class="production-header">
            <div>
                <h2 class="production-page-title"><i class="bi bi-gear-wide-connected me-2"></i>Production Orders</h2>
                <p class="production-page-subtitle">Track mill processing orders, planned quantities and production progress</p>
            </div>
            <a href="{{ route('production.orders.create') }}" class="btn btn-dark rounded-3 px-4"><i class="bi bi-plus-lg me-1"></i>Add Production Order</a>
        </div>
        @if(session('success'))
            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
        @endif
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3"><div class="card production-card production-stat-card"><div class="card-body d-flex align-items-center justify-content-between"><div><div class="production-stat-label">Total Orders</div><div class="production-stat-value">{{ number_format($orders->total()) }}</div></div><div class="production-stat-icon bg-soft-primary text-primary"><i class="bi bi-clipboard-data fs-4"></i></div></div></div></div>
            <div class="col-6 col-lg-3"><div class="card production-card production-stat-card"><div class="card-body d-flex align-items-center justify-content-between"><div><div class="production-stat-label">Pending (this page)</div><div class="production-stat-value text-warning">{{ $pageOrders->filter(fn($o) => in_array(strtolower(str_replace([' ', '-'], '_', trim((string) $o->status))), ['pending', 'draft']))->count() }}</div></div><div class="production-stat-icon text-warning"><i class="bi bi-clock fs-4"></i></div></div></div></div>
            <div class="col-6 col-lg-3"><div class="card production-card production-stat-card"><div class="card-body d-flex align-items-center justify-content-between"><div><div class="production-stat-label">In Progress (this page)</div><div class="production-stat-value text-primary">{{ $pageOrders->filter(fn($o) => strtolower(str_replace([' ', '-'], '_', trim((string) $o->status))) === 'in_progress')->count() }}</div></div><div class="production-stat-icon text-primary"><i class="bi bi-arrow-repeat fs-4"></i></div></div></div></div>
            <div class="col-6 col-lg-3"><div class="card production-card production-stat-card"><div class="card-body d-flex align-items-center justify-content-between"><div><div class="production-stat-label">Completed (this page)</div><div class="production-stat-value text-success">{{ $pageOrders->filter(fn($o) => strtolower(str_replace([' ', '-'], '_', trim((string) $o->status))) === 'completed')->count() }}</div></div><div class="production-stat-icon text-success"><i class="bi bi-check-circle fs-4"></i></div></div></div></div>
        </div>
        <div class="card production-filter-card mb-4"><div class="card-body p-3">
            <form method="GET" action="{{ route('production.orders.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-lg-9"><label for="order-search" class="form-label small fw-semibold text-muted">Search Production Orders</label><div class="production-search"><i class="bi bi-search"></i><input id="order-search" name="search" class="form-control rounded-3" value="{{ request('search') }}" placeholder="Search by order number..."></div></div>
                <div class="col-12 col-lg-3 d-flex gap-2"><button type="submit" class="btn btn-dark rounded-3 flex-fill"><i class="bi bi-funnel-fill me-1"></i>Search</button>@if(request()->filled('search'))<a href="{{ route('production.orders.index') }}" class="btn btn-outline-secondary rounded-3" title="Clear search"><i class="bi bi-x-lg"></i></a>@endif</div>
            </form>
        </div></div>
        <div class="card production-table-card"><div class="table-responsive production-table-responsive">
            <table class="table production-table align-middle" style="min-width:950px">
                <thead><tr><th class="first-column">Order</th><th>Product ID</th><th class="text-end">Ordered Quantity</th><th>Planned Start</th><th class="text-center">Status</th><th class="last-column text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($orders as $order)
                        @php $status = strtolower(str_replace([' ', '-'], '_', trim((string) ($order->status ?? '')))); @endphp
                        <tr>
                            <td class="first-column"><div class="d-flex align-items-center gap-3"><div class="production-avatar"><i class="bi bi-gear-wide-connected"></i></div><div><div class="fw-semibold text-dark">{{ $order->order_number ?: 'Order #'.$order->id }}</div><div class="text-muted small">Order ID: {{ $order->id }}</div></div></div></td>
                            <td>{{ $order->product_id ?? '—' }}</td>
                            <td class="text-end fw-semibold">{{ is_numeric($order->quantity_ordered) ? number_format((float)$order->quantity_ordered, 2) : ($order->quantity_ordered ?? '—') }}</td>
                            <td class="text-muted">{{ $order->planned_start_date ? \Carbon\Carbon::parse($order->planned_start_date)->format('d M Y') : '—' }}</td>
                            <td class="text-center"><span class="production-badge {{ $statusStyles[$status] ?? 'bg-light text-muted border' }}">{{ $status ? ucwords(str_replace('_', ' ', $status)) : '—' }}</span></td>
                            <td class="last-column"><div class="production-actions">
                                <a href="{{ route('production.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm production-action-btn" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('production.orders.edit', $order->id) }}" class="btn btn-outline-info btn-sm production-action-btn" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                <form method="POST" action="{{ route('production.orders.destroy', $order->id) }}" class="d-inline" onsubmit="return confirm('Delete this production order?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm production-action-btn" title="Delete"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5"><div class="production-empty-icon mb-3"><i class="bi bi-inboxes"></i></div><h5 class="text-muted">No production orders found</h5><p class="text-muted small">Create an order to start tracking mill production.</p><a href="{{ route('production.orders.create') }}" class="btn btn-dark btn-sm rounded-3">Add Production Order</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>@if($orders->hasPages())<div class="production-pagination">{{ $orders->appends(request()->query())->links() }}</div>@endif</div>
    </div>
</div>
@endsection
