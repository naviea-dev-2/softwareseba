@extends('inc.master')

@section('head')
    <title>Stock Reservations</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h4><b>Stock Reservations</b></h4>
                <small class="text-muted">Reserved stock for pending orders.</small>
            </div>
            <a href="{{ route('stock.reservations.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Create Reservation
            </a>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Order, customer, product...">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" @selected(request('status') == 'active')>Active</option>
                                <option value="fulfilled" @selected(request('status') == 'fulfilled')>Fulfilled</option>
                                <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Search</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('stock.reservations') }}" class="btn btn-light w-100">Reset</a>
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
                            <th>Warehouse</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Expires</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr>
                                <td>
                                    <strong>{{ $reservation->product->product_name ?? 'Unknown' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $reservation->product->product_code ?? '' }}</small>
                                </td>
                                <td>{{ $reservation->warehouse->name ?? 'Unknown' }}</td>
                                <td>{{ $reservation->order_number ?? '-' }}</td>
                                <td>{{ $reservation->customer_name ?? '-' }}</td>
                                <td class="fw-bold">{{ $reservation->qty }}</td>
                                <td>
                                    @if($reservation->status === 'active')
                                        <span class="badge bg-primary">Active</span>
                                    @elseif($reservation->status === 'fulfilled')
                                        <span class="badge bg-success">Fulfilled</span>
                                    @else
                                        <span class="badge bg-secondary">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ $reservation->expires_at?->format('d M Y H:i') }}</td>
                                <td>
                                    @if($reservation->status === 'active')
                                        <div class="d-flex gap-1">
                                            <form method="POST" action="{{ route('stock.reservations.fulfill', $reservation->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-success" onclick="return confirm('Fulfill this reservation?')">Fulfill</button>
                                            </form>
                                            <form method="POST" action="{{ route('stock.reservations.cancel', $reservation->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Cancel this reservation?')">Cancel</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted">Completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">No reservations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection