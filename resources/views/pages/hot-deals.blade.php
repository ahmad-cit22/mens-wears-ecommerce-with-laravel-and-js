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

            <div class="shhop-pl-35">
                <div class="tab-content jump-3 pt-30">
                    <div id="shop-1" class="tab-pane active">
                        <div class="row" id="product_filtered">
                            <h2 class="text-center p-4 mb-5">Hot Deals</h2>
                            @foreach ($products as $product)
                                <div class="col-xl-3 col-6 col-lg-4">
                                    @include('pages.partials.product')
                                </div>
                            @endforeach
                        </div>
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
