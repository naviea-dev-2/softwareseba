@extends('inc.master')

@section('head')
    <title>Stock History</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4><b>Stock History</b></h4>
                <small class="text-muted">
                    {{ $product->product_name ?? 'Unknown' }} - {{ $warehouse->name }}
                </small>
            </div>
            <a href="{{ route('stock.ledger') }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Reference</th>
                            <th>Reason</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr>
                                <td>{{ $movement->created_at?->format('d M Y H:i') }}</td>
                                <td>
                                    <span class="badge
                                        @if(in_array($movement->type, ['in','transfer_in']))
                                            bg-success
                                        @elseif(in_array($movement->type, ['out','transfer_out']))
                                            bg-danger
                                        @else
                                            bg-secondary
                                        @endif">
                                        {{ strtoupper(str_replace('_', ' ', $movement->type)) }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ $movement->qty }}</td>
                                <td>{{ $movement->before_qty }}</td>
                                <td>{{ $movement->after_qty }}</td>
                                <td>{{ $movement->reference_id }}</td>
                                <td>{{ $movement->reason }}</td>
                                <td>{{ $movement->user_name ?? $movement->user->name ?? 'System' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">No movement history found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection