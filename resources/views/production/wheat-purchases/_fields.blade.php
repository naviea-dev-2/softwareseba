@php($p = $purchase ?? null)
<div class="wheat-card">
    <h4>Delivery Information</h4>
    <div class="wheat-grid">
        <div class="wheat-field"><label>Purchase Date</label><input type="date" name="purchase_date" value="{{ old('purchase_date', $p?->purchase_date?->format('Y-m-d')) }}"></div>
        <div class="wheat-field"><label>Weight Slip ID *</label><input required name="weight_slip_no" maxlength="100" placeholder="e.g. #000715" value="{{ old('weight_slip_no', $p?->weight_slip_no) }}"></div>
        <div class="wheat-field"><label>Raw Material</label><select name="raw_material_id">
                <option value="">Wheat (unlinked)</option>@foreach($materials as $material)<option value="{{ $material->id }}" @selected(old('raw_material_id',$p?->raw_material_id)==$material->id)>{{ $material->name }} ({{ $material->unit_of_measure }})</option>@endforeach
            </select></div>
        <!-- <div class="wheat-field"><label>Supplier / Party Name</label><input name="supplier_name" value="{{ old('supplier_name',$p?->supplier_name) }}"></div> -->
        <div class="wheat-field">
            <label>Supplier / Party Name</label>

            <select name="supplier_id">
                <option value="">Select Supplier</option>

                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    @selected((string) old('supplier_id', $p?->supplier_id) === (string) $supplier->id)>
                    {{ $supplier->name }}
                </option>
                @endforeach
            </select>

            @error('supplier_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="wheat-field"><label>Vehicle No</label><input name="vehicle_no" value="{{ old('vehicle_no',$p?->vehicle_no) }}"></div>
        <div class="wheat-field"><label>Driver Name</label><input name="driver_name" value="{{ old('driver_name',$p?->driver_name) }}"></div>
        <div class="wheat-field"><label>Driver Mobile</label><input name="driver_mobile" value="{{ old('driver_mobile',$p?->driver_mobile) }}"></div>
        <div class="wheat-field"><label>Slip Receiver</label><input name="slip_receiver" value="{{ old('slip_receiver',$p?->slip_receiver) }}"></div>
        <div class="wheat-field"><label>Number of Bags</label><input type="number" min="0" name="bag_quantity" value="{{ old('bag_quantity',$p?->bag_quantity) }}"></div>
        <div class="wheat-field"><label>Quantity Description (Excel text)</label><input name="quantity_description" placeholder="e.g. 310 Bag" value="{{ old('quantity_description',$p?->quantity_description) }}"></div>
    </div>
</div>
<div class="wheat-card">
    <h4>Weight & Purchase Calculation</h4>
    <div class="wheat-grid">
        <div class="wheat-field"><label>Unload / Empty Weight (KG) *</label><input required type="number" step="0.01" min="0" id="empty_kg" name="empty_weight_kg" value="{{ old('empty_weight_kg',$p?->empty_weight_kg) }}"></div>
        <div class="wheat-field"><label>Load Weight (KG) *</label><input required type="number" step="0.01" min="0" id="loaded_kg" name="loaded_weight_kg" value="{{ old('loaded_weight_kg',$p?->loaded_weight_kg) }}"></div>
        <div class="wheat-field"><label>Price Per KG (৳) *</label><input required type="number" step="0.01" min="0" id="rate_kg" name="rate_per_kg" value="{{ old('rate_per_kg',$p?->rate_per_kg ?? 0) }}"></div>
        <div class="wheat-field"><label>Net Weight (KG)</label><input id="net_kg" readonly style="background:#eef5fd"></div>
        <div class="wheat-field"><label>Net Weight (TON)</label><input id="net_ton" readonly style="background:#eef5fd"></div>
        <div class="wheat-field"><label>Total Purchase (৳)</label><input id="total_amount" readonly style="background:#eef5fd;font-weight:700"></div>
        <div class="wheat-field"><label>Status</label><select name="status">
                <option value="draft" @selected(old('status',$p?->status ?? 'draft')==='draft')>Draft</option>
                <option value="confirmed" @selected(old('status',$p?->status)==='confirmed')>Confirmed</option>
                <option value="cancelled" @selected(old('status',$p?->status)==='cancelled')>Cancelled</option>
            </select></div>
        <div class="wheat-field wheat-full"><label>Remarks</label><textarea name="remarks">{{ old('remarks',$p?->remarks) }}</textarea></div>
    </div>
</div>
<div class="wheat-actions"><button type="submit" class="wheat-btn">Save Wheat Purchase</button><a href="{{ route('production.wheat-purchases.index') }}" class="wheat-btn light">Cancel</a></div>
<script>
    (function() {
        const ids = ['empty_kg', 'loaded_kg', 'rate_kg'];
        const els = ids.map(id => document.getElementById(id));

        function calc() {
            const [empty, loaded, rate] = els.map(e => Number(e.value) || 0);
            const net = Math.max(0, loaded - empty);
            document.getElementById('net_kg').value = net.toFixed(2);
            document.getElementById('net_ton').value = (net / 1000).toFixed(2);
            document.getElementById('total_amount').value = (net * rate).toFixed(2)
        }
        els.forEach(e => e.addEventListener('input', calc));
        calc()
    })();
</script>