@php
    $business = App\Models\Setting::find(1);
@endphp
<!doctype html>
<html class="no-js" lang="en">

<head>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MSXD3K9');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZPLMG5ECY6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-ZPLMG5ECY6');
    </script>
    <!-- Google tag (gtag.js) -->

    <!--facebook-domain-verification-->
    <meta name="facebook-domain-verification" content="lakjzs0vhg73078a1sirl7arc7mvux"  />
    <!--facebook-domain-verification-->

    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '6814850821967961');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=6814850821967961&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', $settings->name)</title>

    @yield('meta_description')
    @yield('meta_keywords')
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/website/' . $business->favicon) }}">

    <!-- All CSS is here
 ============================================ -->

    @include('pages.partials.style')
    <style>
        .stock-out-tag {
            position: absolute;
            right: 5px;
            top: 6px;
            padding: 0px 8px;
            background: #ff000098;
            font-weight: 600;
            border-radius: 4px;
            font-size: 10px;
        }

        .nav-link {
            padding: 0 !important;
        }

        @media only screen and (max-width: 1503px) {
            .nav-link {
                font-size: 12px !important;
            }
        }

        @media only screen and (max-width: 1305px) {
            .main-menu li {
                /* font-size: 12px !important; */
                padding: 0 8px !important;
            }
        }

        @media only screen and (max-width: 1225px) {
            .main-menu li {
                padding: 0 5px !important;
            }

            .nav-link {
                font-size: 11px !important;
            }

            .header-right-wrap a {
                font-size: 14px !important;
            }
        }
    </style>
    @yield('style')

</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MSXD3K9" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Messenger Chat plugin Code -->
    <div id="fb-root"></div>

    <!-- Your Chat plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>
    <div class="main-wrapper">
        @include('pages.partials.header') <br><br>
        @yield('content')
        @include('pages.partials.footer')
    </div>

    <!-- All JS is here
============================================ -->

    @include('pages.partials.script')

    @yield('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
