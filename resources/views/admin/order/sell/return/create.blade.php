@extends('admin.layouts.master')
@php
    $business = App\Models\Setting::find(1);
@endphp

@section('style')
    <style>
        .return_qty {
            display: none;
        }
    </style>
@endsection

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
                                        <img src="{{ asset('images/website/' . $business->footer_logo) }}" width="200">
                                        <small class="float-right" style="float: right;">Date: {{ Carbon\Carbon::parse($order->created_at)->format('d M Y, g:ia') }}</small>
                                    </h4><br>
                                </div><br>

                                <hr style="color: #800020;">
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-md-5 invoice-col">

                                    <address>
                                        Name: <strong>{{ $order->name }}</strong><br>
                                        Phone: <strong>{{ $order->phone }}</strong><br>
                                        @if ($order->email != null)
                                            Email: {{ $order->email }}<br>
                                        @endif
                                        Shipping Address: {{ $order->shipping_address }}, {{ optional($order->area)->name }}, {{ optional($order->district)->name }}<br>
                                        @if ($order->courier_name)
                                            Courier Name: {{ $order->courier_name }}<br>
                                        @endif
                                        Reference Code: {{ $order->refer_code }}
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
                                        Order Source: {{ $order->source }}
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

                                        @if ($order->order_paid_by)
                                            Paid By: <strong class="ml-1"><a href="{{ route('user.edit', $order->order_paid_by->user_id) }}">{{ $order->order_paid_by->adder->name }}</a> ({{ $order->order_paid_by->created_at->format('d M, Y - g:i A') }}) </strong><br>
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
                                <div class="row mt-3">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>S.N</th>
                                                    <th>Product</th>
                                                    <th>Barcode Checking</th>
                                                    <th>Price</th>
                                                    <th>Qty</th>
                                                    <th>Return Qty</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $checkedElement = null;
                                                    $all_checked = null;
                                                @endphp
                                                @foreach ($order->order_product as $key => $product)
                                                    @php
                                                        $return_products = App\Models\OrderReturn::where('order_id', $product->order_id)
                                                            ->where('product_id', $product->product_id)
                                                            ->where('size_id', $product->size_id)
                                                            ->get();
                                                    @endphp
                                                    @if (!$product->is_checked)
                                                        @php
                                                            if ($checkedElement == null) {
                                                                $checkedElement = $key + 1;
                                                            }
                                                        @endphp
                                                    @endif
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $product->product->title }}{{ isset($product->size_id) ? ' - ' . $product->size->title : '' }}
                                                            @if ($product->return_qty != null)
                                                                <span class="text-danger ml-2">({{ $product->return_qty }} Product(s) Returned)</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($product->qty > 0)
                                                                <input id="order_product_id" type="text" name="order_product_id" value="{{ $product->id }}" hidden readonly>
                                                                <input id="stock_id" type="text" name="stock_id" value="{{ $product->stock()->id }}" hidden readonly>

                                                                <input id="barcode-{{ $product->id }}" class="form-control barcode" type="text" name="barcode" placeholder="Add Barcode Here" style="width: 55%" onkeyup="checkBarcode({{ $product->id }}, {{ $product->stock()->id + 1000 }}, $(this).val())">
                                                            @else
                                                                --
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($order->source == 'Wholesale')
                                                                &#2547; {{ $product->product->variation->wholesale_price }}
                                                            @else
                                                                &#2547; {{ $product->product->variation->price }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $product->qty }}</td>
                                                        <td>
                                                            <input id="return_qty-{{ $product->id }}" type="number" name="qty[]" class="form-control return_qty" min="1" max="{{ $product->qty - $return_products->sum('qty') }}">
                                                            <span class="text-danger">{{ $return_products->sum('qty') }} Product(s) Returned</span>

                                                            <input type="hidden" name="product_id[]" value="{{ $product->product_id }}">
                                                            <input type="hidden" name="size_id[]" value="{{ $product->size_id }}">
                                                            <input type="hidden" name="price[]" value="{{ $product->price }}">
                                                        </td>
                                                        <td>
                                                            @if ($order->source == 'Wholesale')
                                                                &#2547; {{ $product->product->variation->wholesale_price * $product->qty }}
                                                            @else
                                                                &#2547; {{ $product->product->variation->price * $product->qty }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="6" align="right">Delivery Charge:</td>
                                                    <td>{{ env('CURRENCY') }}{{ $order->delivery_charge == null ? 0 : $order->delivery_charge }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" align="right">Discount (-):</td>
                                                    <td>{{ env('CURRENCY') }}{{ $order->discount_amount == null ? 0 : $order->discount_amount }}</td>
                                                </tr>
                                                @if ($order->cod != 0)
                                                    <tr>
                                                        <td colspan="6" align="right">COD (-):</td>
                                                        <td>{{ env('CURRENCY') }}{{ $order->cod }}</td>
                                                    </tr>
                                                @endif
                                                @if ($order->wallet_amount != null)
                                                    <tr>
                                                        <td colspan="6" align="right">Wallet Use:</td>
                                                        <td>{{ env('CURRENCY') }}{{ $order->wallet_amount }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="6" align="right">Total:</td>
                                                    <td>&#2547; {{ round($order->price + $order->delivery_charge - $order->wallet_amount) }}</td>
                                                </tr>
                                                @if ($order->advance)
                                                    <tr>
                                                        <td colspan="6" align="right">Advanced (-):</td>
                                                        <td>{{ env('CURRENCY') }}{{ $order->advance }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="6" align="right">Total Payable:</td>
                                                    <td>&#2547; {{ round($order->price + $order->delivery_charge - $order->wallet_amount - $order->advance) }}</td>
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
        // $(document).ready(function() {
        //     $('.table-striped').find("input").focus();
        // });

        // $('#check-product').on("hidden.bs.modal", function() {
        //     $('.table-striped').find("input").focus();
        // })
    </script>

    <script>
        // $(".barcodesdfsdf").keyup(function() {
        //     var barcode = $(this).val();
        //     // alert(barcode);
        //     var stock_id = $('#stock_id').val();
        //     var order_product_id = $('#order_product_id').val();

        //     if (!isNaN(barcode)) {
        //         if (barcode.length == 4) {
        //             // alert(barcode);
        //             url = "{{ route('order.barcode.check') }}";
        //             $.ajax({
        //                 url: url,
        //                 type: "POST",
        //                 data: {
        //                     barcode: barcode,
        //                     stock_id: stock_id,
        //                     order_product_id: order_product_id,
        //                     _token: '{{ csrf_token() }}',
        //                 },
        //                 success: function(response) {
        //                     $('#check-product').modal('show');
        //                     $('#check-product .modal-body').html(response);
        //                     $("#barcode").val('');
        //                     $("#barcode").focus();
        //                 }
        //             });
        //         }
        //     } else {

        //     }
        // });
    </script>

    <script>
        function checkBarcode(order_product_id, order_product_code, barcode) {
            // var return_qty_element = $('.return_qty-' + order_product_id);
            if (!isNaN(barcode)) {
                if (barcode.length == 4) {
                    if (barcode == order_product_code) {
                        // alert(barcode);
                        $('#return_qty-' + order_product_id).show();
                        $('#barcode-' + order_product_id).val('');

                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Product Matched Successfully!",
                            showConfirmButton: false,
                            timer: 1200
                        });
                    } else {
                        $('#return_qty-' + order_product_id).hide();
                        $('#barcode-' + order_product_id).val('');

                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Product Not Matched! Try Again.",
                            showConfirmButton: false,
                            timer: 1200
                        });
                    }
                }
            }
            // alert(3333);
        }
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
