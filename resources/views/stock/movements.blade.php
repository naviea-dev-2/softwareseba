@extends('inc.master')

@section('head')
    <title>Stock Movements</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="mb-3">
            <h4><b>Stock Movements</b></h4>
            <small class="text-muted">Complete stock movement audit trail.</small>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="warehouse_id" class="form-select">
                                <option value="">All Warehouses</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" @selected(request('warehouse_id') == $warehouse->id)>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="type" class="form-select">
                                <option value="">All Movement Types</option>
                                <option value="in" @selected(request('type') == 'in')>Stock In</option>
                                <option value="out" @selected(request('type') == 'out')>Stock Out</option>
                                <option value="transfer_in" @selected(request('type') == 'transfer_in')>Transfer In</option>
                                <option value="transfer_out" @selected(request('type') == 'transfer_out')>Transfer Out</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('stock.movements') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Warehouse</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Reference</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr>
                                <td>{{ $movement->created_at?->format('d M Y H:i') }}</td>
                                <td>{{ $movement->product->product_name ?? 'Unknown' }}</td>
                                <td>{{ $movement->warehouse->name ?? 'Unknown' }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ strtoupper(str_replace('_', ' ', $movement->type)) }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ $movement->qty }}</td>
                                <td>{{ $movement->before_qty }}</td>
                                <td>{{ $movement->after_qty }}</td>
                                <td>{{ $movement->reference_id }}</td>
                                <td>{{ $movement->user_name ?? 'System' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">No stock movements found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection