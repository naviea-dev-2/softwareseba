@extends('inc.master')
@section('content')
@include('production.wheat-purchases._style')
<div class="wheat-wrap"><div class="wheat-top"><div><h2>Wheat Purchase Records</h2><div class="wheat-muted">Kazi Flour Mill style weighbridge purchase register</div></div><div class="wheat-actions"><a class="wheat-btn light" href="{{ route('production.wheat-purchases.report') }}">Reports</a><a class="wheat-btn" href="{{ route('production.wheat-purchases.create') }}">+ New Purchase</a></div></div>
@include('production.wheat-purchases._alerts')
@include('production.wheat-purchases._summary')
<div class="wheat-card">@include('production.wheat-purchases._filters',['action'=>route('production.wheat-purchases.index')])</div>
<div class="wheat-card"><div class="wheat-top"><h4>All Deliveries</h4><a class="wheat-btn light" href="{{ route('production.wheat-purchases.export',request()->query()) }}">Export CSV</a></div>@include('production.wheat-purchases._table',['actions'=>true,'offset'=>($purchases->currentPage()-1)*$purchases->perPage()])<div style="margin-top:18px">{{ $purchases->links() }}</div></div></div>
@endsection