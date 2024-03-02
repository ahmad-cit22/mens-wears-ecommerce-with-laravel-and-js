@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Reject Product Panel</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <h3 class="m-0 ml-3 mt-2">Reject Product Out/Sells List</h3>
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
                                <th>Price</th>
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
                                <th>Price</th>
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
                        data: 'price'
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
