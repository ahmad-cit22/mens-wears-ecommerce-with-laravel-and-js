@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Product Transfer to Vendors</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">product-transfer</li>
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
                    <form id="stockForm" action="{{ route('vendor.transfer_products.store') }}" method="POST">
                        @csrf
                        <div class="row" id="barcodeContainer">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Vendor/Display Center*</label>
                                    <select class="select2 form-control  @error('vendor_id') is-invalid @enderror" name="vendor_id" id="vendor_id" required>
                                        <option value="">---- Select ----</option>
                                        @foreach ($vendors as $vendor)
                                            @if ($vendor->is_active == 1)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('vendor_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Scan Barcode</label>
                                    <input type="text" id="barcode" name="barcode" class="form-control  @error('barcode') is-invalid @enderror" placeholder="Scan Barcode" autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="formContainer">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product*</label>
                                    <select class="select2 form-control @error('product_id') is-invalid @enderror" name="product_id[]" id="product_id1">
                                        <option value="">---- Select ----</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->title }} - ({{ $product->is_active == 1 ? 'Active' : 'Inactive' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Size*</label>
                                    <select class="select2 form-control" name="size_id[]" id="size_id1">

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Qty*</label>
                                    <input type="number" name="qty[]" class="form-control  @error('qty') is-invalid @enderror" placeholder="Add Quantity">
                                </div>
                            </div>

                            {{-- <div class="col-md-2">
                                <div class="form-group">
                                    <label>Discount Amount</label>
                                    <input type="number" name="number[]" class="form-control  @error('number') is-invalid @enderror" placeholder="Add Discount">
                                </div>
                            </div> --}}

                            <div class="col-md-3">
                                <div class="row align-items-end">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <input type="text" name="remarks[]" class="form-control  @error('remarks') is-invalid @enderror" placeholder="Add Remarks">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary f-14 addNewRow"><i class="fa fa-plus"></i></button>
                                            <button type="button" class="btn btn-danger f-14 remove" name="button"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-header -->
                @include('admin.partials.page_search')
                {{-- <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Note</th>
                                <th>Created By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S.N</th>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Note</th>
                                <th>Created By</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                    </table>
                </div> --}}
                <!-- /.card-body -->
            </div>
            <!-- /.card -->


        </div>
    </section>
@endsection

@section('scripts')
    <script>
        var rowIdx = 2;

        $('#product_id1').change(function() {
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
                $('#size_id1').html(option);
            });

        });
    </script>

    <script>
        $('#stockForm').on('click', '.addNewRow', function() {
            let html = `
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Product*</label>
                                        <select class="select2 form-control" name="product_id[]" id="product_id` + rowIdx + `">
                                            <option value="">---- Select ----</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }} - ({{ $product->is_active == 1 ? 'Active' : 'Inactive' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Size*</label>
                                        <select class="select2 form-control" name="size_id[]" id="size_id` + rowIdx + `">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Qty*</label>
                                        <input type="number" name="qty[]" class="form-control" placeholder="Add Quantity">
                                    </div>
                                </div>



                                <div class="col-md-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Remarks</label>
                                                <input type="text" name="remarks[]" class="form-control" placeholder="Add Remarks">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary f-14 addNewRow"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-danger f-14 remove" name="button"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;

            $(this).closest('div[class=row]').after(html);

            $('.select2').select2();

            rowIdx++;

            for (let i = 1; i < rowIdx + 1; i++) {
                $('#product_id' + i).change(function() {
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
                        $('#size_id' + i).html(option);
                    });
                });
            }
        });

        $('#stockForm').on('click', '.remove', function() {
            $(this).closest('div[class=row]').remove();
        });
    </script>

    <script>
        var checkReq = false;
        $("#barcode").keyup(function() {
            var barcode = $(this).val();
            if (!isNaN(barcode)) {
                if (barcode.length == 4 && !checkReq) {
                    checkReq = true;
                    // alert(barcode);
                    url = "{{ route('stock.add.barcode.scan') }}";
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            barcode: barcode,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            // console.log(response);
                            if (response.product != null) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: "Product Scanned Successfully!",
                                    showConfirmButton: false,
                                    timer: 1200
                                });

                                let html = `
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Product*</label>
                                            <select class="select2 form-control" name="product_id[]" id="product_id` + rowIdx + `">
                                                <option value="">---- Select ----</option>
                                                    <option value="` + response.product.id + `" selected>` + response.product.title + `</option>
                                            </select>
                                            @error('product_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Size*</label>
                                            <select class="select2 form-control" name="size_id[]" id="size_id` + rowIdx + `">
                                                <option value="` + response.size.id + `">` + response.size.title + `</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Qty*</label>
                                            <input type="number" name="qty[]" class="form-control" placeholder="Add Quantity">
                                        </div>
                                    </div>



                                    <div class="col-md-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Remarks</label>
                                                    <input type="text" name="remarks[]" class="form-control" placeholder="Add Remarks">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary f-14 addNewRow"><i class="fa fa-plus"></i></button>
                                                    <button type="button" class="btn btn-danger f-14 remove" name="button"><i class="fa fa-minus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;

                                $('.addNewRow').first().closest('div[class=row]').before(html);

                                $('.select2').select2();

                                rowIdx++;

                                for (let i = 1; i < rowIdx + 1; i++) {
                                    $('#product_id' + i).change(function() {
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
                                            $('#size_id' + i).html(option);
                                        });
                                    });
                                }

                                $("#barcode").val('');

                                checkReq = false;
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: "Product Not Found! Try Again.",
                                    showConfirmButton: false,
                                    timer: 1200
                                });
                                $("#barcode").val('');
                                checkReq = false;
                            }
                        }
                    });
                }
            } else {

            }
        });
    </script>
@endsection

{{-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Discount Amount</label>
                                        <input type="number" name="number[]" class="form-control  @error('number') is-invalid @enderror" placeholder="Add Discount">
                                    </div>
                                </div>
<div class="col-md-2">
                                        <div class="form-group">
                                            <label>Discount Amount</label>
                                            <input type="number" name="number[]" class="form-control  @error('number') is-invalid @enderror" placeholder="Add Discount">
                                        </div>
                                    </div> --}}
