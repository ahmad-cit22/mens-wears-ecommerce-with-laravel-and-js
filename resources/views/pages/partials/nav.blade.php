<nav>
    <ul>
        <li class="position-static"><a class="nav-link" href="{{ route('index') }}"
                class="{{ Route::currentRouteName() == 'index' ? 'active' : '' }}">Home</a>
        </li>
        <li><a class="nav-link" href="{{ route('products') }}"
                class="{{ Route::currentRouteName() == 'products' ? 'active' : '' }}">Products</a>
        </li>
        <li><a class="nav-link" href="{{ route('offer.products') }}"
                class="{{ Route::currentRouteName() == 'offer.products' ? 'active' : '' }}">Discounted Product</a>
        </li>
        <li><a class="nav-link" href="{{ route('hot.deals') }}"
                class="{{ Route::currentRouteName() == 'hot.deals' ? 'active' : '' }}">Hot Deals</a>
        </li>
        <li><a class="nav-link" href="{{ route('categories') }}"
                class="{{ Route::currentRouteName() == 'categories' ? 'active' : '' }}">Categories <i
                    class="fa fa-angle-down"></i></a>
            <ul class="mega-menu-style-2 mega-menu-width2 menu-negative-mrg1">
                <div class="row">
                    @foreach (App\Models\Category::where('is_active', 1)->where('parent_id', 0)->orderBy('position', 'ASC')->get() as $category)
                        <div class="col-md-3 mb-2">
                            <li><a class="menu-title"
                                    href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}"
                                    style="color: #fff;">{{ $category->title }}</a>
                                <ul style="margin-left: 15px;">
                                    @foreach ($category->childs as $child)
                                        <li><a href="{{ route('category.products', [$child->id, Str::slug($child->title)]) }}"
                                                style="color: #fff;">{{ $child->title }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        </div>
                    @endforeach
                </div>
            </ul>
        </li>
        <li><a class="nav-link" href="#"
                class="{{ Route::currentRouteName() == 'about' || Route::currentRouteName() == 'contact' ? 'active' : '' }}">About<i
                    class="fa fa-angle-down"></i></a>
            <ul class="" style="padding: 10px 15px; width: 200%;">
                <div class="row">
                    <div class="col-12">
                        <li><a class="nav-link mb-2" href="{{ route('about') }}" style="color: #fff;">About Us</a>
                        </li>
                        <li><a class="nav-link mb-2" href="{{ route('contact') }}" style="color: #fff;">Contact Us</a>
                        </li>
                    </div>
                </div>
            </ul>
        </li>
        @if (Auth::check())
            <li><a class="nav-link" href="{{ route('home') }}"
                    class="{{ Route::currentRouteName() == 'customer.account' ? 'active' : '' }}">My Account</a>
            </li>
        @else
            <li><a class="nav-link" href="{{ route('login') }}"
                    class="{{ Route::currentRouteName() == 'login' ? 'active' : '' }}">Login</a>
            </li>
            <li><a class="nav-link" href="{{ route('register') }}"
                    class="{{ Route::currentRouteName() == 'register' ? 'active' : '' }}">Reegister</a>
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
