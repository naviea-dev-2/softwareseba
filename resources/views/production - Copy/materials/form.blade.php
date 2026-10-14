@extends('inc.master')

@section('content')

@php

    $isEdit = isset($material);

    $title = $isEdit
        ? 'Edit Material'
        : 'New Material';

    $subtitle = $isEdit
        ? 'Update raw material details and information'
        : 'Add a new raw material to the system';

    $action = $isEdit
        ? route('production.materials.update', $material)
        : route('production.materials.store');

    $units = [
        'kg',
        'g',
        'ton',
        'litre',
        'piece',
        'meter',
        'roll',
        'box',
    ];

@endphp


<div class="production-page">

    <div class="container" style="max-width: 1000px;">

        {{-- Header --}}
        <div class="production-form-header">

            <a href="{{ route('production.materials.index') }}"
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


        <form
            method="POST"
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

                                        Material Name

                                        <span class="required">*</span>

                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-box-seam production-input-icon"></i>

                                        <input
                                            type="text"
                                            name="name"
                                            value="{{ old('name', $material->name ?? '') }}"
                                            class="form-control production-has-icon @error('name') is-invalid @enderror"
                                            placeholder="e.g. Cotton Fabric"
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
                                        SKU
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-upc-scan production-input-icon"></i>

                                        <input
                                            type="text"
                                            name="sku"
                                            value="{{ old('sku', $material->sku ?? '') }}"
                                            class="form-control production-has-icon"
                                            placeholder="e.g. RM-001">

                                    </div>

                                    @error('sku')

                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>


                            {{-- Description --}}
                            <div class="mb-4">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Enter material description...">{{ old('description', $material->description ?? '') }}</textarea>

                            </div>


                            {{-- Material Details --}}
                            <div class="production-section-title">
                                Material Details
                            </div>


                            <div class="row g-3 mb-4">

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Unit of Measure

                                        <span class="required">*</span>

                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-rulers production-input-icon"></i>

                                        <select
                                            name="unit_of_measure"
                                            class="form-select production-has-icon"
                                            required>

                                            @foreach($units as $unit)

                                                <option
                                                    value="{{ $unit }}"
                                                    {{ old('unit_of_measure', $material->unit_of_measure ?? 'kg') === $unit ? 'selected' : '' }}>

                                                    {{ ucfirst($unit) }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Cost per Unit
                                    </label>

                                    <div class="position-relative">

                                        <i class="bi bi-currency-bdt production-input-icon"></i>

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            name="cost_per_unit"
                                            value="{{ old('cost_per_unit', $material->cost_per_unit ?? '') }}"
                                            class="form-control production-has-icon"
                                            placeholder="0.00">

                                    </div>

                                    @error('cost_per_unit')

                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>


                            {{-- Supplier --}}
                            <div class="production-section-title">
                                Supplier
                            </div>


                            <div class="mb-4">

                                <label class="form-label">
                                    Supplier
                                </label>

                                <div class="position-relative">

                                    <i class="bi bi-truck production-input-icon"></i>

                                    <select
                                        name="supplier_id"
                                        class="form-select production-has-icon">

                                        <option value="">
                                            Select Supplier
                                        </option>

                                        @foreach($suppliers ?? [] as $supplier)

                                            <option
                                                value="{{ $supplier->id }}"
                                                {{ (string) old('supplier_id', $material->supplier_id ?? '') === (string) $supplier->id ? 'selected' : '' }}>

                                                {{ $supplier->name }}

                                                @if($supplier->email)
                                                    — {{ $supplier->email }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                @error('supplier_id')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Status --}}
                            <div class="production-section-title">
                                Status
                            </div>


                            <div class="form-check form-switch">

                                <input
                                    type="hidden"
                                    name="is_active"
                                    value="0">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', $material->is_active ?? 1) ? 'checked' : '' }}>

                                <label
                                    class="form-check-label fw-semibold"
                                    for="is_active">

                                    Active Material

                                </label>

                            </div>


                            {{-- Actions --}}
                            <div class="d-flex justify-content-end gap-2 pt-4 mt-4 border-top">

                                <a
                                    href="{{ route('production.materials.index') }}"
                                    class="btn btn-outline-secondary rounded-3">

                                    <i class="bi bi-x-lg me-1"></i>

                                    Cancel

                                </a>


                                <button
                                    type="submit"
                                    class="btn btn-dark rounded-3 px-4">

                                    <i class="bi bi-check-lg me-1"></i>

                                    {{ $isEdit ? 'Update Material' : 'Create Material' }}

                                </button>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Sidebar --}}
                <div class="col-lg-4">

                    <div class="card production-form-card mb-4">

                        <div class="card-body text-center">

                            <div class="production-avatar-lg mx-auto mb-3">

                                <i class="bi bi-box-seam"></i>

                            </div>

                            <h5 class="fw-bold mb-1">

                                {{ $isEdit ? $material->name : 'New Material' }}

                            </h5>

                            <p class="text-muted small mb-0">

                                {{ $isEdit ? ($material->unit_of_measure ?? 'Material') : 'Draft Material' }}

                            </p>


                            @if($isEdit)

                                <div class="mt-3 pt-3 border-top">

                                    <div class="d-flex justify-content-between small mb-2">

                                        <span class="text-muted">
                                            SKU
                                        </span>

                                        <span class="fw-semibold font-monospace">

                                            #{{ $material->sku ?? 'N/A' }}

                                        </span>

                                    </div>


                                    <div class="d-flex justify-content-between small mb-2">

                                        <span class="text-muted">
                                            Unit
                                        </span>

                                        <span class="fw-semibold">

                                            {{ ucfirst($material->unit_of_measure) }}

                                        </span>

                                    </div>


                                    <div class="d-flex justify-content-between small">

                                        <span class="text-muted">
                                            Status
                                        </span>

                                        <span class="fw-semibold
                                            {{ $material->is_active ? 'text-success' : 'text-danger' }}">

                                            {{ $material->is_active ? 'Active' : 'Inactive' }}

                                        </span>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- Info --}}
                    <div class="card production-form-card">

                        <div class="card-body">

                            <h6 class="fw-bold mb-3">

                                <i class="bi bi-info-circle me-1"></i>

                                Material Information

                            </h6>

                            <p class="text-muted small mb-0">

                                Keep the material name, SKU, unit and supplier
                                information accurate so inventory and purchasing
                                records remain consistent.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection