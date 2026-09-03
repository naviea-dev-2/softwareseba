@extends('inc.master')

@section('content')
<div class="p-6 max-w-3xl mx-auto" x-data="woForm()">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('production.production.work-orders.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            {{ isset($workOrder) ? 'Edit Work Order' : 'New Work Order' }}
        </h1>
    </div>

    @if($errors->has('general') || session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="text-red-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <div class="flex-1"><p class="text-sm font-medium text-red-800">{{ $errors->first('general') ?? session('error') }}</p></div>
    </div>
    @endif

    <form method="POST" action="{{ isset($workOrder) ? route('production.production.work-orders.update', $workOrder) : route('production.production.work-orders.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5" @submit.prevent="loading=true;$el.submit()">
        @csrf
        @if(isset($workOrder)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">WO Number <span class="text-red-500">*</span></label>
                <input name="work_order_number" value="{{ old('work_order_number', $workOrder->work_order_number ?? '') }}" required class="w-full px-3 py-2 border {{ $errors->has('work_order_number') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('work_order_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Operation</label>
                <input name="operation" value="{{ old('operation', $workOrder->operation ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('operation') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('operation')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Production Order</label>
                <select name="production_order_id" class="w-full px-3 py-2 border {{ $errors->has('production_order_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    <option value="">Select</option>
                    @foreach($productionOrders ?? [] as $po)
                    <option value="{{ $po->id }}" {{ old('production_order_id', $workOrder->production_order_id ?? '') == $po->id ? 'selected' : '' }}>{{ $po->order_number }}</option>
                    @endforeach
                </select>
                @error('production_order_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Machine</label>
                <select name="machine_id" class="w-full px-3 py-2 border {{ $errors->has('machine_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    <option value="">Select</option>
                    @foreach($machines ?? [] as $m)
                    <option value="{{ $m->id }}" {{ old('machine_id', $workOrder->machine_id ?? '') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>
                @error('machine_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Worker</label>
                <select name="worker_id" class="w-full px-3 py-2 border {{ $errors->has('worker_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    <option value="">Select</option>
                    @foreach($workers ?? [] as $w)
                    <option value="{{ $w->id }}" {{ old('worker_id', $workOrder->worker_id ?? '') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                    @endforeach
                </select>
                @error('worker_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity to Produce</label>
                <input name="quantity_to_produce" type="number" step="0.01" value="{{ old('quantity_to_produce', $workOrder->quantity_to_produce ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('quantity_to_produce') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('quantity_to_produce')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Produced</label>
                <input name="quantity_produced" type="number" step="0.01" value="{{ old('quantity_produced', $workOrder->quantity_produced ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('quantity_produced') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('quantity_produced')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    @foreach(['pending','in_progress','completed','on_hold'] as $s)
                    <option value="{{ $s }}" {{ old('status', $workOrder->status ?? 'pending') === $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                <input name="start_time" type="datetime-local" value="{{ old('start_time', $workOrder->start_time ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                <input name="end_time" type="datetime-local" value="{{ old('end_time', $workOrder->end_time ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('production.production.work-orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" :disabled="loading" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 disabled:opacity-50">
                <span x-text="loading ? 'Saving...' : ({{ isset($workOrder) ? 'true' : 'false' }} ? 'Update' : 'Create')"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>function woForm(){return{loading:false}}</script>
@endpush
@endsection