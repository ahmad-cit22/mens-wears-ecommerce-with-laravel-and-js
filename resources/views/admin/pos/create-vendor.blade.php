@php
    $business = App\Models\Setting::find(1);

    $discount = 0;
    if (Session::has('coupon_discount')) {
        $discount = Session::get('coupon_discount');
    }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Display Center POS | {{ $business->name }}</title>
    <meta name="description" content="Updates and statistics" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" /> -->
    <!--end::Fonts-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ asset('assets/css/stylec619.css?v=1.0') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->

    <link href="{{ asset('assets/api/pace/pace-theme-flat-top.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/api/mcustomscrollbar/jquery.mCustomScrollbar.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/unpkg.com/multiple-select%401.5.2/dist/multiple-select.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css') }}" />

    <link rel="shortcut icon" type="image/png" href="{{ asset('images/website/' . $business->favicon) }}" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="tc_body" class="header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed">
    <!-- Paste this code after body tag -->
    <div class="se-pre-con">
        <div class="pre-loader">
            <img class="img-fluid" src="{{ asset('assets/images/loadergif.gif') }}" alt="loading">
        </div>
    </div>
    <!-- pos header -->

    <header class="pos-header bg-white">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="greeting-text">
                        <h3 class="card-label mb-0 font-weight-bold text-primary">WELCOME
                        </h3>
                        <h3 class="card-label mb-0 ">
                            {{ $business->name }} - Display Center POS
                        </h3>
                    </div>

                </div>
                <div class="col-xl-4 col-lg-5 col-md-6  clock-main">
                    <div class="clock">
                        <div class="datetime-content">
                            <ul>
                                <li id="hours"></li>
                                <li id="point1">:</li>
                                <li id="min"></li>
                                <li id="point">:</li>
                                <li id="sec"></li>
                            </ul>
                        </div>
                        <div class="datetime-content">
                            <div id="Date" class=""></div>
                        </div>

                    </div>

                </div>
                <div class="col-xl-4 col-lg-3 col-md-12  order-lg-last order-second">

                    <div class="topbar justify-content-end">
                        <div class="dropdown mega-dropdown">
                            <div id="id2" class="topbar-item " data-toggle="dropdown" data-display="static">
                                <div class="btn btn-icon w-auto h-auto btn-clean d-flex align-items-center py-0 mr-3">

                                    <span class="symbol symbol-35 symbol-light-success">
                                        <span class="symbol-label bg-primary  font-size-h5 ">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="#fff" class="bi bi-calculator-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm2 .5v2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5zm0 4v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zM4.5 9a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zM4 12.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zM7.5 6a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zM7 9.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm.5 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zM10 6.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm.5 2.5a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5h-1z" />
                                            </svg>
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="dropdown-menu dropdown-menu-right calu" style="min-width: 248px;">
                                <div class="calculator">
                                    <div class="input" id="input"></div>
                                    <div class="buttons">
                                        <div class="operators">
                                            <div>+</div>
                                            <div>-</div>
                                            <div>&times;</div>
                                            <div>&divide;</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="leftPanel">
                                                <div class="numbers">
                                                    <div>7</div>
                                                    <div>8</div>
                                                    <div>9</div>
                                                </div>
                                                <div class="numbers">
                                                    <div>4</div>
                                                    <div>5</div>
                                                    <div>6</div>
                                                </div>
                                                <div class="numbers">
                                                    <div>1</div>
                                                    <div>2</div>
                                                    <div>3</div>
                                                </div>
                                                <div class="numbers">
                                                    <div>0</div>
                                                    <div>.</div>
                                                    <div id="clear">C</div>
                                                </div>
                                            </div>
                                            <div class="equal" id="result">=</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <a class="btn btn-success btn-icon btn-clean px-3 py-1 mr-2" href="{{ route('sell.wholesale.index') }}">Wholesale List</a>
                        <a class="btn btn-success btn-icon btn-clean px-3 py-1" href="{{ route('sell.index') }}">Retail Sell List</a>
                    </div>

                </div>
            </div>
        </div>
    </header>
    <div class="contentPOS">
        <div class="container-fluid">
            <form action="{{ route('pos.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xl-4 order-xl-first order-last">
                        <div class="card card-custom gutter-b bg-white border-0" style="height: auto;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-4">
                                    <input type="text" class="form-control border-dark" id="product_name" placeholder="Product Name">
                                </div>
                                <div class="d-flex justify-content-between colorfull-select">
                                    <div class="selectmain">
                                        <select class="w-170px bag-primary" id="category_id">
                                            <option value="all">Please Select Category</option>
                                            @foreach ($categories as $category)
                                                @if ($category->parent_id == null)
                                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="selectmain">
                                        <select class="w-160px bag-secondary" id="brand_id">
                                            <option value="all">Please Select Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div align="right">
                                        <button class="btn btn-info white" id="filter-btn" type="button" style="padding: 4.5px 10px !important;">Search</button>
                                    </div>
                                </div>
                            </div>
                            <div class="product-items">
                                <div class="row" id="product_filtered">
                                    @foreach ($products as $product)
                                        @if (!is_null($product) && $product->product->is_active && $product->qty > 0)
                                            <div class="col-xl-4 col-lg-2 col-md-3 col-sm-4 col-6">
                                                <div class="productCard">
                                                    <a onclick="add_cart({{ $product->id }})" style="cursor: pointer;">
                                                        <div class="productThumb">
                                                            <img class="img-fluid" src="{{ asset('images/product/pos_images/' . $product->product->image) }}" alt="{{ $product->product->title }}">
                                                        </div>

                                                        <div class="productContent">

                                                            {{ $product->product->title }}{{ is_null($product->size) ? '' : ' - ' . optional($product->size)->title }} ({{ $product->qty }})

                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-8 col-md-8">
                        <div class="">
                            <div class="card card-custom gutter-b bg-white border-0 table-contentpos">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <h6 class="text-dark fw-bold">Choose a Customer - </h6>
                                        <select class="mb-3 select2 select-down arabic-select2" style="width: 100%;" name="customer_id" id="customer_id">
                                            <option value="0">Walk in Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ $fos_order != null ? ($fos_order->customer_id == $customer->id ? 'selected' : '') : '' }}>{{ $customer->name }} - {{ $customer->phone }}</option>
                                            @endforeach
                                        </select>
                                        <div id="new-customer-form">
                                            <div class="form-group row mt-3">

                                                <div class="col-md-12">
                                                    <label class="text-body">Customer Name <span class="text-danger">*</span></label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Customer Name" value="{{ old('name') }}" @if ($fos_order == null) required @endif>
                                                    </fieldset>
                                                    {{-- @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror --}}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label class="text-body">Email</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="email" name="email" class="form-control" placeholder="Enter E-mail" value="{{ old('email') }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-body">Phone <span class="text-danger">*</span></label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter Phone Number" value="{{ old('phone') }}" @if ($fos_order == null) required @endif>
                                                        <span id="err-phone" class="invalid-feedback"></span>
                                                        {{-- @error('phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror --}}
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-6">
                                                <label class="text-body">Address <span class="text-danger">*</span></label>
                                                <fieldset class="form-group mb-3">
                                                    <input type="text" class="form-control " placeholder="Enter Address" name="shipping_address" value="{{ $fos_order != null ? $fos_order->shipping_address : old('shipping_address') }}" required>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="text-body">Membership Card No.</label>
                                                <fieldset class="form-group mb-3">
                                                    <input type="text" class="form-control" placeholder="Enter Membership Card No." id="card_no" name="card_no" value="{{ old('card_no') }}">
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row colorfull-select">

                                        <div class="col-md-6">
                                            <label class="text-body">Membership Status</label>
                                            <fieldset class="form-group mb-3">
                                                <input type="text" class="form-control" placeholder="Membership Status" id="card_status" name="card_status" readonly>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="selectmain">
                                                <label class="text-dark d-flex">Courier Name <span class="text-danger">*</span></label>
                                                <select name="courier_id" class="select2 select-down" id="courier_id">
                                                    <option value="0">--- Select an Option ---</option>
                                                    @foreach ($couriers as $courier)
                                                        <option value="{{ $courier->id }}" {{ $fos_order != null ? ($fos_order->courier_id == $courier->id ? 'selected' : '') : '' }}>{{ $courier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row colorfull-select">
                                        <div class="selectmain col-md-6">
                                            <label class="text-dark d-flex">District <span class="text-danger">*</span></label>
                                            <select name="district_id" class="select2 select-down" id="district_id" required>
                                                <option value="">--- Select ---</option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="selectmain col-md-6">
                                            <label class="text-dark d-flex">Area <span class="text-danger">*</span></label>
                                            <select name="area_id" class="select2 select-down" id="areas" required>
                                                <!-- <option value="">Please Select an Area</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label id="remove_shipping_charge_label" class="ms-2 d-inline-block"><input class="mr-1" type="checkbox" id="remove_shipping_charge" name="remove_shipping_charge" value="1">Free Shipping</label>

                                    </div>
                                    <div class="mt-2">

                                        <div class="form-group row justify-content-start">
                                            <div class="col-md-6">
                                                <label id="advance_shipping_charge_label" class="ms-2 d-inline-block"><input type="checkbox" id="advance_shipping_charge" name="advance_shipping_charge" value="1"> Shipping Charge Advanced</label>
                                            </div>
                                            <div class="col-md-6 text-start" id="showChargeBox" style="display: none">
                                                <label class="text-body">Enter Charge Amount</label>
                                                <fieldset class="form-group mb-3">
                                                    <input type="number" name="advanced_charge" id="advanced_charge" class="form-control" placeholder="Advanced Delivery Charge Amount" value="{{ $fos_order != null ? $fos_order->advance : 0 }}">
                                                </fieldset>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="mt-2">
                                        <label class="text-body">Add Note</label>
                                        <fieldset class="form-group">
                                            <textarea name="note" id="note" class="form-control" placeholder="Add Notes If Needed">{{ $fos_order != null ? $fos_order->note : old('note') }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-custom gutter-b bg-white border-0 table-contentpos">
                                <div class="card-body">
                                    <div class="form-group row mb-0">
                                        <div class="col-md-12">
                                            <label>Select Product</label>
                                            <fieldset class="form-group mb-0 d-flex barcodeselection">
                                                <select class="form-control w-25" id="exampleFormControlSelect1">
                                                    <option>Bar Code</option>
                                                </select>
                                                <input type="text" class="form-control border-dark" id="barcode" placeholder="" autofocus>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-datapos">
                                    <div class="table-responsive" id="printableTable">
                                        <table id="" class="table table-bordered table-striped display" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Subtotal</th>
                                                    <th class=" text-right no-sort">
                                                        <i class="fas fa-times"></i>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="cart_table">
                                                @foreach ($carts as $cart)
                                                    @include('admin.pos.partials.cart-item')
                                                @endforeach


                                            </tbody>
                                        </table>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4">
                        <div class="card card-custom gutter-b bg-white border-0">
                            <div class="card-body">
                                <div class="shop-profile">
                                    <div class="media">
                                        <div class="bg-primary w-100px h-100px d-flex justify-content-center align-items-center">
                                            <h2 class="mb-0 white">G</h2>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h3 class="title font-weight-bold">{{ $business->name }}</h3>
                                            <p class="phoonenumber">
                                                {{ $business->phone }}
                                            </p>
                                            <p class="adddress">
                                                {{ $business->address }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="resulttable-pos">
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="Discount Amount" id="discount_amount" value="{{ $fos_order != null ? $fos_order->discount_amount : old('discount_amount') }}" onblur="apply_discount()">
                                    </div>

                                    <table class="table right-table">

                                        <tbody>
                                            <tr class="d-flex align-items-center justify-content-between">
                                                <th class="border-0 font-size-h5 mb-0 font-size-bold text-dark">
                                                    Total Items
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-dark font-size-base" id="total_count">{{ Cart::count() }}</td>

                                            </tr>
                                            <tr class="d-flex align-items-center justify-content-between">
                                                <th class="border-0 font-size-h5 mb-0 font-size-bold text-dark">
                                                    Subtotal
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-dark font-size-base">
                                                    {{ env('CURRENCY') }}<span id="subtotal">{{ Cart::subtotal() }}</span>
                                                    <input type="hidden" name="subtotal" id="subtotal_amount" value="{{ Cart::subtotal() }}">
                                                </td>

                                            </tr>

                                            <tr class="d-flex align-items-center justify-content-between">
                                                <th class="border-0">
                                                    <div class="d-flex align-items-center font-size-h5 mb-0 font-size-bold text-dark">
                                                        Shipping Charge

                                                    </div>
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-dark font-size-base">
                                                    {{ env('CURRENCY') }}<span id="shipping_charge_label">0</span>
                                                    <input type="hidden" name="shipping_charge" id="shipping_charge" value="0" />
                                                </td>

                                            </tr>
                                            <tr class="d-flex align-items-center justify-content-between">
                                                <th class="border-0">
                                                    <div class="d-flex align-items-center font-size-h5 mb-0 font-size-bold text-dark">
                                                        Charge Advanced
                                                    </div>
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-dark font-size-base">
                                                    {{ env('CURRENCY') }}<span id="charge_advanced_label">0</span>
                                                </td>

                                            </tr>
                                            <tr class="d-flex align-items-center justify-content-between">
                                                <th class="border-0 font-size-h5 mb-0 font-size-bold text-dark">
                                                    Discount
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-dark font-size-base">
                                                    {{ env('CURRENCY') }}<span id="discount_amount_label">{{ $discount }}</span>
                                                </td>
                                            </tr>
                                            <tr class="d-flex align-items-center justify-content-between">
                                                <th id="member_discount_label" class="border-0 font-size-h5 mb-0 font-size-bold text-dark" style="display: none">
                                                    Membership Discount
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-dark font-size-base">
                                                    <span id="membership_discount" class="text-success"></span>
                                                </td>
                                            </tr>
                                            <input type="hidden" name="discount" id="discount" value="{{ $discount }}">
                                            <input type="hidden" name="member_discount_rate" id="member_discount_rate" value="0">
                                            <input type="hidden" name="member_discount_amount" id="member_discount_amount" value="0">
                                            <input type="hidden" name="redeem_points_amount" id="redeem_points_amount" value="0">

                                            <tr class="d-flex align-items-center justify-content-between">
                                                <th id="point_row" class="border-0 font-size-h5 mb-0 font-size-bold text-dark" style="display: none">
                                                    Member Points Remaining
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-dark font-size-base">
                                                    <span id="point_label" class="text-success"></span>
                                                </td>
                                            </tr>
                                            <tr class="d-flex align-items-center justify-content-between">
                                                <th id="point_input_label" class="border-0 font-size-h5 mb-0 font-size-bold text-dark" style="display: none">
                                                    Redeem Points
                                                </th>
                                                <td>
                                                    <div id="point_input" class="input-group" style="display: none">
                                                        <input type="number" class="form-control" placeholder="Redeem Points" id="redeem_points" value="{{ old('redeem_points') }}">
                                                    </div>
                                                    <span id="point_error" class="text-danger font-weight-bold fs-6"></span>
                                                </td>
                                            </tr>

                                            <tr class="d-flex align-items-center justify-content-between item-price">
                                                <th class="border-0 font-size-h5 mb-0 font-size-bold text-primary">

                                                    TOTAL
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-primary font-size-base">{{ env('CURRENCY') }}<span id="total_amount">{{ Cart::subtotal() - $discount }}</span></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div align="right">
                                    <button class="btn btn-primary white mb-2" type="submit"><i class="fas fa-money-bill-wave mr-2"></i>Save</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-left" id="choosecustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel13" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="myModalLabel13"> Customer Details</h3>
                                <button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-dismiss="modal" aria-label="Close">
                                    <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> -->
    @include('admin.partials.scripts')
    <script src="{{ asset('assets/js/plugin.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/unpkg.com/multiple-select%401.5.2/dist/multiple-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert1.js') }}"></script>
    <script src="{{ asset('assets/js/script.bundle.js') }}"></script>
    <script>
        jQuery(function() {
            jQuery('.arabic-select').multipleSelect({
                filter: true,
                filterAcceptOnEnter: true
            })
        });
        jQuery(function() {
            jQuery('.js-example-basic-single').multipleSelect({
                filter: true,
                filterAcceptOnEnter: true
            })
        });
        jQuery(document).ready(function() {
            jQuery('#orderTable').DataTable({

                "info": false,
                "paging": false,
                "searching": false,

                "columnDefs": [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
        });
    </script>

    @if (session('errMsg'))
        <script>
            Swal.fire(
                'Oops!',
                "{{ session('errMsg') }}",
                'error'
            );
        </script>
    @endif

    @if ($fos_order != null)
        <script>
            $('#new-customer-form').hide();

            var url = "{{ route('pos.check.membership') }}";
            // alert(url);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    customer_id: {{ $fos_order->customer_id }},
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.status) {
                        $('#card_status').val(response.card.card_status);
                        $('#card_no').val(response.card_number);
                        $('#point_row').show();
                        $('#point_label').html(response.member.current_points);
                        $('#point_input').show();
                        $('#point_input_label').show();
                        $('#member_discount_label').show();
                        if (response.card.min_point > response.member.current_points) {
                            $('#redeem_points').prop('disabled', true);
                            $('#point_error').html('Point Not Sufficient to Redeem!');
                        } else {
                            $('#redeem_points').prop('disabled', false);
                            $('#point_error').html('');
                        }
                        Swal.fire(
                            'Done!',
                            "Member Found",
                            'success'
                        );

                        var subtotal = $('#subtotal_amount').val();
                        $('#member_discount_rate').val(response.card.discount_rate);

                        let membership_discount = Math.round(subtotal * (response.card.discount_rate / 100));
                        $('#member_discount').show();
                        $('#membership_discount').html("{{ env('CURRENCY') }}" + membership_discount);
                        $('#member_discount_amount').val(membership_discount);
                        var shipping_charge = $('#shipping_charge').val();
                        var advanced_charge = $('#advanced_charge').val();
                        var discount = $('#discount').val();

                        $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(discount) - parseInt(membership_discount));
                    } else {
                        $('#card_status').val('');
                        $('#card_no').val('');
                        $('#point_row').hide();
                        $('#point_label').html('');
                        $('#point_input').hide();
                        $('#member_discount').hide();
                        $('#point_input_label').hide();
                        $('#member_discount_label').hide();
                        $('#membership_discount').html('');
                        $('#member_discount_amount').val('');
                        $('#member_discount_rate').val('');
                        $('#card_status').val(response.card);
                    }
                    console.log(response);
                }
            });
        </script>

        <script>
            var subtotal = $('#subtotal_amount').val();
            var discount = $('#discount').val();
            var shipping_charge = $('#shipping_charge').val();
            var charge_advanced = $('#advanced_charge').val();

            let membership_discount = $('#member_discount_amount').val();
            var redeem_points = $('#redeem_points_amount').val();

            if (charge_advanced > 0) {
                $('#charge_advanced_label').html(charge_advanced);
                $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(charge_advanced) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
            }
        </script>

        @if ($fos_order->discount_amount != null)
            <script>
                var amount = $('#discount_amount').val();
                var subtotal = $('#subtotal_amount').val();
                if (amount != '') {
                    var url = "{{ route('pos.apply.discount') }}";

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            amount: amount,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            var shipping_charge = $('#shipping_charge').val();
                            var advanced_charge = $('#advanced_charge').val();

                            let membership_discount = $('#member_discount_amount').val();
                            var redeem_points = $('#redeem_points_amount').val();

                            $('#discount_amount_label').html(response);
                            $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(response) - parseInt(membership_discount) - parseInt(redeem_points));
                            $('#discount').val(response);
                        }
                    });
                } else {
                    var shipping_charge = $('#shipping_charge').val();
                    var advanced_charge = $('#advanced_charge').val();

                    let membership_discount = $('#member_discount_amount').val();
                    var redeem_points = $('#redeem_points_amount').val();

                    $('#discount_amount_label').html(0);
                    $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(0) - parseInt(membership_discount) - parseInt(redeem_points));
                    $('#discount').val(0);
                }
            </script>
        @endif
    @else
        <script>
            $('#discount').val(0);
            var subtotal = $('#subtotal_amount').val();
            var shipping_charge = $('#shipping_charge').val();
            var discount = $('#discount').val();
            var advanced_charge = $('#advanced_charge').val();
            $('#discount_amount_label').html(discount);

            let membership_discount = $('#member_discount_amount').val();
            var redeem_points = $('#redeem_points_amount').val();

            $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
        </script>
    @endif

    <script>
        jQuery(function() {
            jQuery('#category_id').multipleSelect({
                filter: true,
                filterAcceptOnEnter: true
            }).change(function() {
                // load_product();
            })
        });

        jQuery(function() {
            jQuery('#brand_id').multipleSelect({
                filter: true,
                filterAcceptOnEnter: true
            }).change(function() {
                // load_product();
            })
        });

        function load_product() {
            var product_name = $('#product_name').val();
            var category_id = $('#category_id').val();
            var brand_id = $('#brand_id').val();
            // window.alert(product_name + ' - ' + category_id + ' - ' + brand_id
            // 	);

            url = "{{ route('pos.product.filter') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    category_id: category_id,
                    brand_id: brand_id,
                    product_name: product_name,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    //console.log(response.product_filtered);
                    $('#product_filtered').html(response.product_filtered);
                }
            });
        }

        $('#customer_id').change(function() {
            var customer_id = $(this).val();

            if (customer_id == '0') {
                $('#new-customer-form').show();

                $("#name").prop('required', true);
                $("#phone").prop('required', true);

            } else {
                $('#new-customer-form').hide();

                $("#name").prop('required', false);
                $("#phone").prop('required', false);

                var url = "{{ route('pos.check.membership') }}";
                // alert(url);
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        customer_id: customer_id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#card_status').val(response.card.card_status);
                            $('#card_no').val(response.card_number);
                            $('#point_row').show();
                            $('#point_label').html(response.member.current_points);
                            $('#point_input').show();
                            $('#point_input_label').show();
                            $('#member_discount_label').show();
                            if (response.card.min_point > response.member.current_points) {
                                $('#redeem_points').prop('disabled', true);
                                $('#point_error').html('Point Not Sufficient to Redeem!');
                            } else {
                                $('#redeem_points').prop('disabled', false);
                                $('#point_error').html('');
                            }
                            Swal.fire(
                                'Done!',
                                "Member Found",
                                'success'
                            );

                            var subtotal = $('#subtotal_amount').val();
                            $('#member_discount_rate').val(response.card.discount_rate);

                            let membership_discount = Math.round(subtotal * (response.card.discount_rate / 100));
                            $('#member_discount').show();
                            $('#membership_discount').html("{{ env('CURRENCY') }}" + membership_discount);
                            $('#member_discount_amount').val(membership_discount);
                            var shipping_charge = $('#shipping_charge').val();
                            var advanced_charge = $('#advanced_charge').val();
                            var discount = $('#discount').val();

                            $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(discount) - parseInt(membership_discount));
                        } else {
                            $('#card_status').val('');
                            $('#card_no').val('');
                            $('#point_row').hide();
                            $('#point_label').html('');
                            $('#point_input').hide();
                            $('#member_discount').hide();
                            $('#point_input_label').hide();
                            $('#member_discount_label').hide();
                            $('#membership_discount').html('');
                            $('#member_discount_amount').val('');
                            $('#member_discount_rate').val('');
                            $('#card_status').val(response.card);
                        }
                        console.log(response);
                    }
                });
            }

        });

        $("#redeem_points").keyup(function() {
            let redeem_points = $(this).val();
            $('#redeem_points_amount').val(redeem_points);
            var subtotal = $('#subtotal_amount').val();
            let membership_discount = $('#member_discount_amount').val();
            var shipping_charge = $('#shipping_charge').val();
            var advanced_charge = $('#advanced_charge').val();
            var discount = $('#discount').val();

            if (redeem_points > 0) {
                $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
            } else {
                $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(discount) - parseInt(membership_discount) - parseInt(0));

            }
        });

        $('#phone').keyup(function() {
            var phone = $(this).val();
            if (phone.length == 11) {
                $(this).removeClass("is-invalid");
                $('#err-phone').html("");
            } else {
                $(this).addClass("is-invalid");
                $('#err-phone').html("Phone Number must be 11 digits long.");
            }
        });

        $('#district_id').change(function() {
            var district_id = $(this).val();
            if (district_id == '') {
                district_id = -1;
            }
            var option = "<option value=''>Please Select an Area</option>";
            var url = "{{ url('/') }}";
            $.get(url + "/get-area/" + district_id, function(data) {
                data = JSON.parse(data);
                data.forEach(function(element) {
                    option += "<option value='" + element.id + "'>" + element.name + "</option>";
                });
                $('#areas').html(option);
            });
        });

        $('#areas').change(function() {
            var area_id = $(this).val();
            if (area_id == '') {
                area_id = -1;
            }
            var url = "{{ url('/') }}";

            var subtotal = $('#subtotal_amount').val();
            var discount = $('#discount').val();
            var charge_advanced = $('#advanced_charge').val();

            $.ajax({
                url: url + "/get-shipping-charge",
                type: "POST",
                data: {
                    area_id: area_id,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    let membership_discount = $('#member_discount_amount').val();
                    var redeem_points = $('#redeem_points_amount').val();

                    $('#shipping_charge_label').html(response);
                    $('#total_amount').html(parseInt(subtotal) + parseInt(response) - parseInt(charge_advanced) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
                    $('#shipping_charge').val(response);
                }
            });

        });

        $("#remove_shipping_charge").click(function() {
            var subtotal = $('#subtotal_amount').val();
            var discount = $('#discount').val();
            var charge_advanced = $('#advanced_charge').val();
            let membership_discount = $('#member_discount_amount').val();
            var redeem_points = $('#redeem_points_amount').val();

            if ($("#remove_shipping_charge").is(':checked')) {

                $('#shipping_charge_label').html(0);
                $('#total_amount').html(parseInt(subtotal) - parseInt(charge_advanced) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
                $('#shipping_charge').val(0);

            } else {
                var area_id = $('#areas').val();
                if (area_id == '') {
                    area_id = -1;
                }
                var url = "{{ url('/') }}";

                var subtotal = $('#subtotal_amount').val();
                var discount = $('#discount').val();
                $.ajax({
                    url: url + "/get-shipping-charge",
                    type: "POST",
                    data: {
                        area_id: area_id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        $('#shipping_charge_label').html(response);
                        $('#total_amount').html(parseInt(subtotal) + parseInt(response) - parseInt(charge_advanced) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
                        $('#shipping_charge').val(response);
                    }
                });
            }
        })

        $("#advance_shipping_charge").click(function() {
            var subtotal = $('#subtotal_amount').val();
            var discount = $('#discount').val();
            var shipping_charge = $('#shipping_charge').val();
            let membership_discount = $('#member_discount_amount').val();
            var redeem_points = $('#redeem_points_amount').val();

            if ($("#advance_shipping_charge").is(':checked')) {
                $('#showChargeBox').show();
                // $('#total_amount').html(parseInt(subtotal) - parseInt(discount));
                $("#advanced_charge").on("input", function() {
                    let charge_advanced = $(this).val();
                    if (charge_advanced > 0) {
                        $('#charge_advanced_label').html(charge_advanced);
                        $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(charge_advanced) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
                    }
                });

            } else {
                $('#advanced_charge').val(0);
                $('#charge_advanced_label').html(0);
                $('#showChargeBox').hide();

                var shipping_charge = $('#shipping_charge').val();
                var subtotal = $('#subtotal_amount').val();
                var discount = $('#discount').val();

                $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
            }
        })

        function add_cart(stock_id) {
            url = "{{ route('pos.cart.add') }}";
            var stock_id = stock_id;

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    stock_id: stock_id,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    var shipping_charge = $('#shipping_charge').val();
                    var advanced_charge = $('#advanced_charge').val();
                    var discount = $('#discount').val();

                    var member_discount_rate = $('#member_discount_rate').val();
                    var subtotal = response.total_amount;

                    let membership_discount = Math.round(subtotal * (member_discount_rate / 100));

                    if (membership_discount > 0) {
                        $('#membership_discount').html("{{ env('CURRENCY') }}" + membership_discount);
                        $('#member_discount_amount').val(membership_discount);
                    }

                    var redeem_points = $('#redeem_points_amount').val();

                    $('#total_count').html(response.total_count);
                    $('#subtotal').html(subtotal);
                    $('#subtotal_amount').val(subtotal);
                    $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
                    $('#cart_table').html(response.cart_table);
                }
            });
        }

        function remove_cart(rowId) {
            var rowId = rowId;
            url = "{{ route('pos.cart.remove') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    rowId: rowId,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    var shipping_charge = $('#shipping_charge').val();
                    var advanced_charge = $('#advanced_charge').val();
                    var discount = $('#discount').val();

                    var member_discount_rate = $('#member_discount_rate').val();
                    var subtotal = response.total_amount;

                    let membership_discount = Math.round(subtotal * (member_discount_rate / 100));

                    $('#membership_discount').html("{{ env('CURRENCY') }}" + membership_discount);
                    $('#member_discount_amount').val(membership_discount);

                    var redeem_points = $('#redeem_points_amount').val();

                    $('#total_count').html(response.total_count);
                    $('#subtotal').html(subtotal);
                    $('#subtotal_amount').val(subtotal);
                    $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
                    $('#cart_table').html(response.cart_table);
                }
            });

        }

        var checkReq = false;
        $("#barcode").keyup(function() {
            var barcode = $(this).val();
            if (!isNaN(barcode)) {
                if (barcode.length == 4 && !checkReq) {
                    checkReq = true;
                    //alert(barcode);
                    url = "{{ route('pos.barcode.cart.add') }}";
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            barcode: barcode,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            var shipping_charge = $('#shipping_charge').val();
                            var advanced_charge = $('#advanced_charge').val();
                            var discount = $('#discount').val();

                            var member_discount_rate = $('#member_discount_rate').val();
                            var subtotal = response.total_amount;

                            let membership_discount = Math.round(subtotal * (member_discount_rate / 100));

                            if (membership_discount > 0) {
                                $('#membership_discount').html("{{ env('CURRENCY') }}" + membership_discount);
                                $('#member_discount_amount').val(membership_discount);
                            }

                            var redeem_points = $('#redeem_points_amount').val();

                            $('#total_count').html(response.total_count);
                            $('#subtotal').html(subtotal);
                            $('#subtotal_amount').val(subtotal);
                            $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
                            $('#cart_table').html(response.cart_table);
                            $("#barcode").val('');
                            checkReq = false;
                        }
                    });
                }
            } else {

            }
        });

        function update_cart(rowId) {
            var price = $('#price_' + rowId).val();
            var rowId = rowId;
            if (parseInt(price) > 0) {
                url = "{{ route('pos.cart.update') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        rowId: rowId,
                        price: price,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        var shipping_charge = $('#shipping_charge').val();
                        var advanced_charge = $('#advanced_charge').val();
                        var discount = $('#discount').val();

                        var member_discount_rate = $('#member_discount_rate').val();
                        var subtotal = response.total_amount;

                        let membership_discount = Math.round(subtotal * (member_discount_rate / 100));

                        $('#membership_discount').html("{{ env('CURRENCY') }}" + membership_discount);
                        $('#member_discount_amount').val(membership_discount);

                        var redeem_points = $('#redeem_points_amount').val();

                        $('#total_count').html(response.total_count);
                        $('#subtotal').html(subtotal);
                        $('#subtotal_amount').val(subtotal);
                        $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(discount) - parseInt(membership_discount) - parseInt(redeem_points));
                        $('#cart_table').html(response.cart_table);
                    }
                });
            }
        }

        function apply_discount() {
            var amount = $('#discount_amount').val();
            var subtotal = $('#subtotal_amount').val();
            if (amount != '') {
                var url = "{{ route('pos.apply.discount') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        amount: amount,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        var shipping_charge = $('#shipping_charge').val();
                        var advanced_charge = $('#advanced_charge').val();

                        let membership_discount = $('#member_discount_amount').val();
                        var redeem_points = $('#redeem_points_amount').val();

                        $('#discount_amount_label').html(response);
                        $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(response) - parseInt(membership_discount) - parseInt(redeem_points));
                        $('#discount').val(response);
                    }
                });
            } else {
                var url = "{{ route('pos.apply.discount') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        amount: 0,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        var shipping_charge = $('#shipping_charge').val();
                        var advanced_charge = $('#advanced_charge').val();

                        let membership_discount = $('#member_discount_amount').val();
                        var redeem_points = $('#redeem_points_amount').val();

                        $('#discount_amount_label').html(response);
                        $('#total_amount').html(parseInt(subtotal) + parseInt(shipping_charge) - parseInt(advanced_charge) - parseInt(response) - parseInt(membership_discount) - parseInt(redeem_points));
                        $('#discount').val(response);
                    }
                });
            }

        }

        $("#filter-btn").click(function() {
            load_product();
            $('#product_name').val('');
        });
    </script>

</body>

</html>
