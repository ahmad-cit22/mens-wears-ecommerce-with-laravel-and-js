@extends('admin.layouts.master')
@php
  $total = 0;
  $liability = 0;
@endphp
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Owners Equity</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('index') }}" target="_blank">Home</a></li>
          <li class="breadcrumb-item active">owners-equity</li>
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
                <h3 class="text-center">GO BY FABRIFEST</h3><hr>
                <h3 class="text-center">OWNERS EQUITY FOR  {{ date('M') }}, {{ date('Y') }}</h3>
                
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>S.N</th>
                      <th>Name</th>
                      <th>Share Portion</th>
                      <th>Investment Amount</th>
                      <th>Withdraw Amount</th>
                      <th>Profit Amount</th>
                      <th>Balance</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($partners as $partner)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>{{ $partner->name }}</td>
                      <td>{{ $partner->share_portion }}%</td>
                      <td>{{ env('CURRENCY') }}{{ $partner->transactions->sum('credit') }}</td>
                      <td>{{ env('CURRENCY') }}{{ $partner->transactions->sum('debit') }}</td>
                      <td>{{ env('CURRENCY') }}{{ round((($other_income->sum('credit') + $order_amount - $production_cost - $expenses->sum('amount'))*($partner->share_portion/100)), 2) }}</td>
                      <td>{{ env('CURRENCY') }}{{ round(($partner->transactions)->sum('credit') - ($partner->transactions)->sum('debit') + (($other_income->sum('credit') + $order_amount - $production_cost - $expenses->sum('amount'))*($partner->share_portion/100)), 2) }}</td>
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
    //Date range picker
    $('#reservation').daterangepicker();
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
@endsection