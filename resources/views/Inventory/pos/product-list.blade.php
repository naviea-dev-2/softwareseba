@foreach ($products as $product)
    @if($product->variations->count())
        @foreach ($product->variations as $variation)
        @php
            $v_product = $variation?->product;
        @endphp
        @if($v_product)
        <div class="col-md-4" style="padding:2px">
            <div class="product-box">
                <div class="product-img">
                    <img src="{{$v_product->image_show}}" alt="{{$v_product->product_name}}">
                </div>
                <div class="product-content">
                    <div class="product-name">{{$v_product->product_name}}</div>
                    <div class="product-name">{!! $v_product->variation_attributes2 !!}</div>
                    <div class="product-code">{{$v_product->product_code}}</div>
                    @php
                        $p_qty = $v_product->qty;
                    @endphp
                    <div class="product-stock">Stock : {{$p_qty}}</div>
                </div>
                <div class="product-hover">
                    @if($p_qty > 0)
                        <div class="add-cart-box">
                            <button class="cart-btn"  onclick="minusProduct({{$v_product->id}})">
                                <img src="{{asset('public/images/minus.svg')}}" alt="">
                            </button>
                            <span class="cart-qty" id="featuredProducts_qty_{{$v_product->id}}">0</span>
                            <button class="cart-btn" onclick="addProduct({{$v_product->id}},{{$p_qty}})">
                                <img src="{{asset('public/images/plus.svg')}}" alt="">
                            </button>
                        </div>
                    @else
                        <div class="stock-msg">
                            Out of Stock
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endforeach
    @else
        <div class="col-md-4" style="padding:2px">
            <div class="product-box">
                <div class="product-img">
                    <img src="{{$product->image_show}}" alt="{{$product->product_name}}">
                </div>
                <div class="product-content">
                    <div class="product-name">{{$product->product_name}}</div>
                    <div class="product-code">{{$product->product_code}}</div>
                    @php
                        $p_qty = $product->qty;
                    @endphp
                    <div class="product-stock">Stock : {{$p_qty}}</div>
                </div>
                <div class="product-hover">
                    @if($p_qty > 0)
                        <div class="add-cart-box">
                            <button class="cart-btn" onclick="minusProduct({{$product->id}})">
                                <img src="{{asset('public/images/minus.svg')}}" alt="">
                            </button>
                            <span class="cart-qty" id="featuredProducts_qty_{{$product->id}}">0</span>
                            <button class="cart-btn" onclick="addProduct({{$product->id}},{{$p_qty}})">
                                <img src="{{asset('public/images/plus.svg')}}" alt="">
                            </button>
                        </div>
                    @else
                        <div class="stock-msg">
                            Out of Stock
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endforeach
