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
            width: 70mm;
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
            text-align: center !important;
            //padding: 5px 0 5px 15px;
            //border: 1px solid #EEE
        }

        .payment {
            text-align: right !important;
            padding-right: 20px;
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
            width: 25mm;
        }

        .item-code {
            width: 35mm;
        }

        .itemtext {
            font-size: .7em;
        }

        #legalcopy {
            margin-top: 5mm;
        }

        /* @page {
            size: 6in 9in;
        } */

        /* @page {
            size: 10in 9in;
        }*/
        html,
        body {
            /* width: 80mm !important; */
            /* margin: 0 !important; */
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        @media print {



            html,
            body {
                /* width: 80mm !important; */
                /* margin: 0 !important; */
                font-family: sans-serif;
            }
        }
    </style>
</head>

<body>

    <div id="invoice-POS">

        <center id="top">
            <div class="logo">
                <img src="{{ asset('images/website/black-logo.jpeg') }}" alt="logo" width="70%;">
                <p class="text-center" style="margin-top: 0 !important">Intense Quality</p>
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
                    Date : {{ Carbon\Carbon::parse($order->created_at)->format('d M Y, g:ia') }}</br>
                    Invoice No. : <b>{{ $order->code }}</b><br>
                    Sales Person : <b>{{ $order->sold_by }}</b><br><br>
                    Customer Name : {{ $order->name }} </br>
                    Phone No. : <b>{{ $order->phone }}</b><br>
                    @if ($order->membership_card_no)
                        Membership Card No. : {{ $order->membership_card_no }} <br>
                    @endif

                </p>
            </div>
        </div>
        <!--End Invoice Mid-->

        <div id="bot">

            <div id="table">
                <table>
                    <tr class="tabletitle">
                        <td class="item-code">
                            <h2>Item & Code</h2>
                        </td>
                        <td class="item text-center">
                            <h2>Size</h2>
                        </td>
                        <td class="item">
                            <h2>Qty</h2>
                        </td>
                        <td class="item payment">
                            <h2>Amount</h2>
                        </td>
                    </tr>

                    @php
                        $total_qty = 0;
                    @endphp
                    @foreach ($order->order_product as $product)
                        @php
                            if ($order->source == 'Wholesale') {
                                $total_qty += $product->qty;
                            }
                        @endphp
                        <tr class="service">
                            <td class="item">
                                <p class="itemtext">{{ $product->product->title }}</p>
                            </td>
                            <td class="item">
                                <p class="itemtext">{{ isset($product->size_id) ? $product->size->title : '' }}</p>
                            </td>
                            <td class="item">
                                <p class="itemtext">{{ $product->qty }}</p>
                            </td>
                            <td class="item payment">
                                <p class="itemtext">
                                    @if ($order->source == 'Wholesale')
                                        &#2547; {{ $product->product->variation->wholesale_price }}
                                    @else
                                        @if ($product->product->variation->discount_price != null && $order->source == 'Website')
                                            &#2547; {{ $product->product->variation->discount_price * $product->qty }} <small>(Discounted)</small>
                                        @else
                                            &#2547; {{ $product->product->variation->price * $product->qty }}
                                        @endif
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforeach

                    @if ($order->extra_charge)
                        <tr class="tabletitle">

                            <td class="item" colspan="2" style="text-align: center;">
                                <h2>Extra Charge ({{ $order->extra_charge_type }})</h2>
                            </td>
                            <td></td>
                            <td class="payment">
                                <h2>{{ env('CURRENCY') }}{{ $order->extra_charge }}</h2>
                            </td>
                        </tr>
                    @endif
                    @if ($order->discount_amount)
                        <tr class="tabletitle">

                            <td class="item" colspan="2" style="text-align: center;">
                                <h2>Discount (-)</h2>
                            </td>
                            <td></td>
                            <td class="payment">
                                <h2>&#2547; {{ $order->discount_amount }}</h2>
                            </td>
                        </tr>
                    @endif
                    @if ($order->cod)
                        <tr class="tabletitle">

                            <td class="item" colspan="2" style="text-align: center;">
                                <h2>COD (-)</h2>
                            </td>
                            <td></td>
                            <td class="payment">
                                <h2>&#2547; {{ $order->cod }}</h2>
                            </td>
                        </tr>
                    @endif
                    {{-- @if ($order->points_redeemed)
                        <tr class="tabletitle">

                            <td class="item" colspan="2" style="text-align: center;">
                                <h2>Points Redeemed (-)</h2>
                            </td>
                            <td></td>
                            <td class="payment">
                                <h2>{{ $order->points_redeemed }}</h2>
                            </td>
                        </tr>
                    @endif --}}
                    @if ($order->membership_discount)
                        <tr class="tabletitle">

                            <td class="item" colspan="2" style="text-align: center;">
                                <h2>Membership Discount (-)</h2>
                            </td>
                            <td></td>
                            <td class="payment">
                                <h2>&#2547; {{ $order->membership_discount + $order->points_redeemed }}</h2>
                            </td>
                        </tr>
                    @endif

                    <tr class="tabletitle">

                        <td class="item" colspan="2" style="text-align: center;">
                            <h2>VAT (Incl.)</h2>
                        </td>
                        <td></td>
                        <td class="payment">
                            <h2>&#2547; {{ round(($order->price * $business->vat) / 100, 2) }}</h2>
                        </td>
                    </tr>

                    <tr class="tabletitle">
                        <td class="item" colspan="2" style="text-align: center;">
                            <h2>Total Payable</h2>
                        </td>
                        <td></td>
                        <td class="payment">
                            <h2>&#2547; {{ round($order->price + $order->delivery_charge - $order->wallet_amount - $order->advance + $order->extra_charge) }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">
                        <td class="item" colspan="2" style="text-align: center;">
                            <h2>Paid</h2>
                        </td>
                        <td></td>
                        <td class="payment">
                            <h2>&#2547; {{ round($order->paid_amount) }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">
                        <td class="item" colspan="2" style="text-align: center;">
                            <h2>Change</h2>
                        </td>
                        <td></td>
                        <td class="payment">
                            <h2>&#2547; {{ round($order->change_amount) }}</h2>
                        </td>
                    </tr>

                </table>
                <div class="info">
                    <p>
                        @if ($order->payment_method)
                            Payment Method : {{ $order->payment_method }}</br>
                        @endif
                        @if ($order->transaction_id)
                            Transaction ID : {{ $order->transaction_id }}</br>
                        @endif
                        @if ($order->customer->member)
                            Received Membership Point : {{ $order->points_received }}<br>
                            Total Membership Point : {{ $order->customer->member->current_points }}<br>
                        @endif
                    </p>
                </div>
            </div>
            <!--End Table-->

            {{-- @if ($order->note)
                <div class="info mb-1">
                    <p class="" style="font-style: italic; font-size: 11px">
                        Note : {{ $order->note }}
                    </p>
                </div>
            @endif --}}

            <div id="legalcopy">
                <p class="legal"><strong>Thank you for your shopping!</strong>
                </p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 15px">
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order->code, 'C39') }}" alt="barcode" width="160" /><br>
            <p style="margin: 3px 0;">www.gobyfabrifest.com</p>
        </div>
        <!--End InvoiceBot-->
    </div>
    <!--End Invoice-->
    <script>
        window.print();
    </script>
</body>

</html>
