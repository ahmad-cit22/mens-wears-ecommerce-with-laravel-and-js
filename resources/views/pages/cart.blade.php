@extends('pages.layouts.master')

@section('title')
	Shopping Cart
@endsection

@php
    $discount = 0;
    if(Session::has('coupon_discount')){
        $discount = Session::get('coupon_discount');
    }
@endphp

@section('content')
	<div class="breadcrumb-area section-padding-1 bg-gray breadcrumb-ptb-1">
        <div class="container-fluid">
            <div class="breadcrumb-content text-center">
                <div class="breadcrumb-title">
                    <h2>Shopping Cart</h2>
                </div>
                <ul>
                    <li>
                        <a href="{{ route('index') }}">Home </a>
                    </li>
                    <li><span> &gt; </span></li>
                    <li class="active"> Shopping Cart </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- cart start -->
    <div class="cart-main-area pt-90 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    
                    <div class="row">
                    	@if(Cart::count() > 0)
                        <div class="col-lg-8">
                            <div class="table-content table-responsive cart-table-content">
                                <table>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Product</th>
                                            <th> Price</th>
                                            <th>Quantity</th>
                                            <th>total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	@foreach(Cart::content() as $cart)
                                        <tr>
                                            <td class="product-remove">
                                                <!-- <a href="#"><i class="dlicon ui-1_simple-remove"></i></a> -->
                                                <form action="{{ route('cart.remove') }}" method="POST">
                                                	@csrf
                                                	<input type="hidden" name="rowId" value="{{ $cart->rowId }}">
                                                	<button type="submit" style="background-color: transparent;border: none;"><i class="dlicon ui-1_simple-remove"></i></button>
                                                </form>
                                            </td>
                                            <td class="product-img">
                                                <a href="#"><img src="{{ asset('images/product/'.$cart->options->image) }}" height="75" alt=""></a>
                                            </td>
                                            <td class="product-name"><a href="{{ route('single.product', [$cart->id, Str::slug($cart->name)]) }}">{{ $cart->name }} {{ $cart->options->size_name == '' ? '' : (' - '.$cart->options->size_name) }}</a></td>
                                            <td class="product-price"><span class="amount">{{ env('CURRENCY') }}{{ $cart->price }}</span></td>
                                            <td class="cart-quality">
                                                <div class="product-details-quality quality-width-cart">
                                                	<form action="{{ route('cart.update') }}" method="POST"> 
                                                		@csrf
                                                		<input type="hidden" name="rowId" value="{{ $cart->rowId }}">
                                                        <div class="cart-plus-minus">
                                                            <input class="cart-plus-minus-box" type="text" name="qty" value="{{ $cart->qty }}">

                                                        </div>
                                                        <button type="submit" class="btn btn-danger bg-black">Update</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="product-total"><span>{{ env('CURRENCY') }}{{ $cart->qty * $cart->price }}</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="cart-shiping-update-wrapper">
                                <div class="discount-code">
                                    <form action="{{ route('coupon.apply') }}" method="POST">
                                        @csrf
                                        <input type="text" required="" name="code" placeholder="Coupon code" required>
                                        <button class="coupon-btn" type="submit">Apply coupon</button>
                                        
                                    </form>
                                </div>
                                <div class="cart-clear">
                                    @if(Session::has('coupon_discount'))
                                    <a href="{{ route('coupon.remove') }}">Remove Coupon</a>
                                    @endif
                                </div>
                            </div>
                            @if(Session::has('success'))
                            <p class="alert alert-success">{{ Session::get('success') }} </p>
                            @endif
                            
                            @if(Session::has('invalid'))
                            <p class="alert alert-danger">{{ Session::get('invalid') }}</p>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <div class="grand-total-wrap">
                                <h4>Cart totals</h4>
                                <div class="grand-total-content">
                                    <ul>
                                        <li>Subtotal <span> {{ env('CURRENCY') }}{{ Cart::subTotal() }}</span></li>
                                        @if(Session::has('coupon_discount'))
                                        <li>Discount(-) <span class="text-danger"> {{ env('CURRENCY') }}{{ $discount }}</span></li>
                                        @endif
                                        <li>Total <span>{{ env('CURRENCY') }}{{ Cart::subTotal() - $discount }}</span> </li>
                                    </ul>
                                </div>
                                <div class="grand-btn">
                                    <a href="{{ route('checkout') }}">Proceed to checkout</a>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-12">
                        	<h3 class="text-center">Your cart is empty</h3>
                        	<p class="text-center">Go <a href="{{ route('index') }}" class="
                        		text-danger">Home</a></p>
                        </div>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection