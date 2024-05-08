@extends('admin.layouts.master')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Membership Cards</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Membership Panel</li>
                        <li class="breadcrumb-item active">Membership Cards</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="container-fluid">
                <a href="#create-card" class="btn btn-primary" data-toggle="modal" title="Create"><i class="fas fa-plus mr-2"></i>Create New Card</a>
            </div>
            <div class="card">

                <!-- /.card-header -->
                @include('admin.partials.page_search')
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>S.N</th>
                                <th>Card Status</th>
                                <th>Discount Rate (%)</th>
                                <th>Minimum Purchase</th>
                                <th>Point Percentage (%)</th>
                                <th>Minimum Points</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cards as $card)
                                <tr class="text-center">
                                    <td>{{ $card->id }}</td>
                                    <td>{{ $card->card_status }}</td>
                                    <td>{{ $card->discount_rate }}</td>
                                    <td>&#2547; {{ $card->min_purchase ?? '--' }}</td>
                                    <td>{{ $card->point_percentage }}</td>
                                    <td>{{ $card->min_point }}</td>
                                    @can('membership.update')
                                        <td>
                                            <a href="#edit-card{{ $card->id }}" class="btn btn-primary btn-sm" data-toggle="modal" title="Edit"><i class="fas fa-edit"></i></a>
                                        </td>
                                    @endcan
                                </tr>

                                @can('membership.update')
                                    <!-- edit Modal -->
                                    <div class="modal fade" id="edit-card{{ $card->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Card - {{ $card->id }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('membership.card.update', $card->id) }}" method="POST">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="card_status">Card Status</label>
                                                            <input type="text" name="card_status" placeholder="Enter Card Status" class="form-control" value="{{ $card->card_status }}" required>

                                                            @error('card_status')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="discount_rate">Discount Rate (%)</label>
                                                            <input type="number" name="discount_rate" placeholder="Enter Discount Rate" class="form-control" value="{{ $card->discount_rate }}" required>

                                                            @error('discount_rate')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="min_purchase">Minimum Purchase</label>
                                                            <input type="number" name="min_purchase" placeholder="Enter Minimum Purchase" class="form-control" value="{{ $card->min_purchase }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="point_percentage">Point Percentage for Discount</label>
                                                            <input type="number" name="point_percentage" placeholder="Enter Point Percentage" class="form-control" value="{{ $card->point_percentage }}" required>

                                                            @error('point_percentage')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="min_point">Minimum Points for Discount</label>
                                                            <input type="number" name="min_point" placeholder="Enter Minimum Points" class="form-control" value="{{ $card->min_point }}" required>

                                                            @error('min_point')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="row justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary ml-1 mr-2">Confirm</button>
                                                        </div>
                                                    </form>
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

            <!-- create Modal -->
            <div class="modal fade" id="create-card" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Create New Card</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('membership.card.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="card_status">Card Status</label>
                                    <input type="text" name="card_status" placeholder="Enter Card Status" class="form-control" required>

                                    @error('card_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="discount_rate">Discount Rate (%)</label>
                                    <input type="number" name="discount_rate" placeholder="Enter Discount Rate" class="form-control" required>

                                    @error('discount_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="min_purchase">Minimum Purchase</label>
                                    <input type="number" name="min_purchase" placeholder="Enter Minimum Purchase" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="point_percentage">Point Percentage for Discount</label>
                                    <input type="number" name="point_percentage" placeholder="Enter Point Percentage" class="form-control" required>

                                    @error('point_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="min_point">Minimum Points for Discount</label>
                                    <input type="number" name="min_point" placeholder="Enter Minimum Points" class="form-control" required>

                                    @error('min_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row justify-content-end">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary ml-1 mr-2">Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
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
                        // customize: function(doc) {
                        //     doc.defaultStyle.font = "nikosh";
                        // }
                    },

                    'print',
                ]
            }).buttons().container().appendTo('#data-table_wrapper .col-md-6:eq(0)');

        });
    </script>

    <script>
        // var isShown = false;
        $('#show-add-form-btn').click(function() {
            $('#add-form').show();
            $('#hide-add-form-btn').show();
            $('#show-add-form-btn').hide();
            // var isShown = true;
        })

        $('#hide-add-form-btn').click(function() {
            $('#add-form').hide();
            $('#hide-add-form-btn').hide();
            $('#show-add-form-btn').show();
            // var isShown = false;
        })
    </script>
@endsection
