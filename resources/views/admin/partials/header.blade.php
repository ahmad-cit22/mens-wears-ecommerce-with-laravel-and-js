@php
    $business = App\Models\Setting::find(1);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $business->name }} | Dashboard</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('images/website/' . $business->favicon) }}" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"> --}}

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <style type="text/css">
        [class*="sidebar-dark-"] .nav-sidebar>.nav-item>.nav-treeview {
            background-color: transparent;
            margin-left: 16px;
        }

        .tox-notifications-container {
            display: none;
        }

        @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            }
        }

        /* .dataTable tr th,
        td {
            word-wrap: break-word;
            word-break: break-all;
        } */

        .badge-packet-done {
            background: rgb(75, 119, 0) !important;
            color: #fff !important;
        }

        .badge-in-transit {
            background: rgb(0, 92, 112) !important;
            color: #fff !important;
        }

        .badge-delivered {
            background: rgb(0, 41, 112) !important;
            color: #fff !important;
        }

        .badge-office-sell {
            background: rgb(40, 0, 110) !important;
            color: #fff !important;
        }

        .stock-out-tag {
            position: absolute;
            right: 5px;
            top: 6px;
            padding: 0px 8px;
            background: #ff000098;
            font-weight: 600;
            border-radius: 4px;
            font-size: 13px;
            color: #fff !important;
        }

        .categoryCardBox {
            gap: 20px
        }

        .categoryCard1 {
            padding: 15px 15px 0px;
            padding-left: 22px;
            border-radius: 14px;
            background: rgba(235, 98, 0, 0.888);
            color: white;
            display: inline-block;
            max-width: 22% !important;
            box-shadow: 0px 4px 12px 1px rgba(124, 48, 0, 0.665);
        }

        .categoryCard2 {
            padding: 15px 15px 0px;
            padding-left: 22px;
            border-radius: 14px;
            background: rgba(238, 99, 0, 0.773);
            color: white;
            display: inline-block;
            max-width: 22% !important;
            box-shadow: 0px 4px 14px 2px rgba(124, 48, 0, 0.665);
        }
    </style>
    @yield('style')
    <script src="https://cdn.tiny.cloud/1/yze2qblheth712tqw6y3f30283f8nch1bogp0jzpsm3rn79f/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('index') }}" class="nav-link" target="_blank"><i class="fas fa-home"></i> Visit Website</a>
                </li>
                <li class="nav-item ml-2">
                    <a class="nav-link btn btn-sm btn-danger" href="{{ route('cache.clear') }}">
                        <i class="far fa-trash-alt mr-2"></i>Clear Cache
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                @if (auth()->user()->can('order_sheet.create'))
                    <li class="nav-item mr-2">
                        <a class="nav-link btn btn-primary bg-blue" href="{{ route('fos.create') }}">
                            <i class="far fa-file-alt mr-2"></i>Order Sheet
                        </a>
                    </li>
                @endif
                @if (auth()->user()->can('pos.create'))
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary bg-purple" href="{{ route('pos.create', 'none') }}">
                            <i class="fas fa-table mr-1"></i> POS
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>

            </ul>
        </nav>
        <!-- /.navbar -->
