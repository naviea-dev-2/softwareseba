@extends('inc.master')

@section('content')
<div class="p-6 max-w-4xl mx-auto" x-data="bomForm()">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('production.boms.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
            {{ isset($bom) ? 'Edit BOM' : 'New BOM' }}
        </h1>
    </div>

    @if($errors->has('general') || session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 mb-6">
        <svg class="text-red-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <div class="flex-1">
            <p class="text-sm font-medium text-red-800">{{ $errors->first('general') ?? session('error') }}</p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ isset($bom) ? route('production.boms.update', $bom) : route('production.boms.store') }}" class="space-y-6" @submit.prevent="submitForm">
        @csrf
        @if(isset($bom)) @method('PUT') @endif

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">BOM Number <span class="text-red-500">*</span></label>
                    <input name="bom_number" value="{{ old('bom_number', $bom->bom_number ?? '') }}" required class="w-full px-3 py-2 border {{ $errors->has('bom_number') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                    @error('bom_number')<p class="mt-1 text-xs text-red-600 flex items-center gap-1"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input name="name" value="{{ old('name', $bom->name ?? '') }}" required class="w-full px-3 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                    @error('name')<p class="mt-1 text-xs text-red-600 flex items-center gap-1"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product ID</label>
                    <input name="product_id" type="number" value="{{ old('product_id', $bom->product_id ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('product_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                    @error('product_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Version</label>
                    <input name="version" type="number" value="{{ old('version', $bom->version ?? '1') }}" class="w-full px-3 py-2 border {{ $errors->has('version') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                    @error('version')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit of Measure</label>
                    <input name="unit_of_measure" value="{{ old('unit_of_measure', $bom->unit_of_measure ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('unit_of_measure') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                    @error('unit_of_measure')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $bom->is_active ?? 1) ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_default" value="0">
                    <input type="checkbox" name="is_default" value="1" {{ old('is_default', $bom->is_default ?? 0) ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-sm text-gray-700">Default BOM</span>
                </label>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">BOM Items</h2>
                <button type="button" @click="addItem" class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Add Item
                </button>
            </div>

            <template x-if="items.length === 0">
                <p class="text-gray-400 text-sm text-center py-6">No items added yet</p>
            </template>

            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-6 gap-3 items-start p-3 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Component Name *</label>
                            <input :name="`items[${index}][component_name]`" x-model="item.component_name" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-gray-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Material ID</label>
                            <input :name="`items[${index}][raw_material_id]`" x-model="item.raw_material_id" type="number" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-gray-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Qty Required *</label>
                            <input :name="`items[${index}][quantity_required]`" x-model="item.quantity_required" type="number" step="0.0001" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-gray-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Unit</label>
                            <input :name="`items[${index}][unit_of_measure]`" x-model="item.unit_of_measure" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-gray-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Wastage %</label>
                            <input :name="`items[${index}][wastage_percentage]`" x-model="item.wastage_percentage" type="number" step="0.01" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-gray-500 outline-none">
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <label class="block text-xs text-gray-500 mb-1">Sort</label>
                                <input :name="`items[${index}][sort_order]`" x-model="item.sort_order" type="number" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-gray-500 outline-none">
                            </div>
                            <button type="button" @click="removeItem(index)" class="p-1.5 hover:bg-red-100 rounded mb-0.5">
                                <svg class="text-red-500" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('production.boms.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" :disabled="loading" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 disabled:opacity-50">
                <span x-text="loading ? 'Saving...' : ({{ isset($bom) ? 'true' : 'false' }} ? 'Update' : 'Create')"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function bomForm() {
    return {
        loading: false,
        items: @json(old('items', isset($bom) ? $bom->items->map(fn($i) => [
            'id' => $i->id,
            'raw_material_id' => $i->raw_material_id ?? '',
            'component_name' => $i->component_name ?? '',
            'quantity_required' => $i->quantity_required ?? '0',
            'unit_of_measure' => $i->unit_of_measure ?? '',
            'wastage_percentage' => $i->wastage_percentage ?? '0',
            'sort_order' => $i->sort_order ?? '0',
        ]) : [])),
        addItem() {
            this.items.push({
                raw_material_id: '',
                component_name: '',
                quantity_required: '',
                unit_of_measure: '',
                wastage_percentage: '0',
                sort_order: String(this.items.length)
            });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        },
        submitForm(e) {
            this.loading = true;
            e.target.submit();
        }
    }
}
</script>
@endpush
@endsection