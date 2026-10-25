@extends('Hr.salary_section.partial.layout')
@section('title','Salary Head')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search head">
        <select name="type">
            <option value="">All types</option>
            <option @selected(request('type') === 'Earning')>Earning</option>
            <option @selected(request('type') === 'Deduction')>Deduction</option>
        </select>
        <select name="status">
            <option value="">All status</option>
            <option @selected(request('status') === 'Active')>Active</option>
            <option @selected(request('status') === 'Inactive')>Inactive</option>
        </select>
        @include('Hr.salary_section.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('salary-heads.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('salary-heads.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $heads->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Type</th><th>Calculation</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($heads as $h)
                    <tr>
                        <td>{{ $h->name }}</td>
                        <td>{{ $h->type }}</td>
                        <td>{{ $h->calc }}</td>
                        <td><span class="badge">{{ $h->status }}</span></td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('salary-heads.index', ['edit' => $h->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('salary-heads.destroy', $h) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this salary head?')">
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
    <div class="pager">{{ $heads->links() }}</div>
</div>

@if ($editing)
    <div class="modal" onclick="if (event.target === this) window.location = '{{ route('salary-heads.index') }}'">
        <div class="modal-box">
            <h3>{{ $editing->exists ? 'Edit' : 'Add' }} salary head</h3>
            <form method="POST" class="form-grid"
                  action="{{ $editing->exists ? route('salary-heads.update', $editing) : route('salary-heads.store') }}">
                @csrf
                @if ($editing->exists) @method('PUT') @endif
                <label>Head name <input name="name" required value="{{ old('name', $editing->name) }}"></label>
                <label>Type
                    <select name="type" required>
                        <option @selected(old('type', $editing->type) === 'Earning')>Earning</option>
                        <option @selected(old('type', $editing->type) === 'Deduction')>Deduction</option>
                    </select>
                </label>
                <label>Calculation
                    <select name="calc" required>
                        <option @selected(old('calc', $editing->calc) === 'Fixed')>Fixed</option>
                        <option @selected(old('calc', $editing->calc) === '% of Basic')>% of Basic</option>
                    </select>
                </label>
                <label>Status
                    <select name="status" required>
                        <option @selected(old('status', $editing->status) === 'Active')>Active</option>
                        <option @selected(old('status', $editing->status) === 'Inactive')>Inactive</option>
                    </select>
                </label>
                <div class="actions">
                    <button type="submit" class="btn primary">Save</button>
                    <a href="{{ route('salary-heads.index') }}" class="btn ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
