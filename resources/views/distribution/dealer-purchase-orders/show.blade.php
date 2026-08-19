@extends('inc.master')

@section('head')

<title>
    {{ $dealerPurchaseOrder->po_number }}
</title>

@endsection


@section('content')

<div class="content-area">

    <div class="container-fluid">


        {{-- HEADER --}}

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h4>

                    <b>
                        Dealer Purchase Order
                    </b>

                </h4>

                <small class="text-muted">

                    {{ $dealerPurchaseOrder->po_number }}

                </small>

            </div>


            <div>

                <a href="{{ route(
                    'dealer-purchase-orders.index'
                ) }}"
                    class="btn btn-secondary">

                    Back

                </a>


                @if($dealerPurchaseOrder->status === 'draft')

                <a href="{{ route(
                        'dealer-purchase-orders.edit',
                        $dealerPurchaseOrder
                    ) }}"
                    class="btn btn-info">

                    <i class="bx bx-edit"></i>

                    Edit

                </a>

                @endif

            </div>

        </div>


        @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

        @endif


        @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

        @endif


        {{-- PO INFORMATION --}}

        <div class="card mb-3">

            <div class="card-header">

                <h5 class="mb-0">
                    Purchase Order Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-3 mb-3">

                        <strong>
                            PO Number
                        </strong>

                        <br>

                        {{ $dealerPurchaseOrder->po_number }}

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>
                            PO Date
                        </strong>

                        <br>

                        {{ $dealerPurchaseOrder->po_date?->format('d M Y') }}

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>
                            Dealer
                        </strong>

                        <br>

                        {{ $dealerPurchaseOrder->dealer?->name }}

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>
                            Depot
                        </strong>

                        <br>

                        {{ $dealerPurchaseOrder->depot?->name }}

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>
                            Super Depot
                        </strong>

                        <br>

                        {{ $dealerPurchaseOrder
                            ->dealer
                            ?->depot
                            ?->superDepot
                            ?->name ?? '-' }}

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>
                            Expected Delivery
                        </strong>

                        <br>

                        {{ $dealerPurchaseOrder
                            ->expected_delivery_date
                            ?->format('d M Y') ?? '-' }}

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>
                            Created By
                        </strong>

                        <br>

                        {{ $dealerPurchaseOrder
                            ->createdBy
                            ?->name ?? '-' }}

                    </div>


                    <div class="col-md-3 mb-3">

                        <strong>
                            Status
                        </strong>

                        <br>

                        <span class="badge bg-primary">

                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $dealerPurchaseOrder->status
                                )
                            ) }}

                        </span>

                    </div>


                    <div class="col-md-12">

                        <strong>
                            Delivery Address
                        </strong>

                        <br>

                        {{ $dealerPurchaseOrder->delivery_address ?? '-' }}

                    </div>

                </div>

            </div>

        </div>


        {{-- PRODUCTS --}}

        <div class="card mb-3">

            <div class="card-header">

                <h5 class="mb-0">
                    Products
                </h5>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Product</th>

                                <th>Qty</th>

                                <th>Unit</th>

                                <th>Price</th>

                                <th>Discount</th>

                                <th>Tax</th>

                                <th>Total</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                            $dealerPurchaseOrder->items
                            as $item
                            )

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    {{ $item->product?->name }}

                                </td>

                                <td>

                                    {{ number_format(
                                        $item->quantity,
                                        3
                                    ) }}

                                </td>

                                <td>

                                    {{ $item->unit ?? '-' }}

                                </td>

                                <td>

                                    {{ number_format(
                                        $item->unit_price,
                                        2
                                    ) }}

                                </td>

                                <td>

                                    {{ number_format(
                                        $item->discount_amount,
                                        2
                                    ) }}

                                </td>

                                <td>

                                    {{ number_format(
                                        $item->tax_amount,
                                        2
                                    ) }}

                                </td>

                                <td>

                                    <strong>

                                        {{ number_format(
                                            $item->total_amount,
                                            2
                                        ) }}

                                    </strong>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="row justify-content-end">

                    <div class="col-md-5">

                        <table class="table table-bordered">

                            <tr>

                                <th>
                                    Subtotal
                                </th>

                                <td class="text-end">

                                    {{ number_format(
                                        $dealerPurchaseOrder->subtotal,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Tax
                                </th>

                                <td class="text-end">

                                    {{ number_format(
                                        $dealerPurchaseOrder->tax_amount,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Discount
                                </th>

                                <td class="text-end">

                                    {{ number_format(
                                        $dealerPurchaseOrder->discount_amount,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Grand Total
                                </th>

                                <td class="text-end">

                                    <strong>

                                        {{ number_format(
                                            $dealerPurchaseOrder->grand_total,
                                            2
                                        ) }}

                                    </strong>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- WORKFLOW --}}

        <div class="card mb-3">

            <div class="card-header">

                <h5 class="mb-0">
                    PO Actions
                </h5>

            </div>


            <div class="card-body">


                @if($dealerPurchaseOrder->status === 'draft')

                <form action="{{ route(
                        'dealer-purchase-orders.submit',
                        $dealerPurchaseOrder
                    ) }}"
                    method="POST"
                    class="d-inline">

                    @csrf

                    <button class="btn btn-warning"
                        onclick="return confirm(
                                    'Submit this PO for approval?'
                                )">

                        Submit For Approval

                    </button>

                </form>

                @endif


                @if(
                $dealerPurchaseOrder->status
                === 'pending_approval'
                )

                <form action="{{ route(
                        'dealer-purchase-orders.approve',
                        $dealerPurchaseOrder
                    ) }}"
                    method="POST"
                    class="d-inline">

                    @csrf

                    <button class="btn btn-success"
                        onclick="return confirm(
                                    'Approve this PO?'
                                )">

                        Approve

                    </button>

                </form>


                <form action="{{ route(
                        'dealer-purchase-orders.reject',
                        $dealerPurchaseOrder
                    ) }}"
                    method="POST"
                    class="d-inline">

                    @csrf

                    <button class="btn btn-danger"
                        onclick="return rejectPo()">

                        Reject

                    </button>

                    <input type="hidden"
                        name="note"
                        id="rejectNote">

                </form>

                @endif

                @if(in_array(
                $dealerPurchaseOrder->status,
                [
                'approved',
                'partially_delivered'
                ]
                ))

                <a href="{{ route(
        'dealer-deliveries.create',
        ['purchase_order_id' => $dealerPurchaseOrder->id]
    ) }}"
                    class="btn btn-primary">

                    Create Delivery

                </a>

                @endif


                @if(!in_array(
                $dealerPurchaseOrder->status,
                [
                'cancelled',
                'fully_delivered'
                ]
                ))

                <form action="{{ route(
                        'dealer-purchase-orders.cancel',
                        $dealerPurchaseOrder
                    ) }}"
                    method="POST"
                    class="d-inline ms-2">

                    @csrf

                    <button class="btn btn-outline-danger"
                        onclick="return confirm(
                                    'Cancel this PO?'
                                )">

                        Cancel PO

                    </button>

                </form>

                @endif

            </div>

        </div>


        {{-- HISTORY --}}

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0">
                    PO History
                </h5>

            </div>


            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Date</th>

                            <th>Status</th>

                            <th>User</th>

                            <th>Note</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach(
                        $dealerPurchaseOrder->histories
                        as $history
                        )

                        <tr>

                            <td>

                                {{ $history->created_at
                                    ->format('d M Y h:i A') }}

                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $history->status
                                        )
                                    ) }}

                                </span>

                            </td>

                            <td>

                                {{ $history->createdBy?->name ?? '-' }}

                            </td>

                            <td>

                                {{ $history->note ?? '-' }}

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


@push('scripts')

<script>
    function rejectPo() {
        const note = prompt(
            'Reason for rejection:'
        );

        if (note === null) {
            return false;
        }

        document.getElementById(
            'rejectNote'
        ).value = note;

        return confirm(
            'Reject this purchase order?'
        );
    }
</script>

@endpush

@endsection