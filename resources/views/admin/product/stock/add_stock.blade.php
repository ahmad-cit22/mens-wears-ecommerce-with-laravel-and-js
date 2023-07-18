@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Stock Management</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">add-product-stock</li>
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
                    <h4>Add Stock</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    @can('product.edit')
                        <form action="{{ route('stock.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product*</label>
                                        <select class="select2 form-control  @error('product_id') is-invalid @enderror" name="product_id" id="product_id">
                                            <option value="">---- Select ----</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }} - ({{ $product->is_active == 1 ? 'Active' : 'Inactive' }})</option>
                                            @endforeach
                                        </select>
                                        @error('product_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Size*</label>
                                        <select class="select2 form-control" name="size_id" id="size_id">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Qty*</label>
                                        <input type="number" name="qty" class="form-control  @error('qty') is-invalid @enderror">
                                        @error('qty')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    @endcan
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->


            </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('#product_id').change(function() {
            var product_id = $(this).val();
            if (product_id == '') {
                product_id = -1;
            }
            var option = "";
            var url = "{{ url('/') }}";

            $.get(url + "/get-size/" + product_id, function(data) {
                data = JSON.parse(data);
                data.forEach(function(element) {
                    option += "<option value='" + element.id + "'>" + element.title + "</option>";
                });
                $('#size_id').html(option);
            });

        });
    </script>
@endsection
