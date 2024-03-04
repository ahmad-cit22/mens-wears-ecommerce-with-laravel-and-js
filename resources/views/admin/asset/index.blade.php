@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create New Asset</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">create-new-asset</li>
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
                    <a href="{{ route('asset.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Create New Asset</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Bank</th>
                                <th>Purchase Amount</th>
                                <th>Depre. Value</th>
                                <th>Total Depre.</th>
                                <th>Estim. Months</th>
                                <th>Passing Months</th>
                                <th>Net Value</th>
                                <th>Depre. Date</th>
                                <th width="14%">Note</th>
                                <th>Purchase Date</th>
                                <th>Last Depre.</th>
                                <th>Action</th>
                                <th>Disposal</th>
                                <th>Gain/Loss</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assets as $asset)
                                @php
                                    $fromDate = Carbon\Carbon::parse($asset->purchase_date);
                                    $toDate = Carbon\Carbon::today();

                                    $passing_months = $toDate->diffInMonths($fromDate);
                                    $net_value = $asset->amount - optional($asset->deductions)->sum('amount');

                                    $month = $asset->deductions->first() ? Carbon\Carbon::parse($asset->deductions->first()->created_at)->format('M Y') : '--';
                                @endphp
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $asset->name }}
                                        @if ($asset->disposal_amount)
                                            <span class="badge badge-primary">Disposed</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($asset->bank)->name }}</td>
                                    <td>{{ $asset->amount }}</td>
                                    <td>&#2547; {{ $asset->depreciation_value }}</td>
                                    <td>&#2547; {{ optional($asset->deductions)->sum('amount') ?? '--' }}</td>
                                    <td>{{ $asset->estimated_life }}</td>
                                    <td>{{ $passing_months }}</td>
                                    <td>&#2547; {{ $net_value }}</td>
                                    <td>{{ $asset->depreciation_date }}</td>
                                    <td>{{ $asset->note ?? '--' }}</td>
                                    <td>{{ Carbon\Carbon::parse($asset->purchase_date)->format('d M Y, g:iA') }}</td>
                                    <td>{{ $month }}</td>
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('asset.edit', $asset->id) }}" class="btn btn-primary btn-sm mt-2" title="Edit"><i class="fas fa-edit"></i></a>
                                            @if ($net_value > 0 && !$asset->disposal_amount)
                                                <a href="#deduct-now{{ $asset->id }}" class="ml-1 btn btn-info btn-sm mt-2" data-toggle="modal" title="Depreciate Now"><i class="fas fa-arrow-down"></i></a>
                                                <a href="#dispose{{ $asset->id }}" class="ml-1 btn btn-success btn-sm mt-2" data-toggle="modal" title="Dispose"><i class="fas fa-arrow-up"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>&#2547; {{ $asset->disposal_amount ?? '--' }}</td>
                                    <td>
                                        @if ($asset->disposal_amount)
                                            @if ($asset->disposal_amount > $net_value)
                                                <span class="text-success">Profit: &#2547; {{ $asset->disposal_amount - $net_value }}</span>
                                            @else
                                                <span class="text-danger">Loss: &#2547; {{ $net_value - $asset->disposal_amount }}</span>
                                            @endif
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>

                                @if ($net_value > 0)
                                    <!-- deduct_now Modal -->
                                    <div class="modal fade" id="deduct-now{{ $asset->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Depreciate Now - {{ $asset->name }} ({{ date('M Y') }})</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('asset.deduct.now', $asset->id) }}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label>Depreciation Value</label>
                                                                    <input type="number" name="depreciation_value" class="form-control @error('depreciation_value') is-invalid @enderror" value="{{ $asset->depreciation_value }}" max="{{ $net_value }}" placeholder="Depreciation Value">
                                                                    @error('depreciation_value')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary ml-1 mr-2">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- dispose Modal -->
                                    <div class="modal fade" id="dispose{{ $asset->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Dispose Asset - {{ $asset->name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('asset.dispose', $asset->id) }}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label>Disposal Amount</label>
                                                                    <input type="number" name="disposal_amount" class="form-control @error('disposal_amount') is-invalid @enderror" value="{{ $asset->disposal_amount }}" placeholder="Disposal Amount" required>
                                                                    @error('disposal_amount')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label>Bank*</label>
                                                                    <select name="bank_id" class="select2 form-control @error('bank_id') is-invalid @enderror" required>
                                                                        <option value="">PLease select bank</option>
                                                                        @foreach ($banks as $bank)
                                                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('bank_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label>Note</label>
                                                                    <input type="text" name="note" class="form-control @error('note') is-invalid @enderror" placeholder="Add Note">
                                                                    @error('note')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary ml-1 mr-2">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Bank</th>
                                <th>Purchase Amount</th>
                                <th>Depreciation Value</th>
                                <th>Total Depreciated</th>
                                <th>Estimated Months</th>
                                <th>Passing Months</th>
                                <th>Net Value</th>
                                <th>Depre. Date</th>
                                <th width="14%">Note</th>
                                <th>Purchase Date</th>
                                <th>Last Depre.</th>
                                <th>Action</th>
                                <th>Disposal</th>
                                <th>Gain/Loss</th>
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
                // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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
                    },

                    'print',
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endsection
