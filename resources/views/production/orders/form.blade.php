@extends('inc.master')

@section('content')
<div class="p-6 max-w-4xl mx-auto" x-data="orderForm()">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('production.production.orders.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" x2="12" y1="22.08" y2="12"/></svg>
            {{ isset($order) ? 'Edit Production Order' : 'New Production Order' }}
        </h1>
    </div>

    @if($errors->has('general') || session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="text-red-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <div class="flex-1"><p class="text-sm font-medium text-red-800">{{ $errors->first('general') ?? session('error') }}</p></div>
    </div>
    @endif

    <form method="POST" action="{{ isset($order) ? route('production.production.orders.update', $order) : route('production.production.orders.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5" @submit.prevent="loading=true;$el.submit()">
        @csrf
        @if(isset($order)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Order Number <span class="text-red-500">*</span></label>
                <input name="order_number" value="{{ old('order_number', $order->order_number ?? '') }}" required class="w-full px-3 py-2 border {{ $errors->has('order_number') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('order_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                <input name="quantity" type="number" step="0.01" value="{{ old('quantity', $order->quantity ?? '') }}" required class="w-full px-3 py-2 border {{ $errors->has('quantity') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('quantity')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                <select name="product_id" class="w-full px-3 py-2 border {{ $errors->has('product_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    <option value="">Select Product</option>
                    @foreach($products ?? [] as $p)
                    <option value="{{ $p->id }}" {{ old('product_id', $order->product_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                @error('product_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">BOM</label>
                <select name="bom_id" class="w-full px-3 py-2 border {{ $errors->has('bom_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    <option value="">Select BOM</option>
                    @foreach($boms ?? [] as $b)
                    <option value="{{ $b->id }}" {{ old('bom_id', $order->bom_id ?? '') == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->bom_number }})</option>
                    @endforeach
                </select>
                @error('bom_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Planned Start</label>
                <input name="planned_start" type="date" value="{{ old('planned_start', $order->planned_start ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('planned_start') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('planned_start')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Planned End</label>
                <input name="planned_end" type="date" value="{{ old('planned_end', $order->planned_end ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('planned_end') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('planned_end')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Actual Start</label>
                <input name="actual_start" type="date" value="{{ old('actual_start', $order->actual_start ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('actual_start') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Actual End</label>
                <input name="actual_end" type="date" value="{{ old('actual_end', $order->actual_end ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('actual_end') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    @foreach(['planned','in_progress','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $order->status ?? 'planned') === $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    @foreach(['low','medium','high'] as $p)
                    <option value="{{ $p }}" {{ old('priority', $order->priority ?? 'medium') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('production.production.orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" :disabled="loading" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 disabled:opacity-50">
                <span x-text="loading ? 'Saving...' : ({{ isset($order) ? 'true' : 'false' }} ? 'Update' : 'Create')"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>function orderForm(){return{loading:false}}</script>
@endpush
@endsection