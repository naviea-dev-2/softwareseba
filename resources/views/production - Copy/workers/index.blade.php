@extends('inc.master')

@section('content')

@php
    $statusBadges = [
        'active' => 'bg-success-subtle text-success border border-success-subtle',
        'inactive' => 'bg-danger-subtle text-danger border border-danger-subtle',
        'on_leave' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
    ];

    $statusIcons = [
        'active' => 'bi-check-circle-fill',
        'inactive' => 'bi-x-circle-fill',
        'on_leave' => 'bi-clock-fill',
    ];
@endphp

<div class="production-page">

    <div class="production-container">

        {{-- Header --}}
        <div class="production-header">

            <div>
                <h2 class="production-page-title">
                    <i class="bi bi-people me-2"></i>
                    Workers
                </h2>

                <p class="production-page-subtitle">
                    Manage your workforce and track employee status
                </p>
            </div>

            <a href="{{ route('production.workers.create') }}"
               class="btn btn-dark rounded-3 px-4">

                <i class="bi bi-plus-lg me-1"></i>
                Add Worker

            </a>

        </div>


        {{-- Stats --}}
        <div class="row g-3 mb-4">

            <div class="col-6 col-lg-3">

                <div class="card production-card production-card-hover production-stat-card">

                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div>

                            <div class="production-stat-label">
                                Total Workers
                            </div>

                            <div class="production-stat-value">
                                {{ $workers->total() }}
                            </div>

                        </div>

                        <div class="production-stat-icon bg-soft-primary bg-opacity-10 text-primary">

                            <i class="bi bi-people-fill fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-6 col-lg-3">

                <div class="card production-card production-card-hover production-stat-card">

                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div>

                            <div class="production-stat-label">
                                Active
                            </div>

                            <div class="production-stat-value text-success">
                                {{ $workers->where('status', 'active')->count() }}
                            </div>

                        </div>

                        <div class="production-stat-icon bg-soft-success bg-opacity-10 text-success">

                            <i class="bi bi-check-circle-fill fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-6 col-lg-3">

                <div class="card production-card production-card-hover production-stat-card">

                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div>

                            <div class="production-stat-label">
                                On Leave
                            </div>

                            <div class="production-stat-value text-warning">
                                {{ $workers->where('status', 'on_leave')->count() }}
                            </div>

                        </div>

                        <div class="production-stat-icon bg-soft-warning bg-opacity-10 text-warning">

                            <i class="bi bi-clock-fill fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-6 col-lg-3">

                <div class="card production-card production-card-hover production-stat-card">

                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div>

                            <div class="production-stat-label">
                                Departments
                            </div>

                            <div class="production-stat-value text-info">
                                {{ $workers->pluck('department')->unique()->filter()->count() }}
                            </div>

                        </div>

                        <div class="production-stat-icon bg-soft-info bg-opacity-10 text-info">

                            <i class="bi bi-building-fill fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Filters --}}
        <div class="card production-filter-card mb-4">

            <div class="card-body p-3">

                <form method="GET" class="row g-3 align-items-end">

                    <div class="col-12 col-lg-5">

                        <label class="form-label small fw-semibold text-muted">
                            Search
                        </label>

                        <div class="production-search">

                            <i class="bi bi-search"></i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control rounded-3"
                                placeholder="Search by name, code, department..."
                            >

                        </div>

                    </div>


                    <div class="col-6 col-lg-2">

                        <label class="form-label small fw-semibold text-muted">
                            Status
                        </label>

                        <select name="status" class="form-select rounded-3">

                            <option value="">All Status</option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                            <option value="on_leave"
                                {{ request('status') === 'on_leave' ? 'selected' : '' }}>
                                On Leave
                            </option>

                        </select>

                    </div>


                    <div class="col-6 col-lg-2">

                        <label class="form-label small fw-semibold text-muted">
                            Shift
                        </label>

                        <select name="shift" class="form-select rounded-3">

                            <option value="">All Shifts</option>

                            @foreach(['morning','evening','night'] as $shift)

                                <option value="{{ $shift }}"
                                    {{ request('shift') === $shift ? 'selected' : '' }}>

                                    {{ ucfirst($shift) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-12 col-lg-3 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-dark rounded-3 flex-fill">

                            <i class="bi bi-funnel-fill me-1"></i>
                            Filter

                        </button>


                        @if(request()->hasAny(['search', 'status', 'shift']))

                            <a href="{{ route('production.workers.index') }}"
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
                                Worker
                            </th>

                            <th>
                                Employee Code
                            </th>

                            <th>
                                Department
                            </th>

                            <th>
                                Designation
                            </th>

                            <th>
                                Shift
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

                        @forelse($workers as $item)

                            <tr>

                                {{-- Worker --}}
                                <td class="first-column">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="production-avatar">

                                            {{ collect(explode(' ', $item->name))
                                                ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                                                ->take(2)
                                                ->join('') }}

                                        </div>

                                        <div>

                                            <div class="fw-semibold text-dark">
                                                {{ $item->name }}
                                            </div>

                                            <div class="text-muted small">
                                                Added {{ $item->created_at?->diffForHumans() ?? 'recently' }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Code --}}
                                <td>

                                    <span class="production-code">
                                        #{{ $item->employee_code ?? 'N/A' }}
                                    </span>

                                </td>


                                {{-- Department --}}
                                <td class="text-muted">

                                    <i class="bi bi-building me-1"></i>

                                    {{ $item->department ?? '-' }}

                                </td>


                                {{-- Designation --}}
                                <td class="text-muted">

                                    {{ $item->designation ?? '-' }}

                                </td>


                                {{-- Shift --}}
                                <td class="text-muted">

                                    <i class="bi bi-clock me-1"></i>

                                    {{ $item->shift ? ucfirst($item->shift) : '-' }}

                                </td>


                                {{-- Status --}}
                                <td class="text-center">

                                    <span class="production-badge
                                        {{ $statusBadges[$item->status] ?? 'bg-light text-muted border' }}">

                                        @if(isset($statusIcons[$item->status]))
                                            <i class="bi {{ $statusIcons[$item->status] }} me-1"></i>
                                        @endif

                                        {{ ucwords(str_replace('_', ' ', $item->status ?? 'active')) }}

                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td class="last-column">

                                    <div class="production-actions">

                                        <a href="{{ route('production.workers.edit', $item) }}"
                                           class="btn btn-outline-info btn-sm production-action-btn"
                                           title="Edit">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route('production.workers.destroy', $item) }}"
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

                                        <i class="bi bi-people"></i>

                                    </div>

                                    <h5 class="fw-semibold text-muted">
                                        No workers found
                                    </h5>

                                    <p class="text-muted small mb-3">
                                        Try adjusting your search or filters.
                                    </p>

                                    @if(request()->hasAny(['search', 'status', 'shift']))

                                        <a href="{{ route('production.workers.index') }}"
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


            @if($workers->hasPages())

                <div class="production-pagination">

                    {{ $workers->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection