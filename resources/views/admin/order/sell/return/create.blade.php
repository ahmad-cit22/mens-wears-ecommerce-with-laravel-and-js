@extends('admin.layouts.master')
@php
$business = App\Models\Setting::find(1);
@endphp
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Sell Return</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">sell-return</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                  <img src="{{ asset('images/website/'.$business->footer_logo) }}" width="200">
                    <small class="float-right" style="float: right;">Date: {{ Carbon\Carbon::parse($order->created_at)->format('d M Y, g:ia') }}</small>
                  </h4><br>
                </div><br>
                
                  <hr style="color: #800020;">
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-8 invoice-col">
                  
                  <address>
                   	Name: <strong>{{ $order->name }}</strong><br>
                    Phone:  <strong>{{ $order->phone }}</strong><br>
                    Email: {{ $order->email }}<br>
                    Shipping Address: {{ $order->shipping_address }}, {{ optional($order->area)->name }}, {{ optional($order->district)->name }}
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  
                  <address>
                    Satus: <spane class="badge badge-{{ $order->status->color }}"> {{ $order->status->title }}</spane><br>
                    Order Track Number: <strong># {{ $order->code }}</strong><br>
                    Payment Satus: <spane class="badge badge-{{ $order->payment_status == 1 ? 'success' : 'danger' }}"> {{ $order->payment_status == 1 ? 'Paid' : 'Not Paid' }}</spane><br>
                    Payment Method: <strong># {{ $order->payment_method }}</strong><br>
                    @if($order->payment_method == 'Bkash' || $order->payment_method == 'Rocket')
                    Transaction ID: <strong> {{ $order->transaction_id }}</strong><br>
                    Sender Phone: <strong> {{ $order->sender_phone }}</strong><br>
                    Amount: <strong>{{ env('CURRENCY') }} {{ $order->sender_amount }}</strong><br>
                    @endif
                  </address>
                </div>
              </div>
              <!-- /.row -->

              

              <form action="{{ route('sellreturn.store') }}" method="POST">
              	@csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <input type="hidden" name="order_code" value="{{ $order->code }}">
              	<!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>S.N</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Return Qty</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($order->order_product as $product)
                        @php
                        $return_products = App\Models\OrderReturn::where('order_id', $product->order_id)->where('product_id', $product->product_id)->where('size_id', $product->size_id)->get();
                        @endphp
                        <tr>
                          <td>{{ $loop->index + 1 }}</td>
                          <td>{{ $product->product->title }}{{ isset($product->size_id) ? ' - '.$product->size->title : '' }}</td>
                          <td>{{ env('CURRENCY') }}{{ $product->price }}</td>
                          <td>{{ $product->qty }}</td>
                          <td>
                            <input type="number" name="qty[]" class="form-control" min="1" max="{{ $product->qty - $return_products->sum('qty') }}">
                            <span class="text-danger">{{ $return_products->sum('qty') }} Product(s) Returned</span>

                            <input type="hidden" name="product_id[]" value="{{ $product->product_id }}">
                            <input type="hidden" name="size_id[]" value="{{ $product->size_id }}">
                            <input type="hidden" name="price[]" value="{{ $product->price }}">
                          </td>
                          <td>{{ env('CURRENCY') }}{{ $product->price * $product->qty }}</td>
                        </tr>
                      @endforeach
                      <tr>
                        <td colspan="5" align="right">Delivery Charge:</td>
                        <td>{{ env('CURRENCY') }}{{ $order->delivery_charge == NULL ? 0 : $order->delivery_charge }}</td>
                      </tr>
                      <tr>
                        <td colspan="5" align="right">Discount:</td>
                        <td>{{ env('CURRENCY') }}{{ $order->discount_amount == NULL ? 0 : $order->discount_amount }}</td>
                      </tr>
                      @if($order->wallet_amount != NULL)
                      <tr>
                        <td colspan="5" align="right">Wallet Use:</td>
                        <td>{{ env('CURRENCY') }}{{ $order->wallet_amount }}</td>
                      </tr>
                      @endif
                      <tr>
                        <td colspan="5" align="right">Total:</td>
                        <td>{{ env('CURRENCY') }}{{ $order->price + $order->delivery_charge - $order->wallet_amount }}</td>
                      </tr>
                      <tr>
                        <td colspan="5" align="right">Total Payable:</td>
                        <td>{{ env('CURRENCY') }}{{ $order->price + $order->delivery_charge - $order->wallet_amount - $order->advance }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col-12">
                	<button class="btn btn-primary">Save</button>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              </form>
              

              

             
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection

@section('scripts')
	<script>
    //Date range picker
    $('#reservation').daterangepicker();
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
@endsection