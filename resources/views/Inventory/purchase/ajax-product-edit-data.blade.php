  <tr id="row_{{ $row_no }}" class="pro-row-{{$product->id}}">
    @php
        // if($is_sale == 1){
        //     $purchase_price = $product->product_stock->sale_price;
        // }else{
        //     $purchase_price = $product->product_stock->purchase_price;
        // }
        $purchase_price = $product_item->per_cost;
        $orginal_product = $product->OriginalProduct;
        $hidden_purchase_price = $product_item->purchase_price == 0 ? $product->purchase_price : $product_item->purchase_price;
    @endphp
    <td>
        <input type="hidden" name="old_product_id[{{ $item_id }}]" class="select_product_id_{{ $row_no }}" value="{{ $product->id }}">
        <input type="hidden" class="is_inital{{ $row_no }}" value="{{ $product->product_name == "initial" ? 1 : 0 }}">
        @if($product->is_variant == 1)
        <label data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="select_product_code_{{ $row_no }} select_product_code"><a data-bs-toggle="modal" data-bs-target="#prouct_modal_{{ $product->id }}">{{ $product->product_name }} {{ $product->variation_attributes}}</a></label>
        @else
         <label data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="select_product_code_{{ $row_no }} select_product_code"><a data-bs-toggle="modal" data-bs-target="#prouct_modal_{{ $product->id }}">{{ $product->product_name }} </a></label>
        @endif
        <div class="modal fade" id="prouct_modal_{{ $product->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="">Purchase Price</label>
                            <input type="number" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- <label data-in="{{ $row_no }}" data-id="{{ $product->id }}" class="select_product_code_{{ $row_no }} select_product_code">{{ $product->product_name }} - ({{ $product->product_code }})</label> --}}
    </td>
    <td>

        {{ $orginal_product?->manufacture?->name }}
    </td>
    <td>
      {{ $orginal_product?->brand?->name }}
    </td>
    <td>
        <input data-in="{{ $row_no }}" class="form-control touchspin form-control-sm select_qty_{{ $row_no }} select_qty" type="number" name="old_qty[{{ $item_id }}]" value="{{ $qty }}" placeholder="Qty">
    </td>
    <td>
        <select name="old_unit[{{ $item_id }}]" data-in="{{ $row_no }}" class="form-control select_unit_{{ $row_no }} select_unit mb-0">
            <option value="">Unit</option>

        </select>
    </td>
    <td>
        <input class="hidden_purchase_price_{{ $row_no }}" type="hidden" name="old_purchase_price[{{ $item_id }}]" value="{{ $hidden_purchase_price }}">
    <input data-in="{{ $row_no }}" class="form-control form-control-sm purchase_price_{{ $row_no }} select_purchase_price" name="old_per_cost[{{ $item_id }}]" type="text" placeholder="Price" value="{{ $purchase_price }}">
    </td>

    <td>
    @php
        if($product->discount){
            if($product?->discount_type == "fixed"){
                $dis = $product->discount;
            }else{
                $dis = $purchase_price * $product->discount/100;
            }
        }else{
            $dis = 0;
        }
        $total = $qty * $purchase_price;
    @endphp
    @php
        $tax=0;
        if($product->tax_id != 0){
            if($product->tax->rate_type == "Percentage"){
                $tax = ($total) * $product->tax->rate/100;
            }else{
                $tax = $product->tax->rate;
            }
        }
    @endphp
    <input name="tax[]" data-in="{{ $row_no }}" type="text" class="form-control form-control-sm tax_{{ $row_no }} select_tax" placeholder="Tax" value="{{$tax}}" is_per = "{{ $product->tax_id != 0 ? ($product->tax->rate_type == "Percentage" ? 1 : 0) : 0 }}" dis-per="{{ $product->tax_id != 0 ? $product->tax->rate : 0 }}">
    </td>
    <td>

    <input class="form-control form-control-sm discount_price_{{ $row_no }} select_discount_price" type="text" dis-per="{{ $product->discount }}" is-per="{{ $product->discount_type == "fixed" ? 0 : 1 }}" name="old_discount[{{ $item_id }}]" value="{{ $dis }}" placeholder="Discount">
    </td>
    <td>
        <input name="old_total_purchase[{{ $item_id }}]" data-in="{{ $row_no }}" type="hidden" class="purchase_total_price_{{ $row_no }}" value="{{$qty * $hidden_purchase_price }}">
    <input name="old_total[{{ $item_id }}]" data-in="{{ $row_no }}" type="text" class="form-control form-control-sm total_price_{{ $row_no }} select_total_price" placeholder="Total" value="{{$total}}">
    </td>
    <td>
        <a class="old_delete_item" data-id="{{ $item_id }}" href="javascript:void(0);"><i style="color:red;" class="bx bx-trash"></i></a>
    </td>
</tr>
