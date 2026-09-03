@extends('inc.master')

@section('head')
    <title>{{ isset($type) ? 'Edit' : 'Create' }} Work Order Type</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
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

        .help-text {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 0.25rem;
        }

        .json-preview {
            background: #1a1a2e;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-family: monospace;
            overflow-x: auto;
            max-height: 400px;
        }
    </style>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="form-card">
            <div class="form-header">
                <h5>
                    <i class="bx bx-{{ isset($type) ? 'edit' : 'plus-circle' }}"></i>
                    {{ isset($type) ? 'Edit' : 'Create' }} Work Order Type
                </h5>
                <a href="{{ route('work-order-types.index') }}" class="btn btn-sm btn-light">
                    <i class="bx bx-arrow-back"></i> Back to List
                </a>
            </div>

            <form action="{{ isset($type) ? route('work-order-types.update', $type->id) : route('work-order-types.store') }}" 
                  method="POST" id="typeForm">
                @csrf
                @if(isset($type))
                    @method('PUT')
                @endif

                {{-- Basic Information --}}
                <div class="field-group">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $type->name ?? '') }}" required>
                            <div class="help-text">Display name for this work order type.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control" 
                                   value="{{ old('slug', $type->slug ?? '') }}" 
                                   placeholder="Leave empty to auto-generate">
                            <div class="help-text">URL-friendly identifier. Auto-generated if left empty.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" 
                                      placeholder="Brief description of this work order type">{{ old('description', $type->description ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                       id="isActive" {{ old('is_active', $type->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">
                                    <i class="bx bx-{{ (old('is_active', $type->is_active ?? true)) ? 'check-circle' : 'x-circle' }}"></i>
                                    Active
                                </label>
                            </div>
                            <div class="help-text">Inactive types won't appear in the work order creation flow.</div>
                        </div>
                    </div>
                </div>

                {{-- Configuration --}}
                <div class="section-title-modern">
                    <i class="bx bx-cog"></i> Configuration
                </div>

                <div class="field-group">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Config (JSON) <span class="text-danger">*</span></label>
                            <textarea name="config" class="form-control" rows="15" 
                                      id="configTextarea" required>{{ old('config', isset($type) ? json_encode($type->config, JSON_PRETTY_PRINT) : json_encode([], JSON_PRETTY_PRINT)) }}</textarea>
                            <div class="help-text">
                                <i class="bx bx-info-circle"></i> 
                                Enter valid JSON configuration. See documentation for structure.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Config Preview (read-only) --}}
                <div class="field-group">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">Configuration Preview</label>
                            <div class="json-preview" id="jsonPreview">
                                {{ old('config', isset($type) ? json_encode($type->config, JSON_PRETTY_PRINT) : '{}') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="action-bar">
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="bx bx-{{ isset($type) ? 'save' : 'check-circle' }}"></i>
                        {{ isset($type) ? 'Update' : 'Create' }} Type
                    </button>
                    <a href="{{ route('work-order-types.index') }}" class="btn btn-outline-modern">
                        <i class="bx bx-x"></i> Cancel
                    </a>
                    @if(isset($type))
                        <button type="button" class="btn btn-outline-modern ms-auto" onclick="validateConfig()">
                            <i class="bx bx-check"></i> Validate JSON
                        </button>
                        <button type="button" class="btn btn-outline-modern" onclick="formatConfig()">
                            <i class="bx bx-code"></i> Format JSON
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Update preview when config changes
    document.getElementById('configTextarea').addEventListener('input', function() {
        try {
            const json = JSON.parse(this.value);
            document.getElementById('jsonPreview').textContent = JSON.stringify(json, null, 2);
            document.getElementById('jsonPreview').style.color = '#e2e8f0';
        } catch (e) {
            document.getElementById('jsonPreview').textContent = 'Invalid JSON: ' + e.message;
            document.getElementById('jsonPreview').style.color = '#f87171';
        }
    });

    // Validate JSON
    function validateConfig() {
        const textarea = document.getElementById('configTextarea');
        try {
            JSON.parse(textarea.value);
            alert('✅ JSON is valid!');
        } catch (e) {
            alert('❌ Invalid JSON: ' + e.message);
        }
    }

    // Format JSON
    function formatConfig() {
        const textarea = document.getElementById('configTextarea');
        try {
            const json = JSON.parse(textarea.value);
            textarea.value = JSON.stringify(json, null, 2);
            document.getElementById('jsonPreview').textContent = JSON.stringify(json, null, 2);
            document.getElementById('jsonPreview').style.color = '#e2e8f0';
        } catch (e) {
            alert('❌ Cannot format: ' + e.message);
        }
    }

    // Form submit validation
    document.getElementById('typeForm').addEventListener('submit', function(e) {
        const textarea = document.getElementById('configTextarea');
        try {
            JSON.parse(textarea.value);
        } catch (e) {
            e.preventDefault();
            alert('❌ Invalid JSON configuration. Please fix the errors.');
        }
    });

    // Auto-slug generation
    document.querySelector('input[name="name"]').addEventListener('input', function() {
        const slugInput = document.querySelector('input[name="slug"]');
        if (!slugInput.value || slugInput.dataset.generated === 'true') {
            slugInput.value = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.dataset.generated = 'true';
        }
    });

    // Reset generated flag when user manually types in slug
    document.querySelector('input[name="slug"]').addEventListener('input', function() {
        this.dataset.generated = 'false';
    });

    // Auto-generate slug on page load if empty
    document.addEventListener('DOMContentLoaded', function() {
        const slugInput = document.querySelector('input[name="slug"]');
        const nameInput = document.querySelector('input[name="name"]');
        if (!slugInput.value && nameInput.value) {
            slugInput.value = nameInput.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.dataset.generated = 'true';
        }
    });
</script>
@endsection