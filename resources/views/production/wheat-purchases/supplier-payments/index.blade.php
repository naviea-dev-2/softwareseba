@extends('inc.master')

@section('title', 'Supplier-Wise Wheat Payments')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Supplier-Wise Wheat Payments</h4>
            <p class="text-muted mb-0">
                View supplier purchases, payments and outstanding balances.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('production.wheat-purchases.supplier-payments.export', request()->query()) }}"
               class="btn btn-success">
                <i class="bx bx-spreadsheet"></i> Export Excel
            </a>
            <a href="{{ route('production.wheat-purchases.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Back to Purchases
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0">Supplier Accounts</h5>

            <form method="get" action="{{ route('production.wheat-purchases.supplier-payments.index') }}"
                  class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control form-control-sm" placeholder="Search supplier">
                <button class="btn btn-sm btn-primary" type="submit"><i class="bx bx-search"></i></button>
                @if(request('search'))
                    <a href="{{ route('production.wheat-purchases.supplier-payments.index') }}"
                       class="btn btn-sm btn-light">Reset</a>
                @endif
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px">SL</th>
                            <th>Supplier</th>
                            <th class="text-end">Total Purchases</th>
                            <th class="text-end">Total Payments</th>
                            <th class="text-end">Outstanding</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            @php $due = round((float) $supplier->outstanding, 2); @endphp
                            <tr>
                                <td>{{ $suppliers->firstItem() + $loop->index }}</td>
                                <td><strong>{{ $supplier->supplier_name }}</strong></td>
                                <td class="text-end">{{ number_format($supplier->total_purchases, 2) }}</td>
                                <td class="text-end text-success">{{ number_format($supplier->total_payments, 2) }}</td>
                                <td class="text-end fw-bold {{ $due > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($due, 2) }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('production.wheat-purchases.supplier-payments.show', $supplier->supplier_id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="bx bx-wallet"></i> View / Pay
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No supplier records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection