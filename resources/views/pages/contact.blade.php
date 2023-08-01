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

@section('content')
    <div class="breadcrumb-area section-padding-1 breadcrumb-bg-4" style="padding: 100px 0">
        <div class="container-fluid">
            <div class="breadcrumb-content text-center">
                <div class="breadcrumb-title">
                    <h2>Contact Us</h2>
                </div>
                <ul>
                    <li>
                        <a href="{{ route('index') }}">Home</a>
                    </li>
                    <li><span> > </span></li>
                    <li class="active"> Contact Us </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="contact-us-area pt-50 pb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 ms-auto me-auto">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8 col-md-7">
                            <div class="contact-form-area">
                                <h2>Get a Quote</h2>
                                @if (Session::has('success'))
                                    <h3 class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </h3>
                                @endif
                                <form action="{{ route('message.send') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <input name="name" type="text" placeholder="Your Name">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <input name="subject" type="text" placeholder="Subject">
                                            @error('subject')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <input name="email" type="email" placeholder="Your Email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <input name="phone" type="text" placeholder="Your Phone">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <textarea name="message" placeholder="Your Message"></textarea>
                                            @error('message')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <button class="submit" type="submit">Send</button>
                                        </div>
                                    </div>
                                </form>
                                <p class="form-messege"></p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-5">
                            <div class="contact-info-area">
                                <h2>Get Info</h2>
                                <div class="contact-info-top">
                                    <div class="sin-contact-info-wrap mb-25">
                                        <div class="contact-icon">
                                            <i class="dlicon business_building"></i>
                                        </div>
                                        <div class="contact-address">
                                            <span>{{ $business->name }}</span>
                                            <p>{{ $business->address }}</p>
                                        </div>
                                    </div>
                                    <!-- <div class="sin-contact-info-wrap mb-25">
                                                        <div class="contact-icon">
                                                            <i class="dlicon business_building"></i>
                                                        </div>
                                                        <div class="contact-address">
                                                            <span>Toro Headquarter</span>
                                                            <p>PO Box 16122 Collins Street West Victoria 8007 Australia</p>
                                                        </div>
                                                    </div> -->
                                </div>
                                <div class="contact-info-bottom">
                                    <ul>
                                        <li><i class="dlicon ui-1_email-83"></i>{{ $business->email }}</li>
                                        <li><i class="dlicon ui-2_phone"></i>{{ $business->phone }}</li>
                                    </ul>
                                    <div class="contact-info-social">
                                        @if ($business->facebook != null)
                                            <a class="facebook" href="{{ $business->facebook }}" target="_blank"><i class="fa fa-facebook"></i></a>
                                        @endif
                                        @if ($business->twitter != null)
                                            <a class="twitter" href="{{ $business->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a>
                                        @endif
                                        @if ($business->youtube != null)
                                            <a class="youtube" href="{{ $business->youtube }}" target="_blank"><i class="fa fa-youtube"></i></a>
                                        @endif
                                        @if ($business->instagram != null)
                                            <a class="dribbble" href="{{ $business->instagram }}" target="_blank"><i class="fa fa-instagram"></i></a>
                                        @endif
                                        @if ($business->linkedin != null)
                                            <a class="facebook" href="{{ $business->linkedin }}" target="_blank"><i class="fa fa-linkedin"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-10 ms-auto me-auto">
                    <h3>OUR LOCATION</h3>
                    <hr>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14601.556398409222!2d90.3690562!3d23.804759!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x60049e2df6c2e04b!2sGo%20By%20Fabrifest!5e0!3m2!1sen!2sbd!4v1656419579274!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
