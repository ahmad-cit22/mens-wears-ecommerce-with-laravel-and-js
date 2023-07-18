@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Sell Return</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">sell-return</li>
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
              <form action="{{ route('sell.search') }}" method="get">
                  @csrf
                  <div class="row">
                    
                    <div class="col-md-4">
                      <div class="form-group">
                        <label style="color: #fff;">.</label>
                        <button type="submit" class="form-control btn  btn-primary">Search</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example1" class="table table-bordered table-hover">
                  <thead>
	                <tr>
	                    <th>S.N</th>
                      	<th>Code</th>
	                    <th>Order Code</th>
                      	<th>Date</th>
	                </tr>
                  </thead>
                  <tbody>
	                  @foreach($sell_returns as $return)
	                  <tr>
	                  	<td>{{ $loop->index }}</td>
	                  	<td>{{ $return->code }}</td>
	                  	<td>{{ $return->order_code }}</td>
	                  	<td>{{ Carbon\Carbon::parse($return->created_at)->format('d M, Y g:iA') }}</td>
	                  </tr>
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
    
  });
</script>

@endsection