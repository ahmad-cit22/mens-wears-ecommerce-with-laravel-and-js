@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Current Stock</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">product-current-stock</li>
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
                    <div class="row fs-5 justify-content-start">
                        <h5 class="ml-lg-2 mr-5"><b class="mr-1"> Overall Production Cost:</b> {{ $total_production_cost }} TK</h5>
                        <h5 class="mr-4"><b class="mr-1"> Overall Price:</b> {{ $total_price }} TK</h5>
                    </div>
                    <div class="row fs-5 mt-3">
                        @foreach ($categories as $key => $category)
                            @php
                                $production_cost_sum = 0;
                                $price_sum = 0;
                            @endphp
                            @foreach ($p_stocks as $item)
                                @if ($category->parent_id == 0 && $item->product->category_id == $category->id)
                                    @php
                                        $production_cost_sum += $item->production_cost * $item->qty;
                                        $price_sum += $item->price * $item->qty;
                                    @endphp
                                @endif
                            @endforeach
                            @if ($category->parent_id == 0)
                                <div class="col-3 gap-3 mb-3 mt-2">
                                    <h5><b>{{ $category->title }}</b></h5>
                                    <span>Total Production Cost: <span class="ml-1">{{ $production_cost_sum }} TK</span></span>
                                    <p>Total Price: <span class="ml-1">{{ $price_sum }} TK</span></p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="data-table-current" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Production Cost</th>
                                <th>Price</th>
                                <th>Discount Price</th>
                                <th>Wholesale Price</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
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
        // var table = new DataTable('#data-table');

        $(function() {

            var table = $('#data-table-current').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stock.current') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'size_id',
                        name: 'size_id'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'production_cost',
                        name: 'production_cost'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'discount_price',
                        name: 'discount_price'
                    },
                    {
                        data: 'wholesale_price',
                        name: 'wholesale_price',
                        orderable: true,
                        searchable: true,
                    },
                ]
            });

        });
    </script>
@endsection
