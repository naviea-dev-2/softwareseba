@extends('inc.master')
@section('content')

@include('production.wheat-purchases._style')

<div class="wheat-wrap">

    {{-- HEADER --}}
    <div class="wheat-pagehead wheat-no-print">
        <div class="wheat-pagehead-left">
            <div class="wheat-pagehead-icon">
                <i class="bx bx-bar-chart-alt-2"></i>
            </div>
            <div>
                <h2>Wheat Purchase Report</h2>
                <div class="wheat-muted">
                    Monthly, supplier-wise and detailed purchase report
                </div>
            </div>
        </div>

        <div class="wheat-pagehead-actions">
            <a class="wheat-btn light"
               href="{{ route('production.wheat-purchases.index') }}">
                <i class="bx bx-arrow-back"></i> Back
            </a>
            <a class="wheat-btn light"
               href="{{ route('production.wheat-purchases.export', request()->query()) }}">
                <i class="bx bx-download"></i> Export CSV
            </a>
            <button type="button"
                    class="wheat-btn primary"
                    onclick="window.print()">
                <i class="bx bx-printer"></i> Print / Save PDF
            </button>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="wheat-card wheat-card-filter wheat-no-print">
        <div class="wheat-card-head">
            <h4><i class="bx bx-filter-alt"></i> Filters</h4>
        </div>
        <div class="wheat-card-body">
            @include('production.wheat-purchases._filters', [
                'action' => route('production.wheat-purchases.report'),
            ])
        </div>
    </div>

    {{-- PRINT TITLE --}}
    <div class="wheat-print-title">
        <h2>Kazi Global Food Products Industries Limited</h2>
        <h3>Kazi Flour Mill</h3>
        <div>Wheat (Purchase) In Record</div>
        <small>
            {{ request('from') ?: 'All dates' }} — {{ request('to') ?: 'All dates' }}
        </small>
    </div>

    {{-- KPI SUMMARY --}}
    @include('production.wheat-purchases._summary')

    {{-- DETAILED REGISTER --}}
    <div class="wheat-card">
        <div class="wheat-card-head">
            <h4><i class="bx bx-list-ul"></i> Detailed Purchase Register</h4>
        </div>
        <div class="wheat-card-body wheat-table-body">
            @include('production.wheat-purchases._table', ['actions' => false])
        </div>
    </div>

    {{-- MONTHLY SUMMARY --}}
    <div class="wheat-card">
        <div class="wheat-card-head">
            <h4><i class="bx bx-calendar"></i> Monthly Purchase Summary</h4>
        </div>
        <div class="wheat-table-wrap">
            <table class="wheat-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="wheat-right">Deliveries</th>
                        <th class="wheat-right">Net Weight (KG)</th>
                        <th class="wheat-right">Monthly Purchase TON</th>
                        <th class="wheat-right">Monthly Purchase (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthly as $m)
                        <tr>
                            <td>{{ $m->period }}</td>
                            <td class="wheat-right">{{ $m->deliveries }}</td>
                            <td class="wheat-right">{{ number_format($m->total_kg, 3) }}</td>
                            <td class="wheat-right">{{ number_format($m->total_kg / 1000, 3) }}</td>
                            <td class="wheat-right">{{ number_format($m->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No dated records
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SUPPLIER SUMMARY --}}
    <div class="wheat-card">
        <div class="wheat-card-head">
            <h4><i class="bx bx-store-alt"></i> Supplier-wise Summary</h4>
        </div>
        <div class="wheat-table-wrap">
            <table class="wheat-table">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th class="wheat-right">Deliveries</th>
                        <th class="wheat-right">Net Weight (KG)</th>
                        <th class="wheat-right">Weight (TON)</th>
                        <th class="wheat-right">Purchase Amount (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $s)
                        <tr>
                            <td>{{ $s->supplier_name ?: 'Unspecified' }}</td>
                            <td class="wheat-right">{{ $s->deliveries }}</td>
                            <td class="wheat-right">{{ number_format($s->total_kg, 3) }}</td>
                            <td class="wheat-right">{{ number_format($s->total_kg / 1000, 3) }}</td>
                            <td class="wheat-right">{{ number_format($s->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No supplier records
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

