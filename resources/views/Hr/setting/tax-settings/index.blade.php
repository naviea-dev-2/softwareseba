

@extends('Hr.setting.partial.layout')
@section('title','Tax Setting')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search tax name">
        @include('Hr.setting.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('tax-settings.index') }}" class="btn ghost">Clear filters</a>
        <a href="{{ route('tax-settings.index', ['add' => 1]) }}" class="btn primary">+ Add</a>
        <span class="count">{{ $settings->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Type</th><th style="text-align:right">Rate</th><th style="text-align:right">Threshold</th><th>Effective from</th><th>Slabs</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($settings as $s)
                    <tr>
                        <td>{{ $s->name }}</td>
                        <td>{{ $s->tax_type }}</td>
                        <td style="text-align:right">{{ (float) $s->rate }}</td>
                        <td style="text-align:right">{{ number_format($s->threshold, 2) }}</td>
                        <td>{{ $s->effective_from->format('d M Y') }}</td>
                        <td>
                            @if ($s->tax_type === 'Slab')
                                <a class="link" href="{{ route('tax-slabs.index', $s) }}">{{ $s->slabs_count }} slab(s)</a>
                            @else
                                -
                            @endif
                        </td>
                        <td><span class="badge">{{ $s->status }}</span></td>
                        <td style="text-align:right">
                            <a class="link" href="{{ route('tax-settings.index', ['edit' => $s->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('tax-settings.destroy', $s) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this tax setting?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pager">{{ $settings->links() }}</div>
</div>

@if ($editing)
    <div class="modal" onclick="if (event.target === this) window.location = '{{ route('tax-settings.index') }}'">
        <div class="modal-box">
            <h3>{{ $editing->exists ? 'Edit' : 'Add' }} tax setting</h3>
            <form method="POST" class="form-grid"
                  action="{{ $editing->exists ? route('tax-settings.update', $editing) : route('tax-settings.store') }}">
                @csrf
                @if ($editing->exists) @method('PUT') @endif
                <label>Tax name <input name="name" required value="{{ old('name', $editing->name) }}"></label>
                <label>Tax type
                    <select name="tax_type" required>
                        @foreach (['Percentage', 'Fixed Amount', 'Slab'] as $t)
                            <option @selected(old('tax_type', $editing->tax_type) === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Rate / amount <input type="number" step="any" min="0" name="rate" required value="{{ old('rate', $editing->rate) }}"></label>
                <label>Taxable threshold (exempt) <input type="number" step="any" min="0" name="threshold" value="{{ old('threshold', $editing->threshold ?? 0) }}"></label>
                <label>Effective from
                    <input type="date" name="effective_from" required
                           value="{{ old('effective_from', $editing->effective_from?->format('Y-m-d') ?? now()->toDateString()) }}">
                </label>
                <label>Status
                    <select name="status" required>
                        <option @selected(old('status', $editing->status) === 'Active')>Active</option>
                        <option @selected(old('status', $editing->status) === 'Inactive')>Inactive</option>
                    </select>
                </label>
                <div class="actions">
                    <button type="submit" class="btn primary">Save</button>
                    <a href="{{ route('tax-settings.index') }}" class="btn ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
