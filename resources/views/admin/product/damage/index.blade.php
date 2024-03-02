@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Product Damage History</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">product-damage-history</li>
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
                    @can('product.edit')
                        <form action="{{ route('damage.store') }}" method="POST">
                            @csrf
                            <div class="row" id="barcodeContainer">
                                <div class="col-md-3">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product*</label>
                                        <select class="select2 form-control  @error('product_id') is-invalid @enderror" name="product_id" id="product_id" required>
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
                                        <select class="select2 form-control" name="size_id" id="size_id" required>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Qty*</label>
                                        <input type="number" name="qty" id="qty" class="form-control  @error('qty') is-invalid @enderror" required>
                                        @error('qty')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <input type="text" name="note" class="form-control  @error('note') is-invalid @enderror">
                                        @error('note')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    @endcan
                </div>
                <!-- /.card-header -->
                @include('admin.partials.page_search')
                <div class="card-body table-responsive">
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
                </div>
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

    <script>
        // $(function () {
        //   $("#example1").DataTable({
        //     "responsive": true, "lengthChange": false, "autoWidth": false,
        //     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        //   }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        //   $('#example2').DataTable({
        //     "paging": true,
        //     "lengthChange": false,
        //     "searching": true,
        //     "ordering": true,
        //     "info": true,
        //     "autoWidth": false,
        //     "responsive": true,
        //   });
        // });
        $(function() {
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'product'
                    },
                    {
                        data: 'size_id'
                    },
                    {
                        data: 'qty'
                    },
                    {
                        data: 'note'
                    },
                    {
                        data: 'created_by'
                    },
                    {
                        data: 'date',
                        orderable: false,
                        searchable: true
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });

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
                    url = "{{ route('damage.product.barcode.scan') }}";
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
                                <div class="row" id="formContainer">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Product*</label>
                                            <select class="select2 form-control" name="product_id" id="product_id" required>
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

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Size*</label>
                                            <select class="select2 form-control" name="size_id" id="size_id" required>
                                                <option value="` + response.size.id + `">` + response.size.title + `</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Qty*</label>
                                            <input type="number" name="qty" class="form-control" placeholder="Add Quantity" id="qty" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Note</label>
                                            <input type="text" name="note" class="form-control  @error('note') is-invalid @enderror">
                                            @error('note')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                `;

                                $('#formContainer').remove();
                                $('#barcodeContainer').after(html);

                                $('.select2').select2();

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
