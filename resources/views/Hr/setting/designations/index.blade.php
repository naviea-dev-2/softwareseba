
@extends('Hr.setting.partial.layout')
@section('title','Designation')
@section('setting-content')


<div class="card">
    <form method="GET" class="toolbar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search designation">
        <select name="department_id">
            <option value="">All departments</option>
            @foreach ($departments as $d)
                <option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">All status</option>
            <option @selected(request('status') === 'Active')>Active</option>
            <option @selected(request('status') === 'Inactive')>Inactive</option>
        </select>
        @include('Hr.setting.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('designations.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('designations.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $designations->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Designation</th><th>Department</th><th>Level</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($designations as $d)
                    <tr>
                        <td>{{ $d->name }}</td>
                        <td>{{ $d->department?->name ?? '-' }}</td>
                        <td>{{ $d->level }}</td>
                        <td><span class="badge">{{ $d->status }}</span></td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('designations.index', ['edit' => $d->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('designations.destroy', $d) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this designation?')">
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
    <div class="pager">{{ $designations->links() }}</div>
</div>

@if ($editing)
    @include('Hr.setting.designations._form')
@endif
@endsection
