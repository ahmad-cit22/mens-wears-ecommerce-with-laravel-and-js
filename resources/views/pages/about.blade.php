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
    <div class="about-us-skill pt-50 pb-50 padding-50-row-col">
        <div class="container">
            <div class="row">
                <!-- <div class="col-lg-6">
                                                                <div class="skill-img default-overlay">
                                                                    <img src="{{ asset('images/banner/about-01.jpg') }}" alt="banner">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="skill-content">
                                                                    <h2>Fashion Luxury</h2>
                                                                    <p>Donec accumsan auctor iaculis. Sed suscipit arcu ligula, at egestas magna molestie a. Proin ac ex maximus, ultrices justo eget, sodal</p>
                                                                    <div class="skill-bar">
                                                                        <div class="skill-bar-item">
                                                                            <span>WEB DEVELOPMENT </span>
                                                                            <div class="progress">
                                                                                <div class="progress-bar wow fadeInLeft" data-progress="95%" data-wow-duration="1.5s" data-wow-delay="1.2s">
                                                                                    <span class="text-top">95%</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="skill-bar-item">
                                                                            <span>DESIGN FOR SEO </span>
                                                                            <div class="progress">
                                                                                <div class="progress-bar wow fadeInLeft" data-progress="85%" data-wow-duration="1.5s" data-wow-delay="1.2s">
                                                                                    <span class="text-top">85%</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="skill-bar-item">
                                                                            <span>DEDICATED SUPPORT</span>
                                                                            <div class="progress">
                                                                                <div class="progress-bar wow fadeInLeft" data-progress="80%" data-wow-duration="1.5s" data-wow-delay="1.2s">
                                                                                    <span class="text-top">90%</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="skill-bar-item">
                                                                            <span>POWERFUL ADMIN INFERFACE</span>
                                                                            <div class="progress">
                                                                                <div class="progress-bar wow fadeInLeft" data-progress="99%" data-wow-duration="1.5s" data-wow-delay="1.2s">
                                                                                    <span class="text-top">90%</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                <div class="col-lg-12 px-3">
                    <h3>ABOUT US</h3>
                    <p style="text-align: justify">
                        GO by Fabrifest is a clothing brand for men which produce lifestyle goods with its own designs and Supervision. It's all products are designed by its own designer. And very few quantity are produce for each design. when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of
                        Letraset sheets containing Lorem Ipsum passages, and more
                        recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </p>
                    <p style="text-align: justify">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more
                        recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </p>
                    <p style="text-align: justify">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more
                        recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
