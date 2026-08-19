@extends('inc.master')

@section('head')

    <title>Show Delivery</title>

@endsection


@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">

        <h4>
            Delivery: {{ $dealerDelivery->delivery_number }}
        </h4>

        <div>

            <a href="{{ route('dealer-deliveries.index') }}"
               class="btn btn-secondary">
                Back
            </a>

            <a href="{{ route(
                'dealer-deliveries.status.form',
                $dealerDelivery
            ) }}"
               class="btn btn-primary">
                Update Status
            </a>

        </div>

    </div>


    <div class="card mb-3">

        <div class="card-header">
            Delivery Information
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">
                    <strong>Dealer</strong><br>
                    {{ $dealerDelivery->dealer->name ?? '-' }}
                </div>

                <div class="col-md-4">
                    <strong>PO</strong><br>
                    {{ $dealerDelivery->purchaseOrder->po_number ?? '-' }}
                </div>

                <div class="col-md-4">
                    <strong>Delivery Date</strong><br>
                    {{ $dealerDelivery->delivery_date?->format('d-m-Y') }}
                </div>

                <div class="col-md-4 mt-3">
                    <strong>Depot</strong><br>
                    {{ $dealerDelivery->depot->name ?? '-' }}
                </div>

                <div class="col-md-4 mt-3">
                    <strong>Vehicle</strong><br>
                    {{ $dealerDelivery->vehicle_no ?? '-' }}
                </div>

                <div class="col-md-4 mt-3">
                    <strong>Driver</strong><br>
                    {{ $dealerDelivery->driver_name ?? '-' }}
                </div>

                <div class="col-md-4 mt-3">
                    <strong>Driver Mobile</strong><br>
                    {{ $dealerDelivery->driver_mobile ?? '-' }}
                </div>

                <div class="col-md-4 mt-3">
                    <strong>Status</strong><br>

                    <span class="badge bg-primary">
                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $dealerDelivery->status
                            )
                        ) }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    <div class="card mb-3">

        <div class="card-header">
            Delivery Items
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>

                </thead>

                <tbody>

                @php
                    $grandTotal = 0;
                @endphp

                @foreach($dealerDelivery->items as $item)

                    @php
                        $total =
                            $item->quantity *
                            $item->unit_price;

                        $grandTotal += $total;
                    @endphp

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->product->product_name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td>
                            {{ number_format($item->unit_price, 2) }}
                        </td>

                        <td>
                            {{ number_format($total, 2) }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

                <tfoot>

                    <tr>

                        <th colspan="4"
                            class="text-end">
                            Grand Total
                        </th>

                        <th>
                            {{ number_format($grandTotal, 2) }}
                        </th>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>


    <div class="card">

        <div class="card-header">
            Delivery Tracking
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Remarks</th>
                        <th>Updated By</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($dealerDelivery->trackings as $tracking)

                    <tr>

                        <td>
                            {{ $tracking->created_at?->format('d-m-Y H:i') }}
                        </td>

                        <td>

                            <span class="badge bg-info">

                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $tracking->status
                                    )
                                ) }}

                            </span>

                        </td>

                        <td>
                            {{ $tracking->location ?? '-' }}
                        </td>

                        <td>
                            {{ $tracking->remarks ?? '-' }}
                        </td>

                        <td>
                            {{ $tracking->creator->name ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="text-center">

                            No tracking history.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection