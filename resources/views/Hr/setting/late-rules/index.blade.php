@extends('Hr.setting.partial.layout')
@section('title','Deparment')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search rule">
        <select name="deduction">
            <option value="">All deductions</option>
            <option @selected(request('deduction') === 'Warning')>Warning</option>
            <option @selected(request('deduction') === 'Half Day')>Half Day</option>
            <option @selected(request('deduction') === 'Full Day')>Full Day</option>
            <option @selected(request('deduction') === 'Salary cut')>Salary cut</option>
        </select>
        <select name="status">
            <option value="">All status</option>
            <option @selected(request('status') === 'Active')>Active</option>
            <option @selected(request('status') === 'Inactive')>Inactive</option>
        </select>
        @include('Hr.setting.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('late-rules.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('late-rules.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $rules->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Rule</th><th style="text-align:right">Grace (min)</th><th style="text-align:right">Late limit</th><th>Deduction</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($rules as $r)
                    <tr>
                        <td>{{ $r->name }}</td>
                        <td style="text-align:right">{{ $r->grace_min }}</td>
                        <td style="text-align:right">{{ $r->late_limit }}</td>
                        <td>{{ $r->deduction }}</td>
                        <td><span class="badge">{{ $r->status }}</span></td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('late-rules.index', ['edit' => $r->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('late-rules.destroy', $r) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this rule?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pager">{{ $rules->links() }}</div>
</div>

@if ($editing)
    @include('Hr.setting.late-rules._form')
@endif
@endsection
