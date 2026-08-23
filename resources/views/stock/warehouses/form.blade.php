<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Warehouse Name</label>
        <input type="text" name="name" value="{{ old('name', $warehouse->name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Code</label>
        <input type="text" name="code" value="{{ old('code', $warehouse->code ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Type</label>
        <select name="type" id="type" class="form-control">
            <option value="main">Main Warehouse</option>
            <option value="secondary">Secondary Warehouse</option>
            <option value="regional">Regional Warehouse</option>
            <option value="virtual">Virtual Warehouse</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Location</label>
        <input type="text" name="location" value="{{ old('location', $warehouse->location ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Capacity</label>
        <input type="number" name="capacity" value="{{ old('capacity', $warehouse->capacity ?? 0) }}" min="0" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Contact Person</label>
        <input type="text" name="contact_person" value="{{ old('contact_person', $warehouse->contact_person ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Contact Phone</label>
        <input type="text" name="contact_phone" value="{{ old('contact_phone', $warehouse->contact_phone ?? '') }}" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">Address</label>
        <textarea name="address" rows="3" class="form-control">{{ old('address', $warehouse->address ?? '') }}</textarea>
    </div>
</div>