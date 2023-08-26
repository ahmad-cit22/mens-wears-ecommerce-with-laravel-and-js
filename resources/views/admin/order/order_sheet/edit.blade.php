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
                    <h1 class="m-0">Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Order</li>
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
                    <!-- <div class="callout callout-info">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <h5><i class="fas fa-info"></i> Note:</h5>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                  This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div> -->


                    <!-- Main content -->
                    <form action="" method="POST">
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="invoice p-3 mb-3">
                                <!-- title row -->
                                <div class="row">
                                    @if ($order->is_final == 0)
                                        {{-- <div class="col-12" align="right">
                                        <a href="#confirmSell{{ $order->id }}" data-toggle="modal" class="btn btn-primary bg-purple"><i class="fas fa-check"></i> Mark as Sold</a>
                                        <hr>
                                    </div> --}}
                                    @endif
                                    <div class="col-10">
                                        <h4>
                                            <img src="{{ asset('images/website/' . $business->footer_logo) }}" width="200">
                                            <small class="float-right" style="float: right;">Date: {{ Carbon\Carbon::parse($order->created_at)->format('d M Y, g:ia') }}</small>
                                        </h4><br>
                                    </div>
                                    <div class="col-2">
                                        <a href="{{ route('fos.index') }}" class="btn btn-info bg-primary float-right">Order Sheet</a>

                                    </div>
                                    <br>

                                    <hr style="color: #800020;">
                                    <!-- /.col -->
                                </div>
                                <!-- info row -->
                                <div class="row invoice-info">
                                    <div class="col-12">
                                        <address>
                                            Memo Code:
                                            @if ($order->code)
                                                <strong># {{ $order->code }}</strong>
                                            @else
                                                N/A
                                            @endif
                                            <br>
                                            <div>
                                                Status: <span class="ml-1 fs-5 p-2 my-2 badge badge-{{ $order->status->color }}"> {{ $order->status->title }}</span><br>
                                                Special Status: <span class="ml-1 fs-5 p-2 mb-2 badge badge-{{ $order->special_status->color }}"> {{ $order->special_status->title }}</span>
                                            </div>

                                        </address>
                                    </div>
                                    <div class="col-12 invoice-col">

                                        <address>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="text-body">Customer Name</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="name" class="form-control" placeholder="Enter Customer Name" value="{{ $order->name }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-body">Email</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="email" name="email" class="form-control" placeholder="Enter E-mail" value="{{ $order->email }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-body">Phone</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number" value="{{ $order->phone }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-body">Customer Address</label>
                                                    <fieldset class="form-group mb-3">
                                                        <textarea type="text" class="form-control" placeholder="Enter Customer Address" name="shipping_address" required>{{ $order->shipping_address }}</textarea>
                                                    </fieldset>
                                                </div>
                                                <div class="col-12 row">
                                                    <div class="col-lg-3 col-md-4 col-6">
                                                        <div class="selectmain">
                                                            <label class="text-dark d-flex">Order Source</label>
                                                            <select name="source" class="select2 select-down" id="soudrce" style="width: 100% !important;">
                                                                <option value="0">--- Select an Option ---</option>
                                                                <option value="Offline" {{ $order->source == 'Offline' ? 'selected' : '' }}>Offline</option>
                                                                <option value="Online" {{ $order->source == 'Online' ? 'selected' : '' }}>Online</option>
                                                                <option value="Wholesale" {{ $order->source == 'Wholesale' ? 'selected' : '' }}>Wholesale</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-6">
                                                        <div class="selectmain">
                                                            <label class="text-dark d-flex">Courier Name</label>
                                                            <select name="courier_id" class="select2 select-down" id="coudrier_id" style="width: 100% !important;">
                                                                <option value="">--- Select an Option ---</option>
                                                                @foreach ($couriers as $courier)
                                                                    <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>{{ $courier->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-6">
                                                        <div class="selectmain">
                                                            <label class="text-dark d-flex">Status</label>
                                                            <select name="source" class="select2 select-down" id="source" style="width: 100% !important;">
                                                                <option value="0">--- Select an Option ---</option>
                                                                @foreach ($statuses as $status)
                                                                    <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>{{ $status->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-6">
                                                        <div class="selectmain">
                                                            <label class="text-dark d-flex">Special Status</label>
                                                            <select name="courier_id" class="select2 select-down" id="courier_id" style="width: 100% !important;">
                                                                <option value="">--- Select an Option ---</option>
                                                                @foreach ($special_statuses as $status)
                                                                    <option value="{{ $status->id }}" {{ $order->special_status_id == $status->id ? 'selected' : '' }}>{{ $status->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="text-body">Note</label>
                                        <fieldset class="form-group">
                                            <textarea name="note" id="note" class="form-control" placeholder="Add Notes Here">{{ $order->note }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-body">Remarks</label>
                                        <fieldset class="form-group">
                                            <textarea name="remarks" id="remarks" class="form-control" placeholder="Add Notes Here">{{ $order->remarks }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                Phone: <strong>{{ $order->phone }}</strong><br>
                                Email: {{ $order->email }}<br>
                                Shipping Address: {{ $order->shipping_address }}<br>
                                @if ($order->courier_id)
                                    Courier Name: {{ $order->courier->name }}
                                @endif
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <form action="{{ route('order.advance.payment', $order->id) }}" method="POST">
                                    @csrf
                                    <div class="form-froup">
                                        <label>Advance Amount</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="amount" value="{{ $order->advance }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary bg-purple" type="submit">Save payment</button>
                                            </div>
                                        </div>
                                        @error('amount')
                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.row -->

                        <div class="row">


                        </div>

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
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->order_product as $product)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                @if ($product->product != null)
                                                    <td>{{ $product->product->title }}{{ isset($product->size_id) ? ' - ' . $product->size->title : '' }}
                                                        @if ($product->return_qty != null)
                                                            <span class="text-danger ml-2">({{ $product->return_qty }} Product(s) Returned)</span>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td> --</td>
                                                @endif
                                                <td>{{ env('CURRENCY') }}{{ $product->price }}</td>
                                                <td>{{ $product->qty }}</td>
                                                <td>{{ env('CURRENCY') }}{{ $product->price * $product->qty }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" align="right">Discount (-):</td>
                                            <td>{{ env('CURRENCY') }}{{ $order->discount_amount == null ? 0 : $order->discount_amount }}</td>
                                        </tr>

                                        <tr>
                                            <td colspan="4" align="right">Total:</td>
                                            <td>{{ env('CURRENCY') }}{{ round($order->price) }}</td>
                                        </tr>
                                        @if ($order->advance)
                                            <tr>
                                                <td colspan="4" align="right">Advanced (-):</td>
                                                <td>{{ env('CURRENCY') }}{{ $order->advance }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="4" align="right">Total Payable:</td>
                                            <td>{{ env('CURRENCY') }}{{ round($order->price - $order->advance) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->



                        <!-- this row will not appear when printing -->
                        <!-- <div class="row no-print">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="col-12">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <a href="" class="btn btn-primary float-right" style="margin-right: 5px;"><i class="fas fa-download"></i> Generate PDF</a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                  </div> -->
                </div>
                <!-- /.invoice -->
            </div>
            </form>
        </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <script>
        //Date range picker
        $('#reservation').daterangepicker();
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
