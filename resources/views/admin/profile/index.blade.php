@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Profile</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" target="_blank">Home</a></li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section class="content">
	<div class="container-fluid">
		<!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('images/user/user-avatar-icon.jpg') }}"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $user->name . ' ' . $user->last_name }}</h3>

                <strong><i class="fas fa-phone mr-1"></i> Phone</strong>
                <p class="text-muted">{{ $user->phone }}</p>

                <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                <p class="text-muted">{{ $user->email }}</p>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Change Information</a></li>
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Change Password</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <form action="{{ route('user.profile.update') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputName" name="name" placeholder="Name" value="{{ $user->name }}">
                          @error('name')
                            <span class="text-danger" style="font-size: 80%;" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                        </div>
                      </div>
                      <!-- <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Last Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputName2" name="last_name" placeholder="Name" value="{{ $user->last_name }}">
                        </div>
                      </div> -->
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail" name="email" placeholder="Email" value="{{ $user->email }}">
                          @error('email')
                            <span class="text-danger" style="font-size: 80%;" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="phone" name="phone" placeholder="Email" value="{{ $user->phone }}">
                          @error('phone')
                            <span class="text-danger" style="font-size: 80%;" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="address" name="address"  value="{{ $user->address }}">
                        </div>
                      </div>
                      <!-- <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Gender</label>
                        <div class="col-sm-10">
                          <select class="form-control " name="gender">
                              <option value="0">Select Your Gender</option>
                              <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                              <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                              <option value="Prefer not to disclose" {{ $user->gender == 'Prefer not to disclose' ? 'selected' : '' }}>Prefer not to disclose</option>
                          </select>
                        </div>
                      </div> -->
                      <div class="form-group row">
                        <label for="image" class="col-sm-2 col-form-label">Image</label>
                        <div class="col-sm-10">
                          <input type="file" class="form-control" id="image" name="image">
                        </div>
                      </div>
                      
                      
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          
                          <button type="submit" class="btn btn-primary">Update</button>
                          
                        </div>
                      </div>
                    </form>
                  </div>
                  

                  <div class="tab-pane" id="settings">
                    <form action="{{ route('user.password.change') }}" method="POST" class="form-horizontal">
                      @csrf
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Current Password</label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="inputName" name="c_password" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">New Password</label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="inputEmail" name="n_password" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="inputName2" name="cf_password" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
	</div>
</section>
@endsection
