<div class="modal" onclick="if (event.target === this) window.location = '{{ route('overtime.index') }}'">
    <div class="modal-box">
        <h3>{{ $editing->exists ? 'Edit' : 'Add' }} overtime setting</h3>
        <form method="POST" class="form-grid"
              action="{{ $editing->exists ? route('overtime.update', $editing) : route('overtime.store') }}">
            @csrf
            @if ($editing->exists) @method('PUT') @endif

            <label>Setting name <input name="name" required value="{{ old('name', $editing->name) }}"></label>
            <label>Rate multiplier <input type="number" step="0.01" min="0" name="rate" required value="{{ old('rate', $editing->rate) }}"></label>
            <label>Max hours / day <input type="number" step="0.1" min="0" name="max_day" required value="{{ old('max_day', $editing->max_day) }}"></label>
            <label>Max hours / month <input type="number" step="0.1" min="0" name="max_month" required value="{{ old('max_month', $editing->max_month) }}"></label>
            <label>Designation
                <select name="designation_id">
                    <option value="">All designations</option>
                    @foreach ($designations as $d)
                        <option value="{{ $d->id }}" @selected(old('designation_id', $editing->designation_id) == $d->id)>{{ $d->title }}</option>
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
                <a href="{{ route('overtime.index') }}" class="btn ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
