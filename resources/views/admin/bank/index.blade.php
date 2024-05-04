@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bank List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">bank</li>
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
                    @can('bank.create')
                        <a href="#addModal" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus"></i> Create Bank</a>
                    @endcan
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Account Number</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banks as $bank)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $bank->name }}</td>
                                    <td>{{ $bank->account_number }}</td>
                                    <td>{{ $bank->transactions->sum('credit') - $bank->transactions->sum('debit') }}</td>
                                    <td>
                                        @can('brand.edit')
                                            <a href="#editModal{{ $bank->id }}" class="btn btn-primary" data-toggle="modal" title="Edit"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('brand.delete')
                                            <!-- <a href="#deleteModal{{ $bank->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a> -->
                                        @endcan
                                    </td>
                                </tr>

                                <!-- Edit brand Modal -->
                                <div class="modal fade" id="editModal{{ $bank->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    Edit - {{ $bank->name }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('bank.update', $bank->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Bank *</label>
                                                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" Value="{{ $bank->name }}" required>
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Account Number *</label>
                                                                <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" Value="{{ $bank->account_number }}" required>
                                                                @error('account_number')
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
                <div class="modal fade" id="deleteModal{{ $bank->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" align="right">
                                <form action="{{ route('brand.destroy', $bank->id) }}" method="POST">
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
                        <th>Account Number</th>
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
                        <h5 class="modal-title" id="exampleModalLabel">Create Bank</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('bank.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Bank Name *</label>
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
                                        <label>Account Number *</label>
                                        <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" placeholder="Account Number">
                                        @error('account_number')
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
