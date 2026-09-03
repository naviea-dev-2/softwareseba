@extends('inc.master')

@section('head')
    <title>Work Orders</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">

        <div class="row g-3 mb-4">
            <div class="col-md-2 col-6"><div class="card p-3 text-center border-0 shadow-sm"><small class="text-muted">Total</small><h4 class="fw-bold mb-0">{{ $stats['total'] }}</h4></div></div>
            <div class="col-md-2 col-6"><div class="card p-3 text-center border-0 shadow-sm"><small class="text-muted">Open</small><h4 class="fw-bold text-primary mb-0">{{ $stats['open'] }}</h4></div></div>
            <div class="col-md-2 col-6"><div class="card p-3 text-center border-0 shadow-sm"><small class="text-muted">In Progress</small><h4 class="fw-bold text-info mb-0">{{ $stats['in_progress'] }}</h4></div></div>
            <div class="col-md-2 col-6"><div class="card p-3 text-center border-0 shadow-sm"><small class="text-muted">Completed</small><h4 class="fw-bold text-success mb-0">{{ $stats['completed'] }}</h4></div></div>
            <div class="col-md-2 col-6"><div class="card p-3 text-center border-0 shadow-sm"><small class="text-muted">Overdue</small><h4 class="fw-bold text-danger mb-0">{{ $stats['overdue'] }}</h4></div></div>
            <div class="col-md-2 col-6"><div class="card p-3 text-center border-0 shadow-sm"><small class="text-muted">Closed</small><h4 class="fw-bold text-secondary mb-0">{{ $stats['total'] - $stats['open'] }}</h4></div></div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <select name="type_id" class="form-select form-select-sm" style="width:160px" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    @foreach($types as $t)
                        <option value="{{ $t->id }}" {{ request('type_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select form-select-sm" style="width:140px" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach(['draft','pending','in_progress','on_hold','completed','cancelled','closed'] as $s)
                        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
                <div class="form-check form-check-inline mt-1">
                    <input class="form-check-input" type="checkbox" name="overdue" value="1" id="chkOverdue" {{ request('overdue')?'checked':'' }} onchange="this.form.submit()">
                    <label class="form-check-label" for="chkOverdue">Overdue</label>
                </div>
            </form>
            <a href="{{ route('work-orders.create') }}" class="btn btn-primary"><i class="bx bx-plus"></i> New Work Order</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>WO #</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Party</th>
                            <th>Assigned</th>
                            <th class="text-center">Priority</th>
                            <th class="text-center">Status</th>
                            <th>Due</th>
                            <th class="text-end">Progress</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $wo)
                        <tr class="{{ $wo->is_overdue ? 'table-danger' : '' }}">
                            <td class="fw-medium">{{ $wo->work_order_no }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $wo->workOrderType->name ?? ucfirst($wo->work_order_type) }}</span></td>
                            <td>
                                <div class="fw-medium">{{ Str::limit($wo->title, 35) }}</div>
                                @if($wo->reference_no)<small class="text-muted">Ref: {{ $wo->reference_no }}</small>@endif
                            </td>
                            <td>
                                @if($wo->customer)<small class="text-success"><i class="bx bx-user"></i> {{ Str::limit($wo->customer->name,18) }}</small>
                                @elseif($wo->vendor)<small class="text-warning"><i class="bx bx-buildings"></i> {{ Str::limit($wo->vendor->name,18) }}</small>
                                @else<small class="text-muted">-</small>@endif
                            </td>
                            <td>{{ $wo->assignee->name ?? '-' }}</td>
                            <td class="text-center">{!! $wo->priority_badge !!}</td>
                            <td class="text-center">{!! $wo->status_badge !!}</td>
                            <td><small class="{{ $wo->is_overdue?'text-danger fw-bold':'text-muted' }}">{{ $wo->due_at?->format('M d, Y')??'-' }}</small></td>
                            <td class="text-end" style="width:110px">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:6px;">
                                        <div class="progress-bar bg-{{ $wo->progress>=100?'success':($wo->progress>=50?'primary':'warning') }}" style="width:{{ $wo->progress }}%"></div>
                                    </div>
                                    <small class="text-muted" style="width:32px">{{ $wo->progress }}%</small>
                                </div>
                            </td>
                            <td class="text-end"><a href="{{ route('work-orders.show', $wo) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="text-center text-muted py-4">No work orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection