<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />





    <title>@yield('title') - {{ $generalsetting->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}" />

    <!-- Bootstrap css -->
    <link href="{{ asset('public/backEnd/') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('public/backEnd/') }}/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- icons -->
    <link href="{{ asset('public/backEnd/') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- toastr css -->
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/assets/css/toastr.min.css" />
    <!-- custom css -->
    <link href="{{ asset('public/backEnd/') }}/assets/css/custom.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Head js -->
    @yield('css')
    <script src="{{ asset('public/backEnd/') }}/assets/js/head.js"></script>


</head>

<!-- body start -->

<body data-layout-mode="default" data-theme="light" data-layout-width="fluid" data-topbar-color="dark"
    data-menu-position="fixed" data-leftbar-color="light" data-leftbar-size="default" data-sidebar-user="false">
    <!-- Google Tag Manager (noscript) -->

    <!-- End Google Tag Manager (noscript) -->
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-end mb-0">
                    <li class="dropdown d-inline-block d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="fe-search noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                            <form class="p-3">
                                <input type="text" class="form-control" placeholder="Search ..."
                                    aria-label="Recipient's username" />
                            </form>
                        </div>
                    </li>

                    <li class="dropdown d-none d-lg-inline-block">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen"
                            href="#">
                            <i class="fe-maximize noti-icon"></i>
                        </a>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-bell noti-icon"></i>
                            <span class="badge bg-danger rounded-circle noti-icon-badge">{{ $neworder }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="m-0">
                                    <span class="float-end">
                                        <a href="{{ route('admin.orders', ['slug' => 'pending']) }}" class="text-dark">
                                            <small>View All</small>
                                        </a>
                                    </span>
                                    Orders
                                </h5>
                            </div>

                            <div class="noti-scroll" data-simplebar>
                                @foreach ($pendingorder as $porder)
                                    <!-- item-->
                                    <a href="{{ route('admin.orders', ['slug' => 'pending']) }}"
                                        class="dropdown-item notify-item active">
                                        <div class="notify-icon">
                                            <img src="{{ asset($porder->customer ? $porder->customer->image : '') }}"
                                                class="img-fluid rounded-circle" alt="" />
                                        </div>
                                        <p class="notify-details">
                                            {{ $porder->customer ? $porder->customer->name : '' }}
                                        </p>
                                        <p class="text-muted mb-0 user-msg">
                                            <small>Invoice : {{ $porder->invoice_id }}</small>
                                        </p>
                                    </a>
                                @endforeach

                                <!-- item-->
                            </div>

                            <!-- All-->
                            <a href="{{ route('admin.orders', ['slug' => 'pending']) }}"
                                class="dropdown-item text-center text-primary notify-item notify-all">
                                View all
                                <i class="fe-arrow-right"></i>
                            </a>
                        </div>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="{{ asset(Auth::user()->image) }}" alt="user-image" class="rounded-circle" />
                            <span class="pro-user-name ms-1"> {{ Auth::user()->name }} <i
                                    class="mdi mdi-chevron-down"></i> </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a href="{{ route('dashboard') }}" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>Dashboard</span>
                            </a>

                            <!-- item-->
                            <a href="{{ route('change_password') }}" class="dropdown-item notify-item">
                                <i class="fe-settings"></i>
                                <span>Change Password</span>
                            </a>

                            <!-- item-->
                            <a href="{{ route('locked') }}" class="dropdown-item notify-item">
                                <i class="fe-lock"></i>
                                <span>Lock Screen</span>
                            </a>

                            <div class="dropdown-divider"></div>
                            <!-- item-->
                            <a href="https://my.eiconbd.com/?ng=client/login/" target="_blank"
                                class="dropdown-item notify-item">
                                <i class="fa-comments-question-check"></i>
                                <span>Support Ticket</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();" class="dropdown-item notify-item">
                                <i class="fe-log-out me-1"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>


                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="{{ url('admin/dashboard') }}" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="{{ asset($generalsetting->white_logo) }}" alt="" height="50" />
                            <!-- <span class="logo-lg-text-light">UBold</span> -->
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset($generalsetting->dark_logo) }}" alt="" height="50" />
                            <!-- <span class="logo-lg-text-light">U</span> -->
                        </span>
                    </a>

                    <a href="{{ url('admin/dashboard') }}" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="{{ asset($generalsetting->white_logo) }}" alt="" height="30" />
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset($generalsetting->white_logo) }}" alt="" height="50" />
                        </span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0 d-flex align-items-center">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>

                    <li>
                        <!-- Mobile menu toggle (Horizontal Layout)-->
                        <a class="navbar-toggle nav-link" data-bs-toggle="collapse"
                            data-bs-target="#topnav-menu-content">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>

                    <li class="dropdown d-none d-xl-block">
                        <a class="nav-link dropdown-toggle waves-effect waves-light" href="{{ route('home') }}"
                            target="_blank"> <i data-feather="globe"></i> Visit Site </a>
                    </li>
                    <li class="dropdown d-none d-xl-block w-50 mx-auto">
                        <div class="input-group rounded-pill">
                            <input type="text" class="form-control border-0" placeholder="Search...">
                            <button class="btn btn-primary border-0">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">
            <div class="h-100" data-simplebar>
                <!-- User box -->
                <div class="user-box text-center">
                    <img src="{{ asset('public/backEnd/') }}/assets/images/users/user-1.jpg" alt="user-img" title="Mat Helme"
                        class="rounded-circle avatar-md" />
                    <div class="dropdown">
                        <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                            data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                        <div class="dropdown-menu user-pro-dropdown">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user me-1"></i>
                                <span>My Account</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-settings me-1"></i>
                                <span>Settings</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-lock me-1"></i>
                                <span>Lock Screen</span>
                            </a>

                            <!-- item-->
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                class="dropdown-item notify-item">
                                <i class="fe-log-out me-1"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                    <p class="text-muted">Admin Head</p>
                </div>

                <!--- Sidemenu -->
                <div id="sidebar-menu" class="sidemenu">
                    <ul id="side-menu">
                        @php
                            $isAuthor = auth()->user()->hasRole('Book Author');
                        @endphp
                        @if(!$isAuthor)
                            <li>
                                <a href="{{ url('admin/dashboard') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#1f1f1f">
                                        <path
                                            d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z" />
                                    </svg>
                                    <span> Dashboard </span>
                                </a>
                            </li>

                            @if (auth()->user()->can('order-all'))
                                <li>
                                    <a href="#sidebar-orders" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                                        </svg>
                                        <span> Orders </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sidebar-orders">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('admin.orders', ['slug' => 'all']) }}"><i
                                                        data-feather="file-plus"></i> All Order</a>
                                            </li>
                                            @foreach ($orderstatus as $value)
                                                <li>
                                                    <a href="{{ route('admin.orders', ['slug' => $value->slug]) }}"><i
                                                            data-feather="file-plus"></i>{{ $value->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endif










                            <!-- nav items -->
                            @if (auth()->user()->can('product-list') || auth()->user()->can('product-create') || auth()->user()->can('product-edit'))
                                <li>
                                    <a href="#siebar-product" data-bs-toggle="collapse">

                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M620-163 450-333l56-56 114 114 226-226 56 56-282 282Zm220-397h-80v-200h-80v120H280v-120h-80v560h240v80H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v200ZM480-760q17 0 28.5-11.5T520-800q0-17-11.5-28.5T480-840q-17 0-28.5 11.5T440-800q0 17 11.5 28.5T480-760Z" />
                                        </svg>
                                        <span>Products</span>

                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="siebar-product">
                                        <ul class="nav-second-level">
                                            @if (auth()->user()->can('product-list'))
                                                <li>
                                                    <a href="{{ route('products.index') }}"><i data-feather="file-plus"></i>
                                                        Product Manage</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('category-list'))
                                                <li>
                                                    <a href="{{ route('categories.index') }}"><i data-feather="file-plus"></i>
                                                        Categories</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('subcategory-list'))
                                                <li>
                                                    <a href="{{ route('subcategories.index') }}"><i data-feather="file-plus"></i>
                                                        Subcategories</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('childcategory-list'))
                                                <li>
                                                    <a href="{{ route('childcategories.index') }}"><i data-feather="file-plus"></i>
                                                        Childcategories</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('brand-list'))
                                                <li>
                                                    <a href="{{ route('brands.index') }}"><i data-feather="file-plus"></i>
                                                        Brands</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('color-list'))
                                                <li>
                                                    <a href="{{ route('colors.index') }}"><i data-feather="file-plus"></i>
                                                        Colors</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('size-list'))
                                                <li>
                                                    <a href="{{ route('sizes.index') }}"><i data-feather="file-plus"></i>
                                                        Sizes</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('price-edit'))
                                                <li>
                                                    <a href="{{ route('products.price_edit') }}"><i data-feather="file-plus"></i>
                                                        Price Edit</a>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <!-- nav items -->
                            @if (auth()->user()->can('fraud-check'))
                                <li>
                                    <a href="#siebar-Checker" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                                        </svg>
                                        <span> Fraud Check </span>
                                        <span class="menu-arrow"></span>
                                    </a>

                                    <div class="collapse" id="siebar-Checker">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('order.fraud') }}"><i data-feather="file-plus"></i> Fraud
                                                    Check</a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <!-- nav items end -->
                            @if (auth()->user()->can('review_all'))
                                @php
                                    $pending_reviews = \App\Models\Review::where('status', 'pending')->count();
                                @endphp
                                <li>
                                    <a href="#sidebar-product-review" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M856-390 570-104q-12 12-27 18t-30 6q-15 0-30-6t-27-18L103-457q-11-11-17-25.5T80-513v-287q0-33 23.5-56.5T160-880h287q16 0 31 6.5t26 17.5l352 353q12 12 17.5 27t5.5 30q0 15-5.5 29.5T856-390ZM513-160l286-286-353-354H160v286l353 354ZM260-640q25 0 42.5-17.5T320-700q0-25-17.5-42.5T260-760q-25 0-42.5 17.5T200-700q0 25 17.5 42.5T260-640Zm220 160Z" />
                                        </svg>
                                        <span> Reviews </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sidebar-product-review">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('reviews.pending') }}"><i data-feather="file-plus"></i>
                                                    Pending Reviews ({{ $pending_reviews }})</a>
                                            </li>

                                            <li>
                                                <a href="{{ route('reviews.pending') }}"><i data-feather="file-plus"></i>
                                                    Create</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('reviews.index') }}"><i data-feather="file-plus"></i> All
                                                    Reviews</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('issue.index') }}"><i data-feather="file-plus"></i> All
                                                    Issue</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <!-- nav items end -->
                            @if (auth()->user()->can('landing-page'))
                                <li>
                                    <a href="#sidebar-landing-page" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M200-120q-50 0-85-35t-35-85q0-50 35-85t85-35h560q50 0 85 35t35 85q0 50-35 85t-85 35H200Zm0-80h560q17 0 28.5-11.5T800-240q0-17-11.5-28.5T760-280H200q-17 0-28.5 11.5T160-240q0 17 11.5 28.5T200-200Zm200-240q-17 0-28.5-11.5T360-480v-320q0-17 11.5-28.5T400-840h320q17 0 28.5 11.5T760-800v320q0 17-11.5 28.5T720-440H400Zm40-80h240v-240H440v240Zm-360-2v-77h197v77H80Zm400-118h160v-79H480v79Zm-320 0h117v-79H160v79Zm280 120v-240 240Z" />
                                        </svg>
                                        <span> Landing Page </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sidebar-landing-page">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('landing_page.manage') }}"><i data-feather="file-text"></i>
                                                    Landing Page</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <!-- nav items -->
                            @if (auth()->user()->can('marketing-all'))
                                <li>
                                    <a href="#siebar-Marketing" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M720-440v-80h160v80H720Zm48 280-128-96 48-64 128 96-48 64Zm-80-480-48-64 128-96 48 64-128 96ZM200-200v-160h-40q-33 0-56.5-23.5T80-440v-80q0-33 23.5-56.5T160-600h160l200-120v480L320-360h-40v160h-80Zm240-182v-196l-98 58H160v80h182l98 58Zm120 36v-268q27 24 43.5 58.5T620-480q0 41-16.5 75.5T560-346ZM300-480Z" />
                                        </svg>
                                        <span> Marketing </span>
                                        <span class="menu-arrow"></span>
                                    </a>

                                    <div class="collapse" id="siebar-Marketing">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('sms.index') }}"><i data-feather="file-plus"></i>SMS
                                                    Template</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('hotdeal.index') }}"><i data-feather="file-plus"></i>Flash
                                                    Deal</a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <!-- nav items end -->

                            {{-- Photo Gallery Menu --}}
                            @if(auth()->user()->can('gallery-manage'))
                                <li>
                                    <a href="{{ route('media.index') }}"
                                        class="{{ request()->routeIs('media.*') ? 'active' : '' }}">
                                        {{-- আইকন শুরু --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z" />
                                        </svg>
                                        {{-- আইকন শেষ --}}

                                        <span > Photo Gallery </span>
                                    </a>
                                </li>
                            @endif


                            @if (auth()->user()->can('wholesale-manage') || auth()->user()->can('user-list') || auth()->user()->can('role-list'))
                                <li class="nav-item">
                                    {{-- ১. B2B Management (Parent) --}}
                                    <a href="#sidebar-b2b" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M160-120q-33 0-56.5-23.5T80-200v-440q0-33 23.5-56.5T160-720h160v-80q0-33 23.5-56.5T400-880h160q33 0 56.5 23.5T640-800v80h160q33 0 56.5 23.5T880-640v440q0 33-23.5 56.5T800-120H160Zm0-80h640v-440H160v440Zm240-520h160v-80H400v80ZM160-200v-440 440Z" />
                                        </svg>
                                        <span> B2B Management </span>
                                        <span class="menu-arrow"></span>
                                    </a>


                                    
                        
                                    {{-- ২. সাব-মেনু কন্টেইনার --}}
                                    <div class="collapse" id="sidebar-b2b">
                                        <ul class="nav-second-level">

                                            {{-- সাব-মেনু ১: Wholesale (Direct Link) --}}
                                            <li>
                                                <a href="{{ route('wholeseller.index') }}">
                                                    <i data-feather="truck"></i> Wholesale
                                                </a>
                                            </li>

                                            {{-- সাব-মেনু ২: Author Manage (Direct Link - No Dropdown) --}}
                                            <li>
                                                <a href="{{ route('author.index') }}">
                                                    <i data-feather="pen-tool"></i> Author Manage
                                                </a>
                                            </li>

                                            {{-- সাব-মেনু ৩: Dealer Requests --}}
                                            <li>
                                                <a href="{{ route('admin.dealer.index') }}">
                                                    <i data-feather="users"></i> Dealer Manage
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.reseller.index') }}">
                                                    <i data-feather="users"></i> Reseller Manage
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif




                            <!-- Inventory Management Menu -->
                            @if(auth()->user()->can('inventory-manage'))
                                <li>
                                    <a href="#sidebar-inventory" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M480-120q-75 0-140.5-28.5t-114-77q-48.5-48.5-77-114T120-480q0-75 28.5-140.5t77-114q48.5-48.5 114-77T480-840q82 0 155.5 35T760-706v-94h80v240H600v-80h110q-41-56-101-88t-129-32q-117 0-198.5 81.5T200-480q0 117 81.5 198.5T480-200q105 0 185.5-65.5T766-440h82q-18 139-119.5 219.5T480-120Zm-40-200h80v-240h-80v240Z" />
                                        </svg>
                                        <span> Inventory </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sidebar-inventory">
                                        <ul class="nav-second-level">
                                            <li>
                                                <a href="{{ route('admin.inventory.dashboard') }}"><i
                                                        data-feather="bar-chart-2"></i> Dashboard</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.purchase.index') }}"><i
                                                        data-feather="shopping-bag"></i> Stock In (Purchase)</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.inventory.shipping') }}"><i data-feather="truck"></i>
                                                    Stock Out (Ship)</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.inventory.return') }}"><i
                                                        data-feather="refresh-ccw"></i> Stock Return</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.inventory.stock_list') }}"><i data-feather="grid"></i>
                                                    Stock List</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.inventory.logs') }}"><i data-feather="list"></i> Stock
                                                    Logs</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.supplier.index') }}"><i data-feather="users"></i>
                                                    Supplier
                                                    Manage</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif

                            @if (auth()->user()->can('user-list') || auth()->user()->can('user-create') || auth()->user()->can('user-edit') || auth()->user()->can('customer-list') || auth()->user()->can('permission-list'))
                                <li>
                                    <a href="#sidebar-users" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113ZM120-240h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0 320Zm0-400Z" />
                                        </svg>
                                        <span> Users </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sidebar-users">
                                        <ul class="nav-second-level">
                                            @if (auth()->user()->can('user-list'))
                                                <li>
                                                    <a href="{{ route('users.index') }}"><i data-feather="file-plus"></i>
                                                        User</a>
                                                </li>
                                            @endif

                                            @if (auth()->user()->can('role-list'))
                                                <li>
                                                    <a href="{{ route('roles.index') }}"><i data-feather="file-plus"></i>
                                                        Roles</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('permission-list'))
                                                <li>
                                                    <a href="{{ route('permissions.index') }}"><i data-feather="file-plus"></i>
                                                        Permissions</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('customer-list'))
                                                <li>
                                                    <a href="{{ route('customers.index') }}"><i data-feather="file-plus"></i>
                                                        Customers</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif









                            <!-- nav items -->

                            @if (auth()->user()->can('setting-list') || auth()->user()->can('social-list') || auth()->user()->can('contact-list') || auth()->user()->can('page-list') || auth()->user()->can('tiktok-pixel-setting') || auth()->user()->can('order-status'))
                                <li>
                                    <a href="#siebar-sitesetting" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z" />
                                        </svg>
                                        <span> Site Setting </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="siebar-sitesetting">
                                        <ul class="nav-second-level">
                                            @if (auth()->user()->can('setting-list'))
                                                <li>
                                                    <a href="{{ route('settings.index') }}"><i data-feather="file-plus"></i>
                                                        General Setting</a>
                                                </li>
                                            @endif

                                            @if (auth()->user()->can('social-list'))
                                                <li>
                                                    <a href="{{ route('socialmedias.index') }}"><i data-feather="file-plus"></i>
                                                        Social Media</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('contact-list'))
                                                <li>
                                                    <a href="{{ route('contact.index') }}"><i data-feather="file-plus"></i>
                                                        Contact</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('page-list'))
                                                <li>
                                                    <a href="{{ route('pages.index') }}"><i data-feather="file-plus"></i> Create
                                                        Page</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('shipping-list'))
                                                <li>
                                                    <a href="{{ route('shippingcharges.index') }}"><i data-feather="file-plus"></i>
                                                        Shipping Charge</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('quicktab-manage'))
                                                <li>
                                                    <a href="{{ route('admin.quick_tabs.index') }}"><i data-feather="file-plus"></i>
                                                        QuickTab Manage</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('order-status'))
                                                <li>
                                                    <a href="{{ route('orderstatus.index') }}"><i data-feather="file-plus"></i>
                                                        Order Status</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <!-- nav items end -->
                            <!-- Api integration -->
                            @if (auth()->user()->can('payment-gateway') || auth()->user()->can('sms-gateway') || auth()->user()->can('courier-api') || auth()->user()->can('pixel-setting') || auth()->user()->can('tag-manager') || auth()->user()->can('tiktok-pixel-setting'))
                                <li>
                                    {{-- ডিফল্ট হিসেবে পেমেন্ট গেটওয়ে পেজে নিয়ে যাবে --}}
                                    <a href="{{ route('paymentgeteway.manage') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="m480-400-80-80 80-80 80 80-80 80Zm-85-235L295-735l185-185 185 185-100 100-85-85-85 85ZM225-295 40-480l185-185 100 100-85 85 85 85-100 100Zm510 0L635-395l85-85-85-85 100-100 185 185-185 185ZM480-40 295-225l100-100 85 85 85-85 100 100L480-40Z" />
                                        </svg>
                                        <span> API Integration </span>
                                    </a>
                                </li>
                            @endif
                            <!-- nav items end -->

                            <!-- nav items end -->
                            @if (auth()->user()->can('banner-list') || auth()->user()->can('banner-category-list'))
                                <li>
                                    <a href="#siebar-banner" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M240-240h480v-80H240v80Zm-40 120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                                        </svg>
                                        <span> Banner & Ads </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="siebar-banner">
                                        <ul class="nav-second-level">
                                            @if (auth()->user()->can('banner-list'))
                                                <li>
                                                    <a href="{{ route('banner_category.index') }}"><i data-feather="file-plus"></i>
                                                        Banner Category</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('banner-category-list'))
                                                <li>
                                                    <a href="{{ route('banners.index') }}"><i data-feather="file-plus"></i>
                                                        Banner & Ads</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <!-- nav items end -->
                            @if (auth()->user()->can('stock-report') || auth()->user()->can('ip-block') || auth()->user()->can('order-report') || auth()->user()->can('product-reports'))
                                <li>
                                    <a href="#sitebar-report" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M380-400h60v-120h180l-60-80 60-80H380v280ZM200-120v-640q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v640L480-240 200-120Zm80-122 200-86 200 86v-518H280v518Zm0-518h400-400Z" />
                                        </svg>
                                        <span> Reports </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sitebar-report">
                                        <ul class="nav-second-level">
                                            @if (auth()->user()->can('stock-report'))
                                                <li>
                                                    <a href="{{ route('admin.stock_report') }}"><i data-feather="file-plus"></i>
                                                        Stock Report</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('ip-block'))
                                                <li>
                                                    <a href="{{ route('customers.ip_block') }}"><i data-feather="file-plus"></i>
                                                        IP Block</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('order-report'))
                                                <li>
                                                    <a href="{{ route('admin.order_report') }}"><i data-feather="file-plus"></i>
                                                        Order Reports</a>
                                                </li>
                                            @endif
                                            @if (auth()->user()->can('product-reports'))
                                                <li>
                                                    <a href="{{ route('reports.product_report') }}"><i data-feather="file-plus"></i>
                                                        Product Reports</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <!-- nav items end -->
                            @if (auth()->user()->can('company-hidden'))
                                <li>
                                    <a href="#sitebar-EiconBD" data-bs-toggle="collapse">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f1f1f">
                                            <path
                                                d="M440-120v-80h320v-284q0-117-81.5-198.5T480-764q-117 0-198.5 81.5T200-484v244h-40q-33 0-56.5-23.5T80-320v-80q0-21 10.5-39.5T120-469l3-53q8-68 39.5-126t79-101q47.5-43 109-67T480-840q68 0 129 24t109 66.5Q766-707 797-649t40 126l3 52q19 9 29.5 27t10.5 38v92q0 20-10.5 38T840-249v49q0 33-23.5 56.5T760-120H440Zm-80-280q-17 0-28.5-11.5T320-440q0-17 11.5-28.5T360-480q17 0 28.5 11.5T400-440q0 17-11.5 28.5T360-400Zm240 0q-17 0-28.5-11.5T560-440q0-17 11.5-28.5T600-480q17 0 28.5 11.5T640-440q0 17-11.5 28.5T600-400Zm-359-62q-7-106 64-182t177-76q89 0 156.5 56.5T720-519q-91-1-167.5-49T435-698q-16 80-67.5 142.5T241-462Z" />
                                        </svg>
                                        <span> EiconBD </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sitebar-EiconBD">
                                        <ul class="nav-second-level">
                                            <li class="dropdown d-none d-xl-block">
                                                <a class="nav-link dropdown-toggle waves-effect waves-light"
                                                    href="https://shop.eiconbd.com/" target="_blank"> <i
                                                        data-feather="globe"></i> Domain </a>
                                            </li>
                                            <li class="dropdown d-none d-xl-block">
                                                <a class="nav-link dropdown-toggle waves-effect waves-light"
                                                    href="https://my.eiconbd.com/?ng=client/items/bdix-hosting/"
                                                    target="_blank"> <i data-feather="globe"></i> BDIX Hosting </a>
                                            </li>
                                            <li class="dropdown d-none d-xl-block">
                                                <a class="nav-link dropdown-toggle waves-effect waves-light"
                                                    href="https://eiconbd.com/business-phone.html" target="_blank"> <i
                                                        data-feather="globe"></i> Business Phone </a>
                                            </li>
                                            <li class="dropdown d-none d-xl-block">
                                                <a class="nav-link dropdown-toggle waves-effect waves-light"
                                                    href="https://my.eiconbd.com/?ng=client/items/ip-phone/" target="_blank">
                                                    <i data-feather="globe"></i> IP Phone Recharge </a>
                                            </li>
                                            <li>
                                            <li class="dropdown d-none d-xl-block">
                                                <a class="nav-link dropdown-toggle waves-effect waves-light"
                                                    href="https://my.eiconbd.com/?ng=client/items/non-masking-sms/"
                                                    target="_blank"> <i data-feather="globe"></i>Non Masking SMS </a>
                                            </li>
                                </li>
                            @endif
                        @else
                            <li>
                                <a href="{{ url('admin/dashboard') }}" data-bs-toggle="collapse">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#1f1f1f">
                                        <path
                                            d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z" />
                                    </svg>
                                    <span> Dashboard </span>
                                </a>
                            </li>

                            <li>
                                <a href="#sidebar-orders" data-bs-toggle="collapse">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#1f1f1f">
                                        <path
                                            d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                                    </svg>
                                    <span> Orders </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebar-orders">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('admin.orders', ['slug' => 'all']) }}"><i
                                                    data-feather="file-plus"></i> All Order</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>


                            <li>
                                <a href="#siebar-product" data-bs-toggle="collapse">

                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#1f1f1f">
                                        <path
                                            d="M620-163 450-333l56-56 114 114 226-226 56 56-282 282Zm220-397h-80v-200h-80v120H280v-120h-80v560h240v80H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v200ZM480-760q17 0 28.5-11.5T520-800q0-17-11.5-28.5T480-840q-17 0-28.5 11.5T440-800q0 17 11.5 28.5T480-760Z" />
                                    </svg>
                                    <span>Products</span>

                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="siebar-product">
                                    <ul class="nav-second-level">
                                        @if (auth()->user()->can('product-list'))
                                            <li>
                                                <a href="{{ route('author.product') }}"><i data-feather="file-plus"></i>
                                                    Product Manage</a>
                                            </li>
                                        @endif

                                    </ul>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
                </li>
                
      <li class="nav-item">
    <a href="{{ route('admin.pixel_setup') }}" class="nav-link {{ request()->routeIs('admin.pixel_setup') ? 'active' : '' }}">
        <i class="nav-icon fas fa-bullseye"></i>
        <span>Pixel Trigger</span>
    </a>
</li>




<li>
    <a href="{{ route('admin.recruitment.index') }}">
        <i class="fe-users"></i>
        <span> Recruitment Page </span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.courier.import') }}" class="nav-link">
        <i class="nav-icon fas fa-file-excel"></i>
        <span>Courier Reconciliation</span>
    </a>
</li>


<li>
    <a href="{{ route('admin.moderator.report') }}">
        <i class="fas fa-chart-line"></i>
        <span> My Report </span>
    </a>
</li>
                <!-- nav items end -->

                <li>
                    <a href="{{ route('admin.system.update.index') }}">
                        <i data-feather="refresh-cw"></i>
                        <span> System Update </span>
                    </a>
                </li>

                </ul>
                
            </div>
            <!-- End Sidebar -->

            <div class="clearfix"></div>
        </div>
        <!-- Sidebar -left -->
    </div>
    <!-- Left Sidebar End -->

    <div class="content-page">
        <div class="content">
            @yield('content')
        </div>
        <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 text-end">&copy; {{ $generalsetting->name }} Version: 2.3.8 Powered by <a
                            href="https://wa.me/8801723546492" target="_blank">EiconBD</a></div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-bordered nav-justified" role="tablist">
                <li class="nav-item">
                    <a class="nav-link py-2" data-bs-toggle="tab" href="#chat-tab" role="tab">
                        <i class="mdi mdi-message-text d-block font-22 my-1"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2" data-bs-toggle="tab" href="#tasks-tab" role="tab">
                        <i class="mdi mdi-format-list-checkbox d-block font-22 my-1"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 active" data-bs-toggle="tab" href="#settings-tab" role="tab">
                        <i class="mdi mdi-cog-outline d-block font-22 my-1"></i>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content pt-0">
                <div class="tab-pane" id="chat-tab" role="tabpanel">
                    <form class="search-bar p-3">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Search..." />
                            <span class="mdi mdi-magnify"></span>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" id="tasks-tab" role="tabpanel">
                    <h6 class="fw-medium p-3 m-0 text-uppercase">Working Tasks</h6>
                </div>
                <div class="tab-pane active" id="settings-tab" role="tabpanel">
                    <h6 class="fw-medium px-3 m-0 py-2 font-13 text-uppercase bg-light">
                        <span class="d-block py-1">Theme Settings</span>
                    </h6>

                    <div class="p-3">
                        <div class="alert alert-warning" role="alert"><strong>Customize </strong> the overall color
                            scheme, sidebar menu, etc.</div>

                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Color Scheme</h6>
                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-color" value="light"
                                id="light-mode-check" checked />
                            <label class="form-check-label" for="light-mode-check">Light Mode</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-color" value="dark"
                                id="dark-mode-check" />
                            <label class="form-check-label" for="dark-mode-check">Dark Mode</label>
                        </div>

                        <!-- Width -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Width</h6>
                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-width" value="fluid"
                                id="fluid-check" checked />
                            <label class="form-check-label" for="fluid-check">Fluid</label>
                        </div>
                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-width" value="boxed"
                                id="boxed-check" />
                            <label class="form-check-label" for="boxed-check">Boxed</label>
                        </div>

                        <!-- Menu positions -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Menus (Leftsidebar and Topbar) Positon</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="menu-position" value="fixed"
                                id="fixed-check" checked />
                            <label class="form-check-label" for="fixed-check">Fixed</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="menu-position" value="scrollable"
                                id="scrollable-check" />
                            <label class="form-check-label" for="scrollable-check">Scrollable</label>
                        </div>

                        <!-- Left Sidebar-->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Left Sidebar Color</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="light"
                                id="light-check" />
                            <label class="form-check-label" for="light-check">Light</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="dark"
                                id="dark-check" checked />
                            <label class="form-check-label" for="dark-check">Dark</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="brand"
                                id="brand-check" />
                            <label class="form-check-label" for="brand-check">Brand</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="gradient"
                                id="gradient-check" />
                            <label class="form-check-label" for="gradient-check">Gradient</label>
                        </div>

                        <!-- size -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Left Sidebar Size</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-size" value="default"
                                id="default-size-check" checked />
                            <label class="form-check-label" for="default-size-check">Default</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-size" value="condensed"
                                id="condensed-check" />
                            <label class="form-check-label" for="condensed-check">Condensed <small>(Extra Small
                                    size)</small></label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-size" value="compact"
                                id="compact-check" />
                            <label class="form-check-label" for="compact-check">Compact <small>(Small
                                    size)</small></label>
                        </div>

                        <!-- User info -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Sidebar User Info</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="sidebar-user" value="fixed"
                                id="sidebaruser-check" />
                            <label class="form-check-label" for="sidebaruser-check">Enable</label>
                        </div>

                        <!-- Topbar -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Topbar</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="topbar-color" value="dark"
                                id="darktopbar-check" checked />
                            <label class="form-check-label" for="darktopbar-check">Dark</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="topbar-color" value="light"
                                id="lighttopbar-check" />
                            <label class="form-check-label" for="lighttopbar-check">Light</label>
                        </div>

                        <div class="d-grid mt-4">
                            <button class="btn btn-primary" id="resetBtn">Reset to Default</button>
                            <a href="https://1.envato.market/uboldadmin" class="btn btn-danger mt-3" target="_blank"><i
                                    class="mdi mdi-basket me-1"></i> Purchase Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end slimscroll-menu-->
    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <!-- Vendor js -->
    <script src="{{ asset('public/backEnd/') }}/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="{{ asset('public/backEnd/') }}/assets/js/app.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    <script src="{{ asset('public/backEnd/') }}/assets/js/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // Function to play the sound when a new order comes in
            function playSound() {
                if (window.Audio !== undefined) {
                    navigator.mediaDevices.getUserMedia({
                        audio: true
                    })
                        .then(() => {
                            // User granted permission, proceed with audio playback
                            const audio = new Audio('audio/success.mp3');

                            audio.play();
                        })
                        .catch(err => {
                            console.error('User denied audio permission:', err);
                            // Handle the case where the user denied permission
                            // (e.g., display a message to the user)
                        });
                } else {
                    console.error('Audio playback is not supported in this browser.');
                }
            }

            // Function to reload the notifications (or fetch new orders)
            function loadNewOrders() {

                $.ajax({
                    url: '{{ route('sound.order', ['slug' => 'pending']) }}', // Adjust the route URL if needed
                    type: 'GET',
                    success: function (data) {

                        if (data === "Succefull") {

                            playSound(); // Play sound if the response indicates a new order
                        }
                    },
                    error: function () {
                        console.error('Error fetching new orders.');
                    }
                });
            }

            // Trigger the function every 10 seconds to check for new orders
            setInterval(loadNewOrders, 10000); //
        });
        $(".delete-confirm").click(function (event) {
            var form = $(this).closest("form");
            event.preventDefault();
            swal({
                title: `Are you sure you want to delete this record?`,
                text: "If you delete this, it will be gone forever.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
        $(".change-confirm").click(function (event) {
            var form = $(this).closest("form");
            event.preventDefault();
            swal({
                title: `Are you sure you want to change this record?`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
    </script>
    <!--patho courier-->
    <script type="text/javascript">
        $(document).ready(function () {
            $('.pathaocity').change(function () {
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('admin/pathao-city') }}?city_id=" + id,
                        success: function (res) {
                            if (res && res.data && res.data.data) {
                                $(".pathaozone").empty();
                                $(".pathaozone").append('<option value="">Select..</option>');
                                $.each(res.data.data, function (index, zone) {
                                    $(".pathaozone").append('<option value="' + zone
                                        .zone_id + '">' + zone.zone_name +
                                        '</option>');
                                    $('.pathaozone').trigger("chosen:updated");
                                });
                            } else {
                                $(".pathaoarea").empty();
                                $(".pathaozone").empty();
                            }
                        }
                    });
                } else {
                    $(".pathaoarea").empty();
                    $(".pathaozone").empty();
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.pathaozone').change(function () {
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('admin/pathao-zone') }}?zone_id=" + id,
                        success: function (res) {
                            if (res && res.data && res.data.data) {
                                $(".pathaoarea").empty();
                                $(".pathaoarea").append('<option value="">Select..</option>');
                                $.each(res.data.data, function (index, area) {
                                    $(".pathaoarea").append('<option value="' + area
                                        .area_id + '">' + area.area_name +
                                        '</option>');
                                    $('.pathaoarea').trigger("chosen:updated");
                                });
                            } else {
                                $(".pathaoarea").empty();
                            }
                        }
                    });
                } else {
                    $(".pathaoarea").empty();
                }
            });
        });
    </script>
    @yield('script')
    {{-- মিডিয়া ম্যানেজার ইনক্লুড করা হলো --}}
    @include('backEnd.layouts.media_manager_modal')
</body>

</html>