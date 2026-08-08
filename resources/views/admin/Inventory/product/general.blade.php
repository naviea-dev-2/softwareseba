<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <label for="cost_price">{{ __('Purchase Price') }}</label>
            <input value="{{ $product ? $product->product_stock?->purchase_price : 0 }}" name="cost_price" id="edit_cost_price" type="text" class="form-control parsley-validated">
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for="sale_price">{{ __('Sale Price') }}<span class="red">*</span></label>
            <input value="{{ $product ? $product->product_stock?->sale_price : $o0 }}" name="sale_price" id="edit_sale_price" type="text" class="form-control parsley-validated" data-required="true">
        </div>
    </div>
    {{-- <div class="col-lg-4">
        <div class="form-group">
            <label for="old_price">{{ __('Old Price') }}</label>
            <input value="{{ $product ? $product->old_price : $originalProduct->old_price }}" name="old_price" id="edit_old_price" type="text" class="form-control parsley-validated">
        </div>
    </div>
</div>
<div class="row"> --}}
    <div class="col-lg-4">
        <div class="form-group">
            <label for="sku">{{ __('Sku') }}</label>
            <input value="{{ $product ? $product->product_code : '' }}" name="sku" id="edit_sku" type="text" class="form-control parsley-validated">
        </div>
    </div>
    {{-- <div class="col-md-4">
        <div class="form-group">
            <label for="edit_is_stock">{{ __('Manage Stock') }}</label>
            @php
               $is_stock = $product ? $product->is_stock : $originalProduct->is_stock;
            @endphp
            <select name="is_stock" id="edit_is_stock" class="chosen-select form-control">
                <option @if($is_stock == 0) selected @endif value="0">{{ __('No') }}</option>
                <option @if($is_stock == 1) selected @endif value="1">{{ __('Yes') }}</option>
            </select>
        </div>
    </div> --}}
    {{-- <div class="col-md-4 edit_variation_stock_status" @if($is_stock == 1) style="display: none;" @endif>
        <div class="form-group">
            <label for="stock_status_id">{{ __('Stock Status') }}</label>
            <select name="stock_status_id" id="edit_stock_status_id" class="chosen-select form-control parsley-validated">
                @php
                $stock_status_id = $product ? $product->stock_status_id : $originalProduct->stock_status_id;
                @endphp
                <option @if($stock_status_id == 1) selected @endif value="1">{{ __('In Stock') }}</option>
                <option @if($stock_status_id == 0) selected @endif value="0">{{ __('Out Of Stock') }}</option>
            </select>
        </div>
    </div> --}}
    {{-- <div class="col-md-4 edit_variation_stock_qty" @if($is_stock == 0) style="display: none;" @endif>
        <div class="form-group">
            <label for="stock_qty">{{ __('Stock Quantity') }}</label>
            <input value="{{ $product ? $product->stock_qty : $originalProduct->stock_qty }}" name="stock_qty" id="edit_stock_qty" type="number" class="form-control">
        </div>
    </div> --}}
</div>
{{-- <h3>Shipping Information</h3> --}}

{{-- <div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="actual_weight">{{ __('Weight') }}</label>
            <input value="{{ $product ? $product->actual_weight : $originalProduct->actual_weight }}" name="actual_weight" id="edit_actual_weight" type="text" class="form-control parsley-validated">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="length">{{ __('Length') }}</label>
            <input value="{{ $product ? $product->length : $originalProduct->length }}" name="length" id="edit_length" type="text" class="form-control parsley-validated">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="width">{{ __('Width') }}</label>
            <input value="{{ $product ? $product->width : $originalProduct->width }}" name="width" id="edit_width" type="text" class="form-control parsley-validated">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="height">{{ __('Height') }}</label>
            <input value="{{ $product ? $product->height : $originalProduct->height }}" name="height" id="edit_height" type="text" class="form-control parsley-validated">
        </div>
    </div>

</div> --}}
