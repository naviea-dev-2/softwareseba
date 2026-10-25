@extends('Hr.salary_section.partial.layout')
@section('title','Salary Details')
@section('setting-content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-wrap:wrap;gap:8px">
        <div>
            <h3 style="margin:0">Salary details: {{ $salary->employee->name }}</h3>
            <span style="color:var(--muted)">Payroll month {{ $salary->payroll_month }} · Basic {{ number_format($salary->basic, 2) }}</span>
        </div>
        <div style="display:flex;gap:8px">
            <form method="POST" action="{{ route('salary.refresh', $salary) }}"
                  onsubmit="return confirm('Replace these details with the current pay assignments?')">
                @csrf
                <button type="submit" class="btn ghost">Refresh from pay assignments</button>
            </form>
            <a class="btn ghost" href="{{ route('salary.index', ['month' => $salary->payroll_month]) }}">Back</a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Salary head</th><th>Type</th><th>Calculation</th>
                    <th style="text-align:right">Value</th><th style="text-align:right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $d)
                    <tr>
                        <td>{{ $d->head_name }}</td>
                        <td>{{ $d->type }}</td>
                        <td>{{ $d->calc }}</td>
                        <td style="text-align:right">{{ (float) $d->value }}{{ $d->calc === '% of Basic' ? '%' : '' }}</td>
                        <td style="text-align:right">{{ number_format($d->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">No salary details. Assign salary heads on Payroll Assign, then generate or refresh.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <table style="margin-top:12px;max-width:420px">
        <tr><td>Earnings total</td><td style="text-align:right">{{ number_format($details->where('type', 'Earning')->sum('amount'), 2) }}</td></tr>
        <tr><td>Deductions total</td><td style="text-align:right">{{ number_format($details->where('type', 'Deduction')->sum('amount'), 2) }}</td></tr>
    </table>
</div>
@endsection
