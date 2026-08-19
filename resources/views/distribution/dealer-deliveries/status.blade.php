@extends('inc.master')

@section('head')

    <title>Delivery Status</title>

@endsection


@section('content')

<div class="container">

    <h4 class="mb-3">
        Update Delivery Status
    </h4>

    <div class="card">

        <div class="card-body">

            <div class="mb-3">

                <strong>Delivery:</strong>

                {{ $dealerDelivery->delivery_number }}

            </div>


            <form method="POST"
                  action="{{ route(
                      'dealer-deliveries.status.update',
                      $dealerDelivery
                  ) }}">

                @csrf
                @method('PUT')


                <div class="mb-3">

                    <label>Status</label>

                    <select name="status"
                            class="form-control"
                            required>

                        <option value="pending"
                            @selected($dealerDelivery->status === 'pending')>
                            Pending
                        </option>

                        <option value="preparing"
                            @selected($dealerDelivery->status === 'preparing')>
                            Preparing
                        </option>

                        <option value="dispatched"
                            @selected($dealerDelivery->status === 'dispatched')>
                            Dispatched
                        </option>

                        <option value="in_transit"
                            @selected($dealerDelivery->status === 'in_transit')>
                            In Transit
                        </option>

                        <option value="delivered"
                            @selected($dealerDelivery->status === 'delivered')>
                            Delivered
                        </option>

                        <option value="cancelled"
                            @selected($dealerDelivery->status === 'cancelled')>
                            Cancelled
                        </option>

                    </select>

                </div>


                <div class="mb-3">

                    <label>Current Location</label>

                    <input type="text"
                           name="location"
                           class="form-control"
                           placeholder="Example: Dhaka Depot">

                </div>


                <div class="mb-3">

                    <label>Remarks</label>

                    <textarea name="remarks"
                              class="form-control"
                              rows="4"></textarea>

                </div>


                <button class="btn btn-success">
                    Update Status
                </button>

                <a href="{{ route(
                    'dealer-deliveries.show',
                    $dealerDelivery
                ) }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection