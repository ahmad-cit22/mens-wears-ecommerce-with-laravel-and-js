@if ($product)
    <div class="product-wrap mb-50">
        <div class="product-img default-overlay mb-25">
            <a href="{{ route('single.product', [$product->id, $product->title]) }}">
                <img class="default-img" src="{{ asset('images/product/' . $product->image) }}" alt="">
                <img class="hover-img" src="{{ asset('images/product/' . $product->image) }}" alt="">
                @if ($product->total_stock() < 1)
                    <span class="stock-out-tag">Stock Out</span>
                @endif
            </a>
            <div class="product-action product-action-position-1">
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_product_details({{ $product->id }})"><i class="fa fa-eye"></i><span>Quick Shop</span></a>
                <a title="Add to Wishlist" href="javascript:void(0)" onclick="addToWishlist({{ $product->id }})"><i class="fa fa-heart"></i><span>Add to Wishlist</span></a>
                <!-- <a class="icon-blod" title="Add to Compare" href="#"><i class="dlicon arrows-4_compare"></i><span>Add to Compare</span></a>
            <a title="Add to Cart" href="#"><i class="fa fa-shopping-cart"></i><span>Add to Cart</span></a> -->
            </div>
        </div>
        <div class="product-content-2 title-font-width-400 text-center">
            <h3><a href="{{ route('single.product', [$product->id, $product->title]) }}">{{ $product->title }}</a></h3>
            <div class="product-price">
                @if ($product->type == 'single')
                    @if ($product->is_offer == 1)
                        <del class="text-danger"><span class="new-price">&#2547; {{ $product->variation->price }}</span></del> <span class="new-price">&#2547; {{ $product->variation->discount_price }}
                        @else
                            <span class="new-price">&#2547; {{ $product->variation->price }}</span>
                    @endif
                @else
                    @if ($product->is_offer == 1)
                        <del class="text-danger"><span class="new-price">&#2547; {{ $product->variations->where('price', $product->variations->min('price'))->first()->price }}</span></del> <span class="new-price">&#2547; {{ $product->variations->where('discount_price', $product->variations->min('discount_price'))->first()->discount_price }}</span>
                    @else
                        <span class="new-price">&#2547; {{ $product->variations->where('price', $product->variations->min('price'))->first()->price }}</span>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endif
