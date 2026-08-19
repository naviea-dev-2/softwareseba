@extends('inc.master')

@section('head')

    <title>Dealer Purchase Orders</title>

@endsection


@section('content')

<div class="content-area">

    <div class="container-fluid">


        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h4 class="mb-1">
                    <b>Dealer Purchase Orders</b>
                </h4>

                <small class="text-muted">
                    Manage dealer purchase orders
                </small>

            </div>


            <a href="{{ route('dealer-purchase-orders.create') }}"
               class="btn btn-primary">

                <i class="bx bx-plus"></i>

                New Dealer PO

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


        {{-- FILTER --}}

        <div class="card mb-3">

            <div class="card-body">

                <form method="GET"
                      action="{{ route('dealer-purchase-orders.index') }}">

                    <div class="row">

                        <div class="col-md-4">

                            <label class="form-label">
                                Dealer
                            </label>

                            <select name="dealer_id"
                                    class="form-select">

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


                        <div class="col-md-4">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="">
                                    All Status
                                </option>

                                <option value="draft"
                                    @selected(request('status') == 'draft')>
                                    Draft
                                </option>

                                <option value="pending_approval"
                                    @selected(request('status') == 'pending_approval')>
                                    Pending Approval
                                </option>

                                <option value="approved"
                                    @selected(request('status') == 'approved')>
                                    Approved
                                </option>

                                <option value="rejected"
                                    @selected(request('status') == 'rejected')>
                                    Rejected
                                </option>

                                <option value="partially_delivered"
                                    @selected(request('status') == 'partially_delivered')>
                                    Partially Delivered
                                </option>

                                <option value="fully_delivered"
                                    @selected(request('status') == 'fully_delivered')>
                                    Fully Delivered
                                </option>

                                <option value="cancelled"
                                    @selected(request('status') == 'cancelled')>
                                    Cancelled
                                </option>

                            </select>

                        </div>


                        <div class="col-md-4 d-flex align-items-end">

                            <button class="btn btn-primary me-2">

                                <i class="bx bx-search"></i>

                                Filter

                            </button>


                            <a href="{{ route('dealer-purchase-orders.index') }}"
                               class="btn btn-secondary">

                                Reset

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- LIST --}}

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped align-middle">

                        <thead>

                        <tr>

                            <th>#</th>

                            <th>PO Number</th>

                            <th>Date</th>

                            <th>Dealer</th>

                            <th>Depot</th>

                            <th>Total</th>

                            <th>Status</th>

                            <th>Created By</th>

                            <th width="150">Action</th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($purchaseOrders as $po)

                            <tr>

                                <td>

                                    {{ $purchaseOrders->firstItem() + $loop->index }}

                                </td>


                                <td>

                                    <strong>

                                        {{ $po->po_number }}

                                    </strong>

                                </td>


                                <td>

                                    {{ $po->po_date?->format('d M Y') }}

                                </td>


                                <td>

                                    {{ $po->dealer?->name ?? '-' }}

                                </td>


                                <td>

                                    {{ $po->depot?->name ?? '-' }}

                                </td>


                                <td>

                                    <strong>

                                        {{ number_format($po->grand_total, 2) }}

                                    </strong>

                                </td>


                                <td>

                                    @php

                                        $statusClasses = [

                                            'draft' => 'bg-secondary',

                                            'pending_approval' => 'bg-warning',

                                            'approved' => 'bg-success',

                                            'rejected' => 'bg-danger',

                                            'partially_delivered' => 'bg-info',

                                            'fully_delivered' => 'bg-success',

                                            'cancelled' => 'bg-dark',

                                        ];

                                    @endphp


                                    <span class="badge {{ $statusClasses[$po->status] ?? 'bg-secondary' }}">

                                        {{ ucwords(str_replace('_', ' ', $po->status)) }}

                                    </span>

                                </td>


                                <td>

                                    {{ $po->createdBy?->name ?? '-' }}

                                </td>


                                <td>

                                    <a href="{{ route('dealer-purchase-orders.show', $po) }}"
                                       class="btn btn-sm btn-primary"
                                       title="View">

                                        <i class="bx bx-show"></i>

                                    </a>


                                    @if($po->status === 'draft')

                                        <a href="{{ route('dealer-purchase-orders.edit', $po) }}"
                                           class="btn btn-sm btn-info"
                                           title="Edit">

                                            <i class="bx bx-edit"></i>

                                        </a>

                                    @endif


                                    @if(in_array($po->status, ['draft', 'rejected']))

                                        <form action="{{ route('dealer-purchase-orders.destroy', $po) }}"
                                              method="POST"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this PO?')">

                                                <i class="bx bx-trash"></i>

                                            </button>

                                        </form>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-4">

                                    No Dealer Purchase Order found.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>


                @if($purchaseOrders->hasPages())

                    <div class="mt-3">

                        {{ $purchaseOrders->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection