@extends('inc.master')

@section('head')

    <title>Create Dealer PO</title>

@endsection


@section('content')

<div class="content-area">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h4>
                    <b>Create Dealer Purchase Order</b>
                </h4>

            </div>


            <a href="{{ route('dealer-purchase-orders.index') }}" class="btn btn-secondary">

                <i class="bx bx-arrow-back"></i>

                Back

            </a>

        </div>


        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="card">

            <div class="card-body">

                <form action="{{ route('dealer-purchase-orders.store') }}"
                      method="POST">

                    @csrf

                    @include(
                        'distribution.dealer-purchase-orders.form'
                    )


                    <div class="mt-4">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bx bx-save"></i>

                            Save Draft

                        </button>


                        <a href="{{ route('dealer-purchase-orders.index') }}"
                           class="btn btn-secondary">

                            Cancel

                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



@section('script')
<script src="{{asset('public/assets/js/jquery.bootstrap-touchspin.js') }}"></script>
<script>

let itemIndex = {{ isset($dealerPurchaseOrder)
    ? $dealerPurchaseOrder->items->count()
    : 1
}};

const productsHtml = `
    <option value="">Select Product</option>
    @foreach($products as $product)
        <option value="{{ $product->id }}">
            {{ $product->product_name }}
            @if(isset($product->product_code))
                - {{ $product->product_code }}
            @endif
        </option>
    @endforeach
`;

document
    .getElementById('addItem')
    .addEventListener('click', function () {

        const html = `

        <tr class="item-row">

            <td>

                <select
                    name="items[${itemIndex}][product_id]"
                    class="form-select product-select"
                    required>

                    ${productsHtml}

                </select>

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][quantity]"
                    class="form-control quantity"
                    value="1"
                    min="0.001"
                    step="0.001"
                    required>

            </td>


            <td>

                <input
                    type="text"
                    name="items[${itemIndex}][unit]"
                    class="form-control"
                    placeholder="pcs">

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][unit_price]"
                    class="form-control unit-price"
                    value="0"
                    min="0"
                    step="0.01"
                    required>

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][discount_amount]"
                    class="form-control discount"
                    value="0"
                    min="0"
                    step="0.01">

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][tax_amount]"
                    class="form-control tax"
                    value="0"
                    min="0"
                    step="0.01">

            </td>


            <td>

                <input
                    type="text"
                    class="form-control line-total"
                    value="0.00"
                    readonly>

            </td>


            <td>

                <button
                    type="button"
                    class="btn btn-sm btn-danger remove-item">

                    <i class="bx bx-trash"></i>

                </button>

            </td>

        </tr>

        `;


        document
            .getElementById('itemsBody')
            .insertAdjacentHTML(
                'beforeend',
                html
            );


        itemIndex++;

    });



document.addEventListener(
    'click',
    function (e) {

        if (
            e.target.closest('.remove-item')
        ) {

            const rows =
                document.querySelectorAll('.item-row');

            if (rows.length <= 1) {

                alert(
                    'At least one product is required.'
                );

                return;
            }

            e.target
                .closest('.item-row')
                .remove();

            calculateTotals();

        }

    }
);



document.addEventListener(
    'input',
    function (e) {

        if (
            e.target.closest('.item-row')
        ) {

            calculateTotals();

        }

    }
);



function calculateTotals()
{
    let subtotal = 0;
    let tax = 0;
    let discount = 0;

    document
        .querySelectorAll('.item-row')
        .forEach(function (row) {

            const quantity =
                parseFloat(
                    row.querySelector('.quantity')?.value
                ) || 0;

            const price =
                parseFloat(
                    row.querySelector('.unit-price')?.value
                ) || 0;

            const rowDiscount =
                parseFloat(
                    row.querySelector('.discount')?.value
                ) || 0;

            const rowTax =
                parseFloat(
                    row.querySelector('.tax')?.value
                ) || 0;

            const gross =
                quantity * price;

            const total =
                Math.max(
                    0,
                    gross - rowDiscount
                ) + rowTax;

            subtotal += gross;
            discount += rowDiscount;
            tax += rowTax;
            const totalInput = row.querySelector('.line-total');

            if (totalInput) {
                totalInput.value = total.toFixed(2);
            }
        });


    const grandTotal =
        subtotal - discount + tax;


    document.getElementById(
        'subtotal'
    ).innerText =
        subtotal.toFixed(2);


    document.getElementById(
        'totalTax'
    ).innerText =
        tax.toFixed(2);


    document.getElementById(
        'totalDiscount'
    ).innerText =
        discount.toFixed(2);


    document.getElementById(
        'grandTotal'
    ).innerText =
        grandTotal.toFixed(2);
}


calculateTotals();

</script>
@endsection