@extends('admin.layouts.master')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Create Member</h4>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Membership Panel</li>
                        <li class="breadcrumb-item active">Members</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <!-- Main content -->
                    <form action="{{ route('membership.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="p-4 invoice col-10 m-auto" style="border-radius: 10px">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="selectmain">
                                            <label class="text-dark d-flex">Customer *</label>
                                            <select name="user_id" class="select2 select-down" id="user_id" style="width: 100% !important;" required>
                                                <option value="0">Walk in Customer</option>
                                                @foreach ($customers as $item)
                                                    @if (!$item->member)
                                                        <option value="{{ $item->id }}" {{ old('user_id') == $item->id ? 'selected' : '' }}>{{ $item->name . ' - ' . $item->phone }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="selectmain">
                                            <label class="text-dark d-flex">Card Status *</label>
                                            <select name="membership_card_id" class="select2 select-down" id="membership_card_id" style="width: 100% !important;" required>
                                                <option value="0">--- Select an Option ---</option>
                                                @foreach ($cards as $item)
                                                    <option value="{{ $item->id }}" {{ old('membership_card_id') == $item->id ? 'selected' : '' }}>{{ $item->card_status }}</option>
                                                @endforeach
                                            </select>
                                            @error('membership_card_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-body">Card Number *</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="text" name="card_number" class="form-control" placeholder="Enter the Membership Card Number" value="{{ old('card_number') }}" required>
                                        </fieldset>
                                        @error('card_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row" id="new-customer-form">
                                    <div class="col-md-4 mb-3">
                                        <label class="text-body">Name *</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter the Name" value="{{ old('name') }}" required>
                                        </fieldset>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="text-body">Phone *</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter the Phone" value="{{ old('phone') }}" required>
                                        </fieldset>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="text-body">Address</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="text" name="address" class="form-control" placeholder="Enter the address" value="{{ old('address') }}">
                                        </fieldset>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row justify-content-end">
                                    <button type="submit" class="mr-2 btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <!-- /.card-header -->
                @include('admin.partials.page_search')
                <h3 class="ml-3">Member List</h3>
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th width="12%">Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Card No.</th>
                                <th>Status</th>
                                <th>Registration Date</th>
                                <th>Discount Rate</th>
                                <th>Total Purchase</th>
                                <th>Point Balance</th>
                                <th>Points Used</th>
                                <th width="9%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

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
        $('#user_id').change(function() {
            var customer_id = $(this).val();

            if (customer_id == '0') {
                $('#new-customer-form').show();

                $("#name").prop('required', true);
                $("#phone").prop('required', true);

            } else {
                $('#new-customer-form').hide();

                $("#name").prop('required', false);
                $("#phone").prop('required', false);
            }
        })
    </script>
    <script type="text/javascript">
        $(function() {
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'card_number'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'discount_rate'
                    },
                    {
                        data: 'purchase'
                    },
                    {
                        data: 'current_points'
                    },
                    {
                        data: 'used_points'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: true
                    },
                ],
            });
        });
    </script>
@endsection
