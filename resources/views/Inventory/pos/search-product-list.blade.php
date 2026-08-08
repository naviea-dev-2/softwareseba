@if($products->count() > 0)
@foreach ($products as $product)
    @if($product->variations->count())
        @foreach ($product->variations as $variation)
            @php
                $v_product=$variation?->product;
            @endphp
            @if($v_product)
            <div class="search-list-item search-product-select" data-id="{{$v_product->id}}" data-stock="{{$v_product->qty}}">
                {{$v_product->product_name.$v_product->variation_attributes}}
            </div>
            @endif
        @endforeach
    @else
        <div class="search-list-item search-product-select" data-id="{{$product->id}}" data-stock="{{$product->qty}}">
            {{$product->product_name}}
        </div>
    @endif
@endforeach
@else
<div class="search-list-item">
No result found
</div>
@endif
