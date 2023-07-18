@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Trendong Settings</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">trending-settings</li>
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
               <form action="{{ route('trending.update', $trending->id) }}" method="POST" enctype="multipart/form-data">
                 @csrf
                 <div class="row">
                   
                   <div class="col-md-12">
                     <div class="form-group">
                       <label>Image</label>
                       <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                       @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <img src="{{ asset('images/website/'. $trending->image) }}" width="200">
                     </div>
                   </div>

                   <div class="col-md-12">
                     <div class="form-group">
                        <label>Trending Option*</label>
                        <select name="type" class="select2 form-control @error('type') is-invalid @enderror">
                          <option value="single_product" {{ $trending->type == 'single_product' ? 'selected' : '' }}>Single Product</option>
                          <option value="group_product" {{ $trending->type == 'group_product' ? 'selected' : '' }}>Group of product</option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>
                   <div class="col-md-12">
                     <div class="form-group">
                       <label>Link*</label>
                       <input type="text" name="link" value="{{ $trending->link }}" class="form-control @error('link') is-invalid @enderror">
                       @error('link')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-12">
                     <div class="form-group">
                       <button class="btn btn-primary">Save Changes</button>
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
    
  });
</script>
@endsection