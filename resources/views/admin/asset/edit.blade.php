@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Edit Asset</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">edit-asset</li>
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
                  
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="{{ route('asset.update', $asset->id) }}" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Name*</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $asset->name }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Bank*</label>
                        <select name="bank_id" class="select2 form-control @error('bank_id') is-invalid @enderror" disabled>
                          <option value="">PLease select bank</option>
                          @foreach($banks as $bank)
                          <option value="{{ $bank->id }}" {{ $asset->bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                          @endforeach
                        </select>
                        @error('bank_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Amount*</label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ $asset->amount }}" disabled>
                        @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Reduction Amount*</label>
                        <input type="number" name="reduction_amount" class="form-control @error('reduction_amount') is-invalid @enderror" value="{{ $asset->reduction_amount }}" required>
                        @error('reduction_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Reduction Period*(in month)</label>
                        <input type="number" name="reduction_period" class="form-control @error('reduction_period') is-invalid @enderror" value="{{ $asset->reduction_period }}" required>
                        @error('reduction_period')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Depreciation Value*</label>
                        <input type="number" name="depreciation_value" class="form-control @error('depreciation_value') is-invalid @enderror" value="{{ $asset->depreciation_value }}" required>
                        @error('depreciation_value')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Purchase Date*</label>
                        <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ $asset->purchase_date }}" required>
                        @error('purchase_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Note</label>
                        <input type="text" name="note" class="form-control @error('note') is-invalid @enderror" value="{{ $asset->note }}">
                        @error('note')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
	</div>
</section>
@endsection

@section('scripts')
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
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