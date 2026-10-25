@extends('Hr.setting.partial.layout')
@section('title','Holiday')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search holiday">
        <select name="type">
            <option value="">All types</option>
            <option @selected(request('type') === 'Public')>Public</option>
            <option @selected(request('type') === 'Optional')>Optional</option>
            <option @selected(request('type') === 'Weekend')>Weekend</option>
        </select>
        <select name="status">
            <option value="">All status</option>
            <option @selected(request('status') === 'Active')>Active</option>
            <option @selected(request('status') === 'Inactive')>Inactive</option>
        </select>
        @include('Hr.setting.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('holidays.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('holidays.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $holidays->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Date</th><th>Name</th><th>Type</th><th>Applies to</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($holidays as $h)
                    <tr>
                        <td>{{ $h->holiday_date->format('d M Y') }}</td>
                        <td>{{ $h->name }}</td>
                        <td>{{ $h->type }}</td>
                        <td>{{ $h->department?->name ?? 'All' }}</td>
                        <td><span class="badge">{{ $h->status }}</span></td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('holidays.index', ['edit' => $h->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('holidays.destroy', $h) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this holiday?')">
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
   <div class="pager">
        {{ $holidays->links('pagination::bootstrap-5') }}
    </div>     
    {{-- <div class="pager">{{ $holidays->links() }}</div> --}}
</div>

@if ($editing)
    @include('Hr.setting.holidays._form')
@endif
@endsection
