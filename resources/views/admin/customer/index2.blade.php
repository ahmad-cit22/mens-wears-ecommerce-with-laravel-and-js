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
                            <input type="text" placeholder="Search with name.." name="search" class="form-control"
                                style="width: 70%; margin-right: 10px">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
                        </form>
                    </div>

                    <div class="col-2">
                        <form class="row" action="{{ route('customer.search') }}" method="get" role="search">
                            <input type="number" placeholder="Search with phone.." name="search_phone" class="form-control"
                                style="width: 70%; margin-right: 10px">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-sm"></i></button>
                        </form>
                    </div>

                    <div class="col-2">
                        <form class="row" action="{{ route('customer.index') }}" method="get" role="search">
                            <input type="number" placeholder="Go to page.." name="page" class="form-control"
                                style="width: 70%; margin-right: 10px">
                            <button type="submit" class="btn btn-primary"><i
                                    class="fa fa-location-arrow fa-sm"></i></button>
                        </form>
                    </div>
                </div>
                <p class="text-right mr-3 mt-2">
                    <a href="{{ route('customer.index') }}">
                        <i class="fas fa-reply fa-sm mr-1"></i> Reset Results
                    </a>
                </p>
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover datatable">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Orders</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $key => $customer)
                                <tr>
                                    {{-- @php
                                        $i = $customers->perPage() * ($customers->currentPage() - 1);
                                    @endphp --}}
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $customer->name . ' ' . $customer->last_name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    @if (Auth::id() == 1)
                                        <td><a href="" data-toggle="modal"
                                                data-target="#exampleModalCenter">{{ $customer->phone }}</a></td>
                                    @else
                                        <td>{{ $customer->phone }}</td>
                                    @endif
                                    <td>
                                        <form action="{{ route('customer.status_update', $customer->id) }}" method="POST">
                                            @csrf
                                            <select name="status" onchange="changeStatus(this, {{ $customer->id }})"
                                                class="form-select @error('status')is-invalid @enderror" required>
                                                <option value="0" {{ $customer->is_active == 0 ? 'selected' : '' }}>
                                                    Inactive</option>
                                                <option value="1" {{ $customer->is_active == 1 ? 'selected' : '' }}>
                                                    Active</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        @if (count($customer->orders))
                                            <a
                                                href="{{ route('order.customer.orders', $customer->id) }}">{{ count($customer->orders) }}</a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('customer.type_update', $customer->id) }}" method="POST">
                                            @csrf
                                            <select name="is_fraud" onchange="changeType(this, {{ $customer->id }})"
                                                class="form-select @error('is_fraud')is-invalid @enderror" required>
                                                <option value="0" {{ $customer->is_fraud == 0 ? 'selected' : '' }}>
                                                    Regular</option>
                                                <option value="1" {{ $customer->is_fraud == 1 ? 'selected' : '' }}>
                                                    Fraud</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                {{-- <th>Referrer</th> --}}
                                <th>Status</th>
                                <th>Orders</th>
                                <th>Type</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row">
                        <a href="{{ route('customer.index.excel', $page + 1) }}" class="ml-3 mt-2 btn btn-primary btn-sm"
                            style="">View Next 1000</a>
                    </div>
                </div>
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

        $("#data-table").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [

                {
                    extend: 'excel',
                    footer: 'true',
                    text: 'Excel',
                },

                {
                    extend: 'pdf',
                    footer: 'true',
                    text: 'PDF',
                    orientation: 'landscape',
                    // exportOptions: {
                    //     columns: [0, 1, 2, 3, 4, 5, 6],
                    //     // rows:
                    // },
                    // customize: function(doc) {
                    //     doc.defaultStyle.font = "nikosh";
                    // }
                },

                'print',
            ]
        }).buttons().container().appendTo('#data-table_wrapper .col-md-6:eq(0)');
    </script>
@endsection
