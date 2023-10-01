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
    <!-- <div class="breadcrumb-area section-padding-1 breadcrumb-bg-1 breadcrumb-ptb-2">
                        <div class="container-fluid">
                            <div class="breadcrumb-content text-center">
                                <div class="breadcrumb-title">
                                    <h2>Shop</h2>
                                </div>
                                <ul>
                                    <li>
                                        <a href="{{ route('index') }}">Home 01 </a>
                                    </li>
                                    <li><span> &gt; </span></li>
                                    <li class="active"> shop </li>
                                </ul>
                            </div>
                        </div>
                    </div> -->
    <div class="shop-area section-padding-3 pt-70 pb-100">
        <div class="container-fluid">
            <div class="row flex-row-reverse">
                <div class="col-lg-9">
                    <div class="shhop-pl-35">


                        <div class="tab-content jump-3 pt-30">
                            <div id="shop-1" class="tab-pane active">
                                <div class="row" id="product_filtered">
                                    @foreach ($products as $product)
                                        <div class="col-6 col-lg-4">
                                            @include('pages.partials.product')
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="">
                                {{-- @php
                                    $total = $products->total();
                                    $currentPage = $products->currentPage();
                                    $perPage = $products->perPage();
                                    
                                    $from = ($currentPage - 1) * $perPage + 1;
                                    $to = min($currentPage * $perPage, $total);
                                @endphp

                                <p class="ml-4">
                                    Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                                </p> --}}
                                <div class="row justify-content-center">
                                    <div class="col-6">{{ $products->links() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="shop-sidebar-style mt-25">
                        <div class="sidebar-widget mb-65">
                            <h4 class="pro-sidebar-title">Categories </h4>
                            <div class="sidebar-widget-list mt-50">
                                <ul>
                                    <!-- <li><a href="#">Electronics & Tech</a> <span>(16)</span></li> -->
                                    <label><input type="radio" name="category_id" value="all" style="background-color: none;border: none;width: auto;height: auto;" checked>ALL</label>
                                    @foreach (App\Models\Category::orderBy('position', 'ASC')->get() as $category)
                                        <div>
                                            <label><input type="radio" name="category_id" value="{{ $category->id }}" style="background-color: none;border: none;width: auto;height: auto;"> {{ $category->title }}</label> <span style="float: right;">({{ count($category->products) }})</span>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="sidebar-widget mb-65">
                            <h4 class="pro-sidebar-title">Brands </h4>
                            <div class="sidebar-widget-list mt-50">
                                <ul>
                                    <!-- <li><a href="#">Electronics & Tech</a> <span>(16)</span></li> -->
                                    <label><input type="radio" name="brand_id" value="all" style="background-color: none;border: none;width: auto;height: auto;" checked> ALL</label>
                                    @foreach (App\Models\Brand::orderBy('id', 'DESC')->get() as $brand)
                                        <div>
                                            <label><input type="radio" name="brand_id" {{ $brand->id }} style="background-color: none;border: none;width: auto;height: auto;"> {{ $brand->title }}</label> <span style="float: right;">({{ count($brand->products) }})</span>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="sidebar-widget mb-70">
                            <h4 class="pro-sidebar-title">Filter by price </h4>
                            <!-- <div class="price-filter mt-60">
                                                <div id="slider-range"></div>
                                                <div class="price-slider-amount">
                                                    <div class="label-input">
                                                        <span>Price: </span><input type="text" id="amount" name="price" placeholder="Add Your Price" />
                                                    </div>
                                                    <button type="button">Filter</button>
                                                </div>
                                            </div> -->
                            <fieldset class="filter-price">

                                <div class="price-field">
                                    <input type="range" min="{{ $min_price }}" max="{{ $max_price }}" value="{{ $min_price }}" id="lower">
                                    <input type="range" min="{{ $min_price }}" max="{{ $max_price }}" value="{{ $max_price }}" id="upper">
                                </div>
                                <div class="price-wrap">
                                    <div class="price-wrap-1">
                                        <label for="one">{{ env('CURRENCY') }}</label>
                                        <input id="one">
                                    </div>
                                    <div class="price-wrap_line">-</div>
                                    <div class="price-wrap-2">
                                        <label for="two">{{ env('CURRENCY') }}</label>
                                        <input id="two">
                                    </div>
                                </div>
                                <a class="btn btn-primary" onclick="product_price_filter()" style="color: #fff;">FILTER</a>
                            </fieldset>
                        </div>

                        <div class="sidebar-widget">
                            <div class="shop-sidebar-banner default-overlay">
                                <a href="#"><img alt="" src="assets/images/banner/sidebar-banner.jpg"></a>
                                <div class="shop-sidebar-content">
                                    <h5>Houndstooth coat</h5>
                                    <h3>off 25%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("input[type='radio']").change(function() {

                var category_id = $("input[name='category_id']:checked").val();
                var brand_id = $("input[name='brand_id']:checked").val();
                var min_price = $('#lower').val();
                var max_price = $('#upper').val();
                url = "{{ route('product.filter') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        category_id: category_id,
                        brand_id: brand_id,
                        min_price: min_price,
                        max_price: max_price,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        console.log(response.product_filtered);
                        $('#product_filtered').html(response.product_filtered);
                    }
                });

            });
        });
    </script>
    <script>
        var lowerSlider = document.querySelector('#lower');
        var upperSlider = document.querySelector('#upper');

        document.querySelector('#two').value = upperSlider.value;
        document.querySelector('#one').value = lowerSlider.value;

        var lowerVal = parseInt(lowerSlider.value);
        var upperVal = parseInt(upperSlider.value);

        upperSlider.oninput = function() {
            lowerVal = parseInt(lowerSlider.value);
            upperVal = parseInt(upperSlider.value);

            if (upperVal < lowerVal + 4) {
                lowerSlider.value = upperVal - 4;
                if (lowerVal == lowerSlider.min) {
                    upperSlider.value = 4;
                }
            }
            document.querySelector('#two').value = this.value
        };

        lowerSlider.oninput = function() {
            lowerVal = parseInt(lowerSlider.value);
            upperVal = parseInt(upperSlider.value);
            if (lowerVal > upperVal - 4) {
                upperSlider.value = lowerVal + 4;
                if (upperVal == upperSlider.max) {
                    lowerSlider.value = parseInt(upperSlider.max) - 4;
                }
            }
            document.querySelector('#one').value = this.value
        };

        function product_price_filter() {
            var category_id = $("input[name='category_id']:checked").val();
            var brand_id = $("input[name='brand_id']:checked").val();
            var min_price = $('#lower').val();
            var max_price = $('#upper').val();
            //alert(category_id + '-' + min_price + '-' + max_price);
            url = "{{ route('product.filter') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    category_id: category_id,
                    brand_id: brand_id,
                    min_price: min_price,
                    max_price: max_price,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    //console.log(response.product_filtered);
                    $('#product_filtered').html(response.product_filtered);
                }
            });
        }
    </script>
@endsection
