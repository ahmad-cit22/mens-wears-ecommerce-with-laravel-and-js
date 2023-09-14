@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Product Stock History</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">product-stock-history</li>
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
                    <h4 class="m-0 mb-3">Product Stock History</h4>
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
                                <th>Production Cost</th>
                                <th>Price</th>
                                <th>Note</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @php
                                $sl = 1;
                            @endphp
                            @foreach ($stocks as $key => $stock)
                                @if ($stock->product)
                                    <tr>
                                        <td>{{ $sl }}</td>
                                        <td>{{ $stock->product->title }}</td>
                                        <td>{{ $stock->size->title }}</td>
                                        <td>{{ $stock->qty }}</td>
                                        <td>{{ $stock->note }}</td>
                                    </tr>
                                    @php
                                        $sl++;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody> --}}
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

            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('stock.index') }}",
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
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        orderable: true,
                        searchable: true,
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });

        });
    </script>
@endsection
