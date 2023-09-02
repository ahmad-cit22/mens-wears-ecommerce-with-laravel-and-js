@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Courier Info List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Courier Infos</li>
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
                    <a href="#addModal" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus"></i> Create Courier Info</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Charge One</th>
                                <th>Charge Two</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($couriers as $courier)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $courier->name }}</td>
                                    <td>{{ $courier->charge_one }}</td>
                                    <td>{{ $courier->charge_two }}</td>
                                    <td>
                                        <a href="#editModal{{ $courier->id }}" class="btn btn-primary" data-toggle="modal" number="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="#deleteModal{{ $courier->id }}" class="btn btn-danger" data-toggle="modal" number="Delete"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>

                                <!-- Edit size Modal -->
                                <div class="modal fade" id="editModal{{ $courier->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-number" id="exampleModalLabel">
                                                    Edit - {{ $courier->name }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('fos.courier_name.update', $courier->id) }}" method="POST">
                                                <div class="modal-body">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Courier Name * </label>
                                                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Courier Name" value="{{ $courier->name }}" required>
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Primary Charge </label>
                                                                <input type="number" name="charge_one" class="form-control @error('charge_one') is-invalid @enderror" placeholder="Enter primary Charge" value="{{ $courier->charge_one }}">
                                                                @error('charge_one')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Secondary Charge </label>
                                                                <input type="number" name="charge_two" class="form-control @error('charge_two') is-invalid @enderror" placeholder="Enter Secondary Charge" value="{{ $courier->charge_two }}">
                                                                @error('charge_two')
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
                                    </div>
                                </div>
                </div>
                <!-- Delete size Modal -->
                <div class="modal fade" id="deleteModal{{ $courier->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-number" id="exampleModalLabel">Are you sure you want to delete ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" align="right">
                                <form action="{{ route('fos.courier_name.destroy', $courier->id) }}" method="POST">
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
                        <th>Charge One</th>
                        <th>Charge Two</th>
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
                <form action="{{ route('fos.courier_name.store') }}" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-number" id="exampleModalLabel">Create Courier Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Courier Name * </label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Courier Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Primary Charge </label>
                                        <input type="number" name="charge_one" class="form-control @error('charge_one') is-invalid @enderror" placeholder="Enter primary Charge" value="{{ old('charge_one') }}">
                                        @error('charge_one')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Secondary Charge </label>
                                        <input type="number" name="charge_two" class="form-control @error('charge_two') is-invalid @enderror" placeholder="Enter Secondary Charge" value="{{ old('charge_two') }}">
                                        @error('charge_two')
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
                    </div>
                </form>
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
