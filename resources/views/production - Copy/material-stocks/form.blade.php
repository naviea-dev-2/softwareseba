@extends('inc.master')

@section('content')
<div class="p-6 max-w-3xl mx-auto" x-data="stockForm()">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('production.material-stock.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 8.35V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8.35A2 2 0 0 1 3.26 6.5l8-3.2a2 2 0 0 1 1.48 0l8 3.2A2 2 0 0 1 22 8.35Z"/><path d="M6 18h12"/><path d="M6 14h12"/><path d="M6 10h12"/></svg>
            {{ isset($stock) ? 'Edit Stock' : 'New Stock' }}
        </h1>
    </div>

    @if($errors->has('general') || session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="text-red-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <div class="flex-1"><p class="text-sm font-medium text-red-800">{{ $errors->first('general') ?? session('error') }}</p></div>
    </div>
    @endif

    <form method="POST" action="{{ isset($stock) ? route('production.material-stock.update', $stock) : route('production.material-stock.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5" @submit.prevent="loading=true;$el.submit()">
        @csrf
        @if(isset($stock)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Raw Material <span class="text-red-500">*</span></label>
                <div class="relative" @click.outside="open = false">
                    <template x-if="selectedMaterial">
                        <div class="w-full px-3 py-2 border border-gray-300 rounded-lg flex items-center justify-between bg-gray-50">
                            <span class="text-gray-900 font-medium text-sm truncate" x-text="selectedMaterial"></span>
                            <button type="button" @click="clearMaterial()" class="p-1 hover:bg-gray-200 rounded-full ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    </template>
                    <template x-if="!selectedMaterial">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" x-model="materialQuery" @focus="open=true" @input="open=true;filterMaterials()" class="w-full pl-10 pr-10 py-2 border {{ $errors->has('raw_material_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none" placeholder="Search material...">
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </template>
                    <div x-show="open && !selectedMaterial" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto">
                        <template x-for="m in filteredMaterials" :key="m.id">
                            <button type="button" @click="selectMaterial(m)" class="w-full px-4 py-2.5 text-left hover:bg-gray-50 flex flex-col border-b border-gray-50 last:border-0">
                                <span class="text-sm font-medium text-gray-900" x-text="m.name"></span>
                                <span class="text-xs text-gray-500" x-show="m.sku" x-text="'SKU: '+m.sku"></span>
                            </button>
                        </template>
                        <div x-show="filteredMaterials.length===0" class="px-4 py-3 text-sm text-gray-500 text-center">No materials found</div>
                    </div>
                    <input type="hidden" name="raw_material_id" :value="materialId">
                </div>
                @error('raw_material_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Batch Number</label>
                <input name="batch_number" value="{{ old('batch_number', $stock->batch_number ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('batch_number') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('batch_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                <input name="quantity" type="number" step="0.01" value="{{ old('quantity', $stock->quantity ?? '') }}" required class="w-full px-3 py-2 border {{ $errors->has('quantity') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('quantity')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reserved Quantity</label>
                <input name="reserved_quantity" type="number" step="0.01" value="{{ old('reserved_quantity', $stock->reserved_quantity ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('reserved_quantity') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('reserved_quantity')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input name="location" value="{{ old('location', $stock->location ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('location') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                <input name="expiry_date" type="date" value="{{ old('expiry_date', $stock->expiry_date ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('expiry_date') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('expiry_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('production.material-stock.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" :disabled="loading" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 disabled:opacity-50">
                <span x-text="loading ? 'Saving...' : ({{ isset($stock) ? 'true' : 'false' }} ? 'Update' : 'Create')"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function stockForm() {
    return {
        loading: false,
        open: false,
        materialQuery: '',
        materialId: '{{ old('raw_material_id', $stock->raw_material_id ?? '') }}',
        selectedMaterial: '',
        materials: @json($materials ?? []),
        get filteredMaterials() {
            if (!this.materialQuery) return this.materials;
            const q = this.materialQuery.toLowerCase();
            return this.materials.filter(m => m.name.toLowerCase().includes(q) || (m.sku && m.sku.toLowerCase().includes(q)));
        },
        init() {
            const id = this.materialId;
            if (id) {
                const found = this.materials.find(m => String(m.id) === String(id));
                this.selectedMaterial = found ? `${found.name}${found.sku ? ' ('+found.sku+')' : ''}` : `Material #${id}`;
            }
        },
        selectMaterial(m) {
            this.materialId = m.id;
            this.selectedMaterial = `${m.name}${m.sku ? ' ('+m.sku+')' : ''}`;
            this.materialQuery = '';
            this.open = false;
        },
        clearMaterial() {
            this.materialId = '';
            this.selectedMaterial = '';
            this.materialQuery = '';
        },
        filterMaterials() {}
    }
}
</script>
@endpush
@endsection