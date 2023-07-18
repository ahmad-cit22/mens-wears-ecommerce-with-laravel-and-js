@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Create New Asset</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">create-new-asset</li>
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
                  <a href="{{ route('asset.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Create New Asset</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example1" class="table table-bordered table-hover">
                  <thead>
	                  <tr>
	                    <th>S.N</th>
	                    <th>Name</th>
                      	<th>Bank</th>
                      	<th>Amount</th>
	                    <th>Reduction Amount</th>
                      	<th>Reduction Period</th>
                      	<th>Purchase Date</th>
                      	<th>Date</th>
	                    <th>Action</th>
	                  </tr>
                  </thead>
                  <tbody>
	                  @foreach($assets as $asset)
	                  	<tr>
		                    <td>{{ $loop->index + 1 }}</td>
		                    <td>{{ $asset->name }}</td>
                        	<td>{{ optional($asset->bank)->name }}</td>
                        	<td>
                        		Purchase Amount:{{ $asset->amount }}<br>
                        		<span class="badge badge-danger">Deducted Amount:{{ optional($asset->deductions)->sum('amount') }}</span>
                        		<br>
                        		<span class="badge badge-success">Current Value:{{ $asset->amount - optional($asset->deductions)->sum('amount') }}</span>
                        		
                        	</td>
		                    <td>{{ $asset->reduction_amount }}</td>
		                    <td>{{ count($asset->deductions) }}/{{ $asset->reduction_period }}</td>
                        	<td>{{ Carbon\Carbon::parse($asset->purchase_date)->format('d M Y') }}</td>
	                        <td>{{ Carbon\Carbon::parse($asset->created_at)->format('d M Y, g:iA') }}</td>
		                    <td>
		                    	<a href="{{ route('asset.edit', $asset->id) }}" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
		                    </td>
		                </tr>
	                  @endforeach
                  </tbody>
                  <tfoot>
                  	<tr>
                        <th>S.N</th>
	                    <th>Name</th>
                      	<th>Bank</th>
                      	<th>Amount</th>
	                    <th>Reduction Amount</th>
                      	<th>Reduction Period</th>
                      	<th>Purchase Date</th>
                      	<th>Date</th>
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