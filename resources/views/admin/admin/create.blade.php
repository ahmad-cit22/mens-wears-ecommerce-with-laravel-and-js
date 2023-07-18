@extends('admin.layouts.master')
@section('content')
  <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Create User</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">user</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
	<div class="container-fluid">
		<form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
			@csrf
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label><b>Name *</b></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label><b>Email *</b></label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label><b>Phone *</b></label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" required>
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label><b>Address *</b></label>
                <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" required>
                @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label><b>Role *</b></label>
                <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                  <option value="">Please select a role</option>
                  @foreach($roles as $role)
                  <option value="{{ $role->id }}">{{ $role->name }}</option>
                  @endforeach
                </select>
                @error('type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            

            
            <div class="col-md-4">
              <div class="form-group">
                <label><b>Password *</b></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>
		</form>
	</div>
</section>
@endsection
