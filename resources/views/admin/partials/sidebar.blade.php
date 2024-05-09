<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- <a href="{{ route('home') }}" class="brand-link" target="_blank" style="background-color: #fff">
    <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light" style="color: #000;">{{ env('APP_NAME') }}</span>
  </a> -->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('images/user/user-avatar-icon.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                <li class="nav-item menu-open">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if (Auth::user()->type == 1)
                    @if (auth()->user()->can('supplier.index') || auth()->user()->can('customer.list'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    User Management
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @hasrole(1)
                                    <li class="nav-item">
                                        <a href="{{ route('role.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Roles</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Users</p>
                                        </a>
                                    </li>
                                @endhasrole
                                @if (auth()->user()->can('customer.list'))
                                    <li class="nav-item">
                                        <a href="{{ route('customer.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Customers</p>
                                        </a>
                                    </li>
                                @endif
                                @if (!auth()->user()->vendor)
                                    @if (auth()->user()->can('supplier.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('supplier.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Suppliers</p>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (auth()->user()->can('bank.index'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>
                                    Bank
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('bank.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Bank List</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('banktransaction.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Transactions</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('bankcontra.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Cash Flow</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif

                    @if (auth()->user()->can('product.index') || auth()->user()->can('product.create') || auth()->user()->can('category.index') || auth()->user()->can('brand.index') || auth()->user()->can('size.index') || auth()->user()->can('production.index') || auth()->user()->can('product.print_label'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Product
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                @if (auth()->user()->can('product.index'))
                                    <li class="nav-item">
                                        <a href="{{ route('product.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Products List</p>
                                        </a>
                                    </li>
                                @endif
                                @if (!auth()->user()->vendor)
                                    @if (auth()->user()->can('product.create'))
                                        <li class="nav-item">
                                            <a href="{{ route('product.create') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Add Product</p>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('category.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('category.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Category</p>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('brand.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('brand.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Brand</p>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('size.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('size.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Size</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('production.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('production.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Production Sheet</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('product.print_label'))
                                        <li class="nav-item">
                                            <a href="{{ route('product.printlabel') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Print Labels</p>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (!auth()->user()->vendor)
                        @if (auth()->user()->can('order_sheet.create') || auth()->user()->can('order_sheet.index') || auth()->user()->can('order_sheet_status.index') || auth()->user()->can('courier_name.index') || auth()->user()->can('business_bkash_number.index'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-th"></i>
                                    <p>
                                        Order Sheet
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (auth()->user()->can('order_sheet.create'))
                                        <li class="nav-item">
                                            <a href="{{ route('fos.create') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>New Order (Retail)</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('fos.wholesale.create') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>New Order (Wholesale)</p>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('order_sheet.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('fos.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Order List</p>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('order_sheet_status.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('fos.status.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Statuses</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('fos.special_status.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Special Statuses</p>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('courier_name.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('fos.courier_name.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Courier Infos</p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if (auth()->user()->can('business_bkash_number.index'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-money-bill-alt"></i>
                                    <p>
                                        Bkash Panel
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('fos.bkash_number.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Bkash Numbers</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('bkash_panel.tr_purposes') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Bkash TR Purposes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('bkash_panel.create') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Add Transaction Record</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('bkash_panel.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Bkash Transactions</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if (auth()->user()->can('order.index') || auth()->user()->can('order.view'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-plus-square"></i>
                                    <p>
                                        Orders
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (auth()->user()->can('order.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('order.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>All Orders</p>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('order.view'))
                                        @foreach (App\Models\OrderStatus::all() as $status)
                                            <li class="nav-item">
                                                <a href="{{ route('order.status.filter', $status->id) }}" class="nav-link">
                                                    <i class="fas fa-angle-right"></i>
                                                    <p>{{ $status->title }}</p>
                                                </a>
                                            </li>
                                        @endforeach
                                        <li class="nav-item">
                                            <a href="{{ route('order.export.excel') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Export to Excel</p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if (auth()->user()->can('pos.create') || auth()->user()->can('wholesale.create') || auth()->user()->can('sell.index') || auth()->user()->can('order.return') || auth()->user()->can('wholesale.index'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-arrow-circle-up"></i>
                                <p>
                                    Sell
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            @if (auth()->user()->vendor)
                                <ul class="nav nav-treeview">

                                    @if (auth()->user()->can('pos.create'))
                                        <li class="nav-item">
                                            <a href="{{ route('pos.create', 'none') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>POS</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('sell.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('vendor_sell.report') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Sells Report</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('vendor_sell.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Sell List</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('vendor_sell.export.excel', 0) }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Export to Excel (Retail)</p>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- @if (auth()->user()->can('order.return'))
                                    <li class="nav-item">
                                        <a href="{{ route('sellreturn.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Sell Return</p>
                                        </a>
                                    </li>
                                @endif --}}

                                </ul>
                            @else
                                <ul class="nav nav-treeview">

                                    @if (auth()->user()->can('pos.create'))
                                        <li class="nav-item">
                                            <a href="{{ route('pos.create', 'none') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>POS</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('wholesale.create'))
                                        <li class="nav-item">
                                            <a href="{{ route('pos.wholesale.create', 'none') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Wholesale(POS)</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('sell.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('sell.report') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Sells Report</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('sell.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Sell List</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('sell.export.excel', 0) }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Export to Excel (Retail)</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('order.return'))
                                        <li class="nav-item">
                                            <a href="{{ route('sellreturn.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Sell Return</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('wholesale.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('sell.wholesale.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Wholesale List</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('sell.wholesale.export.excel', 0) }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Export to Excel (Wholesale)</p>
                                            </a>
                                        </li>
                                    @endif

                                </ul>

                            @endif
                        </li>
                    @endif

                    @if (auth()->user()->can('vat.calculate'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-money-bill-alt"></i>
                                <p>
                                    VAT Management
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('vat_entry.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>VAT Entries</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (!auth()->user()->vendor)
                        @if (auth()->user()->can('membership.cards'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-address-card"></i>
                                    <p>
                                        Membership Panel
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('membership.cards') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Membership Cards</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('membership.create') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Membership Registration</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('membership.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Members</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('membership.purchases') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Member Purchases</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if (auth()->user()->can('add.stock') || auth()->user()->can('current.stock.view') || auth()->user()->can('stock.history') || auth()->user()->can('damage.view'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>
                                    Stock Management
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                @if (!auth()->user()->vendor)
                                    @if (auth()->user()->can('add.stock'))
                                        <li class="nav-item">
                                            <a href="{{ route('stock.add') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Add Stock</p>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if (auth()->user()->can('current.stock.view'))
                                    <li class="nav-item">
                                        <a href="{{ route('stock.current') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Current Stock</p>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->can('stock.history'))
                                    <li class="nav-item">
                                        <a href="{{ route('stock.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Stock History</p>
                                        </a>
                                    </li>
                                @endif

                                @if (auth()->user()->can('damage.view'))
                                    <li class="nav-item">
                                        <a href="{{ route('damage.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Damage List</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (!auth()->user()->vendor)
                        @if (auth()->user()->can('damage.view'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-times-circle"></i>
                                    <p>
                                        Reject Panel
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('reject.add.view') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Transfer to Reject List</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('reject.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Reject Products History</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('reject.stock') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Reject Products Stock</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('reject.product.out.form') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Reject Product Out</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('reject.product.out.list') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Reject Product Out List</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if (auth()->user()->can('expense.index') || auth()->user()->can('expense.view'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-minus-circle"></i>
                                <p>
                                    Expense
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                @if (auth()->user()->can('expense_type.index'))
                                    <li class="nav-item">
                                        <a href="{{ route('expense.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Expense Type</p>
                                        </a>
                                    </li>
                                @endif

                                @if (auth()->user()->can('expense.view'))
                                    <li class="nav-item">
                                        <a href="{{ route('expenseentry.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Expense List</p>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    @if (!auth()->user()->vendor)
                        @if (auth()->user()->can('coupon.index'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-percent"></i>
                                    <p>
                                        Campaign
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">

                                    @if (auth()->user()->can('coupon.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('coupon.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Coupon</p>
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                        @if (auth()->user()->can('subscriber.index'))
                            <li class="nav-item">
                                <a href="{{ route('admin.subscribers') }}" class="nav-link">
                                    <i class="nav-icon fas fa-bell-slash"></i>
                                    <p>
                                        Subscribers
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->can('district.index'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-map-marker-alt"></i>
                                    <p>
                                        Location
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('district.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>District List</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('area.index') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Area List</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endif
                    @endif

                    @if (auth()->user()->can('report.income') || auth()->user()->can('report.balance_sheet') || auth()->user()->can('report.owners'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Reports
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                @if (auth()->user()->can('report.income'))
                                    <li class="nav-item">
                                        <a href="{{ route('report.incomestatement') }}" class="nav-link">
                                            <i class="fas fa-angle-right"></i>
                                            <p>Income Statement</p>
                                        </a>
                                    </li>
                                @endif
                                @if (!auth()->user()->vendor)
                                    @if (auth()->user()->can('report.balance_sheet'))
                                        <li class="nav-item">
                                            <a href="{{ route('report.balancesheet') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Balance Sheet</p>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('report.owners'))
                                        <li class="nav-item">
                                            <a href="{{ route('report.ownersequity') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Owners Equity</p>
                                            </a>
                                        </li>
                                    @endif
                                @endif

                            </ul>
                        </li>
                    @endif

                    @if (!auth()->user()->vendor)
                        @if (auth()->user()->can('report.owners'))
                            <li class="nav-item">
                                <a href="{{ route('partner.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Owners
                                    </p>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (auth()->user()->vendor)
                        @if (auth()->user()->can('report.owners'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>
                                        Transfer Products
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('vendor.transfer_products_main') }}" class="nav-link">
                                            <i class="nav-icon fas fa-list"></i>
                                            <p>
                                                Transfer to Main Business
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('vendor.transfer_products_vendor') }}" class="nav-link">
                                            <i class="nav-icon fas fa-list"></i>
                                            <p>
                                                Transfer to Vendors
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if (!auth()->user()->vendor)
                        @if (auth()->user()->can('report.owners'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Vendors
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('vendor.index') }}" class="nav-link">
                                            <i class="nav-icon fas fa-list"></i>
                                            <p>
                                                Vendor List
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('vendor.transfer_products') }}" class="nav-link">
                                            <i class="nav-icon fas fa-list"></i>
                                            <p>
                                                Transfer Products
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if (!auth()->user()->vendor)
                        @if (auth()->user()->can('setting.asset'))
                            <li class="nav-item">
                                <a href="{{ route('asset.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-truck-monster"></i>
                                    <p>Assets</p>
                                </a>
                            </li>
                        @endif
                    @endif
                    @if (auth()->user()->can('setting.accessory'))
                        <li class="nav-item">
                            <a href="{{ route('accessory.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>Accessories</p>
                            </a>
                        </li>
                    @endif
                    @if (!auth()->user()->vendor)
                        @if (auth()->user()->can('setting.business_settings') || auth()->user()->can('setting.asset') || auth()->user()->can('setting.accessory') || auth()->user()->can('page.index'))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        Settings
                                        <i class="fas fa-angle-right right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">

                                    @if (auth()->user()->can('setting.business_settings'))
                                        <li class="nav-item">
                                            <a href="{{ route('setting.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Business Settings</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('page.index'))
                                        <li class="nav-item">
                                            <a href="{{ route('trending.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Trending</p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('slider.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Slider Option</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('page.index') }}" class="nav-link">
                                                <i class="fas fa-angle-right"></i>
                                                <p>Pages</p>
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                    @endif

                    <li class="nav-item">
                        <a href="{{ route('user.profile') }}" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Profile
                            </p>
                        </a>
                    </li>
                @endif
                <div class="p-2"></div>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
