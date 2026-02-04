<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="{{route('admin.dealer.create')}}"
                            class="nav-link {{ request()->routeIs('admin.dealer.create') ? 'active' : '' }}">
                            <i data-feather="plus-circle" class="me-1 icon-dual"></i> Dealer Create
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.dealer.index')}}"
                            class="nav-link {{ request()->routeIs('admin.dealer.index') ? 'active' : '' }}">
                            <i data-feather="list" class="me-1 icon-dual"></i> Dealer List
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.reseller.index')}}"
                            class="nav-link {{ request()->routeIs('admin.reseller.index') ? 'active' : '' }}">
                            <i data-feather="users" class="me-1 icon-dual"></i> Reseller List
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link disabled" title="Coming Soon">
                            <i data-feather="clock" class="me-1 icon-dual"></i> Payment History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.dealer.payment_requests')}}"
                            class="nav-link {{ request()->routeIs('admin.dealer.payment_requests') ? 'active' : '' }}">
                            <i data-feather="dollar-sign" class="me-1 icon-dual"></i> Payment Request
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.dealer.orders')}}"
                            class="nav-link {{ request()->routeIs('admin.dealer.orders') ? 'active' : '' }}">
                            <i data-feather="shopping-cart" class="me-1 icon-dual"></i> Order List
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link disabled" title="Coming Soon">
                            <i data-feather="briefcase" class="me-1 icon-dual"></i> Wallet
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>