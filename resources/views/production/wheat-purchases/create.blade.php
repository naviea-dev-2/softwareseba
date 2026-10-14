@extends('inc.master')
@section('content')
@include('production.wheat-purchases._style')
<div class="wheat-wrap"><div class="wheat-top"><div><h2>New Wheat Purchase</h2><div class="wheat-muted">Wheat Purchase In Record / Weighbridge</div></div><a class="wheat-btn light" href="{{ route('production.wheat-purchases.index') }}">← Back to List</a></div>
@include('production.wheat-purchases._alerts')
<form method="post" action="{{ route('production.wheat-purchases.store') }}">
@csrf

@include('production.wheat-purchases._fields')
</form></div>
@endsection
