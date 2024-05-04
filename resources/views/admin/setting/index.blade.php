@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Business Settings</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">business-settings</li>
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
               <form action="{{ route('setting.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
                 @csrf
                 <div class="row">
                   <div class="col-md-12">
                     <div class="form-group">
                       <label>Name*</label>
                       <input type="text" name="name" value="{{ $setting->name }}" class="form-control @error('name') is-invalid @enderror">
                       @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>
                   <div class="col-md-4">
                     <div class="form-group">
                       <label>Logo*</label>
                       <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror">
                       @error('logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <img src="{{ asset('images/website/'. $setting->logo) }}" width="200">
                     </div>
                   </div>
                   <div class="col-md-4">
                     <div class="form-group">
                       <label>Footer Logo*</label>
                       <input type="file" name="footer_logo" class="form-control @error('footer_logo') is-invalid @enderror">
                       @error('footer_logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <img src="{{ asset('images/website/'. $setting->footer_logo) }}" width="200">
                     </div>
                   </div>
                   <div class="col-md-4">
                     <div class="form-group">
                       <label>Favicon*</label>
                       <input type="file" name="favicon" class="form-control @error('favicon') is-invalid @enderror">
                       @error('favicon')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <img src="{{ asset('images/website/'. $setting->favicon) }}" width="80">
                     </div>
                   </div>
                   <div class="col-md-6">
                     <div class="form-group">
                       <label>Phone*</label>
                       <input type="text" name="phone" value="{{ $setting->phone }}" class="form-control @error('phone') is-invalid @enderror">
                       @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>
                   <div class="col-md-6">
                     <div class="form-group">
                       <label>Email*</label>
                       <input type="email" name="email" value="{{ $setting->email }}" class="form-control @error('email') is-invalid @enderror">
                       @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>
                   <div class="col-md-12">
                     <div class="form-group">
                       <label>Additional Phone*</label>
                       <input type="text" name="additional_phone" value="{{ $setting->additional_phone }}" class="form-control @error('additional_phone') is-invalid @enderror">
                       @error('additional_phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>
                   <div class="col-md-12">
                     <div class="form-group">
                       <label>Address*</label>
                       <input type="text" name="address" value="{{ $setting->address }}" class="form-control @error('address') is-invalid @enderror">
                       @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-12">
                     <div class="form-group">
                        <label>Slider Option*</label>
                        <select name="slider_option" class="select2 form-control @error('slider_option') is-invalid @enderror">
                          <option value="image" {{ $setting->slider_option == 'image' ? 'selected' : '' }}>Image</option>
                          <option value="video" {{ $setting->slider_option == 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                        @error('slider_option')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-12">
                     <div class="form-group">
                       <label>Footer text*</label>
                       <textarea name="combine_address" class="summernote form-control @error('combine_address') is-invalid @enderror">{!! $setting->combine_address !!}</textarea>
                       @error('combine_address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-4">
                     <div class="form-group">
                       <label>Shipping Charge(Inside Dhaka)*</label>
                       <input type="text" name="shipping_charge_dhaka" value="{{ $setting->shipping_charge_dhaka }}" class="form-control @error('shipping_charge_dhaka') is-invalid @enderror">
                       @error('shipping_charge_dhaka')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-4">
                     <div class="form-group">
                       <label>Shipping Charge(Dhaka Nearby)*</label>
                       <input type="text" name="shipping_charge_dhaka_metro" value="{{ $setting->shipping_charge_dhaka_metro }}" class="form-control @error('shipping_charge_dhaka_metro') is-invalid @enderror">
                       @error('shipping_charge_dhaka_metro')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-4">
                     <div class="form-group">
                       <label>Shipping Charge(Outside Dhaka)*</label>
                       <input type="text" name="shipping_charge" value="{{ $setting->shipping_charge }}" class="form-control @error('shipping_charge') is-invalid @enderror">
                       @error('shipping_charge')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-6">
                     <div class="form-group">
                       <label>VAT (%)</label>
                       <input type="number" name="vat" value="{{ $setting->vat }}" class="form-control @error('vat') is-invalid @enderror" placeholder="Enter VAT Percentage">
                       @error('vat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-6">
                     <div class="form-group">
                       <label>BIN No.</label>
                       <input type="text" name="bin_no" value="{{ $setting->bin_no }}" class="form-control @error('bin_no') is-invalid @enderror" placeholder="Enter BIN No.">
                       @error('bin_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-4">
                     <div class="form-group">
                       <label>Facebook</label>
                       <input type="text" name="facebook" value="{{ $setting->facebook }}" class="form-control @error('facebook') is-invalid @enderror">
                       @error('facebook')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>
                   <div class="col-md-4">
                     <div class="form-group">
                       <label>Instagram</label>
                       <input type="text" name="instagram" value="{{ $setting->instagram }}" class="form-control @error('instagram') is-invalid @enderror">
                       @error('instagram')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>
                   <div class="col-md-4">
                     <div class="form-group">
                       <label>Twitter</label>
                       <input type="text" name="twitter" value="{{ $setting->twitter }}" class="form-control @error('twitter') is-invalid @enderror">
                       @error('twitter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>
                   <div class="col-md-4">
                     <div class="form-group">
                       <label>YouTube</label>
                       <input type="text" name="youtube" value="{{ $setting->youtube }}" class="form-control @error('youtube') is-invalid @enderror">
                       @error('youtube')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>
                   <div class="col-md-8">
                     <div class="form-group">
                       <label>Linkedin</label>
                       <input type="text" name="linkedin" value="{{ $setting->linkedin }}" class="form-control @error('linkedin') is-invalid @enderror">
                       @error('linkedin')
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
