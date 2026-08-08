@if($customers->count() > 0)
@foreach ($customers as $customer)
<div class="customer-search-list-item customer-select" data-id="{{$customer->id}}" data-name="{{$customer->name}}"> 
    {{$customer->name}}
</div>
@endforeach
@else
<div class="customer-search-list-item">
No result found
</div>
@endif
