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
    <div class="shop-area section-padding-3 pt-70 pb-100">
        <div class="container-fluid">
            <h2 class="text-center p-4 mb-5">Categories</h2>
            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-6 col-lg-4 mb-2">
                        <div class="card">
                            <a href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}" class="category-media">
                                <img src="{{ asset('images/category/' . $category->image) }}" alt="Category" width="100%">
                            </a>
                            <div class="card-body p-3">
                                <h5 class="card-title text-center"><a href="{{ route('category.products', [$category->id, Str::slug($category->title)]) }}" class="">{{ $category->title }}</a></h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
