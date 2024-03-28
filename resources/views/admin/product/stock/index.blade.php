@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Product Stock History</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">product-stock-history</li>
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
                    <h4 class="m-0 mb-3">Product Stock History</h4>
                    <button id="total-sold-amount" class="btn btn-info">Sold Products Cost</button>
                    <button id="total-remaining-amount" class="btn btn-success ml-2">Remaining Products Cost</button>

                    <form class="mt-4" action="{{ route('stock.history.search') }}" method="get">
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
                                    <label>Reason</label>
                                    <select name="reason" class="select2 form-control @error('reason') is-invalid @enderror">
                                        <option value="0">Please Select a Reason (Optional)</option>
                                        <option value="Stockin" @if ($reason == 'Stockin') selected @endif>Stockin</option>
                                        <option value="Opening Stock" @if ($reason == 'Opening Stock') selected @endif>Opening Stock</option>
                                        <option value="Damage" @if ($reason == 'Damage') selected @endif>Damage</option>
                                        <option value="Sell (Online)" @if ($reason == 'Sell (Website)') selected @endif>Sell (Online)</option>
                                        <option value="Sell (Offline)" @if ($reason == 'Sell (Offline)') selected @endif>Sell (Offline)</option>
                                        <option value="Sell (Wholesale)" @if ($reason == 'Sell (Wholesale)') selected @endif>Sell (Wholesale)</option>
                                        <option value="Order Cancel" @if ($reason == 'Order Cancel') selected @endif>Order Cancel</option>
                                        <option value="Order Return" @if ($reason == 'Order Return') selected @endif>Order Return</option>
                                        <option value="Order Products Change" @if ($reason == 'Order Products Change') selected @endif>Order Products Change</option>
                                        <option value="Order Products Added" @if ($reason == 'Order Products Added') selected @endif>Order Products Added</option>
                                        <option value="Reject Product" @if ($reason == 'Reject Product') selected @endif>Reject Product</option>
                                        <option value="Display Center" @if ($reason == 'Display Center') selected @endif>Display Center</option>
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
                                    <label style="color: #fff;">.</label>
                                    <button type="submit" class="form-control btn btn-primary">Search</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <!-- /.card-header -->
                @include('admin.partials.page_search')
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Reference Code</th>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Production Cost</th>
                                <th>Price</th>
                                <th>Note</th>
                                <th>Remarks</th>
                                <th>Added By</th>
                                <th>Date</th>
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

        <!-- total sold amount Modal -->
        <div class="modal fade" id="total-sold-amount-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Total Sold Amount</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>

        <!-- total remaining amount Modal -->
        <div class="modal fade" id="total-remaining-amount-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Total Remaining Amount</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('#product_id').change(function() {
            var product_id = $(this).val();
            if (product_id == '') {
                product_id = -1;
            }
            var option = "";
            var url = "{{ url('/') }}";

            $.get(url + "/get-size/" + product_id, function(data) {
                data = JSON.parse(data);
                data.forEach(function(element) {
                    option += "<option value='" + element.id + "'>" + element.title + "</option>";
                });
                $('#size_id').html(option);
            });

        });
    </script>

    <script>
        $("#total-sold-amount").click(function() {

            url = "{{ route('stock.total.sold.amount') }}";
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    $('#total-sold-amount-modal').modal('show');
                    $('#total-sold-amount-modal .modal-body').html(response);
                }
            });
        });

        $("#total-remaining-amount").click(function() {

            url = "{{ route('stock.total.remaining.amount') }}";
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    $('#total-remaining-amount-modal').modal('show');
                    $('#total-remaining-amount-modal .modal-body').html(response);
                }
            });
        });
    </script>

    <script>
        // $(function () {
        //   $("#example1").DataTable({
        //     "responsive": true, "lengthChange": false, "autoWidth": false,
        //     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        //   }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        //   $('#example2').DataTable({
        //     "paging": true,
        //     "lengthChange": false,
        //     "searching": true,
        //     "ordering": true,
        //     "info": true,
        //     "autoWidth": false,
        //     "responsive": true,
        //   });
        // });
        // var table = new DataTable('#data-table');

        $(function() {

            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                columns: [{
                        data: 'id',
                    },
                    {
                        data: 'reference_code',
                    },
                    {
                        data: 'product',
                    },
                    {
                        data: 'size_id',
                    },
                    {
                        data: 'qty',
                    },
                    {
                        data: 'production_cost',
                    },
                    {
                        data: 'price',
                    },
                    {
                        data: 'note',
                    },
                    {
                        data: 'remarks',
                    },
                    {
                        data: 'created_by',
                    },
                    {
                        data: 'date',
                        orderable: false,
                        searchable: true,
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });

        });
    </script>
@endsection
