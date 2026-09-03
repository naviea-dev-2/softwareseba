{{-- resources/views/factory/raw-materials/show.blade.php --}}
@extends('layouts.app')

@section('content')
@php $unitLabels = ['kg'=>'Kilogram','g'=>'Gram','ton'=>'Ton','litre'=>'Litre','piece'=>'Piece','meter'=>'Meter','roll'=>'Roll','box'=>'Box']; @endphp
<div class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('factory.raw-materials.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Material Details</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $rawMaterial->name }}</span></div>
            <div><span class="text-gray-500">SKU:</span> <span class="font-medium">{{ $rawMaterial->sku ?? '-' }}</span></div>
            <div><span class="text-gray-500">Unit:</span> <span class="font-medium">{{ $unitLabels[$rawMaterial->unit_of_measure] ?? $rawMaterial->unit_of_measure }}</span></div>
            <div><span class="text-gray-500">Cost/Unit:</span> <span class="font-medium">{{ $rawMaterial->cost_per_unit ? '$'.$rawMaterial->cost_per_unit : '-' }}</span></div>
            <div><span class="text-gray-500">Supplier:</span> <span class="font-medium">{{ $rawMaterial->supplier?->name ?? '-' }}</span></div>
            <div><span class="text-gray-500">Status:</span>
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $rawMaterial->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $rawMaterial->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('factory.raw-materials.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Back</a>
            <a href="{{ route('factory.raw-materials.edit', $rawMaterial) }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Edit</a>
        </div>
    </div>
</div>
@endsection