@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Edit Product</title>
<style>
    label{
       font-size: 1rem;
    }
</style>
@endsection
@section('content')
<div class="content-area">
    {{-- <div class="container-fluid">
        <div class="row row-card-one">
            <div class="col-sm-12 ">
                <div class="row report-title">
                    <h4 class=""><b>Edit Product</b></h4>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container pt-0">
        <h4 class="text-center"><b>Edit Product</b></h4>
        <div class="row row-card-one">
            <div class="col-sm-12">
                <!-- start form here -->
                <form method="POST" action="{{route('product.update')}}" enctype="multipart/form-data">
                    @csrf
                    @php
                        $b_type_id = auth()->user()->business->business_type_id;
                    @endphp
                    <div class="row">
                        <input type="hidden" value="{{ $product->id }}" name="id" id="edit_data_id" required>
                        @if($b_type_id != 16)
                        <div class="col-sm-4 @error('manufacture') error-show @enderror">
                            <label for=""><b>Manufacturer</b> *</label>
                            <select id="manufacture" class="form-control select_manufacture" name="manufacture">
                                <option value="">-- Select One --</option>
                            </select>
                        </div>
                        @endif
                        <div class="col-sm-4  @error('category') error-show @enderror">
                            <label for=""><b>Category</b> *</label>
                            <select class="form-control select_category" id="category" name="category">
                                <option>-- Select One --</option>


                            </select>
                        </div>
                        @if($b_type_id != 16)
                        <div class="col-sm-4 @error('brand') error-show @enderror">
                            <label for=""><b>Brand</b> *</label>
                            <select class="form-control select2-brand" id="brand" name="brand">
                                <option>-- Select One --</option>

                            </select>
                        </div>
                        @endif
                        <div class="col-sm-4">
                            <label for=""> <b>Product Name</b> *</label>
                            <input @error('product_name')
                            style="border:1px solid red!important;"
                            @enderror type="text" class=" form-control " id="product_name" name="product_name" value="{{ old('product_name') ?? $product->product_name }}" autocomplete="off">
                        </div>
                        <div class="col-sm-4">
                            <label for=""> <b>Sku</b> *</label>
                            <input @error('product_code')
                            style="border:1px solid red!important;"
                            @enderror type="text" class=" form-control " value="{{ old('product_code') ?? $product->product_code }}" id="product_code" name="product_code" autocomplete="off">
                        </div>
                        @if($b_type_id != 16)
                        <div class="col-sm-4">
                            <label for=""><b>Batch/DDR No.</b></label>
                            <input @error('batch_no')
                            style="border:1px solid red!important;"
                            @enderror   type="text" class=" form-control " name="batch_no" autocomplete="off" value="{{ old('batch_no') ?? $product->batch_no }}">
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>Manufacture Date</b></label>
                            <input @error('manufacture_date')
                            style="border:1px solid red!important;"
                            @enderror   type="text" class=" form-control datepicker" name="manufacture_date" autocomplete="off" value="{{ old('manufacture_date') ?? $product->manufacture_date }}">
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>Expire Date</b></label>
                            <input @error('exipre_date')
                            style="border:1px solid red!important;"
                            @enderror   type="text" class=" form-control datepicker" name="exipre_date" autocomplete="off" value="{{ old('exipre_date') ??  $product->exipre_date }}">
                        </div>
                        @endif
                        @if($b_type_id == 6 || $b_type_id == 15)
                        <div class="col-sm-6">
                            <label for=""><b>IMEI 1</b></label>
                            <input @error('imei_1') style="border:1px solid red!important;"
                            @enderror type="text" class=" form-control" name="imei_1" autocomplete="off" value="{{ old('imei_1',$product->imei_1) }}">
                        </div>
                        <div class="col-sm-6">
                            <label for=""><b>IMEI 2</b></label>
                            <input @error('imei_2') style="border:1px solid red!important;"
                            @enderror type="text" class=" form-control" name="imei_2" autocomplete="off" value="{{ old('imei_2',$product->imei_2) }}">
                        </div>
                        @endif
                        @if($b_type_id == 5 )
                        <div class="col-sm-3">
                            <label for=""><b>Product Type</b></label>
                            <select id="p_type" @error('p_type')
                            style="border:1px solid red!important;"
                            @enderror name="p_type" class="form-control select2-p_type">
                                <option value="">Select</option>

                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for=""><b>Generics</b></label>
                            <select @error('generic')
                            style="border:1px solid red!important;"
                            @enderror id="generic" name="generic" class="form-control select2-generic">
                                <option value="">Select</option>

                            </select>
                        </div>

                        @endif
                        <div class="col-sm-3">
                            <label for=""><b>Discount Type</b></label>
                            <select
                            id="edit_discount_type" name="discount_type" class="form-control ">
                                @if(old('discount_type'))
                                <option @if(old('discount_type') == "percent") selected @endif value="percent">Percent</option>
                                <option @if(old('discount_type') == "fixed") selected @endif value="fixed">Fixed</option>
                                @else
                                <option @if($product->discount_type == "percent") selected @endif value="percent">Percent</option>
                                <option @if($product->discount_type == "fixed") selected @endif value="fixed">Fixed</option>
                                @endif
                            </select>

                        </div>
                        <div class="col-sm-3">
                            <label for=""><b>Discount</b></label>
                            <input id="edit_product_discount" type="number" class=" form-control " name="product_discount" value="{{ old('discount') ?? $product->discount }}" autocomplete="off">
                        </div>
                        <div class="col-sm-3">
                            <label for=""><b>Tax</b></label>
                            <select id="tax" name="tax" class="form-control ">
                                <option value="">Select Tax</option>
                                @foreach ($taxes as $tax)
                                @if(old('tax'))
                                <option  @if(old('tax') == $tax->id) selected @endif value="{{ $tax->id }}">{{ $tax->name.($tax->rate_type == "percent" ? '('.$tax->rate."%".')' : '') }}</option>
                                @else
                                 <option  @if($product->tax_id == $tax->id) selected @endif value="{{ $tax->id }}">{{ $tax->name.($tax->rate_type == "percent" ? '('.$tax->rate."%".')' : '') }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for=""><b>Unit</b></label>
                            <select class="add-select2-unit form-control mb-0" name="unit"></select>
                        </div>
                        @if($product->atttribute_sets->count() == 0)
                        <div class="col-sm-4">
                            <label for=""><b>Purchase Price</b></label>
                            <input @error('purchase_price') style="border:1px solid red!important;"
                            @enderror   type="number" step="any" class=" form-control " name="purchase_price" autocomplete="off" value="{{ old('purchase_price') ??  $product->purchase_price }}">
                        </div>
                        <div class="col-sm-4">
                            <label for=""><b>Sale Price</b></label>
                            <input @error('sale_price') style="border:1px solid red!important;"
                            @enderror   type="number" step="any" class=" form-control " name="sale_price" autocomplete="off" value="{{ old('sale_price') ?? $product->sale_price }}">
                        </div>
                        @endif
                    </div>
                     @if($b_type_id != 16)
                    <div class="row mt-3 mb-3">
                        @if($product->atttribute_sets->count() == 0)
                        <div class="col-md-2">
                            <button type="button" data-id="{{ $product->id }}" href="javascript:void(0);" class="btn border js-add-p-attribute">{{ __('Add Attribute') }}</button>
                        </div>
                        @else
                            <div class="col-md-1 delete_all_btn" style="display: none;">
                                <button  type="button" style="padding: 5px;" data-id="{{ $product->id }}" class="btn border btn-danger  btn-trigger-delete-selected-variations" style="" type="button">
                                    <span class="icon-tabler-wrapper icon-left">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 7l16 0"></path>
                                        <path d="M10 11l0 6"></path>
                                        <path d="M14 11l0 6"></path>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                        </svg>


                                    </span>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button  type="button" data-id="{{$product->id }}" href="javascript:void(0);" class="btn border js-edit-p-attribute-set" data-bs-toggle="modal" data-bs-target="#edit_attribute_set">{{ __('Edit Attribute') }}</button>
                            </div>
                            <div class="col-md-3 ml-2">
                                <button type="button" href="javascript:void(0);" class="btn border" data-bs-toggle="modal" data-bs-target="#add_new_variation_modal">{{ __('Add New Variation') }}</button>
                            </div>
                        @endif
                        @if($product->atttribute_sets->count() == 0)
                        <div class="col-md-12">
                             <div id="ajax-attribute-list">
                                @if(old('attribute'))
                                    @foreach (old('attribute') as $old_k=>$old_attribute)
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="attribute">{{ __('Attribute') }}</label>
                                                <select name="attribute[]" class="p-attribute chosen-select form-control">
                                                @php
                                                    $f_attribute_set = null;
                                                    $k=0;
                                                    $product_id=0;
                                                    $attribute_sets =\App\Models\Inventory\AttributeSet::get();
                                                @endphp
                                                @foreach($attribute_sets as $attribute_set)
                                                    @php
                                                        $check_set =  \App\Models\Inventory\ProductWithAttributSet::where('attribute_set_id',$attribute_set->id)->where('product_id',$product_id)->first();
                                                    @endphp
                                                    @if(!$check_set)
                                                    @php
                                                        if($attribute_set->id == $old_attribute){
                                                            $f_attribute_set = $attribute_set;
                                                        }
                                                    @endphp
                                                    <option {{ $attribute_set->id == $old_attribute ? "selected=selected" : '' }} value="{{ $attribute_set->id }}">
                                                        {{ $attribute_set->title }}
                                                    </option>
                                                    @php
                                                        $k++;
                                                    @endphp
                                                    @endif
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if($f_attribute_set)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="attribute_value">{{ __('Value') }}</label>
                                                <select name="attribute_value[{{ $f_attribute_set->id }}][]"  class="p-attribute-value chosen-select form-control">
                                                @foreach($f_attribute_set->attributes as $k=>$attribute)
                                                    <option {{ old('attribute_value')[ $f_attribute_set->id][0] == $attribute->id ? "selected=selected" : '' }} value="{{ $attribute->id }}">
                                                        {{ $attribute->title }}
                                                    </option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-1">
                                            <div class="form-group">
                                                <label for="attribute_value"></label>
                                                <div class="form-control mt-2" style="margin-right: 2px;padding: 7px!important;background: transparent;border: navajowhite;">
                                                    <a style="color:red;" class="attribute-set-remove" href="javascript:void(0);"><i class="bx bx-trash"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="col-md-12">
                             <div class="table-responsive">
                                <table class="table table-borderless table-theme" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="checkboxlist text-center"><input class="tp-check-all checkAll" type="checkbox"></th>
                                            <th class="text-left">{{ __('ID') }}</th>
                                            @foreach($product->atttribute_sets as $atttribute_set)
                                            <th class="text-left">{{ $atttribute_set->attribute->title }}</th>
                                            @endforeach
                                            <th class="text-left">{{ __('Purchase Price') }}</th>
                                             <th class="text-left">{{ __('Sale Price') }}</th>
                                            {{-- <th class="text-center">{{ __('Quantity') }}</th> --}}
                                            <th class="text-left">{{ __('IS Defualt') }}</th>
                                            <th class="text-left">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td></td>
                                            <td></td>


                                            @foreach($product->atttribute_sets as $atttribute_with_set)
                                               @php
                                                   $atttribute_set = $atttribute_with_set->attribute;
                                               @endphp
                                            <td>
                                                <div class="form-group">

                                                    <select name="search_attribute_value[{{ $atttribute_set->id }}][]"  class="search-attribute-value chosen-select form-control">
                                                    @foreach($atttribute_set->attributes as $k=>$attribute)
                                                        <option {{ $attribute->is_default == 1 ? "selected=selected" : '' }} value="{{ $attribute->id }}">
                                                            {{ $attribute->title }}
                                                        </option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            @endforeach

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr> --}}
                                        @foreach ($product->variations as $variation)
                                        <tr>
                                            <td class="checkboxlist text-center"><input name="item_ids[]" value="{{ $variation->id }}" class="tp-checkbox selected_item" type="checkbox"></td>
                                            <td>{{ $variation->id }}</td>

                                            @foreach ($product->atttribute_sets as $atttribute_set)
                                            <td>

                                                {{ findAttr($variation,$atttribute_set) }}
                                            </td>
                                            @endforeach
                                            <td>{{ $variation->product->purchase_price }}</td>
                                            <td>{{ $variation->product->sale_price }}</td>
                                            {{-- <td>{{ $variation->product->is_stock == 1 ? $variation->product->stock_qty : '∞' }}</td> --}}
                                            <td>
                                                <label class="form-check form-check-inline form-check-single">
                                                    <input  class="form-check-input is_default_change" type="radio" name="is_default" value="{{ $variation->id }}" @if($variation->is_default == 1)checked @endif>

                                                    <span class="form-check-label"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a
                                                    class="btn btn-primary btn-trigger-edit-product-version"
                                                    data-id="{{ $variation->id }}"
                                                    data-bs-toggle="tooltip"
                                                    href="javascript:void(0);"
                                                    title="{{ __('Edit Variation Item') }}"
                                                >
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a
                                                    class="btn-trigger-delete-version btn btn-danger"
                                                    data-id="{{ $variation->id }}"
                                                    data-bs-toggle="tooltip"
                                                    href="javascript:void(0);"
                                                    title="{{ __('Delete') }}"
                                                >
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        @endif

                    </div>
                    @endif
                    <div class="row">

                        <div class="col-sm-6">
                            <label for=""><b>Product Image</b></label>
                            <div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 150px;">
                                <img class="display-upload-img" style="width: 150px;height: 70px;" id="edit_image_show" src="{{ $product->image_show}}" alt="">
                                <input type="file" name="product_image" class="form-control upload-img" placeholder="Enter Activity Image" style="position: absolute;top: 0;opacity: 0;height: 100%;">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <br/>
                            <button class="btn btn-sm btn-primary mt-4 ">
                                <i class="fa fa-save pr-2"></i>Save
                            </button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
@if($product->productAttributeSets->count() > 0)
    <!--Edit Attribute Set Modal -->
    <div class="modal fade" id="edit_attribute_set" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form  novalidate="" data-validate="parsley" id="attribute_set_edit_formId">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __("Select attribute") }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            @php
                                $arribute_set_list=\App\Models\Inventory\AttributeSet::get();
                            @endphp
                            @foreach ($arribute_set_list as $atttribute_set)

                                <div class="col-md-3">
                                    <div class="tw_checkbox checkbox_group">
                                        <input id="tax{{ $atttribute_set->id }}" name="attribute[]" @if($product->productAttributeSets->where('id',$atttribute_set->id)->first()) checked @endif type="checkbox" value="{{ $atttribute_set->id }}">
                                        <label for="tax{{ $atttribute_set->id }}">{{ __($atttribute_set->title) }}</label>
                                        <span></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input value="{{ $product->id }}" type="text" name="RecordId" id="RecordId" class="d-none">

                    </div>
                    <div class="modal-footer" style="display: flex;flex-shrink: 0;flex-wrap: wrap;justify-content: space-between;align-items: center;">

                        <button type="button" class="btn" data-dismiss="modal" style="color:black;">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn_edit_form_attribute">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Add New Variation Modal -->
    <div class="modal fade modal-blur " id="add_new_variation_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-xl modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __("Add new variation") }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="variation-form-wrapper">
                        <form  novalidate="" data-validate="parsley" id="add_new_variation_formId">
                            @csrf
                        <div class="row">
                            @foreach ($product->productAttributeSets as $attribute_set)

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __($attribute_set->title) }}<span class="red">*</span></label>
                                        <select id="add_attribute_{{ $attribute_set->title }}" name="attribute[{{ $attribute_set->id }}]" class="chosen-select form-control">
                                        @foreach($attribute_set->attributes as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->title }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="cost_price">{{ __('Purchase Price') }}</label>
                                    <input value="" name="cost_price" id="cost_price" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="sale_price">{{ __('Sale Price') }}<span class="red">*</span></label>
                                    <input value="" name="sale_price" id="sale_price" type="text" class="form-control parsley-validated" data-required="true">
                                </div>
                            </div>
                            {{-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="old_price">{{ __('Old Price') }}</label>
                                    <input value="" name="old_price" id="old_price" type="text" class="form-control">
                                </div>
                            </div> --}}

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="sku">{{ __('SKU') }}</label>
                                    <input value="" name="sku" id="sku" type="text" class="form-control">
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_stock">{{ __('Manage Stock') }}</label>
                                    <select name="is_stock" id="is_stock" class="chosen-select form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-4 variation_stock_status">
                                <div class="form-group">
                                    <label for="stock_status_id">{{ __('Stock Status') }}</label>
                                    <select name="stock_status_id" id="stock_status_id" class="chosen-select form-control">
                                        <option value="1">{{ __('In Stock') }}</option>
                                        <option value="0">{{ __('Out Of Stock') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 variation_stock_qty" style="display:none;">
                                <div class="form-group">
                                    <label for="stock_qty">{{ __('Stock Quantity') }}</label>
                                    <input value="" name="stock_qty" id="stock_qty" type="number" class="form-control">
                                </div>
                            </div> --}}
                        </div>

                        {{-- <h3>Shipping Information</h3>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="actual_weight">{{ __('Weight') }}</label>
                                    <input value="" name="actual_weight" id="actual_weight" type="text" class="form-control parsley-validated">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="length">{{ __('Length') }}</label>
                                    <input value="" name="length" id="length" type="text" class="form-control parsley-validated">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="width">{{ __('Width') }}</label>
                                    <input value="" name="width" id="width" type="text" class="form-control parsley-validated">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="height">{{ __('Height') }}</label>
                                    <input value="" name="height" id="height" type="text" class="form-control parsley-validated">
                                </div>
                            </div>

                        </div> --}}
                        <input value="{{ $product->id }}" type="text" name="RecordId" id="RecordId" class="d-none">
                        </form>
                    </div>

                </div>
                <div class="modal-footer" style="display: flex;flex-shrink: 0;flex-wrap: wrap;justify-content: space-between;align-items: center;">

                    <button type="button" class="btn" data-dismiss="modal" style="color:black;">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn_add_new_variation_form">Save changes</button>
                </div>

            </div>
        </div>
    </div>
    <!--Edit Variation Modal -->
    <div class="modal fade modal-blur " id="edit_variation_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-xl modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __("Edit variation") }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="variation-form-wrapper">
                        <form  novalidate="" data-validate="parsley" id="edit_variation_formId">
                            @csrf
                            <div id="edit_ajax_data">

                            </div>



                        </form>
                    </div>

                </div>
                <div class="modal-footer" style="display: flex;flex-shrink: 0;flex-wrap: wrap;justify-content: space-between;align-items: center;">

                    <button type="button" class="btn" data-dismiss="modal" style="color:black;">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn_edit_variation_form">Save changes</button>
                </div>

            </div>
        </div>
    </div>
@endif
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>

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
        @php
            $o_brand = \App\Models\Inventory\Brand::find(old('brand'))
        @endphp
        var brand_option = new Option("{{ $o_brand->name }}","{{ $o_brand->id }}", true, true);
        $('.select2-brand').append(brand_option).trigger('change');
    @else
        $('#brand').append(brand_option).trigger('change');
        var brand_option = new Option("{{ $product->brand?->name }}","{{ $product->brand_id }}", true, true);
        $('#brand').append(brand_option).trigger('change');
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
    console.log("{{ old('manufacture') }}");
    @if(old('manufacture'))
        @php
             $o_manufacture = \App\Models\Inventory\Manufature::find(old('manufacture'))
        @endphp
         var manufacture_option = new Option("{{ $o_manufacture->name }}","{{ $o_manufacture->id }}", true, true);
        $('#manufacture').append(manufacture_option).trigger('change');

    @else
        var manufacture = new Option("{{ $product->manufacture?->name }}","{{ $product->manufacture_id }}", true, true);
        $('#manufacture').append(manufacture).trigger('change');
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
    @else
        var unit_option = new Option("{{ $product?->unit?->name }}","{{ $product?->unit_id }}", true, true);
        $('.add-select2-unit').append(unit_option).trigger('change');
    @endif
    $(document).on('change','.upload-img',function(){
        var files = $(this).get(0).files;
        var reader = new FileReader();
        reader.readAsDataURL(files[0]);
        var arg=this;
        reader.addEventListener("load", function(e) {
            var image = e.target.result;
            $(arg).parent().find('.display-upload-img').attr('src', image);
        });
    });
    // editInitUnit(1);
    @if(old('category'))
        @php
            $o_category = \App\Models\Inventory\Category::find(old('category'))
        @endphp
        var category_option = new Option("{{ $o_category->name }}","{{ $o_category->id }}", true, true);
        $('#category').append(category_option).trigger('change');
    @else
        var category_option = new Option("{{ $product->category?->name }}","{{ $product->category_id }}", true, true);
        $('#category').append(category_option).trigger('change');
        var brand_option = new Option("{{ $product->brand?->name }}","{{ $product->brand_id }}", true, true);
    @endif
    @if($b_type_id == 5)
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

            @php
                $p_type = \App\Models\Inventory\ProductType::find(old('p_type'));
            @endphp
            var p_type_option = new Option("{{ $p_type->name }}","{{ $p_type->id }}", true, true);
            $('.select2-p_type').append(p_type_option).trigger('change');

        @else
            var p_type_option = new Option("{{ $product->product_type?->name }}","{{ $product->type_id }}", true, true);
            $('#p_type').append(p_type_option).trigger('change');
        @endif

        $('.select2-generic').select2({
            theme: "bootstrap-5",
            placeholder: 'Select Size',
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

            @php
                $generic = \App\Models\Inventory\Generic::find(old('generic'));
            @endphp
            var generic_option = new Option("{{ $generic->name }}","{{ $generic->id }}", true, true);
            $('.select2-generic').append(generic_option).trigger('change');

        @else
            var generic_option = new Option("{{ $product->generic?->name }}","{{ $product->generic_id }}", true, true);
            $('#generic').append(generic_option).trigger('change');
        @endif
    @endif

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
    $(document).on("click", "#btn_add_new_variation_form", function () {
         $.ajax({
            type: "POST",
            url: "{{ url('/') }}" + "/saveNewVariationsData",
            data: $("#add_new_variation_formId").serialize(),
            success: function (response) {
                console.log(response);
                var msgType = response.status;

                if (msgType == "ok") {
                    // onSuccessMsg(msg);
                    window.location.reload();
                } else {
                    var msg = response.error_m;
                    toastr.error(msg);

                }
            },
        });
    });
    $(document).on("click", ".btn-trigger-edit-product-version", function () {
         var id = $(this).attr("data-id");
        $.ajax({
            type: "GET",
            url: "{{ url('/') }}" + "/getVariationData",
            data: { id: id },
            success: function (response) {
                console.log(response);
                if (response.msgType == "success") {
                    $("#edit_ajax_data").html(response.html);
                    $("#edit_variation_modal").modal("show");

                }
            },
        });
    });
    $(document).on("click", "#btn_edit_variation_form", function () {
        $.ajax({
            type: "POST",
            url: "{{ url('/') }}" + "/saveEditVariationsData",
            data: $("#edit_variation_formId").serialize(),
            success: function (response) {
                console.log(response);
                var msgType = response.msgType;

                if (msgType == "success") {
                    // onSuccessMsg(msg);
                    window.location.reload();
                } else {
                    var msg = response.msg;
                    toastr.error(msg);

                }
            },
        });
    });
    $(document).on("click", "#btn-trigger-delete-version", function () {
        var id = $(this).attr("data-id");
        Swal.fire({
            title: '{{__("lang.are_you_sure")}}',
            text: '{{__("lang.you_wont_be_able_to_revert_this")}}',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{__("lang.yes_delete_it")}}',
            cancelButtonText: '{{__("lang.cancel")}}',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ms-2',
            buttonsStyling: false,
		}).then(function (result) {
		    if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/') }}" + "/deleteVariation",
                    data: { id: id },
                    success: function (response) {
                        console.log(response);
                        var msgType = response.msgType;

                        if (msgType == "success") {
                            // onSuccessMsg(msg);
                            window.location.reload();
                        } else {
                            var msg = response.msg;
                            toastr.error(msg);

                        }
                    },
                });
            }
        });

    });
    $(document).on('click','#btn_edit_form_attribute',function(){
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ url('/') }}" + "/editProdeuctAttribute",
            data: $("#attribute_set_edit_formId").serialize(),
            success: function (response) {
                console.log(response);
                var msgType = response.msgType;

                if (msgType == "success") {
                    // onSuccessMsg(msg);
                    window.location.reload();
                } else {
                    var msg = response.msg;
                    toastr.error(msg);

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
