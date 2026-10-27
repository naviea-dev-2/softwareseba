@extends('Hr.salary_section.partial.layout')
@section('title','Payslip')
@section('setting-content')
<style>
@media print {
    header, .tabs, .no-print { display: none !important; }
    body { background: #fff; }
    .card { box-shadow: none; border: 0; }
}
</style>
@include('Hr.salary_section.partial.tabs')

<div class="card">
    <div class="no-print" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <h3 style="margin:0">Payslip</h3>
        <div>
            <button type="button" class="btn primary" onclick="window.print()">Print</button>
            <a class="btn ghost" href="{{ route('salary.index', ['month' => $salary->payroll_month]) }}">Back</a>
        </div>
    </div>

    <p>
        <b>{{ $salary->employee->name }}</b><br>
        <span style="color:var(--muted)">Payroll month: {{ $salary->payroll_month }}</span>
    </p>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
        <div>
            <h4>Earnings</h4>
            <table>
                <tr><td>Basic salary</td><td style="text-align:right">{{ number_format($calc['basic'], 2) }}</td></tr>
                @foreach ($calc['earn_lines'] as [$label, $amount])
                    <tr><td>{{ $label }}</td><td style="text-align:right">{{ number_format($amount, 2) }}</td></tr>
                @endforeach
                <tr><td>Overtime ({{ $calc['ot_hours'] }} hrs)</td><td style="text-align:right">{{ number_format($calc['overtime'], 2) }}</td></tr>
                <tr><th>Gross</th><th style="text-align:right">{{ number_format($calc['gross'], 2) }}</th></tr>
            </table>
        </div>
        <div>
            <h4>Deductions</h4>
            <table>
                @forelse ($calc['deduction_lines'] as [$label, $amount])
                    <tr><td>{{ $label }}</td><td style="text-align:right">{{ number_format($amount, 2) }}</td></tr>
                @empty
                    <tr><td colspan="2" style="color:var(--muted)">No deductions</td></tr>
                @endforelse
                <tr><td>Income tax ({{ $calc['tax_rule'] ?? 'no active rule' }})</td><td style="text-align:right">{{ number_format($calc['tax'], 2) }}</td></tr>
                <tr><th>Total deductions</th><th style="text-align:right">{{ number_format($calc['deductions'] + $calc['tax'], 2) }}</th></tr>
            </table>
        </div>
    </div>

    <table style="margin-top:16px">
        <tr><td>Net salary</td><td style="text-align:right">{{ number_format($calc['net'], 2) }}</td></tr>
        <tr><td>Advance recovery</td><td style="text-align:right">-{{ number_format($calc['advance'], 2) }}</td></tr>
        <tr><td>Previous due</td><td style="text-align:right">+{{ number_format($calc['due'], 2) }}</td></tr>
        <tr><th>Payable</th><th style="text-align:right">{{ number_format($calc['payable'], 2) }}</th></tr>
        <tr><td>Paid so far</td><td style="text-align:right">{{ number_format($calc['paid'], 2) }}</td></tr>
        <tr><th>Balance</th><th style="text-align:right">{{ number_format($calc['balance'], 2) }}</th></tr>
    </table>
</div>
@endsection
