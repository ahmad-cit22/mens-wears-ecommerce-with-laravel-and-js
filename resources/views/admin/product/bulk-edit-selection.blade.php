@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Product</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">product</li>
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
              <div class="card-body table-responsive">
                <form action="{{ route('product.bulk.edit') }}" method="GET">
                      @csrf
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
	                  <tr>
	                    <th>S.N</th>
	                    <th>Title</th>
                      <th>Weight</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Discount Price</th>
	                  </tr>
                  </thead>
                  <tbody>
                    
                    @php
                    $i = 1
                    @endphp
	                  @foreach($products as $product)
                    
	                  	<tr>
		                    <td>{{ $i }}</td>
		                    <td><label>
                          <input type="checkbox" name="products[]" value="{{ $product->id }}"> {{ $product->title }} </label>
                        </td>
                        <td>
                          {{ $product->weight }} {{ $product->unit }}  
                        </td>
                        <td>
                          {{ $product->qty }}
                        </td>
                        <td>
                          {{ $product->price }} 
                        </td>
                        <td>
                          {{ $product->discount_price }}
                        </td>
		                </tr>
                    @php 
                    $i += 1;
                    @endphp
	                  @endforeach
                    <tr>
                      <td ><span class="d-none">{{ $i }}</span></td>
                      <td>
                        <button type="submit" class="btn btn-primary">Goto Edit</button>
                      </td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    
                    
                  </tbody>
                </table>
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
      "paging": false,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": false,
    });
  });
</script>
@endsection