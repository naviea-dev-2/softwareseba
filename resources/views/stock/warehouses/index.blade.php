@extends('inc.master')

@section('head')
    <title>Warehouses</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4><b>Warehouses</b></h4>
                <small class="text-muted">Manage your warehouses and stock locations.</small>
            </div>
            <a href="{{ route('stock.warehouses.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Add Warehouse
            </a>
        </div>
        <div class="row g-3">
            @forelse($warehouses as $warehouse)
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-1">{{ $warehouse->name }}</h5>
                                    <small class="text-muted">{{ $warehouse->code }}</small>
                                </div>
                                @if($warehouse->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col">
                                    <strong>{{ $warehouse->stock_balances_count }}</strong>
                                    <br>
                                    <small class="text-muted">SKUs</small>
                                </div>
                                <div class="col">
                                    <strong>{{ $warehouse->capacity ?? 0 }}</strong>
                                    <br>
                                    <small class="text-muted">Capacity</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-4">
                                <a href="{{ route('stock.warehouses.show', $warehouse->id) }}" class="btn btn-outline-primary btn-sm">View</a>
                                <a href="{{ route('stock.warehouses.edit', $warehouse->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('stock.warehouses.toggle', $warehouse->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-outline-warning btn-sm">
                                        {{ $warehouse->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">No warehouses found.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-3">
            {{ $warehouses->links() }}
        </div>
    </div>
</div>
@endsection