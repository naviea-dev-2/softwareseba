@extends('Hr.setting.partial.layout')
@section('title','Deparment')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search name or code">
        <select name="status">
            <option value="">All status</option>
            <option @selected(request('status') === 'Active')>Active</option>
            <option @selected(request('status') === 'Inactive')>Inactive</option>
        </select>
        @include('Hr.setting.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('departments.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('departments.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $departments->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Code</th><th>Name</th><th>Head</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($departments as $d)
                    <tr>
                        <td>{{ $d->code }}</td>
                        <td>{{ $d->name }}</td>
                        <td>{{ $d->head ?? '-' }}</td>
                        <td><span class="badge">{{ $d->status }}</span></td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('departments.index', ['edit' => $d->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('departments.destroy', $d) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this department?')">
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
    <div class="pager">{{ $departments->links() }}</div>
</div>

@if ($editing)
    @include('Hr.setting.departments._form')
@endif
@endsection
