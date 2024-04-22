@extends('admin.layouts.master')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="m-0">Membershipship Card Details</h2>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Membership Panel</li>
                        <li class="breadcrumb-item active">Membershipship Card Details</li>
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
                    {{-- <form action="{{ route('vat_entry.search') }}" method="get">
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
                    </form> --}}

                    <div class="row mt-4">
                        <div class="col-md-8 m-auto">
                            <table id="" class="table table-bordered table-striped table-hover">
                                <tr style="font-size: 20px !important">
                                    {{-- <th colspan="3" width="25%"></th> --}}
                                    <th width="20%">Card Number</th>
                                    <td class="text-center font-weight-bold text-unpaid">{{ $member->card_number }}</td>
                                    <th class="">Phone Number</th>
                                    <td class="text-center font-weight-bold" style="display: flex; justify-content: center;">
                                        <select class="form-control select2" id="member-phone">
                                            @foreach ($members as $item)
                                                <option class="form-control" value="{{ $item->customer->phone }}" {{ $item->customer->phone == $member->customer->phone ? 'selected' : '' }}>{{ $item->customer->phone }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr style="font-size: 20px !important">
                                    <th width="20%">Card Status</th>
                                    @if ($member->membership_card_id == 1)
                                        <td class="text-center font-weight-bold text-info">{{ $member->card->card_status }}</td>
                                    @elseif ($member->membership_card_id == 2)
                                        <td class="text-center font-weight-bold text-primary">{{ $member->card->card_status }}</td>
                                    @else
                                        <td class="text-center font-weight-bold text-success">{{ $member->card->card_status }}</td>
                                    @endif
                                    <th width="20%">Discount Rate</th>
                                    <td class="text-center font-weight-bold text-success">{{ $member->card->discount_rate }}%</td>
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
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Registration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $member->customer->name }}</td>
                                <td>{{ $member->customer->phone }}</td>
                                <td>{{ $member->customer->district ? $member->customer->address . ', ' . $member->customer->district->name : $member->customer->address }}</td>
                                <td>{{ Carbon\Carbon::parse($member->created_at)->format('d M, Y') }}</td>
                            </tr>
                        </tbody>

                    </table>
                </div>

                <div class="row mt-2">
                    <div class="col-md-8 m-auto">
                        <table id="" class="table table-striped table-hover">
                            <tr class="text-center" style="font-size: 18px !important">
                                <th>Total Orders</th>
                                <th>Total Purchased</th>
                                <th>Point Use Turn</th>
                                <th>Total Used</th>
                                <th>Point Balance</th>
                                <th>Discount Rate</th>
                            </tr>
                            <tr class="text-center font-weight-bold" style="font-size: 18px !important">
                                <td class="text-success">{{ $member->customer->orders->count() }}</td>
                                <td class="text-success">&#2547; {{ $member->customer->orders->sum('price') }}</td>
                                <td class="text-primary">{{ $member->customer->orders->where('points_redeemed', '!=', null)->count() }}</td>
                                <td class="text-primary">{{ $member->customer->orders->where('points_redeemed', '!=', null)->sum('points_redeemed') }}</td>
                                <td class="text-primary">{{ $member->current_points }}</td>
                                <td class="text-secondary">{{ $member->card->discount_rate }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="card">
                <div class="card-body table-responsive">
                    <h4 class="mb-3">Purchase Records</h4>
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>S.N</th>
                                <th>Date</th>
                                <th>Memo Number</th>
                                <th>Purchase Amount</th>
                                <th>Discount Rate</th>
                                <th>Discount Amount</th>
                                <th>Point Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($member->customer->orders as $purchase)
                                <tr class="text-center">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ Carbon\Carbon::parse($purchase->created_at)->format('d M, Y') }}</td>
                                    <td>
                                        <a class="" href="{{ route('order.edit', $purchase->id) }}">{{ $purchase->code }}</a>
                                    </td>
                                    <td>&#2547; {{ $purchase->price }}</td>
                                    <td>{{ $purchase->discount_rate }}%</td>
                                    <td>&#2547; {{ $purchase->membership_discount }}</td>
                                    <td>{{ $purchase->points_redeemed ?? '--' }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
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

        // write ajax function that will run when member-phone select option is changed
        $('#member-phone').change(function() {
            var phone = $(this).val();

            var url = "{{ route('membership.get.member') }}";

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    phone: phone
                },
                success: function(data) {
                    // console.log(data);
                    let location = "{{ route('membership.edit', ':id') }}";
                    location = location.replace(':id', data);

                    window.location.href = location;
                    // console.log(location);
                }
            });
        });
    </script>
@endsection
