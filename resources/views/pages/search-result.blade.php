@extends('pages.layouts.master')

@section('title')
	Search List
@endsection

@section('content')
<div class="shop-area section-padding-3 pt-70 pb-100">
    <div class="container-fluid">
    	
        <div class="shhop-pl-35">    
            <div class="tab-content jump-3 pt-30">
                <div id="shop-1" class="tab-pane active">
                    <div class="row" id="product_filtered">
                    	@if(count($products) > 0)
                    	<h3 class="text-center p-4">Search List</h3>
                    	@foreach($products as $product)
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            @include('pages.partials.product')
                        </div>
                        @endforeach
                        @else
                        <h3 class="text-center p-4">Products not found.</h3>
                        <p class="text-center"><a href="{{ route('products') }}">Browse All Products</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection