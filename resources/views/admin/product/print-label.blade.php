@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Print Product Label</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">product-label</li>
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
                @can('product.edit')
                <form action="{{ route('product.printlabel.result') }}" method="GET">
                  @csrf
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Product*</label>
                        <select class="select2 form-control  @error('product_id') is-invalid @enderror" name="product_id" id="product_id" required>
                          <option value="">---- Select ----</option>
                          @foreach($products as $product)
                          <option value="{{ $product->id }}">{{ $product->title }}</option>
                          @endforeach
                        </select>
                        @error('product_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Size*</label>
                        <select class="select2 form-control" name="size_id" id="size_id">
                          
                        </select>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Qty*</label>
                        <input type="number" name="qty" class="form-control  @error('qty') is-invalid @enderror" required>
                        @error('qty')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Generate Labels</button>
                      </div>
                    </div>

                  </div>
                </form>
                @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <div class="row">
                  
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
    $('#product_id').change(function(){
        var product_id = $(this).val();
        if (product_id == ''){
            product_id = -1;
        }
        var option = "";
        var url = "{{ url('/') }}";

        $.get( url + "/get-size/"+product_id, function( data ) {
            data = JSON.parse(data);
            data.forEach(function (element) {
                option += "<option value='"+ element.id +"'>"+ element.title + "</option>";
            });
            $('#size_id').html(option);
        });

    });
  </script>
@endsection