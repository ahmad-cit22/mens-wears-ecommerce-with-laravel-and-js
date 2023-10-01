@extends('pages.layouts.master')

@section('title')
    {{ $brand->title . ' | ' . $settings->name }}
@endsection

@section('meta_description')
    <meta name="description" content="{{ $brand->meta_description }}">
@endsection

@section('content')
    <div class="shop-area section-padding-3 pt-70 pb-100">
        <div class="container-fluid">

            <div class="shhop-pl-35">
                <div class="tab-content jump-3 pt-30">
                    <div id="shop-1" class="tab-pane active">
                        <div class="row" id="product_filtered">
                            <h3 class="text-center p-4">{{ $brand->title }}</h3>
                            @foreach ($products as $product)
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    @include('pages.partials.product')
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="pro-pagination-style text-center">
                        @php
                            $total = $products->total();
                            $currentPage = $products->currentPage();
                            $perPage = $products->perPage();
                            
                            $from = ($currentPage - 1) * $perPage + 1;
                            $to = min($currentPage * $perPage, $total);
                        @endphp

                        <p class="ml-4">
                            Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-4">{{ $products->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
