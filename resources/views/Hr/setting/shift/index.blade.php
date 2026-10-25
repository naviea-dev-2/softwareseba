
@extends('Hr.setting.partial.layout')
@section('title','Shift')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search shift">
        <select name="status">
            <option value="">All status</option>
            <option @selected(request('status') === 'Active')>Active</option>
            <option @selected(request('status') === 'Inactive')>Inactive</option>
        </select>
        @include('Hr.setting.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('shift.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('shift.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $shifts->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Start</th><th>End</th><th style="text-align:right">Break (min)</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($shifts as $s)
                    <tr>
                        <td>{{ $s->shiftName }}</td>
                        <td>{{ substr($s->startTime, 0, 5) }}</td>
                        <td>{{ substr($s->endTime, 0, 5) }}</td>
                        <td style="text-align:right">{{ $s->break_min }}</td>
                        <td><span class="badge">{{ $s->status }}</span></td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('shift.index', ['edit' => $s->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('shift.destroy', $s) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this shift?')">
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
    <div class="pager">{{ $shifts->links() }}</div>
</div>

@if ($editing)
    @include('Hr.setting.shift._form')
@endif
@endsection
