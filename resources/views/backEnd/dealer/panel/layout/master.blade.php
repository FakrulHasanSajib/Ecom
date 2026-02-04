<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') - {{$generalsetting->name}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}">

    <!-- App css -->
    <link href="{{asset('public/backEnd/')}}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{asset('public/backEnd/')}}/assets/css/app.min.css" rel="stylesheet" type="text/css"
        id="app-default-stylesheet" />
    <link href="{{asset('public/backEnd/')}}/assets/css/bootstrap-dark.min.css" rel="stylesheet" type="text/css"
        id="bs-dark-stylesheet" disabled />
    <link href="{{asset('public/backEnd/')}}/assets/css/app-dark.min.css" rel="stylesheet" type="text/css"
        id="app-dark-stylesheet" disabled />
    <link href="{{asset('backEnd/')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/toastr.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/custom.css">
    @yield('css')
</head>

<body
    data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": false}'>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-end mb-0">

                    <li class="dropdown d-none d-lg-inline-block">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen"
                            href="#">
                            <i class="fe-maximize noti-icon"></i>
                        </a>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="{{asset(Auth::guard('dealer')->user()->image)}}" alt="user-image"
                                class="rounded-circle">
                            <span class="pro-user-name ms-1">
                                {{Auth::guard('dealer')->user()->name}} <i class="mdi mdi-chevron-down"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>
                            <a href="{{route('dealer.profile')}}" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>Profile Settings</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="{{ route('dealer.logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('dealer.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="{{route('dealer.dashboard')}}" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="{{asset($generalsetting->white_logo)}}" alt="" height="22">
                            <!-- <span class="logo-lg-text-light">UBold</span> -->
                        </span>
                        <span class="logo-lg">
                            <img src="{{asset($generalsetting->white_logo)}}" alt="" height="20">
                            <!-- <span class="logo-lg-text-light">U</span> -->
                        </span>
                    </a>

                    <a href="{{route('dealer.dashboard')}}" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="{{asset($generalsetting->white_logo)}}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{asset($generalsetting->white_logo)}}" alt="" height="20">
                        </span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
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
                    <img src="{{asset(Auth::guard('dealer')->user()->image)}}" alt="user-img" title="Mat Helme"
                        class="rounded-circle avatar-md">
                    <div class="dropdown">
                        <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                            data-bs-toggle="dropdown">{{Auth::guard('dealer')->user()->name}}</a>
                        <div class="dropdown-menu user-pro-dropdown">
                            <!-- item-->
                            <a href="{{ route('dealer.logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item notify-item">
                                <i class="fe-log-out me-1"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                    <p class="text-muted">Dealer</p>
                </div>

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    <ul id="side-menu">
                        <li>
                            <a href="{{route('dealer.dashboard')}}">
                                <i data-feather="monitor"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('dealer.products')}}">
                                <i data-feather="shopping-bag"></i>
                                <span> My Products </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('dealer.order_history')}}">
                                <i data-feather="list"></i>
                                <span> Order History </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('dealer.withdrawals')}}">
                                <i data-feather="dollar-sign"></i>
                                <span> Withdrawals </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('dealer.referrals')}}">
                                <i data-feather="users"></i>
                                <span> Referrals </span>
                            </a>
                        </li>


                        <li>
    <a href="{{ route('dealer.recruitment.index') }}">
        <i class="mdi mdi-account-group"></i>
        <span> My Campaigns </span>
    </a>
</li>

                    </ul>

                </div>
                <!-- Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                @yield('content')
            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>document.write(new Date().getFullYear())</script> &copy; {{$generalsetting->name}}
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page Content here -->
        <!-- ============================================================== -->

    </div>
   
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="{{asset('public/backEnd/')}}/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="{{asset('public/backEnd/')}}/assets/js/app.min.js"></script>
    <script src="{{asset('public/backEnd/')}}/assets/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    @yield('script')
@include('backEnd.layouts.media_manager_modal')
</body>

</html>