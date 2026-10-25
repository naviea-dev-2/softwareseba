<div class="modal" onclick="if (event.target === this) window.location = '{{ route('shift.index') }}'">
    <div class="modal-box">
        <h3>{{ $editing->exists ? 'Edit' : 'Add' }} shift</h3>
        <form method="POST" class="form-grid"
              action="{{ $editing->exists ? route('shift.update', $editing) : route('shift.store') }}">
            @csrf
            @if ($editing->exists) @method('PUT') @endif

            <label>Shift name <input name="shiftName" required value="{{ old('shiftName', $editing->shiftName) }}"></label>
            <label>Start time <input type="time" name="startTime" required value="{{ old('startTime', $editing->startTime ? substr($editing->startTime, 0, 5) : '') }}"></label>
            <label>End time <input type="time" name="endTime" required value="{{ old('endTime', $editing->endTime ? substr($editing->endTime, 0, 5) : '') }}"></label>
            <label>Break (min) <input type="number" min="0" name="break_min" value="{{ old('break_min', $editing->break_min ?? 0) }}"></label>
            <label>Status
                <select name="status" required>
                    <option @selected(old('status', $editing->status) === 'Active')>Active</option>
                    <option @selected(old('status', $editing->status) === 'Inactive')>Inactive</option>
                </select>
            </label>

            <div class="actions">
                <button type="submit" class="btn primary">Save</button>
                <a href="{{ route('shift.index') }}" class="btn ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
