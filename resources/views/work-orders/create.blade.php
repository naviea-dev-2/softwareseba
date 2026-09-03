@extends('inc.master')

@section('head')
    <title>New Work Order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        /* ── Keep your original type card styles ── */
        .type-card { 
            cursor: pointer; 
            transition: all .2s; 
            border: 2px solid transparent; 
        }
        .type-card:hover { 
            border-color: #0d6efd; 
            transform: translateY(-3px); 
            box-shadow: 0 4px 12px rgba(0,0,0,.1); 
        }
        .section-title { 
            font-size: .8rem; 
            text-transform: uppercase; 
            letter-spacing: .5px; 
            color: #6c757d; 
            margin: 1.5rem 0 1rem; 
            border-bottom: 1px solid #dee2e6; 
            padding-bottom: .5rem; 
            font-weight: 600; 
        }

        /* ── Enhanced Form Styles ── */
        .form-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
            border: 1px solid #e9edf2;
        }

        .form-header {
            background: linear-gradient(135deg, #0b2b4a 0%, #1a4a6e 100%);
            margin: -2rem -2rem 2rem -2rem;
            padding: 1.5rem 2rem;
            border-radius: 16px 16px 0 0;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .form-header h5 {
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .form-header h5 i {
            font-size: 1.5rem;
        }
        .form-header .badge {
            font-size: 0.85rem;
            padding: 0.4rem 1.2rem;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            background: #fafbfc;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.08);
            background: #ffffff;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #1e293b;
            margin-bottom: 0.3rem;
        }
        .form-label .text-danger {
            color: #dc3545;
            font-weight: 700;
        }

        .field-group {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            border: 1px solid #eef2f6;
            transition: all 0.2s;
        }
        .field-group:hover {
            border-color: #d0d8e0;
        }

        .section-title-modern {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9edf2;
            margin: 2rem 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-title-modern i {
            color: #0d6efd;
            font-size: 1.1rem;
        }

        /* ── Company Settings Toggle ── */
        .company-toggle-btn {
            background: none;
            border: none;
            color: #0d6efd;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .company-toggle-btn:hover {
            background: #e7f1ff;
        }
        .company-toggle-btn i {
            font-size: 1rem;
        }

        .company-preview {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 16px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e9edf2;
            flex-wrap: wrap;
        }
        .company-preview .preview-logo {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: #e9edf2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            color: #64748b;
            flex-shrink: 0;
            overflow: hidden;
        }
        .company-preview .preview-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .company-preview .preview-info {
            flex: 1;
            min-width: 200px;
        }
        .company-preview .preview-info .name {
            font-weight: 600;
            color: #1a1a2e;
        }
        .company-preview .preview-info .details {
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
        }

        .company-settings-form {
            display: none;
            margin-top: 15px;
            padding: 20px;
            background: #ffffff;
            border: 2px dashed #dbeafe;
            border-radius: 12px;
        }
        .company-settings-form.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .company-settings-form .form-label {
            font-size: 0.8rem;
        }

        /* ── Line Items Table ── */
        .items-wrapper {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #eef2f6;
        }
        #itemsTable {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        #itemsTable thead {
            background: #f1f4f9;
        }
        #itemsTable thead th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #475569;
            border-bottom: 2px solid #d0d8e0;
            padding: 0.6rem 0.5rem;
        }
        #itemsTable tbody td {
            vertical-align: middle;
            padding: 0.4rem 0.4rem;
            background: white;
        }
        #itemsTable .form-control-sm, #itemsTable .form-select-sm {
            border-radius: 8px;
            font-size: 0.8rem;
            border-width: 1px;
        }

        .btn-add-item {
            border-radius: 50px;
            padding: 0.4rem 1.5rem;
            font-weight: 600;
            border: 2px dashed #0d6efd;
            color: #0d6efd;
            background: transparent;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }
        .btn-add-item:hover {
            background: #e7f1ff;
            border-style: solid;
            transform: translateY(-1px);
        }

        /* ── Buttons ── */
        .btn-primary-modern {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border: none;
            padding: 0.6rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }
        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
            color: white;
        }

        .btn-outline-modern {
            border-radius: 50px;
            padding: 0.6rem 2rem;
            font-weight: 600;
            border: 1.5px solid #d0d8e0;
            color: #475569;
            transition: all 0.2s;
        }
        .btn-outline-modern:hover {
            background: #f1f4f9;
            border-color: #b0bcc8;
        }

        .action-bar {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e9edf2;
        }

        /* ── Success Message ── */
        .alert-success-modern {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .alert-success-modern i {
            font-size: 1.5rem;
            color: #155724;
        }
        .alert-success-modern .btn-group-actions {
            margin-left: auto;
        }
        .alert-success-modern .btn-action-icon {
            background: white;
            border-color: #155724;
            color: #155724;
        }
        .alert-success-modern .btn-action-icon:hover {
            background: #155724;
            color: white;
        }

        /* ── Print & Download Button Group ── */
        .btn-group-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .btn-action-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
            border: 1.5px solid #d0d8e0;
            background: white;
            color: #475569;
            transition: all 0.2s;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-action-icon:hover {
            background: #f1f4f9;
            border-color: #0d6efd;
            color: #0d6efd;
            text-decoration: none;
        }
        .btn-action-icon i {
            font-size: 1.1rem;
        }

        /* ── Responsive ── */
        @media (max-width: 576px) {
            .form-card {
                padding: 1rem;
            }
            .form-header {
                margin: -1rem -1rem 1.5rem -1rem;
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
            }
            .field-group {
                padding: 0.75rem;
            }
            .alert-success-modern {
                flex-direction: column;
                text-align: center;
            }
            .alert-success-modern .btn-group-actions {
                margin-left: 0;
            }
            .company-preview {
                flex-direction: column;
                text-align: center;
            }
            .company-preview .preview-info {
                min-width: auto;
            }
            .company-toggle-btn {
                width: 100%;
                text-align: center;
            }
        }

        /* ── Logo Preview ── */
        #logoPreview {
            margin-top: 8px;
            display: none;
        }
        #logoPreview img {
            max-height: 60px;
            border-radius: 8px;
            border: 1px solid #e9edf2;
            padding: 4px;
        }
    </style>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">

        @if(!$selectedType)
            {{-- YOUR ORIGINAL TYPE SELECTION (UNCHANGED) --}}
            <h4 class="mb-4"><b>New Work Order</b></h4>
            
            <p class="text-muted mb-3">Select the type of work order to begin:</p>
            <div class="row g-3">
                @foreach($types as $t)
                <div class="col-md-3 col-sm-6">
                    <div class="card type-card h-100" onclick="location.href='{{ route('work-orders.create', ['type_id' => $t->id]) }}'">
                        <div class="card-body text-center">
                            <i class="bx bx-task text-primary" style="font-size: 2.5rem;"></i>
                            <h6 class="fw-bold mt-2 mb-1">{{ $t->name }}</h6>
                            <small class="text-muted">{{ $t->description }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3">
                <a href="{{ route('work-orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>

        @else
            {{-- ENHANCED FORM --}}
            <div class="form-card">
                {{-- Form Header --}}
                <div class="form-header">
                    <h5>
                        <i class="bx bx-task"></i>
                        New Work Order
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge">
                            <i class="bx bx-folder"></i> {{ $selectedType->name }}
                        </span>
                        <a href="{{ route('work-orders.create') }}" class="btn btn-sm btn-light no-print">
                            <i class="bx bx-refresh"></i> Change Type
                        </a>
                    </div>
                </div>

                {{-- Success Message with Print/Download Actions --}}
                @if(session('work_order_created'))
                    <div class="alert-success-modern">
                        <i class="bx bx-check-circle"></i>
                        <div>
                            <strong>Work Order Created Successfully!</strong>
                            <div class="text-muted" style="font-size:0.9rem;">
                                #{{ session('work_order_no') }} - {{ session('work_order_title') }}
                            </div>
                        </div>
                        <div class="btn-group-actions no-print">
                            <a href="{{ route('work-orders.print', session('work_order_id')) }}" 
                               class="btn-action-icon" 
                               target="_blank">
                                <i class="bx bx-printer"></i> Print
                            </a>
                            <a href="{{ route('work-orders.download', session('work_order_id')) }}" 
                               class="btn-action-icon">
                                <i class="bx bx-download"></i> Download PDF
                            </a>
                            <a href="{{ route('work-orders.show', session('work_order_id')) }}" 
                               class="btn-action-icon" style="border-color:#0d6efd;color:#0d6efd;">
                                <i class="bx bx-show"></i> View
                            </a>
                        </div>
                    </div>
                @endif

                <form action="{{ route('work-orders.store') }}" method="POST" id="woForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="work_order_type_id" value="{{ $selectedType->id }}">

                    {{-- ── MAIN FIELDS ── --}}
                    <div class="field-group">
                        <div class="row g-3">
                            {{-- Title --}}
                            <div class="col-md-8">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Enter work order title" required value="{{ old('title') }}">
                            </div>

                            {{-- Priority --}}
                            @if($selectedType->isVisible('priority'))
                            <div class="col-md-4">
                                <label class="form-label">{{ $selectedType->getFieldLabel('priority') }} @if($selectedType->isRequired('priority'))<span class="text-danger">*</span>@endif</label>
                                <select name="priority" class="form-select" {{ $selectedType->isRequired('priority')?'required':'' }}>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                                    <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>🔵 Normal</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟡 High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                                </select>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($selectedType->isVisible('description'))
                    <div class="field-group">
                        <label class="form-label">{{ $selectedType->getFieldLabel('description') }} @if($selectedType->isRequired('description'))<span class="text-danger">*</span>@endif</label>
                        <textarea name="description" class="form-control ckeditor" rows="3" placeholder="Provide detailed description of the work to be performed" {{ $selectedType->isRequired('description')?'required':'' }}>{{ old('description') }}</textarea>
                    </div>
                    @endif

                    {{-- Assignment & Details --}}
                    <div class="field-group">
                        <div class="row g-3">
                            {{-- Assigned To --}}
                            @if($selectedType->isVisible('assigned_to'))
                            <div class="col-md-4">
                                <label class="form-label">{{ $selectedType->getFieldLabel('assigned_to') }} @if($selectedType->isRequired('assigned_to'))<span class="text-danger">*</span>@endif</label>
                                <select name="assigned_to" class="form-select" {{ $selectedType->isRequired('assigned_to')?'required':'' }}>
                                    <option value="">Unassigned</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ old('assigned_to') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Customer --}}
                            @if($selectedType->isVisible('customer_id'))
                            <div class="col-md-4">
                                <label class="form-label">{{ $selectedType->getFieldLabel('customer_id') }} @if($selectedType->isRequired('customer_id'))<span class="text-danger">*</span>@endif</label>
                                <select name="customer_id" class="form-select" {{ $selectedType->isRequired('customer_id')?'required':'' }}>
                                    <option value="">None</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Vendor --}}
                            @if($selectedType->isVisible('vendor_id'))
                            <div class="col-md-4">
                                <label class="form-label">{{ $selectedType->getFieldLabel('vendor_id') }} @if($selectedType->isRequired('vendor_id'))<span class="text-danger">*</span>@endif</label>
                                <select name="vendor_id" class="form-select" {{ $selectedType->isRequired('vendor_id')?'required':'' }}>
                                    <option value="">None</option>
                                    @foreach($vendors as $v)
                                        <option value="{{ $v->id }}" {{ old('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Location & Dates --}}
                    <div class="field-group">
                        <div class="row g-3">
                            {{-- Warehouse --}}
                            @if($selectedType->isVisible('warehouse_id'))
                            <div class="col-md-4">
                                <label class="form-label">{{ $selectedType->getFieldLabel('warehouse_id') }} @if($selectedType->isRequired('warehouse_id'))<span class="text-danger">*</span>@endif</label>
                                <select name="warehouse_id" class="form-select" {{ $selectedType->isRequired('warehouse_id')?'required':'' }}>
                                    <option value="">None</option>
                                    @foreach($warehouses as $w)
                                        <option value="{{ $w->id }}" {{ old('warehouse_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Due Date --}}
                            @if($selectedType->isVisible('due_at'))
                            <div class="col-md-4">
                                <label class="form-label">{{ $selectedType->getFieldLabel('due_at') }} @if($selectedType->isRequired('due_at'))<span class="text-danger">*</span>@endif</label>
                                <input type="datetime-local" name="due_at" class="form-control" value="{{ old('due_at') }}" {{ $selectedType->isRequired('due_at')?'required':'' }}>
                            </div>
                            @endif

                            {{-- Scheduled Date --}}
                            @if($selectedType->isVisible('scheduled_at'))
                            <div class="col-md-4">
                                <label class="form-label">Scheduled Start</label>
                                <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}">
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Cost & Reference --}}
                    <div class="field-group">
                        <div class="row g-3">
                            {{-- Estimated Cost --}}
                            @if($selectedType->isVisible('estimated_cost'))
                            <div class="col-md-3">
                                <label class="form-label">Estimated Cost @if($selectedType->isRequired('estimated_cost'))<span class="text-danger">*</span>@endif</label>
                                <div class="input-group">
                                    <span class="input-group-text">&#2547;</span>
                                    <input type="number" step="0.01" name="estimated_cost" class="form-control" value="{{ old('estimated_cost', 0) }}" {{ $selectedType->isRequired('estimated_cost')?'required':'' }}>
                                </div>
                            </div>
                            @endif

                            {{-- Estimated Hours --}}
                            @if($selectedType->isVisible('estimated_hours'))
                            <div class="col-md-3">
                                <label class="form-label">Estimated Hours @if($selectedType->isRequired('estimated_hours'))<span class="text-danger">*</span>@endif</label>
                                <input type="number" step="0.01" name="estimated_hours" class="form-control" value="{{ old('estimated_hours', 0) }}" {{ $selectedType->isRequired('estimated_hours')?'required':'' }}>
                            </div>
                            @endif

                            {{-- Reference --}}
                            @if($selectedType->isVisible('reference_no'))
                            <div class="col-md-6">
                                <label class="form-label">{{ $selectedType->getFieldLabel('reference_no') }}</label>
                                <input type="text" name="reference_no" class="form-control" placeholder="Enter reference number (e.g., PO-12345)" value="{{ old('reference_no') }}">
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── COMPANY INFORMATION WITH TOGGLE ── --}}
                    <div class="section-title-modern">
                        <i class="bx bx-building"></i> Company Information
                    </div>

                    <div class="field-group">
                        {{-- Default Company Preview --}}
                        <div class="company-preview">
                            <div class="preview-logo">
                                @if(isset($business) && $business->business_logo)
                                    <img src="{{ asset('public/upload/business/'.$business->business_logo) }}" style="width:100px; height:auto" alt="{{ $business->business_name }}">
                                @endif
                            </div>
                            <div class="preview-info">
                                <div class="name">{{ $business->business_name ?? config('app.name') }}</div>
                                <div class="details">
                                    @if(isset($business))
                                        {{ $business->address1 ?? '' }},  {{ $business->address2 ?? '' }}
                                        @if(($business->city->name ?? '') || ($business->state->name ?? ''))
                                            , {{ $business->city->name ?? '' }} {{ $business->state->name ?? '' }}
                                        @endif
                                        @if($business->phone_number ?? '')
                                            | 📞 {{ $business->phone_number }}
                                        @endif
                                        @if($business->email ?? '')
                                            | ✉️ {{ $business->email }}
                                        @endif
                                    @else
                                        Default company information will be used
                                    @endif
                                </div>
                            </div>
                            <button type="button" class="company-toggle-btn" onclick="toggleCompanySettings()">
                                <i class="bx bx-edit"></i> 
                                <span id="toggleText">Change Company Info</span>
                            </button>
                        </div>

                        {{-- Company Settings Form (Hidden by default) --}}
                        <div class="company-settings-form" id="companySettingsForm">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="alert alert-info" style="border-radius:10px;padding:0.75rem 1rem;font-size:0.9rem;">
                                        <i class="bx bx-info-circle"></i> 
                                        Customize company information for this work order only. Leave fields empty to use default business info.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Company Logo</label>
                                    <input type="file" name="company_logo" class="form-control" accept="image/*" onchange="previewLogo(this)">
                                    <small class="text-muted">Upload custom logo for this order</small>
                                    <div id="logoPreview">
                                        <img id="logoPreviewImg" src="#" alt="Logo preview">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name" class="form-control" placeholder="Company name" value="{{ old('company_name', '') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tagline / Slogan</label>
                                    <input type="text" name="company_tagline" class="form-control" placeholder="Tagline" value="{{ old('company_tagline', '') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="company_phone" class="form-control" placeholder="Phone number" value="{{ old('company_phone', '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="company_email" class="form-control" placeholder="Email address" value="{{ old('company_email', '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Website</label>
                                    <input type="text" name="company_website" class="form-control" placeholder="Website URL" value="{{ old('company_website', '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tax / VAT Number</label>
                                    <input type="text" name="company_tax_number" class="form-control" placeholder="Tax number" value="{{ old('company_tax_number', '') }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="company_address" class="form-control" placeholder="Street address" value="{{ old('company_address', '') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">City</label>
                                    <input type="text" name="company_city" class="form-control" placeholder="City" value="{{ old('company_city', '') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">State</label>
                                    <input type="text" name="company_state" class="form-control" placeholder="State" value="{{ old('company_state', '') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">ZIP</label>
                                    <input type="text" name="company_zip" class="form-control" placeholder="ZIP code" value="{{ old('company_zip', '') }}">
                                </div>
                                <div class="col-md-10">
                                    <label class="form-label">Footer Text</label>
                                    <input type="text" name="company_footer_text" class="form-control" placeholder="Thank you message" value="{{ old('company_footer_text', 'Thank you for your business!') }}">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetCompanyFields()" style="border-radius:50px;">
                                        <i class="bx bx-reset"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── CUSTOM SECTIONS ── --}}
                    @foreach($selectedType->getCustomSections() as $section)
                        <div class="section-title-modern">
                            <i class="bx bx-extension"></i> {{ $section['title'] }}
                        </div>
                        <div class="field-group">
                            <div class="row g-3">
                                @foreach($section['fields'] as $field)
                                    @php
                                        $fieldName = 'meta[' . $field['name'] . ']';
                                        $fieldValue = old($fieldName);
                                    @endphp
                                    <div class="col-md-4">
                                        <label class="form-label">{{ $field['label'] }} @if($field['required']??false)<span class="text-danger">*</span>@endif</label>

                                        @if($field['type'] === 'select')
                                            <select name="{{ $fieldName }}" class="form-select" {{ ($field['required']??false)?'required':'' }}>
                                                <option value="">Select...</option>
                                                @foreach($field['options'] ?? [] as $opt)
                                                    <option value="{{ $opt }}" {{ $fieldValue == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($field['type'] === 'select-product')
                                            <select name="{{ $fieldName }}" class="form-select" {{ ($field['required']??false)?'required':'' }}>
                                                <option value="">Select product...</option>
                                                @foreach($products as $p)
                                                    <option value="{{ $p->id }}" {{ $fieldValue == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->sku }})</option>
                                                @endforeach
                                            </select>
                                        @elseif($field['type'] === 'textarea')
                                            <textarea name="{{ $fieldName }}" class="form-control" rows="2" {{ ($field['required']??false)?'required':'' }}>{{ $fieldValue }}</textarea>
                                        @elseif($field['type'] === 'date')
                                            <input type="date" name="{{ $fieldName }}" class="form-control" value="{{ $fieldValue }}" {{ ($field['required']??false)?'required':'' }}>
                                        @elseif($field['type'] === 'number')
                                            <input type="number" step="0.01" name="{{ $fieldName }}" class="form-control" value="{{ $fieldValue }}" {{ ($field['required']??false)?'required':'' }}>
                                        @else
                                            <input type="{{ $field['type'] }}" name="{{ $fieldName }}" class="form-control" placeholder="Enter {{ $field['label'] }}" value="{{ $fieldValue }}" {{ ($field['required']??false)?'required':'' }}>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- ── NOTES ── --}}
                    <div class="section-title-modern">
                        <i class="bx bx-note"></i> Notes & Instructions
                    </div>
                    <div class="field-group">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Instructions</label>
                                <textarea name="instructions" class="form-control ckeditor" rows="3" placeholder="Step-by-step instructions for the technician">{{ old('instructions') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Internal Notes</label>
                                <textarea name="internal_notes" class="form-control ckeditor" rows="3" placeholder="Private notes for internal team only">{{ old('internal_notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ── LINE ITEMS ── --}}
                    @if($selectedType->lineItemsEnabled())
                        <div class="section-title-modern">
                            <i class="bx bx-list-ul"></i> {{ $selectedType->lineItemLabel() }}
                        </div>
                        <div class="items-wrapper">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th style="width:100px">Type</th>
                                            <th>Product / Description</th>
                                            <th style="width:90px">Qty</th>
                                            <th style="width:110px">Unit Cost</th>
                                            <th style="width:140px">Source WH</th>
                                            <th style="width:45px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $oldItems = old('items', []);
                                            $itemCount = count($oldItems) > 0 ? count($oldItems) : 1;
                                        @endphp
                                        @for($i = 0; $i < $itemCount; $i++)
                                        <tr>
                                            <td>
                                                <select name="items[{{ $i }}][item_type]" class="form-select form-select-sm">
                                                    @foreach($selectedType->lineItemTypes() as $lt)
                                                        <option value="{{ $lt }}" {{ (old("items.{$i}.item_type") == $lt) ? 'selected' : '' }}>{{ ucfirst($lt) }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="items[{{ $i }}][product_id]" class="form-select form-select-sm mb-1">
                                                    <option value="">-- Product --</option>
                                                    @foreach($products as $p)
                                                        <option value="{{ $p->id }}" {{ old("items.{$i}.product_id") == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="items[{{ $i }}][description]" class="form-control form-control-sm" placeholder="Description" value="{{ old("items.{$i}.description") }}" required>
                                            </td>
                                            <td><input type="number" step="0.01" name="items[{{ $i }}][quantity]" class="form-control form-control-sm" value="{{ old("items.{$i}.quantity", 1) }}" required></td>
                                            <td><input type="number" step="0.01" name="items[{{ $i }}][unit_cost]" class="form-control form-control-sm" value="{{ old("items.{$i}.unit_cost", 0) }}"></td>
                                            <td>
                                                <select name="items[{{ $i }}][source_warehouse_id]" class="form-select form-select-sm">
                                                    <option value="">--</option>
                                                    @foreach($warehouses as $w)
                                                        <option value="{{ $w->id }}" {{ old("items.{$i}.source_warehouse_id") == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-add-item no-print" onclick="addItem()">
                                <i class="bx bx-plus"></i> Add Line Item
                            </button>
                        </div>
                    @endif

                    {{-- ── ACTION BAR ── --}}
                    <div class="action-bar no-print">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary-modern" id="submitBtn">
                                <i class="bx bx-check-circle"></i> Create Work Order
                            </button>
                            <a href="{{ route('work-orders.create') }}" class="btn btn-outline-modern">
                                <i class="bx bx-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
    let idx = {{ count(old('items', [])) > 0 ? count(old('items', [])) : 1 }};
    
    function addItem() {
        const tbody = document.querySelector('#itemsTable tbody');
        const row = tbody.querySelector('tr').cloneNode(true);
        row.querySelectorAll('select, input').forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/, '[' + idx + ']');
            }
            if (el.tagName === 'INPUT' && el.type !== 'hidden') {
                if (el.type === 'number') {
                    el.value = (el.name.includes('quantity')) ? 1 : 0;
                } else {
                    el.value = '';
                }
            }
            if (el.tagName === 'SELECT') {
                const firstOpt = el.querySelector('option');
                if (firstOpt) el.selectedIndex = 0;
            }
        });
        tbody.appendChild(row);
        idx++;
    }

    // ── Toggle Company Settings ──
    function toggleCompanySettings() {
        const form = document.getElementById('companySettingsForm');
        const toggleText = document.getElementById('toggleText');
        form.classList.toggle('active');
        
        if (form.classList.contains('active')) {
            toggleText.textContent = 'Hide Company Settings';
        } else {
            toggleText.textContent = 'Change Company Info';
        }
    }

    // ── Preview Logo ──
    function previewLogo(input) {
        const preview = document.getElementById('logoPreview');
        const previewImg = document.getElementById('logoPreviewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ── Reset Company Fields ──
    function resetCompanyFields() {
        const form = document.getElementById('companySettingsForm');
        const inputs = form.querySelectorAll('input:not([type="file"])');
        inputs.forEach(input => {
            input.value = '';
        });
        const fileInput = form.querySelector('input[type="file"]');
        if (fileInput) {
            fileInput.value = '';
        }
        document.getElementById('logoPreview').style.display = 'none';
    }

    // ── Auto-hide success message ──
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.querySelector('.alert-success-modern');
        if (alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            }, 10000);
        }

        // Show company settings if there are old values
        const hasCompanySettings = @json(
            old('company_name') || 
            old('company_address') || 
            old('company_phone') || 
            old('company_email')
        );
        if (hasCompanySettings) {
            document.getElementById('companySettingsForm').classList.add('active');
            document.getElementById('toggleText').textContent = 'Hide Company Settings';
        }
    });
</script>
@endsection