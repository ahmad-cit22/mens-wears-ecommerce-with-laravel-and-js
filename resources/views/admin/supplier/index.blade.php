@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Supplier List</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">supplier</li>
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
                <a href="#addModal" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus"></i> Create Supplier</a>
                <a href="#adddebtModal" class="btn btn-primary bg-purple" data-toggle="modal"><i class="fas fa-plus"></i> Add Supplier Debt</a>
                <a href="{{ route('supplierpayment.index') }}" class="btn btn-success"><i class="fas fa-eye"></i> Supplier Payments</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example1" class="table table-bordered table-hover">
                  <thead>
	                  <tr>
	                    <th>S.N</th>
	                    <th>Name</th>
	                    <th>Phone</th>
                      <th>Email</th>
                      <th>Balance</th>
                      <th>Action</th>
	                  </tr>
                  </thead>
                  <tbody>
	                  @foreach($suppliers as $supplier)
	                  	<tr>
		                    <td>{{ $loop->index + 1 }}</td>
		                    <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->productions->sum('amount') + $supplier->opening_balance - $supplier->payments->sum('amount') }}</td>
		                    <td>
		                    	<a href="#editModal{{ $supplier->id }}" class="btn btn-primary" data-toggle="modal" title="Edit"><i class="fas fa-edit"></i></a>
		                    </td>
		                </tr>

                    <!-- Edit size Modal -->
            <div class="modal fade" id="editModal{{ $supplier->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                              Edit - {{ $supplier->name }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                            @csrf
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Name *</label>
                                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" Value="{{ $supplier->name }}" required>
                                  @error('name')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Phone *</label>
                                  <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" Value="{{ $supplier->phone }}" required>
                                  @error('phone')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Email *</label>
                                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" Value="{{ $supplier->email }}" required>
                                  @error('email')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Address *</label>
                                  <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" Value="{{ $supplier->address }}" required>
                                  @error('address')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>
                              </div>
                                <div class="form-group">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                  <button class="btn btn-primary">Save Changes</button>
                                </div>
                              </div>
                            </div>
                          </form>

                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>

	                  @endforeach
                  </tbody>
                  <tfoot>
                  	<tr>
	                    <th>S.N</th>
	                    <th>Name</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Balance</th>
	                    <th>Action</th>
	                </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Add Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ route('supplier.store') }}" method="POST">
                    @csrf
                  <div class="modal-body">
                  
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Name *</label>
                          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" required>
                          @error('name')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Phone *</label>
                          <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="phone" required>
                          @error('phone')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Email *</label>
                          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="email" required>
                          @error('email')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Address *</label>
                          <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="address" required>
                          @error('address')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Opening Balance *</label>
                          <input type="number" name="opening_balance" class="form-control @error('opening_balance') is-invalid @enderror" placeholder="opening balance" required>
                          @error('opening_balance')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Add Modal -->
            <div class="modal fade" id="adddebtModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Supplier Debt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ route('supplier.debt') }}" method="POST">
                    @csrf
                  <div class="modal-body">
                  
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Supplier *</label>
                         <select class="select2 form-control @error('supplier_id') is-invalid @enderror" name="supplier_id" required>
                           <option value="">--- Select Supplier ---</option>
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
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Amount *</label>
                          <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Amount" required>
                          @error('amount')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Note *</label>
                          <input type="text" name="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note">
                          @error('note')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                  </div>
                  </form>
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