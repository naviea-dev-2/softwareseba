@extends('inc.master')

@section('head')
    <title>New Work Order Type</title>
    <style>
        .section-box { border: 1px solid #dee2e6; border-radius: .5rem; padding: 1rem; margin-bottom: 1rem; background: #f8f9fa; }
        .field-row { background: #fff; padding: .75rem; border-radius: .375rem; margin-bottom: .5rem; border: 1px solid #e9ecef; }
        .form-check-inline { margin-right: 1rem; }
    </style>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <h4 class="mb-4"><b>New Work Order Type</b></h4>

        <form action="{{ route('work-order-types.store') }}" method="POST" id="typeForm">
            @csrf

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Basic Info</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Production" required oninput="document.getElementById('slug').value = this.value.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'')">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="production" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Standard Fields</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Field</th>
                                    <th>Show</th>
                                    <th>Required</th>
                                    <th>Custom Label (optional)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach([
                                    'title' => 'Title',
                                    'description' => 'Description',
                                    'customer_id' => 'Customer',
                                    'vendor_id' => 'Vendor',
                                    'assigned_to' => 'Assigned To',
                                    'warehouse_id' => 'Warehouse',
                                    'priority' => 'Priority',
                                    'due_at' => 'Due Date',
                                    'scheduled_at' => 'Scheduled Start',
                                    'estimated_cost' => 'Estimated Cost',
                                    'estimated_hours' => 'Estimated Hours',
                                    'reference_no' => 'Reference No',
                                ] as $key => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td class="text-center"><input type="checkbox" name="fields[{{ $key }}][show]" class="form-check-input" checked></td>
                                    <td class="text-center"><input type="checkbox" name="fields[{{ $key }}][required]" class="form-check-input"></td>
                                    <td><input type="text" name="fields[{{ $key }}][label]" class="form-control form-control-sm" placeholder="{{ $label }}"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Custom Sections</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSection()"><i class="bx bx-plus"></i> Add Section</button>
                </div>
                <div class="card-body" id="sectionsContainer">
                    {{-- Sections added by JS --}}
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Line Items</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="line_items_enabled" id="liEnabled" value="1">
                                <label class="form-check-label" for="liEnabled">Enable Line Items</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Label</label>
                            <input type="text" name="line_items_label" class="form-control" value="Items">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Allowed Types</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="line_item_types[]" value="product" id="lt_prod" checked>
                                <label class="form-check-label" for="lt_prod">Product</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="line_item_types[]" value="material" id="lt_mat">
                                <label class="form-check-label" for="lt_mat">Material</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="line_item_types[]" value="labor" id="lt_lab">
                                <label class="form-check-label" for="lt_lab">Labor</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="line_item_types[]" value="expense" id="lt_exp">
                                <label class="form-check-label" for="lt_exp">Expense</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Type</button>
                <a href="{{ route('work-order-types.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
let secIdx = 0;
function addSection() {
    const container = document.getElementById('sectionsContainer');
    const div = document.createElement('div');
    div.className = 'section-box';
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <input type="text" name="sections[${secIdx}][title]" class="form-control form-control-sm fw-bold" placeholder="Section Title" style="width:300px" required>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.section-box').remove()"><i class="bx bx-trash"></i></button>
        </div>
        <div class="fields-container" id="secFields_${secIdx}"></div>
        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addField(${secIdx})"><i class="bx bx-plus"></i> Add Field</button>
    `;
    container.appendChild(div);
    addField(secIdx);
    secIdx++;
}

function addField(sIdx) {
    const container = document.getElementById('secFields_' + sIdx);
    const fCount = container.children.length;
    const row = document.createElement('div');
    row.className = 'field-row row g-2 align-items-end';
    row.innerHTML = `
        <div class="col-md-3">
            <label class="form-label small mb-1">Field Name</label>
            <input type="text" name="sections[${sIdx}][fields][${fCount}][name]" class="form-control form-control-sm" placeholder="e.g. machine_line" required>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Label</label>
            <input type="text" name="sections[${sIdx}][fields][${fCount}][label]" class="form-control form-control-sm" placeholder="Machine / Line" required>
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Type</label>
            <select name="sections[${sIdx}][fields][${fCount}][type]" class="form-select form-select-sm" onchange="toggleOptions(this, ${sIdx}, ${fCount})">
                <option value="text">Text</option>
                <option value="number">Number</option>
                <option value="textarea">Textarea</option>
                <option value="select">Select</option>
                <option value="select-product">Select Product</option>
                <option value="date">Date</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Options (comma sep)</label>
            <input type="text" name="sections[${sIdx}][fields][${fCount}][options]" class="form-control form-control-sm" placeholder="A, B, C" id="opt_${sIdx}_${fCount}" disabled>
        </div>
        <div class="col-md-1">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="sections[${sIdx}][fields][${fCount}][required]" id="req_${sIdx}_${fCount}">
                <label class="form-check-label small" for="req_${sIdx}_${fCount}">Required</label>
            </div>
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.field-row').remove()"><i class="bx bx-trash"></i></button>
        </div>
    `;
    container.appendChild(row);
}

function toggleOptions(select, sIdx, fCount) {
    const optInput = document.getElementById('opt_' + sIdx + '_' + fCount);
    optInput.disabled = select.value !== 'select';
    if (select.value !== 'select') optInput.value = '';
}
</script>
@endsection