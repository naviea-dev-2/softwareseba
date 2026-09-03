{{-- resources/views/factory/workers/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('factory.workers.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Worker Details</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $worker->name }}</span></div>
            <div><span class="text-gray-500">Employee Code:</span> <span class="font-medium">{{ $worker->employee_code ?? '-' }}</span></div>
            <div><span class="text-gray-500">Department:</span> <span class="font-medium">{{ $worker->department ?? '-' }}</span></div>
            <div><span class="text-gray-500">Designation:</span> <span class="font-medium">{{ $worker->designation ?? '-' }}</span></div>
            <div><span class="text-gray-500">Shift:</span> <span class="font-medium">{{ $worker->shift ?? '-' }}</span></div>
            <div><span class="text-gray-500">Status:</span>
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $worker->status === 'active' ? 'bg-green-100 text-green-700' : ($worker->status === 'on_leave' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ ucwords(str_replace('_',' ',$worker->status ?? 'active')) }}
                </span>
            </div>
            <div><span class="text-gray-500">Contact:</span> <span class="font-medium">{{ $worker->contact_number ?? '-' }}</span></div>
            <div><span class="text-gray-500">Email:</span> <span class="font-medium">{{ $worker->email ?? '-' }}</span></div>
            <div><span class="text-gray-500">Hire Date:</span> <span class="font-medium">{{ $worker->hire_date ?? '-' }}</span></div>
        </div>
        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('factory.workers.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Back</a>
            <a href="{{ route('factory.workers.edit', $worker) }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Edit</a>
        </div>
    </div>
</div>
@endsection