@extends('Hr.setting.partial.layout')
@section('title','Overtime')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search setting">
        <select name="status">
            <option value="">All status</option>
            <option @selected(request('status') === 'Active')>Active</option>
            <option @selected(request('status') === 'Inactive')>Inactive</option>
        </select>
        @include('Hr.setting.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('overtime.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('overtime.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $settings->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th style="text-align:right">Rate</th><th style="text-align:right">Max / day</th><th style="text-align:right">Max / month</th><th>Designation</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($settings as $s)
                    <tr>
                        <td>{{ $s->name }}</td>
                        <td style="text-align:right">{{ $s->rate }}</td>
                        <td style="text-align:right">{{ $s->max_day }}</td>
                        <td style="text-align:right">{{ $s->max_month }}</td>
                        <td>{{ $s->designation?->title ?? 'All' }}</td>
                        <td><span class="badge">{{ $s->status }}</span></td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('overtime.index', ['edit' => $s->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('overtime.destroy', $s) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this setting?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pager">{{ $settings->links() }}</div>
</div>

@if ($editing)
    @include('Hr.setting.overtime._form')
@endif
@endsection
