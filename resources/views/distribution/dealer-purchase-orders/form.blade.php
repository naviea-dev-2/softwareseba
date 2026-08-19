<div class="row">


    {{-- DEALER --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Dealer
            <span class="text-danger">*</span>

        </label>

        <select name="dealer_id"
                id="dealer_id"
                class="form-select"
                required>

            <option value="">
                -- Select Dealer --
            </option>

            @foreach($dealers as $dealer)

                <option value="{{ $dealer->id }}"
                    @selected(old(
                        'dealer_id',
                        $dealerPurchaseOrder->dealer_id ?? ''
                    ) == $dealer->id)>

                    {{ $dealer->name }}

                    @if($dealer->code)
                        ({{ $dealer->code }})
                    @endif

                </option>

            @endforeach

        </select>

    </div>


    {{-- DEPOT --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Depot
            <span class="text-danger">*</span>

        </label>

        <select name="depot_id"
                id="depot_id"
                class="form-select"
                required>

            <option value="">
                -- Select Depot --
            </option>

            @foreach($depots as $depot)

                <option value="{{ $depot->id }}"
                    @selected(old(
                        'depot_id',
                        $dealerPurchaseOrder->depot_id ?? ''
                    ) == $depot->id)>

                    {{ $depot->name }}

                    @if($depot->code)
                        ({{ $depot->code }})
                    @endif

                </option>

            @endforeach

        </select>

    </div>


    {{-- PO NUMBER --}}

    @if(isset($dealerPurchaseOrder))

        <div class="col-md-4 mb-3">

            <label class="form-label">
                PO Number
            </label>

            <input type="text"
                   class="form-control"
                   value="{{ $dealerPurchaseOrder->po_number }}"
                   readonly>

        </div>

    @endif


    {{-- PO DATE --}}

    <div class="col-md-4 mb-3">

        <label class="form-label">

            PO Date
            <span class="text-danger">*</span>

        </label>

        <input type="date"
               name="po_date"
               value="{{ old(
                   'po_date',
                   isset($dealerPurchaseOrder)
                       ? $dealerPurchaseOrder->po_date?->format('Y-m-d')
                       : now()->format('Y-m-d')
               ) }}"
               class="form-control"
               required>

    </div>


    {{-- EXPECTED DELIVERY --}}

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Expected Delivery Date
        </label>

        <input type="date"
               name="expected_delivery_date"
               value="{{ old(
                   'expected_delivery_date',
                   isset($dealerPurchaseOrder)
                       ? $dealerPurchaseOrder->expected_delivery_date?->format('Y-m-d')
                       : ''
               ) }}"
               class="form-control">

    </div>


    {{-- DELIVERY ADDRESS --}}

    <div class="col-md-12 mb-3">

        <label class="form-label">
            Delivery Address
        </label>

        <textarea name="delivery_address"
                  class="form-control"
                  rows="2">{{ old(
                      'delivery_address',
                      $dealerPurchaseOrder->delivery_address ?? ''
                  ) }}</textarea>

    </div>


</div>


<hr>


<div class="d-flex justify-content-between align-items-center mb-3">

    <h5 class="mb-0">
        Products
    </h5>


    <button type="button"
            class="btn btn-sm btn-success"
            id="addItem">

        <i class="bx bx-plus"></i>

        Add Product

    </button>

</div>


<div class="table-responsive">

    <table class="table table-bordered"
           id="itemsTable">

        <thead>

        <tr>

            <th width="25%">Product</th>

            <th width="10%">Qty</th>

            <th width="10%">Unit</th>

            <th width="12%">Price</th>

            <th width="12%">Discount</th>

            <th width="12%">Tax</th>

            <th width="12%">Total</th>

            <th width="50">#</th>

        </tr>

        </thead>


        <tbody id="itemsBody">

        @if(isset($dealerPurchaseOrder) && $dealerPurchaseOrder->items->count())

            @foreach($dealerPurchaseOrder->items as $index => $item)

                <tr class="item-row">

                    <td>

                        <select name="items[{{ $index }}][product_id]"
                                class="form-select product-select select2"
                                required>

                            <option value="">
                                Select Product
                            </option>

                            @foreach($products as $product)

                                <option value="{{ $product->id }}"
                                    @selected($item->product_id == $product->id)>

                                    {{ $product->product_name }}

                                    @if(isset($product->product_code))
                                        - {{ $product->product_code }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </td>


                    <td>

                        <input type="number"
                               name="items[{{ $index }}][quantity]"
                               class="form-control quantity"
                               value="{{ $item->quantity }}"
                               min="0.001"
                               step="0.001"
                               required>

                    </td>


                    <td>

                        <input type="text"
                               name="items[{{ $index }}][unit]"
                               class="form-control"
                               value="{{ $item->unit ?? '' }}"
                               placeholder="pcs">

                    </td>


                    <td>

                        <input type="number"
                               name="items[{{ $index }}][unit_price]"
                               class="form-control unit-price"
                               value="{{ $item->unit_price }}"
                               min="0"
                               step="0.01"
                               required>

                    </td>


                    <td>

                        <input type="number"
                               name="items[{{ $index }}][discount_amount]"
                               class="form-control discount"
                               value="{{ $item->discount_amount }}"
                               min="0"
                               step="0.01">

                    </td>


                    <td>

                        <input type="number"
                               name="items[{{ $index }}][tax_amount]"
                               class="form-control tax"
                               value="{{ $item->tax_amount }}"
                               min="0"
                               step="0.01">

                    </td>


                    <td>

                        <input type="text"
                               class="form-control line-total"
                               value="{{ number_format($item->total_amount, 2, '.', '') }}"
                               readonly>

                    </td>


                    <td>

                        <button type="button"
                                class="btn btn-sm btn-danger remove-item">

                            <i class="bx bx-trash"></i>

                        </button>

                    </td>

                </tr>

            @endforeach

        @else

            <tr class="item-row">

                <td>

                    <select name="items[0][product_id]"
                            class="form-select product-select"
                            required>

                        <option value="">
                            Select Product
                        </option>

                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->product_name }}
                                @if(isset($product->product_code))
                                    - {{ $product->product_code }}
                                @endif
                            </option>
                        @endforeach

                    </select>

                </td>


                <td>

                    <input type="number"
                           name="items[0][quantity]"
                           class="form-control quantity"
                           value="1"
                           min="0.001"
                           step="0.001"
                           required>

                </td>


                <td>

                    <input type="text"
                           name="items[0][unit]"
                           class="form-control"
                           placeholder="pcs">

                </td>


                <td>

                    <input type="number"
                           name="items[0][unit_price]"
                           class="form-control unit-price"
                           value="0"
                           min="0"
                           step="0.01"
                           required>

                </td>


                <td>

                    <input type="number"
                           name="items[0][discount_amount]"
                           class="form-control discount"
                           value="0"
                           min="0"
                           step="0.01">

                </td>


                <td>

                    <input type="number"
                           name="items[0][tax_amount]"
                           class="form-control tax"
                           value="0"
                           min="0"
                           step="0.01">

                </td>


                <td>

                    <input type="text"
                           class="form-control line-total"
                           value="0.00"
                           readonly>

                </td>


                <td>

                    <button type="button"
                            class="btn btn-sm btn-danger remove-item">

                        <i class="bx bx-trash"></i>

                    </button>

                </td>

            </tr>

        @endif

        </tbody>

    </table>

</div>


<div class="row mt-4">

    <div class="col-md-7">

        <label class="form-label">
            Order Note
        </label>

        <textarea name="note"
                  class="form-control"
                  rows="4">{{ old(
                      'note',
                      $dealerPurchaseOrder->note ?? ''
                  ) }}</textarea>

    </div>


    <div class="col-md-5">

        <table class="table table-bordered">

            <tr>

                <th>
                    Subtotal
                </th>

                <td class="text-end">

                    <span id="subtotal">
                        0.00
                    </span>

                </td>

            </tr>


            <tr>

                <th>
                    Tax
                </th>

                <td class="text-end">

                    <span id="totalTax">
                        0.00
                    </span>

                </td>

            </tr>


            <tr>

                <th>
                    Discount
                </th>

                <td class="text-end">

                    <span id="totalDiscount">
                        0.00
                    </span>

                </td>

            </tr>


            <tr>

                <th>
                    Grand Total
                </th>

                <td class="text-end">

                    <strong>

                        <span id="grandTotal">
                            0.00
                        </span>

                    </strong>

                </td>

            </tr>

        </table>

    </div>

</div>

