@extends('pages.layouts.master')

@section('title')
    {{ $category->meta_title ? $category->meta_title . ' | ' . $settings->name : $category->title . ' | ' . $settings->name }}
@endsection

@section('meta_description')
    <meta name="description" content="{{ $category->meta_description }}">
@endsection

@section('meta_keywords')
    <meta name="keywords" content="{{ $category->meta_keywords }}">
@endsection

@section('content')
    <div class="shop-area section-padding-3 pt-70 pb-100">
        <div class="container-fluid">
            @if (count($category->childs) > 0)
                <div class="row">
                    <h2 class="text-center p-4 mb-4">Sub Categories</h2>
                    @foreach ($category->childs as $child)
                        <div class="col-6 col-lg-4 mb-2">
                            <div class="card">
                                <a href="{{ route('category.products', [$child->id, Str::slug($child->title)]) }}" class="category-media">
                                    <img src="{{ asset('images/category/' . $child->image) }}" alt="Category" width="100%">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title text-center"><a href="{{ route('category.products', [$child->id, Str::slug($child->title)]) }}" class="">{{ $child->title }}</a></h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="shhop-pl-35">
                <div class="tab-content jump-3 pt-30">
                    <div id="shop-1" class="tab-pane active">
                        <div class="row" id="product_filtered">
                            <h2 class="text-center p-4 mb-4">{{ $category->title }}</h2>
                            @foreach ($products as $product)
                                <div class="col-xl-3 col-6 col-lg-4">
                                    @include('pages.partials.product')
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="">
                        {{-- @php
                            $total = $products->total();
                            $currentPage = $products->currentPage();
                            $perPage = $products->perPage();
                            
                            $from = ($currentPage - 1) * $perPage + 1;
                            $to = min($currentPage * $perPage, $total);
                        @endphp

                        <p class="ml-4">
                            Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                        </p> --}}
                        <div class="row justify-content-center">
                            <div class="col-4">{{ $products->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
