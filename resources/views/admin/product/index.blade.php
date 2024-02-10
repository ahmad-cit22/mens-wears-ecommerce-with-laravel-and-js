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
                        <a href="{{ route('product.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Add Product</a>
                    @endcan
                </div>
                <!-- /.card-header -->
                <div class="row mt-3">
                    <div class="col-lg-6">
                    </div>

                    <div class="col-lg-4">
                        <form class="row" action="{{ route('product.search_table') }}" method="get" role="search">
                            <input type="text" placeholder="Search with product name.." name="search" class="form-control" style="width: 70%; margin-right: 10px">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
                        </form>
                    </div>

                    <div class="col-lg-2">
                        <form class="row" action="{{ route('product.index') }}" method="get" role="search">
                            <input type="number" placeholder="Go to page.." name="page" class="form-control" style="width: 70%; margin-right: 10px">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-location-arrow fa-sm"></i></button>
                        </form>
                    </div>
                </div>
                <p class="text-right mr-4 mt-2">
                    <a href="{{ route('product.index') }}">
                        <i class="fas fa-reply fa-sm mr-1"></i> Reset Results
                    </a>
                </p>
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Created By</th>
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
                                    <td>
                                        @if ($product->created_by)
                                            <a href="{{ route('user.edit', $product->created_by->user_id) }}">{{ $product->created_by->adder->name }}</a>
                                        @else
                                            --
                                        @endif
                                    </td>
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
                                <th>Created By</th>
                                <th>Meta Description</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @php
                    $total = $products->total();
                    $currentPage = $products->currentPage();
                    $perPage = $products->perPage();

                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp

                <p class="ml-4">
                    Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                </p>
                <div class="row justify-content-center">
                    {{ $products->withQueryString()->links() }}
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->


        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // $(function() {
        //     var table = $("#data-table").DataTable({
        //         "responsive": true,
        //         "lengthChange": false,
        //         "autoWidth": false,
        //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        //     $('#example2').DataTable({
        //         "paging": true,
        //         "lengthChange": false,
        //         "searching": true,
        //         "ordering": true,
        //         "info": true,
        //         "autoWidth": false,
        //         "responsive": true,
        //     });
        // });
    </script>
@endsection
