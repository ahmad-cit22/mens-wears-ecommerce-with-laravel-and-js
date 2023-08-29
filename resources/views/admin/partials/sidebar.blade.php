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
                            <li class="nav-item">
                                <a href="{{ route('customer.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Customers</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('supplier.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Suppliers</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @hasrole(1)
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
                    @endhasrole

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Product
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('product.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Products List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product.create') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Add Product</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('category.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('brand.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Brand</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('size.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Size</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('production.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Production Sheet</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product.printlabel') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Print Labels</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Order Sheet
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('fos.create') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>New Order</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('fos.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Order List</p>
                                </a>
                            </li>
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
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-plus-square"></i>
                            <p>
                                Orders
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('order.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>All Orders</p>
                                </a>
                            </li>
                            @foreach (App\Models\OrderStatus::all() as $status)
                                <li class="nav-item">
                                    <a href="{{ route('order.status.filter', $status->id) }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>{{ $status->title }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-arrow-circle-up"></i>
                            <p>
                                Sell
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('pos.create') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>POS</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('pos.wholesale.create') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Wholesale(POS)</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('sell.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Sell List</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('sellreturn.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Sell Return</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('sell.wholesale.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Wholesale List</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>
                                Stock Management
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('stock.add') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Add Stock</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('stock.current') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Current Stock</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('stock.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Stock History</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('damage.index') }}" class="nav-link">
                                    <i class="fas fa-angle-right"></i>
                                    <p>Damage List</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    @hasrole(1)
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-minus-circle"></i>
                                <p>
                                    Expense
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('expense.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Expense Type</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('expenseentry.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Expense List</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-percent"></i>
                                <p>
                                    Campaign
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('coupon.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Coupone</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.subscribers') }}" class="nav-link">
                                <i class="nav-icon fas fa-bell-slash"></i>
                                <p>
                                    Subscribers
                                </p>
                            </a>
                        </li>

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

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Reports
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('report.incomestatement') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Income Statement</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('report.balancesheet') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Balance Sheet</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('report.ownersequity') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Owners Equity</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('partner.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Owners
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Settings
                                    <i class="fas fa-angle-right right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('setting.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Business Settings</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('asset.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Assets</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('accessory.index') }}" class="nav-link">
                                        <i class="fas fa-angle-right"></i>
                                        <p>Accessories</p>
                                    </a>
                                </li>
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


                            </ul>
                        </li>
                    @endhasrole

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
