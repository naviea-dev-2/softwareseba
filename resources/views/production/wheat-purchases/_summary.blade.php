@php
    $amount = (float) $summary['amount'];
    $paid   = (float) $summary['paid'];   // from the payments table
    $due    = (float) $summary['due'];    // purchases minus payments
@endphp

<div class="wheat-kpis">

    <div class="wheat-kpi wheat-kpi-deliveries">
        <div class="wheat-kpi-icon"><i class="bx bx-package"></i></div>
        <div class="wheat-kpi-body">
            <span class="wheat-muted">Total Deliveries</span>
            <b>{{ number_format($summary['deliveries']) }}</b>
        </div>
    </div>

    <div class="wheat-kpi wheat-kpi-kg">
        <div class="wheat-kpi-icon"><i class="bx bx-weight"></i></div>
        <div class="wheat-kpi-body">
            <span class="wheat-muted">Net Weight (KG)</span>
            <b>{{ number_format($summary['total_kg'], 3) }}</b>
        </div>
    </div>

    <div class="wheat-kpi wheat-kpi-ton">
        <div class="wheat-kpi-icon"><i class="bx bx-tachometer"></i></div>
        <div class="wheat-kpi-body">
            <span class="wheat-muted">Net Weight (TON)</span>
            <b>{{ number_format($summary['total_kg'] / 1000, 3) }}</b>
        </div>
    </div>

    <div class="wheat-kpi wheat-kpi-purchase">
        <div class="wheat-kpi-icon"><i class="bx bx-receipt"></i></div>
        <div class="wheat-kpi-body">
            <span class="wheat-muted">Purchase Amount (৳)</span>
            <b>{{ number_format($amount, 2) }}</b>
        </div>
    </div>

    <div class="wheat-kpi wheat-kpi-paid">
        <div class="wheat-kpi-icon"><i class="bx bx-check-shield"></i></div>
        <div class="wheat-kpi-body">
            <span class="wheat-muted">Total Paid (৳)</span>
            <b>{{ number_format($paid, 2) }}</b>
        </div>
    </div>

    <div class="wheat-kpi {{ $due > 0 ? 'wheat-kpi-due' : 'wheat-kpi-paid' }}">
        <div class="wheat-kpi-icon">
            <i class="bx {{ $due > 0 ? 'bx-error-circle' : 'bx-check-circle' }}"></i>
        </div>
        <div class="wheat-kpi-body">
            <span class="wheat-muted">Total Due (৳)</span>
            <b>{{ number_format($due, 2) }}</b>
        </div>
    </div>

</div>