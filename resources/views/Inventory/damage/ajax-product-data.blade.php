<tr id="row_{{ $row_no }}" class="pro-row-{{$product->id}}">
    @php
        if($is_sale == 1 || $is_sale == 2){
            $purchase_price = $product->sale_price ?? 0;
        }else{
            $purchase_price = $product->purchase_price ?? 0;
        }
        $hidden_purchase_price = $product->purchase_price ?? 0;
        $orginal_product = $product->OriginalProduct;
    @endphp
    <td>
        <input type="hidden" name="product_id[]" class="select_product_id_{{ $row_no }}" value="{{ $product->id }}">
        <input type="hidden" class="is_inital{{ $row_no }}" value="{{ $product->product_name == "initial" ? 1 : 0 }}">
        @if($product->is_variant == 1)
        <label data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="select_product_code_{{ $row_no }} select_product_code">{{ $product->product_name }} {{ $product->variation_attributes}}</label>
        @else
         <label data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="select_product_code_{{ $row_no }} select_product_code">{{ $product->product_name }}</label>
        @endif
    </td>
    <td>

        {{ $orginal_product?->manufacture?->name }}
    </td>
    <td>
      {{ $orginal_product?->brand?->name }}
    </td>
   
    <td>
        <input data-in="{{ $row_no }}" class="form-control touchspin form-control-sm select_qty_{{ $row_no }} select_qty" type="text" name="qty[]" value="{{ $qty }}" placeholder="Qty">
    </td>
    <td>
        <select name="unit[]" data-in="{{ $row_no }}" class="form-control select_unit_{{ $row_no }} select_unit mb-0">
            <option value="">Unit</option>
        </select>
    </td>
    <td>
        <input class="hidden_purchase_price_{{ $row_no }}" type="hidden" name="purchase_price[]" value="{{ $hidden_purchase_price }}">
        <input data-in="{{ $row_no }}" class="form-control form-control-sm purchase_price_{{ $row_no }} select_purchase_price" name="per_cost[]" type="text" placeholder="Price" value="{{ $purchase_price }}">
    </td>
    
    @php
        $total = $qty * $purchase_price;
    @endphp
    <td>
        <input name="total_purchase[]" data-in="{{ $row_no }}" type="hidden" class="purchase_total_price_{{ $row_no }}" value="{{$qty * $hidden_purchase_price }}">
        <input name="total[]" data-in="{{ $row_no }}" type="text" class="form-control form-control-sm total_price_{{ $row_no }} select_total_price" placeholder="Total" value="{{$total}}">
    </td>
    <td>
        <a class="delete_item" href="javascript:void(0);"><i style="color:red;" class="bx bx-trash"></i></a>
    </td>
</tr>
