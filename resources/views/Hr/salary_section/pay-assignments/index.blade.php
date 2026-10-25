@extends('Hr.salary_section.partial.layout')
@section('title','Payroll Assign')
@section('setting-content')

<div class="card">
    <form method="GET" class="toolbar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search employee">
        <select name="department_id">
            <option value="">All departments</option>
            @foreach ($departments as $d)
                <option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
        @include('Hr.salary_section.partial.per-page')
        <button type="submit" class="btn primary">Filter</button>
        <a href="{{ route('pay-assignments.index') }}" class="btn ghost">Clear filters</a>
        <span class="count">{{ $employees->total() }} employees</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    @foreach ($heads as $h)
                        <th>{{ $h->name }}<br><small style="text-transform:none">{{ $h->type }} · {{ $h->calc }}</small></th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $e)
                    <tr>
                        <td>{{ $e->employee_name }}</td>
                        <td>{{ $e->department?->name ?? '-' }}</td>
                        @foreach ($heads as $h)
                            @php $a = $e->payAssignments->firstWhere('salary_head_id', $h->id); @endphp
                            <td style="text-align:right">
                                @if ($a)
                                    {{ (float) $a->value }}{{ $h->calc === '% of Basic' ? '%' : '' }}
                                @else
                                    <span style="color:var(--muted)">-</span>
                                @endif
                            </td>
                        @endforeach
                        <td style="text-align:right">
                            <a class="link" href="{{ route('pay-assignments.index', ['edit' => $e->id]) }}">Edit all</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $heads->count() + 3 }}" class="empty">No employees found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pager">{{ $employees->links() }}</div>
</div>

@if ($editing)
    <div class="modal" onclick="if (event.target === this) window.location = '{{ route('pay-assignments.index') }}'">
        <div class="modal-box">
            <h3>Salary heads: {{ $editing->name }}</h3>
            <p style="color:var(--muted);font-size:12px;margin-top:0">
                Enter an amount for each head (a percentage for "% of Basic" heads). Leave blank to remove the head.
            </p>
            <form method="POST" action="{{ route('pay-assignments.update', $editing) }}" class="form-grid">
                @csrf
                @method('PUT')
                @foreach ($heads as $h)
                    @php $current = $editing->payAssignments->firstWhere('salary_head_id', $h->id)?->value; @endphp
                    <label>{{ $h->name }} ({{ $h->calc === '% of Basic' ? '% of basic' : 'fixed amount' }})
                        <input type="number" step="any" min="0" name="values[{{ $h->id }}]"
                               value="{{ old('values.'.$h->id, $current !== null ? (float) $current : '') }}">
                    </label>
                @endforeach
                <div class="actions">
                    <button type="submit" class="btn primary">Save all</button>
                    <a href="{{ route('pay-assignments.index') }}" class="btn ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
