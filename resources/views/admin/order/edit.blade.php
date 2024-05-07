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
                        @if ($order->vendor_id)
                            <li class="breadcrumb-item active">vendor-order</li>
                        @else
                            <li class="breadcrumb-item active">order</li>
                        @endif
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
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="row">
                                @if ($order->is_final == 0)
                                    <div class="col-12" align="right">
                                        <a href="#confirmSell{{ $order->id }}" data-toggle="modal" class="btn btn-primary bg-purple"><i class="fas fa-check"></i> Mark as Sold</a>
                                        <hr>
                                    </div>
                                @endif
                                <div class="col-11">
                                    <h4>
                                        <img src="{{ asset('images/website/' . $business->footer_logo) }}" width="200">
                                        <small class="float-right" style="float: right;">Date: {{ Carbon\Carbon::parse($order->created_at)->format('d M Y, g:ia') }}</small>
                                    </h4><br>
                                </div>
                                <div class="col-1">
                                    @if ($order->source == 'Wholesale')
                                        <a href="{{ route('sell.wholesale.index') }}" class="btn btn-info bg-primary float-right mb-2">Wholesale List</a> <br>
                                    @else
                                        @if ($order->vendor_id)
                                            <a href="{{ route('vendor_sell.index') }}" class="btn btn-info bg-primary float-right mb-2">Sell List</a> <br>
                                        @else
                                            <a href="{{ route('sell.index') }}" class="btn btn-info bg-primary float-right mb-2">Sell List</a> <br>
                                        @endif
                                    @endif

                                    <div class="ml-4">
                                        <a href="{{ route('order.invoice.generate', $order->id) }}" class="btn btn-secondary btn-sm" title="Download Invoice"><i class="fas fa-download"></i></a>
                                        <a href="{{ route('order.invoice.pos.generate', $order->id) }}" class="btn btn-success btn-sm" title="Print POS Invoice"><i class="fas fa-print"></i></a>
                                    </div>
                                </div>
                                <br>

                                <hr style="color: #800020;">
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-md-5 invoice-col">

                                    <address>
                                        Name: <strong>{{ $order->name }}</strong>
                                        @if ($order->customer->member)
                                            <span class="badge badge-info">{{ $order->customer->member->card->card_status }}</span>
                                        @endif <br>
                                        Phone: <strong>{{ $order->phone }}</strong><br>
                                        @if ($order->email != null)
                                            Email: {{ $order->email }}<br>
                                        @endif
                                        @if ($order->other_info != null)
                                            Other Info: {{ $order->other_info }}<br>
                                        @endif
                                        @if (!$order->vendor_id)
                                            Shipping Address: {{ $order->shipping_address }}, {{ optional($order->area)->name }}, {{ optional($order->district)->name }}<br>
                                            @if ($order->courier_name)
                                                Courier Name: {{ $order->courier_name }}<br>
                                            @endif
                                        @endif
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-3 invoice-col">
                                    <address>
                                        <div class="mb-1">
                                            Status: <span class="ml-1 badge badge-{{ $order->status->color }}"> {{ $order->status->title }}</span>
                                            @if ($order->is_return == 1)
                                                <span class="badge badge-danger ml-1">Returned</span>
                                            @elseif ($order->is_return == 2)
                                                <span class="badge badge-danger ml-1">Returned Partially</span>
                                            @else
                                            @endif
                                            @if ($order->cod != 0)
                                                <span class="badge badge-info ml-1">COD Applied</span>
                                            @endif
                                            @if ($order->add_loss == 1)
                                                <span class="badge badge-danger ml-1">Loss Added</span>
                                            @endif <br>
                                        </div>

                                        Order Track Number: <strong class="ml-1"># {{ $order->code }}</strong><br>
                                        Payment Satus: <span class="badge badge-{{ $order->payment_status == 1 ? 'success' : 'danger' }}"> {{ $order->payment_status == 1 ? 'Paid' : 'Not Paid' }}</span><br>
                                        @if ($order->payment_method != null)
                                            Payment Method: <strong> {{ $order->payment_method }}</strong><br>
                                            @if ($order->payment_method == 'Bkash' || $order->payment_method == 'Rocket')
                                                Transaction ID: <strong> {{ $order->transaction_id }}</strong><br>
                                                Sender Phone: <strong> {{ $order->sender_phone }}</strong><br>
                                                Amount: <strong>{{ env('CURRENCY') }} {{ $order->sender_amount }}</strong><br>
                                            @endif
                                        @endif
                                        Order Source: {{ $order->source }} @if ($order->vendor_id)
                                            <b>({{ $order->vendor->name }})</b>
                                        @endif
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-4 invoice-col">
                                    <address>
                                        Created By: @if ($order->created_by)
                                            <strong class="ml-1"><a href="{{ route('user.edit', $order->created_by->user_id) }}">{{ $order->created_by->adder->name }}</a> ({{ $order->created_by->created_at->format('d M, Y - g:i A') }})</strong><br>
                                        @else
                                            N/A <br>
                                        @endif

                                        @if ($order->printed_by)
                                            Printed By: <strong class="ml-1"><a href="{{ route('user.edit', $order->printed_by->user_id) }}">{{ $order->printed_by->adder->name }}</a> ({{ $order->printed_by->created_at->format('d M, Y - g:i A') }})</strong><br>
                                        @endif

                                        @if ($order->packaged_by)
                                            Packaged By: <strong class="ml-1"><a href="{{ route('user.edit', $order->packaged_by->user_id) }}">{{ $order->packaged_by->adder->name }}</a> ({{ $order->packaged_by->created_at->format('d M, Y - g:i A') }})</strong><br>
                                        @endif

                                        @if ($order->convert_sell_by)
                                            Marked Sold By: <strong class="ml-1"><a href="{{ route('user.edit', $order->convert_sell_by->user_id) }}">{{ $order->convert_sell_by->adder->name }}</a> ({{ $order->convert_sell_by->created_at->format('d M, Y - g:i A') }})</strong><br>
                                        @endif

                                        @if ($order->order_paid_by)
                                            Paid By: <strong class="ml-1"><a href="{{ route('user.edit', $order->order_paid_by->user_id) }}">{{ $order->order_paid_by->adder->name }}</a> ({{ $order->order_paid_by->created_at->format('d M, Y - g:i A') }}) </strong><br>
                                        @endif

                                        @if ($order->order_returned_by)
                                            Returned By: <strong class="ml-1"><a href="{{ route('user.edit', $order->order_returned_by->user_id) }}">{{ $order->order_returned_by->adder->name }}</a> ({{ $order->order_returned_by->created_at->format('d M, Y - g:i A') }}) </strong><br>
                                        @endif

                                        @if ($order->order_canceled_by)
                                            Canceled By: <strong class="ml-1"><a href="{{ route('user.edit', $order->order_canceled_by->user_id) }}">{{ $order->order_canceled_by->adder->name }}</a> ({{ $order->order_canceled_by->created_at->format('d M, Y - g:i A') }}) </strong><br>
                                        @endif

                                        @if ($order->add_loss_by)
                                            Loss Added By: <strong class="ml-1"><a href="{{ route('user.edit', $order->add_loss_by->user_id) }}">{{ $order->add_loss_by->adder->name }}</a> ({{ $order->add_loss_by->created_at->format('d M, Y - g:i A') }}) </strong><br>
                                        @endif

                                        @if ($order->cod > 0 && $order->apply_cod_by)
                                            COD Applied By: <strong class="ml-1"><a href="{{ route('user.edit', $order->apply_cod_by->user_id) }}">{{ $order->apply_cod_by->adder->name }}</a> ({{ $order->apply_cod_by->created_at->format('d M, Y - g:i A') }}) </strong><br>
                                        @endif
                                    </address>
                                </div>
                            </div>
                            <!-- /.row -->
                            @if ($order->vendor_id)
                                <div class="row mt-3">
                                    <div class="col-md-6 border-right align-content-end">
                                        <form action="{{ route('order.discount_amount.update', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Discount Amount</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" name="discount_amount" value="{{ $order->discount_amount ?? 0 }}">
                                                </div>
                                                @error('discount_amount')
                                                    <span class="invalid-feedback" role="alert" style="display: block;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Extra Charge ({{ $order->extra_charge_type }})</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" name="extra_charge" value="{{ $order->extra_charge ?? 0 }}">
                                                </div>
                                                @error('extra_charge')
                                                    <span class="invalid-feedback" role="alert" style="display: block;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                        <form action="{{ route('order.payment.status.change', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Payment Status</label>
                                                <select name="payment_status" class="form-control select2">
                                                    @if (auth()->user()->can('order.paid') || 1 == $order->payment_status)
                                                        <option value="1" {{ $order->payment_status == 1 ? 'selected' : '' }}>Paid</option>
                                                    @endif
                                                    <option value="0" {{ $order->payment_status == 0 ? 'selected' : '' }}>Not Paid</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6 border-right">
                                        <form action="{{ route('order.status.change', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Order Status</label>
                                                <select name="order_status_id" class="form-control select2">
                                                    @foreach (App\Models\OrderStatus::where('is_active', 1)->orderBy('priority_no', 'ASC')->get() as $status)
                                                        @if (in_array($status->id, [1, 2, 4, 5]))
                                                            <option value="{{ $status->id }}" {{ $status->id == $order->order_status_id ? 'selected' : '' }}>{{ $status->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Reason</label>
                                                <input type="text" name="note" value="{{ $order->note }}" class="form-control">
                                                @error('note')
                                                    <span class="invalid-feedback" role="alert" style="display: block;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </div>
                                                <div class="col-8 text-right">
                                                    @if (1)
                                                        <a href="#add-loss" class="btn btn-primary bg-purple" data-toggle="modal">Add Loss</a>
                                                    @else
                                                        <a href="{{ route('order.remove.loss', $order->id) }}" class="btn btn-primary bg-info">Removed Loss</a>
                                                    @endif
                                                    @if ($order->price > 0)
                                                        <a href="{{ route('order.return', $order->id) }}" class="btn btn-primary bg-primary">Return Products</a>
                                                    @endif
                                                    @if ($order->discount_amount != null)
                                                        <a href="#remove-discount" data-toggle="modal" class="btn btn-primary bg-danger">Remove Discount</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-12">
                                        <form action="{{ route('order.apply.cod', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group" align="right">
                                                <button type="submit" class="btn btn-secondary" name="submit" value="remove">Remove COD</button>
                                                <button type="submit" class="btn btn-danger" name="submit" value="apply">Apply 1% COD</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="row mt-3">
                                    <div class="col-md-6 border-right">
                                        <form action="{{ route('order.courier_info.store', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Courier Name</label>
                                                <select name="courier_name" class="form-control select2 select-down" id="courier_name">
                                                    <option value="0"> -- Select Courier Name -- </option>
                                                    @foreach ($couriers as $courier)
                                                        <option value="{{ $courier->name }}" {{ $order->courier_name == $courier->name ? 'selected' : '' }}>{{ $courier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($order->status->priority_no > 3)
                                                <div class="form-group">
                                                    <label>Reference Code</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" name="refer_code" value="{{ $order->refer_code }}">
                                                    </div>
                                                    @error('refer_code')
                                                        <span class="invalid-feedback" role="alert" style="display: block;">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6 border-right align-content-end">
                                        <form action="{{ route('order.discount_amount.update', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Discount Amount</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" name="discount_amount" value="{{ $order->discount_amount ?? 0 }}">
                                                </div>
                                                @error('discount_amount')
                                                    <span class="invalid-feedback" role="alert" style="display: block;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 border-right">
                                        <form action="{{ route('order.payment.status.change', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Payment Status</label>
                                                <select name="payment_status" class="form-control select2">
                                                    @if (auth()->user()->can('order.paid') || 1 == $order->payment_status)
                                                        <option value="1" {{ $order->payment_status == 1 ? 'selected' : '' }}>Paid</option>
                                                    @endif
                                                    <option value="0" {{ $order->payment_status == 0 ? 'selected' : '' }}>Not Paid</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>

                                        <form action="{{ route('order.advance.payment', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-froup">
                                                <label>Advance Amount</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" name="amount" value="{{ $order->advance }}">
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
                                    <div class="col-md-6">
                                        <form action="{{ route('order.status.change', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Order Status</label>
                                                <select name="order_status_id" class="form-control select2">
                                                    @foreach (App\Models\OrderStatus::where('is_active', 1)->orderBy('priority_no', 'ASC')->get() as $status)
                                                        @if (auth()->user()->can('order.change_all_status'))
                                                            <option value="{{ $status->id }}" {{ $status->id == $order->order_status_id ? 'selected' : '' }}>{{ $status->title }}</option>
                                                        @else
                                                            @if (in_array($status->priority_no, [1, 3, 7, 10]) || $status->id == $order->order_status_id)
                                                                <option value="{{ $status->id }}" {{ $status->id == $order->order_status_id ? 'selected' : '' }}>{{ $status->title }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Reason</label>
                                                <input type="text" name="note" value="{{ $order->note }}" class="form-control">
                                                @error('note')
                                                    <span class="invalid-feedback" role="alert" style="display: block;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </div>
                                                <div class="col-8 text-right">
                                                    @if (1)
                                                        <a href="#add-loss" class="btn btn-primary bg-purple" data-toggle="modal">Add Loss</a>
                                                    @else
                                                        <a href="{{ route('order.remove.loss', $order->id) }}" class="btn btn-primary bg-info">Removed Loss</a>
                                                    @endif
                                                    @if ($order->price > 0)
                                                        <a href="{{ route('order.return', $order->id) }}" class="btn btn-primary bg-primary">Return Products</a>
                                                    @endif
                                                    @if ($order->discount_amount != null)
                                                        <a href="#remove-discount" data-toggle="modal" class="btn btn-primary bg-danger">Remove Discount</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-12">
                                        <form action="{{ route('order.apply.cod', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group" align="right">
                                                <button type="submit" class="btn btn-secondary" name="submit" value="remove">Remove COD</button>
                                                <button type="submit" class="btn btn-danger" name="submit" value="apply">Apply 1% COD</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif


                            <form id="update-products-form" action="{{ route('order.order_products.update', $order->id) }}" method="POST">
                                @csrf
                                <!-- Table row -->
                                <div class="row mt-3">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>S.N</th>
                                                    <th>Product</th>
                                                    @if (!$order->vendor_id)
                                                        <th>Barcode Checking</th>
                                                        <th>Checking Status</th>
                                                    @endif
                                                    <th>Price</th>
                                                    <th>Qty</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $checkedElement = null;
                                                    $all_checked = null;
                                                    $total_qty = 0;
                                                    $cat_text = '';
                                                @endphp
                                                @php
                                                    $k = 0;
                                                @endphp
                                                @foreach ($order->order_product as $key => $order_product)
                                                    @php
                                                        $k += $key;
                                                    @endphp
                                                    @if (!$order_product->is_checked)
                                                        @php
                                                            if ($checkedElement == null) {
                                                                $checkedElement = $key + 1;
                                                            }
                                                        @endphp
                                                    @endif
                                                    @php
                                                        if ($order->source == 'Wholesale') {
                                                            $total_qty += $order_product->qty;
                                                        }
                                                        // $cat_text .= $order_product->product->category->title . ': ' . $order_product->qty . ', ';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>
                                                            @if (auth()->user()->can('update.products') && $order->is_return == 0)
                                                                <div class="selectmain">
                                                                    <select name="product[]" class="select2 select-down" id="" style="width: 90% !important;">
                                                                        @foreach ($products as $product)
                                                                            @if (!is_null($product) && $product->product->is_active)
                                                                                <option value="{{ $product->id }}" {{ $product->product_id == $order_product->product_id && $product->size_id == $order_product->size_id ? 'selected' : '' }}>{{ $product->product->title }} {{ isset($product->size_id) ? ' - ' . $product->size->title : '' }} - {{ env('CURRENCY') . $product->price }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @else
                                                                {{ $order_product->product->title }}{{ isset($order_product->size_id) ? ' - ' . $order_product->size->title : '' }}
                                                            @endif

                                                            @if ($order_product->return_qty != null)
                                                                <span class="text-danger ml-2">({{ $order_product->return_qty }} Product(s) Returned)</span>
                                                            @endif
                                                        </td>
                                                        @if (!$order->vendor_id)
                                                            <td>
                                                                @if ($checkedElement == $key + 1)
                                                                    <input id="order_product_id" type="text" name="order_product_id" value="{{ $order_product->id }}" hidden readonly>
                                                                    <input id="stock_id" type="text" name="stock_id" value="{{ $order_product->stock()->id }}" hidden readonly>

                                                                    <input id="barcode" class="form-control" type="text" name="barcode" placeholder="Add Barcode Here" style="width: 55%">
                                                                @else
                                                                    @if ($checkedElement == null)
                                                                        Checking Done
                                                                        @php
                                                                            if ($key + 1 == $order->order_product->count()) {
                                                                                $all_checked = 1;
                                                                            }
                                                                        @endphp
                                                                    @else
                                                                        Pending to be checked
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($order_product->is_checked)
                                                                    <i class="fas fa-check-circle text-success"></i>
                                                                @else
                                                                    <i class="fas fa-times-circle text-danger"></i>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td>
                                                            @if ($order->source == 'Wholesale')
                                                                &#2547; {{ $order_product->product->variation->wholesale_price }}
                                                            @else
                                                                @if ($order_product->product->variation->discount_price != null && $order->source == 'Website')
                                                                    <s class="text-muted">&#2547; {{ $order_product->product->variation->price }}</s>
                                                                    &#2547; {{ $order_product->product->variation->discount_price }}
                                                                @else
                                                                    &#2547; {{ $order_product->product->variation->price }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (auth()->user()->can('update.products') && $order->is_return == 0)
                                                                <fieldset class="form-group mb-3">
                                                                    <input type="number" name="qty[]" class="form-control" placeholder="Enter Quantity" value="{{ $order_product->qty }}" style="width: 60% !important;">
                                                                    @if (session('qtyError' . $key))
                                                                        <span class="invalid-feedback" role="alert" style="display: block;">
                                                                            <strong>{{ session('qtyError' . $key) }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </fieldset>
                                                            @else
                                                                {{ $order_product->qty }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($order->source == 'Wholesale')
                                                                &#2547; {{ $order_product->product->variation->wholesale_price * $order_product->qty }}
                                                            @else
                                                                @if ($order_product->product->variation->discount_price != null && $order->source == 'Website')
                                                                    &#2547; {{ $order_product->product->variation->discount_price * $order_product->qty }}
                                                                @else
                                                                    &#2547; {{ $order_product->product->variation->price * $order_product->qty }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if (auth()->user()->can('update.products') && $order->is_return == 0)
                                                    <tr>
                                                        <td>{{ $k + 2 }}</td>
                                                        <td>
                                                            <div class="selectmain">
                                                                <select name="product[]" class="select2 select-down" id="" style="width: 90% !important;">
                                                                    <option value="0"> -- Add another product -- </option>
                                                                    @foreach ($products as $product)
                                                                        @if (!is_null($product) && $product->product->is_active && $product->qty > 0)
                                                                            <option value="{{ $product->id }}">{{ $product->product->title }} {{ isset($product->size_id) ? ' - ' . $product->size->title : '' }} - {{ env('CURRENCY') . $product->price }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </td>
                                                        @if (!$order->vendor_id)
                                                            <td></td>
                                                            <td></td>
                                                        @endif
                                                        <td></td>
                                                        <td>
                                                            <fieldset class="form-group mb-3">
                                                                <input type="number" name="qty[]" class="form-control" placeholder="Enter Quantity" value="" style="width: 60% !important;">
                                                            </fieldset>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                @endif
                                                @if ($order->source == 'Wholesale')
                                                    <tr>
                                                        <td @if (!$order->vendor_id) colspan="5" @else colspan="3" @endif align="right">Total Qty:</td>
                                                        <td colspan=""> {{ $total_qty }}</td>
                                                        <td colspan=""></td>
                                                    </tr>
                                                @endif
                                                @if ($order->extra_charge)
                                                    <tr>
                                                        <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">Extra Charge:</td>
                                                        <td>{{ env('CURRENCY') }}{{ $order->extra_charge }}</td>
                                                    </tr>
                                                @endif
                                                @if ($order->delivery_charge)
                                                    <tr>
                                                        <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">Delivery Charge:</td>
                                                        <td>{{ env('CURRENCY') }}{{ $order->delivery_charge == null ? 0 : $order->delivery_charge }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">Discount (-):</td>
                                                    <td>{{ env('CURRENCY') }}{{ $order->discount_amount == null ? 0 : $order->discount_amount }}</td>
                                                </tr>
                                                @if ($order->cod != 0)
                                                    <tr>
                                                        <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">COD (-):</td>
                                                        <td>{{ env('CURRENCY') }}{{ $order->cod }}</td>
                                                    </tr>
                                                @endif
                                                @if ($order->wallet_amount != null)
                                                    <tr>
                                                        <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">Wallet Use:</td>
                                                        <td>{{ env('CURRENCY') }}{{ $order->wallet_amount }}</td>
                                                    </tr>
                                                @endif
                                                @if ($order->points_redeemed)
                                                    <tr>
                                                        <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">Points Redeemed (-):</td>
                                                        <td>{{ $order->points_redeemed }}</td>
                                                    </tr>
                                                @endif
                                                @if ($order->membership_discount)
                                                    <tr>
                                                        <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">Membership Discount (-):</td>
                                                        <td>{{ env('CURRENCY') }}{{ $order->membership_discount }} ({{ $order->discount_rate }}%) </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">Total:</td>
                                                    <td>&#2547; {{ round($order->price + $order->extra_charge + $order->delivery_charge - $order->wallet_amount) }}</td>
                                                </tr>
                                                @if ($order->advance)
                                                    <tr>
                                                        <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">Advanced (-):</td>
                                                        <td>{{ env('CURRENCY') }}{{ $order->advance }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td @if (!$order->vendor_id) colspan="6" @else colspan="4" @endif align="right">Total Payable:</td>
                                                    <td>&#2547; {{ round($order->price + $order->extra_charge + $order->delivery_charge - $order->wallet_amount - $order->advance) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- /.col -->
                                </div>
                                <div class="row justify-content-end">
                                    @if (auth()->user()->can('update.products') && $order->is_return == 0)
                                        <button id="update-products-btn" type="submit" class="mr-4 my-3 btn btn-primary">Update Products </button>
                                    @else
                                        <button disabled type="submit" class="mr-4 my-3 btn btn-primary">Update Products </button>
                                    @endif
                                </div>
                            </form>

                            <!-- /.row -->

                            @if ($all_checked == 1)
                                @if ($order->status->priority_no < 4)
                                    <div class="row justify-content-center mb-2">
                                        <a href="{{ route('order.packet_done', $order->id) }}" class="btn btn-primary btn-sm">Packet Done</a>
                                    </div>
                                @endif
                            @else
                                <div class="row justify-content-center mb-2">
                                    <button class="btn btn-primary btn-sm" disabled>Packet Done</button>
                                </div>
                            @endif


                            <!-- this row will not appear when printing -->
                            <!-- <div class="row no-print">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="col-12">

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href="" class="btn btn-primary float-right" style="margin-right: 5px;"><i class="fas fa-download"></i> Generate PDF</a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div> -->
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- check product Modal -->
    <div class="modal fade" id="check-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Checking Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmSell{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('order.convert.sell', $order->id) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary bg-purple" type="submit"><i class="fas fa-check"></i> Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- add loss Modal -->
    <div class="modal fade" id="add-loss" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Loss</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('expenseentry.loss.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $order->id }}" readonly>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}" required>
                                    @error('date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bank*</label>
                                    <select class="select2 form-control @error('bank_id') is-invalid @enderror" name="bank_id" required>
                                        <option value="">Please select relevant bank</option>
                                        @if (!$order->vendor_id)
                                            @foreach (App\Models\Bank::orderBy('name', 'ASC')->get() as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach
                                        @else
                                            @foreach (App\Models\Bank::orderBy('name', 'ASC')->where('vendor_id', $order->vendor_id)->get() as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('bank_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount*</label>
                                    <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" required>
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input type="text" name="note" class="form-control @error('note') is-invalid @enderror">
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- remove_discount Modal -->
    <div class="modal fade" id="remove-discount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Remove Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="{{ route('order.remove.discount', $order->id) }}" class="btn btn-primary bg-danger">Confirm</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.table-striped').find("input[id=barcode]").focus();
        });

        $('#check-product').on("hidden.bs.modal", function() {
            $('.table-striped').find("input[id=barcode]").focus();
        })

        $('#update-products-form').on('submit', function(e) {
            if ($("#barcode").val().length > 0) {
                e.preventDefault();
            }
        });
    </script>

    <script>
        $("#barcode").keyup(function() {
            var barcode = $(this).val();
            var stock_id = $('#stock_id').val();
            var order_product_id = $('#order_product_id').val();

            if (!isNaN(barcode)) {
                if (barcode.length == 4) {
                    // alert(barcode);
                    url = "{{ route('order.barcode.check') }}";
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            barcode: barcode,
                            stock_id: stock_id,
                            order_product_id: order_product_id,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            $('#check-product').modal('show');
                            $('#check-product .modal-body').html(response);
                            $("#barcode").val('');
                            $("#barcode").focus();
                        }
                    });
                }
            } else {

            }
        });
    </script>

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
