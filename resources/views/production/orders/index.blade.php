@extends('inc.master')

@section('content')
@php
$statusColors = ['planned' => 'bg-gray-100 text-gray-600','in_progress' => 'bg-yellow-100 text-yellow-700','completed' => 'bg-green-100 text-green-700','cancelled' => 'bg-red-100 text-red-700'];
$priorityColors = ['low' => 'bg-blue-50 text-blue-600','medium' => 'bg-orange-50 text-orange-600','high' => 'bg-red-50 text-red-600'];
@endphp
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" x2="12" y1="22.08" y2="12"/></svg>
            Production Orders
        </h1>
        <a href="{{ route('production.production-orders.create') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Add Order
        </a>
    </div>

    <form method="GET" class="relative mb-4">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
    </form>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Order #</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Product</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">BOM</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Quantity</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Planned Start</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Planned End</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-700">Priority</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-700">Status</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $item->order_number }}</td>
                    <td class="py-3 px-4">{{ $item->product?->name ?? $item->product_id ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->bom?->name ?? $item->bom_id ?? '-' }}</td>
                    <td class="py-3 px-4 text-right">{{ $item->quantity ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->planned_start ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->planned_end ?? '-' }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $priorityColors[$item->priority] ?? 'bg-gray-100' }}">{{ ucfirst($item->priority ?? 'medium') }}</span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$item->status] ?? 'bg-gray-100' }}">{{ ucwords(str_replace('_',' ',$item->status ?? 'planned')) }}</span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('production.production-orders.edit', $item) }}" class="p-1.5 hover:bg-gray-200 rounded"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg></a>
                            <form method="POST" action="{{ route('production.production-orders.destroy', $item) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 hover:bg-red-100 rounded"><svg class="text-red-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-8 text-gray-400">No production orders found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())<div class="mt-4">{{ $orders->links() }}</div>@endif
</div>
@endsection