
  @include('admin.partials.header')


  @include('admin.partials.sidebar')

  <!-- Content Wrapper. Contains page content -->
  
  <div class="content-wrapper">
  	
  	 @include('admin.partials.messages')
    @yield('content')
  </div>
  @include('admin.partials.footer')
