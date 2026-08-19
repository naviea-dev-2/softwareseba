@extends('inc.master')

@section('head')
    <title>Edit Super Depot</title>
@endsection

@section('content')

<div class="container-fluid">

    <h4>Edit Super Depot</h4>

    <form action="{{ route('super-depots.update', $superDepot) }}"
          method="POST">

        @csrf
        @method('PUT')

        @include('distribution.super-depots.form')

        <button class="btn btn-primary">
            Update
        </button>

        <a href="{{ route('super-depots.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection