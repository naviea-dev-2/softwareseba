  <tr id="row_{{ $row_no }}">
    @php
        $p_price = $product->product_p_price[0];
    @endphp
    <td>
        <input type="hidden" name="old_product_id[{{ $item_id }}]" class="select_product_id_{{ $row_no }}" value="{{ $product->id }}">
        <label data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="select_product_code_{{ $row_no }} select_product_code">{{ $product->product_code }}</label>
    </td>
    <td>
        <select data-in="{{ $row_no }}" class="form-control select_color_{{ $row_no }} select_color mb-0" name="old_color[{{ $item_id }}]">
            <option value="">Color</option>
        </select>
    </td>
    <td>
        <select data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="form-control select_size select_size_{{ $row_no }} mb-0" name="old_size[{{ $item_id }}]">
            <option value="">Size</option>
        </select>
    </td>
    <td>
        <input data-in="{{ $row_no }}" class="form-control touchspin form-control-sm select_qty_{{ $row_no }} select_qty" type="number" name="old_qty[{{ $item_id }}]" value="1" placeholder="Qty">
    </td>
    <td>
        <select name="old_unit[{{ $item_id }}]" data-in="{{ $row_no }}" class="form-control select_unit_{{ $row_no }} select_unit mb-0">
            <option value="">Unit</option>

        </select>
    </td>
    <td>
    <input data-in="{{ $row_no }}" class="form-control form-control-sm purchase_price_{{ $row_no }} select_purchase_price" name="old_per_cost[{{ $item_id }}]" type="text" placeholder="Price" value="{{ $p_price->purchase_price }}">
    </td>
    <td>
    @php
        $tax=0;
        if($product->tax_id != 0){
            if($product->tax->rate_type == "percent"){
                $tax = ($total) * $product->tax->rate/100;
            }else{
                $tax = $product->tax->rate;
            }
        }
    @endphp
    <input name="tax[]" data-in="{{ $row_no }}" type="text" class="form-control form-control-sm tax_{{ $row_no }} select_tax" placeholder="Tax" value="{{$tax}}" is_per = "{{ $product->tax_id != 0 ? ($product->tax->rate_type == "percent" ? 1 : 0) : 0 }}" dis-per="{{ $product->tax_id != 0 ? $product->tax->rate : 0 }}">
    </td>
    <td>

        @php
            if($product->discount_type == "fixed"){
                $dis = $product->discount;
            }else{
                $dis = $p_price->sale_price * $product->discount/100;
            }
            $total = 1 * $p_price->sale_price;
        @endphp
    <input class="form-control form-control-sm discount_price_{{ $row_no }} select_discount_price" type="text" dis-per="{{ $product->discount }}" is-per="{{ $product->discount_type == "fixed" ? 0 : 1 }}" name="old_discount[{{ $item_id }}]" value="{{ $dis }}" placeholder="Discount">
    </td>
    <td>
    <input name="old_total[{{ $item_id }}]" data-in="{{ $row_no }}" type="text" class="form-control form-control-sm total_price_{{ $row_no }} select_total_price" placeholder="Total" value="{{$total}}">
    </td>
    <td>
        <a class="old_delete_item" data-id="{{ $item_id }}" href="javascript:void(0);"><i style="color:red;" class="fa fa-trash"></i></a>
    </td>
</tr>
