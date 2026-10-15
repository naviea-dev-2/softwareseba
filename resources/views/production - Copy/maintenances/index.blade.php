@extends('inc.master')

@section('content')
@php
$typeColors = ['preventive' => 'bg-blue-100 text-blue-700','corrective' => 'bg-orange-100 text-orange-700','predictive' => 'bg-purple-100 text-purple-700','emergency' => 'bg-red-100 text-red-700'];
$statusColors = ['scheduled' => 'bg-gray-100 text-gray-600','in_progress' => 'bg-yellow-100 text-yellow-700','completed' => 'bg-green-100 text-green-700','cancelled' => 'bg-red-100 text-red-700'];
@endphp
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            Machine Maintenance
        </h1>
        <a href="{{ route('production.maintenances.create') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Add Maintenance
        </a>
    </div>

    <form method="GET" class="relative mb-4">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search maintenance records..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
    </form>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Machine</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Type</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Scheduled</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Completed</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Cost</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Downtime (hrs)</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-700">Status</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $item->machine?->name ?? $item->machine_id }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $typeColors[$item->maintenance_type] ?? 'bg-gray-100' }}">{{ $item->maintenance_type }}</span>
                    </td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->scheduled_date ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $item->completed_date ?? '-' }}</td>
                    <td class="py-3 px-4 text-right">{{ $item->cost ? '$'.$item->cost : '-' }}</td>
                    <td class="py-3 px-4 text-right">{{ $item->downtime_hours ?? '-' }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$item->status] ?? 'bg-gray-100' }}">{{ str_replace('_', ' ', $item->status ?? '') }}</span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('production.maintenances.edit', $item) }}" class="p-1.5 hover:bg-gray-200 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('production.maintenances.destroy', $item) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 hover:bg-red-100 rounded">
                                    <svg class="text-red-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-8 text-gray-400">No maintenance records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())<div class="mt-4">{{ $items->links() }}</div>@endif
</div>
@endsection