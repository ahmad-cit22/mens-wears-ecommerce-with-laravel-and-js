@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Product</h1>
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
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Title *</b></label>
                            <div class="col-sm-10">
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
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
                                        <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->title }}</option>
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
                                        <option value="{{ $brand->id }}" {{ $brand->id == old('brand_id') ? 'selected' : '' }}>{{ $brand->title }}</option>
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
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" required>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Gallery (960x620px)*</b></label>
                            <div class="col-sm-10">
                                <input type="file" name="gallery[]" class="form-control @error('gallery') is-invalid @enderror" multiple required>
                                @error('gallery')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Size Chart (400x300px)*</b></label>
                            <div class="col-sm-10">
                                <input type="file" name="size_chart" class="form-control @error('size_chart') is-invalid @enderror" required>
                                @error('size_chart')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><b>Short Description</b></label>
                            <div class="col-sm-10">
                                <textarea class="summernote form-control @error('short_description') is-invalid @enderror" name="short_description">{{ old('short_description') }}</textarea>
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
                                <textarea class="summernote form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <label class="col-sm-2 col-form-label"><b>Type*</b></label>
                            <div class="col-sm-4">
                                <label><input type="radio" name="type" value="single" checked> Single </label>
                                <label><input type="radio" name="type" value="variation"> Variation </label>

                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label class="col-sm-2 col-form-label"><b>Reference Code</b></label>
                            <div class="col-sm-4">
                                <input type="text" name="reference_code" class="form-control  @error('reference_code') is-invalid @enderror">
                                @error('reference_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div id="single">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Code</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code">
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
                                        <input type="number" class="form-control @error('production_cost') is-invalid @enderror" name="production_cost">
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
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price">
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
                                        <input type="number" class="form-control @error('discount_price') is-invalid @enderror" name="discount_price">
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
                                        <input type="number" class="form-control @error('wholesale_price') is-invalid @enderror" name="wholesale_price">
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
                                        <input type="text" class="form-control @error('qty') is-invalid @enderror" name="qty">
                                        @error('qty')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Meta Description</label>
                                        <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" placeholder="Add Meta Description Here" rows="5"></textarea>
                                        @error('meta_description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="variation" style="display: none;">
                            <div class="row" align="right">
                                <div class="col-md-12 mb-2">
                                    <a class="btn btn-primary  extra-fields-variation"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="variation_records">
                                <div class="row pt-2 pb-2 mb-2" style="border: 1px solid green;">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Size*</label>
                                            <select class="form-control @error('sizes') is-invalid @enderror" name="sizes[]" required>
                                                @foreach ($sizes as $size)
                                                    <option value="{{ $size->id }}">{{ $size->title }}</option>
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
                                            <input type="text" class="form-control @error('codes') is-invalid @enderror" name="codes[]">
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
                                            <input type="number" class="form-control @error('production_costs') is-invalid @enderror" name="production_costs[]">
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
                                            <input type="number" class="form-control @error('prices') is-invalid @enderror" name="prices[]">
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
                                            <input type="number" class="form-control @error('discount_prices') is-invalid @enderror" name="discount_prices[]">
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
                                            <input type="number" class="form-control @error('wholesale_prices') is-invalid @enderror" name="wholesale_prices[]">
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
                                                <input type="text" class="form-control @error('qtys') is-invalid @enderror" name="qtys[]">
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
                            </div>

                            <div class="variation_records_dynamic"></div>

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('#category_id').change(function() {
            var category_id = $(this).val();
            if (category_id == '') {
                category_id = -1;
            }
            var option = '<option value="">Please Select a Sub Category</option>';
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
    <script>
        $('.extra-fields-variation').click(function() {
            $('.variation_records').clone().appendTo('.variation_records_dynamic');
            $('.variation_records_dynamic .variation_records').addClass('single remove');
            $('.single .extra-fields-variation').remove();
            $('.single .row .input-group-append').append('<a href="#" class="remove-field btn-remove-variation btn btn-danger"><i class="fas fa-minus"></i></a>');
            $('.variation_records_dynamic > .single').attr("class", "remove");

            $('.variation_records_dynamic input').each(function() {
                var count = 0;
                var fieldname = $(this).attr("name");
                $(this).attr('name', fieldname + count);
                count++;
            });

        });

        $(document).on('click', '.remove-field', function(e) {
            $(this).parent('.input-group-append').parent('.input-group').parent('.form-group').parent('.col-md-2').parent('.row').parent('.remove').remove();
            e.preventDefault();
        });
    </script>
@endsection
