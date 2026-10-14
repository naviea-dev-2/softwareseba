@extends('inc.master')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l4 2"/></svg>
            Production History
        </h1>
    </div>

    <form method="GET" class="relative mb-4">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search history..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
    </form>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Production Order</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Work Order</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Action</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Quantity</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-500">{{ $item->created_at?->format('Y-m-d H:i') ?? '-' }}</td>
                    <td class="py-3 px-4 font-medium">{{ $item->productionOrder?->order_number ?? $item->production_order_id ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->workOrder?->work_order_number ?? $item->work_order_id ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $item->action ?? '-' }}</td>
                    <td class="py-3 px-4 text-right">{{ $item->quantity ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-8 text-gray-400">No history records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())<div class="mt-4">{{ $items->links() }}</div>@endif
</div>
@endsection