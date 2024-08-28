@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Slider Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}" target="_blank">Home</a></li>
                        <li class="breadcrumb-item active">Slider</li>
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
                    <form action="{{ route('slider.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select slide image To be change</label>
                                    <select name="position" class="form-control @error('position') is-invalid @enderror"
                                        required>
                                        <option value="">Chose</option>
                                        <option value="1">1st Slide</option>
                                        <option value="2">2nd Slide</option>
                                        <option value="3">3rd Slide</option>
                                    </select>
                                    @error('position')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Change Slide Images (1730x590 px)</label>
                                    <input type="file" name="image"
                                        class="form-control @error('image') is-invalid @enderror">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}
                            <!-- <div class="col-md-6">
                      <div class="form-group">
                        <label>Small Description</label>
                        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror">
                        @error('description')
        <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
    @enderror
                      </div>
                    </div> -->
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input type="text" name="button_text"
                                        class="form-control @error('button_text') is-invalid @enderror">
                                    @error('button_text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Link</label>
                                    <input type="text" name="link"
                                        class="form-control @error('link') is-invalid @enderror">
                                    @error('link')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Change Slide</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="card-body row">
                    @foreach ($sliders as $slider)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <img src="{{ asset('images/slider/' . $slider->image) }}" width="100%">
                                    {{-- <h3>{{ $slider->title }}</h3> --}}
                                    <a href="{{ $slider->link }}" class="btn btn-info"
                                        target="_blank">Link</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <form action="{{ route('slider.video.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Video*</label>
                                <input type="file" name="video"
                                    class="form-control @error('video') is-invalid @enderror">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Change video</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body row">
                    <div class="col-md-5">
                        <video style="width: 100%;" controls>
                            <source src="{{ asset('videos/' . $video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
    </section>
@endsection

@section('scripts')
    <script></script>
@endsection
