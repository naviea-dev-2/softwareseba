<div class="modal" onclick="if (event.target === this) window.location = '{{ route('holidays.index') }}'">
    <div class="modal-box">
        <h3>{{ $editing->exists ? 'Edit' : 'Add' }} holiday</h3>
        <form method="POST" class="form-grid"
              action="{{ $editing->exists ? route('holidays.update', $editing) : route('holidays.store') }}">
            @csrf
            @if ($editing->exists) @method('PUT') @endif

            <label>Date
                <input type="date" name="holiday_date" required value="{{ old('holiday_date', $editing->holiday_date?->format('Y-m-d')) }}">
            </label>
            <label>Holiday name <input name="name" required value="{{ old('name', $editing->name) }}"></label>
            <label>Type
                <select name="type" required>
                    @foreach (['Public', 'Optional', 'Weekend'] as $t)
                        <option @selected(old('type', $editing->type) === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </label>
            <label>Applies to
                <select name="department_id">
                    <option value="">All departments</option>
                    @foreach ($departments as $d)
                        <option value="{{ $d->id }}" @selected(old('department_id', $editing->department_id) == $d->id)>{{ $d->name }}</option>
                    @endforeach
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
                <a href="{{ route('holidays.index') }}" class="btn ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
