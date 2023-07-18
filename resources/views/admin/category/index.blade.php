@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Category List</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">category</li>
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
                @can('category.create')
                <a href="{{ route('category.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create Category</a>
                @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
	                  <tr>
	                    <th>S.N</th>
	                    <th>Title</th>
                      <th>Parent Category</th>
                      <th>Position</th>
                      <th>Image</th>
	                    <th>Action</th>
	                  </tr>
                  </thead>
                  <tbody>
	                  @foreach($categories as $category)
	                  	<tr>
		                    <td>{{ $loop->index + 1 }}</td>
		                    <td>{{ $category->title }}</td>
                        <td>{{ !is_null($category->parent) ? $category->parent->title : '' }}</td>
                        <td>{{ $category->position }}</td>
                        <td><img src="{{ asset('images/category/'.$category->image ) }}" width="100"></td>
		                    <td>
                          @can('category.edit')
		                    	<a href="{{ route('category.edit', $category->id) }}" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                          @endcan
                          @can('category.delete')
		                    	<a href="#deleteModal{{ $category->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                          @endcan
		                    </td>
		                </tr>
        <!-- Delete Category Modal -->
            <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete ?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" align="right">
                            <form action="{{ route('category.destroy', $category->id) }}" method="POST">
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
                  
                </table>
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