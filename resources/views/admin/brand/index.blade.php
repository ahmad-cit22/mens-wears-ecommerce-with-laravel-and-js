@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Brand List</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">brand</li>
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
                @can('brand.create')
                <a href="#addModal" class="btn btn-primary" data-toggle="modal"><i class="fas fa-plus"></i> Create Brand</a>
                @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
	                  <tr>
	                    <th>S.N</th>
	                    <th>Title</th>
                      <th>Image</th>
	                    <th>Action</th>
	                  </tr>
                  </thead>
                  <tbody>
	                  @foreach($brands as $brand)
	                  	<tr>
		                    <td>{{ $loop->index + 1 }}</td>
		                    <td>{{ $brand->title }}</td>
                        <td>
                          <img src="{{ asset('images/brand/'. ($brand->image !=  Null ? $brand->image : 'default.png')) }}" width="100">
                        </td>
		                    <td>
                          @can('brand.edit')
		                    	<a href="#editModal{{ $brand->id }}" class="btn btn-primary" data-toggle="modal" title="Edit"><i class="fas fa-edit"></i></a>
                          @endcan
                          @can('brand.delete')
		                    	<a href="#deleteModal{{ $brand->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                          @endcan
		                    </td>
		                </tr>

                    <!-- Edit brand Modal -->
            <div class="modal fade" id="editModal{{ $brand->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                              Edit - {{ $brand->title }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>brand Title *</label>
                                  <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" Value="{{ $brand->title }}" required>
                                  @error('title')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>
                              </div> 
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Brand Image *</label>
                                  <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" Value="{{ $brand->image }}">
                                  @error('image')
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
        <!-- Delete brand Modal -->
            <div class="modal fade" id="deleteModal{{ $brand->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete ?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" align="right">
                            <form action="{{ route('brand.destroy', $brand->id) }}" method="POST">
                                @csrf
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Permanent Delete</button>
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
	                    <th>Title</th>
                      <th>Image</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Create Brand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                  <div class="modal-body">
                  
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Brand Title *</label>
                          <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Title" required>
                          @error('title')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Brand Image *</label>
                          <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" placeholder="image">
                          @error('image')
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