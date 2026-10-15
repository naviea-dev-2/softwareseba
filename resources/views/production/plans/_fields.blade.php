<div class="factory-grid">
    <div class="factory-field"><label for="plan_number">Plan Number *</label><input type="text" id="plan_number" name="plan_number" value="{{ old('plan_number', $plan->plan_number ?? '') }}" required>@error('plan_number')<div class="factory-error">{{ $message }}</div>@enderror</div>
    <div class="factory-field"><label for="plan_period_start">Period Start *</label><input type="date" id="plan_period_start" name="plan_period_start" value="{{ old('plan_period_start', $plan->plan_period_start ?? '') }}" required>@error('plan_period_start')<div class="factory-error">{{ $message }}</div>@enderror</div>
    <div class="factory-field"><label for="plan_period_end">Period End *</label><input type="date" id="plan_period_end" name="plan_period_end" value="{{ old('plan_period_end', $plan->plan_period_end ?? '') }}" required>@error('plan_period_end')<div class="factory-error">{{ $message }}</div>@enderror</div>
    <div class="factory-field"><label for="target_quantity">Target Quantity *</label><input type="number" id="target_quantity" name="target_quantity" value="{{ old('target_quantity', $plan->target_quantity ?? '') }}" step="any" min="0" required>@error('target_quantity')<div class="factory-error">{{ $message }}</div>@enderror</div>
    <div class="factory-field"><label for="planned_capacity_hours">Capacity Hours</label><input type="number" id="planned_capacity_hours" name="planned_capacity_hours" value="{{ old('planned_capacity_hours', $plan->planned_capacity_hours ?? '') }}" step="any" min="0">@error('planned_capacity_hours')<div class="factory-error">{{ $message }}</div>@enderror</div>
    <div class="factory-field"><label for="status">Status</label><select id="status" name="status">
            <option value="">Select Status</option>
            <option value="draft" {{ (string) old('status', $plan->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="approved" {{ (string) old('status', $plan->status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="active" {{ (string) old('status', $plan->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="completed" {{ (string) old('status', $plan->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ (string) old('status', $plan->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>@error('status')<div class="factory-error">{{ $message }}</div>@enderror</div>
</div>