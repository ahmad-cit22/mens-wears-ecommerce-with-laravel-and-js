@extends('pages.layouts.master')

@section('title')
Track Order
@endsection

@section('content')
<div class="shop-area section-padding-3 pt-70">
    <div class="container-fluid">
        <div class="row justify-content-center p-4">
			<div class="col-md-8">
				<form action="{{ route('order.track.result') }}" method="GET" style="padding-top: 50px;">
					<input type="hidden" name="_token" value="Kwge0xIKTysflJAyOJvd86ewJWSSEHE7LQNz3EFG">						<div class="form-group">
						<input type="text" name="code" placeholder="Code" class="form-control">
					</div>
					<div class="form-group" align="center">
						<button type="submit" class="btn btn-rounded btn-dark mt-4" style="background-color: #6f42c1;">Track Now</button>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>
@endsection