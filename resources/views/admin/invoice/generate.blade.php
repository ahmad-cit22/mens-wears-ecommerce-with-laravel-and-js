@php
    $business = App\Models\Setting::find(1);
@endphp
<!DOCTYPE HTML>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title></title>

    <style>
        li {
            list-style: none;
            float: left;
            overflow: hidden;
        }

        p {
            font-size: 13px;
        }

        .customar_info {
            width: 100%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid black;
            text-align: left;
            padding: 5px;
            font-size: 13px;
        }

        .invoiceIDandDate {
            text-align: right;

        }

        .clientInfo {
            background-color: red;
        }
    </style>
</head>

<body>


    <div>
        <div>
            <img src="{{ asset('images/website/' . $business->footer_logo) }}" class="center">
        </div>
        <table>
            <tr>
                <th style="border: 0px solid white;">
                    <div>
                        <p>
                            Bill To, <br>
                            Name: {{ $order->name }}<br>
                            Phone: {{ $order->phone }}<br>
                            Email: {{ $order->email }}<br>
                            Shipping Address: {{ $order->shipping_address }}, {{ optional($order->area)->name }}, {{ optional($order->district)->name }}<br>
                        </p>
                    </div>
                </th>
                <th style="text-align: right; border: 0px solid white;">
                    <p class="invoiceIDandDate" style="font-family: Arial;">Invoice # {{ $order->code }}<br>Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</p>
                </th>
            </tr>
        </table>
    </div>
    <br />

    <div>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr style="text-align: right; background-color: #dddddd;">
                    <!-- <th scope="col" style="text-align: center;">S.N</th> -->
                    <th width="50px" style="text-align: left;">Product Name</th>
                    <th scope="col" style="text-align: center;">Quantity</th>
                    <th scope="col" style="text-align: right;">Price</th>
                    <!-- <th scope="col" style="text-align: right;"> Sub Total</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach ($order->order_product as $product)
                    <tr style="text-align: right;">
                        <!-- <th scope="row" style="text-align: center;">{{ $loop->index + 1 }}</th> -->
                        <td width="350px" style="text-align: left;">{{ $product->product->title }}{{ isset($product->size_id) ? ' - ' . $product->size->title : '' }}
                        </td>
                        <td style="text-align: center;">{{ $product->qty }}</td>
                        <td style="text-align: right;">{{ $product->price }}/-</td>
                        <!-- <td style="text-align: right;"><span>{{ $product->price * $product->qty }}/-</span> -->

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    <div style="margin-top: 8px; text-align: right;">
        <table>
            <tbody style="text-align: right;">
                <tr style="text-align: right;">
                    <td style="border-bottom: 1px solid white; border-left: 1px solid white; border-top: 1px solid white; text-align: right;"><b>Sub Total</b></td>
                    <td style="text-align: right;">{{ $order->price + $order->discount_amount }}/-</td>
                </tr>

                <tr style="text-align: right;">
                    <td style="border-bottom: 1px solid white; border-left: 1px solid white; border-top: 1px solid white; text-align: right;"><b>Delivery Charge</b></td>
                    <td style="text-align: right;">{{ $order->delivery_charge == null ? 0 : $order->delivery_charge }}/-</td>
                </tr>

                @if ($order->wallet_amount != null)
                    <tr style="text-align: right;">
                        <td style="border-bottom: 1px solid white; border-left: 1px solid white; border-top: 1px solid white; text-align: right;"><b>Wallet Use:</b></td>
                        <td style="text-align: right;">{{ $order->wallet_amount }}/-</td>
                    </tr>
                @endif

                @if ($order->discount_amount > 0)
                    <tr style="text-align: right;">
                        <td style="border-bottom: 1px solid white; border-left: 1px solid white; border-top: 1px solid white; text-align: right;"><b>Discount(-):</b></td>
                        <td style="text-align: right;">{{ $order->discount_amount }}/-</td>
                    </tr>
                @endif

                <tr style="text-align: right;">
                    <td style="border-bottom: 1px solid white; border-left: 1px solid white; border-top: 1px solid white; text-align: right;"><b>Total</b></td>
                    <td style="text-align: right;">{{ $order->price + $order->delivery_charge - $order->wallet_amount }}/-</td>
                </tr>

                <tr style="text-align: right;">
                    <td style="border-bottom: 1px solid white; border-left: 1px solid white; border-top: 1px solid white; text-align: right;"><b>Total Payable</b></td>
                    <td style="text-align: right;">{{ $order->price + $order->delivery_charge - $order->wallet_amount - $order->advance }}/-</td>
                </tr>

            </tbody>
        </table>
    </div>

    @if ($order->payment_method != null)
        <p><b>Payment Method:</b>&nbsp;&nbsp; {{ $order->payment_method }}</p>
    @endif

    @if ($order->note != null)
        <div class="info" style="margin-top: 25px">
            <p class="" style="font-style: italic; font-size: 11px; margin-left: 5px;">
                Note : {{ $order->note }}
            </p>
        </div>
    @endif

</body>

</html>
