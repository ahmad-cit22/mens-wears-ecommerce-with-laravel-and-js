@php
    $business = App\Models\Setting::find(1);
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>Invoice - {{ $order->code }}</title>
    <style type="text/css">
        #invoice-POS {
            padding: 2mm;
            margin: 0 auto;
            width: 44mm;
            background: #FFF;
        }

        h1 {
            font-size: 1.5em;
            color: #222;
        }

        h2 {
            font-size: .9em;
        }

        h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }

        p {
            font-size: .7em;
            line-height: 1.2em;
        }

        #top,
        #mid,
        #bot {
            /* Targets all id with 'col-' */
            border-bottom: 1px solid #EEE;
        }

        /*#top{min-height: 100px;}*/
        #mid {
            min-height: 80px;
        }

        #bot {
            min-height: 50px;
        }

        #top .logo {
            //float: left;
            /*height: 60px;*/
            /*width: 60px;*/
        }

        .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
            border-radius: 50px;
        }

        .info {
            display: block;
            //float:left;
            margin-left: 0;
        }

        .title {
            float: right;
        }

        .title p {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            //padding: 5px 0 5px 15px;
            //border: 1px solid #EEE
        }

        .tabletitle {
            //padding: 5px;
            font-size: .7em;
            background: #EEE;
        }

        .service {
            border-bottom: 1px solid #EEE;
        }

        .item {
            width: 24mm;
        }

        .itemtext {
            font-size: .7em;
        }

        #legalcopy {
            margin-top: 5mm;
        }
    </style>
</head>

<body>

    <div id="invoice-POS">

        <center id="top">
            <div class="logo">
                <img src="{{ asset('images/website/black-logo.jpeg') }}" alt="logo" width="100%;">
            </div>
            <div class="info">
                <!--<h2>Go By Fabrifest</h2>-->
            </div>
            <!--End Info-->
        </center>
        <!--End InvoiceTop-->

        <div id="mid">
            <div class="info">
                <p>
                    Order ID : <b>{{ $order->code }}</b></br>
                    Name : {{ $order->name }}</br>
                    Phone : <b>{{ $order->phone }}</b></br>
                    Email : {{ $order->email }}</br>
                    Address : {{ $order->shipping_address }}, {{ optional($order->area)->name }}, {{ optional($order->district)->name }}</br>
                    @if ($order->courier_name)
                        Courier Name: {{ $order->courier_name }}<br>
                    @endif
                    Date : {{ Carbon\Carbon::parse($order->created_at)->format('d M Y, g:ia') }}</br>

                </p>
            </div>
        </div>
        <!--End Invoice Mid-->

        <div id="bot">

            <div id="table">
                <table>
                    <tr class="tabletitle">
                        <td class="item">
                            <h2>Item</h2>
                        </td>
                        <td class="Hours">
                            <h2>Qty</h2>
                        </td>
                        <td class="Rate">
                            <h2>Price</h2>
                        </td>
                    </tr>

                    @foreach ($order->order_product as $product)
                        <tr class="service">
                            <td class="tableitem">
                                <p class="itemtext">{{ $product->product->title }}{{ isset($product->size_id) ? ' - ' . $product->size->title : '' }}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">{{ $product->qty }}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">{{ env('CURRENCY') }}{{ $product->price }}</p>
                            </td>
                        </tr>
                    @endforeach



                    <tr class="tabletitle">

                        <td class="Rate" colspan="2" style="text-align: center;">
                            <h2>Shipping Charge</h2>
                        </td>
                        <td class="payment">
                            <h2>{{ env('CURRENCY') }}{{ $order->delivery_charge }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">

                        <td class="Rate" colspan="2" style="text-align: center;">
                            <h2>Advanced (-)</h2>
                        </td>
                        <td class="payment">
                            <h2>{{ env('CURRENCY') }}{{ $order->advance }}</h2>
                        </td>
                    </tr>
                    @if ($order->discount_amount)
                        <tr class="tabletitle">

                            <td class="Rate" colspan="2" style="text-align: center;">
                                <h2>Discount (-)</h2>
                            </td>
                            <td class="payment">
                                <h2>{{ env('CURRENCY') }}{{ $order->discount_amount }}</h2>
                            </td>
                        </tr>
                    @endif
                    <tr class="tabletitle">
                        <td class="Rate" colspan="2" style="text-align: center;">
                            <h2>Total</h2>
                        </td>
                        <td class="payment">
                            <h2>{{ env('CURRENCY') }}{{ round($order->price + $order->delivery_charge - $order->wallet_amount - $order->advance) }}</h2>
                        </td>
                    </tr>

                </table>
            </div>
            <!--End Table-->

            @if ($order->note)
                <div class="info mb-1">
                    <p class="" style="font-style: italic; font-size: 11px">
                        Note : {{ $order->note }}
                    </p>
                </div>
            @endif

            <div id="legalcopy">
                <p class="legal"><strong>Thank you for your shopping!</strong>  .
                </p>
            </div>
        </div>
        <!--End InvoiceBot-->
    </div>
    <!--End Invoice-->

</body>

</html>
