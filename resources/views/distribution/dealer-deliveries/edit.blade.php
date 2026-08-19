@extends('inc.master')

@section('head')

    <title>Edit Delivery</title>

@endsection


@section('content')

<div class="container-fluid">

    <h4 class="mb-3">
        Edit Dealer Delivery
    </h4>

    <form method="POST"
          action="{{ route(
              'dealer-deliveries.update',
              $dealerDelivery
          ) }}">

        @csrf
        @method('PUT')

        @include(
            'dealer-deliveries._form',
            ['delivery' => $dealerDelivery]
        )

    </form>

</div>

@endsection