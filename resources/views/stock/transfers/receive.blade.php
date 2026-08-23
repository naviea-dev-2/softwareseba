@extends('inc.master')

@section('head')
    <title>Receive Transfer</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4><b>Receive Transfer #{{ $transfer->id }}</b></h4>
                <small class="text-muted">
                    {{ $transfer->fromWarehouse->name }} → {{ $transfer->toWarehouse->name }}
                </small>
            </div>
            <a href="{{ route('stock.transfers') }}" class="btn btn-secondary">Back</a>
        </div>
        <form method="POST" action="{{ route('stock.transfers.receive', $transfer->id) }}">
            @csrf
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Shipped</th>
                                <th>Received</th>
                                <th>Damaged</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transfer->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product->product_name ?? 'Unknown' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $item->product->product_code ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->shipped_qty }}</span>
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $item->id }}][received_qty]" value="{{ $item->shipped_qty }}" min="0" max="{{ $item->shipped_qty }}" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $item->id }}][damaged_qty]" value="0" min="0" max="{{ $item->shipped_qty }}" class="form-control">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    <button class="btn btn-success">
                        <i class="bx bx-check"></i> Receive Transfer
                    </button>
                    <a href="{{ route('stock.transfers') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection