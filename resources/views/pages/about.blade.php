@extends('pages.layouts.master')

@section('title')
    {{ $page->name . ' | ' . $settings->name }}
@endsection

@section('meta_description')
    <meta name="description" content="{{ $page->meta_description }}">
@endsection

@section('meta_keywords')
    <meta name="keywords" content="{{ $page->meta_keywords }}">
@endsection

@section('content')
    <div class="breadcrumb-area section-padding-1 breadcrumb-bg-4" style="padding: 100px 0">
        <div class="container-fluid">
            <div class="breadcrumb-content text-center">
                <div class="breadcrumb-title">
                    <h2>About Us</h2>
                </div>
                <ul>
                    <li>
                        <a href="{{ route('index') }}">Home</a>
                    </li>
                    <li><span> > </span></li>
                    <li class="active"> About Us </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="about-us-skill pt-20 pb-50 padding-50-row-col">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 px-3">
                    <p style="text-align: justify">
                        "Go By Fabrifest" is a dynamic men’s fashion brand that redefines everyday style with a unique blend
                        of comfort, quality, and affordability. Specializing in an extensive collection of men's shirts,
                        t-shirts, and Punjabis, our brand focuses on creating versatile pieces that are perfect for every
                        occasion. From casual outings to festive celebrations, we offer a diverse range of designs that
                        capture the essence of modern fashion while ensuring a perfect fit for every customer.
                    </p>
                    <p style="text-align: justify">
                        Founded on the principles of innovation and excellence, "Go By Fabrifest" is committed to delivering
                        high-quality fabrics and detailed craftsmanship. Our designs reflect the latest trends, combining
                        classic elegance with contemporary flair to appeal to young men who appreciate style and substance.
                        Every garment is meticulously crafted to offer superior comfort and durability, allowing our
                        customers to express their individuality with confidence.
                    </p>
                    <p style="text-align: justify">
                        Our mission is to provide an exceptional shopping experience that goes beyond just selling clothes.
                        We believe in creating a seamless and enjoyable journey for our customers, from browsing our
                        collections online to receiving their orders at their doorstep. With a focus on customer
                        satisfaction, we offer easy returns, secure payments, and reliable customer service to make fashion
                        accessible and enjoyable for everyone.
                    </p>
                    <p style="text-align: justify">
                        At "Go By Fabrifest," we are more than just a clothing brand; we are a community of style
                        enthusiasts who believe in making a statement with every outfit. Whether you are looking for a
                        classic piece or a trendy new look, we have something for everyone. Explore our collections and
                        experience the perfect blend of quality, style, and value that defines "Go By Fabrifest."

                        Discover your next favorite outfit at Go By Fabrifest and join us on our journey to redefine men’s
                        fashion.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
