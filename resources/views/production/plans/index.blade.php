@extends('inc.master')

@section('head')
<title>Production Plan</title>
@endsection
@section('content')
@php
    // These status counts refer to the current page of paginated results.
    $pagePlans = $plans->getCollection();
    // Controller should pass $planStatusCounts for database-wide counts.
    $counts = $planStatusCounts ?? null;
    $countStatus = fn ($status) => $counts !== null
        ? (int) ($counts[$status] ?? 0)
        : $pagePlans->filter(fn ($plan) => str_replace([' ', '-'], '_', strtolower(trim((string) $plan->status))) === $status)->count();
    $statusStyles = [
        'draft' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
        'pending' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
        'approved' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
        'in_progress' => 'bg-primary-subtle text-primary border border-primary-subtle',
        'completed' => 'bg-success-subtle text-success border border-success-subtle',
        'cancelled' => 'bg-danger-subtle text-danger border border-danger-subtle',
    ];
    $statusIcons = [
        'draft' => 'bi-file-earmark', 'pending' => 'bi-clock',
        'approved' => 'bi-check-circle', 'in_progress' => 'bi-arrow-repeat',
        'completed' => 'bi-check-circle-fill', 'cancelled' => 'bi-x-circle-fill',
    ];
@endphp

<div class="production-page">
    <div class="production-container">
        {{-- Header --}}
        <div class="production-header">
            <div>
                <h2 class="production-page-title"><i class="bi bi-clipboard2-check me-2"></i>Production Plans</h2>
                <p class="production-page-subtitle">Manage production schedules, targets and progress</p>
            </div>
            <a href="{{ route('production.plans.create') }}" class="btn btn-dark rounded-3 px-4">
                <i class="bi bi-plus-lg me-1"></i>Add Production Plan
            </a>
        </div>

        {{-- Controller-provided $planStatusCounts gives database-wide totals; otherwise counts are page-level. --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card production-card production-card-hover production-stat-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div><div class="production-stat-label">Total Plans</div><div class="production-stat-value">{{ number_format($plans->total()) }}</div></div>
                        <div class="production-stat-icon bg-soft-primary bg-opacity-10 text-primary"><i class="bi bi-clipboard-data-fill fs-4"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="col-6 col-lg">
                <div class="card production-card production-card-hover production-stat-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div><div class="production-stat-label">Pending</div><div class="production-stat-value text-warning">{{ $countStatus('pending') }}</div></div>
                        <div class="production-stat-icon bg-soft-warning bg-opacity-10 text-warning"><i class="bi bi-clock-fill fs-4"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg">
                <div class="card production-card production-card-hover production-stat-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div><div class="production-stat-label">Approved</div><div class="production-stat-value text-primary">{{ $countStatus('approved') }}</div></div>
                        <div class="production-stat-icon bg-soft-primary bg-opacity-10 text-primary"><i class="bi bi-arrow-repeat fs-4"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg">
                <div class="card production-card production-card-hover production-stat-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div><div class="production-stat-label">Completed</div><div class="production-stat-value text-success">{{ $countStatus('completed') }}</div></div>
                        <div class="production-stat-icon bg-soft-success bg-opacity-10 text-success"><i class="bi bi-check-circle-fill fs-4"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg">
                <div class="card production-card production-card-hover production-stat-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div><div class="production-stat-label">Cancelled</div><div class="production-stat-value text-danger">{{ $countStatus('cancelled') }}</div></div>
                        <div class="production-stat-icon bg-soft-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle-fill fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="card production-filter-card mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('production.plans.index') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-lg-9">
                        <label for="plan-search" class="form-label small fw-semibold text-muted">Search Production Plans</label>
                        <div class="production-search">
                            <i class="bi bi-search"></i>
                            <input id="plan-search" type="text" name="search" value="{{ request('search') }}" class="form-control rounded-3" placeholder="Search by plan number...">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-dark rounded-3 flex-fill"><i class="bi bi-funnel-fill me-1"></i>Filter</button>
                        @if(request()->filled('search'))
                            <a href="{{ route('production.plans.index') }}" class="btn btn-outline-secondary rounded-3" title="Clear Search" aria-label="Clear Search"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card production-table-card">
            <div class="table-responsive production-table-responsive">
                <table class="table production-table align-middle" style="min-width: 950px;">
                    <thead><tr>
                        <th class="first-column">Plan</th>
                        <th>Period Start</th>
                        <th>Period End</th>
                        <th class="text-end">Target Quantity</th>
                        <th class="text-center">Status</th>
                        <th class="last-column text-end">Actions</th>
                    </tr></thead>
                    <tbody>
                    @forelse($plans as $plan)
                        @php
                            $status = strtolower(trim((string) ($plan->status ?? '')));
                            $status = str_replace([' ', '-'], '_', $status);
                        @endphp
                        <tr>
                            <td class="first-column">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="production-avatar"><i class="bi bi-clipboard2-check"></i></div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $plan->plan_number ?? 'Plan #' . $plan->id }}</div>
                                        <div class="text-muted small">Added {{ $plan->created_at?->diffForHumans() ?? 'recently' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted"><i class="bi bi-calendar-event me-1"></i>{{ $plan->plan_period_start ? \Carbon\Carbon::parse($plan->plan_period_start)->format('d M Y') : '—' }}</td>
                            <td class="text-muted"><i class="bi bi-calendar-check me-1"></i>{{ $plan->plan_period_end ? \Carbon\Carbon::parse($plan->plan_period_end)->format('d M Y') : '—' }}</td>
                            <td class="text-end"><span class="fw-semibold text-dark">{{ is_numeric($plan->target_quantity) ? number_format((float) $plan->target_quantity, 2) : ($plan->target_quantity ?? '—') }}</span></td>
                            <td class="text-center">
                                <span class="production-badge {{ $statusStyles[$status] ?? 'bg-light text-muted border' }}">
                                    <i class="bi {{ $statusIcons[$status] ?? 'bi-circle' }} me-1"></i>
                                    {{ $status ? ucwords(str_replace('_', ' ', $status)) : '—' }}
                                </span>
                            </td>
                            <td class="last-column">
                                <div class="production-actions">
                                    <a href="{{ route('production.plans.show', $plan->id) }}" class="btn btn-outline-secondary btn-sm production-action-btn" title="View" aria-label="View Plan"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('production.plans.edit', $plan->id) }}" class="btn btn-outline-info btn-sm production-action-btn" title="Edit" aria-label="Edit Plan"><i class="bi bi-pencil-square"></i></a>
                                    <form method="POST" action="{{ route('production.plans.destroy', $plan->id) }}" class="d-inline" onsubmit="return confirm('Delete this production plan? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm production-action-btn" title="Delete" aria-label="Delete Plan"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5">
                            <div class="production-empty-icon mb-3"><i class="bi bi-clipboard2-x"></i></div>
                            <h5 class="fw-semibold text-muted">No production plans found</h5>
                            <p class="text-muted small mb-3">{{ request()->filled('search') ? 'Try another search term.' : 'Create your first production plan to get started.' }}</p>
                            @if(request()->filled('search'))
                                <a href="{{ route('production.plans.index') }}" class="btn btn-outline-dark btn-sm rounded-3">Clear Search</a>
                            @else
                                <a href="{{ route('production.plans.create') }}" class="btn btn-dark btn-sm rounded-3"><i class="bi bi-plus-lg me-1"></i>Add Production Plan</a>
                            @endif
                        </td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($plans->hasPages())
                <div class="production-pagination">{{ $plans->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
