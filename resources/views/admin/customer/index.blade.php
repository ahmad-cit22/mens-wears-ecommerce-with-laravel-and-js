@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Customers List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}" target="_blank">Home</a></li>
                        <li class="breadcrumb-item active">Customer</li>
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

                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Referrer</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Orders</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $customer->name . ' ' . $customer->last_name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    @if (Auth::id() == 1)
                                        <td><a href="" data-toggle="modal" data-target="#exampleModalCenter">{{ $customer->phone }}</a></td>
                                    @else
                                        <td>{{ $customer->phone }}</td>
                                    @endif
                                    <td>{{ optional($customer->referrer)->name }}</td>
                                    <td><img src="{{ asset('images/customer/' . $customer->image) }}" width="100"></td>
                                    <td>
                                        <form action="{{ route('customer.status_update', $customer->id) }}" method="POST">
                                            @csrf
                                            <select name="status" onchange="changeStatus(this, {{ $customer->id }})" class="form-select @error('status')is-invalid @enderror" required>
                                                <option value="0" {{ $customer->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                                <option value="1" {{ $customer->is_active == 1 ? 'selected' : '' }}>Active</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        @if (count($customer->orders))
                                            <a href="{{ route('order.customer.orders', $customer->id) }}">{{ count($customer->orders) }}</a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        @if (Auth::id() == 1)
                                            <a href="#deleteModal{{ $customer->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('customer.password.change.admin', $customer->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Change Password - {{ $customer->name . ' ' . $customer->last_name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>


                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <p>Phone: {{ $customer->phone }}</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Password *</label>
                                                        <input type="text" name="password" class="form-control @error('password') is-invalid @enderror">
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- customer Modal -->
                                <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Are tou sure you want to delete ?</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" align="right">
                                                <form action="{{ route('customer.destroy', $customer->id) }}" method="POST">
                                                    {{ csrf_field() }}
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
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Referrer</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Orders</th>
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

    <script>
        function changeStatus(selectElement, id) {
            let formElement = selectElement.parentNode;

            Swal.fire({
                title: 'Are you sure?',
                text: "Change the status only if you are sure about it!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed.'
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit();
                }
            });
        }
    </script>
@endsection
