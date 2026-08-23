@extends('inc.master')

@section('head')
    <title>Edit Warehouse</title>
@endsection

@section('content')
<div class="content-area">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h4><b>Edit Warehouse</b></h4>
            <a href="{{ route('stock.warehouses.index') }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('stock.warehouses.update', $warehouse->id) }}">
                    @csrf
                    @method('PUT')
                    @include('stock.warehouses.form', ['warehouse' => $warehouse])
                    <div class="mt-3">
                        <button class="btn btn-primary">
                            <i class="bx bx-save"></i> Update Warehouse
                        </button>
                        <a href="{{ route('stock.warehouses.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection