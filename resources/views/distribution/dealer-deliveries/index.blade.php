@extends('inc.master')

@section('head')

    <title>Delivery List</title>

@endsection


@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4>Dealer Deliveries</h4>

        <a href="{{ route('dealer-deliveries.create') }}"
           class="btn btn-primary">
            + New Delivery
        </a>

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


    <div class="card">

        <div class="card-body">

            <form method="GET" class="row mb-3">

                <div class="col-md-4">

                    <select name="dealer_id"
                            class="form-control">

                        <option value="">
                            All Dealers
                        </option>

                        @foreach($dealers as $dealer)

                            <option value="{{ $dealer->id }}"
                                @selected(request('dealer_id') == $dealer->id)>

                                {{ $dealer->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-3">

                    <select name="status"
                            class="form-control">

                        <option value="">
                            All Status
                        </option>

                        <option value="pending"
                            @selected(request('status') == 'pending')>
                            Pending
                        </option>

                        <option value="prepared"
                            @selected(request('status') == 'prepared')>
                            Prepared
                        </option>

                        <option value="in_transit"
                            @selected(request('status') == 'in_transit')>
                            In Transit
                        </option>

                        <option value="delivered"
                            @selected(request('status') == 'delivered')>
                            Delivered
                        </option>

                        <option value="cancelled"
                            @selected(request('status') == 'cancelled')>
                            Cancelled
                        </option>

                    </select>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-secondary">
                        Filter
                    </button>

                </div>

            </form>


            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Delivery No</th>
                            <th>Dealer</th>
                            <th>PO</th>
                            <th>Depot</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($deliveries as $delivery)

                        <tr>

                            <td>
                                {{ $deliveries->firstItem() + $loop->index }}
                            </td>

                            <td>
                                {{ $delivery->delivery_number }}
                            </td>

                            <td>
                                {{ $delivery->dealer->name ?? '-' }}
                            </td>

                            <td>
                                {{ $delivery->purchaseOrder->po_no ?? '-' }}
                            </td>

                            <td>
                                {{ $delivery->depot->name ?? '-' }}
                            </td>

                            <td>
                                {{ $delivery->delivery_date?->format('d-m-Y') }}
                            </td>

                            <td>

                                <span class="badge bg-secondary">
                                    {{ ucwords(str_replace('_', ' ', $delivery->status)) }}
                                </span>

                            </td>

                            <td>

                                <a href="{{ route('dealer-deliveries.show', $delivery) }}"
                                   class="btn btn-sm btn-info">
                                    View
                                </a>

                                @if($delivery->status === 'pending')

                                    <a href="{{ route('dealer-deliveries.edit', $delivery) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center">

                                No deliveries found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            {{ $deliveries->withQueryString()->links() }}

        </div>

    </div>

</div>

@endsection