@extends('inc.master')

@section('content')
<div class="production-page">
    <div class="container" style="max-width:1000px;">
        <div class="production-form-header">
            <a href="{{ route('production.orders.index') }}" class="btn btn-light production-back-btn" title="Back to orders">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h3 class="production-page-title">New Production Order</h3>
                <p class="production-page-subtitle">Create an order to schedule wheat processing at a mill</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-4 mb-4" role="alert">
                <strong><i class="bi bi-exclamation-circle me-1"></i> Please correct the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('production.orders.store') }}" class="production-form">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card production-form-card">
                        <div class="card-body">
                            <div class="production-section-title">Order Information</div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="order_number" class="form-label">Order Number <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <i class="bi bi-hash production-input-icon"></i>
                                        <input id="order_number" name="order_number" type="text" value="{{ old('order_number') }}"
                                               class="form-control production-has-icon @error('order_number') is-invalid @enderror"
                                               placeholder="e.g. ORD-2026-001" required>
                                    </div>
                                    @error('order_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <i class="bi bi-box-seam production-input-icon"></i>
                                        <select id="product_id" name="product_id" class="form-select production-has-icon @error('product_id') is-invalid @enderror" required>
                                            <option value="">Select product</option>
                                            @foreach(($products ?? []) as $product)
                                                <option value="{{ $product->id }}" @selected((string) old('product_id') === (string) $product->id)>
                                                    {{ $product->product_name ?? ('Product #'.$product->id) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('product_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    @if(empty($products) || count($products) === 0)
                                        <div class="form-text text-warning">The controller must provide a $products collection for this dropdown.</div>
                                    @endif
                                </div>
                            </div>

                            <div class="production-section-title">Production Schedule</div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="quantity_ordered" class="form-label">Quantity Ordered <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <i class="bi bi-boxes production-input-icon"></i>
                                        <input id="quantity_ordered" name="quantity_ordered" type="number" min="0.01" step="0.01"
                                               value="{{ old('quantity_ordered') }}"
                                               class="form-control production-has-icon @error('quantity_ordered') is-invalid @enderror"
                                               placeholder="e.g. 40.00" required>
                                    </div>
                                    @error('quantity_ordered')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    <div class="form-text">Use the measurement unit configured for the product.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="planned_start_date" class="form-label">Planned Start Date <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <i class="bi bi-calendar-event production-input-icon"></i>
                                        <input id="planned_start_date" name="planned_start_date" type="date"
                                               value="{{ old('planned_start_date') }}"
                                               class="form-control production-has-icon @error('planned_start_date') is-invalid @enderror" required>
                                    </div>
                                    @error('planned_start_date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="production-section-title">Order Status</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <i class="bi bi-flag production-input-icon"></i>
                                        <select id="status" name="status" class="form-select production-has-icon @error('status') is-invalid @enderror" required>
                                            @foreach(($orderStatuses ?? ['draft' => 'Draft', 'pending' => 'Pending', 'approved' => 'Approved', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled']) as $value => $label)
                                                <option value="{{ $value }}" @selected(old('status', 'draft') == $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    <div class="form-text">Select a status supported by your backend validation.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card production-form-card">
                        <div class="card-body text-center">
                            <div class="production-avatar-lg mx-auto mb-3"><i class="bi bi-gear-wide-connected"></i></div>
                            <h5 class="fw-semibold mb-2">Production Order</h5>
                            <p class="text-muted small mb-4">Review the product, quantity and processing date before saving.</p>
                            <button type="submit" class="btn btn-dark rounded-3 w-100 mb-2">
                                <i class="bi bi-check-lg me-1"></i> Save Production Order
                            </button>
                            <a href="{{ route('production.orders.index') }}" class="btn btn-outline-secondary rounded-3 w-100">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
