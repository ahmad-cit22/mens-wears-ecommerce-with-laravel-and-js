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
                                <th>Disposal</th>
                                <th>Gain/Loss</th>
                                <th>Purchase Date</th>
                                <th>Last Depre.</th>
                                <th>Action</th>
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
                                    <td>{{ $asset->name }}</td>
                                    <td>{{ optional($asset->bank)->name }}</td>
                                    <td>{{ $asset->amount }}</td>
                                    <td>{{ $asset->depreciation_value }}</td>
                                    <td>{{ optional($asset->deductions)->sum('amount') ?? '--' }}</td>
                                    <td>{{ $asset->estimated_life }}</td>
                                    <td>{{ $passing_months }}</td>
                                    <td>{{ $net_value }}</td>
                                    <td>{{ $asset->depreciation_date }}</td>
                                    <td>{{ $asset->note ?? '--' }}</td>
                                    <td>{{ $asset->disposal_amount ?? '--' }}</td>
                                    <td>
                                        @if ($asset->disposal_amount)
                                            @if ($asset->disposal_amount > $net_value)
                                                <span class="text-success">Profit: {{ $asset->disposal_amount - $net_value }}</span>
                                            @else
                                                <span class="text-danger">Loss: {{ $net_value - $asset->disposal_amount }}</span>
                                            @endif
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($asset->purchase_date)->format('d M Y, g:iA') }}</td>
                                    <td>{{ $month }}</td>
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('asset.edit', $asset->id) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                            @if ($net_value > 0)
                                                <a href="#deduct-now{{ $asset->id }}" class="ml-1 btn btn-info btn-sm" data-toggle="modal" title="Depreciate Now"><i class="fas fa-arrow-down"></i></a>
                                            @endif
                                        </div>
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
                                                                    <input type="number" name="depreciation_value" class="form-control @error('depreciation_value') is-invalid @enderror" value="{{ $asset->depreciation_value }}" max="{{ $net_value }}">
                                                                    @error('depreciation_value')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
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
                                <th>Disposal</th>
                                <th>Gain/Loss</th>
                                <th>Purchase Date</th>
                                <th>Last Depre.</th>
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
