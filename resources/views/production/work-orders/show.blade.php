@extends('layouts.app')
@section('content')
@php $statusColors = ['pending'=>'bg-gray-100 text-gray-600','in_progress'=>'bg-yellow-100 text-yellow-700','completed'=>'bg-green-100 text-green-700','on_hold'=>'bg-orange-100 text-orange-700']; @endphp
<div class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('factory.work-orders.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Work Order Details</h1>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">WO #:</span> <span class="font-medium">{{ $workOrder->work_order_number }}</span></div>
            <div><span class="text-gray-500">Production Order:</span> <span class="font-medium">{{ $workOrder->productionOrder?->order_number ?? $workOrder->production_order_id ?? '-' }}</span></div>
            <div><span class="text-gray-500">Machine:</span> <span class="font-medium">{{ $workOrder->machine?->name ?? '-' }}</span></div>
            <div><span class="text-gray-500">Worker:</span> <span class="font-medium">{{ $workOrder->worker?->name ?? '-' }}</span></div>
            <div><span class="text-gray-500">Operation:</span> <span class="font-medium">{{ $workOrder->operation ?? '-' }}</span></div>
            <div><span class="text-gray-500">Qty to Produce:</span> <span class="font-medium">{{ $workOrder->quantity_to_produce ?? '-' }}</span></div>
            <div><span class="text-gray-500">Qty Produced:</span> <span class="font-medium">{{ $workOrder->quantity_produced ?? '-' }}</span></div>
            <div><span class="text-gray-500">Status:</span>
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$workOrder->status] ?? 'bg-gray-100' }}">{{ ucwords(str_replace('_',' ',$workOrder->status ?? 'pending')) }}</span>
            </div>
            <div><span class="text-gray-500">Start:</span> <span class="font-medium">{{ $workOrder->start_time ?? '-' }}</span></div>
            <div><span class="text-gray-500">End:</span> <span class="font-medium">{{ $workOrder->end_time ?? '-' }}</span></div>
        </div>
        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('factory.work-orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Back</a>
            <a href="{{ route('factory.work-orders.edit', $workOrder) }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Edit</a>
        </div>
    </div>
</div>
@endsection