@extends('admin.layouts.master')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">VAT Management Panel</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">vat-entries</li>
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
                    {{-- bkash_panel.search --}}
                    <form action="{{ route('vat_entry.search') }}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>VAT Status</label>
                                    <select name="status" class="select2 form-control @error('status') is-invalid @enderror">
                                        <option value="">Please Select a Status</option>
                                        <option value="0" {{ $status == '0' ? 'selected' : '' }}>OUT STANDING</option>
                                        <option value="1" {{ $status == '1' ? 'selected' : '' }}>PAID</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="color: #fff;">.</label>
                                    <button type="submit" class="form-control btn btn-primary">Search</button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label style="color: #fff;">.</label>
                                <a href="{{ route('vat_entry.index') }}" class="form-control btn btn-primary">Clear Filter</a>
                            </div>

                        </div>
                    </form>

                    <div class="row mt-4">
                        <div class="col-md-8 m-auto">
                            <table id="" class="table table-bordered table-striped table-hover">
                                <tr style="font-size: 20px !important">
                                    <th width="25%">Total Outstanding</th>
                                    <td class="text-center font-weight-bold text-unpaid">&#2547; {{ $total_outstanding }}</td>
                                    <th width="25%">Total Paid</th>
                                    <td class="text-center font-weight-bold text-success">&#2547; {{ $total_paid }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                {{-- @include('admin.partials.page_search') --}}
                {{-- <p class="text-left ml-4 mb-0 mt-0">
                    <a href="{{ route('sell.sell.export') }}">
                        <i class="fas fa-file-export fa-sm mr-1"></i> Export to excel
                    </a>
                </p> --}}
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>S.N</th>
                                <th>Date of Sell</th>
                                <th>BIN No.</th>
                                <th>Memo No.</th>
                                <th>Sold Amount</th>
                                <th width="10%">VAT Amount</th>
                                <th>Status</th>
                                <th width="8%" class="text-center">Payment</th>
                                @can('vat-entry.delete')
                                    <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        @php
                            $business = App\Models\Setting::find(1);
                        @endphp
                        <tbody>
                            @foreach ($vat_entries as $item)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->date_of_sell }}</td>
                                    <td>{{ $business->bin_no }}</td>
                                    <td>
                                        @if ($item->order->source == 'Wholesale')
                                            <a class="" href="{{ route('order.edit', $item->order->id) }}">{{ $item->order->code }}
                                                <span class="ml-1 badge bg-secondary">Wholesale</span></a>
                                        @else
                                            <a class="" href="{{ route('order.edit', $item->order->id) }}">{{ $item->order->code }}
                                                <span class="ml-1 badge bg-primary">Retail</span></a>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">&#2547; {{ $item->order->sold_amount() }}</td>
                                    <td class="font-weight-bold">
                                        @if ($item->is_paid == 1)
                                            <span class="text-success">&#2547; {{ $item->vat_amount }}</span>
                                        @else
                                            <span class="text-unpaid">&#2547; {{ $item->vat_amount }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->is_paid == 1)
                                            <span class="badge badge-success">PAID</span>
                                        @else
                                            <span class="badge badge-warning">OUT STANDING</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->is_paid == 1)
                                            <button class="btn btn-success btn-sm" disabled><i class="fas fa-check"></i></button>
                                        @else
                                            <a href="#paid-done{{ $item->id }}" class="btn btn-info btn-sm" data-toggle="modal" title="Confirm VAT Payment">Confirm</a>
                                        @endif
                                    </td>
                                    @can('vat-entry.delete')
                                        <td>
                                            <a href="#deleteModal{{ $item->id }}" class="btn btn-danger btn-sm" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                                        </td>
                                    @endcan
                                </tr>

                                @if ($item->is_paid != 1)
                                    <!-- paid_done Modal -->
                                    <div class="modal fade" id="paid-done{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Confirm VAT Payment</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('vat_entry.paid', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="row justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary ml-1 mr-2">Confirm</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @can('vat-entry.delete')
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Are tou sure you want to delete this entry?</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('vat_entry.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            @endforeach
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
        // $(function() {
        //     var table = $('#data-table').DataTable({
        //         processing: true,
        //         serverSide: true,
        //         ordering: false,
        //         columns: [{
        //                 data: 'id'
        //             },
        //             {
        //                 data: 'code'
        //             },
        //             {
        //                 data: 'name'
        //             },
        //             {
        //                 data: 'phone'
        //             },
        //             {
        //                 data: 'status'
        //             },
        //             {
        //                 data: 'note'
        //             },
        //             {
        //                 data: 'source'
        //             },
        //             {
        //                 data: 'cod'
        //             },
        //             {
        //                 data: 'date'
        //             },
        //             {
        //                 data: 'action',
        //                 orderable: false,
        //                 searchable: true
        //             },
        //         ],
        //     });

        // });

        $(function() {
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
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6],
                            // rows:
                        },
                        // customize: function(doc) {
                        //     doc.defaultStyle.font = "nikosh";
                        // }
                    },

                    'print',
                ]
            }).buttons().container().appendTo('#data-table_wrapper .col-md-6:eq(0)');

        });

        // $(document).ready(function() {
        //     var table = $('#example').DataTable();
        //     var pageNo = 6
        //     table.page(pageNo - 1).draw('page');
        // });
    </script>
@endsection
