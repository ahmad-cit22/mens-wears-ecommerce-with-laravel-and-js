@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Income Statement</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}" target="_blank">Home</a></li>
                        <li class="breadcrumb-item active">report-income-statement</li>
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
                    <div class="hidden-print">
                        <form action="{{ route('report.incomestatement.search') }}" method="get">
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
                                        <label style="color: #fff;">.</label>
                                        <button type="submit" class="form-control btn  btn-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                    </div>
                    <h3 class="text-center">GO BY FABRIFEST - {{ $vendor->name }}</h3>
                    <hr>
                    @if (Route::currentRouteName() == 'report.incomestatement')
                        <h3 class="text-center">INCOME STATEMENT FOR {{ date('M') }}, {{ date('Y') }}</h3>
                    @else
                        <h3 class="text-center">INCOME STATEMENT</h3>
                    @endif

                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">

                    <table id="" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sells</td>
                                <td class="text-success font-weight-bold">{{ env('CURRENCY') }}{{ round($retail_order_amount) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Total VAT</td>
                                <td class="text-danger font-weight-bold">{{ env('CURRENCY') }}{{ round($vat_amount) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Cost of good sold</td>
                                <td class="text-danger font-weight-bold">{{ env('CURRENCY') }}{{ round($retail_production_cost, 2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Gross Profit</th>
                                <th></th>
                                <th>{{ env('CURRENCY') }}{{ round($order_amount - $production_cost - $vat_amount) }}</th>
                            </tr>
                            <tr>
                                <th>Others Income</th>
                                <th></th>
                                <th>{{ env('CURRENCY') }}{{ $other_income->sum('credit') }}</th>
                            </tr>
                            <tr>
                                <th>Total Profit</th>
                                <th></th>
                                <th>{{ env('CURRENCY') }}{{ round($other_income->sum('credit') + $order_amount - $production_cost - $vat_amount) }}</th>
                            </tr>
                            <tr>
                                <th colspan="3">EXPENSE</th>
                            </tr>
                            {{-- <tr>{{ $expenses->sum('amount') }}</tr> --}}
                            @foreach ($expense_types as $type)
                                @php
                                    $sum = 0;
                                    $type_name = '';
                                @endphp
                                @foreach ($expenses as $expense)
                                    @if ($expense->expense_id == $type->id)
                                        @php
                                            $sum += $expense->amount;
                                            $type_name = $expense->expense->type;
                                        @endphp
                                    @endif
                                @endforeach
                                @if ($sum > 0)
                                    <tr>
                                        <td>{{ $type_name }}</td>
                                        <td class="text-danger font-weight-bold">{{ env('CURRENCY') }}{{ $sum }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <th>Total Expense</th>
                                <th></th>
                                <th>{{ env('CURRENCY') }}{{ $expenses->sum('amount') }}</th>
                            </tr>
                            <tr>
                                @php
                                    $result = $other_income->sum('credit') + $order_amount - $production_cost - $expenses->sum('amount') - $vat_amount;
                                @endphp
                                <th>Net {{ $result >= 0 ? 'Profit' : 'Loss' }}</th>
                                <th></th>
                                <th><span class="text-{{ $result >= 0 ? 'success' : 'danger' }}">{{ env('CURRENCY') }}{{ round($result) }}</span></th>
                            </tr>
                            <tr>
                                <th>{{ $result >= 0 ? 'Profit' : 'Loss' }} Share to Main Business</th>
                                <th></th>
                                <th><span class="text-{{ $result >= 0 ? 'danger' : 'success' }}">{{ env('CURRENCY') }}{{ round(abs($result * ($vendor->profit_percentage / 100))) }}</span></th>
                            </tr>
                            <tr>
                                <th>Final {{ $result >= 0 ? 'Profit' : 'Loss' }}</th>
                                <th></th>
                                <th><span class="text-{{ $result >= 0 ? 'success' : 'danger' }}">{{ env('CURRENCY') }}{{ round($result) - round($result * ($vendor->profit_percentage / 100)) }}</span></th>
                            </tr>
                        </tbody>
                        <tfoot>

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
        //Date range picker
        $('#reservation').daterangepicker();
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
