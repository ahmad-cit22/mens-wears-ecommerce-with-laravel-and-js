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
    <title> Retail Order Sheet | {{ $business->name }}</title>
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
        <h1 class="mt-2 mb-4 text-center text-success font-weight-bold">Order Sheet - Retail</h1>
        <div class="container-fluid py-3 text-white" style="background: rgba(0, 128, 0, 0.387)">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-12">
                    <div class="greeting-text">
                        <h3 class="card-label mb-0 text-white">
                            {{ $business->name }} - Retail
                        </h3>
                    </div>

                </div>
                <div class="col-lg-4 col-md-12 clock-main">
                    <div class="clock">
                        <div class="datetime-content">
                            <div id="Date" class="text-white"></div>
                        </div>

                    </div>

                </div>
                <div class="col-lg-4 col-md-12  order-lg-last order-second">

                    <div class="topbar justify-content-end" style="background: rgba(0, 128, 0, 0.024)">
                        <div class="dropdown mega-dropdown">
                            <div id="id2" class="topbar-item" data-toggle="dropdown" data-display="static">
                                <div class="btn btn-icon w-auto h-auto btn-clean d-flex align-items-center py-0 mr-3">

                                    <span class="symbol symbol-35 symbol-light-success">
                                        <span class="symbol-label bg-info  font-size-h5 ">

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
                        <a class="btn btn-success btn-icon btn-clean px-3 py-1" href="{{ route('fos.index') }}">Order List</a>
                    </div>

                </div>
            </div>
        </div>
    </header>
    <div class="contentPOS">
        <div class="container-fluid">
            <form action="{{ route('fos.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xl-4 order-xl-first order-last">
                        <div class="card card-custom gutter-b bg-white border-0" style="height: auto; background: rgba(255, 140, 0, 0.156)">
                            <div class="card-body" style="background: rgba(255, 140, 0, 0.156)">
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
                                <div class="row" id="product_filtered" style="background: rgba(255, 140, 0, 0.156)">
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
                                <h3 class="my-3 text-center text-success font-weight-bold">New Order Entry</h3>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <h6 class="text-dark fw-bold">Choose a Customer - </h6>
                                        <select class="mb-3 select2 select-down arabic-select2" style="width: 100%;" name="customer_id" id="customer_id">
                                            <option value="0">Walk in Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                            @endforeach
                                        </select>
                                        <div id="new-customer-form">
                                            <div class="form-group row mt-3">

                                                <div class="col-md-12">
                                                    <label class="text-body">Customer Name</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="name" class="form-control" placeholder="Enter Customer Name" id="name" value="{{ old('name') }}" required>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label class="text-body">Email</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="email" name="email" class="form-control" placeholder="Enter E-mail" value="{{ old('email') }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="text-body">Phone</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number" id="phone" value="{{ old('phone') }}" required>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-2">
                                            <div class="col-md-12">
                                                <label class="text-body">Customer Address</label>
                                                <fieldset class="form-group mb-3">
                                                    <textarea type="text" class="form-control" placeholder="Enter Customer Address" name="shipping_address" rows="4" required>{{ old('shipping_address') }}</textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="selectmain">
                                                    <label class="text-dark d-flex">Order Source*</label>
                                                    <select name="source" class="select2 select-down" id="source" required="true">
                                                        <option value="0">--- Select ---</option>
                                                        <option value="Offline">Offline</option>
                                                        <option value="Online">Online</option>
                                                        <option value="Wholesale">Wholesale</option>
                                                        {{-- @foreach ($districts as $district)
                                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                        @endforeach --}}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="selectmain">
                                                    <label class="text-dark d-flex">Courier Name</label>
                                                    <select name="courier_id" class="select2 select-down" id="courier_id" required>
                                                        <option value="">--- Select ---</option>
                                                        @foreach ($couriers as $courier)
                                                            <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label class="text-body">Add Note</label>
                                        <fieldset class="form-group">
                                            <textarea name="note" id="note" class="form-control" placeholder="Add Notes Here">{{ old('note') }}</textarea>
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
                                                    @include('admin.fos.partials.cart-item')
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
                            <div class="card-body" style="background: rgba(255, 140, 0, 0.156)">
                                <div class="shop-profile">
                                    <div class="media">
                                        <div class="w-100px h-100px d-flex justify-content-center align-items-center" style="background: rgba(255, 140, 0, 0.806)">
                                            <h2 class="mb-0 white">G</h2>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h3 class="title font-weight-bold text-info">{{ $business->name }}</h3>
                                            <p class="phoonenumber text-dark">
                                                {{ $business->phone }}
                                            </p>
                                            <p class="adddress text-dark">
                                                {{ $business->address }}
                                            </p>
                                            <!-- <p class="countryname">USA</p> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="resulttable-pos">
                                    <!-- <div class="input-group mb-3">
         <input type="number" class="form-control" placeholder="Discount Amount">
         <div class="input-group-append">
         <button class="btn btn-outline-secondary" type="button" style="padding: 0.375rem 0.75rem">Apply</button>
         </div>
         </div> -->
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="Discount Amount" id="discount_amount" onblur="apply_discount()">
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
                                                <th class="border-0 font-size-h5 mb-0 font-size-bold text-dark">
                                                    Discount
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-dark font-size-base">
                                                    {{ env('CURRENCY') }}<span id="discount_amount_label">{{ $discount }}</span>

                                                </td>

                                            </tr>
                                            <input type="hidden" name="discount" id="discount" value="{{ $discount }}">

                                            <tr class="d-flex align-items-center justify-content-between item-price">
                                                <th class="border-0 font-size-h5 mb-0 font-size-bold text-info">

                                                    TOTAL
                                                </th>
                                                <td class="border-0 justify-content-end d-flex text-info font-size-base">{{ env('CURRENCY') }}<span id="total_amount">{{ Cart::subtotal() - $discount }}</span></td>

                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div align="right">
                                    <button class="btn white mb-2" style="background: rgba(255, 140, 0, 0.806)" type="submit"><i class="fas fa-money-bill-wave mr-2"></i>Save</button>
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

    <script>
        // $("#product_name").keyup(function() {
        //     load_product();
        // });

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

        // $('#category_id').change(function(){
        //        load_product();
        //    });

        //    $('#brand_id').change(function(){
        //        load_product();
        //    });

        function load_product() {
            var product_name = $('#product_name').val();
            var category_id = $('#category_id').val();
            var brand_id = $('#brand_id').val();
            // window.alert(product_name + ' - ' + category_id + ' - ' + brand_id
            // 	);

            url = "{{ route('fos.product.filter') }}";
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
            }
        });

        function add_cart(stock_id) {
            url = "{{ route('fos.cart.add') }}";
            var stock_id = stock_id;

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    stock_id: stock_id,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    var discount = $('#discount').val();

                    $('#total_count').html(response.total_count);
                    $('#subtotal').html(response.total_amount);
                    $('#subtotal_amount').val(response.total_amount);
                    $('#total_amount').html(parseInt(response.total_amount) - parseInt(discount));
                    $('#cart_table').html(response.cart_table);
                }
            });
        }

        function remove_cart(rowId) {
            var rowId = rowId;
            url = "{{ route('fos.cart.remove') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    rowId: rowId,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    $('#total_count').html(response.total_count);
                    $('#subtotal').html(response.total_amount);
                    $('#subtotal_amount').val(response.total_amount);
                    var discount = $('#discount').val();
                    $('#total_amount').html(parseInt(response.total_amount) - parseInt(discount));
                    $('#cart_table').html(response.cart_table);
                }
            });
        }

        $("#barcode").keyup(function() {
            var barcode = $(this).val();
            if (!isNaN(barcode)) {
                if (barcode.length == 4) {
                    //alert(barcode);
                    url = "{{ route('fos.barcode.cart.add') }}";
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            barcode: barcode,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            $('#total_count').html(response.total_count);
                            $('#subtotal').html(response.total_amount);
                            $('#subtotal_amount').val(response.total_amount);
                            var discount = $('#discount').val();
                            $('#total_amount').html(parseInt(response.total_amount) - parseInt(discount));
                            $('#cart_table').html(response.cart_table);
                            $("#barcode").val('');
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
                url = "{{ route('fos.cart.update') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        rowId: rowId,
                        price: price,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        $('#total_count').html(response.total_count);
                        $('#subtotal').html(response.total_amount);
                        $('#subtotal_amount').val(response.total_amount);
                        var discount = $('#discount').val();
                        $('#total_amount').html(parseInt(response.total_amount) - parseInt(discount));
                        $('#cart_table').html(response.cart_table);
                    }
                });
            }
        }

        function apply_discount() {
            var amount = $('#discount_amount').val();
            var subtotal = $('#subtotal_amount').val();
            if (amount != '') {
                var url = "{{ route('fos.apply.discount') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        amount: amount,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        $('#discount_amount_label').html(response);
                        $('#total_amount').html(parseInt(subtotal) - parseInt(response));
                        $('#discount').val(response);
                    }
                });
            } else {
                var url = "{{ route('fos.apply.discount') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        amount: 0,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        $('#discount_amount_label').html(response);
                        $('#total_amount').html(parseInt(subtotal) - parseInt(response));
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
<!--end::Body-->

</html>
