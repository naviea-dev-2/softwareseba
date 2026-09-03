@extends('inc.master')

@section('content')
<div class="p-6 max-w-3xl mx-auto" x-data="maintenanceForm()">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('production.maintenances.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            {{ isset($maintenance) ? 'Edit Maintenance' : 'New Maintenance' }}
        </h1>
    </div>

    @if($errors->has('general') || session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="text-red-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <div class="flex-1"><p class="text-sm font-medium text-red-800">{{ $errors->first('general') ?? session('error') }}</p></div>
    </div>
    @endif

    <form method="POST" action="{{ isset($maintenance) ? route('production.maintenances.update', $maintenance) : route('production.maintenances.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5" @submit.prevent="loading=true;$el.submit()">
        @csrf
        @if(isset($maintenance)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Machine <span class="text-red-500">*</span></label>
                <div class="relative" @click.outside="open = false">
                    <template x-if="selectedMachine">
                        <div class="w-full px-3 py-2 border border-gray-300 rounded-lg flex items-center justify-between bg-gray-50">
                            <span class="text-gray-900 font-medium text-sm truncate" x-text="selectedMachine"></span>
                            <button type="button" @click="clearMachine()" class="p-1 hover:bg-gray-200 rounded-full ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    </template>
                    <template x-if="!selectedMachine">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" x-model="machineQuery" @focus="open=true" @input="open=true;filterMachines()" class="w-full pl-10 pr-10 py-2 border {{ $errors->has('machine_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none" placeholder="Search machine...">
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </template>
                    <div x-show="open && !selectedMachine" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto">
                        <template x-for="m in filteredMachines" :key="m.id">
                            <button type="button" @click="selectMachine(m)" class="w-full px-4 py-2.5 text-left hover:bg-gray-50 flex flex-col border-b border-gray-50 last:border-0">
                                <span class="text-sm font-medium text-gray-900" x-text="m.name"></span>
                                <span class="text-xs text-gray-500" x-show="m.code" x-text="'Code: '+m.code"></span>
                            </button>
                        </template>
                        <div x-show="filteredMachines.length===0" class="px-4 py-3 text-sm text-gray-500 text-center">No machines found</div>
                    </div>
                    <input type="hidden" name="machine_id" :value="machineId">
                </div>
                @error('machine_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Type</label>
                <select name="maintenance_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    @foreach(['preventive','corrective','predictive','emergency'] as $t)
                    <option value="{{ $t }}" {{ old('maintenance_type', $maintenance->maintenance_type ?? 'preventive') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date</label>
                <input name="scheduled_date" type="date" value="{{ old('scheduled_date', $maintenance->scheduled_date ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Completed Date</label>
                <input name="completed_date" type="date" value="{{ old('completed_date', $maintenance->completed_date ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">{{ old('description', $maintenance->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cost</label>
                <input name="cost" type="number" step="0.01" value="{{ old('cost', $maintenance->cost ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Downtime Hours</label>
                <input name="downtime_hours" type="number" step="0.01" value="{{ old('downtime_hours', $maintenance->downtime_hours ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    @foreach(['scheduled','in_progress','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $maintenance->status ?? 'scheduled') === $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Next Due Date</label>
            <input name="next_due_date" type="date" value="{{ old('next_due_date', $maintenance->next_due_date ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('production.maintenances.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" :disabled="loading" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 disabled:opacity-50">
                <span x-text="loading ? 'Saving...' : ({{ isset($maintenance) ? 'true' : 'false' }} ? 'Update' : 'Create')"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function maintenanceForm() {
    return {
        loading: false,
        open: false,
        machineQuery: '',
        machineId: '{{ old('machine_id', $maintenance->machine_id ?? '') }}',
        selectedMachine: '',
        machines: @json($machines ?? []),
        get filteredMachines() {
            if (!this.machineQuery) return this.machines;
            const q = this.machineQuery.toLowerCase();
            return this.machines.filter(m => m.name.toLowerCase().includes(q) || (m.code && m.code.toLowerCase().includes(q)));
        },
        init() {
            const id = this.machineId;
            if (id) {
                const found = this.machines.find(m => String(m.id) === String(id));
                this.selectedMachine = found ? `${found.name}${found.code ? ' ('+found.code+')' : ''}` : `Machine #${id}`;
            }
        },
        selectMachine(m) {
            this.machineId = m.id;
            this.selectedMachine = `${m.name}${m.code ? ' ('+m.code+')' : ''}`;
            this.machineQuery = '';
            this.open = false;
        },
        clearMachine() {
            this.machineId = '';
            this.selectedMachine = '';
            this.machineQuery = '';
        },
        filterMachines() {}
    }
}
</script>
@endpush
@endsection