<tr id="row_{{ $row_no }}" class="pro-row-{{ $product->id }}">
    @php
    // Product prices
    $purchase_price = in_array((int) $is_sale, [1, 2], true)
    ? (float) ($product->sale_price ?? 0)
    : (float) ($product->purchase_price ?? 0);

    $hidden_purchase_price = (float) ($product->purchase_price ?? 0);
    $orginal_product = $product->OriginalProduct;

    // Quantity and subtotal
    $quantity = max(0, (float) ($qty ?? 1));
    $subtotal = $quantity * $purchase_price;

    // Product discount
    $discount_rate = max(0, (float) ($product->discount ?? 0));
    $discount_type = $product->discount_type ?? 'fixed';

    if ($discount_type === 'fixed') {
    $discount_amount = $discount_rate * $quantity;
    } else {
    $discount_amount = $subtotal * $discount_rate / 100;
    }

    $discount_amount = min($subtotal, $discount_amount);

    // Tax after item discount
    $taxable_amount = max(0, $subtotal - $discount_amount);

    $tax_model = $product->tax_id
    ? $product->tax
    : null;

    $tax_is_percentage = $tax_model
    && $tax_model->rate_type === 'Percentage';

    $tax_rate = max(0, (float) ($tax_model->rate ?? 0));

    if ($tax_is_percentage) {
    $tax_amount = $taxable_amount * $tax_rate / 100;
    } else {
    $tax_amount = $tax_rate * $quantity;
    }

    $line_total = $taxable_amount + $tax_amount;
    @endphp

    {{-- Product --}}
    <td>
        <input
            type="hidden"
            name="product_id[]"
            class="select_product_id_{{ $row_no }}"
            value="{{ $product->id }}">

        <input
            type="hidden"
            class="is_inital{{ $row_no }}"
            value="{{ $product->product_name === 'initial' ? 1 : 0 }}">

        <label
            data-in="{{ $row_no }}"
            data-id="{{ $product->id }}"
            class="select_product_code_{{ $row_no }} select_product_code">
            {{ $product->product_name }}

            @if($product->is_variant == 1)
            {{ $product->variation_attributes }}
            @endif
        </label>
    </td>

    {{-- Manufacturer --}}
    <td>
        {{ $orginal_product?->manufacture?->name }}
    </td>

    {{-- Brand --}}
    <td>
        {{ $orginal_product?->brand?->name }}
    </td>

    {{-- Quantity --}}
    <td>
        <input
            data-in="{{ $row_no }}"
            class="form-control form-control-sm touchspin select_qty_{{ $row_no }} select_qty"
            type="text"
            name="qty[]"
            value="{{ $quantity }}"
            placeholder="Qty">
    </td>

    {{-- Unit --}}
    <td>
        <select
            name="unit[]"
            data-in="{{ $row_no }}"
            class="form-control select_unit_{{ $row_no }} select_unit mb-0">
            <option value="">Unit</option>
        </select>
    </td>

    {{-- Purchase Price --}}
    <td>
        <input
            class="hidden_purchase_price_{{ $row_no }}"
            type="hidden"
            name="purchase_price[]"
            value="{{ $hidden_purchase_price }}">

        <input
            data-in="{{ $row_no }}"
            class="form-control form-control-sm purchase_price_{{ $row_no }} select_purchase_price"
            name="per_cost[]"
            type="number"
            step="0.01"
            min="0"
            placeholder="Price"
            value="{{ $purchase_price }}">
    </td>

    {{-- Tax: Fixed Amount or Percentage --}}
    <td>
        @php
        // Quantity and purchase price
        $taxQty = max(0, (float) ($qty ?? 1));
        $taxPrice = (float) ($purchase_price ?? 0);

        // Product subtotal
        $taxSubtotal = $taxQty * $taxPrice;

        // Discount calculation
        $taxDiscountValue = max(0, (float) ($product->discount ?? 0));

        $taxDiscountType = $product->discount_type ?? 'fixed';

        if ($taxDiscountType === 'fixed') {
        $taxDiscountAmount = $taxDiscountValue;
        } else {
        $taxDiscountAmount = $taxSubtotal * $taxDiscountValue / 100;
        }

        $taxDiscountAmount = min(
        $taxSubtotal,
        $taxDiscountAmount
        );

        // Taxable amount after discount
        $taxableAmount = max(
        0,
        $taxSubtotal - $taxDiscountAmount
        );

        // Product tax information
        $taxModel = $product->tax_id
        ? $product->tax
        : null;

        $taxType = $taxModel
        && $taxModel->rate_type === 'Percentage'
        ? 'percentage'
        : 'fixed';

        $taxValue = max(
        0,
        (float) ($taxModel->rate ?? 0)
        );

        // Calculate tax
        if ($taxType === 'percentage') {
        $taxAmount = $taxableAmount * $taxValue / 100;
        } else {
        $taxAmount = $taxValue;
        }
        @endphp

        <div class="purchase-tax-control">
            <select
                name="tax_type[]"
                data-in="{{ $row_no }}"
                class="select_tax_type">
                <option value="fixed"
                    {{ $taxType === 'fixed' ? 'selected' : '' }}>
                    ৳
                </option>

                <option value="percentage"
                    {{ $taxType === 'percentage' ? 'selected' : '' }}>
                    %
                </option>
            </select>

            <input
                type="number"
                name="tax_rate[]"
                data-in="{{ $row_no }}"
                class="select_tax_rate tax_rate_{{ $row_no }}"
                value="{{ $taxValue }}"
                min="0"
                step="0.01"
                placeholder="0.00">

            <input
                type="hidden"
                name="tax[]"
                data-in="{{ $row_no }}"
                class="tax_{{ $row_no }} select_tax calculated_tax"
                value="{{ number_format($taxAmount, 2, '.', '') }}">
        </div>
    </td>

    {{-- Discount --}}
    <td>
        <input
            class="form-control form-control-sm discount_price_{{ $row_no }} select_discount_price"
            type="number"
            step="0.01"
            min="0"
            dis-per="{{ $discount_rate }}"
            is-per="{{ $discount_type === 'fixed' ? 0 : 1 }}"
            name="discount[]"
            value="{{ number_format($discount_amount, 2, '.', '') }}"
            placeholder="Discount">
    </td>

    {{-- Total --}}
    <td>
        <input
            name="total_purchase[]"
            data-in="{{ $row_no }}"
            type="hidden"
            class="purchase_total_price_{{ $row_no }}"
            value="{{ number_format($quantity * $hidden_purchase_price, 2, '.', '') }}">

        <input
            name="total[]"
            data-in="{{ $row_no }}"
            type="text"
            class="form-control form-control-sm total_price_{{ $row_no }} select_total_price"
            placeholder="Total"
            value="{{ number_format($line_total, 2, '.', '') }}"
            readonly>
    </td>

    {{-- Delete --}}
    <td>
        <a class="delete_item" href="javascript:void(0);">
            <i class="bx bx-trash"></i>
        </a>
    </td>
</tr>