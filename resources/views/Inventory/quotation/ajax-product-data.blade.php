<tr id="row_{{ $row_no }}">
    @php

        if($product->product_p_price->count() > 0){
            $p_price = $product->product_p_price[0];
            $purchase_price = $p_price->sale_price;
        }else{
            $purchase_price = 0;
        }

    @endphp
    <td>
        <input type="hidden" name="product_id[]" class="select_product_id_{{ $row_no }}" value="{{ $product->id }}">
        <label data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="select_product_code_{{ $row_no }} select_product_code">{{ $product->product_code }}</label>
    </td>
    <td>
        <select data-in="{{ $row_no }}" class="form-control select_color_{{ $row_no }} select_color mb-0" name="color[]">
            <option value="">Color</option>
        </select>
    </td>
    <td>
        <select data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="form-control select_size select_size_{{ $row_no }} mb-0" name="size[]">
            <option value="">Size</option>
        </select>
    </td>
    <td>
        <input data-in="{{ $row_no }}" class="form-control touchspin form-control-sm select_qty_{{ $row_no }} select_qty" type="number" name="qty[]" value="{{ $qty }}" placeholder="Qty">
    </td>
    <td>
        <select name="unit[]" data-in="{{ $row_no }}" class="form-control select_unit_{{ $row_no }} select_unit mb-0">
            <option value="">Unit</option>

        </select>
    </td>
    <td>
    <input data-in="{{ $row_no }}" class="form-control form-control-sm purchase_price_{{ $row_no }} select_purchase_price" name="per_cost[]" type="text" placeholder="Price" value="{{ $purchase_price }}">
    </td>
     @php
        if($product->discount_type == "fixed"){
            $dis = $product->discount;
        }else{
            $dis = $purchase_price * $product->discount/100;
        }
        $total = $qty * $purchase_price
    @endphp
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
    <td>
    <input name="tax[]" data-in="{{ $row_no }}" type="text" class="form-control form-control-sm tax_{{ $row_no }} select_tax" placeholder="Tax" value="{{$tax}}" is_per = "{{ $product->tax_id != 0 ? ($product->tax->rate_type == "percent" ? 1 : 0) : 0 }}" dis-per="{{ $product->tax_id != 0 ? $product->tax->rate : 0 }}">
    </td>
    <td>

    <input class="form-control form-control-sm discount_price_{{ $row_no }} select_discount_price" type="text" dis-per="{{ $product->discount }}" is-per="{{ $product->discount_type == "fixed" ? 0 : 1 }}" name="discount[]" value="{{ $dis }}" placeholder="Discount">
    </td>

    <td>
    <input name="total[]" data-in="{{ $row_no }}" type="text" class="form-control form-control-sm total_price_{{ $row_no }} select_total_price" placeholder="Total" value="{{$total}}">
    </td>
    <td>
        <a class="delete_item" href="javascript:void(0);"><i style="color:red;" class="fa fa-trash"></i></a>
    </td>
</tr>
