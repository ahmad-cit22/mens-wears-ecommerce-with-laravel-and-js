@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" target="_blank">Home</a></li>
                        <li class="breadcrumb-item active">Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Title *</b></label>
                            <div class="col-sm-10">
                                <input type="text" name="title" value="{{ $product->title }}" class="form-control @error('title') is-invalid @enderror" required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Category </b></label>
                            <div class="col-sm-10">
                                <select id="category_id" name="category_id" class="select2 form-control @error('category_id') is-invalid @enderror">
                                    <option value="">Please Select a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Sub Category </b></label>
                            <div class="col-sm-10">
                                <select id="sub_category" name="sub_category_id" class="select2 form-control @error('sub_category_id') is-invalid @enderror">
                                    <option value="">Please Select a Sub Category</option>
                                    @if (!is_null($sub_categories))
                                        @foreach ($sub_categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == $product->sub_category_id ? 'selected' : '' }}>{{ $category->title }}</option>
                                        @endforeach
                                    @endif

                                </select>
                                @error('sub_category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Brand </b></label>
                            <div class="col-sm-10">
                                <select name="brand_id" class="select2 form-control @error('brand_id') is-invalid @enderror">
                                    <option value="">Please Select a Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>{{ $brand->title }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Thumbnail (600x750px)*</b></label>
                            <div class="col-sm-10">
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <img src="{{ asset('images/product/' . $product->image) }}" width="100">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Gallery (960x620px)*</b></label>
                            <div class="col-sm-10">
                                <input type="file" name="gallery[]" class="form-control @error('gallery') is-invalid @enderror" multiple>
                                @error('gallery')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @foreach ($product->product_image as $image)
                                    <a href="#deletegallery{{ $image->id }}" data-toggle="modal"><img src="{{ asset('images/product/' . $image->image) }}" width="100" style="margin-right: 15px;"></a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="deletegallery{{ $image->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Are you sure you want to delete?</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <a href="#" class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                                                    <a href="{{ route('product.gallery.destroy', $image->id) }}" class="btn btn-danger">Permanent Delete</a>
                                                </div>
                                                <div class="modal-footer">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Size Chart (400x300px)*</b></label>
                            <div class="col-sm-10">
                                <input type="file" name="size_chart" class="form-control @error('size_chart') is-invalid @enderror">
                                @error('size_chart')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <img src="{{ asset('images/product/' . $product->size_chart) }}" width="100">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Status </b></label>
                            <div class="col-sm-10">
                                <select name="is_active" class="select2 form-control @error('is_active') is-invalid @enderror">
                                    <option value="0" {{ $product->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                    <option value="1" {{ $product->is_active == 1 ? 'selected' : '' }}>Active</option>
                                </select>
                                @error('is_active')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><input type="checkbox" name="is_featured" class="@error('is_featured') is-invalid @enderror" value="{{ $product->is_featured }}" {{ $product->is_featured == 1 ? 'checked' : '' }}> Mark as featured product</label>

                                @error('is_featured')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><input type="checkbox" name="is_trending" class="@error('is_trending') is-invalid @enderror" value="{{ $product->is_trending }}" {{ $product->is_trending == 1 ? 'checked' : '' }}> Mark as trending product</label>

                                @error('is_trending')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><input type="checkbox" name="is_offer" class="@error('is_offer') is-invalid @enderror" value="{{ $product->is_offer }}" {{ $product->is_offer == 1 ? 'checked' : '' }}> Mark as offer product</label>

                                @error('is_offer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Short Description</b></label>
                            <div class="col-sm-10">
                                <textarea class="tinymce form-control @error('short_description') is-invalid @enderror" name="short_description">{{ $product->short_description }}</textarea>
                                @error('short_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Description *</b></label>
                            <div class="col-sm-10">
                                <textarea class="tinymce form-control @error('description') is-invalid @enderror" name="description">{{ $product->description }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if ($product->type == 'single')
                            <div id="single">
                                <div class="row">
                                    <input type="hidden" name="variation_id" value="{{ $product->variation->id }}">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Code</label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $product->variation->code }}" readonly>
                                            @error('code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Production Cost*</label>
                                            <input type="number" class="form-control @error('production_cost') is-invalid @enderror" name="production_cost" value="{{ $product->variation->production_cost }}">
                                            @error('production_cost')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Price*</label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $product->variation->price }}">
                                            @error('price')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Discount Price*</label>
                                            <input type="number" class="form-control @error('discount_price') is-invalid @enderror" name="discount_price" value="{{ $product->variation->discount_price }}">
                                            @error('discount_price')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Wholesale Price*</label>
                                            <input type="number" class="form-control @error('wholesale_price') is-invalid @enderror" name="wholesale_price" value="{{ $product->variation->wholesale_price }}">
                                            @error('wholesale_price')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Opening Stock*</label>
                                            <input type="number" class="form-control @error('qty') is-invalid @enderror" name="qty" value="{{ $product->variation->qty }}" readonly>
                                            @error('qty')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($product->type == 'variation')
                            <div id="variation">
                                <div class="row" align="right">
                                    <div class="col-md-12 mb-2">
                                        <a href="#addVariation" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                @foreach ($product->variations as $variation)
                                    <div class="row pt-2 pb-2 mb-2" style="border: 1px solid green;">
                                        <input type="hidden" name="variation_ids[]" value="{{ $variation->id }}">
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>Size*</label>
                                                <select class="form-control @error('sizes') is-invalid @enderror" name="sizes[]" required>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}" {{ $variation->size_id == $size->id ? 'selected' : '' }}>{{ $size->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error('sizes')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>Code</label>
                                                <input type="text" class="form-control @error('codes') is-invalid @enderror" name="codes[]" value="{{ $variation->code }}" readonly>
                                                @error('codes')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Production Cost*</label>
                                                <input type="text" class="form-control @error('production_costs') is-invalid @enderror" name="production_costs[]" value="{{ $variation->production_cost }}">
                                                @error('production_costs')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Price*</label>
                                                <input type="text" class="form-control @error('prices') is-invalid @enderror" name="prices[]" value="{{ $variation->price }}">
                                                @error('prices')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Discount Price*</label>
                                                <input type="number" class="form-control @error('discount_prices') is-invalid @enderror" name="discount_prices[]" value="{{ $variation->discount_price }}">
                                                @error('discount_prices')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Wholesale Price*</label>
                                                <input type="text" class="form-control @error('wholesale_prices') is-invalid @enderror" name="wholesale_prices[]" value="{{ $variation->wholesale_price }}">
                                                @error('wholesale_prices')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Opening Stock*</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control @error('qtys') is-invalid @enderror" name="qtys[]" value="{{ $variation->qty }}" readonly>
                                                    <div class="input-group-append">
                                                        <!-- <button class="btn btn-outline-danger" type="button"><i class="fas fa-minus"></i></button> -->
                                                    </div>
                                                    @error('qtys')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="addVariation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Variation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('product.variation.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Size*</label>
                                    <select class="form-control @error('size_id') is-invalid @enderror" name="size_id" required>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Code</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" name="code">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Production Cost*</label>
                                    <input type="text" class="form-control @error('production_cost') is-invalid @enderror" name="production_cost">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Price*</label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror" name="price">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Discount Price*</label>
                                    <input type="text" class="form-control @error('discount_price') is-invalid @enderror" name="discount_price">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Wholesale Price*</label>
                                    <input type="text" class="form-control @error('wholesale_price') is-invalid @enderror" name="wholesale_price">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Opening Stock*</label>
                                    <input type="text" class="form-control @error('qty') is-invalid @enderror" name="qty">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#category_id').change(function() {
            var category_id = $(this).val();
            if (category_id == '') {
                category_id = -1;
            }
            var option = "";
            var url = "{{ url('/') }}";

            $.get(url + "/get-sub-category/" + category_id, function(data) {
                data = JSON.parse(data);
                data.forEach(function(element) {
                    option += "<option value='" + element.id + "'>" + element.title + "</option>";
                });
                //console.log(option);
                $('#sub_category').html(option);
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $("input[type='radio']").change(function() {
                if ($(this).val() == "variation") {
                    $("#variation").show();
                } else {
                    $("#variation").hide();
                }

                if ($(this).val() == "single") {
                    $("#single").show();
                } else {
                    $("#single").hide();
                }
            });
        });
    </script>
@endsection
