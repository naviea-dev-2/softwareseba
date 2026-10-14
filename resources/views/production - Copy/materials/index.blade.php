@extends('inc.master')

@section('content')

@php
    $unitLabels = [
        'kg' => 'Kilogram',
        'g' => 'Gram',
        'ton' => 'Ton',
        'litre' => 'Litre',
        'piece' => 'Piece',
        'meter' => 'Meter',
        'roll' => 'Roll',
        'box' => 'Box',
    ];
@endphp


<div class="production-page">

    <div class="production-container">

        {{-- Header --}}
        <div class="production-header">

            <div>

                <h2 class="production-page-title">

                    <i class="bi bi-box-seam me-2"></i>

                    Raw Materials

                </h2>

                <p class="production-page-subtitle">

                    Manage raw materials, suppliers and material costs

                </p>

            </div>


            <a href="{{ route('production.materials.create') }}"
               class="btn btn-dark rounded-3 px-4">

                <i class="bi bi-plus-lg me-1"></i>

                Add Material

            </a>

        </div>


        {{-- Search --}}
        <div class="card production-filter-card mb-4">

            <div class="card-body p-3">

                <form method="GET" class="row g-3 align-items-end">

                    <div class="col-12 col-lg-6">

                        <label class="form-label small fw-semibold text-muted">
                            Search Materials
                        </label>

                        <div class="production-search">

                            <i class="bi bi-search"></i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control rounded-3"
                                placeholder="Search by material name or SKU..."
                            >

                        </div>

                    </div>


                    <div class="col-12 col-lg-3">

                        <label class="form-label small fw-semibold text-muted">
                            Status
                        </label>

                        <select name="status" class="form-select rounded-3">

                            <option value="">
                                All Status
                            </option>

                            <option value="1"
                                {{ request('status') === '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ request('status') === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="col-12 col-lg-3 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-dark rounded-3 flex-fill">

                            <i class="bi bi-funnel-fill me-1"></i>

                            Filter

                        </button>


                        @if(request()->hasAny(['search', 'status']))

                            <a href="{{ route('production.materials.index') }}"
                               class="btn btn-outline-secondary rounded-3">

                                <i class="bi bi-x-lg"></i>

                            </a>

                        @endif

                    </div>

                </form>

            </div>

        </div>


        {{-- Table --}}
        <div class="card production-table-card">

            <div class="table-responsive production-table-responsive">

                <table class="table production-table align-middle"
                       style="min-width: 950px;">

                    <thead>

                        <tr>

                            <th class="first-column">
                                Material
                            </th>

                            <th>
                                SKU
                            </th>

                            <th>
                                Unit
                            </th>

                            <th class="text-end">
                                Cost / Unit
                            </th>

                            <th>
                                Supplier
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th class="last-column text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($materials as $item)

                            <tr>

                                {{-- Material --}}
                                <td class="first-column">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="production-avatar">

                                            <i class="bi bi-box-seam"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold text-dark">

                                                {{ $item->name }}

                                            </div>

                                            <div class="text-muted small">

                                                Added
                                                {{ $item->created_at?->diffForHumans() ?? 'recently' }}

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- SKU --}}
                                <td>

                                    <span class="production-code">

                                        #{{ $item->sku ?? 'N/A' }}

                                    </span>

                                </td>


                                {{-- Unit --}}
                                <td>

                                    <span class="production-badge bg-light text-dark border">

                                        {{ $unitLabels[$item->unit_of_measure] ?? $item->unit_of_measure }}

                                    </span>

                                </td>


                                {{-- Cost --}}
                                <td class="text-end fw-semibold">

                                    @if($item->cost_per_unit !== null)

                                        {{ number_format($item->cost_per_unit, 2) }}

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Supplier --}}
                                <td class="text-muted">

                                    @if($item->supplier)

                                        <div class="d-flex align-items-center gap-2">

                                            <i class="bi bi-truck"></i>

                                            {{ $item->supplier->name }}

                                        </div>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="text-center">

                                    @if($item->is_active)

                                        <span class="production-badge bg-success-subtle text-success border border-success-subtle">

                                            <i class="bi bi-check-circle-fill me-1"></i>

                                            Active

                                        </span>

                                    @else

                                        <span class="production-badge bg-danger-subtle text-danger border border-danger-subtle">

                                            <i class="bi bi-x-circle-fill me-1"></i>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="last-column">

                                    <div class="production-actions">

                                        <a href="{{ route('production.materials.edit', $item) }}"
                                           class="btn btn-outline-info btn-sm production-action-btn"
                                           title="Edit">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route('production.materials.destroy', $item) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete {{ $item->name }}? This cannot be undone.')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger btn-sm production-action-btn"
                                                title="Delete">

                                                <i class="bi bi-trash3"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-5">

                                    <div class="production-empty-icon mb-3">

                                        <i class="bi bi-box-seam"></i>

                                    </div>

                                    <h5 class="fw-semibold text-muted">
                                        No materials found
                                    </h5>

                                    <p class="text-muted small mb-3">
                                        Try adjusting your search or filters.
                                    </p>

                                    @if(request()->hasAny(['search', 'status']))

                                        <a href="{{ route('production.materials.index') }}"
                                           class="btn btn-outline-dark btn-sm rounded-3">

                                            Clear all filters

                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            @if($materials->hasPages())

                <div class="production-pagination">

                    {{ $materials->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection