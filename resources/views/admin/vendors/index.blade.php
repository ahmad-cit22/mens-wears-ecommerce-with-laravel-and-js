@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Vendor/Display Center List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">vendor-list</li>
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
                    <a href="#addModal" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus mr-2"></i> Create Vendor/Display Center</a>
                    {{-- <a href="{{ route('partnertransaction.index') }}" class="btn btn-success"><i class="fas fa-eye mr-2"></i> Partner's Transactios</a> --}}
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Vendor Name</th>
                                <th>Owner Name</th>
                                <th>Opening Balance</th>
                                <th>Total Invested</th>
                                <th>Profit Share</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendors as $vendor)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $vendor->name }}</td>
                                    <td>
                                        <a href="{{ route('user.edit', $vendor->user_id) }}">{{ $vendor->user->name }}</a>
                                    </td>
                                    <td>&#2547; {{ $vendor->opening_balance }}</td>
                                    <td>&#2547; {{ $vendor->invested_amount }}</td>
                                    <td>{{ $vendor->profit_percentage }}%</td>
                                    <td>
                                        @if ($vendor->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @hasrole([1])
                                            {{-- @can('brand.edit') --}}
                                            <a href="#editModal{{ $vendor->id }}" class="btn btn-primary" data-toggle="modal" title="Edit"><i class="fas fa-edit"></i></a>
                                            <a href="#deleteModal{{ $vendor->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                                            <a href="{{ route('report.incomestatement.vendor', $vendor->id) }}" class="btn btn-info" title="View Report"><i class="fas fa-eye"></i></a>
                                            {{-- <a href="{{ route('vendor.vendor_transaction', $vendor->id) }}" class="btn btn-secondary" title="View Transactions"><i class="fas fa-dollar-sign"></i></a> --}}
                                            {{-- @endcan --}}
                                        @endhasrole
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $vendor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    Edit Vendor - {{ $vendor->name }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('vendor.update', $vendor->id) }}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Vendor/Display Center Name *</label>
                                                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" Value="{{ $vendor->name }}" required>
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Owner Name *</label>
                                                                <select name="user_id" class="select2 select-down" style="width: 100% !important;" required>
                                                                    <option value="0">--- Select an Option ---</option>
                                                                    @foreach ($users as $item)
                                                                        <option value="{{ $item->id }}" {{ $vendor->user_id == $item->id ? 'selected' : '' }}>{{ $item->name . ' - ' . $item->phone }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('user_id')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Profit Share(%) *</label>
                                                                <input type="number" name="profit_percentage" class="form-control @error('profit_percentage') is-invalid @enderror" placeholder="Profit Share Portion (in %)" value="{{ $vendor->profit_percentage }}">
                                                                @error('profit_percentage')
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

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $vendor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Are tou sure you want to delete ?</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST">
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
                </div>
                <!-- Delete brand Modal -->
                <div class="modal fade" id="deleteModal{{ $vendor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" align="right">
                                <form action="{{ route('brand.destroy', $vendor->id) }}" method="POST">
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
                        <th>Vendor Name</th>
                        <th>Owner Name</th>
                        <th>Opening Balance</th>
                        <th>Total Invested</th>
                        <th>Profit Share</th>
                        <th>Status</th>
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
                        <h5 class="modal-title" id="exampleModalLabel">Create Vendor/Display Center</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('vendor.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Vendor/Display Center Name *</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Vendor/Display Center Name" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Owner Name *</label>
                                        <select name="user_id" class="select2 select-down" id="user_id" style="width: 100% !important;" required>
                                            <option value="0">--- Select an Option ---</option>
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}" {{ old('user_id') == $item->id ? 'selected' : '' }}>{{ $item->name . ' - ' . $item->phone }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Invested Amount *</label>
                                        <input type="number" name="invested_amount" class="form-control @error('invested_amount') is-invalid @enderror" placeholder="Invested Amount">
                                        @error('invested_amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Profit Share(%) *</label>
                                        <input type="number" name="profit_percentage" class="form-control @error('profit_percentage') is-invalid @enderror" placeholder="Profit Share Portion (in %)">
                                        @error('profit_percentage')
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
