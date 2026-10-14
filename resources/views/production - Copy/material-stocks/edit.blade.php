@extends('inc.master')

@section('content')
    @include('production.workers.form', ['worker' => $worker])
@endsection