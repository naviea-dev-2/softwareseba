@extends('inc.master')

@section('head')
    <title>Warehouse Transfers</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4><b>Warehouse Transfers</b></h4>
                <small class="text-muted">Move stock between warehouses.</small>
            </div>
            <a href="{{ route('stock.transfers.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> New Transfer
            </a>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search warehouse">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                                <option value="in_transit" @selected(request('status') == 'in_transit')>In Transit</option>
                                <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Search</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('stock.transfers') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Tracking</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                            <tr>
                                <td>#{{ $transfer->id }}</td>
                                <td>{{ $transfer->fromWarehouse->name ?? '-' }}</td>
                                <td>{{ $transfer->toWarehouse->name ?? '-' }}</td>
                                <td>{{ $transfer->items->count() }}</td>
                                <td>
                                    <span class="badge
                                        @if($transfer->status === 'pending') bg-warning text-dark
                                        @elseif($transfer->status === 'approved') bg-primary
                                        @elseif($transfer->status === 'in_transit') bg-info
                                        @elseif($transfer->status === 'completed') bg-success
                                        @else bg-secondary
                                        @endif">
                                        {{ strtoupper(str_replace('_', ' ', $transfer->status)) }}
                                    </span>
                                </td>
                                <td>{{ $transfer->tracking_number ?? '-' }}</td>
                                <td>{{ $transfer->created_at?->format('d M Y') }}</td>
                                <td>
                                    @if($transfer->status === 'pending')
                                        <form method="POST" action="{{ route('stock.transfers.approve', $transfer->id) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-primary" onclick="return confirm('Approve transfer?')">Approve</button>
                                        </form>
                                    @elseif($transfer->status === 'approved')
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#shipModal{{ $transfer->id }}">Ship</button>
                                    @elseif($transfer->status === 'in_transit')
                                        <a href="{{ route('stock.transfers.receive.form', $transfer->id) }}" class="btn btn-sm btn-success">Receive</a>
                                    @else
                                        <span class="text-muted">Completed</span>
                                    @endif
                                </td>
                            </tr>
                            @if($transfer->status === 'approved')
                                <div class="modal fade" id="shipModal{{ $transfer->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('stock.transfers.ship', $transfer->id) }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Ship Transfer #{{ $transfer->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tracking Number</label>
                                                        <input type="text" name="tracking_number" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Carrier</label>
                                                        <input type="text" name="carrier" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Ship Transfer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">No transfers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $transfers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection