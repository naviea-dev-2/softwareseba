
@extends('inc.master')

@section('head')
<title>RAW Materials Stock</title>
@endsection
@section('content')

@php
    $stockCollection = $stocks->getCollection();

    $totalQuantity = $stockCollection->sum('quantity');
    $totalReserved = $stockCollection->sum('reserved_quantity');

    $availableQuantity = $totalQuantity - $totalReserved;

    $expiredCount = $stockCollection->filter(function ($item) {
        return $item->expiry_date &&
            \Carbon\Carbon::parse($item->expiry_date)->isPast();
    })->count();
@endphp

<div class="production-page">

    <div class="production-container">

        {{-- Header --}}
        <div class="production-header">

            <div>
                <h2 class="production-page-title">
                    <i class="bi bi-box-seam me-2"></i>
                    Material Stock
                </h2>

                <p class="production-page-subtitle">
                    Manage raw material inventory, stock quantities,
                    reservations and expiry dates
                </p>
            </div>

            <a href="{{ route('production.material-stock.create') }}"
               class="btn btn-dark rounded-3 px-4">

                <i class="bi bi-plus-lg me-1"></i>
                Add Stock

            </a>

        </div>


        {{-- Statistics --}}
        <div class="row g-3 mb-4">

            {{-- Total Stock Records --}}
            <div class="col-6 col-lg-3">

                <div class="card production-card production-card-hover production-stat-card">

                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div>
                            <div class="production-stat-label">
                                Total Stock Records
                            </div>

                            <div class="production-stat-value">
                                {{ number_format($stocks->total()) }}
                            </div>
                        </div>

                        <div class="production-stat-icon bg-soft-primary bg-opacity-10 text-primary">
                            <i class="bi bi-box-seam-fill fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Total Quantity --}}
            <div class="col-6 col-lg-3">

                <div class="card production-card production-card-hover production-stat-card">

                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div>
                            <div class="production-stat-label">
                                Total Quantity
                            </div>

                            <div class="production-stat-value text-success">
                                {{ number_format($totalQuantity, 2) }}
                            </div>
                        </div>

                        <div class="production-stat-icon bg-soft-success bg-opacity-10 text-success">
                            <i class="bi bi-boxes fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Reserved Quantity --}}
            <div class="col-6 col-lg-3">

                <div class="card production-card production-card-hover production-stat-card">

                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div>
                            <div class="production-stat-label">
                                Reserved Quantity
                            </div>

                            <div class="production-stat-value text-warning">
                                {{ number_format($totalReserved, 2) }}
                            </div>
                        </div>

                        <div class="production-stat-icon bg-soft-warning bg-opacity-10 text-warning">
                            <i class="bi bi-lock-fill fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Available Quantity --}}
            <div class="col-6 col-lg-3">

                <div class="card production-card production-card-hover production-stat-card">

                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div>
                            <div class="production-stat-label">
                                Available Quantity
                            </div>

                            <div class="production-stat-value text-info">
                                {{ number_format($availableQuantity, 2) }}
                            </div>
                        </div>

                        <div class="production-stat-icon bg-soft-info bg-opacity-10 text-info">
                            <i class="bi bi-check2-square fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Filters --}}
        <div class="card production-filter-card mb-4">

            <div class="card-body p-3">

                <form method="GET"
                      action="{{ route('production.material-stock.index') }}"
                      class="row g-3 align-items-end">

                    <div class="col-12 col-lg-9">

                        <label class="form-label small fw-semibold text-muted">
                            Search Material Stock
                        </label>

                        <div class="production-search">

                            <i class="bi bi-search"></i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control rounded-3"
                                placeholder="Search by material, batch number, location..."
                            >

                        </div>

                    </div>


                    <div class="col-12 col-lg-3 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-dark rounded-3 flex-fill">

                            <i class="bi bi-funnel-fill me-1"></i>
                            Filter

                        </button>

                        @if(request()->filled('search'))

                            <a href="{{ route('production.material-stock.index') }}"
                               class="btn btn-outline-secondary rounded-3"
                               title="Clear Filters">

                                <i class="bi bi-x-lg"></i>

                            </a>

                        @endif

                    </div>

                </form>

            </div>

        </div>


        {{-- Stock Table --}}
        <div class="card production-table-card">

            <div class="table-responsive production-table-responsive">

                <table class="table production-table align-middle"
                       style="min-width: 1050px;">

                    <thead>
                        <tr>

                            <th class="first-column">
                                Material
                            </th>

                            <th>
                                Batch Number
                            </th>

                            <th class="text-end">
                                Quantity
                            </th>

                            <th class="text-end">
                                Reserved
                            </th>

                            <th class="text-end">
                                Available
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Expiry Date
                            </th>

                            <th class="last-column text-end">
                                Actions
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        @forelse($stocks as $item)

                            @php
                                $quantity = (float) ($item->quantity ?? 0);
                                $reserved = (float) ($item->reserved_quantity ?? 0);
                                $available = $quantity - $reserved;

                                $expiryDate = $item->expiry_date
                                    ? \Carbon\Carbon::parse($item->expiry_date)
                                    : null;

                                $isExpired = $expiryDate &&
                                    $expiryDate->isPast();

                                $isExpiringSoon = $expiryDate &&
                                    !$isExpired &&
                                    $expiryDate->lte(now()->addDays(30));
                            @endphp

                            <tr>

                                {{-- Material --}}
                                <td class="first-column">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="production-avatar">

                                            <i class="bi bi-box-seam"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold text-dark">

                                                {{ $item->rawMaterial?->name ?? 'Material #' . $item->raw_material_id }}

                                            </div>

                                            <div class="text-muted small">

                                                Added
                                                {{ $item->created_at?->diffForHumans() ?? 'recently' }}

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Batch --}}
                                <td>

                                    <span class="production-code">

                                        {{ $item->batch_number ?? 'N/A' }}

                                    </span>

                                </td>


                                {{-- Quantity --}}
                                <td class="text-end">

                                    <span class="fw-semibold text-dark">

                                        {{ number_format($quantity, 2) }}

                                    </span>

                                </td>


                                {{-- Reserved --}}
                                <td class="text-end">

                                    <span class="fw-semibold text-warning">

                                        {{ number_format($reserved, 2) }}

                                    </span>

                                </td>


                                {{-- Available --}}
                                <td class="text-end">

                                    <span class="production-badge
                                        {{ $available > 0
                                            ? 'bg-success-subtle text-success border border-success-subtle'
                                            : 'bg-danger-subtle text-danger border border-danger-subtle' }}">

                                        <i class="bi
                                            {{ $available > 0
                                                ? 'bi-check-circle-fill'
                                                : 'bi-exclamation-circle-fill' }}
                                            me-1"></i>

                                        {{ number_format($available, 2) }}

                                    </span>

                                </td>


                                {{-- Location --}}
                                <td class="text-muted">

                                    <i class="bi bi-geo-alt me-1"></i>

                                    {{ $item->location ?? '-' }}

                                </td>


                                {{-- Expiry --}}
                                <td>

                                    @if($expiryDate)

                                        <div class="d-flex flex-column align-items-start gap-1">

                                            <span class="text-muted small">

                                                <i class="bi bi-calendar3 me-1"></i>

                                                {{ $expiryDate->format('d M Y') }}

                                            </span>

                                            @if($isExpired)

                                                <span class="production-badge bg-danger-subtle text-danger border border-danger-subtle">

                                                    <i class="bi bi-x-circle-fill me-1"></i>
                                                    Expired

                                                </span>

                                            @elseif($isExpiringSoon)

                                                <span class="production-badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">

                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                    Expiring Soon

                                                </span>

                                            @endif

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="last-column">

                                    <div class="production-actions">

                                        <a href="{{ route('production.material-stock.edit', $item) }}"
                                           class="btn btn-outline-info btn-sm production-action-btn"
                                           title="Edit Stock">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route('production.material-stock.destroy', $item) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete this material stock record? This cannot be undone.')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger btn-sm production-action-btn"
                                                title="Delete Stock">

                                                <i class="bi bi-trash3"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-5">

                                    <div class="production-empty-icon mb-3">

                                        <i class="bi bi-box-seam"></i>

                                    </div>

                                    <h5 class="fw-semibold text-muted">

                                        No material stock records found

                                    </h5>

                                    <p class="text-muted small mb-3">

                                        Try adjusting your search or add a new stock record.

                                    </p>

                                    @if(request()->filled('search'))

                                        <a href="{{ route('production.material-stock.index') }}"
                                           class="btn btn-outline-dark btn-sm rounded-3">

                                            Clear all filters

                                        </a>

                                    @else

                                        <a href="{{ route('production.material-stock.create') }}"
                                           class="btn btn-dark btn-sm rounded-3">

                                            <i class="bi bi-plus-lg me-1"></i>
                                            Add Stock

                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($stocks->hasPages())

                <div class="production-pagination">

                    {{ $stocks->appends(request()->query())->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
