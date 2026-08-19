@extends('inc.master')

@section('head')
    <title>Add Super Depot</title>
@endsection

@section('content')

<div class="container-fluid">

    <h4>Add Super Depot</h4>

    <form action="{{ route('super-depots.store') }}"
          method="POST">

        @csrf

        @include('distribution.super-depots.form')

        <button class="btn btn-primary">
            Save
        </button>

        <a href="{{ route('super-depots.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection