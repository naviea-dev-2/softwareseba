@extends('inc.master')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 8.35V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8.35A2 2 0 0 1 3.26 6.5l8-3.2a2 2 0 0 1 1.48 0l8 3.2A2 2 0 0 1 22 8.35Z"/><path d="M6 18h12"/><path d="M6 14h12"/><path d="M6 10h12"/></svg>
                Material Stock
            </h1>
        </div>
        <a href="{{ route('production.material-stock.create') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Add Stock
        </a>
    </div>

    <form method="GET" class="relative mb-4">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search stock..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
    </form>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Material</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Batch</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Quantity</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Reserved</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Location</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Expiry</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $item->rawMaterial?->name ?? $item->raw_material_id }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->batch_number ?? '-' }}</td>
                    <td class="py-3 px-4 text-right">{{ $item->quantity ?? '-' }}</td>
                    <td class="py-3 px-4 text-right">{{ $item->reserved_quantity ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->location ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->expiry_date ?? '-' }}</td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('production.material-stock.edit', $item) }}" class="p-1.5 hover:bg-gray-200 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('production.material-stock.destroy', $item) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 hover:bg-red-100 rounded">
                                    <svg class="text-red-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-400">No stock records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stocks->hasPages())<div class="mt-4">{{ $items->links() }}</div>@endif
</div>
@endsection