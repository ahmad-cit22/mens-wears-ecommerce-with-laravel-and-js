@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Partner List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">partner-list</li>
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
                    <a href="#addModal" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus mr-2"></i> Create partner</a>
                    <a href="{{ route('partnertransaction.index') }}" class="btn btn-success"><i class="fas fa-eye mr-2"></i> Partner's Transactios</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Percentage</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partners as $partner)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $partner->name }}</td>
                                    <td>{{ $partner->share_portion }}%</td>
                                    <td>
                                        <span class="badge badge-success p-2">Current Investment: {{ env('CURRENCY') }}{{ $partner->transactions->sum('credit') - $partner->transactions->sum('debit') }} </span>
                                        <br>
                                        <span class="badge badge-secondary p-2 mt-2 mb-2">
                                            Profit Amount: {{ env('CURRENCY') }}{{ ($other_income->sum('credit') + $order_amount - $production_cost - $expenses->sum('amount')) * ($partner->share_portion / 100) }}
                                        </span>
                                        <br>
                                        <span class="badge badge-info p-2">
                                            Total Amount: {{ env('CURRENCY') }}{{ $partner->transactions->sum('credit') - $partner->transactions->sum('debit') + ($other_income->sum('credit') + $order_amount - $production_cost - $expenses->sum('amount')) * ($partner->share_portion / 100) }}
                                        </span>

                                    </td>
                                    <td>
                                        @can('brand.edit')
                                            <a href="#editModal{{ $partner->id }}" class="btn btn-primary" data-toggle="modal" title="Edit"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('brand.delete')
                                        @endcan
                                    </td>
                                </tr>

                                <!-- Edit brand Modal -->
                                <div class="modal fade" id="editModal{{ $partner->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    Edit - {{ $partner->name }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('partner.update', $partner->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Name *</label>
                                                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" Value="{{ $partner->name }}" required>
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Share Portion *</label>
                                                                <input type="text" name="share_portion" class="form-control @error('share_portion') is-invalid @enderror" Value="{{ $partner->share_portion }}" required>
                                                                @error('share_portion')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </div>
                                            </div>
                                            </form>

                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>
                                </div>
                </div>
                <!-- Delete brand Modal -->
                <div class="modal fade" id="deleteModal{{ $partner->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" align="right">
                                <form action="{{ route('brand.destroy', $partner->id) }}" method="POST">
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
                        <th>Name</th>
                        <th>Percentage</th>
                        <th>Balance</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Partner</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('partner.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Partner Name *</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Share Portion(%) *</label>
                                        <input type="text" name="share_portion" class="form-control @error('share_portion') is-invalid @enderror" placeholder="Share Portion (in %)">
                                        @error('share_portion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Opening Balance *</label>
                                        <input type="text" name="opening_balance" class="form-control @error('opening_balance') is-invalid @enderror" placeholder="Openiing Balance" required>
                                        @error('opening_balance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(function() {
            $("#example1").DataTable({
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
        });
    </script>
@endsection
