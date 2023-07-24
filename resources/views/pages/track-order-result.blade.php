@extends('pages.layouts.master')

@section('title')
    {{ 'Order Status' . ' | ' . $settings->name }}
@endsection

@section('content')
    <div class="shop-area section-padding-3 pt-70">
        <div class="container-fluid">
            <div class="order-success text-center font-weight-bolder text-dark" style="padding-top: 50px;">
                @if (Route::currentRouteName() == 'order.complete')
                    <h2><i class="fa fa-check"></i>
                        Thank you. Your order has been received.</h2>
                @endif
            </div>

            <!-- End of Order Success -->

            <ul class="order-view list-style-none">
                <li>
                    <label>Order number</label>
                    <strong>{{ $order->code }}</strong>
                </li>
                <li>
                    <label>Status</label>
                    <strong>{{ optional($order->status)->title }}</strong>
                </li>
                <li>
                    <label>Date</label>
                    <strong>{{ Carbon\Carbon::parse($order->created_at)->format('d M Y, g:ia') }}</strong>
                </li>
                <!-- <li>
                        <label>Total</label>
                        <strong>450</strong>
                    </li> -->
                <li>
                    <label>Payment Method</label>
                    <strong>{{ $order->payment_method }}</strong>
                </li>
            </ul>
            <!-- End of Order View -->
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="order-details-wrapper mb-5">
                        <h4 class="title text-uppercase ls-25 mb-5">Order Details</h4>
                        <table class="table table-bordered table-hover dataTable">
                            <thead>
                                <tr>
                                    <th class="text-dark">Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $total;
                                @endphp
                                @foreach ($order->order_product as $product)
                                    @php
                                        $total += $product->price * $product->qty;
                                        $total;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $product->product->title }}{{ isset($product->size_id) ? ' - ' . $product->size->title : '' }}
                                        </td>
                                        <td>{{ $product->qty }} </td>
                                        <td>{{ env('CURRENCY') }}{{ $product->price * $product->qty }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" align="right"><b>Subtotal</b></td>
                                    <td>{{ env('CURRENCY') }}{{ $total }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right"><b>Delivery Charge</b></td>
                                    <td>{{ env('CURRENCY') }}{{ $order->delivery_charge }}</td>
                                </tr>
                                @if ($order->discount_amount > 0)
                                    <tr>
                                        <td colspan="2" align="right"><b>Discount(-)</b></td>
                                        <td>{{ env('CURRENCY') }}{{ $order->discount_amount }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2" align="right"><b>Total</b></td>
                                    <td>{{ env('CURRENCY') }}{{ $total - $order->discount_amount + $order->delivery_charge }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- End of Order Details -->
        </div>
    </div>
@endsection
