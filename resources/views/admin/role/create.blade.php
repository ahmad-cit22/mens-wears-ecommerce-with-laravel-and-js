@extends('admin.layouts.master')
@section('content')
  <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Create Role</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">role</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
	<div class="container-fluid">
		<form action="{{ route('role.store') }}" method="POST">
			@csrf
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label><b>Name *</b></label>
                <input type="text" name="name" value="{{ explode('#', old('name'))[0] }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                    <h3>Check Permission(s)</h3>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="permissionCheckboxAll"  value="1">
                      <label class="form-check-label" for="permissionCheckboxAll"><b>All</b></label>
                    </div>
                    <hr>
                    @foreach($permissionGroups as $group)
                    <div class="form-check mb-3">
                      <input class="form-check-input" type="checkbox" id="{{ $group->group_name }}" onclick="checkPermissionByGroup('{{ $group->group_name }}', this)"  value="{{ $group->group_name }}">
                      <label class="form-check-label" for="{{ $group->group_name }}">{{ $group->group_name }}</label>
                    </div>
                    @php
                      $permissions = App\Models\User::permissionsByGroupName($group->group_name);
                    @endphp
                    <div class="row col-md-12 {{ $group->group_name }} ml-2">
                      @foreach($permissions as $permission)

                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="permissionCheckbox{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" {{ (is_array(old('permissions')) && in_array($permission->name, old('permissions'))) ? ' checked' : '' }}>
                        <label class="form-check-label" for="permissionCheckbox{{ $permission->id }}">{{ $permission->name }}</label>
                      </div>
                      @endforeach
                    </div>
                    <hr>
                    @endforeach


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

@section('scripts')
<script>
  $('#permissionCheckboxAll').click(function() {
    if (this.checked) {
      $('input[type=checkbox]').prop('checked', true);
    } else {
      $('input[type=checkbox]').prop('checked', false);
    }
  });
  function checkPermissionByGroup(className, checkThis) {
    const groupIdName = $("#"+checkThis.id);
    const classCheckBox = $('.'+className+' input');
    if (groupIdName.is(':checked')) {
      classCheckBox.prop('checked', true);
    } else {
      classCheckBox.prop('checked', false);
    }
  }
</script>

@endsection
