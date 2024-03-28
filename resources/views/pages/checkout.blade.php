@extends('pages.layouts.master')

@php
    $discount = 0;
    if (Session::has('coupon_discount')) {
        $discount = Session::get('coupon_discount');
    }
@endphp

@section('title')
    Checkout
@endsection

@section('content')
    <div class="breadcrumb-area section-padding-1 bg-gray breadcrumb-ptb-1">
        <div class="container-fluid">
            <div class="breadcrumb-content text-center">
                <div class="breadcrumb-title">
                    <h2>Checkout</h2>
                </div>
                <ul>
                    <li>
                        <a href="{{ route('index') }}">Home</a>
                    </li>
                    <li><span> &gt; </span></li>
                    <li class="active">Checkout </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- checkout start -->
    <div class="checkout-main-area pt-100 pb-100">
        <div class="container">
            @if (Cart::count() > 0)
                <div class="customer-zone mb-30">
                    <p class="cart-page-title">Have a coupon? <a class="checkout-click" href="#">Click here to enter your code</a></p>
                    <div class="checkout-login-info">
                        <p>If you have a coupon code, please apply it below.</p>
                        <form action="{{ route('coupon.apply') }}" method="POST">
                            @csrf
                            <input type="text" name="code" placeholder="Coupon code">
                            <input type="submit" value="Apply Coupon">
                            @if (Session::has('success'))
                                <p class="alert alert-success">{{ Session::get('success') }} </p>
                            @endif

                            @if (Session::has('invalid'))
                                <p class="alert alert-danger">{{ Session::get('invalid') }}</p>
                            @endif
                            @if (Session::has('coupon_discount'))
                                <div>
                                    <a href="{{ route('coupon.remove') }}">Remove Coupon</a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="checkout-wrap">
                    <form id="checkout-form" action="{{ route('order.create') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="billing-info-wrap">
                                    <h3>Billing Details</h3>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="billing-info mb-25">
                                                <label> Name <abbr class="required" title="required">*</abbr></label>
                                                <input type="text" name="name" value="{{ Auth::check() ? Auth::user()->name : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="billing-info mb-25">
                                                <label>Phone <abbr class="required" title="required">*</abbr></label>
                                                <input type="text" name="phone" value="{{ Auth::check() ? Auth::user()->phone : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="billing-info mb-25">
                                                <label>Email Address <abbr class="required" title="required">*</abbr></label>
                                                <input type="email" name="email" value="{{ Auth::check() ? Auth::user()->email : '' }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="billing-select mb-25">
                                                <label>District <abbr class="required" title="required">*</abbr></label>
                                                <select class="select-active @error('district_id') is-invalid @enderror" name="district_id" id="district_id" required>
                                                    <option>--- Select ---</option>
                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('district_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="billing-select mb-25">
                                                <label>Area <abbr class="required" title="required">*</abbr></label>
                                                <select class="select-active @error('area_id') is-invalid @enderror" name="area_id" id="areas" required>
                                                    <option value="">Please Choose an Area</option>
                                                </select>
                                                @error('area_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="billing-info mb-25">
                                                <label>Street address <abbr class="required" title="required">*</abbr></label>
                                                <input class="billing-address" name="shipping_address" placeholder="House number and street name" type="text" value="{{ Auth::check() ? Auth::user()->address : '' }}">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="additional-info-wrap">
                                        <h3>Additional information</h3>
                                        <label>Order notes (optional)</label>
                                        <textarea placeholder="Notes about your order, e.g. special notes for delivery. " name="note"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="your-order-area">
                                    <h3>Your order</h3>
                                    <div class="your-order-wrap gray-bg-4">
                                        <div class="your-order-info-wrap">
                                            <div class="your-order-info">
                                                <ul>
                                                    <li>Product <span>Total</span></li>
                                                </ul>
                                            </div>
                                            <div class="your-order-middle">
                                                <ul>
                                                    @foreach (Cart::content() as $cart)
                                                        <li>{{ $cart->name }}{{ $cart->options->size_name == '' ? '' : ' - ' . $cart->options->size_name }} X {{ $cart->qty }} <span>{{ env('CURRENCY') }}{{ $cart->price * $cart->qty }} </span></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="your-order-info order-subtotal">
                                                <ul>
                                                    <input type="hidden" name="subtotal" id="subtotal" value="{{ Cart::subtotal() - $discount }}" />
                                                    <li>Subtotal <span>{{ env('CURRENCY') }}{{ Cart::subTotal() }} </span></li>
                                                </ul>
                                            </div>
                                            <div class="your-order-info order-subtotal">
                                                <ul>
                                                    <input type="hidden" name="shipping_charge" id="shipping_charge" value="0" />
                                                    <li>Shipping Charge <span>{{ env('CURRENCY') }}<b id="shipping_charge_label">0</b> </span></li>
                                                </ul>
                                            </div>
                                            <div class="your-order-info order-subtotal">
                                                <ul>
                                                    <li>Discount(-) <span>{{ env('CURRENCY') }}{{ $discount }} </span></li>
                                                </ul>
                                            </div>
                                            <div class="your-order-info order-total">
                                                <ul>
                                                    <li>Total <span>{{ env('CURRENCY') }}<b id="total"> {{ Cart::subTotal() - $discount }}</b> </span></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <h3 class="mt-4">Payment Method</h3>
                                        <div class="form-group">
                                            <select name="payment_method" id="payment_option" class="form-control">
                                                <option value="Cash on Delivery">Cash on Delivery</option>
                                                <option value="Bkash">Bkash</option>
                                                <option value="Rocket">Rocket</option>
                                            </select>
                                        </div>
                                        <div class="hidden" id="cod">

                                        </div>
                                        <div class="hidden" id="bkash">
                                            <h4 class="alert-success mt-4">Bkash (01xxxxxxxxx)</h4>
                                            <input type="text" placeholder="Transaction Id" name="bkash_transaction_id" class="form-control mb-2">
                                            <input type="text" placeholder="Phone" name="bkash_phone" class="form-control mb-2">
                                            <input type="text" placeholder="Amount" name="bkash_amount" class="form-control mb-2">

                                        </div>
                                        <div class="hidden" id="rocket">
                                            <h4 class="alert-success mt-4">Rocket (01xxxxxxxxx)</h4>
                                            <input type="text" placeholder="Transaction Id" name="rocket_transaction_id" class="form-control mb-2" id="transaction_id">
                                            <input type="text" placeholder="Phone" name="rocket_phone" class="form-control mb-2">
                                            <input type="text" placeholder="Amount" name="rocket_amount" class="form-control mb-2">
                                        </div>
                                        <div class="condition-wrap">
                                            <div class="condition-form mb-25">
                                                <input type="checkbox" required>
                                                <span>I have read and agree to the website <a href="{{ route('term.condition') }}"><b>terms and conditions</b></a><span class="star">*</span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Place-order mt-30">
                                        <button id="checkout-submit" type="submit">Place Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="">
                    <h3 class="text-center">Your cart is empty</h3>
                    <p class="text-center">Go <a href="{{ route('index') }}" class="
        		text-danger">Home</a></p>
                </div>
            @endif
        </div>
    </div>
    <!-- checkout end -->
@endsection

@section('scripts')
    <script>
        $('#checkout-form').submit(function() {
            $('#checkout-submit').prop('disabled', true);
            $('#checkout-submit').text('Processing...');
        });
    </script>

    <script>
        $('#district_id').change(function() {
            var district_id = $(this).val();
            if (district_id == '') {
                district_id = -1;
            }
            var option = "<option value=''>Please Chose an Area</option>";
            var url = "{{ url('/') }}";

            $.get(url + "/get-area/" + district_id, function(data) {
                data = JSON.parse(data);
                data.forEach(function(element) {
                    option += "<option value='" + element.id + "'>" + element.name + "</option>";
                });
                //console.log(option);
                $('#areas').html(option);
            });

        });
    </script>

    <script>
        $('#areas').change(function() {
            var area_id = $(this).val();
            if (area_id == '') {
                area_id = -1;
            }
            var url = "{{ url('/') }}";

            var subtotal = $('#subtotal').val();
            $.ajax({
                url: url + "/get-shipping-charge",
                type: "POST",
                data: {
                    area_id: area_id,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    $('#shipping_charge_label').html(response);
                    $('#total').html(parseInt(subtotal) + parseInt(response));
                    $('#shipping_charge').val(response);
                }
            });

        });
    </script>
@endsection
