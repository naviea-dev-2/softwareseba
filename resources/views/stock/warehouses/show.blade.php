@extends('inc.master')

@section('head')
    <title>{{ $warehouse->name }}</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4><b>{{ $warehouse->name }}</b></h4>
                <small class="text-muted">{{ $warehouse->code }}</small>
            </div>
            <div>
                <a href="{{ route('stock.warehouses.edit', $warehouse->id) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('stock.warehouses.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Location</small>
                        <h5 class="mt-2">{{ $warehouse->location ?? '-' }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Capacity</small>
                        <h5 class="mt-2">{{ $warehouse->capacity ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Used Capacity</small>
                        <h5 class="mt-2">{{ $warehouse->used_capacity ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Utilization</small>
                        <h5 class="mt-2">{{ number_format($warehouse->utilization_percentage, 1) }}%</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-white">
                <strong>Current Stock</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Total</th>
                            <th>Reserved</th>
                            <th>Available</th>
                            <th>Reorder Point</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warehouse->stockBalances as $balance)
                            <tr>
                                <td>{{ $balance->product->product_name ?? '' }}</td>
                                <td>{{ $balance->product->product_code ?? '-' }}</td>
                                <td>{{ $balance->total_qty }}</td>
                                <td>{{ $balance->reserved_qty }}</td>
                                <td class="fw-bold">{{ $balance->available_qty }}</td>
                                <td>{{ $balance->reorder_point }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No stock in this warehouse.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection