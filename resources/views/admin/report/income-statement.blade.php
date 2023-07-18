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
                          <input type="date" name="date_from" class="form-control @error('date_from') is-invalid @enderror">
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
                          <input type="date" name="date_to" class="form-control @error('date_to') is-invalid @enderror">
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
                <h3 class="text-center">GO BY FABRIFEST</h3><hr>
                @if(Route::currentRouteName() == 'report.incomestatement')
                <h3 class="text-center">INCOME STATEMENT FOR  {{ date('M') }}, {{ date('Y') }}</h3>
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
                      <td>{{ env('CURRENCY') }}{{ round($order_amount, 2) }}</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Cost of good sold</td>
                      <td>{{ env('CURRENCY') }}{{ round($production_cost, 2) }}</td>
                      <td></td>
                    </tr>
                    <tr>
                      <th>Gross Profit</th>
                      <th></th>
                      <th>{{ env('CURRENCY') }}{{ round($order_amount - $production_cost, 2) }}</th>
                    </tr>
                    <tr>
                      <th>Others Income</th>
                      <th></th>
                      <th>{{ env('CURRENCY') }}{{ $other_income->sum('credit') }}</th>
                    </tr>
                    <tr>
                      <th>Total Profit</th>
                      <th></th>
                      <th>{{ env('CURRENCY') }}{{ round($other_income->sum('credit') + $order_amount - $production_cost, 2) }}</th>
                    </tr>
                    <tr>
                      <th colspan="3">EXPENSE</th>
                    </tr>
                    @foreach($expenses as $expense)
                    <tr>
                      <td>{{ $expense->expense->type }}</td>
                      <td>{{ env('CURRENCY') }}{{ $expense->amount }}</td>
                      <td></td>
                    </tr>
                    @endforeach
                    <tr>
                      <th>Total Expense</th>
                      <th></th>
                      <th>{{ env('CURRENCY') }}{{ $expenses->sum('amount') }}</th>
                    </tr>
                    <tr>
                      <th>Net {{ ($other_income->sum('credit') + $order_amount - $production_cost - $expenses->sum('amount')) >= 0? 'Profit': 'Loss' }}</th>
                      <th></th>
                      <th><span class="text-{{ ($other_income->sum('credit') + $order_amount - $production_cost - $expenses->sum('amount')) >= 0? 'success': 'danger' }}">{{ env('CURRENCY') }}{{ round($other_income->sum('credit') + $order_amount - $production_cost - $expenses->sum('amount'),2) }}</span></th>
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
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
@endsection