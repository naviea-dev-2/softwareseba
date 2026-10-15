
@extends('inc.master')

@section('head')
<title>Create Purchase</title>

<style>
.purchase-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 0 4px 40px;
}
.page-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
}
.page-head .ph-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.page-head .ph-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: linear-gradient(135deg,#2f6fed,#1d4ed8);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 6px 14px rgba(47,111,237,.30);
}
.page-head h1 {
    font-size: 20px;
    font-weight: 700;
    color: #101828;
    margin: 0;
}
.page-head .ph-sub {
    font-size: 12.5px;
    color: #667085;
    margin: 2px 0 0;
}
.btn-back-p {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 14px;
    border: 1px solid #d9dee4;
    border-radius: 8px;
    background: #fff;
    color: #344054;
    font-size: 13px;
    font-weight: 500;
    transition: .15s;
}
.btn-back-p:hover {
    background: #f2f4f7;
    color: #101828;
    text-decoration: none;
}
.p-card {
    background: #fff;
    border: 1px solid #e6e8eb;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(16,24,40,.05);
    margin-bottom: 18px;
    overflow: visible;
}
.p-card-header {
    padding: 14px 20px;
    border-bottom: 1px solid #eef0f3;
    display: flex;
    align-items: center;
    gap: 12px;
}
.p-card-header .icon-box {
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}
.icon-blue { background:#eef4ff;color:#2f6fed; }
.icon-green { background:#e7f6ee;color:#12a05c; }
.icon-purple { background:#f1edfd;color:#7047eb; }
.icon-orange { background:#fdf1e7;color:#ea7a1f; }
.p-card-header h3 {
    font-size: 14px;
    font-weight: 600;
    color: #101828;
    margin: 0;
}
.p-card-header .sub {
    font-size: 11.5px;
    color: #98a2b3;
    margin: 1px 0 0;
}
.p-card-body { padding:18px 20px; }
.form-label-p {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #667085;
    margin-bottom: 6px;
    display: block;
}
.form-label-p .req { color:#e04438; }
.purchase-wrap .form-control {
    height: 38px;
    border: 1px solid #d9dee4;
    border-radius: 8px;
    font-size: 13px;
    padding: .45rem .75rem;
    color: #101828;
    background: #fff;
    transition: border-color .15s,box-shadow .15s;
}
.purchase-wrap .form-control:focus {
    border-color: #2f6fed;
    box-shadow: 0 0 0 3px rgba(47,111,237,.12);
}
.purchase-wrap textarea.form-control { height:auto; }
.purchase-wrap .form-control.is-invalid { border-color:#e04438; }
.purchase-wrap .invalid-feedback { font-size:11.5px; }
.purchase-wrap input[type="file"].form-control {
    padding: 7px .75rem;
    height: auto;
}
.input-icon { position:relative; }
.input-icon > i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #98a2b3;
    font-size: 14px;
    pointer-events: none;
}
.input-icon > .form-control { padding-left:34px; }
.auto-search,
.auto-search-product {
    width: 100%;
    display: block;
    position: relative;
}
.auto-search-container {
    position: absolute;
    background: #fff;
    z-index: 9999;
    width: 100%;
    border: 1px solid #e3e6ea;
    border-radius: 10px;
    box-shadow: 0 12px 28px rgba(16,24,40,.14);
    overflow: hidden;
    margin-top: 4px;
}
.auto-search-container ul { padding:6px;margin:0; }
.auto-search-container ul li {
    list-style: none;
    cursor: pointer;
    padding: 8px 10px;
    border-radius: 7px;
    font-size: 13px;
    color: #344054;
}
.auto-search-container ul li + li { margin-top:2px; }
.auto-search-container ul li:hover { background:#f2f4f7; }
.auto-search-container .no-result {
    color: #98a2b3;
    cursor: default;
}
.picker-row {
    background: #f8f9fb;
    border: 1px dashed #d9dee4;
    border-radius: 12px;
    padding: 14px 16px;
}
.picker-row .form-label-p { margin-bottom:5px; }
.barcode-addon {
    background: #101828;
    color: #fff;
    border: none;
    border-radius: 8px 0 0 8px;
    width: 42px;
}
.barcode-addon:hover { background:#000;color:#fff; }
.table-card .p-card-body { padding:0; }
.table-scroll { width:100%;overflow-x:auto; }
table.order-list {
    width: 100%;
    min-width: 1050px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}
table.order-list thead th {
    background: #101828;
    color: #e4e7ec;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 11px 10px;
    border: none;
    white-space: nowrap;
    text-align: center;
}
table.order-list tbody td {
    padding: 8px;
    vertical-align: middle;
    border-bottom: 1px solid #eef0f3;
    background: #fff;
    font-size: 13px;
}
table.order-list tbody tr:nth-child(even) td {
    background: #fafbfc;
}
table.order-list tbody tr:hover td { background:#f5f8ff; }
table.order-list tbody .form-control {
    height: 34px;
    font-size: 12.5px;
    padding: .35rem .6rem;
    border-radius: 7px;
}
table.order-list tfoot th {
    background: #f2f4f7;
    color: #101828;
    font-size: 12px;
    font-weight: 700;
    padding: 11px 10px;
    border-top: 2px solid #e3e6ea;
    text-align: center;
    white-space: nowrap;
}
.delete_item {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    border: 1px solid #f3d2cf;
    background: #fef3f2;
    color: #d92d20;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: .15s;
}
.delete_item:hover { background:#d92d20;color:#fff; }
.empty-cart {
    text-align: center;
    padding: 34px 10px;
    color: #98a2b3;
}
.empty-cart i {
    font-size: 30px;
    display: block;
    margin-bottom: 8px;
    color: #d0d5dd;
}
.empty-cart p { margin:0;font-size:13px; }
.bootstrap-touchspin input {
    width: 100% !important;
    text-align: center;
}
.add-ajax-product input { padding:.2rem !important; }
.select_qty { text-align:center; }
.summary-card .sum-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 9px 0;
    font-size: 13px;
    color: #475467;
}
.summary-card .sum-row + .sum-row {
    border-top: 1px dashed #e6e8eb;
}
.summary-card .sum-row .val {
    font-weight: 600;
    color: #101828;
    font-variant-numeric: tabular-nums;
}
.summary-card .form-control { height:36px;font-size:13px; }
.grand-total-box {
    background: linear-gradient(135deg,#101828,#1d2939);
    border-radius: 12px;
    padding: 14px 16px;
    margin-top: 12px;
}
.grand-total-box .gt-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: #98a2b3;
    margin: 0;
}
.grand-total-box .gt-value {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    font-variant-numeric: tabular-nums;
}
#submit-btn {
    width: 100%;
    height: 44px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    background: linear-gradient(135deg,#2f6fed,#1d4ed8);
    border: none;
    box-shadow: 0 6px 14px rgba(47,111,237,.30);
}
#submit-btn:hover { filter:brightness(1.06); }
.select2-container { max-width:100%; }
.select2-container .select2-selection--single {
    height: 38px !important;
    border: 1px solid #d9dee4 !important;
    border-radius: 8px !important;
}
.select2-container--default .select2-selection--single
.select2-selection__rendered {
    line-height: 36px !important;
    font-size: 13px;
    color: #101828;
}
.select2-container--default .select2-selection--single
.select2-selection__arrow {
    height: 36px !important;
}
.toast-message { color:#fff; }
@media(max-width:991px) {
    .purchase-wrap .col-sidebar { margin-top:4px; }
}
@media(max-width:767px) {
    .p-card-body { padding:14px; }
    .page-head h1 { font-size:18px; }
}

/* ==========================================
   PURCHASE QUANTITY - TOUCHSPIN UI FIX
========================================== */

.purchase-wrap .add-ajax-product .bootstrap-touchspin {
    display: flex !important;
    flex-direction: row !important;
    align-items: stretch !important;
    flex-wrap: nowrap !important;
    width: 140px !important;
    min-width: 140px !important;
    max-width: 140px !important;
    height: 38px !important;
    margin: 0 auto !important;
    border: 1px solid #d9dee4;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}

/* Minus and plus button wrappers */
.purchase-wrap .add-ajax-product
.bootstrap-touchspin .input-group-btn {
    display: flex !important;
    flex: 0 0 36px !important;
    width: 36px !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Buttons */
.purchase-wrap .add-ajax-product
.bootstrap-touchspin .bootstrap-touchspin-down,

.purchase-wrap .add-ajax-product
.bootstrap-touchspin .bootstrap-touchspin-up {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 36px !important;
    height: 36px !important;
    min-width: 36px !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    border-radius: 0 !important;
    background: #eef4ff !important;
    color: #2563eb !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    box-shadow: none !important;
}

.purchase-wrap .add-ajax-product
.bootstrap-touchspin .bootstrap-touchspin-down:hover,

.purchase-wrap .add-ajax-product
.bootstrap-touchspin .bootstrap-touchspin-up:hover {
    background: #2563eb !important;
    color: #fff !important;
}

/* Quantity input */
.purchase-wrap .add-ajax-product
.bootstrap-touchspin input.select_qty {
    display: block !important;
    flex: 1 1 0 !important;
    width: 0 !important;
    min-width: 0 !important;
    height: 36px !important;
    margin: 0 !important;
    padding: 0 3px !important;
    border: none !important;
    border-left: 1px solid #d9dee4 !important;
    border-right: 1px solid #d9dee4 !important;
    border-radius: 0 !important;
    text-align: center !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    background: #fff !important;
    box-shadow: none !important;
}

/* Fix Bootstrap TouchSpin vertical button layout */
.purchase-wrap .add-ajax-product
.bootstrap-touchspin .input-group-btn-vertical {
    position: static !important;
    display: flex !important;
    flex-direction: row !important;
    width: 36px !important;
    height: 36px !important;
}

.purchase-wrap .add-ajax-product
.bootstrap-touchspin .input-group-btn-vertical > .btn {
    position: static !important;
    width: 36px !important;
    height: 36px !important;
    margin: 0 !important;
}

/* Remove number input browser arrows */
.purchase-wrap .select_qty::-webkit-inner-spin-button,
.purchase-wrap .select_qty::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.purchase-wrap .select_qty {
    -moz-appearance: textfield;
}
.purchase-wrap .add-ajax-product .select_tax_rate {
    min-width: 55px;
    width: 65px;
    height: 36px;
    text-align: center;
    font-size: 13px;
    border-radius: 6px 0 0 6px;
}

.purchase-wrap .add-ajax-product .input-group-text {
    height: 36px;
    padding: 0 8px;
    background: #eef4ff;
    color: #2563eb;
    font-weight: 600;
}
/* Compact horizontal tax field */
.purchase-wrap .purchase-tax-cell {
    min-width: 130px;
    vertical-align: middle;
}

.purchase-wrap .purchase-tax-control {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    flex-wrap: nowrap !important;
    width: 100% !important;
    min-width: 125px;
    height: 36px;
    gap: 0 !important;
    border: 1px solid #d9dee4;
    border-radius: 7px;
    background: #fff;
    overflow: hidden;
}

/* Currency / percentage dropdown */
.purchase-wrap .purchase-tax-control .select_tax_type {
    flex: 0 0 49px !important;
    width: 49px !important;
    min-width: 49px !important;
    height: 34px !important;
    margin: 0 !important;
    padding: 0 3px !important;
    border: none !important;
    border-right: 1px solid #d9dee4 !important;
    border-radius: 0 !important;
    background: #f1f5f9 !important;
    color: #334155;
    font-size: 13px;
    text-align: center;
    box-shadow: none !important;
}

/* Tax value input */
.purchase-wrap .purchase-tax-control .select_tax_rate {
    flex: 1 1 0 !important;
    width: 0 !important;
    min-width: 0 !important;
    height: 34px !important;
    margin: 0 !important;
    padding: 0 5px !important;
    border: none !important;
    border-radius: 0 !important;
    background: #fff !important;
    font-size: 12px;
    text-align: center;
    box-shadow: none !important;
}

/* Remove number spinner arrows */
.purchase-wrap .purchase-tax-control
.select_tax_rate::-webkit-inner-spin-button,
.purchase-wrap .purchase-tax-control
.select_tax_rate::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.purchase-wrap .purchase-tax-control
.select_tax_rate {
    appearance: textfield;
    -moz-appearance: textfield;
}

/* Focus state */
.purchase-wrap .purchase-tax-control:focus-within {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, .12);
}
</style>
@endsection

@section('content')
<div class="purchase-wrap">

    <div class="page-head">
        <div class="ph-left">
            <div class="ph-icon">
                <i class="bx bx-cart-download"></i>
            </div>
            <div>
                <h1>Add New Purchase</h1>
                <p class="ph-sub">Create a purchase order for your supplier</p>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="btn-back-p">
            <i class="bx bx-arrow-back"></i> Back
        </a>
    </div>

    <form id="purchase-form"
          action="{{ route('purchase.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xl-9 col-lg-8">

                {{-- Supplier / Billing --}}
                <div class="p-card">
                    <div class="p-card-header">
                        <div class="icon-box icon-blue">
                            <i class="bx bx-buildings"></i>
                        </div>
                        <div>
                            <h3>Supplier / Billing Information</h3>
                            <p class="sub">
                                Search an existing vendor or enter a new one
                            </p>
                        </div>
                    </div>

                    <div class="p-card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label-p">
                                    Company / Supplier Name
                                    <span class="req">*</span>
                                </label>
                                <div class="input-icon">
                                    <i class="bx bx-user"></i>
                                    <input type="text"
                                           name="name"
                                           class="form-control vendor-auto-search @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}"
                                           placeholder="Supplier name"
                                           autocomplete="off"
                                           required>
                                </div>
                                @error('name')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <label class="form-label-p">
                                    Company / Supplier Email
                                </label>
                                <div class="input-icon">
                                    <i class="bx bx-envelope"></i>
                                    <input type="email"
                                           name="email"
                                           class="form-control vendor-auto-search @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}"
                                           placeholder="email@company.com"
                                           autocomplete="off">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <label class="form-label-p">
                                    Company / Supplier Mobile
                                </label>
                                <div class="input-icon">
                                    <i class="bx bx-phone"></i>
                                    <input type="tel"
                                           name="mobile"
                                           class="form-control vendor-auto-search @error('mobile') is-invalid @enderror"
                                           value="{{ old('mobile') }}"
                                           placeholder="01xxxxxxxxx"
                                           autocomplete="off">
                                </div>
                                @error('mobile')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <label class="form-label-p">
                                    Company / Supplier Address
                                </label>
                                <div class="input-icon">
                                    <i class="bx bx-map"></i>
                                    <input type="text"
                                           name="address"
                                           class="form-control vendor-auto-search @error('address') is-invalid @enderror"
                                           value="{{ old('address') }}"
                                           placeholder="Street, city"
                                           autocomplete="off">
                                </div>
                                @error('address')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <label class="form-label-p">
                                    Purchase Date <span class="req">*</span>
                                </label>
                                <div class="input-icon">
                                    <i class="bx bx-calendar"></i>
                                    <input type="text"
                                           name="purchase_date"
                                           class="form-control datepicker @error('purchase_date') is-invalid @enderror"
                                           value="{{ old('purchase_date', date('Y-m-d')) }}"
                                           autocomplete="off"
                                           required>
                                </div>
                                @error('purchase_date')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            @if(auth()->user()->user_type == 0 || auth()->user()?->role?->is_admin == 1)
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label-p">Branch</label>
                                    <input type="hidden"
                                           name="h_branch"
                                           id="h_branch"
                                           value="{{ old('h_branch', auth()->user()?->branch?->name) }}">
                                    <select name="branch"
                                            id="select_branch"
                                            class="form-control select_branch">
                                        <option value="">Select Branch</option>
                                    </select>
                                    @error('branch')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            @else
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label-p">Branch</label>
                                    <input type="text"
                                           class="form-control"
                                           value="{{ auth()->user()?->branch?->name }}"
                                           disabled>
                                    <input type="hidden"
                                           name="branch"
                                           value="{{ auth()->user()->branch_id }}">
                                </div>
                            @endif

                            <div class="col-md-3 col-sm-6">
                                <label class="form-label-p">Purchase Status</label>
                                <select name="status"
                                        class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Received</option>
                                    <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Partial</option>
                                    <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Pending</option>
                                    <option value="4" {{ old('status') == 4 ? 'selected' : '' }}>Ordered</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Picker --}}
                <div class="p-card">
                    <div class="p-card-header">
                        <div class="icon-box icon-green">
                            <i class="bx bx-package"></i>
                        </div>
                        <div>
                            <h3>Add Products</h3>
                            <p class="sub">
                                Select products by category, code or barcode
                            </p>
                        </div>
                    </div>

                    <div class="p-card-body">
                        <div class="picker-row">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label-p">Select Category</label>
                                    <select id="select_category"
                                            class="form-control"
                                            data-in="1">
                                        <option value="">Category</option>
                                    </select>
                                </div>

                                <div class="col-md-4 col-sm-6">
                                    <label class="form-label-p">Select Product</label>
                                    <select id="select_product"
                                            class="form-control"
                                            data-in="1">
                                        <option value="">Product</option>
                                    </select>
                                </div>

                                <div class="col-md-5 col-sm-12">
                                    <label class="form-label-p">
                                        Quick Search / Barcode
                                    </label>
                                    <div class="search-box input-group">
                                        <button class="btn barcode-addon"
                                                type="button"
                                                id="barcode-focus">
                                            <i class="bx bx-barcode"></i>
                                        </button>
                                        <input type="text"
                                               id="productcodeSearch"
                                               name="product_code_name"
                                               class="form-control"
                                               placeholder="Type product code and select..."
                                               autocomplete="off"
                                               style="border-radius:0 8px 8px 0;">
                                    </div>
                                    <div id="auto-serach-res-f"
                                         style="width:100%;position:relative;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="p-card table-card">
                    <div class="p-card-header">
                        <div class="icon-box icon-purple">
                            <i class="bx bx-list-ul"></i>
                        </div>
                        <div>
                            <h3>Order Items</h3>
                            <p class="sub">
                                Review quantities, prices, tax and discounts
                            </p>
                        </div>
                    </div>

                    <div class="table-scroll">
                        <table class="order-list table-responsive-sm">
                            <thead>
                                <tr>
                                    <th width="20%">Product</th>
                                    <th width="10%">Manufacture</th>
                                    <th width="10%">Brand</th>
                                    <th width="10%">Qty</th>
                                    <th width="13%">Unit</th>
                                    <th width="10%">Price</th>
                                    <th width="6%">Tax</th>
                                    <th width="10%">Discount</th>
                                    <th width="10%">Total Price</th>
                                    <th width="4%"></th>
                                </tr>
                            </thead>
                            <tbody class="add-ajax-product">
                                <tr class="empty-cart-row">
                                    <td colspan="10">
                                        <div class="empty-cart">
                                            <i class="bx bx-cart"></i>
                                            <p>
                                                No products added yet —
                                                select a product above to begin.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="tfoot active">
                                <tr>
                                    <th colspan="3" style="text-align:right;">Total</th>
                                    <th id="total_qty">0</th>
                                    <th></th>
                                    <th></th>
                                    <th id="sub_tax_total">0.00</th>
                                    <th id="sub_discount_total">0.00</th>
                                    <th id="total">0.00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Additional Information --}}
                <div class="p-card">
                    <div class="p-card-header">
                        <div class="icon-box icon-orange">
                            <i class="bx bx-note"></i>
                        </div>
                        <div>
                            <h3>Additional Information</h3>
                            <p class="sub">
                                Order notes and supporting documents
                            </p>
                        </div>
                    </div>
                    <div class="p-card-body">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label-p">Order Note</label>
                                <textarea name="order_note"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Any remark about this purchase...">{{ old('order_note') }}</textarea>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label-p">Purchase Document</label>
                                <input type="file"
                                       name="document"
                                       class="form-control @error('document') is-invalid @enderror">
                                <small class="text-muted" style="font-size:11.5px;">
                                    Attach invoice / challan file (optional)
                                </small>
                                @error('document')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Sidebar --}}
            <div class="col-xl-3 col-lg-4 col-sidebar">

                {{-- Payment --}}
                <div class="p-card">
                    <div class="p-card-header">
                        <div class="icon-box icon-blue">
                            <i class="bx bx-credit-card"></i>
                        </div>
                        <div>
                            <h3>Payment</h3>
                            <p class="sub">Method &amp; account</p>
                        </div>
                    </div>
                    <div class="p-card-body pt-3">
                        <div class="mb-3">
                            <label class="form-label-p">Payment Method</label>
                            <select name="payment_method"
                                    id="payment_method"
                                    class="form-control @error('payment_method') is-invalid @enderror">
                                <option value="">Select Method</option>
                                @foreach($methods as $method)
                                    <option value="{{ $method->id }}"
                                        {{ old('payment_method') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        @if(in_array('accounts', load_pack_option()))
                            <div class="mb-1">
                                <label class="form-label-p">Account</label>
                                <input type="hidden"
                                       name="h_bank_account"
                                       id="h_bank_account"
                                       value="{{ old('h_bank_account') }}">
                                <select name="account"
                                        id="add_account"
                                        class="form-control @error('account') is-invalid @enderror">
                                    <option value="">Select Account</option>
                                </select>
                                @error('account')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="p-card summary-card"
                     style="position:sticky;top:15px;">
                    <div class="p-card-header">
                        <div class="icon-box icon-green">
                            <i class="bx bx-receipt"></i>
                        </div>
                        <div>
                            <h3>Order Summary</h3>
                            <p class="sub">Live calculation</p>
                        </div>
                    </div>

                    <div class="p-card-body pt-2 pb-3">
                        <div class="sum-row">
                            <span><i class="bx bx-box me-1"></i> Items</span>
                            <span class="val" id="item">0</span>
                        </div>
                        <div class="sum-row">
                            <span>Subtotal</span>
                            <span class="val" id="subtotal">0.00</span>
                        </div>
                        <div class="sum-row">
                            <span>Total Tax</span>
                            <span class="val" id="order_tax">0.00</span>
                        </div>
                        <div class="sum-row">
                            <span>Item Discount</span>
                            <span class="val"
                                  id="order_discount_items"
                                  style="color:#12a05c;">0.00</span>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="col-12">
                                <label class="form-label-p mb-1">
                                    Shipping Cost
                                </label>
                                <input type="number"
                                       step="any"
                                       min="0"
                                       id="sub_shipping_cost"
                                       name="shipping_cost"
                                       class="form-control"
                                       value="{{ old('shipping_cost', 0) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label-p mb-1">
                                    Order Discount
                                </label>
                                <input type="number"
                                       step="any"
                                       min="0"
                                       id="sub_order_discount"
                                       name="order_discount"
                                       class="form-control"
                                       value="{{ old('order_discount', 0) }}">
                            </div>
                        </div>

                        <div class="sum-row mt-2">
                            <span style="font-weight:600;color:#101828;">
                                Total Discount
                            </span>
                            <span class="val" id="order_discount">0.00</span>
                        </div>
                        <div class="sum-row">
                            <span>Shipping</span>
                            <span class="val" id="shipping_cost">0.00</span>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="col-12">
                                <label class="form-label-p mb-1">
                                    Paid Amount
                                </label>
                                <input type="number"
                                       step="any"
                                       min="0"
                                       id="paid_amount"
                                       name="paid_amount"
                                       class="form-control"
                                       value="{{ old('paid_amount', 0) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label-p mb-1">
                                    Due Amount
                                </label>
                                <input type="number"
                                       step="any"
                                       id="due_amount"
                                       name="due_amount"
                                       class="form-control"
                                       value="{{ old('due_amount', 0) }}"
                                       readonly>
                            </div>
                        </div>

                        <div class="grand-total-box d-flex align-items-center justify-content-between">
                            <div>
                                <p class="gt-label">Grand Total</p>
                                <p class="gt-value mb-0" id="grand_total">
                                    0.00
                                </p>
                            </div>
                            <i class="bx bx-wallet"
                               style="font-size:26px;color:rgba(255,255,255,.35);"></i>
                        </div>

                        <input type="hidden" name="item" id="item_input" value="0">
                        <input type="hidden" name="total_qty" id="total_qty_input" value="0">
                        <input type="hidden" name="is_ini_p" id="is_ini_p" value="0">
                        <input type="hidden" name="total_cost" id="subtotal_input" value="0">
                        <input type="hidden" name="total_tax" id="order_tax_input" value="0">
                        <input type="hidden" name="total_discount" id="order_discount_input" value="0">
                        <input type="hidden" name="grand_total" id="grand_total_input" value="0">
                        <input type="hidden" name="dis_total_tax" id="dis_total_tax" value="0">

                        <div class="form-group mt-3 mb-0">
                            <button type="submit"
                                    class="btn btn-primary"
                                    id="submit-btn">
                                <i class="bx bx-check me-1"></i>
                                Submit Purchase
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@php
    $purchaseHasAccounts = in_array('accounts', load_pack_option());

    $purchaseCanSelectBranch =
        auth()->user()->user_type == 0 ||
        auth()->user()?->role?->is_admin == 1;

    $purchaseOldProducts = [];

    foreach (old('product_id', []) as $index => $productId) {
        $purchaseOldProducts[] = [
            'id' => $productId,
            'qty' => old('qty.' . $index, 1),
            'unit' => old('unit.' . $index),
            'price' => old('per_cost.' . $index),
            'discount' => old('discount.' . $index),
            'tax' => old('tax.' . $index),
        ];
    }

    $purchaseOldBranchId = old(
        'branch',
        auth()->user()?->branch_id
    );

    $purchaseOldBranchName = old(
        'h_branch',
        auth()->user()?->branch?->name
    );

    $purchaseOldAccountId = old('account');
    $purchaseOldAccountName = old('h_bank_account');
    $purchaseCustomErrors = session('cus_errors', []);
@endphp

@section('script')
<script src="{{ asset('public/assets/js/jquery.bootstrap-touchspin.js') }}"></script>

<script>
$(document).ready(function () {
    'use strict';

    const config = {
        routes: {
            vendorSearch: "{{ route('auto-search.vendor') }}",
            productSearch: "{{ route('auto-search.product') }}",
            productDetails: "{{ route('get_product_details_by_id') }}",
            categories: "{{ route('select2.product.categories') }}",
            products: "{{ route('select2.products.by_category') }}",
            units: "{{ route('select2.product.units') }}",
            branches: "{{ route('select2.branches') }}",
            accounts: "{{ route('select2.balance_accounts') }}"
        },
        hasAccounts: {{ $purchaseHasAccounts ? 'true' : 'false' }},
        canSelectBranch: {{ $purchaseCanSelectBranch ? 'true' : 'false' }},
        oldBranch: {
            id: {{ Illuminate\Support\Js::from($purchaseOldBranchId) }},
            name: {{ Illuminate\Support\Js::from($purchaseOldBranchName) }}
        },
        oldAccount: {
            id: {{ Illuminate\Support\Js::from($purchaseOldAccountId) }},
            name: {{ Illuminate\Support\Js::from($purchaseOldAccountName) }}
        },
        oldProducts: {{ Illuminate\Support\Js::from($purchaseOldProducts) }},
        customErrors: {{ Illuminate\Support\Js::from($purchaseCustomErrors) }}
    };

    let rowNumber = 0;
    let vendorXHR = null;
    let productXHR = null;
    let vendorTimer = null;
    let productTimer = null;
    let vendorSearchVersion = 0;
    let productSearchVersion = 0;
    let isSubmitting = false;

    const pendingProducts = new Set();
    const $form = $('#purchase-form');

    function parseNumber(value) {
        const parsed = parseFloat(value);
        return Number.isFinite(parsed) ? parsed : 0;
    }

    function positiveNumber(value) {
        return Math.max(0, parseNumber(value));
    }

    function formatMoney(value) {
        return parseNumber(value).toFixed(2);
    }

    function showError(message) {
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(message);
        }
    }

    function showWarning(message) {
        if (typeof toastr !== 'undefined') {
            toastr.warning(message);
        } else {
            alert(message);
        }
    }

    function parseData(value) {
        if (typeof value === 'object' && value !== null) {
            return value;
        }
        try {
            return JSON.parse(value);
        } catch (error) {
            return null;
        }
    }

    function getRows() {
        return $('.add-ajax-product tr').filter(function () {
            const product = $(this).find('.select_product_code');
            return product.length > 0 &&
                String(product.attr('data-id') || '0') !== '0';
        });
    }

    function productAlreadyAdded(id) {
        return getRows().filter(function () {
            const currentId = $(this)
                .find('.select_product_code')
                .attr('data-id');
            return String(currentId) === String(id);
        }).length > 0;
    }

    function showEmptyCart() {
        if (getRows().length > 0) {
            $('.empty-cart-row').remove();
            return;
        }

        $('.add-ajax-product').html(
            '<tr class="empty-cart-row">' +
                '<td colspan="10">' +
                    '<div class="empty-cart">' +
                        '<i class="bx bx-cart"></i>' +
                        '<p>No products added yet — select a product above to begin.</p>' +
                    '</div>' +
                '</td>' +
            '</tr>'
        );
    }

    function setupSelect2(selector, placeholder, url, extraData) {
        const $element = $(selector);

        if (!$element.length || !$.fn.select2) {
            return;
        }

        if ($element.hasClass('select2-hidden-accessible')) {
            $element.select2('destroy');
        }

        $element.select2({
            placeholder: placeholder,
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    const data = {
                        value: $.trim(params.term || '')
                    };
                    if (typeof extraData === 'function') {
                        Object.assign(data, extraData());
                    }
                    return data;
                },
                processResults: function (response) {
                    if (Array.isArray(response)) {
                        return { results: response };
                    }
                    return {
                        results: response.results || []
                    };
                },
                cache: true
            }
        });
    }

    function selectExistingOption(selector, id, label) {
        if (id === null || id === undefined || id === '') {
            return;
        }
        const option = new Option(
            label || String(id),
            id,
            true,
            true
        );
        $(selector).append(option).trigger('change');
    }

    if (typeof flatpickr !== 'undefined') {
        $('.datepicker').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    }

    if (config.canSelectBranch) {
        setupSelect2(
            '#select_branch',
            'Select Branch',
            config.routes.branches
        );

        selectExistingOption(
            '#select_branch',
            config.oldBranch.id,
            config.oldBranch.name
        );

        $('#select_branch').on('select2:select', function (event) {
            $('#h_branch').val(event.params.data.text);
        });

        $('#select_branch').on('select2:clear', function () {
            $('#h_branch').val('');
        });
    }

    setupSelect2(
        '#select_category',
        'Select Category',
        config.routes.categories
    );

    setupSelect2(
        '#select_product',
        'Select Product',
        config.routes.products,
        function () {
            return {
                cat_id: $('#select_category').val() || ''
            };
        }
    );

    $('#select_category').on('change', function () {
        $('#select_product').val(null).trigger('change');
    });

    $('#select_product').on('select2:select', function (event) {
        const id = event.params.data.id;
        if (id) {
            addProduct(id);
        }
        $('#select_product').val(null).trigger('change');
    });

    function clearVendorSearch() {
        $('.auto-search').remove();
    }

    function fillVendor(vendor) {
        $('input[name="name"]').val(vendor.name || '');
        $('input[name="email"]').val(vendor.email || '');
        $('input[name="mobile"]').val(vendor.mobile || '');
        $('input[name="address"]').val(vendor.address || '');
        clearVendorSearch();
    }

    $('.vendor-auto-search').on('input', function () {
        const input = this;
        clearTimeout(vendorTimer);

        const field = $(input).attr('name');
        const value = $.trim($(input).val());

        vendorSearchVersion++;
        const currentVersion = vendorSearchVersion;

        if (vendorXHR) {
            vendorXHR.abort();
        }

        clearVendorSearch();

        if (value.length < 2) {
            return;
        }

        vendorTimer = setTimeout(function () {
            vendorXHR = $.ajax({
                url: config.routes.vendorSearch,
                method: 'GET',
                data: {
                    field: field,
                    value: value
                },
                success: function (response) {
                    if (currentVersion !== vendorSearchVersion) {
                        return;
                    }
                    if (response && response.data) {
                        $(input).after(response.data);
                    }
                },
                error: function (xhr, status) {
                    if (status !== 'abort') {
                        console.error('Vendor search error', xhr);
                    }
                }
            });
        }, 300);
    });

    $(document).on('click', '.auto-search-res', function (event) {
        event.preventDefault();

        const vendor = parseData($(this).attr('data'));

        if (!vendor) {
            showError('Invalid supplier information.');
            return;
        }

        vendorSearchVersion++;

        if (vendorXHR) {
            vendorXHR.abort();
        }

        fillVendor(vendor);
    });

    function clearProductSearch() {
        $('#auto-serach-res-f').empty();
        $('.auto-search-product').remove();
    }

    function runProductSearch(value, isBarcode) {
        productSearchVersion++;
        const currentVersion = productSearchVersion;

        if (productXHR) {
            productXHR.abort();
        }

        productXHR = $.ajax({
            url: config.routes.productSearch,
            method: 'GET',
            data: {
                value: value,
                is_barcode: isBarcode ? 1 : 0
            },
            success: function (response) {
                if (currentVersion !== productSearchVersion) {
                    return;
                }

                clearProductSearch();

                if (response && response.data) {
                    $('#auto-serach-res-f').html(response.data);
                }

                if (isBarcode && response.status === 'success') {
                    const $first = $(
                        '#auto-serach-res-f .auto-search-res-product'
                    ).first();

                    if ($first.length) {
                        $first.trigger('click');
                    }
                }
            },
            error: function (xhr, status) {
                if (status !== 'abort') {
                    console.error('Product search error', xhr);
                }
            }
        });
    }

    $('#productcodeSearch').on('input', function () {
        clearTimeout(productTimer);

        const value = $.trim($(this).val());

        if (productXHR) {
            productXHR.abort();
        }

        if (!value) {
            productSearchVersion++;
            clearProductSearch();
            return;
        }

        const isBarcode = value.toLowerCase().includes('nbr');

        productTimer = setTimeout(function () {
            runProductSearch(value, isBarcode);
        }, isBarcode ? 50 : 250);
    });

    $('#barcode-focus').on('click', function () {
        $('#productcodeSearch').focus();
    });

    $(document).on('click', '.auto-search-res-product', function (event) {
        event.preventDefault();

        const product = parseData($(this).attr('data'));

        if (!product || !product.p_id) {
            showError('Invalid product information.');
            return;
        }

        productSearchVersion++;

        if (productXHR) {
            productXHR.abort();
        }

        $('#productcodeSearch').val('');
        clearProductSearch();
        addProduct(product.p_id);
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest(
            '.vendor-auto-search, .auto-search'
        ).length) {
            clearVendorSearch();
        }

        if (!$(event.target).closest(
            '#productcodeSearch, #auto-serach-res-f'
        ).length) {
            clearProductSearch();
        }
    });

    function initializeUnit(currentRow) {
        setupSelect2(
            '.select_unit_' + currentRow,
            'Select Unit',
            config.routes.units,
            function () {
                return {
                    p_id: $(
                        '.select_product_code_' + currentRow
                    ).attr('data-id')
                };
            }
        );
    }

    function initializeQuantity(currentRow) {
        const $input = $('.select_qty_' + currentRow);

        if (!$input.length || !$.fn.TouchSpin) {
            return;
        }

        if ($input.data('touchspin')) {
            return;
        }

        $input.TouchSpin({
            min: 1,
            max: 999999999,
            step: 1,
            decimals: 2,
            forcestepdivisibility: 'none',
            verticalbuttons: false,
            buttondown_txt: '−',
            buttonup_txt: '+',
            buttondown_class: 'btn btn-light',
            buttonup_class: 'btn btn-light'
        });
    }

    function restoreRow(currentRow, item) {
        if (!item) {
            return;
        }

        if (item.qty !== null && item.qty !== undefined) {
            $('.select_qty_' + currentRow).val(item.qty);
        }

        if (item.price !== null && item.price !== undefined) {
            $('.purchase_price_' + currentRow).val(item.price);
        }

        if (item.discount !== null && item.discount !== undefined) {
            $('.discount_price_' + currentRow).val(item.discount);
        }

        if (item.tax !== null && item.tax !== undefined) {
            $('.tax_' + currentRow).val(item.tax);
        }

        if (item.unit) {
            const selector = '.select_unit_' + currentRow;
            const $unit = $(selector);

            const exists = $unit.find('option').filter(function () {
                return String(this.value) === String(item.unit);
            });

            if (exists.length) {
                $unit.val(String(item.unit)).trigger('change');
            } else {
                selectExistingOption(
                    selector,
                    item.unit,
                    String(item.unit)
                );
            }
        }
    }

    function addProduct(productId, restoreData = null) {
        const id = String(productId);

        if (!id || id === '0') {
            return Promise.resolve(false);
        }

        if (productAlreadyAdded(id) || pendingProducts.has(id)) {
            if (!restoreData) {
                showWarning('This product is already added.');
            }
            return Promise.resolve(false);
        }

        const currentRow = ++rowNumber;
        pendingProducts.add(id);

        return new Promise(function (resolve) {
            $.ajax({
                url: config.routes.productDetails,
                method: 'GET',
                data: {
                    is_sale: 0,
                    qty: restoreData ? restoreData.qty : 1,
                    old: 0,
                    id: id,
                    row_id: currentRow
                },
                success: function (response) {
                    if (!response || !response.data_view) {
                        showError('Product details not found.');
                        resolve(false);
                        return;
                    }

                    if (productAlreadyAdded(id)) {
                        resolve(false);
                        return;
                    }

                    $('.empty-cart-row').remove();
                    $('.add-ajax-product').append(response.data_view);

                    initializeUnit(currentRow);
                    initializeQuantity(currentRow);

                    if (response.unit_id) {
                        selectExistingOption(
                            '.select_unit_' + currentRow,
                            response.unit_id,
                            response.unit_name
                        );
                    }

                    if (restoreData) {
                        restoreRow(currentRow, restoreData);
                    }

                    priceCalculation();
                    resolve(true);
                },
                error: function (xhr) {
                    console.error('Product load error', xhr);
                    showError('Unable to load product.');
                    resolve(false);
                },
                complete: function () {
                    pendingProducts.delete(id);
                    showEmptyCart();
                }
            });
        });
    }

    window.FillProductOption = function (
        id,
        rowNo,
        old,
        qty,
        unitOption
    ) {
        return addProduct(id, {
            qty: qty || 1,
            unit: unitOption ? unitOption.value : null
        });
    };

    window.touchQty = initializeQuantity;
    window.initalUnit = initializeUnit;

    $(document).on('click', '.delete_item', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        showEmptyCart();
        priceCalculation();
    });

    if (config.hasAccounts && $('#add_account').length) {
        setupSelect2(
            '#add_account',
            'Select Bank Account',
            config.routes.accounts,
            function () {
                return {
                    method_id: $('#payment_method').val()
                };
            }
        );

        selectExistingOption(
            '#add_account',
            config.oldAccount.id,
            config.oldAccount.name
        );

        $('#add_account').on('select2:select', function (event) {
            $('#h_bank_account').val(event.params.data.text);
        });

        $('#add_account').on('select2:clear', function () {
            $('#h_bank_account').val('');
        });
    }

    $('#payment_method').on('change', function () {
        if (String($(this).val()) === '1') {
            $('.method_show').hide();
        } else {
            $('.method_show').show();
        }

        if (config.hasAccounts && $('#add_account').length) {
            $('#add_account').val(null).trigger('change');
            $('#h_bank_account').val('');
        }

        priceCalculation();
    });

    $('#add_account').on('change', function () {
        priceCalculation();
    });

    /*
    |--------------------------------------------------------------------------
    | Price Calculation
    |--------------------------------------------------------------------------
    */
    function priceCalculation() {
        let subtotal = 0;
        let totalQty = 0;
        let totalTax = 0;
        let itemDiscount = 0;
        let totalItems = 0;
        let initialFlag = 0;

        getRows().each(function () {
            const $row = $(this);

            const currentRow = $row
                .find('.select_product_code')
                .attr('data-in');

            const qty = positiveNumber(
                $row.find('.select_qty_' + currentRow).val()
            );

            const price = positiveNumber(
                $row.find('.purchase_price_' + currentRow).val()
            );

            const baseTotal = qty * price;

            // -----------------------------
            // Discount calculation
            // -----------------------------
            const $discount = $row.find(
                '.discount_price_' + currentRow
            );

            let discount = 0;

            if ($discount.length) {
                const isPercentage =
                    String($discount.attr('is-per')) === '1';

                if (isPercentage) {
                    const discountRate = positiveNumber(
                        $discount.attr('dis-per')
                    );

                    discount = baseTotal * discountRate / 100;

                    $discount.val(formatMoney(discount));
                } else {
                    discount = positiveNumber($discount.val());
                }
            }

            discount = Math.min(discount, baseTotal);

            // -----------------------------
            // Tax calculation
            // -----------------------------
            const taxableAmount = Math.max(
                0,
                baseTotal - discount
            );

            const taxType = $row
                .find('.select_tax_type')
                .val() || 'fixed';

            const taxValue = positiveNumber(
                $row.find('.select_tax_rate').val()
            );

            let tax = 0;

            if (taxType === 'percentage') {
                tax = taxableAmount * taxValue / 100;
            } else {
                tax = taxValue;
            }

            // Update hidden tax amount
            $row.find('.calculated_tax')
                .val(formatMoney(tax));

            // -----------------------------
            // Product total
            // -----------------------------
            const rowTotal = taxableAmount + tax;

            $row.find('.total_price_' + currentRow)
                .val(formatMoney(rowTotal));

            // Update original purchase total
            const originalPrice = positiveNumber(
                $row.find(
                    '.hidden_purchase_price_' + currentRow
                ).val()
            );

            $row.find('.purchase_total_price_' + currentRow)
                .val(formatMoney(qty * originalPrice));

            // Initial product flag
            const isInitial = $row.find(
                '.is_inital' + currentRow
            ).val();

            if (String(isInitial) === '1') {
                initialFlag = 1;
            }

            // Add to summary
            subtotal += baseTotal;
            totalQty += qty;
            totalTax += tax;
            itemDiscount += discount;
            totalItems++;
        });

        // -----------------------------
        // Shipping and order discount
        // -----------------------------
        const shipping = positiveNumber(
            $('#sub_shipping_cost').val()
        );

        const orderDiscount = positiveNumber(
            $('#sub_order_discount').val()
        );

        const totalDiscount = itemDiscount + orderDiscount;

        // -----------------------------
        // Grand total
        // -----------------------------
        const grandTotal = Math.max(
            0,
            subtotal + totalTax + shipping - totalDiscount
        );

        // -----------------------------
        // Payment
        // -----------------------------
        const paidAmount = positiveNumber(
            $('#paid_amount').val()
        );

        const dueAmount = Math.max(
            0,
            grandTotal - paidAmount
        );

        // -----------------------------
        // Table footer
        // -----------------------------
        $('#total_qty').text(totalQty.toFixed(2));

        $('#sub_tax_total').text(formatMoney(totalTax));

        $('#sub_discount_total').text(
            formatMoney(itemDiscount)
        );

        $('#total').text(formatMoney(subtotal));

        // -----------------------------
        // Sidebar summary
        // -----------------------------
        $('#item').text(totalItems);

        $('#subtotal').text(formatMoney(subtotal));

        $('#order_tax').text(formatMoney(totalTax));

        $('#order_discount_items').text(
            formatMoney(itemDiscount)
        );

        $('#order_discount').text(
            formatMoney(totalDiscount)
        );

        $('#shipping_cost').text(formatMoney(shipping));

        $('#grand_total').text(formatMoney(grandTotal));

        $('#due_amount').val(formatMoney(dueAmount));

        // -----------------------------
        // Hidden form values
        // -----------------------------
        $('#item_input').val(totalItems);

        $('#total_qty_input').val(totalQty.toFixed(2));

        $('#subtotal_input').val(formatMoney(subtotal));

        $('#order_tax_input').val(formatMoney(totalTax));

        $('#order_discount_input').val(
            formatMoney(totalDiscount)
        );

        $('#grand_total_input').val(
            formatMoney(grandTotal)
        );

        $('#dis_total_tax').val(formatMoney(totalTax));

        $('#is_ini_p').val(initialFlag);

        return {
            totalItems,
            totalQty,
            subtotal,
            totalTax,
            itemDiscount,
            orderDiscount,
            totalDiscount,
            shipping,
            grandTotal,
            paidAmount,
            dueAmount
        };
    }

    $(document).on(
        'input change',
        '.select_qty, ' +
        '.select_purchase_price, ' +
        '.select_discount_price, ' +
        '#sub_order_discount, ' +
        '#sub_shipping_cost, ' +
        '#paid_amount',
        '.add-ajax-product .select_tax_rate',
        function () {
            priceCalculation();
        }
    );

    $(document).on(
        'input keyup change',
        '.add-ajax-product .select_tax_rate',
        function () {
            priceCalculation();
        }
    );

    $(document).on(
        'change',
        '.add-ajax-product .select_tax_type',
        function () {
            priceCalculation();
        }
    );

    $(document).on(
        'touchspin.on.startspin touchspin.on.stopspin',
        '.select_qty',
        function () {
            priceCalculation();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */
    function validatePurchase() {
        const totals = priceCalculation();

        const supplierName = $.trim(
            $('input[name="name"]').val()
        );

        if (!supplierName) {
            showError('Please enter supplier name.');
            $('input[name="name"]').focus();
            return false;
        }

        if (!$('input[name="purchase_date"]').val()) {
            showError('Please select purchase date.');
            $('input[name="purchase_date"]').focus();
            return false;
        }

        /* if (config.canSelectBranch && !$('#select_branch').val()) {
            showError('Please select branch.');
            $('#select_branch').select2('open');
            return false;
        } */

        if (totals.totalItems <= 0) {
            showError('Please add at least one product.');
            $('#productcodeSearch').focus();
            return false;
        }

        let rowInvalid = false;

        getRows().each(function () {
            const $row = $(this);
            const $qty = $row.find('.select_qty');
            const $price = $row.find('.select_purchase_price');

            if (parseNumber($qty.val()) <= 0) {
                showError('Product quantity must be greater than zero.');
                $qty.focus();
                rowInvalid = true;
                return false;
            }

            if (
                $.trim($price.val()) === '' ||
                parseNumber($price.val()) < 0
            ) {
                showError('Please enter a valid purchase price.');
                $price.focus();
                rowInvalid = true;
                return false;
            }

            const $unit = $row.find('.select_unit');

            if ($unit.length && !$unit.val()) {
                showError('Please select product unit.');

                if ($unit.hasClass('select2-hidden-accessible')) {
                    $unit.select2('open');
                } else {
                    $unit.focus();
                }

                rowInvalid = true;
                return false;
            }
        });

        if (rowInvalid) {
            return false;
        }

        const maximumDiscount =
            totals.subtotal + totals.totalTax + totals.shipping;

        if (totals.totalDiscount > maximumDiscount) {
            showError('Discount cannot exceed purchase amount.');
            $('#sub_order_discount').focus();
            return false;
        }

        if (totals.paidAmount > totals.grandTotal + 0.001) {
            showError('Paid amount cannot exceed grand total.');
            $('#paid_amount').focus();
            return false;
        }

        if (
            totals.paidAmount > 0 &&
            !$('#payment_method').val()
        ) {
            showError('Please select payment method.');
            $('#payment_method').focus();
            return false;
        }

        if (
            config.hasAccounts &&
            totals.paidAmount > 0 &&
            $('#add_account').length &&
            !$('#add_account').val()
        ) {
            showError('Please select payment account.');

            if ($('#add_account').hasClass('select2-hidden-accessible')) {
                $('#add_account').select2('open');
            }
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Form Submission
    |--------------------------------------------------------------------------
    */
    $form.on('submit', function (event) {
        if (isSubmitting) {
            event.preventDefault();
            return false;
        }

        if (pendingProducts.size > 0) {
            event.preventDefault();
            showWarning(
                'Please wait until all products finish loading.'
            );
            return false;
        }

        if (!validatePurchase()) {
            event.preventDefault();
            return false;
        }

        isSubmitting = true;

        $('#submit-btn')
            .prop('disabled', true)
            .html(
                '<i class="bx bx-loader-alt bx-spin me-1"></i>' +
                ' Saving Purchase...'
            );

        return true;
    });

    /*
    |--------------------------------------------------------------------------
    | Restore Products After Validation Error
    |--------------------------------------------------------------------------
    */
    async function restoreOldProducts() {
        if (!Array.isArray(config.oldProducts)) {
            return;
        }

        for (const item of config.oldProducts) {
            if (item.id) {
                await addProduct(item.id, item);
            }
        }

        priceCalculation();
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Laravel Errors
    |--------------------------------------------------------------------------
    */
    if (Array.isArray(config.customErrors)) {
        config.customErrors.forEach(function (error) {
            showError(String(error));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Initial Setup
    |--------------------------------------------------------------------------
    */
    showEmptyCart();
    restoreOldProducts();
    priceCalculation();

});
</script>
@endsection
