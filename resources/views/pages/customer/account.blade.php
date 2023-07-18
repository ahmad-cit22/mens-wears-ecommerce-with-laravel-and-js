@extends('pages.layouts.master')

@section('title')
My Account
@endsection

@section('content')
<div class="breadcrumb-area section-padding-1 bg-gray breadcrumb-ptb-1">
    <div class="container-fluid">
        <div class="breadcrumb-content text-center">
            <div class="breadcrumb-title">
                <h2>My Account</h2>
            </div>
            <ul>
                <li>
                    <a href="{{ route('index') }}">Home</a>
                </li>
                <li><span> &gt; </span></li>
                <li class="active"> My Account </li>
            </ul>
        </div>
    </div>
</div>
<div class="my-account-area pt-100 pb-95">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-12">
                <div class="myaccount-tab-menu nav" role="tablist">
                    <a href="#dashboad" class="active" data-bs-toggle="tab">
                        Dashboard</a>
                    <a href="#wishlist" data-bs-toggle="tab"> Wishlist</a>
                    <a href="#account-info" data-bs-toggle="tab"> Account Details</a>
                    
                    	<form action="{{ route('logout') }}" method="POST">
				            @csrf
				            <button class="btn btn-rounded">Logout</button>
				        </form>
                    
                </div>
                <!-- My Account Tab Menu End -->
                <!-- My Account Tab Content Start -->
                <div class="tab-content" id="myaccountContent">
                    <!-- Single Tab Content Start -->
                    <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                        <div class="myaccount-table table-responsive text-center">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->code }}</td>
                                        <td>{{ Carbon\Carbon::parse($order->created)->format('d M Y, g:ia') }}</td>
                                        <td>{{ $order->status->title }}</td>
                                        <td>{{ env('CURRENCY') }} {{ $order->price + $order->discount_amount + $order->delivery_charge }}</td>
                                        <td>
                                        	<!-- <a href="cart.html" class="check-btn sqr-btn ">View</a> -->
                                        	<a href="{{ route('order.invoice.generate', $order->id) }}" class="check-btn sqr-btn "><i class="fa fa-cloud-download"></i> Download Invoice</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Single Tab Content End -->
                    
                    <!-- Single Tab Content Start -->
                    <div class="tab-pane fade" id="wishlist" role="tabpanel">
                        <div class="myaccount-content">
                            <div class="table-content table-responsive wishlist-table-content">
	                            <table class="table ">
	                                <thead class="thead-light">
	                                    <tr>
	                                        <th></th>
	                                        <th></th>
	                                        <th>Product</th>
	                                        <th> Price</th>
	                                        <th class="wishlist-cart-none"><span>Add to cart</span></th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                	@foreach($wishlists as $wishlist)
	                                    <tr>
	                                        <td class="wishlist-remove">
	                                            <!-- <a href="#"><i class="dlicon ui-1_simple-remove"></i></a> -->
	                                            <form action="{{ route('wishlist.remove', $wishlist->id) }}" method="POST">
	                                            	@csrf
	                                            	<button style="background-color: transparent;border: none;"><i class="dlicon ui-1_simple-remove"></i></button>
	                                            </form>
	                                        </td>
	                                        <td class="wishlist-img">
	                                            <a href="#"><img src="{{ asset('images/product/'.$wishlist->product->image) }}" alt="" width="80"></a>
	                                        </td>
	                                        <td class="wishlist-name">
	                                            <a href="#">{{ $wishlist->product->title }}</a>
	                                        </td>
	                                        <td class="wishlist-price"><span class="amount">{{ $wishlist->product->type == 'single' ? (env('CURRENCY') . $wishlist->product->variation->price) : (env('CURRENCY') . $wishlist->product->variations->where('price', $wishlist->product->variations->min('price'))->first()->price . ' - '.  env('CURRENCY') . $wishlist->product->variations->where('price', $wishlist->product->variations->max('price'))->first()->price ) }}</span></td>
	                                        <td class="wishlist-cart">
	                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="view_product_details({{ $wishlist->product->id }})">Add to cart</a>
	                                        </td>
	                                    </tr>
	                                    @endforeach
	                                </tbody>
	                            </table>
	                        </div>
                        </div>
                    </div>
                    <!-- Single Tab Content End -->
                    
                    <!-- Single Tab Content Start -->
                    <div class="tab-pane fade" id="account-info" role="tabpanel">
                        <div class="myaccount-content">
                            <div class="account-details-form">
                                <form action="{{ route('customer.account.update', Auth::id()) }}" method="post" enctype="multipart/form-data">
                                	@csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="single-input-item">
                                                <label for="name" class="required">Name <span>*</span></label>
                                                <input type="text" name="name" value="{{ Auth::user()->name }}" id="name" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="single-input-item">
                                                <label for="last-name" class="required">Phone<span>*</span></label>
                                                <input type="text" name="phone" value="{{ Auth::user()->phone }}" id="last-name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-input-item">
                                        <label for="address" class="required">Address <span>*</span></label>
                                        <input type="text" name="address" value="{{ Auth::user()->address }}" id="address" />
                                    </div>
                                    <div class="single-input-item">
                                        <label for="image" class="required">Image</label>
                                        <input type="file" name="image" id="image" />
                                        <img src="{{ asset('images/website/'.Auth::user()->image) }}" width="100">
                                    </div>
                                    
                                    <div class="single-input-item">
                                        <button class="check-btn sqr-btn ">Save Changes</button>
                                    </div>
                                </form>

                                <form action="{{ route('customer.password.change') }}" method="post">
                                	@csrf
                                	<fieldset>
                                        <legend>Password change</legend>
                                        <div class="single-input-item">
                                            <label for="current-pwd" class="required">Current password</label>
                                            <input type="password" name="c_password" id="current-pwd" />
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="single-input-item">
                                                    <label for="new-pwd" class="required">New password</label>
                                                    <input type="password" name="n_password" id="new-pwd" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="single-input-item">
                                                    <label for="confirm-pwd" class="required">Confirm new password</label>
                                                    <input type="password" name="cf_password" id="confirm-pwd" />
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="single-input-item">
                                        <button class="check-btn sqr-btn "> Change Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- Single Tab Content End -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection