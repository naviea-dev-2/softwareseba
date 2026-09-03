@extends('inc.master')

@section('head')
    <title>Edit Work Order {{ $workOrder->work_order_no }}</title>
    <style>
        .section-title { font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; color: #6c757d; margin: 1.5rem 0 1rem; border-bottom: 1px solid #dee2e6; padding-bottom: .5rem; font-weight: 600; }
    </style>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><b>Edit {{ $workOrder->work_order_no }}</b></h4>
            <span class="badge bg-primary fs-6">{{ $selectedType->name }}</span>
        </div>

        <form action="{{ route('work-orders.update', $workOrder) }}" method="POST" id="woEditForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="work_order_type_id" value="{{ $selectedType->id }}">

            <div class="row g-3">
                {{-- Title --}}
                <div class="col-md-6">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $workOrder->title) }}" required>
                </div>

                {{-- Priority --}}
                @if($selectedType->isVisible('priority'))
                <div class="col-md-3">
                    <label class="form-label">{{ $selectedType->getFieldLabel('priority') }} @if($selectedType->isRequired('priority'))<span class="text-danger">*</span>@endif</label>
                    <select name="priority" class="form-select" {{ $selectedType->isRequired('priority')?'required':'' }}>
                        @foreach(['low'=>'Low','normal'=>'Normal','high'=>'High','urgent'=>'Urgent'] as $k=>$v)
                            <option value="{{ $k }}" {{ old('priority', $workOrder->priority) == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Assigned To --}}
                @if($selectedType->isVisible('assigned_to'))
                <div class="col-md-3">
                    <label class="form-label">{{ $selectedType->getFieldLabel('assigned_to') }} @if($selectedType->isRequired('assigned_to'))<span class="text-danger">*</span>@endif</label>
                    <select name="assigned_to" class="form-select" {{ $selectedType->isRequired('assigned_to')?'required':'' }}>
                        <option value="">Unassigned</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('assigned_to', $workOrder->assigned_to) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Description --}}
                @if($selectedType->isVisible('description'))
                <div class="col-12">
                    <label class="form-label">{{ $selectedType->getFieldLabel('description') }} @if($selectedType->isRequired('description'))<span class="text-danger">*</span>@endif</label>
                    <textarea name="description" class="form-control" rows="2" {{ $selectedType->isRequired('description')?'required':'' }}>{{ old('description', $workOrder->description) }}</textarea>
                </div>
                @endif

                {{-- Customer --}}
                @if($selectedType->isVisible('customer_id'))
                <div class="col-md-4">
                    <label class="form-label">{{ $selectedType->getFieldLabel('customer_id') }} @if($selectedType->isRequired('customer_id'))<span class="text-danger">*</span>@endif</label>
                    <select name="customer_id" class="form-select" {{ $selectedType->isRequired('customer_id')?'required':'' }}>
                        <option value="">None</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $workOrder->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
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
                            <option value="{{ $v->id }}" {{ old('vendor_id', $workOrder->vendor_id) == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Warehouse --}}
                @if($selectedType->isVisible('warehouse_id'))
                <div class="col-md-4">
                    <label class="form-label">{{ $selectedType->getFieldLabel('warehouse_id') }} @if($selectedType->isRequired('warehouse_id'))<span class="text-danger">*</span>@endif</label>
                    <select name="warehouse_id" class="form-select" {{ $selectedType->isRequired('warehouse_id')?'required':'' }}>
                        <option value="">None</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ old('warehouse_id', $workOrder->warehouse_id) == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Dates --}}
                @if($selectedType->isVisible('due_at'))
                <div class="col-md-3">
                    <label class="form-label">{{ $selectedType->getFieldLabel('due_at') }} @if($selectedType->isRequired('due_at'))<span class="text-danger">*</span>@endif</label>
                    <input type="date" name="due_at" class="form-control" value="{{ old('due_at', $workOrder->due_at?->format('Y-m-d')) }}" {{ $selectedType->isRequired('due_at')?'required':'' }}>
                </div>
                @endif

                @if($selectedType->isVisible('scheduled_at'))
                <div class="col-md-3">
                    <label class="form-label">Scheduled Start</label>
                    <input type="date" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', $workOrder->scheduled_at?->format('Y-m-d')) }}">
                </div>
                @endif

                {{-- Cost & Hours --}}
                @if($selectedType->isVisible('estimated_cost'))
                <div class="col-md-3">
                    <label class="form-label">Estimated Cost @if($selectedType->isRequired('estimated_cost'))<span class="text-danger">*</span>@endif</label>
                    <input type="number" step="0.01" name="estimated_cost" class="form-control" value="{{ old('estimated_cost', $workOrder->estimated_cost) }}" {{ $selectedType->isRequired('estimated_cost')?'required':'' }}>
                </div>
                @endif

                @if($selectedType->isVisible('estimated_hours'))
                <div class="col-md-3">
                    <label class="form-label">Estimated Hours @if($selectedType->isRequired('estimated_hours'))<span class="text-danger">*</span>@endif</label>
                    <input type="number" step="0.01" name="estimated_hours" class="form-control" value="{{ old('estimated_hours', $workOrder->estimated_hours) }}" {{ $selectedType->isRequired('estimated_hours')?'required':'' }}>
                </div>
                @endif

                {{-- Reference --}}
                @if($selectedType->isVisible('reference_no'))
                <div class="col-md-4">
                    <label class="form-label">{{ $selectedType->getFieldLabel('reference_no') }}</label>
                    <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no', $workOrder->reference_no) }}">
                </div>
                @endif
            </div>

            {{-- Custom Sections --}}
            @foreach($selectedType->getCustomSections() as $section)
                <div class="section-title">{{ $section['title'] }}</div>
                <div class="row g-3">
                    @foreach($section['fields'] as $field)
                        @php $fieldValue = $workOrder->meta[$field['name']] ?? null; @endphp
                        <div class="col-md-4">
                            <label class="form-label">{{ $field['label'] }} @if($field['required']??false)<span class="text-danger">*</span>@endif</label>

                            @if($field['type'] === 'select')
                                <select name="meta[{{ $field['name'] }}]" class="form-select" {{ ($field['required']??false)?'required':'' }}>
                                    <option value="">Select...</option>
                                    @foreach($field['options'] ?? [] as $opt)
                                        <option value="{{ $opt }}" {{ old('meta.'.$field['name'], $fieldValue) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            @elseif($field['type'] === 'select-product')
                                <select name="meta[{{ $field['name'] }}]" class="form-select" {{ ($field['required']??false)?'required':'' }}>
                                    <option value="">Select...</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" {{ old('meta.'.$field['name'], $fieldValue) == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->sku }})</option>
                                    @endforeach
                                </select>
                            @elseif($field['type'] === 'textarea')
                                <textarea name="meta[{{ $field['name'] }}]" class="form-control" rows="2" {{ ($field['required']??false)?'required':'' }}>{{ old('meta.'.$field['name'], $fieldValue) }}</textarea>
                            @else
                                <input type="{{ $field['type'] }}" name="meta[{{ $field['name'] }}]" class="form-control" value="{{ old('meta.'.$field['name'], $fieldValue) }}" {{ ($field['required']??false)?'required':'' }}>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach

            {{-- Notes --}}
            <div class="section-title">Notes</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Instructions</label>
                    <textarea name="instructions" class="form-control" rows="2">{{ old('instructions', $workOrder->instructions) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Internal Notes</label>
                    <textarea name="internal_notes" class="form-control" rows="2">{{ old('internal_notes', $workOrder->internal_notes) }}</textarea>
                </div>
            </div>

            {{-- Line Items --}}
            @if($selectedType->lineItemsEnabled())
            <div class="section-title">{{ $selectedType->lineItemLabel() }}</div>
            <div class="table-responsive">
                <table class="table table-sm table-bordered" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:120px">Type</th>
                            <th>Product / Description</th>
                            <th style="width:100px">Qty</th>
                            <th style="width:120px">Unit Cost</th>
                            <th style="width:150px">Source WH</th>
                            <th style="width:50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workOrder->items as $idx => $item)
                        <tr>
                            <td>
                                <select name="items[{{ $idx }}][item_type]" class="form-select form-select-sm">
                                    @foreach($selectedType->lineItemTypes() as $lt)
                                        <option value="{{ $lt }}" {{ $item->item_type == $lt ? 'selected' : '' }}>{{ ucfirst($lt) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="items[{{ $idx }}][product_id]" class="form-select form-select-sm mb-1">
                                    <option value="">-- Product --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" {{ $item->product_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="items[{{ $idx }}][description]" class="form-control form-control-sm" value="{{ $item->description }}" placeholder="Description">
                            </td>
                            <td><input type="number" step="0.01" name="items[{{ $idx }}][quantity]" class="form-control form-select-sm" value="{{ $item->quantity }}"></td>
                            <td><input type="number" step="0.01" name="items[{{ $idx }}][unit_cost]" class="form-control form-select-sm" value="{{ $item->unit_cost }}"></td>
                            <td>
                                <select name="items[{{ $idx }}][source_warehouse_id]" class="form-select form-select-sm">
                                    <option value="">--</option>
                                    @foreach($warehouses as $w)
                                        <option value="{{ $w->id }}" {{ $item->source_warehouse_id == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="bx bx-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td>
                                <select name="items[0][item_type]" class="form-select form-select-sm">
                                    @foreach($selectedType->lineItemTypes() as $lt)<option value="{{ $lt }}">{{ ucfirst($lt) }}</option>@endforeach
                                </select>
                            </td>
                            <td>
                                <select name="items[0][product_id]" class="form-select form-select-sm mb-1">
                                    <option value="">-- Product --</option>
                                    @foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                                </select>
                                <input type="text" name="items[0][description]" class="form-control form-select-sm" placeholder="Description">
                            </td>
                            <td><input type="number" step="0.01" name="items[0][quantity]" class="form-control form-select-sm" value="1"></td>
                            <td><input type="number" step="0.01" name="items[0][unit_cost]" class="form-control form-select-sm" value="0"></td>
                            <td>
                                <select name="items[0][source_warehouse_id]" class="form-select form-select-sm">
                                    <option value="">--</option>
                                    @foreach($warehouses as $w)<option value="{{ $w->id }}">{{ $w->name }}</option>@endforeach
                                </select>
                            </td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="bx bx-trash"></i></button></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()"><i class="bx bx-plus"></i> Add Line</button>
            </div>
            @endif

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Work Order</button>
                <a href="{{ route('work-orders.show', $workOrder) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
let idx = {{ $workOrder->items->count() ?: 1 }};
function addItem() {
    const tbody = document.querySelector('#itemsTable tbody');
    const row = tbody.querySelector('tr').cloneNode(true);
    row.querySelectorAll('select, input').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, '[' + idx + ']');
        if(el.tagName === 'INPUT') el.value = el.type === 'number' ? (el.name.includes('quantity')?1:0) : '';
        if(el.tagName === 'SELECT') el.selectedIndex = 0;
    });
    tbody.appendChild(row);
    idx++;
}
</script>
@endsection