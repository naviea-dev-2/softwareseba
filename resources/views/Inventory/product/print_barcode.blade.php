<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Barcode</title>
    <style>
        .old-price{
            position: relative;
        }
        .old-price::before{
            content: " ";
            display: block;
            width: 100%;
            border-top: 2px solid rgba(255, 0, 0, 0.8);
            height: 7px;
            position: absolute;
            bottom: 0;
            left: 0;
            transform: rotate(-2deg);
        }
        @page { margin: 0px; }
         @media print {
             body{
                 margin: 0px;
                 font-size: 8px;
             }
            .print-div{
                /* margin-top:5px; */
                page-break-inside: avoid;
            }
        }
    </style>

</head>
<body style="margin:0;">

    @php
        $b_name = auth()->user()->business->business_name;
    @endphp
    @if($size == "single")


        @foreach ($products as $product)

            @for ($i=0;$i<$qtys[$product->id];$i++)
            @if($product->variations->count())
                @foreach ($product->variations as $variation)
                @php
                    $v_product = $variation?->product;
                @endphp

                @if($v_product)
                    @php
                        $type_barcode = new \Picqer\Barcode\Types\TypeCode128;
                        $barcode = $type_barcode->getBarcode('nbr-'.$v_product->id);
                        $renderer = new \Picqer\Barcode\Renderers\PngRenderer;
                        //$renderer = new Picqer\Barcode\Renderers\PngRenderer();
                        $v_img_barcode = base64_encode($renderer->render($barcode));
                    @endphp
                    <div style="    display: flex;/* flex-wrap: wrap; */justify-content: center;">
                        <div style="width:100%;margin-top:2px;margin-bottom:10px;margin-left:10px;margin-right:10px;border:1px solid #000000;padding: 10px;text-align: center;page-break-inside: avoid; display: flex;flex-direction: column;">
                            <p class="m-0" style="font-weight: 700;padding:0;margin:0;font-size:20px;line-height: 15px;">{{ $b_name }}</p>
                            @if($name_check)
                            <span class="mb-1" style="font-weight: 700;font-size:18px;line-height: 20px;margin-top:5px;">{{$v_product?->product_name}}</span>
                            @endif
                            @if($price_check)
                                @php

                                $sale_price = $v_product->sale_price;
                                @endphp
                                @if($dis_price_check && $v_product->discount > 0)
                                    <div  class="old-price" style="padding-top:10px;font-weight: 700;font-size:18px;line-height: 18px;">

                                        Price:{{ auth()->user()->currency_symbol.' '.$sale_price }}
                                    </div>
                                @endif
                                <div  @if($dis_price_check) style="padding-top: 2px;font-weight: 700;font-size:18px;line-height: 18px;" @else style="padding-top:10px;font-weight: 700;font-size:18px;line-height: 18px;" @endif>
                                    @php
                                        if($v_product->discount > 0){
                                            if($v_product->discount_type == "percent"){
                                                $dis_sale_price = $sale_price -( $sale_price * $v_product->discount/100);
                                            }else{
                                                $dis_sale_price = $sale_price - $v_product->discount;
                                            }
                                        }else{
                                            $dis_sale_price = $sale_price;
                                        }

                                    @endphp
                                    Price :{{ auth()->user()->currency_symbol.' '.$dis_sale_price }}
                                </div>

                            @endif
                            <img src="data:image/png;base64, {{$v_img_barcode}}" style="height: auto; max-width: 100%;">
                            @if($code_check)
                            <div class="mt-1" style="font-weight: 700;font-size:18px;line-height: 18px;">{{$v_product->product_code}}</div>
                            @endif
                            <div class="mt-1" style="font-weight: 700;font-size:18px;line-height: 18px;">
                            {{$v_product?->variation_attributes}}
                            </div>

                        </div>
                    </div>
                @endif
                @endforeach
            @else
            <div style="    display: flex;/* flex-wrap: wrap; */justify-content: center;">
                <div style="width:100%;margin-top:2px;margin-bottom:10px;margin-left:10px;margin-right:10px;border:1px solid #000000;padding: 10px;text-align: center;page-break-inside: avoid; display: flex;flex-direction: column;">
                    @php
                        $type_barcode = new \Picqer\Barcode\Types\TypeCode128;
                        $barcode = $type_barcode->getBarcode('nbr-'.$product->id);
                        $renderer = new \Picqer\Barcode\Renderers\PngRenderer;
                        //$renderer = new Picqer\Barcode\Renderers\PngRenderer();
                        $img_barcode = base64_encode($renderer->render($barcode));
                    @endphp
                    <p class="m-0" style="font-weight: 700;padding:0;margin:0;font-size:20px;line-height: 20px;">{{ $b_name }}</p>
                    @if($name_check)
                    <span class="mb-1"  style="font-weight: 700;font-size:18px;line-height: 20px;margin-top:5px;">{{$product->product_name}}</span>
                    @endif
                    @if($price_check)
                    @php

                        $sale_price = $product->sale_price;
                    @endphp
                    @if($dis_price_check && $product->discount > 0)
                        <div  class="old-price" style="padding-top:10px;font-weight: 700;font-size:18px;line-height: 18px;">

                            Price:{{ auth()->user()->currency_symbol.' '.$sale_price }}
                        </div>
                        @endif
                        <div  @if($dis_price_check) style="padding-top: 2px;font-weight: 700;font-size:18px;line-height: 18px;" @else style="padding-top:10px;font-weight: 700;font-size:18px;line-height: 18px;" @endif>
                            @php
                                if($product->discount_type == "percent"){
                                    $dis_sale_price = $sale_price -( $sale_price * $product->discount/100);
                                }else{
                                    $dis_sale_price = $sale_price - $product->discount;
                                }
                            @endphp
                            Price :{{ auth()->user()->currency_symbol.' '.$dis_sale_price }}
                        </div>

                    @endif
                    <img src="data:image/png;base64, {{$img_barcode}}" style="height: auto; max-width: 100%;">
                    @if($code_check)
                    <div class="mt-1" style="font-weight: 700;font-size:18px;line-height: 18px;">{{$product->product_code}}</div>
                    @endif
                    @if($product->is_variant == 1)
                    <div style="font-weight: 700;font-size:18px;line-height: 18px;">
                        {{$product?->variation_attributes}}
                    </div>
                    @endif


                </div>
            </div>
            @endif
            @endfor


        @endforeach
    @else
    <div style="    display: flex;flex-wrap: wrap;">
    @foreach ($products as $product)
        @for ($i=0;$i<$qtys[$product->id];$i++)
            @if($product->variations->count())
                @foreach ($product->variations as $variation)
                @php
                    $v_product = $variation?->product;
                @endphp

                @if($v_product)
                <div style="width:{{ $box_width }}px;margin:2px;border:1px solid #000000;padding: 10px;text-align: center;page-break-inside: avoid; display: flex;flex-direction: column;">
                    @php
                        $type_barcode = new \Picqer\Barcode\Types\TypeCode128;
                        $barcode = $type_barcode->getBarcode('nbr-'.$v_product->id);
                        $renderer = new \Picqer\Barcode\Renderers\PngRenderer;
                        //$renderer = new Picqer\Barcode\Renderers\PngRenderer();
                        $v_img_barcode = base64_encode($renderer->render($barcode));
                    @endphp
                    <p class="m-0" style="font-weight: 700;padding:0;margin:0;font-size:20px;line-height: 15px;">{{ $b_name }}</p>
                    @if($name_check)
                    <span class="mb-1">{{$v_product->product_name}}</span>
                    @endif

                    @if($price_check)
                        @php

                        $sale_price = $v_product->sale_price;
                        @endphp
                        @if($dis_price_check && $v_product->discount > 0)
                            <div  class="old-price" style="padding-top:10px;">

                                Price:{{ auth()->user()->currency_symbol.' '.$sale_price }}
                            </div>
                        @endif
                        <div  @if($dis_price_check) style="padding-top: 2px;" @else style="padding-top:10px;" @endif>
                            @php
                                if($v_product->discount > 0){
                                    if($v_product->discount_type == "percent"){
                                        $dis_sale_price = $sale_price -( $sale_price * $v_product->discount/100);
                                    }else{
                                        $dis_sale_price = $sale_price - $v_product->discount;
                                    }
                                }else{
                                    $dis_sale_price = $sale_price;
                                }

                            @endphp
                            Price :{{ auth()->user()->currency_symbol.' '.$dis_sale_price }}
                        </div>

                    @endif
                    <img src="data:image/png;base64, {{$v_img_barcode}}" style="height: auto; max-width: 100%;">
                    @if($code_check)
                    <div class="mt-1">{{$v_product->product_code}}</div>
                    @endif
                    {{$v_product?->variation_attributes}}



                </div>
                @endif
                @endforeach
            @else
            <div style="width:{{ $box_width }}px;margin:2px;border:1px solid #000000;padding: 10px;text-align: center;page-break-inside: avoid; display: flex;flex-direction: column;">
                @php
                    $type_barcode = new \Picqer\Barcode\Types\TypeCode128;
                    $barcode = $type_barcode->getBarcode('nbr-'.$product->id);
                    $renderer = new \Picqer\Barcode\Renderers\PngRenderer;
                    //$renderer = new Picqer\Barcode\Renderers\PngRenderer();
                    $img_barcode = base64_encode($renderer->render($barcode));
                @endphp
                <p class="m-0" style="font-weight: 700;padding:0;margin:0;font-size:20px;line-height: 15px;">{{ $b_name }}</p>
                @if($name_check)
                <span class="mb-1">{{$product->product_name}}</span>
                @endif
                @if($price_check)
                    @php

                        $sale_price = $product->sale_price;
                    @endphp
                    @if($dis_price_check && $product->discount > 0)
                    <div  class="old-price" style="padding-top:10px;">

                        Price:{{ auth()->user()->currency_symbol.' '.$sale_price }}
                    </div>
                    @endif
                    <div  @if($dis_price_check) style="padding-top: 2px;" @else style="padding-top:10px;" @endif>
                        @php
                            if($product->discount_type == "percent"){
                                $dis_sale_price = $sale_price -( $sale_price * $product->discount/100);
                            }else{
                                $dis_sale_price = $sale_price - $product->discount;
                            }
                        @endphp
                        Price :{{ auth()->user()->currency_symbol.' '.$dis_sale_price }}
                    </div>

                @endif
                <img src="data:image/png;base64, {{$img_barcode}}" style="height: auto; max-width: 100%;">
                @if($code_check)
                <div class="mt-1">{{$product->product_code}}</div>
                @endif
                @if($product->is_variant == 1)
                {{$product?->variation_attributes}}
                @endif



            </div>
            @endif
        @endfor


    @endforeach
    </div>
    @endif

    <script type="text/javascript">
        function auto_print() {
            window.print()
        }
        setTimeout(auto_print, 1000);
    </script>
</body>
</html>
