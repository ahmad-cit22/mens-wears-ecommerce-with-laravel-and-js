@extends('admin.layouts.master')
@php
    $business = App\Models\Setting::find(1);
@endphp

@section('style')
    <style>
        .badge-special_status {
            background: rgb(255, 132, 0) !important;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Order Sheet</li>
                        <li class="breadcrumb-item active">Edit Order</li>
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
                        <form action="{{ route('fos.order_info.update', $order->id) }}" method="POST">
                            @csrf
                            <div class="invoice p-3 mb-3">
                                <!-- title row -->
                                <div class="row">
                                    {{-- @if ($order->is_final == 0)
                                        <div class="col-12" align="right">
                                        <a href="#confirmSell{{ $order->id }}" data-toggle="modal" class="btn btn-primary bg-purple"><i class="fas fa-check"></i> Mark as Sold</a>
                                        <hr>
                                    </div>
                                    @endif --}}
                                    <div class="col-10">
                                        <h4>
                                            <img src="{{ asset('images/website/' . $business->footer_logo) }}" width="200">
                                            <small class="float-right" style="float: right;">Date: {{ Carbon\Carbon::parse($order->created_at)->format('d M Y, g:ia') }}</small>
                                        </h4><br>
                                    </div>
                                    <div class="col-2">
                                        <a href="{{ route('fos.index') }}" class="btn btn-info bg-primary">Order Sheet</a>
                                        @if ($order->source == 'Wholesale')
                                            <a class="" href="{{ route('pos.wholesale.create', $order->id) }}" target="_blank"><span class="btn btn-success float-right" style="">POS<i class="ml-2 fas fa-angle-double-right"></i></span></a>
                                        @else
                                            <a class="" href="{{ route('pos.create', $order->id) }}" target="_blank"><span class="btn btn-success float-right" style="">POS<i class="ml-2 fas fa-angle-double-right"></i></span></a>
                                        @endif
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
                                                <strong class=" ml-1"># {{ $order->code }}</strong>
                                            @else
                                                <strong class="text-warning ml-1">N/A</strong>
                                            @endif
                                            <br>
                                            <div>
                                                Status: <span class="ml-1 fs-5 p-2 my-2 badge badge-{{ $order->status->color }}"> {{ $order->status->title }}</span><br>
                                                Special Status: <span class="ml-1 fs-5 p-2 mb-2 badge badge-{{ $order->special_status->color }}"> {{ $order->special_status->title }}</span>
                                            </div>
                                            Created By: @if ($order->created_by)
                                                <strong class="ml-1"><a href="{{ route('user.edit', $order->created_by->user_id) }}">{{ $order->created_by->adder->name }}</a> ({{ $order->created_by->created_at->format('d M, Y - g:i A') }})</strong><br>
                                            @else
                                                N/A <br>
                                            @endif

                                        </address>
                                    </div>
                                    <div class="col-12 invoice-col">

                                        <address>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="text-body">Memo Code</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="code" class="form-control" placeholder="Enter Memo Code" value="{{ $order->code }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-body">Customer Name</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="name" class="form-control" placeholder="Enter Customer Name" value="{{ $order->name }}">
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-body">Email</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="email" name="email" class="form-control" placeholder="Enter E-mail" value="{{ $order->email }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-body">Phone</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number" value="{{ $order->phone }}">
                                                        @error('phone')
                                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-body">Whatsapp Number</label>
                                                    <fieldset class="form-group mb-3">
                                                        <input type="text" name="whatsapp_num" class="form-control" placeholder="Enter Customer Whatsapp Number" value="{{ $order->whatsapp_num }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-body">Customer Address</label>
                                                    <fieldset class="form-group mb-3">
                                                        <textarea type="text" class="form-control" placeholder="Enter Customer Address" name="shipping_address" required>{{ $order->shipping_address }}</textarea>
                                                        @error('shipping_address')
                                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
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
                                                        @error('source')
                                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-6">
                                                        <div class="selectmain">
                                                            <label class="text-dark d-flex">Courier Name</label>
                                                            <select name="courier_id" class="select2 select-down" id="courier_id" style="width: 100% !important;">
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
                                                            <select name="order_status_id" class="select2 select-down" id="order_status_id" style="width: 100% !important;">
                                                                <option value="0">--- Select an Option ---</option>
                                                                @foreach ($statuses as $status)
                                                                    <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>{{ $status->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('order_status_id')
                                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-6">
                                                        <div class="selectmain">
                                                            <label class="text-dark d-flex">Special Status</label>
                                                            <select name="special_status_id" class="select2 select-down" id="special_status_id" style="width: 100% !important;">
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
                                        <label class="text-body">Note
                                            @if ($order->special_status_id == 6)
                                                <span class="p-1 pt-2 ml-1 px-2 badge badge-special_status">*</span>
                                            @endif
                                        </label>
                                        <fieldset class="form-group">
                                            <textarea name="note" id="note" class="form-control" placeholder="Add Notes Here">{{ $order->note }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-body">Remarks
                                            @if ($order->special_status_id == 2 || $order->special_status_id == 3 || $order->special_status_id == 6 || $order->special_status_id == 7)
                                                <span class="p-1 pt-2 ml-1 px-2 badge badge-special_status">*</span>
                                            @endif
                                        </label>
                                        <fieldset class="form-group">
                                            <textarea name="remarks" id="remarks" class="form-control" placeholder="Add Remarks Here">{{ $order->remarks }}</textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Advance Amount</label>
                                        @if ($order->special_status_id == 3)
                                            <span class="p-1 pt-2 ml-1 px-2 badge badge-special_status">*</span>
                                        @endif
                                        <input type="number" class="form-control" name="advance" id="advance" value="{{ $order->advance }}" placeholder="Add Advance Amount Here">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Discount Amount</label>
                                        @if ($order->special_status_id == 2)
                                            <span class="p-1 pt-2 ml-1 px-2 badge badge-special_status">*</span>
                                        @endif
                                        <input type="number" class="form-control" name="discount_amount" value="{{ $order->discount_amount }}" placeholder="Add Discount Amount Here">
                                    </div>
                                </div>


                                </address>
                            </div>
                            <div class="row justify-content-end">
                                <button type="submit" class="mr-2 mb-3 btn btn-primary">Save Changes</button>
                            </div>
                        </form>

                        <form action="{{ route('fos.bkash_info.update', $order->id) }}" method="POST">
                            @csrf
                            <div class="invoice p-3 mb-3">
                                <div class="row mt-5 p-3" style="border: 1px solid rgba(255, 162, 0, 0.417); border-radius: 20px; background: rgba(255, 153, 0, 0.07);">
                                    <div class="col-md-4">
                                        <label class="text-body">Bkash Number (Customer)</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="text" name="bkash_num" class="form-control" placeholder="Enter Customer Bkash Number" value="{{ $order->bkash_num }}">
                                            @error('bkash_num')
                                                <span class="invalid-feedback" role="alert" style="display: block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="selectmain">
                                            <label class="text-dark d-flex">Business Bkash Number</label>
                                            <select name="bkash_business_id" class="select2 select-down" id="bkash_business_id" style="width: 100% !important;">
                                                <option value="">--- Select an Option ---</option>
                                                @foreach ($bkash_nums as $num)
                                                    <option value="{{ $num->id }}" {{ $order->bkash_business_id == $num->id ? 'selected' : '' }}>{{ $num->number . ' (' . $num->name . ')' }}</option>
                                                @endforeach
                                            </select>
                                            @error('bkash_business_id')
                                                <span class="invalid-feedback" role="alert" style="display: block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-body">Bkash Amount</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="number" name="bkash_amount" id="bkash_amount" class="form-control" placeholder="Enter Bkash Amount" value="{{ $order->bkash_amount }}">
                                            @error('bkash_amount')
                                                <span class="invalid-feedback" role="alert" style="display: block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="selectmain">
                                            <label class="text-dark d-flex">Purpose of Bkash</label>
                                            <select name="bkash_purpose_id" class="select2 select-down" id="bkash_purpose_id" style="width: 100% !important;">
                                                <option value="">--- Select an Option ---</option>
                                                @foreach ($bkash_purposes as $purpose)
                                                    @if ($order->bkash_record)
                                                        <option value="{{ $purpose->id }}" {{ $order->bkash_record->bkash_purpose->id == $purpose->id ? 'selected' : '' }}>{{ $purpose->title }}</option>
                                                    @else
                                                        <option value="{{ $purpose->id }}">{{ $purpose->title }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('bkash_purpose_id')
                                                <span class="invalid-feedback" role="alert" style="display: block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-body">Bkash Note/Comments
                                        </label>
                                        <fieldset class="form-group">
                                            <textarea name="bkash_note" id="bkash_note" class="form-control" placeholder="Add Notes/Comments about Bkash Transaction">{{ $order->bkash_record ? $order->bkash_record->comments : '' }}</textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="mt-3 row justify-content-end">
                                    <button type="submit" class="mr-2 mb-3 btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">


                    </div>

                    <!-- Table row -->
                    <form action="{{ route('fos.order_products.update', $order->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>
                                            <th width="50%">Product Title - Size - Price
                                                @if ($order->special_status_id == 4 || $order->special_status_id == 5)
                                                    <span class="p-1 pt-2 ml-1 px-2 badge badge-special_status">*</span>
                                                @endif
                                            </th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- a --}}
                                        @php
                                            $k = 0;
                                        @endphp
                                        @foreach ($order->order_product as $key => $order_product)
                                            @php
                                                $k += $key;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                @if ($order_product->product != null)
                                                    <td>
                                                        <div class="selectmain">
                                                            <select name="product[]" class="select2 select-down" id="" style="width: 60% !important;">
                                                                @foreach ($products as $product)
                                                                    @if (!is_null($product) && $product->product->is_active)
                                                                        <option value="{{ $product->id }}" {{ $product->product->id == $order_product->product->id && $product->size_id == $order_product->size_id ? 'selected' : '' }}>{{ $product->product->title }} {{ isset($product->size_id) ? ' - ' . $product->size->title : '' }} - {{ env('CURRENCY') . $product->price }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                @else
                                                    <td> -- </td>
                                                @endif
                                                <td>{{ env('CURRENCY') }}{{ $order_product->price }}</td>
                                                <td>
                                                    {{-- @php
                                                        print_r(session()->all());
                                                    @endphp --}}
                                                    <fieldset class="form-group mb-3">
                                                        <input type="number" name="qty[]" class="form-control" placeholder="Enter Quantity" value="{{ $order_product->qty }}" style="width: 40% !important;">
                                                        @if (session('qtyError' . $key))
                                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                                                <strong>{{ session('qtyError' . $key) }}</strong>
                                                            </span>
                                                        @endif
                                                    </fieldset>
                                                </td>
                                                <td>{{ env('CURRENCY') }}{{ $order_product->price * $order_product->qty }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>{{ $k + 2 }}</td>
                                            @if ($order_product->product != null)
                                                <td>
                                                    <div class="selectmain">
                                                        <select name="product[]" class="select2 select-down" id="" style="width: 60% !important;">
                                                            <option value="0"> -- Add another product -- </option>
                                                            @foreach ($products as $product)
                                                                @if (!is_null($product) && $product->qty > 0)
                                                                    <option value="{{ $product->id }}">{{ $product->product->title }} {{ isset($product->size_id) ? ' - ' . $product->size->title : '' }} - {{ env('CURRENCY') . $product->price }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                            @else
                                                <td> -- </td>
                                            @endif
                                            <td></td>
                                            <td>
                                                <fieldset class="form-group mb-3">
                                                    <input type="number" name="qty[]" class="form-control" placeholder="Enter Quantity" value="" style="width: 40% !important;">
                                                </fieldset>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="right">Discount (-):</td>
                                            <td>{{ env('CURRENCY') }}{{ $order->discount_amount == null ? 0 : $order->discount_amount }}</td>
                                        </tr>

                                        <tr>
                                            <td colspan="4" align="right">Total:</td>
                                            <td style="font-weight: bold">{{ env('CURRENCY') }}{{ round($order->price) }}</td>
                                        </tr>
                                        @if ($order->advance)
                                            <tr>
                                                <td colspan="4" align="right">Advanced (-):</td>
                                                <td>{{ env('CURRENCY') }}{{ $order->advance }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="4" align="right">Total Payable:
                                                @if ($order->special_status_id == 2 || $order->special_status_id == 3)
                                                    <span class="p-1 pt-2 ml-1 px-2 badge badge-special_status">*</span>
                                                @endif
                                            </td>
                                            <td style="font-weight: bold">{{ env('CURRENCY') }}{{ round($order->price - $order->discount_amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row justify-content-end">
                            <button type="submit" class="mr-4 my-3 btn btn-primary">Save Changes</button>
                        </div>
                    </form>
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

        // $("#advance").keyup(function() {
        //     let advance = $(this).val();

        //     if (advance > 0) {
        //         $('#bkash_amount').val(advance);
        //     } else {
        //         $('#bkash_amount').val(0);
        //     }
        // });
    </script>
@endsection
