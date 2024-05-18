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
                <div class="row mt-3">
                    <div class="col-6">
                    </div>
                    <div class="col-2">
                        <form class="row" action="{{ route('customer.search') }}" method="get" role="search">
                            <input type="text" placeholder="Search with name.." name="search" class="form-control" style="width: 70%; margin-right: 10px">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
                        </form>
                    </div>

                    <div class="col-2">
                        <form class="row" action="{{ route('customer.search') }}" method="get" role="search">
                            <input type="number" placeholder="Search with phone.." name="search_phone" class="form-control" style="width: 70%; margin-right: 10px">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
                        </form>
                    </div>

                    <div class="col-2">
                        <form class="row" action="{{ route('customer.index') }}" method="get" role="search">
                            <input type="number" placeholder="Go to page.." name="page" class="form-control" style="width: 70%; margin-right: 10px">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-location-arrow fa-sm"></i></button>
                        </form>
                    </div>
                </div>
                <p class="text-right mr-3 mt-2">
                    <a href="{{ route('customer.index') }}">
                        <i class="fas fa-reply fa-sm mr-1"></i> Reset Results
                    </a>
                </p>
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover datatable">
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
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $key => $customer)
                                <tr>
                                    @php
                                        $i = $customers->perPage() * ($customers->currentPage() - 1);
                                    @endphp
                                    <td>{{ $i + $key + 1 }}</td>
                                    <td>{{ $customer->name . ' ' . $customer->last_name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    @if (Auth::id() == 1)
                                        <td><a href="" data-toggle="modal" data-target="#exampleModalCenter">{{ $customer->phone }}</a></td>
                                    @else
                                        <td>{{ $customer->phone }}</td>
                                    @endif
                                    <td>{{ optional($customer->referrer)->name }}</td>
                                    <td><img src="{{ $customer->image != null ? asset('images/customer/' . $customer->image) : asset('images/user/user-avatar-icon.jpg') }}" width="50"></td>
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
                                        <form action="{{ route('customer.type_update', $customer->id) }}" method="POST">
                                            @csrf
                                            <select name="is_fraud" onchange="changeType(this, {{ $customer->id }})" class="form-select @error('is_fraud')is-invalid @enderror" required>
                                                <option value="0" {{ $customer->is_fraud == 0 ? 'selected' : '' }}>Regular</option>
                                                <option value="1" {{ $customer->is_fraud == 1 ? 'selected' : '' }}>Fraud</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        @if (auth()->user()->can('customer.edit'))
                                            <a href="#editModal{{ $customer->id }}" class="btn btn-primary btn-sm" data-toggle="modal" title="Edit"><i class="fas fa-edit"></i></a>
                                        @endif
                                        @hasrole(1)
                                            <a href="#deleteModal{{ $customer->id }}" class="btn btn-danger btn-sm" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                                        @endhasrole
                                    </td>
                                </tr>
                                <!-- Modal -->
                                {{-- <div class="modal fade" id="exampleModalCenter{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                </div> --}}
                                <div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('customer.account.update', $customer->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Update Customer Profile - {{ $customer->name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>


                                                <div class="modal-body">
                                                    {{-- <div class="form-group">
                                                        <p>Phone: {{ $customer->phone }}</p>
                                                    </div> --}}
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $customer->name }}">
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ $customer->phone }}">
                                                        @error('phone')
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
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @php
                    $total = $customers->total();
                    $currentPage = $customers->currentPage();
                    $perPage = $customers->perPage();

                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp

                <p class="ml-4">
                    Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                </p>
                <div class="row justify-content-center">
                    {{ $customers->withQueryString()->links() }}
                </div>

                {{-- {{ $paginator->getOptions() }} --}}
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </section>
@endsection

@section('scripts')
    @error('name')
        <script>
            Swal.fire(
                'Oops!',
                "{{ $message }}",
                'error'
            );
        </script>
    @enderror
    @error('phone')
        <script>
            Swal.fire(
                'Oops!',
                "{{ $message }}",
                'error'
            );
        </script>
    @enderror

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

        function changeType(selectElement, id) {
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
