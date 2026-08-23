@extends('inc.master')

@section('head')
    <title>Stock Ledger</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1"><b>Stock Ledger</b></h4>
                <small class="text-muted">Current inventory levels across warehouses</small>
            </div>
            <a href="{{ route('stock.adjust') }}" class="btn btn-primary">
                <i class="bx bx-transfer"></i> Adjust Stock
            </a>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('stock.ledger') }}">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Product / SKU">
                        </div>
                        <div class="col-md-3">
                            <select name="warehouse_id" class="form-select">
                                <option value="">All Warehouses</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" @selected(request('warehouse_id') == $warehouse->id)>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="normal" @selected(request('status') === 'normal')>Normal</option>
                                <option value="low" @selected(request('status') === 'low')>Low</option>
                                <option value="critical" @selected(request('status') === 'critical')>Critical</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">
                                <i class="bx bx-search"></i> Search
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('stock.ledger') }}" class="btn btn-light w-100">Reset</a>
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
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Warehouse</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Reserved</th>
                            <th class="text-center">Available</th>
                            <th class="text-center">Reorder</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($balances as $balance)
                            @php
                                if ($balance->available_qty <= 0) {
                                    $status = 'critical';
                                } elseif ($balance->available_qty <= $balance->reorder_point) {
                                    $status = 'low';
                                } else {
                                    $status = 'normal';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $balance->product->product_name ?? '' }}</strong>
                                </td>
                                <td>{{ $balance->product->product_code ?? 'N/A' }}</td>
                                <td>{{ $balance->warehouse->name ?? '' }}</td>
                                <td class="text-center">{{ $balance->total_qty }}</td>
                                <td class="text-center text-primary">{{ $balance->reserved_qty }}</td>
                                <td class="text-center fw-bold">{{ $balance->available_qty }}</td>
                                <td class="text-center">{{ $balance->reorder_point }}</td>
                                <td>
                                    @if($status === 'critical')
                                        <span class="badge bg-danger">Critical</span>
                                    @elseif($status === 'low')
                                        <span class="badge bg-warning text-dark">Low</span>
                                    @else
                                        <span class="badge bg-success">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('stock.history', [$balance->product_id, $balance->warehouse_id]) }}" class="btn btn-sm btn-outline-primary">History</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">No stock records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($balances->hasPages())
                <div class="card-footer bg-white">
                    {{ $balances->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection