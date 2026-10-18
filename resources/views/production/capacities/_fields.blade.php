{{-- Production Capacity fields partial: include inside your existing create/edit form. --}}
<style>
.capacity-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}
.capacity-form-section{margin-bottom:24px;padding:24px;background:#fff;border:1px solid #e3eaf3;border-radius:16px;box-shadow:0 5px 20px rgba(30,55,85,.045)}
.capacity-section-head{display:flex;align-items:center;gap:13px;margin-bottom:22px;padding-bottom:16px;border-bottom:1px solid #edf1f6}
.capacity-section-icon{width:44px;height:44px;border-radius:12px;background:#eaf2ff;color:#2563eb;display:grid;place-items:center;font-size:23px;flex-shrink:0}
.capacity-section-title{font-size:17px;font-weight:750;color:#17304f;margin:0}
.capacity-section-subtitle{font-size:13px;color:#77879a;margin:4px 0 0}
.capacity-field label{display:block;font-size:13px;font-weight:700;color:#35475e;margin-bottom:8px}
.capacity-field .form-control,.capacity-field .form-select{width:100%;min-height:47px;border:1px solid #d9e3ee;border-radius:10px;padding:10px 13px;background:#fff;color:#25354a}
.capacity-field .form-control:focus,.capacity-field .form-select:focus{outline:none;border-color:#6399fa;box-shadow:0 0 0 3px rgba(37,99,235,.10)}
.capacity-field .is-invalid{border-color:#dc3545}
.capacity-error{font-size:12px;color:#dc3545;margin-top:5px}
.capacity-hint{font-size:12px;color:#8290a3;margin-top:6px}
.capacity-required{color:#e11d48}
@media(max-width:700px){.capacity-form-grid{grid-template-columns:1fr}.capacity-form-section{padding:17px}}
</style>

<div class="capacity-form-section">
    <div class="capacity-section-head">
        <div class="capacity-section-icon"><i class="bx bx-time-five"></i></div>
        <div><h4 class="capacity-section-title">Shift &amp; Resource Assignment</h4><p class="capacity-section-subtitle">Select the shift, machine, operator and finished product.</p></div>
    </div>
    <div class="capacity-form-grid">
        <div class="capacity-field">
            <label for="shift">Production Shift <span class="capacity-required">*</span></label>
            <select id="shift" name="shift" class="form-select @error('shift') is-invalid @enderror" required>
                <option value="">Select Shift</option>
                @foreach(['morning' => 'Morning', 'evening' => 'Evening', 'night' => 'Night'] as $value => $label)
                    <option value="{{ $value }}" @selected((string)old('shift', $capacity->shift ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('shift')<div class="capacity-error">{{ $message }}</div>@enderror
        </div>
        <div class="capacity-field">
            <label for="machine_id">Machine</label>
            <select id="machine_id" name="machine_id" class="form-select @error('machine_id') is-invalid @enderror">
                <option value="">Select Machine</option>
                @foreach(($machines ?? collect()) as $option)
                    <option value="{{ $option->id }}" @selected((string)old('machine_id', $capacity->machine_id ?? '') === (string)$option->id)>{{ $option->name ?? $option->id }}</option>
                @endforeach
            </select>
            @error('machine_id')<div class="capacity-error">{{ $message }}</div>@enderror
        </div>
        <div class="capacity-field">
            <label for="worker_id">Responsible Worker</label>
            <select id="worker_id" name="worker_id" class="form-select @error('worker_id') is-invalid @enderror">
                <option value="">Select Worker</option>
                @foreach(($workers ?? collect()) as $option)
                    <option value="{{ $option->id }}" @selected((string)old('worker_id', $capacity->worker_id ?? '') === (string)$option->id)>{{ $option->name ?? $option->id }}</option>
                @endforeach
            </select>
            @error('worker_id')<div class="capacity-error">{{ $message }}</div>@enderror
        </div>
        <div class="capacity-field">
            <label for="product_id">Finished Product</label>
            <select id="product_id" name="product_id" class="form-select @error('product_id') is-invalid @enderror">
                <option value="">Select Product</option>
                @foreach(($products ?? collect()) as $option)
                    <option value="{{ $option->id }}" @selected((string)old('product_id', $capacity->product_id ?? '') === (string)$option->id)>{{ $option->product_name ?? $option->id }}</option>
                @endforeach
            </select>
            <div class="capacity-hint">Example: Atta, Maida, Suji or Bhushi.</div>
            @error('product_id')<div class="capacity-error">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="capacity-form-section">
    <div class="capacity-section-head">
        <div class="capacity-section-icon"><i class="bx bx-tachometer"></i></div>
        <div><h4 class="capacity-section-title">Production Performance</h4><p class="capacity-section-subtitle">Define the machine's rated capacity and expected efficiency.</p></div>
    </div>
    <div class="capacity-form-grid">
        <div class="capacity-field">
            <label for="max_units_per_hour">Maximum Units per Hour</label>
            <input type="number" id="max_units_per_hour" name="max_units_per_hour" class="form-control @error('max_units_per_hour') is-invalid @enderror" value="{{ old('max_units_per_hour', $capacity->max_units_per_hour ?? '') }}" min="0" step="any" placeholder="e.g. 5 tone">
            <div class="capacity-hint">Use a consistent unit, such as tons/hour, across capacity records.</div>
            @error('max_units_per_hour')<div class="capacity-error">{{ $message }}</div>@enderror
        </div>
        <div class="capacity-field">
            <label for="efficiency_rate">Expected Efficiency Rate (%)</label>
            <input type="number" id="efficiency_rate" name="efficiency_rate" class="form-control @error('efficiency_rate') is-invalid @enderror" value="{{ old('efficiency_rate', $capacity->efficiency_rate ?? '') }}" min="0" max="100" step="any" placeholder="e.g. 80%">
            <div class="capacity-hint">100% = rated capacity; 80% = 80% of rated capacity.</div>
            @error('efficiency_rate')<div class="capacity-error">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
