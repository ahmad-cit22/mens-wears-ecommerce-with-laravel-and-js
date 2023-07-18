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
        <h1 class="m-0">Balance Sheet</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('index') }}" target="_blank">Home</a></li>
          <li class="breadcrumb-item active">balance-sheet</li>
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
                <h3 class="text-center">BALANCE SHEET FOR  {{ date('M') }}, {{ date('Y') }}</h3>
                
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <div class="row">
                  <div class="col-md-5">
                    <table class="table table-bordered table-hover">
                      <tr>
                        <th colspan="2"><h4 class="text-center">ASSEET</h4></th>
                      </tr>
                      <tr>
                        <th>Account Title</th>
                        <th>Amount</th>
                      </tr>
                      <tr>
                        <th colspan="2">Current Asset</th>
                      </tr>
                      @foreach($banks as $bank)
                      @php
                      $total += $bank->transactions->sum('credit') - $bank->transactions->sum('debit');
                      @endphp
                      <tr>
                        <td>{{ $bank->name }}</td>
                        <td>{{ env('CURRENCY') }}{{ $bank->transactions->sum('credit') - $bank->transactions->sum('debit') }}</td>
                      </tr>
                      @endforeach
                      <tr>
                        <th colspan="2">Supplies</th>
                      </tr>
                      @foreach($accessories as $accessory)
                      @php
                      $total += $accessory->stock->sum('credit') - $accessory->stock->sum('debit');
                      @endphp
                      <tr>
                        <td>{{ $accessory->name }}</td>
                        <td>{{ env('CURRENCY') }}{{ $accessory->stock->sum('credit') - $accessory->stock->sum('debit') }}</td>
                      </tr>
                      @endforeach
                      <tr>
                        <th colspan="2">Fixed Asset</th>
                      </tr>
                      @foreach($assets as $asset)
                      @php
                      $total += $asset->amount - optional($asset->deductions)->sum('amount');
                      @endphp
                      <tr>
                        <td>{{ $asset->name }}</td>
                        <td>{{ env('CURRENCY') }}{{ $asset->amount - optional($asset->deductions)->sum('amount') }}</td>
                      </tr>
                      @endforeach
                      <tr>
                        <th><h5 class="text-center">Total Asset</h5></th>
                        <th>{{ env('CURRENCY') }}{{ $total }}</th>
                      </tr>
                    </table>
                  </div>
                  <div class="col-md-2 border-left border-right"></div>
                  <div class="col-md-5">
                    <table class="table table-bordered table-hover">
                      <tr>
                        <th colspan="2"><h4 class="text-center">LIABILITY</h4></th>
                      </tr>
                      <tr>
                        <th>Account Title</th>
                        <th>Amount</th>
                      </tr>
                      <tr>
                        <th colspan="2">
                          Suppliers
                        </th>
                      </tr>
                      @foreach($suppliers as $supplier)
                      <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ env('CURRENCY') }}{{ $supplier->productions->sum('amount') + $supplier->opening_balance - $supplier->payments->sum('amount') }}</td>
                      </tr>
                      @endforeach
                      <tr>
                        <th colspan="2">
                          Partners
                        </th>
                      </tr>
                      @foreach($partners as $partner)
                      <tr>
                        <td>{{ $partner->name }}</td>
                        <td>{{ env('CURRENCY') }}{{ round(($partner->transactions)->sum('credit') - ($partner->transactions)->sum('debit') + (($other_income->sum('credit') + $order_amount - $production_cost - $expenses->sum('amount'))*($partner->share_portion/100)), 2) }}</td>
                      </tr>
                      @endforeach
                    </table>
                  </div>
                </div>
                
                
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