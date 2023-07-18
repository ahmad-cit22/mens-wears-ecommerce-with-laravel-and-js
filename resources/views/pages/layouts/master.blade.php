@php
  $business = App\Models\Setting::find(1);
@endphp
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', $business->name)</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/website/'.$business->favicon) }}">

    <!-- All CSS is here
	============================================ -->

    @include('pages.partials.style')
    @yield('style')

</head>

<body>
    <!-- Messenger Chat plugin Code -->
    <div id="fb-root"></div>

    <!-- Your Chat plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>
    <div class="main-wrapper">
        @include('pages.partials.header')
        @yield('content')
        @include('pages.partials.footer')
    </div>

    <!-- All JS is here
============================================ -->

    @include('pages.partials.script')

    @yield('scripts')
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>