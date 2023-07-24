@extends('pages.layouts.master')

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
                    <h2>Privacy Policy</h2>
                </div>
                <ul>
                    <li>
                        <a href="{{ route('index') }}">Home</a>
                    </li>
                    <li><span> > </span></li>
                    <li class="active"> Privacy Policy </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="about-us-skill pt-50 pb-50 padding-60-row-col">
        <div class="container">
            <div class="row">

                <div class="col-lg-12">
                    <h3 class="fw-bold fs-3 mb-4">{{ $page->name }}</h3>
                    {!! $page->description !!}
                </div>
            </div>
        </div>
    </div>
@endsection
