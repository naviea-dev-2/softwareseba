 <div class="auto-search-product">
    <div class="auto-search-container">
        <ul>
            @if($products->isEmpty())
                <li class="auto-search-res-empty" data-id="0" data="">Search is Empty</li>
            @endif

            @foreach ($products as $k=>$product)
                @php
                    $data=array();
                    $data['name']=$product->product_name;
                    $data['v_name']=$product->product_name;
                    $data['code']=$product->product_code;
                    $data['p_id']=$product->id;
                    $data['cat_id']=$product->category_id;
                    $data['cat_name']=$product->category->name;
                @endphp
                @if($product->variations->count())
                    @foreach ($product->variations as $variation)
                        @php
                            $data=array();
                            $data['name']=$variation?->product?->product_name;
                            $data['v_name']=$variation->product?->product_name.$variation->product?->variation_attributes;
                            $data['code']=$variation?->product?->product_code ?? '';
                            $data['p_id']=$variation?->product?->id;
                            $data['cat_id']=$variation->product?->category_id;
                            $data['cat_name']=$variation?->product?->category?->name;
                        @endphp
                        <li class="auto-search-res-product" data-qty="{{ $variation?->product?->qty }}" data-id="{{ $variation->product->id }}" data="{{ json_encode($data) }}">{{ $variation->product->product_name.$variation->product->variation_attributes }}</li>
                    @endforeach
                @else
                    <li class="auto-search-res-product" data-qty="{{ $product->qty }}" data-id="{{ $product->id }}" data="{{ json_encode($data) }}">{{ $product->product_name }}</li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
