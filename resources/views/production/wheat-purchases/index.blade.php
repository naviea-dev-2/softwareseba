@extends('inc.master')

@section('content')

@include('production.wheat-purchases._style')

<div class="wheat-wrap">

    {{-- PAGE HEADER --}}
    <div class="wheat-pagehead">
        <div class="wheat-pagehead-left">
            <div class="wheat-pagehead-icon">
                <i class="bx bx-wheat"></i>
            </div>
            <div>
                <h2>Wheat Purchase Records</h2>
                <div class="wheat-muted">
                    Weighbridge purchase register · Kazi Flour Mill
                </div>
            </div>
        </div>

        <div class="wheat-pagehead-actions">
            <a class="wheat-btn light"
               href="{{ route('production.wheat-purchases.report') }}">
                <i class="bx bx-bar-chart-alt-2"></i> Reports
            </a>

            <a class="wheat-btn primary"
               href="{{ route('production.wheat-purchases.create') }}">
                <i class="bx bx-plus"></i> New Purchase
            </a>
        </div>
    </div>

    {{-- ALERTS --}}
    @include('production.wheat-purchases._alerts')

    {{-- SUMMARY --}}
    @include('production.wheat-purchases._summary')

    {{-- FILTERS --}}
    <div class="wheat-card wheat-card-filter">
        <div class="wheat-card-head">
            <h4><i class="bx bx-filter-alt"></i> Filters</h4>
        </div>
        <div class="wheat-card-body">
            @include('production.wheat-purchases._filters', [
                'action' => route('production.wheat-purchases.index'),
            ])
        </div>
    </div>

    {{-- TABLE --}}
    <div class="wheat-card">
        <div class="wheat-card-head wheat-flex-between">
            <h4><i class="bx bx-list-ul"></i> All Deliveries</h4>

            <a class="wheat-btn light"
               href="{{ route('production.wheat-purchases.export', request()->query()) }}">
                <i class="bx bx-download"></i> Export CSV
            </a>
        </div>

        {{-- FILTERED TOTALS STRIP --}}
        <div class="wheat-filter-totals">
            <div class="wheat-ft-item wheat-ft-purchase">
                <span class="wheat-muted">
                    <i class="bx bx-shopping-bag"></i> Filtered Purchase
                </span>
                <b>৳ {{ number_format($totalPurchase, 2) }}</b>
            </div>

            <div class="wheat-ft-item wheat-ft-paid">
                <span class="wheat-muted">
                    <i class="bx bx-check-circle"></i> Filtered Paid
                </span>
                <b>৳ {{ number_format($totalPaid, 2) }}</b>
            </div>

            <div class="wheat-ft-item {{ $totalDue > 0 ? 'wheat-ft-due' : 'wheat-ft-paid' }}">
                <span class="wheat-muted">
                    <i class="bx bx-error-circle"></i> Filtered Due
                </span>
                <b>৳ {{ number_format($totalDue, 2) }}</b>
            </div>
        </div>

        <div class="wheat-card-body wheat-table-body">
            @include('production.wheat-purchases._table', [
                'actions' => true,
                'offset'  => ($purchases->currentPage() - 1) * $purchases->perPage(),
            ])
        </div>

        <div class="wheat-pagination">
            {{ $purchases->links() }}
        </div>
    </div>

</div>

@endsection