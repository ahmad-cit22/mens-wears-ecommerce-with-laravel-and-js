@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Transactions List</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">transactions-list</li>
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
                
                <form action="{{ route('partnertransaction.search') }}" method="get">
                  @csrf
                  <div class="row">
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Partner</label>
                        <select class="form-control @error('partner_id') is-invalid @enderror" name="partner_id">
                          <option value="">--- Select Partner ---</option>
                          @foreach($partners as $partner)
                          <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                          @endforeach
                        </select>
                        @error('partner_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label style="color: #fff;">.</label>
                        <button type="submit" class="form-control btn  btn-primary">Search</button>
                      </div>
                    </div>
                  </div>
                </form>
              <hr>
              <a href="#create-expense" class="btn btn-primary bg-purple"  data-toggle="modal">Create New Transaction</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example1" class="table table-bordered table-hover">
                  <thead>
	                  <tr>
	                    <th>S.N</th>
                        <th>Partner</th>
                        <th>Bank</th>
                      	<th>Credit</th>
	                    <th>Debit</th>
                      	<th>note</th>
                      	<th>Date</th>
                      	<th>Entry Date</th>
	                  </tr>
                  </thead>
                  <tbody>
	                  @foreach($transactions as $transaction)
	                  <tr>
	                  	<td>{{ $loop->index + 1 }}</td>
	                  	<td>{{ optional($transaction->partner)->name }}</td>
	                  	<td>{{ optional($transaction->bank)->name }}</td>
	                  	<td>{{ $transaction->credit }}</td>
	                  	<td>{{ $transaction->debit }}</td>
	                  	<td>{{ $transaction->note }}</td>
	                  	<td>{{ $transaction->date }}</td>
	                  	<td>{{ $transaction->created_at }}</td>
	                  </tr>
	                  @endforeach
                  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
<!-- Modal -->
<div class="modal fade" id="create-expense" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create New Transaction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('partnertransaction.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Partner*</label>
                <select class="select2 form-control @error('partner_id') is-invalid @enderror" name="partner_id" required>
                  <option value="">Please select partner</option>
                  @foreach($partners as $partner)
                  <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                  @endforeach
                </select>
                @error('partner_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Bank</label>
                <select class="form-control @error('bank_id') is-invalid @enderror" name="bank_id" required>
                  <option value="">Please select bank</option>
                  @foreach(App\Models\Bank::orderBy('name', 'ASC')->get() as $bank)
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
            <div class="col-md-6">
              <div class="form-group">
                <label>Type*</label>
                <select class="form-control @error('type') is-invalid @enderror" name="type" required>
                  <option value="">Transaction type</option>
                  <option value="deposit">New Invest</option>
                  <option value="withdraw">Withdraw</option>
                </select>
                @error('type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Amount*</label>
                <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" required>
                @error('amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Date*</label>
                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ date('Y-m-d') }}" required>
                @error('date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Note</label>
                <input type="text" name="note" class="form-control @error('note') is-invalid @enderror">
                @error('note')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
            
	</div>
</section>
@endsection

@section('scripts')

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "pageLength": 100, "autoWidth": false,
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