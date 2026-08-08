<tr class="productSaleRow" id="productSaleRow_{{$product->id}}" data-id="{{$product->id}}">
    <td style="border-color:#864fe0;color: rgb(22 78 99 /1);font-weight: 400;font-size: 1rem;line-height: 1.5rem;padding: .5rem;border-bottom-width: 1px;height: 3rem;">
        <a href="javascript:void(0);" data-id="{{$product->id}}" class="text-truncate productPriceCustomizeModal" style="color: inherit;width:120px;display:block;">
            @if($product->is_variant == 1)
            {{ $product->product_name }} {!! $product->variation_attributes2 !!}
            @else
            {{$product->product_name}}
            @endif
        </a>
        {{-- product price modal --}}
        <div class="product-price-modal invisible" id="productPriceCustomizationModal_{{$product->id}}">
            <div class="product-price-modal-container">
                <div class="product-price-modal-shadow"></div>
                <span class="product-price-modal-vertical"></span>
                <div class="product-price-modal-content">
                    <div class="product-price-modal-body">
                        <div class="d-sm-flex align-items-sm-start">
                            <div class="mt-3 mt-sm-0 text-center text-sm-start">
                                <h3 class="customer-modal-title">Product Price Customization</h3>
                                <div class="mt-2">
                                    <div class="row mt-2">
                                        <div class="col-md-6 mt-3">
                                            <label style="color: rgb(100 116 139 /1);">Product Name</label>
                                            @if($product->is_variant == 1)
                                            <input placeholder="Enter your product name" value="{{$product->product_name}} {!! $product->variation_attributes !!}" type="text" disabled class="modal-input">
                                            @else
                                            <input placeholder="Enter your product name" value="{{$product->product_name}}" type="text" disabled class="modal-input">
                                            @endif
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label style="color: rgb(100 116 139 /1);">Product Price <span style="color: rgb(239 68 68 / 1);">*</span></label>
                                            <input type="text" placeholder="Enter your product price" name="price" value="{{$unit_price}}" class="modal-input">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label style="color: rgb(100 116 139 /1);">Tax <span style="color: rgb(239 68 68 / 1);">*</span></label>
                                            <select name="tax">
                                                <option>select tax</option>
                                                @foreach ($taxes as $tax)
                                                <option  @if($tax_id == $tax->id) selected @endif rate_type="{{$tax->rate_type}}" rate="{{$tax->rate}}" value="{{$tax->rate_type == "Percentage" ? ($unit_price * $tax->rate/100) : $tax->rate}}">{{$tax->name}}({{$tax->rate_type == "Percentage" ? $tax->rate."%" : $tax->rate}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label style="color: rgb(100 116 139 /1);">Discount Type <span style="color: rgb(239 68 68 / 1);">*</span></label>
                                            <select name="dis_type">
                                                <option @if($discount_type == "percent") selected @endif value="percent">Percent</option>
                                                <option @if($discount_type == "fixed") selected @endif value="fixed">Fixed</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label style="color: rgb(100 116 139 /1);">Discount <span style="color: rgb(239 68 68 / 1);">*</span></label>
                                            <input type="text" placeholder="Enter your product discount"  name="dis" value="{{$discount}}" class="modal-input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="customer-modal-footer">
                        <button class="customer-modal-btn submit submitProductPriceCustomizationModal" data-id="{{$product->id}}">Update & Save</button>
                        <button class="customer-modal-btn close closeProductPriceCustomizationModal" data-id="{{$product->id}}">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </td>
    {{-- <td class="text-center d-flex justify-content-center align-items-center" style="border-color:#864fe0;color: rgb(22 78 99 /1);font-weight: 400;font-size: 1rem;line-height: 1.5rem;padding: .5rem;border-bottom-width: 1px;height: 3rem;">
        {{$product->product_code}}
    </td> --}}
    {{-- <td class="text-center" id="productTax_{{$product->id}}" style="border-color:#864fe0;color: rgb(22 78 99 /1);font-weight: 400;font-size: 1rem;line-height: 1.5rem;padding: .5rem;border-bottom-width: 1px;height: 3rem;">
        $ {{$tax_r}}
    </td> --}}
    <td class="text-center productPrice" data-purchase-price="{{$purchase_price}}"  data-price="{{$unit_price}}" id="productPrice_{{$product->id}}" style="border-color:#864fe0;color: rgb(22 78 99 /1);font-weight: 400;font-size: 1rem;line-height: 1.5rem;padding: .5rem;border-bottom-width: 1px;height: 3rem;">
        $ {{$unit_price}}
    </td>
    <td class="text-center" style="border-color:#864fe0;color: rgb(22 78 99 /1);font-weight: 400;font-size: 1rem;line-height: 1.5rem;padding: .5rem;border-bottom-width: 1px;height: 3rem;">
        <div class="d-flex justify-content-center  align-items-center gap-2">
            <button class="checkout-cart-btn" onclick="minusProduct({{$product->id}})">
                <img src="{{asset('public/images/minus.svg')}}" alt="icon">
            </button>
            <input data-id="{{$product->id}}" data-purchase-price="{{$purchase_price}}" data-unit="{{$unit_id}}" data-price="{{$unit_price}}" data-dis="{{$p_discount}}" data-tax="{{$tax_r}}" class="checkout-cart-qty-input productQty" id="productQty_{{$product->id}}" type="number" value="{{$qty}}">
            {{-- <span class="checkout-cart-qty productQty" id="productQty_{{$product->id}}">{{$qty}}</span> --}}
            <button class="checkout-cart-btn" onclick="addProduct({{$product->id}},{{$stock}})">
                <img src="{{asset('public/images/plus.svg')}}" alt="icon">
            </button>
        </div>
        <input type="hidden" id="p_unit_price_{{$product->id}}" class="p_unit_price" data-id="{{$product->id}}" value="{{$unit_price}}">
        <input type="hidden" id="p_discount_{{$product->id}}" class="p_discount" data-id="{{$product->id}}" value="{{$p_discount}}">
        <input type="hidden" id="p_tax_{{$product->id}}" class="p_tax" data-id="{{$product->id}}" value="{{$tax_r}}">
    </td>
    <td id="productSubtotal_{{$product->id}}" data-subtotal="{{$unit_price}}" class="text-right productSubtotal" style="width: 6rem;border-color:#864fe0;color: rgb(22 78 99 /1);font-weight: 400;font-size: 1rem;line-height: 1.5rem;padding: .5rem;border-bottom-width: 1px;height: 3rem;">
        $ {{$unit_price * $qty}}
    </td>
    <td class="text-center" style="width: 30px;border-color:#864fe0;border-bottom-width: 1px;height: 20px;">
        <div class="remove-checkout-cart" onclick="removeProductFromCart({{$product->id}})">
            <img style="width: 20px;height: 20px" src="{{asset('public/images/remove.svg')}}" alt="icon">
        </div>
    </td>
</tr>
