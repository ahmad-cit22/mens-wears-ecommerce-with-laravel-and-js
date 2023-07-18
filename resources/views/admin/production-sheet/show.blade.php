@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Production Sheet</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">production-sheet</li>
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
                <a href="{{ route('production.recalculate', $production->id) }}" class="btn btn-primary bg-purple">Recalculate</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                	<div class="col-md-3">
                		<label>Product Code</label>
                		<p>{{ $production->product_code }}</p>
                	</div>
                	<div class="col-md-3">
                		<label>Category</label>
                		<p>{{ optional($production->category)->title }}</p>
                	</div>
                	<div class="col-md-2">
                		<label>Product Date</label>
                		<p>{{ Carbon\Carbon::parse($production->date)->format('d M Y') }}</p>
                	</div>
                	<div class="col-md-2">
                		<label>Output Units</label>
                		<p>{{ $production->output_units }}</p>
                	</div>
                	<div class="col-md-2">
                		<label>Unit Cost</label>
                		<p>{{ $production->unit_cost }}</p>
                	</div>
                </div>

                @if(!is_null($fabric))
                <div class="row">
                	<div class="col-md-12">
                		<hr>
                		<h5>Fabric</h5>
                		<hr>
                	</div>
                	<div class="col-md-4">
                		<label>Supplier</label>
                		<p>{{ optional($fabric->supplier)->name }}</p>
                	</div>
                	<div class="col-md-4">
                		<label>Qty</label>
                		<p>{{ $fabric->qty }}</p>
                	</div>
                	<div class="col-md-4">
                		<label>Amount</label>
                		<p>{{ $fabric->amount }}</p>
                	</div>
                </div>
                @else
                <form action="{{ route('production.supplier', $production->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h5>Fabric</h5>
                            <hr>
                        </div>
                        <input type="hidden" name="type" value="fabric">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Supplier</label>
                            <select class="select2 form-control  @error('supplier_id') is-invalid @enderror" name="supplier_id">
                              <option value="">---- Select ----</option>
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
                            <label>Qty</label>
                            <input type="text" name="qty" class="form-control  @error('qty') is-invalid @enderror">
                            @error('qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control  @error('amount') is-invalid @enderror">
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="color: #fff">.</label><br>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    
                </form>
                @endif
                @if(!is_null($contrast))
                <div class="row">
                	<div class="col-md-12">
                		<hr>
                		<h5>Contrast Fabric</h5>
                		<hr>
                	</div>
                	<div class="col-md-4">
                		<label>Supplier</label>
                		<p>{{ optional($contrast->supplier)->name }}</p>
                	</div>
                	<div class="col-md-4">
                		<label>Qty</label>
                		<p>{{ $contrast->qty }}</p>
                	</div>
                	<div class="col-md-4">
                		<label>Amount</label>
                		<p>{{ $contrast->amount }}</p>
                	</div>
                </div>
                @else
                <form action="{{ route('production.supplier', $production->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h5>Contrast Fabric</h5>
                            <hr>
                        </div>
                        <input type="hidden" name="type" value="contrast">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Supplier</label>
                            <select class="select2 form-control  @error('supplier_id') is-invalid @enderror" name="supplier_id">
                              <option value="">---- Select ----</option>
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
                            <label>Qty</label>
                            <input type="text" name="qty" class="form-control  @error('qty') is-invalid @enderror">
                            @error('qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control  @error('amount') is-invalid @enderror">
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="color: #fff">.</label><br>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    
                </form>
                @endif
                @if(!is_null($swing))
                <div class="row">
                	<div class="col-md-12">
                		<hr>
                		<h5>Swing Charge</h5>
                		<hr>
                	</div>
                	<div class="col-md-4">
                		<label>Supplier</label>
                		<p>{{ optional($swing->supplier)->name }}</p>
                	</div>
                	<div class="col-md-4">
                		<label>Qty</label>
                		<p>{{ $swing->qty }}</p>
                	</div>
                	<div class="col-md-4">
                		<label>Amount</label>
                		<p>{{ $swing->amount }}</p>
                	</div>
                </div>
                @else
                <form action="{{ route('production.supplier', $production->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h5>Swing Charge</h5>
                            <hr>
                        </div>
                        <input type="hidden" name="type" value="swing">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Supplier</label>
                            <select class="select2 form-control  @error('supplier_id') is-invalid @enderror" name="supplier_id">
                              <option value="">---- Select ----</option>
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
                            <label>Qty</label>
                            <input type="text" name="qty" class="form-control  @error('qty') is-invalid @enderror">
                            @error('qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control  @error('amount') is-invalid @enderror">
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="color: #fff">.</label><br>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    
                </form>
                @endif
                @if(!is_null($printing))
                <div class="row">
                	<div class="col-md-12">
                		<hr>
                		<h5>Printing Charge</h5>
                		<hr>
                	</div>
                	<div class="col-md-4">
                		<label>Supplier</label>
                		<p>{{ optional($printing->supplier)->name }}</p>
                	</div>
                	<div class="col-md-4">
                		<label>Qty</label>
                		<p>{{ $printing->qty }}</p>
                	</div>
                	<div class="col-md-4">
                		<label>Amount</label>
                		<p>{{ $printing->amount }}</p>
                	</div>
                </div>
                @else
                <form action="{{ route('production.supplier', $production->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h5>Printing Charge</h5>
                            <hr>
                        </div>
                        <input type="hidden" name="type" value="printing">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Supplier</label>
                            <select class="select2 form-control  @error('supplier_id') is-invalid @enderror" name="supplier_id">
                              <option value="">---- Select ----</option>
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
                            <label>Qty</label>
                            <input type="text" name="qty" class="form-control  @error('qty') is-invalid @enderror">
                            @error('qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control  @error('amount') is-invalid @enderror">
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="color: #fff">.</label><br>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    
                </form>
                @endif
                <div class="row">
                	<div class="col-md-6">
                		<hr>
                        <div align="right">
                            <a href="#add_accessory" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus"></i></a>
                        </div>
                		<h5>Accessories</h5>
                		<hr>
                		<table class="table">
	                		<tr>
	                			<th>S.N</th>
	                			<th>Accessory</th>
	                			<th>Amount</th>
	                		</tr>
	                		@foreach($production->accessories as $accessory)
	                		<tr>
	                			<td>{{ $loop->index + 1 }}</td>
	                			<td>{{ $accessory->accessory->name }}</td>
	                			<td>{{ $accessory->amount }}</td>
	                		</tr>
	                		@endforeach
                		</table>
                		
                		<hr>
                	</div>
                	<div class="col-md-6">
                		<hr>
                        <div align="right">
                            <a href="#add_cost" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus"></i></a>
                        </div>
                		<h5>Other Costs</h5>
                		<hr>
                		<table class="table">
	                		<tr>
	                			<th>S.N</th>
	                			<th>Name</th>
	                			<th>Amount</th>
	                		</tr>
	                		@foreach($production->costs as $cost)
	                		<tr>
	                			<td>{{ $loop->index + 1 }}</td>
	                			<td>{{ $cost->name }}</td>
	                			<td>{{ $cost->amount }}</td>
	                		</tr>
	                		@endforeach
                		</table>
                	</div>
                </div>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

        <!-- Modal -->
        <div class="modal fade" id="add_accessory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Accessories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{ route('production.accessory', $production->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Accessory*</label>
                                <select class="select2 form-control" name="accessory_id" required>
                                    <option value="">--- Select Accessory ---</option>
                                    @foreach(App\Models\Accessory::orderBy('id', 'DESC')->get() as $accessory)
                                    <option value="{{ $accessory->id }}">{{ $accessory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount*</label>
                                <input type="number" name="amount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
              </div>
              
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="add_cost" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Production Cost</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{ route('production.cost', $production->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Item*</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount*</label>
                                <input type="number" name="amount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
              </div>
              
            </div>
          </div>
        </div>
            
	</div>
</section>
@endsection

@section('scripts')
  
@endsection