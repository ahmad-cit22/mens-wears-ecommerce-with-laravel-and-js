@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Page Edit</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}" target="_blank">Home</a></li>
                        <li class="breadcrumb-item active">Page</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('page.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label><b>Name *</b></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $page->name }}">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <!-- @if ($page->id == 2 || $page->id == 3 || $page->id == 7 || $page->id == 8)
    <div class="form-group">
            <label><b>Image (1500x360 px)*</b></label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            <img src="{{ asset('web-images/' . $page->image) }}" width="100">
           </div>
    @endif -->
                <div class="form-group">
                    <label><b>Description *</b></label>
                    <textarea class="tinymce form-control @error('description') is-invalid @enderror" name="description">
					{{ $page->description }}
				</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label><b>Meta Description *</b></label>
                    <textarea class="summernote form-control @error('meta_description') is-invalid @enderror" name="meta_description">
					{{ $page->meta_description }}
				</textarea>
                    @error('meta_description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                @if ($page->id == 1)
                    <!-- <div class="form-group">
            <label><b>Description 1 *</b></label>
            <textarea class="summernote form-control @error('description1') is-invalid @enderror" name="description1">
					{{ $page->description1 }}
				</textarea>
            @error('description1')
        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
    @enderror
           </div>
           <div class="form-group">
            <label><b>Description 2 *</b></label>
            <textarea class="summernote form-control @error('description2') is-invalid @enderror" name="description2">
					{{ $page->description2 }}
				</textarea>
            @error('description2')
        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
    @enderror
           </div>
           <div class="form-group">
            <label><b>Description 3 *</b></label>
            <textarea class="summernote form-control @error('description3') is-invalid @enderror" name="description3">
					{{ $page->description3 }}
				</textarea>
            @error('description3')
        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
    @enderror
           </div> -->
                @endif
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </section>
@endsection
