 <div class="auto-search-product">
    <div class="auto-search-container">
        {{-- <h5>searching.....</h5> --}}
        <ul>
            @if($products->isEmpty())
             <li class="auto-search-res-empty" data-id="0" data="">Search is Empty</li>
            @endif
            @foreach ($products as $k=>$product)
            @php
            $data=array();
                $data['name']=$product->product_name;
                $data['code']=$product->product_code;
                $data['p_id']=$product->id;
                $data['cat_id']=$product->category_id;
                $data['cat_name']=$product->category->name;
            @endphp

            <li class="auto-search-res-product" data-id="{{ $product->id }}" data="{{ json_encode($data) }}">{{ $product->product_name."(".$product->product_code.')' }}</li>
            @endforeach


        </ul>
    </div>
</div>
