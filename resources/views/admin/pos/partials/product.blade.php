@if (!is_null($product) && $product->product->is_active && $product->qty > 0)
    <div class="col-xl-4 col-lg-2 col-md-3 col-sm-4 col-6">
        <div class="productCard">
            <a onclick="add_cart({{ $product->id }})" style="cursor: pointer;">
                <div class="productThumb">
                    <img class="img-fluid" src="{{ asset('images/product/pos_images/' . $product->product->image) }}" alt="{{ $product->product->title }}">
                </div>

                <div class="productContent">

                    {{ $product->product->title }}{{ is_null($product->size) ? '' : ' - ' . optional($product->size)->title }} ({{ $product->qty }})

                </div>
            </a>
        </div>
    </div>
@endif
