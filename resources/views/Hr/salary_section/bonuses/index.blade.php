@extends('Hr.salary_section.partial.layout')
@section('title','Bonus')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <select name="employee_id">
            <option value="">All employees</option>
            @foreach ($employees as $e)
                <option value="{{ $e->id }}" @selected(request('employee_id') == $e->id)>{{ $e->employee_name }}</option>
            @endforeach
        </select>
        <select name="month">
            <option value="">All months</option>
            @foreach ($months as $m)
                <option value="{{ $m }}" @selected(request('month') === $m)>{{ $m }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">All status</option>
            <option @selected(request('status') === 'Pending')>Pending</option>
            <option @selected(request('status') === 'Approved')>Approved</option>
        </select>
        @include('Hr.salary_section.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('bonuses.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('bonuses.index', ['add' => 1]) }}" class="btn primary">+ Add bonus</a>
        <span class="count">{{ $bonuses->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Month</th><th>Employee</th><th>Bonus</th><th>Calculation</th>
                    <th style="text-align:right">Value</th><th style="text-align:right">Amount</th>
                    <th>Status</th><th>Note</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bonuses as $b)
                    <tr>
                        <td>{{ $b->payroll_month }}</td>
                        <td>{{ $b->employee->employee_name }}</td>
                        <td>{{ $b->title }}</td>
                        <td>{{ $b->calc }}</td>
                        <td style="text-align:right">{{ (float) $b->value }}{{ $b->calc === '% of Basic' ? '%' : '' }}</td>
                        <td style="text-align:right">{{ $b->amount() !== null ? number_format($b->amount(), 2) : 'No salary record' }}</td>
                        <td><span class="badge">{{ $b->status }}</span></td>
                        <td>{{ $b->note ?? '-' }}</td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('bonuses.index', ['edit' => $b->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('bonuses.destroy', $b) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this bonus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="empty">No bonuses found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pager">{{ $bonuses->links() }}</div>
</div>

@if ($editing)
    <div class="modal" onclick="if (event.target === this) window.location = '{{ route('bonuses.index') }}'">
        <div class="modal-box">
            <h3>{{ $editing->exists ? 'Edit' : 'Add' }} bonus</h3>
            <form method="POST" class="form-grid"
                  action="{{ $editing->exists ? route('bonuses.update', $editing) : route('bonuses.store') }}">
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
                <label>Bonus title <input name="title" required value="{{ old('title', $editing->title) }}" placeholder="e.g. Festival bonus"></label>
                <label>Calculation
                    <select name="calc" required>
                        <option @selected(old('calc', $editing->calc ?? 'Fixed') === 'Fixed')>Fixed</option>
                        <option @selected(old('calc', $editing->calc) === '% of Basic')>% of Basic</option>
                    </select>
                </label>
                <label>Amount or % <input type="number" step="any" min="0" name="value" required value="{{ old('value', $editing->value) }}"></label>
                <label>Status
                    <select name="status" required>
                        <option @selected(old('status', $editing->status ?? 'Approved') === 'Approved')>Approved</option>
                        <option @selected(old('status', $editing->status) === 'Pending')>Pending</option>
                    </select>
                </label>
                <label>Note <input name="note" value="{{ old('note', $editing->note) }}"></label>
                <div class="actions">
                    <button type="submit" class="btn primary">Save</button>
                    <a href="{{ route('bonuses.index') }}" class="btn ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
