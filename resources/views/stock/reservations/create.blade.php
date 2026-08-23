@extends('inc.master')

@section('head')
    <title>Create Reservation</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <h4><b>Create Stock Reservation</b></h4>
            <a href="{{ route('stock.reservations') }}" class="btn btn-secondary">Back</a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following:</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('stock.reservations.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Product</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                        {{ $product->product_name }} - {{ $product->sku }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Warehouse</label>
                            <select name="warehouse_id" class="form-select" required>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="qty" value="{{ old('qty') }}" min="1" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Order Number</label>
                            <input type="text" name="order_number" value="{{ old('order_number') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Customer</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expires At</label>
                            <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="form-control">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-primary">
                            <i class="bx bx-save"></i> Reserve Stock
                        </button>
                        <a href="{{ route('stock.reservations') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection