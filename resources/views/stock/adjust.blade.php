@extends('inc.master')

@section('head')
    <title>Adjust Stock</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h4><b>Adjust Stock</b></h4>
            <a href="{{ route('stock.ledger') }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('stock.adjust.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Product</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }} - {{ $product->sku }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Warehouse</label>
                            <select name="warehouse_id" class="form-select" required>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Adjustment</label>
                            <select name="type" class="form-select" required>
                                <option value="add">Add Stock</option>
                                <option value="remove">Remove Stock</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="qty" min="1" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Why are you adjusting stock?"></textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-primary">
                            <i class="bx bx-save"></i> Adjust Stock
                        </button>
                        <a href="{{ route('stock.ledger') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection