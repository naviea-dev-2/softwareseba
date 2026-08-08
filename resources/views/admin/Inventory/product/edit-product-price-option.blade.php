@if($p_prices->count() == 0)
<table>
    <thead>
        <tr>
            <th width="20%">Unit</th>
            <th>Sale Price</th>
            <th>Purchase Price</th>
            {{-- <th>Discount</th> --}}
            <th></th>
        </tr>
    </thead>
    <tbody class="edit-data-show">
    <tr>
        <td>
            <select class="edit-select2-unit-1 form-control mb-0" name="unit[]"></select>
        </td>
        <td>
            <input min="0" type="text" name="sale_price[]" class="ml-2 form-control change_discount_p " placeholder="Sale Price">
        </td>
        <td>
            <input min="0" type="text" name="purchase_price[]" class="ml-2 form-control change_discount_p " placeholder="Purchase Price">
        </td>
        {{-- <td>
            <input data-row="1" min="0" type="text" name="unit_discount[]" class="ml-2 form-control add_discount_amount add_discount_amount_1" placeholder="Enter Discount">
        </td> --}}
        <td>
            <a id="plus-btn-data-unit" href="javascript:void(0)" class="plus-btn-data px-1 p-0 m-0 ml-2"><i class="fas fa-plus"></i></a>
        </td>
    </tr>
    </tbody>

</table>
@else
<table>
    <thead>
        <tr>
            <th width="20%">Unitss</th>
            <th>Sale Price</th>
            <th>Purchase Price</th>
            {{-- <th>Discount</th> --}}
            <th></th>
        </tr>
    </thead>
    <tbody class="edit-data-show">
    @foreach ($p_prices as $k=>$p_price)
    <tr>
        <td>
            <select class="edit-select2-unit-{{ $k+1 }} form-control mb-0" name="edit_unit[{{ $p_price->id }}]"></select>
        </td>
        <td>
            <input min="0" type="text" name="edit_sale_price[{{ $p_price->id }}]" class="ml-2 form-control change_discount_p " value="{{ $p_price->sale_price }}" placeholder="Sale Price">
        </td>
        <td>
            <input min="0" type="text" name="edit_purchase_price[{{ $p_price->id }}]" class="ml-2 form-control change_discount_p " placeholder="Purchase Price" value="{{ $p_price->purchase_price }}">
        </td>
        {{-- <td>
            <input data-row="1" min="0" type="text" name="edit_unit_discount[]" class="ml-2 form-control add_discount_amount add_discount_amount_1" placeholder="Enter Discount">
        </td> --}}
        <td>
             @if($k == ($p_prices->count()-1))
                <a id="plus-btn-data-edit-section" href="javascript:void(0)" class="plus-btn-data-edit px-1 p-0 m-0 ml-2"><i class="fas fa-plus"></i></a>
                @else
                <a href="javascript:void(0)" data-id="{{ $p_price->id }}" class="minus-btn-data-edit px-1 p-0 m-0 ml-2"><i class="fas fa-minus"></i></a>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>

</table>

@endif
