<div class="modal" onclick="if (event.target === this) window.location = '{{ route('late-rules.index') }}'">
    <div class="modal-box">
        <h3>{{ $editing->exists ? 'Edit' : 'Add' }} late roll rule</h3>
        <form method="POST" class="form-grid"
              action="{{ $editing->exists ? route('late-rules.update', $editing) : route('late-rules.store') }}">
            @csrf
            @if ($editing->exists) @method('PUT') @endif

            <label>Rule name <input name="name" required value="{{ old('name', $editing->name) }}"></label>
            <label>Grace (min) <input type="number" min="0" name="grace_min" required value="{{ old('grace_min', $editing->grace_min) }}"></label>
            <label>Late count limit <input type="number" min="0" name="late_limit" required value="{{ old('late_limit', $editing->late_limit) }}"></label>
            <label>Deduction
                <select name="deduction" required>
                    @foreach (['Warning', 'Half Day', 'Full Day', 'Salary cut'] as $d)
                        <option @selected(old('deduction', $editing->deduction) === $d)>{{ $d }}</option>
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
                <a href="{{ route('late-rules.index') }}" class="btn ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
