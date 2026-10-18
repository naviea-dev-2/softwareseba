@extends('inc.master')

@section('content')
@php
    $isEdit = isset($stock);
    $title = $isEdit ? 'Edit Material Stock' : 'New Material Stock';
    $subtitle = $isEdit ? 'Update stock quantities and storage information' : 'Add a new raw material stock record';
    $action = $isEdit
        ? route('production.material-stock.update', $stock)
        : route('production.material-stock.store');
    $btnText = $isEdit ? 'Update Stock' : 'Create Stock';
    $selectedMaterialId = (string) old('raw_material_id', $stock->raw_material_id ?? '');
    $materialOptions = $rawMaterials ?? $materials ?? collect();
@endphp

<div class="production-page">
    <div class="container" style="max-width: 1000px;">
        {{-- Header --}}
        <div class="production-form-header">
            <a href="{{ route('production.material-stock.index') }}"
               class="btn btn-light production-back-btn" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h3 class="production-page-title">{{ $title }}</h3>
                <p class="production-page-subtitle">{{ $subtitle }}</p>
            </div>
        </div>

        @if($errors->has('general') || session('error'))
            <div class="alert alert-danger rounded-4 border-0 d-flex gap-3 mb-4">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <div>
                    <strong>Something went wrong</strong>
                    <div class="small mt-1">{{ $errors->first('general') ?: session('error') }}</div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ $action }}" class="production-form">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card production-form-card">
                        <div class="card-body">
                            <div class="production-section-title">Material Information</div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="raw_material_id" class="form-label">
                                        Raw Material <span class="required">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <i class="bi bi-box-seam production-input-icon"></i>
                                        <select id="raw_material_id" name="raw_material_id"
                                                class="form-select production-has-icon @error('raw_material_id') is-invalid @enderror"
                                                required>
                                            <option value="">Select Raw Material</option>
                                            @foreach($materialOptions as $material)
                                                <option value="{{ $material->id }}"
                                                    @selected($selectedMaterialId === (string) $material->id)>
                                                    {{ $material->name }}{{ filled($material->sku) ? ' ('.$material->sku.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('raw_material_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    @if(collect($materialOptions)->isEmpty())
                                        <div class="text-warning small mt-1">No raw materials available. Add a raw material first.</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="batch_number" class="form-label">Batch Number</label>
                                    <div class="position-relative">
                                        <i class="bi bi-upc-scan production-input-icon"></i>
                                        <input id="batch_number" type="text" name="batch_number"
                                               value="{{ old('batch_number', $stock->batch_number ?? '') }}"
                                               class="form-control production-has-icon @error('batch_number') is-invalid @enderror"
                                               placeholder="e.g. BATCH-001">
                                    </div>
                                    @error('batch_number')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="production-section-title">Stock Quantity</div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Quantity <span class="required">*</span></label>
                                    <div class="position-relative">
                                        <i class="bi bi-boxes production-input-icon"></i>
                                        <input id="quantity" type="number" name="quantity" min="0" step="0.01"
                                               value="{{ old('quantity', $stock->quantity ?? '') }}"
                                               class="form-control production-has-icon @error('quantity') is-invalid @enderror"
                                               placeholder="e.g. 100.00" required>
                                    </div>
                                    @error('quantity')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="reserved_quantity" class="form-label">Reserved Quantity</label>
                                    <div class="position-relative">
                                        <i class="bi bi-lock production-input-icon"></i>
                                        <input id="reserved_quantity" type="number" name="reserved_quantity" min="0" step="0.01"
                                               value="{{ old('reserved_quantity', $stock->reserved_quantity ?? '0') }}"
                                               class="form-control production-has-icon @error('reserved_quantity') is-invalid @enderror"
                                               placeholder="e.g. 10.00">
                                    </div>
                                    @error('reserved_quantity')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Reserved quantity must not exceed total quantity.</div>
                                </div>
                            </div>

                            <div class="production-section-title">Storage Details</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="location" class="form-label">Location</label>
                                    <div class="position-relative">
                                        <i class="bi bi-geo-alt production-input-icon"></i>
                                        <input id="location" type="text" name="location"
                                               value="{{ old('location', $stock->location ?? '') }}"
                                               class="form-control production-has-icon @error('location') is-invalid @enderror"
                                               placeholder="e.g. Warehouse A">
                                    </div>
                                    @error('location')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <div class="position-relative">
                                        <i class="bi bi-calendar-event production-input-icon"></i>
                                        <input id="expiry_date" type="date" name="expiry_date"
                                               value="{{ old('expiry_date', isset($stock) && $stock->expiry_date ? \Carbon\Carbon::parse($stock->expiry_date)->format('Y-m-d') : '') }}"
                                               class="form-control production-has-icon @error('expiry_date') is-invalid @enderror">
                                    </div>
                                    @error('expiry_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
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
                            <h5 class="fw-bold mb-1">{{ $title }}</h5>
                            <p class="text-muted small mb-0">
                                {{ $isEdit ? 'Existing inventory record' : 'New inventory entry' }}
                            </p>
                            @if($isEdit)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between small mb-2">
                                        <span class="text-muted">Batch</span>
                                        <span class="fw-semibold">{{ $stock->batch_number ?: '-' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between small mb-2">
                                        <span class="text-muted">Quantity</span>
                                        <span class="fw-semibold">{{ number_format((float) $stock->quantity, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Available</span>
                                        <span class="fw-semibold text-success">
                                            {{ number_format((float) $stock->quantity - (float) $stock->reserved_quantity, 2) }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card production-form-card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-dark rounded-3 py-2">
                                    <i class="bi bi-check-lg me-2"></i>{{ $btnText }}
                                </button>
                                <a href="{{ route('production.material-stock.index') }}"
                                   class="btn btn-outline-secondary rounded-3">
                                    <i class="bi bi-x-lg me-2"></i>Cancel
                                </a>
                            </div>
                            @if($isEdit)
                                <hr>
                                <div class="text-center">
                                    <small class="text-muted">
                                        Last updated {{ $stock->updated_at?->diffForHumans() ?? 'recently' }}
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
@endsection
