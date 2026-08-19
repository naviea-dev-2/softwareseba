@extends('inc.master')

@section('head')

    <title>Create Delivery</title>

@endsection


@section('content')

<div class="container-fluid">

    <h4 class="mb-3">
        Create Dealer Delivery
    </h4>

    @if($errors->any())

    <div class="alert alert-danger">

        <ul class="mb-0">

            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>

    @endif


    <form method="POST"
        action="{{ route('dealer-deliveries.store') }}">

        @csrf


        <div class="card mb-3">

            <div class="card-header">
                Delivery Information
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label>Dealer</label>

                        <select name="dealer_id"
                            class="form-control"
                            required>

                            <option value="">
                                Select Dealer
                            </option>

                            @foreach($dealers as $dealer)

                            <option value="{{ $dealer->id }}">

                                {{ $dealer->name }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label>Purchase Order</label>

                        <select name="purchase_order_id"
                            class="form-control">

                            <option value="">
                                Select PO
                            </option>

                            @foreach($purchaseOrders as $po)

                            <option value="{{ $po->id }}">

                                {{ $po->po_number }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label>Delivery Date</label>

                        <input type="date"
                            name="delivery_date"
                            value="{{ date('Y-m-d') }}"
                            class="form-control"
                            required>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label>Depot</label>

                        <select name="depot_id"
                            class="form-control">

                            <option value="">
                                Select Depot
                            </option>

                            @foreach($depots as $depot)

                            <option value="{{ $depot->id }}">

                                {{ $depot->name }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label>Vehicle No</label>

                        <input type="text"
                            name="vehicle_no"
                            class="form-control">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label>Driver Name</label>

                        <input type="text"
                            name="driver_name"
                            class="form-control">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label>Driver Mobile</label>

                        <input type="text"
                            name="driver_mobile"
                            class="form-control">

                    </div>


                    <div class="col-md-8 mb-3">

                        <label>Note</label>

                        <textarea name="note"
                            class="form-control"></textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="card">

            <div class="card-header d-flex justify-content-between">

                <span>
                    Delivery Items
                </span>

                <button type="button"
                    id="addItem"
                    class="btn btn-sm btn-primary">
                    + Add Product
                </button>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered"
                        id="itemsTable">

                        <thead>

                            <tr>

                                <th>Product</th>
                                <th width="150">Quantity</th>
                                <th width="150">Unit Price</th>
                                <th width="80">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>

                                    <select name="items[0][product_id]"
                                        class="form-control"
                                        required>

                                        <option value="">
                                            Select Product
                                        </option>

                                        @foreach($products as $product)

                                        <option value="{{ $product->id }}">

                                            {{ $product->product_name }}

                                        </option>

                                        @endforeach

                                    </select>

                                </td>

                                <td>

                                    <input type="number"
                                        step="0.01"
                                        min="0.01"
                                        name="items[0][quantity]"
                                        class="form-control"
                                        required>

                                </td>

                                <td>

                                    <input type="number"
                                        step="0.01"
                                        min="0"
                                        name="items[0][unit_price]"
                                        class="form-control"
                                        value="0">

                                </td>

                                <td>

                                    <button type="button"
                                        class="btn btn-danger removeItem">
                                        X
                                    </button>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <div class="text-end">

                    <button class="btn btn-success">
                        Save Delivery
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection
@section('script')
<script>
    let itemIndex = 1;

    document.getElementById('addItem')
        .addEventListener('click', function() {

            let row = `
            <tr>

                <td>

                    <select name="items[${itemIndex}][product_id]"
                            class="form-control"
                            required>

                        <option value="">
                            Select Product
                        </option>

                        @foreach($products as $product)

                            <option value="{{ $product->id }}">
                                {{ $product->product_name }}
                            </option>

                        @endforeach

                    </select>

                </td>

                <td>

                    <input type="number"
                           step="0.01"
                           min="0.01"
                           name="items[${itemIndex}][quantity]"
                           class="form-control"
                           required>

                </td>

                <td>

                    <input type="number"
                           step="0.01"
                           min="0"
                           name="items[${itemIndex}][unit_price]"
                           class="form-control"
                           value="0">

                </td>

                <td>

                    <button type="button"
                            class="btn btn-danger removeItem">
                        X
                    </button>

                </td>

            </tr>
        `;

            document.querySelector('#itemsTable tbody')
                .insertAdjacentHTML('beforeend', row);

            itemIndex++;
        });


    document.addEventListener('click', function(e) {

        if (e.target.classList.contains('removeItem')) {

            e.target.closest('tr').remove();

        }

    });
</script>

@endsection