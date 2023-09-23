  @extends('admin.layouts.master')

  @section('style')
      <style>
          .categoryCardBox {
              gap: 20px
          }

          .categoryCard1 {
              padding: 15px 15px 0px;
              padding-left: 22px;
              border-radius: 14px;
              background: rgba(235, 98, 0, 0.888);
              color: white;
              display: inline-block;
              max-width: 22% !important;
              box-shadow: 0px 4px 12px 1px rgba(124, 48, 0, 0.665);
          }

          .categoryCard2 {
              padding: 15px 15px 0px;
              padding-left: 22px;
              border-radius: 14px;
              background: rgba(238, 99, 0, 0.773);
              color: white;
              display: inline-block;
              max-width: 22% !important;
              box-shadow: 0px 4px 14px 2px rgba(124, 48, 0, 0.665);
          }
      </style>
  @endsection

  @section('content')
      <!-- Content Header (Page header) -->
      <div class="content-header">
          <div class="container-fluid">
              <div class="row mb-2">
                  <div class="col-sm-6">
                      <h1 class="m-0">Sells Overall Report</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                          <li class="breadcrumb-item active">Sell</li>
                      </ol>
                  </div><!-- /.col -->
              </div><!-- /.row -->
          </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <section class="content">
          <div class="container-fluid">
              <div class="card p-4">
                  <div class="row">
                      <div class="col-lg-7">
                          <h3>Total Sells Confirmed : {{ count($orders->where('order_status_id', '!=', 5)) }} (Completed: {{ count($orders->where('order_status_id', '==', 4)) }})</h3>
                          <h3 class="text-success">Total Sold Amount :
                              {{ round(
                                  $orders->filter(function ($order) {
                                          return $order->order_status_id != 5 && $order->is_return != 1;
                                      })->sum('price'),
                              ) }} TK
                          </h3>
                          <h5 class="text-" style="color: #e97900">Total Sells Returned : {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', '!=', 0)) }} (Fully: {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', 1)) }}, Partially: {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', 2)) }})</h5>
                          <h5 class="text-danger mt-3">Total Orders Cancelled : {{ count($orders->where('order_status_id', '==', 5)) }}</h5>
                      </div>
                      <div class="col-lg-5">
                          <h4>Total Sells From POS : {{ count($orders->where('source', 'Offline')->where('order_status_id', '!=', 5)) }} (Completed: {{ count($orders->where('source', 'Offline')->where('order_status_id', '==', 4)) }})</h4>
                          <h4>Total Sells From Website : {{ count($orders->where('source', 'Website')->where('order_status_id', '!=', 5)) }} (Completed: {{ count($orders->where('source', 'Website')->where('order_status_id', '==', 4)) }})</h4>
                      </div>
                  </div>
                  <hr>
                  <div class="row mt-5 categoryCardBox">
                      @foreach ($categories as $key => $category)
                          @php
                              $sells_cat = 0;
                              $sells_amount_cat = 0;
                          @endphp
                          @if ($category->parent_id == 0)
                              @foreach ($orders as $item)
                                  {{-- update needed --}}
                                  @if ($item->order_status_id != 5 && $item->is_return != 1)
                                      @foreach ($item->order_product as $order_product)
                                          @if ($order_product->product->category_id == $category->id)
                                              @php
                                                  $sells_cat += $order_product->qty;
                                                  $sells_amount_cat += $order_product->price * $order_product->qty;
                                              @endphp
                                          @endif
                                      @endforeach
                                  @endif
                              @endforeach
                              @if ($sells_cat > 0)
                                  <div class="col-3 gap-3 mb-2 categoryCard1">
                                      <h4 class=""><b>{{ $category->title }}</b></h4>
                                      <span>Total Sold: <span class="ml-1">{{ $sells_cat }} pc</span></span>
                                      <p>Total Sold Amount: <span class="ml-1">{{ round($sells_amount_cat) }} TK</span></p>
                                  </div>
                              @endif
                          @else
                              @foreach ($orders as $item)
                                  {{-- update needed --}}
                                  @if ($item->order_status_id != 5 && $item->is_return != 1)
                                      @foreach ($item->order_product as $order_product)
                                          @if ($order_product->product->sub_category_id == $category->id)
                                              @php
                                                  $sells_cat += $order_product->qty;
                                                  $sells_amount_cat += $order_product->price * $order_product->qty;
                                              @endphp
                                          @endif
                                      @endforeach
                                  @endif
                              @endforeach
                              @if ($sells_cat > 0)
                                  <div class="col-3 gap-3 mb-2 categoryCard2">
                                      <h5><b>{{ $category->parent->title . ' - ' . $category->title }}</b></h5>
                                      <span>Total Sold: <span class="ml-1">{{ $sells_cat }} pc</span></span>
                                      <p>Total Sold Amount: <span class="ml-1">{{ round($sells_amount_cat) }} TK</span></p>
                                  </div>
                              @endif
                          @endif
                      @endforeach
                  </div>
                  <div class="row mt-5">
                      <div class="col-2 m-auto">
                          <a href="{{ route('sell.index') }}" class="btn btn-info bg-primary">Go to Sell List</a>
                      </div>
                  </div>
              </div>
          </div>
      </section>
  @endsection
