@extends('admin.layouts.master')
@section('content')
  <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Product Bulk Upload</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" target="_blank">Home</a></li>
          <li class="breadcrumb-item active">Product Bulk Upload</li>
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
	        <a href="{{ asset('csv/product.csv') }}" class="btn btn-success"><i class="fas fa-download"></i> Download CSV Format</a>
	    </div>
		<div class="card-body">
			<form action="{{ route('product.bulk.store') }}" method="POST" enctype="multipart/form-data">
		      @csrf
			    <div class="form-group row">
				    <label class="col-sm-2 col-form-label"><b>CSV File *</b></label>
				    <div class="col-sm-10">
				      <input type="file" name="csv" class="form-control @error('csv') is-invalid @enderror" required>
			            @error('csv')
			                <span class="invalid-feedback" role="alert">
			                    <strong>{{ $message }}</strong>
			                </span>
			            @enderror
				    </div>
				</div>
				
		      <div class="form-group">
		        <button type="submit" class="btn btn-primary">Save</button>
		      </div>
		    </form>
		    <hr>
		    <div>
		    	<h3>Upload Product Images</h3>
		    	<form action="{{ route('product.bulk.upload.image') }}" method="POST" enctype="multipart/form-data">
			      @csrf
				    <div class="form-group row">
					    <label class="col-sm-2 col-form-label"><b>Images *</b></label>
					    <div class="col-sm-10">
					      <input type="file" name="image[]" class="form-control @error('image') is-invalid @enderror" multiple required>
				            @error('image')
				                <span class="invalid-feedback" role="alert">
				                    <strong>{{ $message }}</strong>
				                </span>
				            @enderror
					    </div>
					</div>
					
			      <div class="form-group">
			        <button type="submit" class="btn btn-primary">Save</button>
			      </div>
			    </form>
		    </div>
		</div>	
	</div>
  </div>
</section>
@endsection


