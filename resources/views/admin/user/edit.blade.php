@extends('admin.layouts.master')
@section('content')
  <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">User Edit</h1>
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
		<form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
			@csrf
			<div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label><b>Name *</b></label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control @error('name') is-invalid @enderror" required>
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
                <input type="email" name="email" value="{{ $user->email }}" class="form-control @error('email') is-invalid @enderror" required>
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
                <input type="text" name="phone" value="{{ $user->phone }}" class="form-control @error('phone') is-invalid @enderror" required>
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label><b>Username *</b></label>
                <input type="text" name="username" value="{{ $user->username }}" class="form-control @error('username') is-invalid @enderror" required>
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label><b>Role *</b></label>
                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                  @foreach($roles as $role)
                  <option value="{{ $role->name }}" {{ in_array($role->name, $user->getRoleNames()->toArray()) ? 'selected' : '' }}>{{ explode('#', $role->name)[0] }}</option>
                  @endforeach
                </select>
                @error('role')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </div>
          </div>
        </div>
      </div>
		</form>
	</div>
</section>
@endsection
