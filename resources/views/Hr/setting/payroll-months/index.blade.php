@extends('Hr.setting.partial.layout')
@section('title','Month')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <label>From <input type="month" name="from" value="{{ request('from') }}"></label>
        <label>To <input type="month" name="to" value="{{ request('to') }}"></label>
        <select name="status">
            <option value="">All status</option>
            <option @selected(request('status') === 'Open')>Open</option>
            <option @selected(request('status') === 'Closed')>Closed</option>
            <option @selected(request('status') === 'Locked')>Locked</option>
        </select>
        @include('Hr.setting.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('payroll-months.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('payroll-months.index', ['add' => 1]) }}" class="btn primary">+ Add month</a>
        <span class="count">{{ $months->total() }} months</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th style="text-align:right">Working days</th>
                    <th style="text-align:right">Salary generated</th>
                    <th style="text-align:right">Net total</th>
                    <th style="text-align:right">Paid</th>
                    <th style="text-align:right">Balance</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($months as $m)
                    <tr>
                        <td>{{ $m->label() }} <small style="color:var(--muted)">({{ $m->month }})</small></td>
                        <td style="text-align:right">{{ $m->working_days }}</td>
                        <td style="text-align:right">{{ $m->generated }}</td>
                        <td style="text-align:right">{{ number_format($m->net_total, 2) }}</td>
                        <td style="text-align:right">{{ number_format($m->paid_total, 2) }}</td>
                        <td style="text-align:right">{{ number_format($m->net_total - $m->paid_total, 2) }}</td>
                        <td><span class="badge">{{ $m->status }}</span></td>
                        <td style="text-align:right; white-space:nowrap">
                            <form method="POST" action="{{ route('payroll-months.generate', $m) }}" style="display:inline"
                                  onsubmit="return confirm('Generate salary records for {{ $m->label() }}?')">
                                @csrf
                                <button type="submit" class="link" @disabled($m->status !== 'Open')>Generate salary</button>
                            </form>
                            {{-- <a class="link" href="{{ route('salary.index', ['month' => $m->month]) }}">View salary</a> --}}
                            <a class="link" href="{{ route('payroll-months.index', ['edit' => $m->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('payroll-months.destroy', $m) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this month? Salary records are kept.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty">No months yet. Click "+ Add month" to create one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pager">
        {{ $months->links('pagination::bootstrap-5') }}
    </div>
    {{-- <div class="pager">{{ $months->links() }}</div> --}}
</div>

@if ($editing)
    <div class="modal" onclick="if (event.target === this) window.location = '{{ route('payroll-months.index') }}'">
        <div class="modal-box">
            <h3>{{ $editing->exists ? 'Edit' : 'Add' }} payroll month</h3>
            <form method="POST" class="form-grid"
                  action="{{ $editing->exists ? route('payroll-months.update', $editing) : route('payroll-months.store') }}">
                @csrf
                @if ($editing->exists) @method('PUT') @endif
                <label>Month
                    <input type="month" name="month" required value="{{ old('month', $editing->month ?? now()->format('Y-m')) }}">
                </label>
                <label>Working days
                    <input type="number" min="1" max="31" name="working_days" required value="{{ old('working_days', $editing->working_days ?? 26) }}">
                </label>
                <label>Status
                    <select name="status" required>
                        @foreach (['Open', 'Closed', 'Locked'] as $s)
                            <option @selected(old('status', $editing->status ?? 'Open') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="actions">
                    <button type="submit" class="btn primary">Save</button>
                    <a href="{{ route('payroll-months.index') }}" class="btn ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
