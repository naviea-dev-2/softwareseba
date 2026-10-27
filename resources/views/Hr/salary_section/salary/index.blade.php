@extends('Hr.salary_section.partial.layout')
@section('title','Salary')
@section('setting-content')

<div class="card">
    <form method="POST" action="{{ route('salary.generate') }}" class="toolbar">
        @csrf
        <label>Generate for active employees
            <input type="month" name="payroll_month" value="{{ request('month', now()->format('Y-m')) }}" required>
        </label>
        <button type="submit" class="btn primary">Generate month</button>
        <span style="color:var(--muted);font-size:12px">Creates a record for each active employee without one, using their last basic salary.</span>
    </form>

    <form method="GET" class="toolbar">
        <select name="month">
            <option value="">All months</option>
            @foreach ($months as $m)
                <option value="{{ $m }}" @selected(request('month') === $m)>{{ $m }}</option>
            @endforeach
        </select>
        <select name="employee_id">
            <option value="">All employees</option>
            @foreach ($employees as $e)
                <option value="{{ $e->id }}" @selected(request('employee_id') == $e->id)>{{ $e->employee_name }}</option>
            @endforeach
        </select>
        @include('Hr.salary_section.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('salary.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('salary.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $rows->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Month</th><th>Employee</th><th>Basic</th><th>Absent days</th><th>Gross</th>
                    <th>Deductions</th><th>Tax</th><th>Net</th><th>Advance</th><th>Due</th>
                    <th>Payable</th><th>Paid</th><th>Balance</th><th>Status</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $r)
                    @php
                        $c = $r->calc;
                        $status = $c['balance'] <= 0 ? 'Paid' : ($c['paid'] > 0 ? 'Partly' : 'Unpaid');
                    @endphp
                    <tr>
                        <td>{{ $r->payroll_month }}</td>
                        <td>{{ $r->employee->employee_name }}</td>
                        <td style="text-align:right">{{ number_format($c['basic'], 2) }}</td>
                        <td style="text-align:right">{{ $c['absent_days'] }}</td>
                        <td style="text-align:right">{{ number_format($c['gross'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($c['deductions'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($c['tax'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($c['net'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($c['advance'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($c['due'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($c['payable'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($c['paid'], 2) }}</td>
                        <td style="text-align:right">{{ number_format($c['balance'], 2) }}</td>
                        <td><span class="badge">{{ $status }}</span></td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('salary.payslip', $r) }}">Payslip</a>
                            <a class="link" href="{{ route('salary.details', $r) }}">Details</a>
                            <a class="link" href="{{ route('salary.index', ['edit' => $r->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('salary.destroy', $r) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this salary record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="15" class="empty">No salary records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pager">{{ $rows->links() }}</div>
</div>

@if ($editing)
    <div class="modal" onclick="if (event.target === this) window.location = '{{ route('salary.index') }}'">
        <div class="modal-box">
            <h3>{{ $editing->exists ? 'Edit' : 'Add' }} salary record</h3>
            <form method="POST" class="form-grid"
                  action="{{ $editing->exists ? route('salary.update', $editing) : route('salary.store') }}">
                @csrf
                @if ($editing->exists) @method('PUT') @endif
                <label>Employee
                    <select name="employee_id" required>
                        <option value="">— select —</option>
                        @foreach ($employees as $e)
                            <option value="{{ $e->id }}" @selected(old('employee_id', $editing->employee_id) == $e->id)>{{ $e->employee_name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Payroll month
                    <input type="month" name="payroll_month" required
                           value="{{ old('payroll_month', $editing->payroll_month ?? now()->format('Y-m')) }}">
                </label>
                <label>Basic salary <input type="number" step="any" min="0" name="basic" required value="{{ old('basic', $editing->basic) }}"></label>
                <label>Advance to recover <input type="number" step="any" min="0" name="advance" value="{{ old('advance', $editing->advance ?? 0) }}"></label>
                <label>Previous due <input type="number" step="any" min="0" name="due" value="{{ old('due', $editing->due ?? 0) }}"></label>
                <div class="actions">
                    <button type="submit" class="btn primary">Save</button>
                    <a href="{{ route('salary.index') }}" class="btn ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
