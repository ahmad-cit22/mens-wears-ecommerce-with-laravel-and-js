@extends('admin.layouts.master')

@section('style')
    <style>
        .bg-special_status {
            background: rgb(255, 132, 0) !important;
        }

        .bigFont {
            font-size: 16px !important;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Order Sheet Entries</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Order Sheet Entries</li>
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
                    <div class="row">
                        <div class="col-4">
                            <h4>Total Offline Orders : {{ count($orders->where('source', 'Offline')) }}</h4>
                        </div>
                        <div class="col-4">
                            <h4>Total Website Orders : {{ count($orders->where('source', 'Website')) }}</h4>
                        </div>
                        <div class="col-4">
                            <h4>Total Wholesale Orders : {{ count($orders->where('source', 'Wholesale')) }}</h4>
                        </div>
                    </div>
                    <hr>
                    <form action="{{ route('fos.search') }}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date From</label>
                                    <input type="date" name="date_from" class="form-control @error('date_from') is-invalid @enderror" @if ($date_from != '') value="{{ $date_from }}" @endif>
                                    @error('date_from')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date To</label>
                                    <input type="date" name="date_to" class="form-control @error('date_to') is-invalid @enderror" @if ($date_to != '') value="{{ $date_to }}" @endif>
                                    @error('date_to')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="order_status_id" class="select2 form-control @error('order_status_id') is-invalid @enderror">
                                        <option value="">Please Select a Status (Optional)</option>
                                        @foreach (App\Models\FacebookOrderStatus::where('is_active', 1)->get() as $status)
                                            <option value="{{ $status->id }}" {{ $status->id == $order_status_id ? 'selected' : '' }}>{{ $status->title }}</option>
                                        @endforeach

                                    </select>
                                    @error('order_status_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Special Status</label>
                                    <select name="special_status_id" class="select2 form-control @error('special_status_id') is-invalid @enderror">
                                        <option value="">Please Select a Status (Optional)</option>
                                        @foreach (App\Models\OrderSpecialStatus::where('is_active', 1)->get() as $status)
                                            <option value="{{ $status->id }}" {{ $status->id == $special_status_id ? 'selected' : '' }}>{{ $status->title }}</option>
                                        @endforeach

                                    </select>
                                    @error('special_status_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-5">
                                    <label style="color: #fff;">.</label>
                                    <button type="submit" class="form-control btn  btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-header -->
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover datatable">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Memo Code</th>
                                <th style="min-width: 130px">Customer Name</th>
                                <th>Phone</th>
                                <th style="min-width: 150px">Address</th>
                                <th>Status</th>
                                <th>Special Status</th>
                                <th>Total Bill</th>
                                <th>Products</th>
                                <th>Courier Name</th>
                                <th style="min-width: 130px">Note</th>
                                <th style="min-width: 130px">Remarks</th>
                                <th>Source</th>
                                <th>Bkash Business</th>
                                <th>Bkash Customer</th>
                                <th>Bkash Amount</th>
                                <th>Email</th>
                                <th>Whatsapp Number</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="bg-{{ $order->status->color }}">
                                    <div class="bg"></div>
                                    <td>{{ $loop->index + 1 }}</td>

                                    <td class="text-center">
                                        <a class="mb-1 d-inline-block" href="{{ route('fos.edit', $order->id) }}"><span class="badge badge-light">{{ $order->code ?? 'N/A' }}</span></a>
                                        <a class="" href="{{ route('pos.create', $order->id) }}"><span class="badge badge-dark">Enter in POS</span></a>
                                    </td>

                                    <td width="20%" style="font-weight: bold">{{ $order->name }}</td>

                                    <td>{{ $order->phone }}</td>

                                    <td>{{ $order->shipping_address }}</td>

                                    <td><span class="badge bigFont badge-{{ $order->status->color }}">{{ $order->status->title }}</span></td>

                                    <td><span class="badge bigFont badge-{{ $order->special_status->color }}">{{ $order->special_status->title }}</span></td>

                                    <td class="{{ $order->special_status_id == 2 || $order->special_status_id == 3 ? 'bg-' . 'special_status' : '' }}" style="font-weight: bold">{{ env('CURRENCY') . $order->price }}</td>

                                    <td class="text-center {{ $order->special_status_id == 4 || $order->special_status_id == 5 ? 'bg-' . 'special_status' : '' }}">
                                        <a href="{{ route('fos.edit', $order->id) }}" class="btn bg-light text-dark" style="color: #000 !important; font-weight: bold" title="Edit">View</a>
                                    </td>

                                    <td>{{ $order->courier->name }}</td>

                                    <td class="{{ $order->special_status_id == 6 ? 'bg-' . 'special_status' : '' }}">{{ $order->note }}</td>

                                    <td class="{{ $order->special_status_id == 2 || $order->special_status_id == 3 || $order->special_status_id == 6 || $order->special_status_id == 7 ? 'bg-' . 'special_status' : '' }}">{{ $order->remarks ?? '--' }}</td>

                                    <td>{{ $order->source }}</td>

                                    <td>{{ $order->bkash_business_id ? $order->bkash_business->number : '--' }}</td>

                                    <td>{{ $order->bkash_num ?? '--' }}</td>

                                    <td>{{ $order->bkash_amount ? env('CURRENCY') . $order->bkash_amount : '--' }}</td>

                                    <td>{{ $order->email ?? '--' }}</td>

                                    <td>{{ $order->whatsapp_num ?? '--' }}</td>

                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y g:iA') }}</td>

                                    <td>
                                        <a href="{{ route('fos.edit', $order->id) }}" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="#deleteModal{{ $order->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <!-- Delete order Modal -->
                                <div class="modal fade" id="deleteModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Are tou sure you want to delete ?</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('fos.destroy', $order->id) }}" method="POST">
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
                                <th>Code</th>
                                <th width="20%">Customer Name</th>
                                <th width="10%">Phone</th>
                                <th width="15%">Address</th>
                                <th>Status</th>
                                <th>Special Status</th>
                                <th>Total Bill</th>
                                <th>Courier Name</th>
                                <th width="15%">Note</th>
                                <th width="15%">Remarks</th>
                                <th>Source</th>
                                <th>Bkash Business</th>
                                <th>Bkash Customer</th>
                                <th>Bkash Amount</th>
                                <th>Email</th>
                                <th>Whatsapp Number</th>
                                <th>Date</th>
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
                "responsive": false,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });
    </script>

    <script>
        $('#district_id').change(function() {
            var district_id = $(this).val();
            if (district_id == '') {
                district_id = -1;
            }
            var option = "<option value=''>Please Select an Area (Optional)</option>";
            var url = "{{ url('/') }}";

            $.get(url + "/get-area/" + district_id, function(data) {
                data = JSON.parse(data);
                data.forEach(function(element) {
                    option += "<option value='" + element.id + "'>" + element.name + "</option>";
                });
                //console.log(option);
                $('#areas').html(option);
            });

        });
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
                        data: 'code'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'note'
                    },
                    {
                        data: 'source'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]
            });

        });
    </script>
@endsection
