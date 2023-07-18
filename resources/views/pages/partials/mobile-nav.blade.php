<!-- aside start -->
        <div class="header-aside-active">
            <div class="header-aside-wrap">
                <a class="aside-close"><i class="dlicon ui-1_simple-remove"></i></a>
                <div class="header-aside-content">
                    <div class="mobile-menu-area">
                        <div class="mobile-search">
                            <form class="search-form" action="{{ route('search.result') }}" method="GET">
                                <input type="text" name="search" placeholder="Search entire store…">
                                <button class="button-search"><i class="dlicon ui-1_zoom"></i></button>
                            </form>
                        </div>
                        <div class="mobile-menu-wrap">
                            <!-- mobile menu start -->
                            <div class="mobile-navigation">
                                <!-- mobile menu navigation start -->
                                <nav>
                                    <ul class="mobile-menu">
                                        <li><a href="{{ route('index') }}">Home</a></li>
                                        
                                        <li><a href="{{ route('products') }}">Products</a></li>

                                        <li><a href="{{ route('offer.products') }}">Offer</a></li>

                                        <li class="menu-item-has-children"><a href="{{ route('categories') }}">Categories</a>
                                            <ul class="dropdown">
                                                @foreach(App\Models\Category::where('is_active', 1)->where('parent_id', 0)->orderBy('position', 'ASC')->get() as $category)
                                                @if(count($category->childs) < 1)
                                                <li><a href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}">{{ $category->title }}</a></li>
                                                @else
                                                <li class="menu-item-has-children"><a href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}">{{ $category->title }}</a>
                                                    
                                                    <ul class="dropdown">
                                                        @foreach($category->childs as $child)
                                                        <li><a href="{{ route('category.products', [$child->id, Str::slug($child->title)]) }}">{{ $child->title }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                    
                                                </li>
                                                @endif
                                                @endforeach
                                            </ul>
                                        </li>

                                        <li><a href="{{ route('about') }}">About Us </a></li>
                                        <li><a href="{{ route('contact') }}">Contact Us </a></li>
                                        @if(Auth::check())
                                        <li><a href="{{ route('home') }}">My Account </a></li>
                                        @else
                                        <li><a href="{{ route('login') }}">Login </a></li>
                                        <li><a href="{{ route('register') }}">Register </a></li>
                                        @endif
                                    </ul>
                                </nav>
                                <!-- mobile menu navigation end -->
                            </div>
                            <!-- mobile menu end -->
                        </div>
                        
                    </div>
                    
                    <div class="aside-contact-info">
                        <ul>
                            <!-- <li><i class="dlicon ui-2_time-clock"></i>Monday - Friday: 9:00 - 19:00</li> -->
                            <li><i class="dlicon ui-1_email-84"></i>{{ $business->email }}</li>
                            <li><i class="dlicon tech-2_rotate"></i>{{ $business->phone }}</li>
                            <li><i class="dlicon ui-1_home-minimal"></i>{{ $business->address }}</li>
                        </ul>
                    </div>
                    <div class="social-icon-style mb-25">
                        @if($business->facebook != NULL)
                        <a class="facebook" href="{{ $business->facebook }}" target="_blank"><i class="fa fa-facebook"></i></a>
                        @endif
                        @if($business->twitter != NULL)
                        <a class="twitter" href="{{ $business->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a>
                        @endif
                        @if($business->youtube != NULL)
                        <a class="youtube" href="{{ $business->youtube }}" target="_blank"><i class="fa fa-youtube"></i></a>
                        @endif
                        @if($business->instagram != NULL)
                        <a class="dribbble" href="{{ $business->instagram }}" target="_blank"><i class="fa fa-instagram"></i></a>
                        @endif
                        @if($business->linkedin != NULL)
                        <a class="facebook" href="{{ $business->linkedin }}" target="_blank"><i class="fa fa-linkedin"></i></a>
                        @endif
                    </div>
                    <!-- <div class="copyright">
                        <p>© 2021 <a href="https://hasthemes.com/">Toro.</a> All rights reserved</p>
                    </div> -->
                </div>
            </div>
        </div>