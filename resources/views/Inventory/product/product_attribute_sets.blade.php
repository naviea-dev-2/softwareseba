<div class="row mt-4">
    <div class="col-md-4">
        <div class="form-group">
            <label for="attribute">{{ __('Attribute') }}</label>
            <select name="attribute[]" class="p-attribute chosen-select form-control">
            @php
                $f_attribute_set = null;
                $k=0;
            @endphp
            @foreach($attribute_sets as $attribute_set)
                @php
                    $check_set =  \App\Models\Inventory\ProductWithAttributSet::where('attribute_set_id',$attribute_set->id)->where('product_id',$product_id)->first();
                @endphp
                @if(!$check_set)
                @php
                    if($k == 0){
                        $f_attribute_set = $attribute_set;
                    }
                @endphp
                <option {{ $k == 0 ? "selected=selected" : '' }} value="{{ $attribute_set->id }}">
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
                <option {{ $attribute->is_default == 1 ? "selected=selected" : '' }} value="{{ $attribute->id }}">
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
