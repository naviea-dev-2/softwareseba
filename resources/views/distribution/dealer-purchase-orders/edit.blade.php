@extends('inc.master')

@section('head')

    <title>Edit Dealer PO</title>

@endsection


@section('content')

<div class="content-area">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h4>
                    <b>Edit Dealer Purchase Order</b>
                </h4>

                <small class="text-muted">

                    {{ $dealerPurchaseOrder->po_number }}

                </small>

            </div>


            <a href="{{ route(
                'dealer-purchase-orders.show',
                $dealerPurchaseOrder
            ) }}"
               class="btn btn-secondary">

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

                <form action="{{ route(
                    'dealer-purchase-orders.update',
                    $dealerPurchaseOrder
                ) }}"
                      method="POST">

                    @csrf

                    @method('PUT')


                    @include(
                        'distribution.dealer-purchase-orders.form'
                    )


                    <div class="mt-4">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bx bx-save"></i>

                            Update PO

                        </button>


                        <a href="{{ route(
                            'dealer-purchase-orders.show',
                            $dealerPurchaseOrder
                        ) }}"
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