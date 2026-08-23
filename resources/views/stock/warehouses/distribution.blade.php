@extends('inc.master')

@section('head')
    <title>Warehouse Distribution</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <h4 class="mb-4"><b>Warehouse Distribution</b></h4>
        <div class="row g-3">
            @foreach($warehouses as $warehouse)
                @php
                    $totalQty = $warehouse->stockBalances->sum('total_qty');
                    $reservedQty = $warehouse->stockBalances->sum('reserved_qty');
                    $availableQty = $warehouse->stockBalances->sum('available_qty');
                @endphp
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>{{ $warehouse->name }}</h5>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>Total</span>
                                <strong>{{ $totalQty }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Reserved</span>
                                <strong>{{ $reservedQty }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Available</span>
                                <strong>{{ $availableQty }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection