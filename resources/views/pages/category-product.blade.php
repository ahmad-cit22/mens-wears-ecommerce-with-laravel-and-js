@extends('pages.layouts.master')

@section('title')
    {{ $category->title }}
@endsection

@section('content')
    <div class="shop-area section-padding-3 pt-70 pb-100">
        <div class="container-fluid">
            @if (count($category->childs) > 0)
                <div class="row">
                    <h3 class="text-center p-4">Sub Categories</h3>
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
                            <h3 class="text-center p-4">{{ $category->title }}</h3>
                            @foreach ($products as $product)
                                <div class="col-xl-3 col-6 col-lg-4">
                                    @include('pages.partials.product')
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="pro-pagination-style text-center">
                        <ul>
                            {{ $products->links('pagination::bootstrap-4') }}
                            <!-- <li><a class="active" href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#"><i class="dlicon arrows-1_tail-right"></i></a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
