@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">{{ __('app.roleList') }}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('app.menu.home') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.roleList') }}</li>
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
                @can('role.create')
                  <a href="{{ route('role.create') }}" class="btn btn-primary">{{ __('app.action.create') }} <i class="fas fa-plus"></i></a>
                @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
	                  <tr>
	                    <th>S.N</th>
	                    <th>Role</th>
	                    <th>Action</th>
	                  </tr>
                  </thead>
                  <tbody>
	                  @foreach($roles as $role)
	                  	<tr>
		                    <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $role->name }}</td>
		                    <td>
                          @can('role.edit')
		                    	<a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                          @endcan
                          @if($role->is_default == 0)
                          @can('role.delete')
		                    	<a href="#deleteModal{{ $role->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                          @endcan
                          @endif
		                    </td>
		                </tr>
        <!-- role Modal -->
            <div class="modal fade" id="deleteModal{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete ?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" align="right">
                            <form action="{{ route('role.destroy', $role->id) }}" method="POST">
                                {{ csrf_field() }}
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
                      <th>Role</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
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