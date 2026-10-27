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
        <label>From <input type="date" name="from" value="{{ request('from') }}"></label>
        <label>To <input type="date" name="to" value="{{ request('to') }}"></label>
        @include('Hr.salary_section.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('advances.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('advances.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $advances->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Date</th><th>Employee</th><th style="text-align:right">Amount</th><th>Reason</th><th></th></tr></thead>
            <tbody>
                @forelse ($advances as $a)
                    <tr>
                        <td>{{ $a->advance_date->format('d M Y') }}</td>
                        <td>{{ $a->employee->employee_name }}</td>
                        <td style="text-align:right">{{ number_format($a->amount, 2) }}</td>
                        <td>{{ $a->reason ?? '-' }}</td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('advances.index', ['edit' => $a->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('advances.destroy', $a) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this advance?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pager">{{ $advances->links() }}</div>
</div>

@if ($editing)
    <div class="modal" onclick="if (event.target === this) window.location = '{{ route('advances.index') }}'">
        <div class="modal-box">
            <h3>{{ $editing->exists ? 'Edit' : 'Add' }} advance</h3>
            <form method="POST" class="form-grid"
                  action="{{ $editing->exists ? route('advances.update', $editing) : route('advances.store') }}">
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
                <label>Advance date
                    <input type="date" name="advance_date" required
                           value="{{ old('advance_date', $editing->advance_date?->format('Y-m-d') ?? now()->toDateString()) }}">
                </label>
                <label>Amount <input type="number" step="any" min="0.01" name="amount" required value="{{ old('amount', $editing->amount) }}"></label>
                <label>Reason <input name="reason" value="{{ old('reason', $editing->reason) }}"></label>
                <div class="actions">
                    <button type="submit" class="btn primary">Save</button>
                    <a href="{{ route('advances.index') }}" class="btn ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
