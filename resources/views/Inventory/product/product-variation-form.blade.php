
        @include("Inventory.product.product-attribute-sets")

        @include("Inventory.product.general",[
            'product' => $product,
            'originalProduct' => $originalProduct,
            'isVariation' => true,
        ])
        <input type="hidden" name="varition_id" value="{{ $variation->id }}">
        {{-- <div class="variation-images">
            @include('core/base::forms.partials.images', [
                'name' => 'images[]',
                'values' => isset($product) ? $product->images : [],
            ])
        </div> --}}

