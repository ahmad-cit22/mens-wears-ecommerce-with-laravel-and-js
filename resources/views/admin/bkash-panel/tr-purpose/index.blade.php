@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bkash Transaction Purposes List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Bkash Transaction Purposes</li>
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
                    <a href="#addModal" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus"></i> Create Purpose</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Color</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purposes as $purpose)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $purpose->title }}</td>
                                    <td><span class="badge badge-{{ $purpose->color }}">{{ $purpose->color }}</span></td>
                                    <td>
                                        <a href="#editModal{{ $purpose->id }}" class="btn btn-primary" data-toggle="modal" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="#deleteModal{{ $purpose->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>

                                <!-- Edit size Modal -->
                                <div class="modal fade" id="editModal{{ $purpose->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    Edit - {{ $purpose->title }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('bkash_panel.tr_purposes.update', $purpose->id) }}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Purpose Name *</label>
                                                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Purpose Name" value="{{ $purpose->title }}" required>
                                                                @error('title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Purpose Color *</label>
                                                                <div class="selectmain">
                                                                    <select name="color" class="select2 select-down" id="">
                                                                        <option value="0"> -- Choose an Option -- </option>
                                                                        <option value="primary" {{ $purpose->color == 'primary' ? 'selected' : '' }}>Primary</option>
                                                                        <option value="secondary" {{ $purpose->color == 'secondary' ? 'selected' : '' }}>Secondary</option>
                                                                        <option value="success" {{ $purpose->color == 'success' ? 'selected' : '' }}>Success</option>
                                                                        <option value="danger" {{ $purpose->color == 'danger' ? 'selected' : '' }}>Danger</option>
                                                                        <option value="warning" {{ $purpose->color == 'warning' ? 'selected' : '' }}>Warning</option>
                                                                        <option value="info" {{ $purpose->color == 'info' ? 'selected' : '' }}>Info</option>
                                                                        <option value="light" {{ $purpose->color == 'light' ? 'selected' : '' }}>Light</option>
                                                                        <option value="dark" {{ $purpose->color == 'dark' ? 'selected' : '' }}>Dark</option>
                                                                    </select>
                                                                </div>
                                                                @error('color')
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
                <!-- Delete size Modal -->
                <div class="modal fade" id="deleteModal{{ $purpose->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" align="right">
                                <form action="{{ route('bkash_panel.tr_purposes.destroy', $purpose->id) }}" method="POST">
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
                        <th>Color</th>
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
                <form action="{{ route('bkash_panel.tr_purposes.store') }}" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create Purpose</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Purpose Name *</label>
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Purpose Name" required>
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Purpose Color *</label>
                                        <div class="selectmain">
                                            <select name="color" class="select2 select-down" id="">
                                                <option value="0"> -- Choose an Option -- </option>
                                                <option value="primary">Primary</option>
                                                <option value="secondary">Secondary</option>
                                                <option value="success">Success</option>
                                                <option value="danger">Danger</option>
                                                <option value="warning">Warning</option>
                                                <option value="info">Info</option>
                                                <option value="light">Light</option>
                                                <option value="dark">Dark</option>
                                            </select>
                                        </div>
                                        @error('color')
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
