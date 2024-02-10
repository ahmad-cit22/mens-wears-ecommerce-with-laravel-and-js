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
                    <h4 class="m-0 mt-2">Product Checking for Order Packaging</h4>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Order Packaging</li>
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


                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Product</th>
                                        <th>Barcode Checking</th>
                                        <th>Checking Status</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $checkedElement = null;
                                    @endphp
                                    @foreach ($order->order_product as $key => $product)
                                        @if (!$product->is_checked)
                                            @php
                                                if ($checkedElement == null) {
                                                    $checkedElement = $key + 1;
                                                }
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $product->product->title }}{{ isset($product->size_id) ? ' - ' . $product->size->title : '' }} - {{ $product->stock()->barcode ? '(Barcode - ' . $product->stock()->barcode . ')' : '' }}
                                                @if ($product->return_qty != null)
                                                    <span class="text-danger ml-2">({{ $product->return_qty }} Product(s) Returned)</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($checkedElement == $key + 1)
                                                    {{-- <form action="" method="POST"> --}}
                                                    <input id="order_product_id" type="text" name="order_product_id" value="{{ $product->id }}" hidden readonly>
                                                    <input id="stock_id" type="text" name="stock_id" value="{{ $product->stock()->id }}" hidden readonly>
                                                    <input id="barcode" class="form-control" type="text" name="barcode" placeholder="Add Barcode Here" style="width: 48%">
                                                    {{-- </form> --}}
                                                @else
                                                    @if ($checkedElement == null)
                                                        Checking Done
                                                    @else
                                                        Pending to be checked
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->is_checked)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-danger"></i>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.table-striped').find("input").focus();
        });

        $('#check-product').on("hidden.bs.modal", function() {
            $('.table-striped').find("input").focus();
        })
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
@endsection
