@extends('inc.master')
@section('content')
<style>
.bom-wrap{max-width:1440px;margin:0 auto;padding:26px 16px 48px;color:#17304e}.bom-head{display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;margin-bottom:24px}.bom-title{font-size:26px;font-weight:750;margin:0;color:#152d4b}.bom-muted{color:#75869b;margin:5px 0 0;font-size:14px}.bom-icon{width:48px;height:48px;border-radius:13px;background:#e8f1ff;color:#2563eb;display:grid;place-items:center;font-size:25px;flex-shrink:0}.bom-card{background:#fff;border:1px solid #e2eaf3;border-radius:16px;box-shadow:0 6px 24px rgba(22,45,75,.05);margin-bottom:20px;overflow:hidden}.bom-card-head{padding:19px 22px;border-bottom:1px solid #e9eef5;display:flex;align-items:center;gap:12px}.bom-card-head h4{font-size:17px;font-weight:750;margin:0}.bom-card-body{padding:22px}.bom-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 15px;border-radius:9px;text-decoration:none!important;font-weight:650;border:1px solid transparent;font-size:13px;cursor:pointer}.bom-btn.primary{background:#2563eb;color:#fff!important}.bom-btn.light{background:#f0f5fb;color:#263e5b!important}.bom-btn.danger{background:#fff0f0;color:#c02727!important}.bom-btn.outline{background:#fff;border-color:#d6e1ef;color:#263e5b!important}.bom-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:22px}.bom-stat{padding:20px;border:1px solid #dbe8f7;background:#f3f8ff;border-radius:13px}.bom-stat:nth-child(2){background:#effaf4;border-color:#d5eddf}.bom-stat:nth-child(3){background:#fff9ed;border-color:#f7e8c6}.bom-stat:nth-child(4){background:#f5f3ff;border-color:#e6defb}.bom-stat-label{font-size:13px;font-weight:650;color:#52677e}.bom-stat-value{font-size:28px;font-weight:800;color:#1b3554;margin:8px 0 2px}.bom-stat-note{font-size:12px;color:#74879c}.bom-search{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px}.bom-search input{flex:1;min-width:180px}.bom-input,.bom-wrap input:not([type=checkbox]),.bom-wrap select{min-height:45px;border:1px solid #d8e2ee;border-radius:9px;padding:10px 12px;width:100%;background:#fff;color:#253d57}.bom-wrap input:focus,.bom-wrap select:focus{outline:none;border-color:#6699f8;box-shadow:0 0 0 3px rgba(37,99,235,.09)}.bom-label{display:block;font-size:13px;font-weight:700;color:#344a61;margin-bottom:7px}.bom-field-error{font-size:12px;color:#be2424;margin-top:5px}.bom-table-scroll{overflow-x:auto}.bom-table{width:100%;border-collapse:collapse;min-width:850px}.bom-table th{background:#2b415a;color:#fff;text-align:left;font-size:12px;letter-spacing:.04em;text-transform:uppercase;padding:15px}.bom-table td{padding:15px;border-bottom:1px solid #e8eef5;font-size:13px;vertical-align:middle}.bom-table tbody tr:hover{background:#f8fbff}.bom-actions{display:flex;gap:6px;flex-wrap:wrap}.bom-actions .bom-btn{padding:7px 10px;font-size:12px}.bom-badge{display:inline-block;border-radius:20px;padding:6px 11px;font-size:12px;font-weight:700;background:#eef3f8;color:#44546b}.bom-badge.yes{background:#e2f7eb;color:#13804a}.bom-badge.no{background:#f1f5f9;color:#64748b}.bom-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:17px}.bom-item{border:1px solid #e1eaf5;border-radius:12px;padding:17px;margin-bottom:13px;background:#f9fbff}.bom-item-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:13px}.bom-checks{display:flex;gap:20px;flex-wrap:wrap;margin-top:20px}.bom-checks label{display:flex;align-items:center;gap:8px;font-weight:600;font-size:13px}.bom-checks input{width:17px;height:17px}.bom-help{color:#77889d;font-size:12px;margin-top:6px}.bom-sidebar{position:sticky;top:18px}.bom-layout{display:grid;grid-template-columns:minmax(0,2fr) minmax(250px,1fr);gap:20px}.bom-alert{padding:13px 17px;background:#fff1f2;color:#a62131;border-radius:9px;margin-bottom:17px}.bom-item-head{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:12px}@media(max-width:980px){.bom-stats{grid-template-columns:repeat(2,1fr)}.bom-layout{grid-template-columns:1fr}.bom-sidebar{position:static}}@media(max-width:600px){.bom-stats,.bom-form-grid,.bom-item-grid{grid-template-columns:1fr}.bom-card-body{padding:16px}.bom-title{font-size:23px}}

/* Premium component editor */
.bom-components-panel{border:1px solid #cddff5;box-shadow:0 12px 36px rgba(24,69,120,.07)}
.bom-components-panel>.bom-card-head{background:linear-gradient(110deg,#102d51,#1d4f88);color:white;border:0;padding:25px}
.bom-components-panel>.bom-card-head h4{color:white;font-size:19px}
.bom-components-panel>.bom-card-head .bom-muted{color:#cbdcf0}
.bom-components-panel>.bom-card-head .bom-icon{background:rgba(255,255,255,.14)!important;color:white}
.bom-components-panel>.bom-card-head .bom-btn{background:white;color:#19487e!important;border-color:white}
.bom-components-intro{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 17px;border-radius:12px;background:#edf5ff;color:#28527e;margin-bottom:18px;flex-wrap:wrap;font-size:13px}
.bom-component-list{display:grid;gap:19px}
.bom-component-card{position:relative;border:1px solid #d8e6f5;border-left:5px solid #3b82f6;border-radius:15px;background:#fff;overflow:hidden;box-shadow:0 5px 17px rgba(32,71,118,.055)}
.bom-component-card:nth-child(3n+2){border-left-color:#14a38b}
.bom-component-card:nth-child(3n+3){border-left-color:#a777e6}
.bom-component-header{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:15px 18px;background:#f4f8fd;border-bottom:1px solid #e6eef8;flex-wrap:wrap}
.bom-component-ident{display:flex;align-items:center;gap:12px;min-width:0}
.bom-component-number{display:grid;place-items:center;width:39px;height:39px;border-radius:11px;background:#e2efff;color:#1d61b2;font-size:17px;font-weight:800}
.bom-component-card:nth-child(3n+2) .bom-component-number{background:#dff8f1;color:#087f6c}
.bom-component-card:nth-child(3n+3) .bom-component-number{background:#f0e8ff;color:#7945b3}
.bom-component-header strong{display:block;font-size:15px;color:#193956}
.bom-component-header small{display:block;color:#7a8ea4;margin-top:3px}
.bom-component-body{padding:19px}
.bom-component-section-label{display:flex;align-items:center;gap:8px;margin-bottom:13px;font-size:11px;letter-spacing:.08em;text-transform:uppercase;font-weight:800;color:#63809e}
.bom-component-section-label:after{content:'';height:1px;background:#e3eaf3;flex:1}
.bom-component-grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:15px}
.bom-component-grid>.field-half{grid-column:span 6}
.bom-component-grid>.field-third{grid-column:span 4}
.bom-component-grid>.field-full{grid-column:span 12}
.bom-component-footer{padding:11px 19px;background:#fafcff;border-top:1px solid #eaf0f8;color:#71849a;font-size:12px}
.bom-components-empty{border:2px dashed #b9d4f3;border-radius:15px;padding:38px 15px;text-align:center;background:#f8fbff}
.bom-components-empty i{font-size:39px;color:#6594c9;display:block;margin-bottom:10px}
.bom-components-empty strong{display:block;font-size:16px;color:#284969}
.bom-components-empty p{color:#778da4;font-size:13px;margin:7px 0 15px}
.bom-components-add-bottom{width:100%;margin-top:19px;border:2px dashed #9ec4f4;background:#f4f9ff;color:#215b9e!important;min-height:54px;font-size:14px}
.bom-components-add-bottom:hover{background:#eaf3ff}
.bom-components-panel .bom-btn.danger{border:1px solid #ffd7d7}
@media(max-width:700px){.bom-component-grid>.field-half,.bom-component-grid>.field-third{grid-column:span 12}.bom-component-header,.bom-component-body{padding:14px}}
</style>
<div class="bom-wrap" x-data="bomForm()">
<div class="bom-head"><div class="d-flex align-items-center gap-3"><div class="bom-icon"><i class="bx bx-file"></i></div><div><h2 class="bom-title">{{ isset($bom) ? 'Edit Bill of Materials' : 'Create Bill of Materials' }}</h2><p class="bom-muted">Define your finished product and raw material requirements</p></div></div><a href="{{ route('production.boms.index') }}" class="bom-btn outline"><i class="bx bx-arrow-back"></i> Back to List</a></div>
@if($errors->any() || session('error'))<div class="bom-alert">{{ session('error') ?? 'Please correct the highlighted fields.' }}@if($errors->has('general')) {{ $errors->first('general') }} @endif</div>@endif
<form method="POST" action="{{ isset($bom) ? route('production.boms.update',$bom) : route('production.boms.store') }}" @submit.prevent="submitForm">
@csrf
@if(isset($bom))
@method('PUT')
@endif
<div class="bom-layout"><div>
<div class="bom-card"><div class="bom-card-head"><div class="bom-icon" style="width:40px;height:40px;font-size:20px"><i class="bx bx-detail"></i></div><div><h4>BOM Information</h4><p class="bom-muted">Recipe identification and output product</p></div></div><div class="bom-card-body">
<div class="bom-form-grid">
<div><label class="bom-label" for="bom_number">BOM Number <span class="text-danger">*</span></label><input id="bom_number" name="bom_number" required value="{{ old('bom_number',$bom->bom_number ?? '') }}" placeholder="e.g. BOM-ATT-001">@error('bom_number')<div class="bom-field-error">{{ $message }}</div>@enderror</div>
<div><label class="bom-label" for="bom_name">BOM Name <span class="text-danger">*</span></label><input id="bom_name" name="name" required value="{{ old('name',$bom->name ?? '') }}" placeholder="e.g. Premium Atta Recipe">@error('name')<div class="bom-field-error">{{ $message }}</div>@enderror</div>
<div><label class="bom-label" for="product_id">Finished Product</label><select id="product_id" name="product_id"><option value="">Select finished product</option>@foreach(($products ?? collect()) as $product)<option value="{{ $product->id }}" @selected((string)old('product_id',$bom->product_id ?? '') === (string)$product->id)>{{ $product->product_name ?? $product->id }}</option>@endforeach</select>@error('product_id')<div class="bom-field-error">{{ $message }}</div>@enderror</div>
<div><label class="bom-label" for="version">Version</label><input id="version" type="number" min="1" name="version" value="{{ old('version',$bom->version ?? 1) }}">@error('version')<div class="bom-field-error">{{ $message }}</div>@enderror</div>
<div><label class="bom-label" for="unit_of_measure">Unit of Measure</label><input id="unit_of_measure" name="unit_of_measure" value="{{ old('unit_of_measure',$bom->unit_of_measure ?? '') }}" placeholder="e.g. ton or kg">@error('unit_of_measure')<div class="bom-field-error">{{ $message }}</div>@enderror</div>
</div>
<div class="bom-checks"><label><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked((bool)old('is_active',$bom->is_active ?? 1))> Active BOM</label><label><input type="hidden" name="is_default" value="0"><input type="checkbox" name="is_default" value="1" @checked((bool)old('is_default',$bom->is_default ?? 0))> Default BOM</label></div>
</div></div>
<div class="bom-card bom-components-panel">
  <div class="bom-card-head" style="justify-content:space-between;flex-wrap:wrap">
    <div class="d-flex align-items-center gap-3">
      <div class="bom-icon" style="width:45px;height:45px;font-size:23px"><i class="bx bx-layer-plus"></i></div>
      <div><h4>Raw Material Components</h4><p class="bom-muted">Build your recipe by adding each required input separately</p></div>
    </div>
    <button type="button" class="bom-btn primary" @click="addItem"><i class="bx bx-plus-circle"></i> Add Component</button>
  </div>
  <div class="bom-card-body">
    <div class="bom-components-intro">
      <span><i class="bx bx-info-circle me-1"></i> Each component is an input ingredient, not a finished good or by-product.</span>
      <span class="bom-badge" x-text="items.length + (items.length === 1 ? ' component' : ' components')"></span>
    </div>
    <div x-show="items.length === 0" class="bom-components-empty">
      <i class="bx bx-package"></i>
      <strong>No components added yet</strong>
      <p>Start by selecting a raw material such as Wheat, then specify its required quantity.</p>
      <button type="button" class="bom-btn primary" @click="addItem"><i class="bx bx-plus"></i> Add First Component</button>
    </div>
    <div class="bom-component-list">
      <template x-for="(item,index) in items" :key="item.key">
        <div class="bom-component-card">
          <div class="bom-component-header">
            <div class="bom-component-ident">
              <div class="bom-component-number" x-text="String(index+1).padStart(2,'0')"></div>
              <div><strong x-text="item.component_name || 'New Raw Material'"></strong><small x-text="'Component '+(index+1)+' of '+items.length"></small></div>
            </div>
            <button type="button" class="bom-btn danger" @click="removeItem(index)" aria-label="Remove component"><i class="bx bx-trash"></i> Remove</button>
          </div>
          <div class="bom-component-body">
            <div class="bom-component-section-label"><i class="bx bx-box"></i> Material identity</div>
            <div class="bom-component-grid">
              <div class="field-half">
                <label class="bom-label">Raw Material</label>
                <select :name="`items[${index}][raw_material_id]`" x-model="item.raw_material_id">
                  <option value="">Select raw material</option>
                  @foreach(($rawMaterials ?? collect()) as $material)
                  <option value="{{ $material->id }}">{{ $material->name ?? $material->id }}</option>
                  @endforeach
                </select>
              </div>
              <div class="field-half">
                <label class="bom-label">Component Name <span class="text-danger">*</span></label>
                <input :name="`items[${index}][component_name]`" x-model="item.component_name" required placeholder="e.g. Wheat">
              </div>
            </div>
            <div class="bom-component-section-label mt-4"><i class="bx bx-calculator"></i> Quantity &amp; processing details</div>
            <div class="bom-component-grid">
              <div class="field-third">
                <label class="bom-label">Quantity Required <span class="text-danger">*</span></label>
                <input :name="`items[${index}][quantity_required]`" x-model="item.quantity_required" type="number" step="0.0001" min="0.0001" required placeholder="0.0000">
              </div>
              <div class="field-third">
                <label class="bom-label">Unit of Measure</label>
                <input :name="`items[${index}][unit_of_measure]`" x-model="item.unit_of_measure" placeholder="e.g. kg or ton">
              </div>
              <div class="field-third">
                <label class="bom-label">Expected Wastage (%)</label>
                <input :name="`items[${index}][wastage_percentage]`" x-model="item.wastage_percentage" type="number" step="0.01" min="0" max="100" placeholder="0">
              </div>
              <div class="field-third">
                <label class="bom-label">Sort Order</label>
                <input :name="`items[${index}][sort_order]`" x-model="item.sort_order" type="number" min="0">
              </div>
            </div>
          </div>
          <div class="bom-component-footer"><i class="bx bx-check-circle me-1"></i> This component will be included in the saved BOM recipe.</div>
        </div>
      </template>
    </div>
    <button type="button" class="bom-btn bom-components-add-bottom" @click="addItem"><i class="bx bx-plus-circle"></i> Add Another Raw Material Component</button>
    @error('items')<div class="bom-field-error mt-2">{{ $message }}</div>@enderror
  </div>
</div>
</div><div><div class="bom-sidebar"><div class="bom-card"><div class="bom-card-head"><div class="bom-icon" style="width:40px;height:40px;font-size:20px"><i class="bx bx-check-shield"></i></div><div><h4>Save BOM</h4><p class="bom-muted">Review before saving</p></div></div><div class="bom-card-body"><button type="submit" class="bom-btn primary w-100 mb-2" :disabled="loading"><i class="bx bx-save"></i><span x-text="loading ? 'Saving...' : (editing ? 'Update BOM' : 'Create BOM')"></span></button><a href="{{ route('production.boms.index') }}" class="bom-btn outline w-100">Cancel</a><p class="bom-help">A BOM is a recipe. Creating one does not deduct wheat stock; stock changes when production is executed.</p></div></div></div></div>
</div></form></div>
@push('scripts')
<script>
function bomForm(){return {
 loading:false,editing:@json(isset($bom)),nextKey:1000,
 items:@json(old('items',isset($bom) ? $bom->items->map(fn($i)=>['id'=>$i->id,'raw_material_id'=>$i->raw_material_id ?? '','component_name'=>$i->component_name ?? '','quantity_required'=>$i->quantity_required ?? '','unit_of_measure'=>$i->unit_of_measure ?? '','wastage_percentage'=>$i->wastage_percentage ?? '0','sort_order'=>$i->sort_order ?? '0'])->values()->all() : [] )).map((i,index)=>({...i,key:index})),
 addItem(){this.items.push({key:this.nextKey++,raw_material_id:'',component_name:'',quantity_required:'',unit_of_measure:'',wastage_percentage:'0',sort_order:String(this.items.length)})},
 removeItem(index){this.items.splice(index,1)},
 submitForm(e){if(this.loading)return;this.loading=true;e.target.submit()}
}}
</script>
@endpush
@endsection
