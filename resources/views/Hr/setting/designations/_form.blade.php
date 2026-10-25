<div class="modal" onclick="if (event.target === this) window.location = '{{ route('designations.index') }}'">
    <div class="modal-box">
        <h3>{{ $editing->exists ? 'Edit' : 'Add' }} designation</h3>
        <form method="POST" class="form-grid"
              action="{{ $editing->exists ? route('designations.update', $editing) : route('designations.store') }}">
            @csrf
            @if ($editing->exists) @method('PUT') @endif

            <label>Designation <input name="title" required value="{{ old('title', $editing->title) }}"></label>
            <label>Department
                <select name="department_id" required>
                    <option value="">— select —</option>
                    @foreach ($departments as $d)
                        <option value="{{ $d->id }}" @selected(old('department_id', $editing->department_id) == $d->id)>{{ $d->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Level
                <select name="level" required>
                    @foreach (['Junior', 'Mid', 'Senior', 'Lead', 'Manager'] as $lvl)
                        <option @selected(old('level', $editing->level) === $lvl)>{{ $lvl }}</option>
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
                <a href="{{ route('designations.index') }}" class="btn ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
