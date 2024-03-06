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
                    <form action="{{ route('bkash_panel.search') }}" method="get">
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
                                        <option value="0">OUT STANDING</option>
                                        <option value="1">PAID</option>
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
                                <a type="submit" href="{{ route('vat_entry.index') }}" class="form-control btn btn-primary">Clear Filter</a>
                            </div>

                        </div>
                    </form>

                    <div class="row mt-4">
                        <div class="col-md-8 m-auto">
                            {{-- <table id="" class="table table-bordered table-striped table-hover">
                                <tr>
                                    <td align="center" style="font-size: 18px !important;" colspan="4">
                                        @if ($bkash_business_id == '')
                                            Select a Number to Get the Data
                                        @else
                                            {{ $bkash_number->number . ' (' . $bkash_number->name . ')' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th width="25%">CASH IN</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ $cash_in }}</td>
                                    <th width="25%">CASH OUT</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ $cash_out }}</td>
                                </tr>
                                <tr>
                                    <th width="25%">SEND MONEY</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ $send_money }}</td>
                                    <th width="25%">PAYMENTS</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ $payments }}</td>
                                </tr>
                                <tr>
                                    <th width="25%">RECHARGE</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ $recharge }}</td>
                                    <th style="font-size: 20px !important; background: rgba(199, 129, 1, 0.176)" width="25%">CURRENT BALANCE</th>
                                    <td style="font-size: 20px !important; background: rgba(199, 129, 1, 0.176)" class="text-center font-weight-bold text-{{ $current_balance >= 0 ? 'success' : 'danger' }}">&#2547; {{ $current_balance }}</td>
                                </tr>

                            </table> --}}
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
                            <tr>
                                <th>S.N</th>
                                <th>Date of Sell</th>
                                <th>BIN No.</th>
                                <th>Memo No.</th>
                                <th>Sold Amount</th>
                                <th>VAT Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @php
                            $business = App\Models\Setting::find(1);
                        @endphp
                        <tbody>
                            @foreach ($vat_entries as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->date_of_sell }}</td>
                                    <td>{{ $business->bin_no }}</td>
                                    <td>{{ $item->order->code }}</td>
                                    <td>{{ $item->order->sold_amount() }}</td>
                                    <td>{{ $item->vat_amount }}</td>
                                    <td>
                                        @if ($item->is_paid == 1)
                                            <span class="badge badge-success">PAID</span>
                                        @else
                                            <span class="badge badge-warning">OUT STANDING</span>
                                        @endif
                                    </td>
                                    <td><a class="btn btn-primary btn-sm">Paid Done</a></td>
                                </tr>
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
