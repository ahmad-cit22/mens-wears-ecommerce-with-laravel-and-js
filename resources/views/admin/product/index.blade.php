@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">product</li>
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
                    @can('product.create')
                        <a href="{{ route('product.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
                    @endcan
                </div>
                <!-- /.card-header -->
                <div class="row mt-3">
                    <div class="col-9"></div>
                    <div class="col-3">
                        <div class="d-flex" style="gap: 10px;">
                            <input id="page-search-field" class="form-control" type="number" name="pageNo" placeholder="Go to Page" style="width: 75%">
                            <a id="page-search-btn" class="btn btn-sm btn-primary">Search</a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Meta Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <img src="{{ asset('images/product/' . $product->image) }}" width="100"><br>
                                        {{ $product->title }}

                                    </td>

                                    <td>{{ !is_null($product->category) ? $product->category->title : '' }}</td>
                                    <td>{{ !is_null($product->brand) ? $product->brand->title : '' }}</td>

                                    <td>{{ $product->type }}</td>
                                    <td>
                                        @if ($product->type == 'single')
                                            {{ optional($product->variation)->qty }}
                                        @else
                                            @foreach ($product->variations as $variation)
                                                {{ optional($variation->size)->title }} - {{ $variation->qty }}<br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td><span class="badge badge-{{ $product->is_active == 1 ? 'success' : 'danger' }}">{{ $product->is_active == 1 ? 'Active' : 'Inactive' }}</span></td>
                                    <td>{{ $product->meta_description }}</td>
                                    <td>
                                        @can('product.edit')
                                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('product.delete')
                                            <a href="#deleteModal{{ $product->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                                <!-- Delete product Modal -->
                                <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Are tou sure you want to delete ?</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('product.destroy', $product->id) }}" method="POST">
                                                    @csrf
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Permanent Delete</button>
                                                </form>

                                            </div>
                                            <div class="modal-footer">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Meta Description</th>
                                <th>Action</th>
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
            var table = $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

            var pageNo = 6
            table.page(pageNo - 1).draw('page');
        });

        $('page-search-btn').click(function() {
            alert(pageNo);
            let pageNo = $('page-search-field').val();
            alert(pageNo);
            abc(pageNo);
        })

        function abc(pageNo) {
            var table = $('#example1').DataTable();
            var pageNo = pageNo
            table.page(pageNo - 1).draw('page');
        };
    </script>
@endsection
