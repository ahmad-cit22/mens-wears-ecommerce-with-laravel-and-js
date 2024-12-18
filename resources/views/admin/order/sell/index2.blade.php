@extends('admin.layouts.master')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Sells
                        @if (!$all)
                            @if (!auth()->user()->vendor)
                                <a href="{{ route('sell.export.excel', 1) }}" class="ml-3 btn btn-primary btn-sm"
                                    style="">View All</a>
                            @else
                                <a href="{{ route('vendor_sell.export.excel', 1) }}" class="ml-3 btn btn-primary btn-sm"
                                    style="">View All</a>
                            @endif
                        @endif
                    </h1>
                    {{-- <div class="row justify-content-end"> --}}

                    {{-- </div> --}}
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">all-sell</li>
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
                    @if (!$all)
                        <div class="row">
                            <div class="col-lg-7">
                                <h3>Total Sells Confirmed : {{ count($orders->where('order_status_id', '!=', 5)) }}
                                    (Completed: {{ count($orders->where('order_status_id', '==', 4)) }})</h3>
                                <h3 class="text-success">Total Sold Amount :
                                    {{ round(
                                        $orders->filter(function ($order) {
                                                return $order->order_status_id != 5 && $order->is_return != 1;
                                            })->sum('price'),
                                    ) }}
                                    TK
                                </h3>
                                <h5 class="text-" style="color: #e97900">Total Sells Returned :
                                    {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', '!=', 0)) }}
                                    (Fully: {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', 1)) }},
                                    Partially:
                                    {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', 2)) }})</h5>
                                <h5 class="text-danger mt-3">Total Orders Cancelled :
                                    {{ count($orders->where('order_status_id', '==', 5)) }}</h5>
                            </div>
                            <div class="col-lg-5">
                                <h4>Total Sells From POS :
                                    {{ count($orders->where('source', 'Offline')->where('order_status_id', '!=', 5)) }}
                                    (Completed:
                                    {{ count($orders->where('source', 'Offline')->where('order_status_id', '==', 4)) }})
                                </h4>
                                <h4>Total Sells From Website :
                                    {{ count($orders->where('source', 'Website')->where('order_status_id', '!=', 5)) }}
                                    (Completed:
                                    {{ count($orders->where('source', 'Website')->where('order_status_id', '==', 4)) }})
                                </h4>
                            </div>
                        </div>
                    @endif
                    <hr>
                    <form action="{{ route('sell.search.export', 0) }}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date From</label>
                                    <input type="date" name="date_from"
                                        class="form-control @error('date_from') is-invalid @enderror"
                                        @if ($date_from != '') value="{{ $date_from }}" @endif>
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
                                    <input type="date" name="date_to"
                                        class="form-control @error('date_to') is-invalid @enderror"
                                        @if ($date_to != '') value="{{ $date_to }}" @endif>
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
                                    <select name="order_status_id"
                                        class="select2 form-control @error('order_status_id') is-invalid @enderror">
                                        <option value="">Please Select a Status (Optional)</option>
                                        @foreach (App\Models\OrderStatus::where('is_active', 1)->get() as $status)
                                            <option value="{{ $status->id }}"
                                                @if ($order_status_id != '' && $order_status_id == $status->id) selected @endif>{{ $status->title }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('order_status_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select name="district_id" id="district_id"
                                        class="select2 form-control @error('district_id') is-invalid @enderror">
                                        <option value="">Please Select a District (Optional)</option>
                                        @foreach (App\Models\District::get() as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach

                                    </select>
                                    @error('district_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Area</label>
                                    <select name="area_id" id="areas"
                                        class="select2 form-control @error('area_id') is-invalid @enderror">
                                        <option value="">Please Select an Area (Optional)</option>

                                    </select>
                                    @error('area_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Courier Name</label>
                                    <select name="courier_name" id="areas"
                                        class="select2 form-control @error('courier_name') is-invalid @enderror">
                                        <option value="0">Please Select a Courier Name (Optional)</option>
                                        @foreach (App\Models\CourierName::get() as $courier)
                                            <option value="{{ $courier->name }}"
                                                @if ($courier_name != '' && $courier_name == $courier->name) selected @endif>{{ $courier->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('courier_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="color: #fff;">.</label>
                                    <button type="submit" class="form-control btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-header -->
                {{-- @include('admin.partials.page_search') --}}
                {{-- <p class="text-left ml-4 mb-0 mt-0">
                    <a href="{{ route('sell.sell.export') }}">
                        <i class="fas fa-file-export fa-sm mr-1"></i> Export to excel
                    </a>
                </p> --}}
                @if ($all)
                    <div class="row mt-3">
                        <div class="col-lg-2">
                        </div>

                        @if (!auth()->user()->vendor)
                            <div class="col-lg-8">
                                <form class="row" action="{{ route('sell.export.excel.search_table', 1) }}"
                                    method="get" role="search">
                                    <input type="text" placeholder="Search with order code.." name="search_code"
                                        class="col-md-5 form-control" style="width: 70%; margin-right: 10px">
                                    <input type="text" placeholder="Search with order phone.." name="search_phone"
                                        class="col-md-5 form-control" style="width: 70%; margin-right: 10px">
                                    <button type="submit" class="col-md-1 btn btn-primary"><i
                                            class="fa fa-search fa-sm"></i></button>
                                </form>
                            </div>

                            <div class="col-lg-2">
                                <form class="row" action="{{ route('sell.export.excel', 1) }}" method="get"
                                    role="search">
                                    <input type="number" placeholder="Go to page.." name="page" class="form-control"
                                        style="width: 70%; margin-right: 10px">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fa fa-location-arrow fa-sm"></i></button>
                                </form>
                            </div>
                        @else
                            <div class="col-lg-8">
                                <form class="row" action="{{ route('sell.export.excel.search_table', 1) }}"
                                    method="get" role="search">
                                    <input type="text" placeholder="Search with order code.." name="search_code"
                                        class="col-md-5 form-control" style="width: 70%; margin-right: 10px">
                                    <input type="text" placeholder="Search with order phone.." name="search_phone"
                                        class="col-md-5 form-control" style="width: 70%; margin-right: 10px">
                                    <button type="submit" class="col-md-1 btn btn-primary"><i
                                            class="fa fa-search fa-sm"></i></button>
                                </form>
                            </div>

                            <div class="col-lg-2">
                                <form class="row" action="{{ route('vendor_sell.export.excel', 1) }}" method="get"
                                    role="search">
                                    <input type="number" placeholder="Go to page.." name="page" class="form-control"
                                        style="width: 70%; margin-right: 10px">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fa fa-location-arrow fa-sm"></i></button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <p class="text-right mr-4 mt-2">
                        @if (!auth()->user()->vendor)
                            <a href="{{ route('sell.export.excel', 1) }}">
                            @else
                                <a href="{{ route('vendor_sell.export.excel', 1) }}">
                        @endif
                        <i class="fas fa-reply fa-sm mr-1"></i> Reset Results
                        </a>
                    </p>
                @endif
                <div class="card-body">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Code</th>
                                <th>Customer Name</th>
                                <th width="6%">Phone</th>
                                <th width="9%">Sub Total</th>
                                <th width="9%">Delivery Charge</th>
                                <th width="9%">Discount</th>
                                <th width="9%">Advance</th>
                                <th width="9%">COD</th>
                                <th width="9%">Total Payable</th>
                                <th width="11%">Order Products</th>
                                <th width="10%">Courier Info</th>
                                <th width="6%">Status</th>
                                <th width="10%">Note</th>
                                <th>Source</th>
                                <th width="8%">Date</th>
                                @if (auth()->user()->can('vat.calculate'))
                                    <th>VAT</th>
                                @endif
                                <th>Created By</th>
                                @if ($all)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <b>
                                            <a class="" href="{{ route('order.edit', $item->id) }}"><span
                                                    class="bigFont badge badge-info">{{ $item->code }}</span></a>
                                        </b>
                                    </td>
                                    <td><b>{{ $item->name }}</b></td>
                                    <td width="6%" style="font-size: 12px !important;"><b>{{ $item->phone }}</b>
                                    </td>
                                    <td><span class="m-0"><b>{{ round($item->price + $item->discount_amount + $item->cod) }}/-
                                            </b></span></td>
                                    <td><span class="m-0"><b>{{ $item->delivery_charge }}/- </b></span></td>
                                    <td>
                                        @if ($item->discount_amount)
                                            <span class="m-0"><b>{{ $item->discount_amount }}/- </b></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->advance)
                                            <span class="m-0"><b>{{ $item->advance }}/- </b></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->cod)
                                            <span class="m-0"><b>{{ $item->cod }}/- </b></span>
                                        @endif
                                    </td>
                                    <td><span class="m-0"><b>{{ round($item->price + $item->delivery_charge - $item->advance) }}/-
                                            </b></span></td>
                                    <td>
                                        @foreach ($item->order_product as $key => $order_product)
                                            <p><b>{{ $key + 1 }}. {{ $order_product->product->title }}</b> x
                                                {{ $order_product->qty }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        <p class="m-0">Courier: <b>{{ $item->courier_name }} </b></p>
                                        <p class="m-0">Refer Code: <b>{{ $item->refer_code ?? 'N/A' }} </b></p>
                                    </td>
                                    <td>
                                        @if ($item->is_return == 1)
                                            <span
                                                class="badge badge-{{ $item->status->color }}">{{ $item->status->title }}</span>
                                            <br>
                                            <span class="badge badge-danger">Returned</span>
                                        @elseif ($item->is_return == 2)
                                            <span
                                                class="badge badge-{{ $item->status->color }}">{{ $item->status->title }}</span>
                                            <br>
                                            <span class="badge badge-danger">Returned Partially</span>
                                        @else
                                            <span
                                                class="badge badge-{{ $item->status->color }}">{{ $item->status->title }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->note }}</td>
                                    <td>{{ $item->source }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->created_at)->format('d M, Y g:iA') }}</td>
                                    @if (auth()->user()->can('vat.calculate'))
                                        <td>
                                            @if ($item->is_return != 1 && !$item->vat_entry)
                                                <a href="#vat-entry{{ $item->id }}"
                                                    class="ml-1 btn btn-info btn-sm mt-2" data-toggle="modal"
                                                    title="Calculate VAT"><i class="fas fa-dollar-sign"></i></a>
                                            @else
                                                @if (!$item->vat_entry)
                                                    --
                                                @else
                                                    <button class="ml-1 btn btn-success btn-sm mt-2"
                                                        title="VAT Calculation Done" disabled><i
                                                            class="fas fa-check"></i></button>
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        @if ($item->created_by)
                                            <a
                                                href="{{ route('user.edit', $item->created_by->user_id) }}">{{ $item->created_by->adder->name }}</a>
                                        @else
                                            --
                                        @endif
                                    </td>
                                    @if ($all)
                                        <td>
                                            <a href="{{ route('order.invoice.generate', $item->id) }}"
                                                class="btn btn-secondary" title="Download Invoice"><i
                                                    class="fas fa-download"></i></a>
                                            <a href="{{ route('order.invoice.pos.generate', $item->id) }}"
                                                class="btn btn-success" title="Print Invoice"><i
                                                    class="fas fa-print"></i></a>
                                            <a href="{{ route('order.edit', $item->id) }}" class="btn btn-primary"
                                                title="Edit"><i class="fas fa-edit"></i></a>
                                            @hasrole(1)
                                                <a href="#deleteModal{{ $item->id }}" class="btn btn-danger"
                                                    data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                                            @endhasrole
                                        </td>
                                    @endif
                                </tr>

                                @if ($all)
                                    @hasrole(1)
                                        <!-- Delete order Modal -->
                                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Are tou sure you want
                                                            to
                                                            delete ?</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('order.destroy', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Permanent
                                                                Delete</button>
                                                        </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endhasrole
                                    @if ($item->is_return != 1 && !$item->vat_entry)
                                        <!-- vat_entry_confirm Modal -->
                                        <div class="modal fade" id="vat-entry{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Confirm VAT
                                                            Entry -
                                                            {{ $item->code }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('sell.vat.calculate', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="row justify-content-end">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary ml-1 mr-2">Confirm</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </tbody>

                    </table>
                </div>
                @if ($all)
                    @php
                        $total = $orders->total();
                        $currentPage = $orders->currentPage();
                        $perPage = $orders->perPage();

                        $from = ($currentPage - 1) * $perPage + 1;
                        $to = min($currentPage * $perPage, $total);

                    @endphp

                    <p class="ml-4">
                        Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                    </p>
                    <div class="row justify-content-center">{{ $orders->withQueryString()->links() }}</div>
                @endif
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

        @if (!$all)
            $(function() {
                $("#data-table").DataTable({
                    "responsive": false,
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
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
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
        @endif

        // $(document).ready(function() {
        //     var table = $('#example').DataTable();
        //     var pageNo = 6
        //     table.page(pageNo - 1).draw('page');
        // });
    </script>
@endsection
