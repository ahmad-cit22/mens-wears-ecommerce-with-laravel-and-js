<nav>
    <ul>
        <li class="position-static"><a href="{{ route('index') }}" class="{{ Route::currentRouteName() == 'index' ? 'active' : '' }}">Home</a>
        </li>
        <li><a href="{{ route('products') }}" class="{{ Route::currentRouteName() == 'products' ? 'active' : '' }}">Products</a>
        </li>
        <li><a href="{{ route('offer.products') }}" class="{{ Route::currentRouteName() == 'offer.products' ? 'active' : '' }}">Discounted Product</a>
        </li>
        <li><a href="{{ route('hot.deals') }}" class="{{ Route::currentRouteName() == 'hot.deals' ? 'active' : '' }}">Hot Deals</a>
        </li>
        <li><a href="{{ route('categories') }}" class="{{ Route::currentRouteName() == 'categories' ? 'active' : '' }}">Categories <i class="fa fa-angle-down"></i></a>
            <ul class="mega-menu-style-2 mega-menu-width2 menu-negative-mrg1">
                <div class="row">
                    @foreach (App\Models\Category::where('is_active', 1)->where('parent_id', 0)->orderBy('position', 'ASC')->get() as $category)
                        <div class="col-md-3 mb-2">
                            <li><a class="menu-title" href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}" style="color: #fff;">{{ $category->title }}</a>
                                <ul style="margin-left: 15px;">
                                    @foreach ($category->childs as $child)
                                        <li><a href="{{ route('category.products', [$child->id, Str::slug($child->title)]) }}" style="color: #fff;">{{ $child->title }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        </div>
                    @endforeach
                </div>
            </ul>
        </li>
        <li><a href="{{ route('about') }}" class="{{ Route::currentRouteName() == 'about' ? 'active' : '' }}">About Us</a>
        </li>
        <li><a href="{{ route('contact') }}" class="{{ Route::currentRouteName() == 'contact' ? 'active' : '' }}">Contact Us</a>
        </li>
        @if (Auth::check())
            <li><a href="{{ route('home') }}" class="{{ Route::currentRouteName() == 'customer.account' ? 'active' : '' }}">My Account</a>
            </li>
        @else
            <li><a href="{{ route('login') }}" class="{{ Route::currentRouteName() == 'login' ? 'active' : '' }}">Login</a>
            </li>
            <li><a href="{{ route('register') }}" class="{{ Route::currentRouteName() == 'register' ? 'active' : '' }}">Reegister</a>
            </li>
        @endif
        <!-- <li><a href="#">Pages <i class="fa fa-angle-down"></i></a>
            <ul class="sub-menu-width">
                <li><a href="about-us.html">About Us</a></li>
                <li><a href="contact-us.html">Contact Page</a></li>
                <li><a href="404.html">404 Page</a></li>
                <li><a href="faq.html">FAQ</a></li>
            </ul>
        </li> -->

        <!-- <li><a href="shop-instagram.html">Instagram Shop </a></li> -->
    </ul>
</nav>
