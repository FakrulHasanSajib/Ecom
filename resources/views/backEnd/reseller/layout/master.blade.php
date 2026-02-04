<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') - {{$generalsetting->name}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}">

    <link href="{{asset('public/backEnd/')}}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{asset('public/backEnd/')}}/assets/css/app.min.css" rel="stylesheet" type="text/css"
        id="app-default-stylesheet" />
    <link href="{{asset('public/backEnd/')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/toastr.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/custom.css">
    @yield('css')
</head>

<body
    data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": false}'>

    <div id="wrapper">

        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-end mb-0">
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="{{asset(Auth::guard('reseller')->user()->image ?? 'default/user.png')}}"
                                alt="user-image" class="rounded-circle">
                            <span class="pro-user-name ms-1">
                                {{Auth::guard('reseller')->user()->name}} <i class="mdi mdi-chevron-down"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>
                            <a href="{{route('reseller.profile')}}" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>Profile Settings</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('reseller.logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('reseller.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>

                <div class="logo-box">
                    <a href="{{route('reseller.dashboard')}}" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="{{asset($generalsetting->white_logo)}}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{asset($generalsetting->white_logo)}}" alt="" height="20">
                        </span>
                    </a>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
        <div class="left-side-menu">
            <div class="h-100" data-simplebar>
                <div id="sidebar-menu">
                    <ul id="side-menu">
                        <li>
                            <a href="{{route('reseller.dashboard')}}">
                                <i data-feather="monitor"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('reseller.products')}}">
                                <i data-feather="shopping-bag"></i>
                                <span> Products </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('reseller.orders')}}">
                                <i data-feather="shopping-cart"></i>
                                <span> Order History </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('reseller.wallet')}}">
                                <i data-feather="dollar-sign"></i>
                                <span> My Wallet </span>
                            </a>
                        </li>
                        
                        {{-- 
                            UPDATE: Logic changed to strict check (=== null).
                            Only show if referrer_id is explicitly NULL (Level 1 Reseller).
                        --}}
                        @if(Auth::guard('reseller')->user()->referrer_id === null)
                        <li>
                            <a href="{{route('reseller.referrals')}}">
                                <i data-feather="users"></i>
                                <span> My Referrals </span>
                            </a>
                        </li>
                        @endif

                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="content-page">
            <div class="content">
                @yield('content')
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>document.write(new Date().getFullYear())</script> &copy; {{$generalsetting->name}}
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>
    <script src="{{asset('public/backEnd/')}}/assets/js/vendor.min.js"></script>
    <script src="{{asset('public/backEnd/')}}/assets/js/app.min.js"></script>
    <script src="{{asset('public/backEnd/')}}/assets/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    @yield('script')

</body>

</html>