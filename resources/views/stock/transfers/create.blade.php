@extends('inc.master')

@section('head')
    <title>Create Warehouse Transfer</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h4><b>Create Warehouse Transfer</b></h4>
            <a href="{{ route('stock.transfers') }}" class="btn btn-secondary">Back</a>
        </div>
        <form method="POST" action="{{ route('stock.transfers.store') }}">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">From Warehouse</label>
                            <select name="from_warehouse_id" class="form-select" required>
                                <option value="">Select source warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">To Warehouse</label>
                            <select name="to_warehouse_id" class="form-select" required>
                                <option value="">Select destination warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" id="addItem" class="btn btn-success w-100">
                                <i class="bx bx-plus"></i> Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-white">
                    <strong>Transfer Items</strong>
                </div>
                <div class="card-body">
                    <div id="itemsContainer">
                        <div class="row g-2 item-row mb-2">
                            <div class="col-md-8">
                                <select name="items[0][product_id]" class="form-select" required>
                                    <option value="">Select product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->product_name }} - {{ $product->sku }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="items[0][qty]" class="form-control" min="1" placeholder="Quantity" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-item">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-4">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>
                    <button class="btn btn-primary">
                        <i class="bx bx-save"></i> Create Transfer
                    </button>
                    <a href="{{ route('stock.transfers') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
let itemIndex = 1;

document.getElementById('addItem').addEventListener('click', function () {
    const container = document.getElementById('itemsContainer');
    const row = document.createElement('div');
    row.className = 'row g-2 item-row mb-2';
    row.innerHTML = `
        <div class="col-md-8">
            <select name="items[${itemIndex}][product_id]" class="form-select" required>
                <option value="">Select product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ addslashes($product->product_name) }} - {{ addslashes($product->sku) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="items[${itemIndex}][qty]" class="form-control" min="1" placeholder="Quantity" required>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger remove-item">
                <i class="bx bx-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
    itemIndex++;
});

document.addEventListener('click', function (event) {
    const button = event.target.closest('.remove-item');
    if (!button) return;
    const rows = document.querySelectorAll('.item-row');
    if (rows.length <= 1) return;
    button.closest('.item-row').remove();
});
</script>
@endsection