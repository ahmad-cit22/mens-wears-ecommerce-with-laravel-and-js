@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Supplier Payments</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">supplier-payments</li>
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
                
                <form action="{{ route('supplierpayment.search') }}" method="get">
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
                        <label>Supplier</label>
                        <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                          <option value="">Please Select Supplier</option>
                          @foreach($suppliers as $supplier)
                          <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                          @endforeach
                        </select>
                        @error('supplier_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Bank</label>
                        <select name="bank_id" class="form-control @error('bank_id') is-invalid @enderror">
                          <option value="">Please Select bank</option>
                          @foreach($banks as $bank)
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
                    <div class="col-md-3">
                      <div class="form-group">
                        <label style="color: #fff;">.</label>
                        <button type="submit" class="form-control btn  btn-primary">Search</button>
                      </div>
                    </div>
                  </div>
                </form>
              <hr>
              <a href="#create-expense" class="btn btn-primary bg-purple"  data-toggle="modal">Make Supplier Payment</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="data-table" class="table table-bordered table-hover">
                  <thead>
	                  <tr>
	                    <th>S.N</th>
                      <th>Supplier</th>
	                    <th>Bank</th>
                      <th>Amount</th>
                      <th>Payment Date</th>
                      <th>Note</th>
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
<!-- Modal -->
<div class="modal fade" id="create-expense" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Make Supplier Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('supplierpayment.store') }}" method="POST">
          @csrf
          <div class="row">
            
            <div class="col-md-6">
              <div class="form-group">
                <label>Supplier*</label>
                <select class="select2 form-control @error('from_id') is-invalid @enderror" name="supplier_id" required>
                  <option value="">Please select supplier</option>
                  @foreach(App\Models\Supplier::orderBy('name', 'ASC')->get() as $supplier)
                  <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                  @endforeach
                </select>
                @error('supplier_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Bank*</label>
                <select class="select2 form-control @error('bank_id') is-invalid @enderror" name="bank_id" required>
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
            <div class="col-md-12">
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
            <div class="col-md-12">
              <div class="form-group">
                <label>Date</label>
                <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ date('Y-m-d') }}">
                @error('payment_date')
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


<script type="text/javascript">
  $(function () {
    
    var table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        columns: [
            {data: 'id'},
            {data: 'supplier'},
            {data: 'bank'},
            {data: 'amount'},
            {data: 'payment_date'},
            {data: 'note'},
            {data: 'date', orderable: false, searchable: true},
        ]
    });
    
  });
</script>
@endsection