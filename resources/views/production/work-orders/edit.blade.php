@extends('layouts.app')
@section('content')
    @include('factory.work-orders._form', ['workOrder' => $workOrder])
@endsection