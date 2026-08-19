@extends('inc.master')

@section('head')

    <title>Edit Dealer</title>

@endsection


@section('content')

<div class="content-area">

    <div class="container-fluid">


        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h4 class="mb-1">
                    <b>Edit Dealer</b>
                </h4>

                <small class="text-muted">
                    Update dealer information
                </small>

            </div>


            <a href="{{ route('dealers.index') }}"
               class="btn btn-secondary">

                <i class="bx bx-arrow-back"></i>
                Back

            </a>

        </div>



        <div class="card">

            <div class="card-body">

                <form action="{{ route('dealers.update', $dealer) }}"
                      method="POST">

                    @csrf

                    @method('PUT')


                    @include('distribution.dealers.form')


                    <div class="mt-3">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bx bx-save"></i>
                            Update Dealer

                        </button>


                        <a href="{{ route('dealers.index') }}"
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