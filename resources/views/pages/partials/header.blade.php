<header class="header-area {{ 1 ? 'transparent-bar' : 'border-bottom-1' }} section-padding-1">
    <div class="top-menu">
        <div class="left-part">
            <span>
                <i class="fa fa-phone"></i>
                <span>{{ $business->phone }}</span>
            </span>
        </div>
        <div class="right-part">
            <span>
                @if ($business->facebook != null)
                    <a href="{{ $business->facebook }}" target="_blank"><i class="fa fa-facebook"></i></a>
                @endif
                @if ($business->instagram != null)
                    <a href="{{ $business->instagram }}" target="_blank"><i class="fa fa-instagram"></i></a>
                @endif
                @if ($business->twitter != null)
                    <a href="{{ $business->twitter }}"><i class="fa fa-twitter" target="_blank"></i></a>
                @endif
                @if ($business->youtube != null)
                    <a href="{{ $business->youtube }}"><i class="fa fa-youtube" target="_blank"></i></a>
                @endif
            </span>
        </div>
    </div>
    <div class="header-bottom {{ 1 ? 'background-rgb-1' : '' }}">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-xl-2 col-lg-2">
                    <div class="logo logo-width">
                        <a href="{{ route('index') }}">
                            {{-- @if (Route::currentRouteName() == 'index') --}}
                            <img src="{{ asset('images/website/' . $business->logo) }}" alt="logo">
                            {{-- @else
                                <img src="{{ asset('images/website/' . $business->footer_logo) }}" alt="logo">
                            @endif --}}
                        </a>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 d-flex justify-content-center position-static">
                    <div class="main-menu menu-lh-1 {{ 1 ? 'main-menu-white' : '' }} main-menu-padding-1 menu-fw-400">
                        @include('pages.partials.nav')
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2">
                    <div class="header-right-wrap header-right-flex">
                        <div class="same-style {{ 1 ? 'same-style-white' : '' }} header-wishlist">
                            <a href="{{ route('customer.account') }}"><i class="fa fa-heart-o"></i></a>
                        </div>
                        <div class="same-style {{ 1 ? 'same-style-white' : '' }} cart-wrap">
                            <a href="#" class="cart-active">
                                <i class="dlicon shopping_bag-20"></i>
                                <span class="count-style" id="total_count">{{ Cart::count() }}</span>
                            </a>
                        </div>
                        <div class="same-style {{ 1 ? 'same-style-white' : '' }} header-search">
                            <a class="search-active" href="#">
                                <i class="dlicon ui-1_zoom"></i>
                            </a>
                        </div>
                        <div class="same-style {{ 1 ? 'same-style-white' : '' }} header-off-canvas">
                            <a class="header-aside-button" href="#">
                                <i class="dlicon ui-3_menu-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="header-small-mobile section-padding-1" style="background: #99999966">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-6">
                <div class="mobile-logo logo-width">
                    <a href="{{ route('index') }}">
                        <img alt="" src="{{ asset('images/website/' . $business->logo) }}">
                    </a>
                </div>
            </div>
            <div class="col-6">
                <div class="mobile-header-right-wrap">
                    <div class="header-right-wrap header-right-flex">
                        <div class="same-style cart-wrap">
                            <a href="#" class="cart-active">
                                <i class="dlicon shopping_bag-20"></i>
                                <span class="count-style" id="mobile_total_count">{{ Cart::count() }}</span>
                            </a>
                        </div>
                        <div class="same-style header-off-canvas">
                            <a class="header-aside-button" href="#">
                                <i class="dlicon ui-3_menu-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- search start -->
<div class="search-content-wrap main-search-active">
    <a class="search-close"><i class="dlicon ui-1_simple-remove"></i></a>
    <div class="search-content">
        <p>Start typing and press Enter to search</p>
        <form class="search-form" action="{{ route('search.result') }}" method="GET">
            <input type="text" name="search" placeholder="Search">
            <button class="button-search"><i class="dlicon ui-1_zoom"></i></button>
        </form>
    </div>
</div>
<!-- mini cart start -->
<div class="sidebar-cart-active">
    <div class="sidebar-cart-all">
        <a class="cart-close" href="#"><i class="dlicon ui-1_simple-remove"></i></a>
        <div class="cart-content">
            <h3>Shopping Cart</h3>
            <ul id="cart_sidebar">
                @foreach (Cart::content() as $cart)
                    <li class="single-product-cart">
                        <div class="cart-img">
                            <a href="{{ route('single.product', [$cart->id, Str::slug($cart->name)]) }}"><img src="{{ asset('images/product/' . $cart->options->image) }}" alt=""></a>
                        </div>
                        <div class="cart-title">
                            <h4><a href="{{ route('single.product', [$cart->id, Str::slug($cart->name)]) }}">{{ $cart->name }}{{ $cart->options->size_name == '' ? '' : ' - ' . $cart->options->size_name }}</a></h4>
                            <span> {{ $cart->qty }} × {{ env('CURRENCY') }}{{ $cart->price }} </span>
                        </div>
                        <div class="cart-delete">
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="rowId" value="{{ $cart->rowId }}">
                                <button type="submit" style="background-color: transparent;border: none;"><i class="dlicon ui-1_simple-remove"></i></button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="cart-total">
                <h4>Subtotal: <span id="cart_sidebar_total">{{ env('CURRENCY') }}{{ Cart::subTotal() }}</span></h4>
            </div>
            <div class="cart-checkout-btn">
                <a class="btn-hover cart-btn-style" href="{{ route('carts') }}">view cart</a>
                <a class="no-mrg btn-hover cart-btn-style" href="{{ route('checkout') }}">checkout</a>
            </div>
        </div>
    </div>
</div>
@include('pages.partials.mobile-nav')
