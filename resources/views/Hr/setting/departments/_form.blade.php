<div class="modal" onclick="if (event.target === this) window.location = '{{ route('departments.index') }}'">
    <div class="modal-box">
        <h3>{{ $editing->exists ? 'Edit' : 'Add' }} department</h3>
        <form method="POST" class="form-grid"
              action="{{ $editing->exists ? route('departments.update', $editing) : route('departments.store') }}">
            @csrf
            @if ($editing->exists) @method('PUT') @endif

            <label>Department name <input name="name" required value="{{ old('name', $editing->name) }}"></label>
            <label>Code <input name="code" required value="{{ old('code', $editing->code) }}"></label>
            <label>Head <input name="head" value="{{ old('head', $editing->head) }}"></label>
            <label>Status
                <select name="status" required>
                    <option @selected(old('status', $editing->status) === 'Active')>Active</option>
                    <option @selected(old('status', $editing->status) === 'Inactive')>Inactive</option>
                </select>
            </label>

            <div class="actions">
                <button type="submit" class="btn primary">Save</button>
                <a href="{{ route('departments.index') }}" class="btn ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
