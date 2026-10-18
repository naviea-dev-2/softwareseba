@extends('inc.master')

@section('head')
<title>Production Capacity</title>
@endsection
@section('content')
<style>
.capacity-page{max-width:1440px;margin:0 auto;padding:26px 18px 48px;color:#172b49}
.capacity-header{display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap;margin-bottom:24px}
.capacity-heading{display:flex;align-items:center;gap:16px}
.capacity-heading-icon,.capacity-panel-icon{display:grid;place-items:center;background:#e7f0ff;color:#2563eb;border-radius:14px;flex-shrink:0}
.capacity-heading-icon{width:62px;height:62px;font-size:29px}.capacity-panel-icon{width:44px;height:44px;font-size:22px}
.capacity-title{font-size:27px;font-weight:750;margin:0}.capacity-subtitle{color:#71839a;margin:5px 0 0}
.capacity-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;border:1px solid transparent;border-radius:9px;padding:11px 17px;font-weight:650;font-size:13px;text-decoration:none!important;cursor:pointer;white-space:nowrap}
.capacity-btn.primary{background:#2563eb;color:white!important}.capacity-btn.primary:hover{background:#1d4ed8}
.capacity-btn.outline{background:#fff;color:#263b58!important;border-color:#d8e2f0}.capacity-btn.outline:hover{background:#f4f7fc}
.capacity-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;margin-bottom:24px}
.capacity-stat{display:flex;gap:17px;align-items:center;border-radius:13px;padding:23px 22px;min-height:133px;border:1px solid}
.capacity-stat .stat-icon{width:66px;height:66px;border-radius:50%;display:grid;place-items:center;flex-shrink:0;font-size:29px}
.capacity-stat .stat-label{font-size:14px;font-weight:700}.capacity-stat .stat-number{font-size:30px;font-weight:800;line-height:1.25;margin:5px 0}.capacity-stat .stat-help{font-size:12px;color:#627894}
.capacity-stat.blue{background:#f0f7ff;border-color:#d5e8ff}.capacity-stat.blue .stat-icon{background:#dfedff;color:#2563eb}
.capacity-stat.green{background:#f1fbf6;border-color:#d5f0e2}.capacity-stat.green .stat-icon{background:#d9f7e5;color:#07944b}
.capacity-stat.amber{background:#fffbf0;border-color:#ffebc5}.capacity-stat.amber .stat-icon{background:#fff1cf;color:#d58a00}
.capacity-stat.purple{background:#f8f5ff;border-color:#e7dcff}.capacity-stat.purple .stat-icon{background:#ebe2ff;color:#784bd4}
.capacity-panel{background:#fff;border:1px solid #e2eaf4;border-radius:16px;box-shadow:0 8px 30px rgba(22,47,80,.045);padding:24px}
.capacity-panel-head{display:flex;align-items:center;gap:15px;margin-bottom:22px}.capacity-panel-head h3{font-size:19px;font-weight:750;margin:0}.capacity-panel-head p{margin:4px 0 0;color:#71839a;font-size:13px}
.capacity-toolbar{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}.capacity-search{display:flex;gap:10px;flex:1;min-width:260px}.capacity-search input{height:47px;flex:1;min-width:0;border:1px solid #d7e1ee;border-radius:9px;padding:0 15px;font-size:14px;color:#243b59;outline:none}.capacity-search input:focus{border-color:#80aaff;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.capacity-table-wrap{overflow-x:auto;border:1px solid #e1e9f3;border-radius:10px}.capacity-table{width:100%;border-collapse:collapse;min-width:850px}.capacity-table thead th{background:#2a405a;color:#fff;text-transform:uppercase;letter-spacing:.045em;font-size:12px;font-weight:750;text-align:left;padding:16px 15px;white-space:nowrap}.capacity-table tbody td{border-bottom:1px solid #e7edf5;padding:15px;font-size:13px;vertical-align:middle}.capacity-table tbody tr:nth-child(even){background:#f8fbff}.capacity-table tbody tr:hover{background:#eef6ff}.capacity-table tbody tr:last-child td{border-bottom:0}
.capacity-tag{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:7px 11px;font-size:12px;font-weight:700;background:#e9f2ff;color:#1d5dc6}.capacity-tag.night{background:#f0eaff;color:#6f45c7}.capacity-tag.evening{background:#fff3df;color:#a86a00}
.capacity-rate{display:inline-block;border-radius:999px;background:#e7f8ef;color:#078342;font-weight:750;padding:7px 11px;font-size:12px}.capacity-rate.low{background:#fff2e0;color:#ad6500}
.capacity-actions{display:flex;gap:7px;align-items:center;flex-wrap:wrap}.capacity-action{display:inline-flex;gap:5px;align-items:center;border:0;border-radius:8px;padding:9px 11px;font-size:12px;font-weight:650;text-decoration:none!important;cursor:pointer}.capacity-action.view{background:#eaf3ff;color:#145ed6!important}.capacity-action.edit{background:#eef2f7;color:#2a405a!important}.capacity-action.delete{background:#ffeded;color:#c71e25!important}
.capacity-footer{margin-top:20px}.capacity-footer .pagination{margin-bottom:0;gap:4px}.capacity-footer .page-link{border-radius:7px;color:#2756a6}
@media(max-width:1100px){.capacity-stats{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:650px){.capacity-page{padding:16px 10px}.capacity-stats{grid-template-columns:1fr;gap:10px}.capacity-stat{min-height:105px;padding:17px}.capacity-panel{padding:16px}.capacity-title{font-size:22px}.capacity-search{min-width:100%;flex-wrap:wrap}.capacity-search input{min-width:180px}}
</style>

@php
    // These statistics use the records in the current paginator page only.
    $pageCapacities = collect($capacities->items());
    $pageCount = $pageCapacities->count();
    $averageEfficiency = $pageCapacities->filter(fn ($item) => is_numeric($item->efficiency_rate))->avg('efficiency_rate');
    $totalHourlyCapacity = $pageCapacities->sum(fn ($item) => (float) ($item->max_units_per_hour ?? 0));
    $shiftCount = $pageCapacities->pluck('shift')->filter()->unique()->count();
@endphp

<div class="capacity-page">
    <div class="capacity-header">
        <div class="capacity-heading">
            <div class="capacity-heading-icon"><i class="bx bx-cog"></i></div>
            <div><h2 class="capacity-title">Production Capacities</h2><p class="capacity-subtitle">Manage machine, worker and shift production capacity</p></div>
        </div>
        <a class="capacity-btn primary" href="{{ route('production.capacities.create') }}"><i class="bx bx-plus"></i> Add New Capacity</a>
    </div>

    <div class="capacity-stats">
        <div class="capacity-stat blue"><div class="stat-icon"><i class="bx bx-list-check"></i></div><div><div class="stat-label">Total Records</div><div class="stat-number">{{ number_format($capacities->total()) }}</div><div class="stat-help">All matching capacity records</div></div></div>
        <div class="capacity-stat green"><div class="stat-icon"><i class="bx bx-tachometer"></i></div><div><div class="stat-label">Hourly Capacity</div><div class="stat-number">{{ number_format($totalHourlyCapacity, 2) }}</div><div class="stat-help">Units/hour · current page</div></div></div>
        <div class="capacity-stat amber"><div class="stat-icon"><i class="bx bx-trending-up"></i></div><div><div class="stat-label">Average Efficiency</div><div class="stat-number">{{ $averageEfficiency === null ? '—' : number_format($averageEfficiency, 1).'%' }}</div><div class="stat-help">Current page</div></div></div>
        <div class="capacity-stat purple"><div class="stat-icon"><i class="bx bx-time-five"></i></div><div><div class="stat-label">Distinct Shifts</div><div class="stat-number">{{ $shiftCount }}</div><div class="stat-help">Represented on current page</div></div></div>
    </div>

    <div class="capacity-panel">
        <div class="capacity-panel-head"><div class="capacity-panel-icon"><i class="bx bx-table"></i></div><div><h3>Capacity List</h3><p>View and manage production capacity assignments</p></div></div>
        <div class="capacity-toolbar">
            <form method="GET" action="{{ route('production.capacities.index') }}" class="capacity-search">
                <input name="search" type="search" placeholder="Search shift, machine or worker..." value="{{ request('search') }}" aria-label="Search capacities">
                <button class="capacity-btn primary" type="submit"><i class="bx bx-search"></i> Search</button>
                @if(request()->filled('search'))<a href="{{ route('production.capacities.index') }}" class="capacity-btn outline">Clear</a>@endif
            </form>
        </div>
        <div class="capacity-table-wrap">
            <table class="capacity-table">
                <thead><tr><th>#</th><th>Shift</th><th>Machine</th><th>Worker</th><th>Max Units / Hour</th><th>Efficiency Rate</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse($capacities as $capacity)
                    @php
                        $shift = strtolower(trim((string) $capacity->shift));
                        $efficiency = is_numeric($capacity->efficiency_rate) ? (float) $capacity->efficiency_rate : null;
                    @endphp
                    <tr>
                        <td><strong>{{ $capacity->id }}</strong></td>
                        <td><span class="capacity-tag {{ str_contains($shift, 'night') ? 'night' : (str_contains($shift, 'evening') ? 'evening' : '') }}"><i class="bx bx-time-five"></i> {{ $capacity->shift ?: '—' }}</span></td>
                        <td>{{ $capacity->machine?->name ?? $capacity->machine_id ?? '—' }}</td>
                        <td>{{ $capacity->worker?->name ?? $capacity->worker_id ?? '—' }}</td>
                        <td><strong>{{ is_numeric($capacity->max_units_per_hour) ? number_format((float) $capacity->max_units_per_hour, 2) : '—' }}</strong></td>
                        <td>@if($efficiency !== null)<span class="capacity-rate {{ $efficiency < 70 ? 'low' : '' }}">{{ number_format($efficiency, 1) }}%</span>@else — @endif</td>
                        <td><div class="capacity-actions">
                            <a class="capacity-action view" href="{{ route('production.capacities.show', $capacity->id) }}"><i class="bx bx-show"></i> View</a>
                            <a class="capacity-action edit" href="{{ route('production.capacities.edit', $capacity->id) }}"><i class="bx bx-edit"></i> Edit</a>
                            <form method="POST" action="{{ route('production.capacities.destroy', $capacity->id) }}" onsubmit="return confirm('Delete this capacity record?')" style="margin:0">@csrf @method('DELETE')<button class="capacity-action delete" type="submit"><i class="bx bx-trash"></i> Delete</button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;padding:38px;color:#71839a"><i class="bx bx-data" style="font-size:28px;display:block;margin-bottom:8px"></i>No capacity records found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="capacity-footer">{{ $capacities->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
