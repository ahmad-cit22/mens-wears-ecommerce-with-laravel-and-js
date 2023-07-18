@extends('pages.layouts.master')

@section('title')
	Discounted Products
@endsection

@section('content')
<div class="shop-area section-padding-3 pt-70 pb-100">
    <div class="container-fluid">
    	
        <div class="shhop-pl-35">    
            <div class="tab-content jump-3 pt-30">
                <div id="shop-1" class="tab-pane active">
                    <div class="row" id="product_filtered">
                    	<h3 class="text-center p-4">Discounted Products</h3>
                    	@foreach($products as $product)
                        <div class="col-xl-3 col-6 col-lg-4">
                            @include('pages.partials.product')
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection