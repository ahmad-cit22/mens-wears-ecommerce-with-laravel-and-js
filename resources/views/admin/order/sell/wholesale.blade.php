@extends('admin.layouts.master')

@section('style')
    <style>
        .categoryCardBox {
            gap: 20px
        }

        .categoryCard1 {
            padding: 15px 15px 0px;
            padding-left: 22px;
            border-radius: 14px;
            background: rgba(235, 98, 0, 0.888);
            color: white;
            display: inline-block;
            max-width: 22% !important;
            box-shadow: 0px 4px 12px 1px rgba(124, 48, 0, 0.665);
        }

        .categoryCard2 {
            padding: 15px 15px 0px;
            padding-left: 22px;
            border-radius: 14px;
            background: rgba(238, 99, 0, 0.773);
            color: white;
            display: inline-block;
            max-width: 22% !important;
            box-shadow: 0px 4px 14px 2px rgba(124, 48, 0, 0.665);
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Wholesale Sells</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Sells</li>
                        <li class="breadcrumb-item active">Wholesale</li>
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
                        <div class="col-12">
                            <h3>Total Sells Confirmed : {{ count($orders->where('order_status_id', '!=', 5)) }} (Completed: {{ count($orders->where('order_status_id', '==', 4)) }})</h3>
                            <h3 class="text-success">Total Sold Amount :
                                {{ round(
                                    $orders->filter(function ($order) {
                                            return $order->order_status_id != 5 && $order->is_return != 1;
                                        })->sum('price'),
                                ) }} TK
                            </h3>
                            <h5 class="text-" style="color: #e97900">Total Sells Returned : {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', '!=', 0)) }} (Fully: {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', 1)) }}, Partially: {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', 2)) }})</h5>
                            <h5 class="text-danger mt-3">Total Orders Cancelled : {{ count($orders->where('order_status_id', '==', 5)) }}</h5>
                        </div>
                    </div>
                    <div class="row mt-5 categoryCardBox">
                        @foreach ($categories as $key => $category)
                            @php
                                $sells_cat = 0;
                                $sells_amount_cat = 0;
                            @endphp
                            @if ($category->parent_id == 0)
                                @foreach ($orders as $item)
                                    @if ($item->order_status_id != 5 && $item->is_return == 0)
                                        @foreach ($item->order_product as $order_product)
                                            @if ($order_product->product)
                                                @if ($order_product->product->category_id == $category->id)
                                                    @php
                                                        $sells_cat += $order_product->qty;
                                                        $sells_amount_cat += $order_product->price * $order_product->qty;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @if ($sells_cat > 0)
                                    <div class="col-3 gap-3 mb-2 categoryCard1">
                                        <h4 class=""><b>{{ $category->title }}</b></h4>
                                        <span>Total Sold: <span class="ml-1">{{ $sells_cat }} pc</span></span>
                                        <p>Total Sold Amount: <span class="ml-1">{{ round($sells_amount_cat) }} TK</span></p>
                                    </div>
                                @endif
                            @else
                                @foreach ($orders as $item)
                                    @if ($item->order_status_id != 5 && $item->is_return == 0)
                                        @foreach ($item->order_product as $order_product)
                                            @if ($order_product->product)
                                                @if ($order_product->product->sub_category_id == $category->id)
                                                    @php
                                                        $sells_cat += $order_product->qty;
                                                        $sells_amount_cat += $order_product->price * $order_product->qty;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @if ($sells_cat > 0)
                                    <div class="col-3 gap-3 mb-2 categoryCard2">
                                        <h5><b>{{ $category->parent->title . ' - ' . $category->title }}</b></h5>
                                        <span>Total Sold: <span class="ml-1">{{ $sells_cat }} pc</span></span>
                                        <p>Total Sold Amount: <span class="ml-1">{{ round($sells_amount_cat) }} TK</span></p>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                    <hr>
                    <form action="{{ route('sell.wholesale.search') }}" method="get">
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
                                        @foreach (App\Models\OrderStatus::where('is_active', 1)->get() as $status)
                                            <option value="{{ $status->id }}" @if ($order_status_id != '' && $order_status_id == $status->id) selected @endif>{{ $status->title }}</option>
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
                                    <select name="district_id" id="district_id" class="select2 form-control @error('district_id') is-invalid @enderror">
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
                                    <select name="area_id" id="areas" class="select2 form-control @error('area_id') is-invalid @enderror">
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
                                    <select name="courier_name" id="areas" class="select2 form-control @error('courier_name') is-invalid @enderror">
                                        <option value="0">Please Select a Courier Name (Optional)</option>
                                        @foreach (App\Models\CourierName::get() as $courier)
                                            <option value="{{ $courier->name }}" @if ($courier_name != '' && $courier_name == $courier->name) selected @endif>{{ $courier->name }}</option>
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
                @include('admin.partials.page_search')
                <p class="text-left ml-4 mb-0 mt-0">
                    <a href="{{ route('sell.wholesale.export') }}">
                        <i class="fas fa-file-export fa-sm mr-1"></i> Export to excel
                    </a>
                </p>
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Code</th>
                                <th>Customer Name</th>
                                <th width="9%">Phone</th>
                                <th>Status</th>
                                <th>Courier Name</th>
                                <th width="13%">Note</th>
                                <th>Source</th>
                                <th>COD</th>
                                <th>Date</th>
                                <th>Created By</th>
                                <th>Action</th>
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
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
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
                        data: 'courier_name'
                    },
                    {
                        data: 'note'
                    },
                    {
                        data: 'source'
                    },
                    {
                        data: 'cod'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'created_by',
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: true
                    },
                ]
            });

        });

        $(document).ready(function() {
            $('#data-table_filter').find("input").focus();
        });
    </script>
@endsection
