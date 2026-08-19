@extends('inc.master')

@section('head')

    <title>Edit Depot</title>

@endsection


@section('content')

<div class="content-area">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h4 class="mb-1">
                    <b>Edit Depot</b>
                </h4>

                <small class="text-muted">
                    Update depot information
                </small>

            </div>


            <a href="{{ route('depots.index') }}"
               class="btn btn-secondary">

                <i class="bx bx-arrow-back"></i>
                Back
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('depots.update', $depot) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('distribution.depots.form')

                    <div class="mt-3">
                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bx bx-save"></i>
                            Update Depot

                        </button>
                        <a href="{{ route('depots.index') }}"
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