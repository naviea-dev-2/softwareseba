@extends('inc.master')

@section('content')

@php

    $isEdit = isset($worker);

    $title = $isEdit ? 'Edit Worker' : 'New Worker';

    $subtitle = $isEdit
        ? 'Update worker details and information'
        : 'Add a new worker to the system';

    $action = $isEdit
        ? route('production.workers.update', $worker)
        : route('production.workers.store');

    $btnText = $isEdit
        ? 'Update Worker'
        : 'Create Worker';

@endphp


<div class="production-page">

    <div class="container" style="max-width: 1000px;">

        {{-- Header --}}
        <div class="production-form-header">

            <a href="{{ route('production.workers.index') }}"
               class="btn btn-light production-back-btn"
               title="Back">

                <i class="bi bi-arrow-left"></i>

            </a>

            <div>

                <h3 class="production-page-title">
                    {{ $title }}
                </h3>

                <p class="production-page-subtitle">
                    {{ $subtitle }}
                </p>

            </div>

        </div>


        {{-- Error --}}
        @if($errors->has('general') || session('error'))

            <div class="alert alert-danger rounded-4 border-0 d-flex gap-3 mb-4">

                <i class="bi bi-exclamation-circle-fill fs-5"></i>

                <div>

                    <strong>
                        Something went wrong
                    </strong>

                    <div class="small mt-1">
                        {{ $errors->first('general') ?? session('error') }}
                    </div>

                </div>

            </div>

        @endif


        <form method="POST"
              action="{{ $action }}"
              class="production-form">

            @csrf

            @if($isEdit)
                @method('PUT')
            @endif


            <div class="row g-4">

                {{-- Main --}}
                <div class="col-lg-8">

                    <div class="card production-form-card">

                        <div class="card-body">

                            {{-- Basic --}}
                            <div class="production-section-title">
                                Basic Information
                            </div>


                            <div class="row g-3 mb-4">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Full Name
                                        <span class="required">*</span>
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-person production-input-icon"></i>

                                        <input
                                            type="text"
                                            name="name"
                                            value="{{ old('name', $worker->name ?? '') }}"
                                            class="form-control production-has-icon @error('name') is-invalid @enderror"
                                            placeholder="e.g. John Doe"
                                            required>

                                    </div>

                                    @error('name')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Employee Code
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-upc-scan production-input-icon"></i>

                                        <input
                                            type="text"
                                            name="employee_code"
                                            value="{{ old('employee_code', $worker->employee_code ?? '') }}"
                                            class="form-control production-has-icon @error('employee_code') is-invalid @enderror"
                                            placeholder="e.g. EMP-001">

                                    </div>

                                    @error('employee_code')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>


                            <div class="row g-3 mb-4">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Department
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-building production-input-icon"></i>

                                        <input
                                            type="text"
                                            name="department"
                                            value="{{ old('department', $worker->department ?? '') }}"
                                            class="form-control production-has-icon"
                                            placeholder="e.g. Production">

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Designation
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-briefcase production-input-icon"></i>

                                        <input
                                            type="text"
                                            name="designation"
                                            value="{{ old('designation', $worker->designation ?? '') }}"
                                            class="form-control production-has-icon"
                                            placeholder="e.g. Supervisor">

                                    </div>

                                </div>

                            </div>


                            {{-- Work --}}
                            <div class="production-section-title">
                                Work Details
                            </div>


                            <div class="row g-3 mb-4">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Shift
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-clock production-input-icon"></i>

                                        <select
                                            name="shift"
                                            class="form-select production-has-icon">

                                            <option value="">
                                                Select Shift
                                            </option>

                                            @foreach(['morning','evening','night'] as $shift)

                                                <option
                                                    value="{{ $shift }}"
                                                    {{ old('shift', $worker->shift ?? '') === $shift ? 'selected' : '' }}>

                                                    {{ ucfirst($shift) }} Shift

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Status
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-activity production-input-icon"></i>

                                        <select
                                            name="status"
                                            class="form-select production-has-icon">

                                            @foreach(['active','inactive','on_leave'] as $status)

                                                <option
                                                    value="{{ $status }}"
                                                    {{ old('status', $worker->status ?? 'active') === $status ? 'selected' : '' }}>

                                                    {{ ucwords(str_replace('_', ' ', $status)) }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                            </div>


                            {{-- Contact --}}
                            <div class="production-section-title">
                                Contact Information
                            </div>


                            <div class="row g-3 mb-4">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Contact Number
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-telephone production-input-icon"></i>

                                        <input
                                            type="tel"
                                            name="phone"
                                            value="{{ old('phone', $worker->phone ?? '') }}"
                                            class="form-control production-has-icon"
                                            placeholder="+880 1XXXXXXXXX">

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Email Address
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-envelope production-input-icon"></i>

                                        <input
                                            type="email"
                                            name="email"
                                            value="{{ old('email', $worker->email ?? '') }}"
                                            class="form-control production-has-icon"
                                            placeholder="worker@company.com">

                                    </div>

                                    @error('email')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>


                            {{-- Hire Date --}}
                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Hire Date
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-calendar-event production-input-icon"></i>

                                        <input
                                            type="date"
                                            name="hire_date"
                                            value="{{ old('hire_date', $worker->hire_date ?? '') }}"
                                            class="form-control production-has-icon">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Sidebar --}}
                <div class="col-lg-4">

                    <div class="card production-form-card mb-4">

                        <div class="card-body text-center">

                            <div class="production-avatar-lg mx-auto mb-3"
                                 id="workerAvatar">

                                {{ collect(explode(' ', old('name', $worker->name ?? 'New Worker')))
                                    ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                                    ->take(2)
                                    ->join('') }}

                            </div>

                            <h5 class="fw-bold mb-1" id="workerName">

                                {{ $isEdit ? $worker->name : 'New Worker' }}

                            </h5>

                            <p class="text-muted small mb-0">

                                {{ $isEdit ? ($worker->designation ?? 'No designation') : 'Draft' }}

                            </p>


                            @if($isEdit)

                                <div class="mt-3 pt-3 border-top">

                                    <div class="d-flex justify-content-between small mb-2">

                                        <span class="text-muted">
                                            Employee Code
                                        </span>

                                        <span class="fw-semibold font-monospace">
                                            #{{ $worker->employee_code ?? 'N/A' }}
                                        </span>

                                    </div>


                                    <div class="d-flex justify-content-between small mb-2">

                                        <span class="text-muted">
                                            Department
                                        </span>

                                        <span class="fw-semibold">
                                            {{ $worker->department ?? '-' }}
                                        </span>

                                    </div>


                                    <div class="d-flex justify-content-between small">

                                        <span class="text-muted">
                                            Status
                                        </span>

                                        <span class="fw-semibold">
                                            {{ ucwords(str_replace('_', ' ', $worker->status)) }}
                                        </span>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="card production-form-card">

                        <div class="card-body">

                            <div class="d-grid gap-2">

                                <button type="submit"
                                        class="btn btn-dark rounded-3 py-2">

                                    <i class="bi bi-check-lg me-2"></i>

                                    {{ $btnText }}

                                </button>


                                <a href="{{ route('production.workers.index') }}"
                                   class="btn btn-outline-secondary rounded-3">

                                    <i class="bi bi-x-lg me-2"></i>

                                    Cancel

                                </a>

                            </div>


                            @if($isEdit)

                                <hr>

                                <div class="text-center">

                                    <small class="text-muted">

                                        Last updated
                                        {{ $worker->updated_at?->diffForHumans() ?? 'recently' }}

                                    </small>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


@push('scripts')

<script>

document.querySelector('input[name="name"]')?.addEventListener('input', function () {

    const name = this.value || 'New Worker';

    const initials = name
        .trim()
        .split(/\s+/)
        .map(name => name.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2);

    document.getElementById('workerAvatar').textContent = initials;

    document.getElementById('workerName').textContent =
        this.value || 'New Worker';

});

</script>

@endpush

@endsection