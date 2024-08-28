@extends('pages.layouts.master')

@php
    $business = App\Models\Setting::find(1);
@endphp

@section('title')
    {{ $page->name . ' | ' . $settings->name }}
@endsection

@section('meta_description')
    <meta name="description" content="{{ $page->meta_description }}">
@endsection

@section('meta_keywords')
    <meta name="keywords" content="{{ $page->meta_keywords }}">
@endsection

@section('style')
    <style type="text/css">
        /*iframe {*/
        /*    width: 100vw;*/
        /*    height: 100%;*/
        /*    pointer-events: none;*/
        /*}*/

        .slider-area {
            height: 44vw;
        }

        .slider-area video {
            height: 44vw;
        }

        /* .featured_category_img {
                                                                                height: 26vw;
                                                                            } */

        @media only screen and (max-width: 767px) {
            /*        .header-small-mobile {*/
            /*     height: 22vw; */
            /*}*/

            /* .featured_category_img {
                                                                                    height: 36vw;
                                                                                } */

            .slider-area {
                height: 60vw;
            }

            .slider-area video {
                height: 60vw;
                transform: scale(1.8);
            }

            .feature-area {
                padding-top: 120px !important;
            }

            .video-container {
                overflow: hidden;
            }
        }
    </style>
@endsection

@section('content')
    @if ($business->slider_option == 'image')
        <div class="slider-area" style="height: 33vw !important;">
        @else
            <div class="slider-area">
    @endif
    <div class="container-fluid p-0">
        @if ($business->slider_option == 'video')
            <!-- Background Video -->
            <div class="video-container">
                <div class="video-cta">
                    <!-- <h2>One is never over-dressed or under-dressed with a Little Black Dress.</h2> -->
                </div>
                <video autoplay loop muted poster="">
                    <source src="{{ asset('videos/' . $business->video) }}">
                </video>
                <!-- <iframe class="yt-video" src="https://www.youtube.com/embed/LXb3EKWsInQ?controls=0&autoplay=1&mute=1&playsinline=1&playlist=LXb3EKWsInQ&loop=1"></iframe> -->
                <!-- <iframe class="yt-video" src="https://www.youtube.com/embed/wUXrSvUAkKI?controls=0&autoplay=1&mute=1&playsinline=1&playlist=wUXrSvUAkKI&loop=1" ></iframe> -->
            </div>
            <!-- Background Video End -->
        @endif
        @if ($business->slider_option == 'image')
            <!-- Slider Section Start -->
            <div class="main-slider-active-3 owl-carousel slider-dot-position-3 slider-dot-style-2">
                @foreach ($sliders as $slider)
                    <div class="single-main-slider slider-animated-1 bg-img slider-height-hm11 align-items-center custom-d-flex"
                        style="height: 33vw !important; background-image:url({{ asset('images/slider/' . $slider->image) }});">
                        <div class="row g-0 width-100-percent">
                            <div class="col-lg-12 col-md-12">
                                <div class="main-slider-content-11-1 text-center">
                                    <h1 class="animated">{{ $slider->title }} </h1>
                                    <div class="slider-btn-2 slider-btn-2-border-white">
                                        <a class="animated" href="{{ $slider->link }}">{{ $slider->button_text }} </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <!-- Slider Section End -->
        @endif
    </div>
    </div>

    {{-- Featured Categories --}}
    <div class="banner-area feature-area section-padding-1 pt-130">
        <div class="container-fluid">
            <div class="section-title-7 mb-70 text-center">
                <h2>Featured Categories</h2>
                <p>Browse <a href="{{ route('categories') }}">All Categories</a></p>
            </div>
            <div class="row justify-content-center">
                {{-- <div class="col-6 col-lg-4">
                    <div class="banner-wrap default-overlay banner-zoom mb-30">
                        @if (count($featured_categories) > 0)
                            <div class="banner-img">
                                <a href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}"><img class="featured_category_img" src="{{ asset('images/category/' . $category->banner) }}" alt="banner"></a>
                            </div>
                            <div class="banner-content-3">
                                <h3><a href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}">{{ $category->title }}</a></h3>
                            </div>
                        @endif
                    </div>
                </div> --}}
                @foreach ($featured_categories as $category)
                    <div class="col-6 col-lg-4">
                        <div class="banner-wrap default-overlay banner-zoom mb-30">
                            @if (count($featured_categories) > 0)
                                <div class="banner-img">
                                    <a
                                        href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}"><img
                                            class="featured_category_img"
                                            src="{{ asset('images/category/' . $category->banner) }}" alt="banner"></a>
                                </div>
                                <div class="banner-content-3">
                                    <h3><a
                                            href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}">{{ $category->title }}</a>
                                    </h3>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Featured Products --}}
    <div class="product-area pt-100 pb-130">
        <div class="container-fluid p-0">
            <div class="section-title-7 mb-70 text-center">
                <h2>Featured Products</h2>
            </div>
        </div>
        <div class="product-slider product-slider-active-3 product-slider-padding-2">
            {{-- <div class="row"> --}}

            @foreach ($featured_products as $product)
                <div class="pro-all product-wrap-opacity">
                    @include('pages.partials.product')
                </div>
            @endforeach
            {{-- </div> --}}
        </div>
    </div>

    {{-- New Arrival --}}
    <div class="product-area-2 section-padding-1 pb-45">
        <div class="container-fluid">
            <div class="section-wrap-2 mb-60">
                <div class="section-title-13">
                    <h2>New Arrival</h2>
                </div>
            </div>
            <div class="tab-content jump jump-2">

                <div id="product" class="tab-pane active">
                    <div class="row">
                        @foreach (App\Models\Product::where('is_active', 1)->orderBy('id', 'DESC')->limit(8)->get() as $product)
                            <div class="col-xl-3 col-lg-4 col-6">
                                @include('pages.partials.product')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Trending --}}
    @if (!is_null($trending))
        <div class="banner-area">
            <div class="container-fluid p-0">
                <div class="section-title-7 mb-70 text-center">
                    <h2>Trending</h2>
                </div>
            </div>
            <div class="banner-bottom">
                <div class="row g-0">
                    <div class="col-lg-12 col-md-12">
                        <div class="banner-wrap banner-zoom">
                            <div class="banner-img">
                                <a
                                    href="{{ $trending->type == 'single_product' ? $trending->link : route('trending.products') }}"><img
                                        src="{{ asset('images/website/' . $trending->image) }}" alt="banner"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Best Seller --}}
    <div class="product-area section-padding-1 pt-125 pb-120">
        <div class="container-fluid">
            <div class="section-title-7 mb-70 text-center">
                <h2>Best Seller</h2>
            </div>
            <div class="product-slider product-slider-active-2 owl-carousel dot-style-2">
                @forelse ($top_sales as $product)
                    @include('pages.partials.product')
                @empty
                    {{-- <div class="row"> --}}
                    @foreach (App\Models\Product::where('is_active', 1)->orderBy('id', 'DESC')->limit(3)->get() as $product)
                        <div class="">
                            @include('pages.partials.product')
                        </div>
                    @endforeach
                    {{-- </div> --}}
                @endforelse
            </div>
        </div>
    </div>

    {{-- service-area --}}
    <div class="service-area bg-gray pt-60 pb-25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="service-wrap mb-30 text-center">
                        <div class="service-icon">
                            <i class="dlicon shopping_delivery"></i>
                        </div>
                        <div class="service-content">
                            <h4>Freeship Wordwide</h4>
                            <p>In ac hendrerit turpis. Aliquam ultrices dolor dolor, at commodo diam feugiat</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="service-wrap mb-30 text-center">
                        <div class="service-icon">
                            <i class="dlicon shopping_gift"></i>
                        </div>
                        <div class="service-content">
                            <h4>Special Offers</h4>
                            <p>In ac hendrerit turpis. Aliquam ultrices dolor dolor, at commodo diam feugiat</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="service-wrap mb-30 text-center">
                        <div class="service-icon">
                            <i class="dlicon tech-2_l-security"></i>
                        </div>
                        <div class="service-content">
                            <h4>Order Protection</h4>
                            <p>In ac hendrerit turpis. Aliquam ultrices dolor dolor, at commodo diam feugiat</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="service-wrap mb-30 text-center">
                        <div class="service-icon">
                            <i class="dlicon tech-2_headset"></i>
                        </div>
                        <div class="service-content">
                            <h4>Professional Support</h4>
                            <p>In ac hendrerit turpis. Aliquam ultrices dolor dolor, at commodo diam feugiat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.product-slider').slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            draggable: false,
            infinite: true,
            // autoplay: true,
            // autoplaySpeed: 3000,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                }
            }]
        });
    </script>
@endsection
