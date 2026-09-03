@extends('inc.master')

@section('content')
<div class="p-6 max-w-3xl mx-auto" x-data="planForm()">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('production.production.plans.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
            {{ isset($plan) ? 'Edit Production Plan' : 'New Production Plan' }}
        </h1>
    </div>

    @if($errors->has('general') || session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="text-red-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <div class="flex-1"><p class="text-sm font-medium text-red-800">{{ $errors->first('general') ?? session('error') }}</p></div>
    </div>
    @endif

    <form method="POST" action="{{ isset($plan) ? route('production.production.plans.update', $plan) : route('production.production.plans.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5" @submit.prevent="loading=true;$el.submit()">
        @csrf
        @if(isset($plan)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plan Number <span class="text-red-500">*</span></label>
                <input name="plan_number" value="{{ old('plan_number', $plan->plan_number ?? '') }}" required class="w-full px-3 py-2 border {{ $errors->has('plan_number') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('plan_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none bg-white">
                    @foreach(['draft','active','closed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $plan->status ?? 'draft') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input name="start_date" type="date" value="{{ old('start_date', $plan->start_date ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('start_date') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('start_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input name="end_date" type="date" value="{{ old('end_date', $plan->end_date ?? '') }}" class="w-full px-3 py-2 border {{ $errors->has('end_date') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">
                @error('end_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full px-3 py-2 border {{ $errors->has('notes') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-gray-500 outline-none">{{ old('notes', $plan->notes ?? '') }}</textarea>
            @error('notes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('production.production.plans.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" :disabled="loading" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 disabled:opacity-50">
                <span x-text="loading ? 'Saving...' : ({{ isset($plan) ? 'true' : 'false' }} ? 'Update' : 'Create')"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>function planForm(){return{loading:false}}</script>
@endpush
@endsection