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

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    @endcan
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Note</th>
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
@endsection
