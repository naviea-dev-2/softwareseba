@extends('inc.master')
@section('head')
<title>Add Product</title>
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
.product-ui{max-width:1500px;margin:auto;padding:26px 18px 48px;color:#132b4b}
.product-head{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px}
.product-title{font-size:27px;font-weight:750;margin:0;color:#132b4b}
.product-subtitle{color:#71829a;margin:5px 0 0}
.product-icon{width:54px;height:54px;display:grid;place-items:center;background:#e7f1ff;color:#2563eb;border-radius:15px;font-size:27px}
.product-panel{background:#fff;border:1px solid #e1e9f3;border-radius:16px;box-shadow:0 8px 28px rgba(27,54,90,.045);margin-bottom:20px;overflow:hidden}
.product-panel-header{padding:21px 24px;border-bottom:1px solid #eaf0f7;display:flex;align-items:center;gap:13px}
.product-panel-header h5{font-size:17px;font-weight:750;margin:0;color:#132b4b}
.product-panel-header p{font-size:13px;color:#71829a;margin:3px 0 0}
.product-panel-body{padding:24px}
.product-ui .btn{border-radius:9px;font-weight:600;padding:10px 17px}
.product-ui .btn-primary{background:#2463eb;border-color:#2463eb}
.product-ui .form-label{font-size:13px;font-weight:700;color:#34465f;margin-bottom:8px}
.product-ui .form-control,.product-ui .form-select,.product-ui .select2-container--bootstrap-5 .select2-selection{border:1px solid #d9e3ef;border-radius:9px;min-height:45px;background-color:#fff}
.product-ui .form-control:focus,.product-ui .form-select:focus{border-color:#80aaff;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.product-ui .select2-container{width:100%!important}
.product-ui .field-hint{font-size:12px;color:#8090a3;margin-top:6px}
.product-ui .section-kicker{font-size:12px;letter-spacing:.08em;text-transform:uppercase;font-weight:800;color:#2563eb;margin-bottom:17px}
.product-ui .product-side{position:sticky;top:20px}
.product-ui .image-preview{height:190px;max-width:100%;object-fit:contain;border-radius:12px;border:1px dashed #cbd8e8;background:#f7faff;padding:12px}
.product-ui .product-table thead th{background:#2b415a;color:#fff;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border:0;padding:16px 14px;white-space:nowrap}
.product-ui .product-table tbody td{padding:14px;vertical-align:middle;border-color:#e9eff6;font-size:13px}
.product-ui .product-table tbody tr:hover{background:#f7faff}
.product-ui .dataTables_filter input{margin-left:8px;border:1px solid #d9e3ef;border-radius:9px;padding:9px 12px;min-width:270px}
.product-ui .dataTables_length select{border:1px solid #d9e3ef;border-radius:8px;padding:7px}
.product-ui .dataTables_wrapper .dataTables_paginate .paginate_button{border-radius:8px!important}
.product-ui .error-show .select2-selection{border-color:#dc3545!important}
@media(max-width:767px){.product-ui{padding:16px 10px}.product-panel-body{padding:17px}.product-panel-header{padding:17px}.product-title{font-size:23px}.product-ui .dataTables_filter input{min-width:0;width:180px}}
</style>
@endsection
@section('content')
@php $b_type_id = auth()->user()->business->business_type_id; @endphp
<div class="product-ui">
  <div class="product-head">
    <div class="d-flex align-items-center gap-3">
      <div class="product-icon"><i class="bx bx-plus-circle"></i></div>
      <div><h2 class="product-title">Add New Product</h2><p class="product-subtitle">Create a product with details, pricing and image</p></div>
    </div>
    <a href="{{ route('product.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Back to Products</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger rounded-3"><strong>Please correct the following:</strong>
      <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="0">
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="product-panel">
          <div class="product-panel-header"><div class="product-icon" style="width:42px;height:42px;font-size:21px"><i class="bx bx-info-circle"></i></div>
            <div><h5>Basic Information</h5><p>Product identity and classification</p></div>
          </div>
          <div class="product-panel-body">
            <div class="row g-3">
              @if($b_type_id != 16)
              <div class="col-md-6 @error('manufacture') error-show @enderror">
                <label class="form-label">Manufacturer <span class="text-danger">*</span></label>
                <select id="manufacture" name="manufacture" class="form-control select_manufacture"><option value="">Select manufacturer</option></select>
                @error('manufacture')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>
              @endif
              <div class="col-md-6 @error('category') error-show @enderror">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select id="add_category" name="category" class="form-control select_category"><option value="">Select category</option></select>
                @error('category')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>
              @if($b_type_id != 16)
              <div class="col-md-6 @error('brand') error-show @enderror">
                <label class="form-label">Brand</label>
                <select name="brand" class="form-control select2-brand"><option value="">Select brand</option></select>
                @error('brand')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>
              @endif
              <div class="col-md-6">
                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}" placeholder="e.g. Premium Atta" required>
                @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Product Code / SKU</label>
                <input type="text" name="product_code" class="form-control @error('product_code') is-invalid @enderror" value="{{ old('product_code') }}" placeholder="e.g. ATT-001">
                @error('product_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              @if($b_type_id != 16)
              <div class="col-md-6">
                <label class="form-label">Batch / DDR No.</label>
                <input type="text" name="batch_no" class="form-control @error('batch_no') is-invalid @enderror" value="{{ old('batch_no') }}">
                @error('batch_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Manufacture Date</label>
                <input type="text" name="manufacture_date" class="form-control datepicker @error('manufacture_date') is-invalid @enderror" value="{{ old('manufacture_date', date('Y-m-d')) }}">
                @error('manufacture_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Expiry Date</label>
                <input type="text" name="exipre_date" class="form-control datepicker @error('exipre_date') is-invalid @enderror" value="{{ old('exipre_date') }}" placeholder="Optional">
                <div class="field-hint">Enter only when a meaningful product expiry date exists.</div>
                @error('exipre_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              @endif
              @if($b_type_id == 6 || $b_type_id == 15)
              <div class="col-md-6"><label class="form-label">IMEI 1</label><input type="text" name="imei_1" class="form-control @error('imei_1') is-invalid @enderror" value="{{ old('imei_1') }}">@error('imei_1')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
              <div class="col-md-6"><label class="form-label">IMEI 2</label><input type="text" name="imei_2" class="form-control @error('imei_2') is-invalid @enderror" value="{{ old('imei_2') }}">@error('imei_2')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
              @endif
              @if($b_type_id == 5)
              <div class="col-md-6"><label class="form-label">Product Type</label><select name="p_type" class="form-control select2-p_type"><option value="">Select product type</option></select></div>
              <div class="col-md-6"><label class="form-label">Generics</label><select name="generic" class="form-control select2-generic"><option value="">Select generic</option></select></div>
              @endif
            </div>
          </div>
        </div>

        <div class="product-panel">
          <div class="product-panel-header"><div class="product-icon" style="width:42px;height:42px;font-size:21px"><i class="bx bx-purchase-tag"></i></div>
            <div><h5>Pricing &amp; Units</h5><p>Set taxes, discount, purchase and sale prices</p></div>
          </div>
          <div class="product-panel-body">
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">Tax</label>
                <select name="tax" class="form-select @error('tax') is-invalid @enderror"><option value="">Select tax</option>
                  @foreach($taxes as $tax)
                  <option value="{{ $tax->id }}" @selected((string)old('tax') === (string)$tax->id)>{{ $tax->name }}{{ $tax->rate_type == 'percent' ? ' ('.$tax->rate.'%)' : '' }}</option>
                  @endforeach
                </select>@error('tax')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6 @error('unit') error-show @enderror"><label class="form-label">Unit</label>
                <select name="unit" class="add-select2-unit form-control"><option value="">Select unit</option></select>
                @error('unit')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Discount Type</label>
                <select id="add_discount_type" name="discount_type" class="form-select">
                  <option value="percent" @selected(old('discount_type','percent') === 'percent')>Percent</option>
                  <option value="fixed" @selected(old('discount_type') === 'fixed')>Fixed</option>
                </select>
              </div>
              <div class="col-md-6"><label class="form-label">Discount</label>
                <input id="add_product_discount" type="number" step="any" min="0" name="product_discount" class="form-control @error('product_discount') is-invalid @enderror" value="{{ old('product_discount',0) }}">
                @error('product_discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Purchase Price <span class="text-danger">*</span></label>
                <input type="number" step="any" min="0" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price',0) }}" required>
                @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Sale Price <span class="text-danger">*</span></label>
                <input type="number" step="any" min="0" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price',0) }}" required>
                @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>
        @if($b_type_id != 16)
        <div class="product-panel">
          <div class="product-panel-header"><div class="product-icon" style="width:42px;height:42px;font-size:21px"><i class="bx bx-slider"></i></div>
            <div><h5>Product Attributes</h5><p>Add product variants and attribute values</p></div>
          </div>
          <div class="product-panel-body">
            <button type="button" class="btn btn-outline-primary js-add-p-attribute mb-3"><i class="bx bx-plus me-1"></i> Add Attribute</button>
            <div id="ajax-attribute-list">
              @if(old('attribute'))
                @foreach(old('attribute') as $old_k=>$old_attribute)
                  @php
                    $f_attribute_set = \App\Models\Inventory\AttributeSet::find($old_attribute);
                  @endphp
                  <div class="row g-3 mb-3">
                    <div class="col-md-5">
                      <label class="form-label">Attribute</label>
                      <select name="attribute[]" class="p-attribute chosen-select form-control">
                        @foreach(\App\Models\Inventory\AttributeSet::all() as $attribute_set)
                        <option value="{{ $attribute_set->id }}" @selected((string)$attribute_set->id === (string)$old_attribute)>{{ $attribute_set->title }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-5">
                      <label class="form-label">Value</label>
                      <select name="attribute_value[{{ $old_attribute }}][]" class="p-attribute-value chosen-select form-control">
                        @foreach($f_attribute_set?->attributes ?? [] as $attribute)
                        <option value="{{ $attribute->id }}" @selected((string)old('attribute_value.'.$old_attribute.'.0') === (string)$attribute->id)>{{ $attribute->title }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                      <button type="button" class="btn btn-outline-danger attribute-set-remove"><i class="bx bx-trash"></i></button>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
          </div>
        </div>
        @endif
      </div>

      <div class="col-lg-4">
        <div class="product-side">
          <div class="product-panel">
            <div class="product-panel-header"><div class="product-icon" style="width:42px;height:42px;font-size:21px"><i class="bx bx-image"></i></div>
              <div><h5>Product Image</h5><p>Upload a clear product photo</p></div>
            </div>
            <div class="product-panel-body text-center">
              <img class="display-upload-img-add image-preview w-100 mb-3" src="{{ asset('public/images/No-image.jpg') }}" alt="Product image preview">
              <input type="file" name="product_image" accept="image/*" class="form-control upload-img-add @error('product_image') is-invalid @enderror">
              @error('product_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <div class="field-hint text-start">Recommended: square JPG, PNG or WebP image.</div>
            </div>
          </div>
          <div class="product-panel">
            <div class="product-panel-header"><div class="product-icon" style="width:42px;height:42px;font-size:21px"><i class="bx bx-check-shield"></i></div>
              <div><h5>Save Product</h5><p>Review details before saving</p></div>
            </div>
            <div class="product-panel-body">
              <button type="submit" class="btn btn-primary w-100 mb-2"><i class="bx bx-save me-1"></i> Save Product</button>
              <a href="{{ route('product.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
              <p class="field-hint mt-3 mb-0">Fields marked * are required.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection
@section('script')


<script>
    $(".datepicker").flatpickr();
    var row_p = 1;
    var edit_row_p=1;
    $('.select_category').select2({
        theme: "bootstrap-5",
      placeholder: 'Select Category',
      allowClear: true,
      width:'100%',
      dropdownAutoWidth : true,
      containerCssClass: 'select-sm',
      ajax: {
        url: '{{route('select2.product.categories')}}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            value: $.trim(params.term),
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });
    @if(old('category'))
    @php
        $o_category = \App\Models\Inventory\Category::find(old('category'))
    @endphp
    var category_option = new Option("{{ $o_category->name }}","{{ $o_category->id }}", true, true);
    $('.select_category').append(category_option).trigger('change');
    @endif

    $('#manufacture').select2({
        theme: "bootstrap-5",
      placeholder: 'Select',
      allowClear: true,
      width:'100%',
      dropdownAutoWidth : true,
      containerCssClass: 'select-sm',
      ajax: {
        url: '{{route('select2.manufactures')}}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            value: $.trim(params.term),
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });
    @if(old('manufacture'))
    @php
        $o_manufacture = \App\Models\Inventory\Manufature::find(old('manufacture'))
    @endphp
    var manufacture_option = new Option("{{ $o_manufacture->name }}","{{ $o_manufacture->id }}", true, true);
    $('#manufacture').append(manufacture_option).trigger('change');
    @endif


    $('.select2-brand').select2({
        theme: "bootstrap-5",
      placeholder: 'Select Brand',
      allowClear: true,
      width:'100%',
      dropdownAutoWidth : true,
      containerCssClass: 'select-sm',
      ajax: {
        url: '{{route('select2.product.brands')}}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            value: $.trim(params.term),
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });
    @if(old('brand'))
    console.log("{{ old('brand') }}");
    @php
        $o_brand = \App\Models\Inventory\Brand::find(old('brand'));
    @endphp
    console.log("{{ $o_brand }}");
    var brand_option = new Option("{{ $o_brand?->name }}","{{ $o_brand?->id }}", true, true);
    $('.select2-brand').append(brand_option).trigger('change');
    @endif

    $('.add-select2-unit').select2({
        theme: "bootstrap-5",
        placeholder: 'Select Unit',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.category.units')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {

                value: $.trim(params.term),
            };
            },
            processResults: function (response) {
            return {
                results: response
            };
            },
            cache: true
        }
    });
    @if(old('unit'))
    @php
        $o_unit = \App\Models\Inventory\Unit::find(old('unit'))
    @endphp
    var unit_option = new Option("{{ $o_unit->name }}","{{ $o_unit->id }}", true, true);
    $('.add-select2-unit').append(unit_option).trigger('change');
    @endif

    $('.select2-p_type').select2({
        theme: "bootstrap-5",
        placeholder: 'Select',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.p_types')}}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                value: $.trim(params.term),
            };
            },
            processResults: function (response) {
            return {
                results: response
            };
            },
            cache: true
        }
    });
    @if(old('p_type'))
        @foreach(old('p_type') as $p_type_id)
        @php
            $p_type = \App\Models\Inventory\ProductType::find($p_type_id);
        @endphp
        var p_type_option = new Option("{{ $p_type->name }}","{{ $p_type->id }}", true, true);
        $('.select2-p_type').append(p_type_option).trigger('change');
        @endforeach
    @endif
    @if($b_type_id == 5)
        $('.select2-generic').select2({
            theme: "bootstrap-5",
            placeholder: 'Select',
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            containerCssClass: 'select-sm',
            ajax: {
                url: '{{route('select2.generics')}}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    value: $.trim(params.term),
                };
                },
                processResults: function (response) {
                return {
                    results: response
                };
                },
                cache: true
            }
        });
        @if(old('generic'))
            @foreach(old('generic') as $generic_id)
            @php
                $generic = \App\Models\Inventory\Generic::find($generic_id);
            @endphp
            var generic_option = new Option("{{ $generic->name }}","{{ $generic->id }}", true, true);
            $('.select2-generic').append(generic_option).trigger('change');
            @endforeach
        @endif
    @endif



    $(document).on('change','.upload-img-add',function(){
        var files = $(this).get(0).files;
        var reader = new FileReader();
        if (!files || !files[0]) return;
        reader.readAsDataURL(files[0]);
        var arg=this;
        reader.addEventListener("load", function(e) {
            var image = e.target.result;
            $('.display-upload-img-add').attr('src', image);
        });
    });



    $(document).on('click','.minus-btn-data',function(){
        $(this).parent().parent().remove();
    });


    $(document).on("click", ".js-add-p-attribute", function () {
        var id = $(this).attr("data-id");
        $.ajax({
            type: "GET",
            url: '{{ url('/') }}' + "/addNewProductAttribute",
            data: { is_new: 1, id: 0 },
            success: function (response) {
                 console.log(response);
                $("#DataEntry_formId").show();
                $("#ajax-attribute-list").append(response);
                var msgType = response.msgType;
                var msg = response.msg;
                // if (msgType == "success") {
                //     onSuccessMsg(msg);
                // } else {
                //     onErrorMsg(msg);
                // }
            },
        });
    });
    $(document).on("click", ".attribute-set-remove", function () {
        $(this).parent().parent().parent().parent().remove();
    });
     $(document).on("change", ".p-attribute", function () {
        console.log(this);
        var id = $(this).val();
        var res = this;
        $.ajax({
            type: "GET",
            url: "{{ url('/') }}" + "/getAttributeValue",
            data: { id: id },
            success: function (response) {
                // console.log(id);
                console.log(response);
                if (response.status != "no") {
                    $(res)
                        .parent()
                        .parent()
                        .parent()
                        // .parent()
                        .find(".p-attribute-value")
                        .html(response.data);
                    $(res)
                        .parent()
                        .parent()
                        .parent()
                        .find(".p-attribute-value")
                        .attr("name", response.set_name);
                }
            },
        });
    });
    @if( $errors->count() > 0)
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
    @if(Session::has('errors'))
        @foreach (Session::get('errors') as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>
@endsection
