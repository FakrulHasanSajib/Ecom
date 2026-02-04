<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('title') - {{$generalsetting->name}}</title>
        <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}" alt="Super Ecommerce Favicon" />
        <meta name="author" content="Super Ecommerce" />
        <link rel="canonical" href="{{ url()->current() }}" />
        @stack('seo')
        @stack('css')
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/animate.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/all.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/owl.carousel.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/owl.theme.default.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/mobile-menu.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/select2.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/toastr.min.css" />

        <link rel="stylesheet" href="{{asset('public/frontEnd/css/wsit-menu.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/style.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/responsive.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/main.css')}}" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

        <meta name="facebook-domain-verification" content="38f1w8335btoklo88dyfl63ba3st2e" />

@foreach($pixels as $pixel)
    <script>
        !(function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = "2.0";
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s);
        })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
        
        fbq("init", "{{{$pixel->code}}}");
        // ❌ fbq("track", "PageView");  <-- এই লাইনটি বাদ দেওয়া হয়েছে (ডুপ্লিকেট ফিক্স)
    </script>
    
    @if(isset($activeTiktokPixel) && !empty($activeTiktokPixel->pixel_id))
    <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject = t; var ttq = w[t] = w[t] || []; ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie", "holdConsent", "revokeConsent", "grantConsent"], ttq.setAndDefer = function (t, e) { t[e] = function () { t.push([e].concat(Array.prototype.slice.call(arguments, 0))) } }; for (var i = 0; i < ttq.methods.length; i++)ttq.setAndDefer(ttq, ttq.methods[i]); ttq.instance = function (t) {
                for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++)ttq.setAndDefer(e, ttq.methods[n]); return e
            }, ttq.load = function (e, n) {
                var r = "https://analytics.tiktok.com/i18n/pixel/events.js", o = n && n.partner; ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = r, ttq._t = ttq._t || {}, ttq._t[e] = +new Date, ttq._o = ttq._o || {}, ttq._o[e] = n || {}; n = document.createElement("script")
                ; n.type = "text/javascript", n.async = !0, n.src = r + "?sdkid=" + e + "&lib=" + t; e = document.getElementsByTagName("script")[0]; e.parentNode.insertBefore(n, e)
            };

            // Database theke dynamic ID load hocche
            ttq.load('{{ trim($activeTiktokPixel->pixel_id) }}');
            // ❌ ttq.page(); <-- এই লাইনটি বাদ দেওয়া হয়েছে (ডুপ্লিকেট ফিক্স)

        }(window, document, 'ttq');
    </script>
    @endif
@endforeach

@php
    $gtm = \App\Models\GoogleTagManager::where('status', 1)->first();
@endphp

{{--
@if($gtm)
<script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ trim($gtm->code) }}');
</script>
@endif
--}}


<script>
    // URL থেকে প্যারামিটার বের করার ফাংশন
    function getParam(p) {
        var match = RegExp('[?&]' + p + '=([^&]*)').exec(window.location.search);
        return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
    }

    // gclid থাকলে সেটা কুকিতে ৩০ দিনের জন্য সেভ করা
    var gclid = getParam('gclid');
    if (gclid) {
        var date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000)); // ৩০ দিন
        document.cookie = "gclid=" + gclid + "; expires=" + date.toGMTString() + "; path=/";
    }
</script>

{{-- Dynamic Header Script --}}
    {!! $generalsetting->header_script !!}
</head>

<body class="gotop">
    @if($gtm)

@endif
    @php $subtotal = Cart::instance('shopping')->subtotal(); @endphp
    <div class="mobile-menu">
        <div class="mobile-menu-logo">
            <div class="logo-image">
                <img src="{{asset($generalsetting->white_logo)}}" alt="" />
            </div>
            <div class="mobile-menu-close">
                <i class="fa fa-times"></i>
            </div>
        </div>
        <ul class="first-nav">
            @foreach($menucategories as $scategory)
                <li class="parent-category">
                    <a href="{{url('category/' . $scategory->slug)}}" class="menu-category-name">
                        <img src="{{asset($scategory->image)}}" alt="" class="side_cat_img" />
                        {{$scategory->name}}
                    </a>
                    @if($scategory->subcategories->count() > 0)
                        <span class="menu-category-toggle">
                            <i class="fa fa-chevron-down"></i>
                        </span>
                    @endif
                    <ul class="second-nav" style="display: none;">
                        @foreach($scategory->subcategories as $subcategory)
                            <li class="parent-subcategory">
                                <a href="{{url('subcategory/' . $subcategory->slug)}}"
                                    class="menu-subcategory-name">{{$subcategory->subcategoryName}}</a>
                                @if($subcategory->childcategories->count() > 0)
                                    <span class="menu-subcategory-toggle"><i class="fa fa-chevron-down"></i></span>
                                @endif
                                <ul class="third-nav" style="display: none;">
                                    @foreach($subcategory->childcategories as $childcat)
                                        <li class="childcategory"><a href="{{url('products/' . $childcat->slug)}}"
                                                class="menu-childcategory-name">{{$childcat->childcategoryName}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
    <header id="navbar_top">
        <div class="mobile-header sticky">
            <div class="mobile-logo">
                <div class="menu-bar">
                    <a class="toggle">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </div>
                <div class="menu-logo">
                    <a href="{{route('home')}}"><img src="{{asset($generalsetting->white_logo)}}" alt="" /></a>
                </div>
                <div class="menu-bag">
                    <p class="margin-shopping">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="mobilecart-qty">{{Cart::instance('shopping')->count()}}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="mobile-search">
            <form action="{{route('search')}}">
                <input type="text" placeholder="Search Product ... " value="" class="msearch_keyword msearch_click"
                    name="keyword" />
                <button><i data-feather="search"></i></button>
            </form>
            <div class="search_result"></div>
        </div>



        <div class="main-header">
            {{-- <div class="logo-area">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="logo-header">
                                <div class="main-logo">
                                    <a href="{{route('home')}}"><img src="{{asset($generalsetting->white_logo)}}"
                                            alt="" /></a>
                                </div>
                                <div class="main-search">
                                    <form action="{{route('search')}}">
                                        <input type="text" placeholder="Search Product..."
                                            class="search_keyword search_click" name="keyword" />
                                        <button>
                                            <i data-feather="search"></i>
                                        </button>
                                    </form>
                                    <div class="search_result"></div>
                                </div>
                                <div class="header-list-items">
                                    <ul>
                                        <li class="track_btn">
                                            <a href="{{route('customer.order_track')}}"> <i
                                                    class="fa fa-truck"></i>Track Order</a>
                                        </li>
                                        @if(Auth::guard('customer')->user())
                                        <li class="for_order">
                                            <p>
                                                <a href="{{route('customer.account')}}">
                                                    <i class="fa-regular fa-user"></i>

                                                    {{Str::limit(Auth::guard('customer')->user()->name,14)}}
                                                </a>
                                            </p>
                                        </li>
                                        @else
                                        <li class="for_order">
                                            <p>
                                                <a href="{{route('customer.login')}}">
                                                    <i class="fa-regular fa-user"></i>
                                                    Login / Sign Up
                                                </a>
                                            </p>
                                        </li>
                                        @endif

                                        <li class="cart-dialog" id="cart-qty">
                                            <a href="{{route('customer.checkout')}}">
                                                <p class="margin-shopping">
                                                    <i class="fa-solid fa-cart-shopping"></i>
                                                    <span>{{Cart::instance('shopping')->count()}}</span>
                                                </p>
                                            </a>
                                            <div class="cshort-summary">
                                                <ul>
                                                    @foreach(Cart::instance('shopping')->content() as $key=>$value)
                                                    <li>
                                                        <a href=""><img src="{{asset($value->options->image)}}"
                                                                alt="" /></a>
                                                    </li>
                                                    <li><a href="">{{Str::limit($value->name, 30)}}</a></li>
                                                    <li>Qty: {{$value->qty}}</li>
                                                    <li>
                                                        <p>৳{{$value->price}}</p>
                                                        <button class="remove-cart cart_remove"
                                                            data-id="{{$value->rowId}}"><i
                                                                data-feather="x"></i></button>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                <p><strong>সর্বমোট : ৳{{$subtotal}}</strong></p>
                                                <a href="{{route('customer.checkout')}}" class="go_cart"> অর্ডার করুন
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="container py-1">
                <div class="row align-items-center">
                    <div class="col-md-3 d-flex justify-content-start text-center text-md-start mb-2 mb-md-0">
                        <div class="main-logo">
                            <a href="{{route('home')}}"><img src="{{asset($generalsetting->white_logo)}}" alt="" /></a>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2 mb-md-0">

                        <form class="d-flex" action="{{route('search')}}">

                            <input placeholder="Search Product..."
                                class="form-control border rounded-start-pill search_keyword search_click" type="search"
                                name="keyword">

                            <button class="btn rounded-end-pill px-3" type="submit"
                                style="background-color:{{$generalsetting->headercolor}}">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                        <div class="search_result"></div>
                    </div>

                    <div class="col-md-3 d-flex justify-content-center justify-content-md-end gap-3">
                        <a href="#" class="text-decoration-none text-dark text-center">
                            <i class="bi bi-heart"></i><br><small>Favorite</small>
                        </a>
                        <li class="cart-dialog" id="cart-qty">
                            <a href="{{route('customer.checkout')}}" class="text-decoration-none text-dark text-center">
                                <p class="margin-shopping">
                                    <i
                                        class="bi bi-cart3">{{Cart::instance('shopping')->count()}}</i><br><small>Cart</small>
                                </p>
                            </a>
                            <div class="cshort-summary">
                                <ul>
                                    @foreach(Cart::instance('shopping')->content() as $key => $value)
                                        <li>
                                            <a href=""><img src="{{asset($value->options->image)}}" alt="" /></a>
                                        </li>
                                        <li><a href="">{{Str::limit($value->name, 30)}}</a></li>
                                        <li>Qty: {{$value->qty}}</li>
                                        <li>
                                            <p>৳{{$value->price}}</p>
                                            <button class="remove-cart cart_remove" data-id="{{$value->rowId}}"><i
                                                    data-feather="x"></i></button>
                                        </li>
                                    @endforeach
                                </ul>
                                <p><strong>সর্বমোট : ৳{{$subtotal}}</strong></p>
                                <a href="{{route('customer.checkout')}}" class="go_cart"> অর্ডার করুন </a>
                            </div>
                        </li>
                        <a href="{{route('customer.login')}}" class="text-decoration-none text-dark text-center">
                            <i class="bi bi-box-arrow-in-right"></i><br><small>Login</small>
                        </a>
                        <a href="{{route('customer.register')}}" class="text-decoration-none text-dark text-center">
                            <i class="bi bi-person-plus"></i><br><small>Registration</small>
                        </a>
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:{{$generalsetting->headercolor}}">
                <div class="container">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            style="background-color:{{$generalsetting->headercolor}}">
                            Categories <small>(See All)</small>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($menucategories as $scategory)
                                <li><a class="dropdown-item"
                                        href="{{url('category/' . $scategory->slug)}}">{{$scategory->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>


                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
    @foreach($headerMenu as $hedear)
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ url($hedear['url'] ?? '#') }}">
                {{ $hedear['label'] ?? '' }}
            </a>
        </li>
    @endforeach
</ul>
                    </div>

                    <div class="text-white d-none d-lg-block">
                        <i class="bi bi-cart3"></i> ৳{{$subtotal}} <small>({{Cart::instance('shopping')->count()}}
                            items)</small>
                    </div>
                </div>
            </nav>


        </div>
    </header>
    <div id="content">
        @yield('content')
    </div>

 <footer class="text-light" style="padding: 0; margin: 0;">

    <div class="footer-shape-1 pt-5 pb-4" style="background-color: {{ $generalsetting->footer_color_1 ?? '#1a1a1a' }};">
        <div class="container px-5">
            <div class="row">
                <div class="col-md-4 d-flex flex-column justify-content-between mb-3 mb-md-0">
                    <div class="footer-about mb-3 mb-md-0">
                        <a href="{{route('home')}}">
                            <img src="{{asset($generalsetting->white_logo)}}" alt="" />
                        </a>
                    </div>
                    <p class="text-light">
                        {{$generalsetting->name}} – Where Quality Meets Affordability.
                    </p>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-between mb-3 mb-md-0">
                    <div class="d-flex justify-content-start gap-3 mb-3">
                        @foreach($socialicons as $socialicon)
                            <a href="{{ $socialicon->link }}" class="text-light d-flex flex-column align-items-center text-decoration-none">
                                <div class="rounded-circle border border-light d-flex justify-content-center align-items-center mb-1"
                                     style="width: 40px; height: 40px;">
                                    <i class="{{ $socialicon->icon }}" style="font-size: 20px;"></i>
                                </div>
                                <span>{{ $socialicon->title }}</span>
                            </a>
                        @endforeach
                    </div>
                    <p class="text-light">
                        Follow our social media to get regular updates.
                    </p>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-start gap-2 mb-3 mb-md-0">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                             alt="Google Play" style="height: 40px;">
                        <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                             alt="App Store" style="height: 40px;">
                    </div>
                    <p class="text-light">Keep our apps with you to get the best offers.</p>
                </div>
            </div>
        </div>
    </div>


    <div class="footer-shape-2 py-5" style="background-color: {{ $generalsetting->footer_color_2 ?? '#0e4f35' }};"> 
        <div class="container px-5">
            <div class="row text-start text-white-50">
                <div class="col-md-3 mb-4">
                    <h6 class="text-light mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        @foreach($headerFooter as $footers)
                            <li>
                                <a href="{{ url($footers['urls']) }}" class="text-white-50 text-decoration-none d-block">
                                    {{$footers['labels']}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h6 class="text-light mb-3">Contacts</h6>
                    <p class="text-white-50">Address:<br> {{$contact->address}}</p>
                    <p class="text-white-50">Phone: {{$contact->phone}}</p>
                    <p class="text-white-50">Email: {{$contact->hotmail}}</p>
                </div>

                <div class="col-md-3 mb-4">
                    <h6 class="text-light mb-3">My Account</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50 text-decoration-none">Login</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Order History</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">My Wishlist</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Track Order</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Be an Affiliate partner</a></li>
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h6 class="text-light mb-3">Selle Zone</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50 text-decoration-none">Become A Reseller</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Become A Wholeseller</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Become A Partner</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="footer-shape-3 py-3" style="background-color: {{ $generalsetting->footer_color_3 ?? '#082e1f' }};"> 
        <div class="container px-5">
            <div class="row text-center text-white-50 small align-items-center">
                <div class="col-md-3 mb-2 mb-md-0 d-flex justify-content-start text-white">
                    Copyright © {{ date('Y') }} {{$generalsetting->name}}
                </div>
                
                <div class="col-md-3 mb-2 mb-md-0 d-flex justify-content-start text-white">
                    Powered by: &nbsp;
                    <a class="text-white" href="{{ $generalsetting->footer_link ?? 'https://eiconbd.com/' }}" target="_blank">
                        {{ $generalsetting->footer_text ?? 'EiconBD' }}
                    </a>
                </div>

                <div class="col-md-3 d-flex justify-content-start text-white align-items-center">
                    Payment: &nbsp;
                    <img src="/frontEnd/images/bkash-logo.png" alt="Payments" class="img-fluid" style="max-height: 25px;">
                </div>

                <div class="col-md-3 d-flex justify-content-start align-items-center">
                    Delivery: &nbsp;
                    <img src="/frontEnd/images/Shipping.png" alt="Shipping" class="img-fluid" style="max-height: 25px;">
                </div>
            </div>
        </div>
    </div>

</footer>

    <div class="footer_nav">
        <ul>
            <li>
                <a class="toggle">
                    <span>
                        <i class="fa-solid fa-bars"></i>
                    </span>
                    <span>Category</span>
                </a>
            </li>
            @if(!Request::is('product/*'))
                <li>
                    <a href="https://wa.me/{{$contact->whatsapp}}">
                        <span>
                            <i class="fa-solid fa-message"></i>
                        </span>
                        <span>Message</span>
                    </a>
                </li>

                <li class="mobile_home">
                    <a href="{{route('home')}}">
                        <span><i class="fa-solid fa-home"></i></span> <span>Home</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('customer.checkout')}}">
                        <span>
                            <i class="fa-solid fa-cart-shopping"></i>
                        </span>
                        <span>Cart (<b class="mobilecart-qty">{{Cart::instance('shopping')->count()}}</b>)</span>
                    </a>
                </li>
                @if(Auth::guard('customer')->user())
                    <li>
                        <a href="{{route('customer.account')}}">
                            <span>
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <span>Account</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{route('customer.login')}}">
                            <span>
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <span>Login</span>
                        </a>
                    </li>
                @endif
            @else
                <li class="order-now">
                    <button onclick="return cart_store();" class="quickbtn">
                        <span><i class="fa-solid fa-shopping-cart"></i></span>
                        <span>Order Now</span>
                    </button>
                </li>

                <li>
                    <a href="{{route('customer.checkout')}}">
                        <span>
                            <i class="fa-solid fa-cart-shopping"></i>
                        </span>
                        <span>Cart (<b class="mobilecart-qty">{{Cart::instance('shopping')->count()}}</b>)</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>


    <div class="scrolltop" style="">
        <div class="scroll">
            <i class="fa fa-angle-up"></i>
        </div>
    </div>

    <div id="custom-modal"></div>
    <div id="page-overlay"></div>
    <div id="loading">
        <div class="custom-loader"></div>
    </div>

        <script src="{{asset('public/frontEnd/js/jquery-3.6.3.min.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/owl.carousel.min.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/mobile-menu.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/wsit-menu.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/mobile-menu-init.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/wow.min.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/bundle.min.js')}}"></script>
    <script>
        new WOW().init();
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{asset('public/backEnd/')}}/assets/js/toastr.min.js"></script>
    {!! Toastr::message() !!} @stack('script')
    <script>
        $(".quick_view").on("click", function () {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{route('quickview')}}",
                    success: function (data) {
                        if (data) {
                            $("#custom-modal").html(data);
                            $("#custom-modal").show();
                            $("#loading").hide();
                            $("#page-overlay").show();
                        }
                    },
                });
            }
        });
    </script>

    {{-- *** FIX: Facebook Pixel AddToCart for simple button added here *** --}}
    <script>
        $(".addcartbutton").on("click", function () {
            var id = $(this).data("id");
            var qty = 1;
            if (id) {
                $.ajax({
                    cache: "false",
                    type: "GET",
                    url: "{{url('add-to-cart')}}/" + id + "/" + qty,
                    dataType: "json",
                    success: function (data) {
                        if (data) {
                            toastr.success('Success', 'Product add to cart successfully');

                            // Facebook Pixel AddToCart Event (Simple)
                            fbq('track', 'AddToCart', {
                                content_ids: [id],
                                content_type: 'product',
                                currency: 'BDT'
                            });
                            // End of FIX

                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });


        // *** DYNAMIC ADD TO CART TRACKING CODE - EXISTING GOOD CODE KEPT ***
        $(".cart_store").on("click", function () {
            var id = $(this).data("id");
            var qty = $(this).parent().find("input").val();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id, qty: qty ? qty : 1 },
                    url: "{{route('cart.store')}}",
                    success: function (data) {
                        // Backend Response-এ যদি product data ও success flag থাকে, তাহলে ট্র্যাকিং করবে
                        if (data && data.success && data.product) {
                            toastr.success('Success', 'Product add to cart succfully');

                            const product = data.product;
                            // ডেটা টাইপ নিশ্চিত করে টোটাল ভ্যালু ক্যালকুলেট করা হলো (সুরক্ষিত পদ্ধতি)
                            const total_value = parseFloat(product.price) * parseInt(product.qty);

                            // 1. GTM DataLayer Push (GA4 Enhanced Ecommerce format)
                            window.dataLayer = window.dataLayer || [];
                            window.dataLayer.push({ ecommerce: null }); // আগের ডেটা ক্লিয়ার করা হলো
                            window.dataLayer.push({
                                event: "add_to_cart",
                                ecommerce: {
                                    currency: product.currency || 'BDT',
                                    value: total_value,
                                    items: [{
                                        'item_id': product.id,
                                        'item_name': product.name,
                                        'price': product.price,
                                        'quantity': product.qty,
                                    }]
                                }
                            });

                            // 2. Facebook Pixel AddToCart Event
                            fbq('track', 'AddToCart', {
                                content_ids: [product.id],
                                content_type: 'product',
                                value: total_value,
                                currency: product.currency || 'BDT'
                            });

                            // কার্ট কাউন্ট আপডেট করা
                            cart_count();
                            mobile_cart();

                            // রিডাইরেক্ট লজিক যোগ করা হলো
                            window.location.href = "{{ route('customer.checkout') }}";
                            // *** FIX END ***

                            // return cart_count() + mobile_cart(); // এই লাইনটি এখন অপ্রয়োজনীয়
                        }
                        // যদি backend থেকে product data না আসে, কিন্তু সফল হয়
                        else if (data && data.success) {
                            toastr.success('Success', 'Product add to cart succfully');
                            cart_count();
                            mobile_cart();
                            // রিডাইরেক্ট লজিক যোগ করা হলো
                            window.location.href = "{{ route('customer.checkout') }}";
                            // *** FIX END ***
                        } else {
                            toastr.error('Error', 'Failed to add product or get tracking data.');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        toastr.error('Error', 'An error occurred during cart addition.');
                    }
                });
            }
        });
        // *** DYNAMIC ADD TO CART TRACKING CODE - END ***


        $(".cart_remove").on("click", function () {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{route('cart.remove')}}",
                    success: function (data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart() + cart_summary();
                        }
                    },
                });
            }
        });

        $(".cart_increment").on("click", function () {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{route('cart.increment')}}",
                    success: function (data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        $(".cart_decrement").on("click", function () {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{route('cart.decrement')}}",
                    success: function (data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        function cart_count() {
            $.ajax({
                type: "GET",
                url: "{{route('cart.count')}}",
                success: function (data) {
                    if (data) {
                        $("#cart-qty").html(data);
                    } else {
                        $("#cart-qty").empty();
                    }
                },
            });
        }
        function mobile_cart() {
            $.ajax({
                type: "GET",
                url: "{{route('mobile.cart.count')}}",
                success: function (data) {
                    if (data) {
                        $(".mobilecart-qty").html(data);
                    } else {
                        $(".mobilecart-qty").empty();
                    }
                },
            });
        }
        function cart_summary() {
            $.ajax({
                type: "GET",
                url: "{{route('shipping.charge')}}",
                dataType: "html",
                success: function (response) {
                    $(".cart-summary").html(response);
                },
            });
        }
    </script>
    <script>
        $(".search_click").on("keyup change", function () {
            var keyword = $(".search_keyword").val();
            $.ajax({
                type: "GET",
                data: { keyword: keyword },
                url: "{{route('livesearch')}}",
                success: function (products) {
                    if (products) {
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });
        $(".msearch_click").on("keyup change", function () {
            var keyword = $(".msearch_keyword").val();
            $.ajax({
                type: "GET",
                data: { keyword: keyword },
                url: "{{route('livesearch')}}",
                success: function (products) {
                    if (products) {
                        $("#loading").hide();
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });
    </script>
    <script></script>
    <script></script>
    <script>
        $(".district").on("change", function () {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                data: { id: id },
                url: "{{route('districts')}}",
                success: function (res) {
                    if (res) {
                        $(".area").empty();
                        $(".area").append('<option value="">Select..</option>');
                        $.each(res, function (key, value) {
                            $(".area").append('<option value="' + key + '" >' + value + "</option>");
                        });
                    } else {
                        $(".area").empty();
                    }
                },
            });
        });
    </script>
    <script>
        $(".toggle").on("click", function () {
            $("#page-overlay").show();
            $(".mobile-menu").addClass("active");
        });

        $("#page-overlay").on("click", function () {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
            $(".feature-products").removeClass("active");
        });

        $(".mobile-menu-close").on("click", function () {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
        });

        $(".mobile-filter-toggle").on("click", function () {
            $("#page-overlay").show();
            $(".feature-products").addClass("active");
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".parent-category").each(function () {
                const menuCatToggle = $(this).find(".menu-category-toggle");
                const secondNav = $(this).find(".second-nav");

                menuCatToggle.on("click", function () {
                    menuCatToggle.toggleClass("active");
                    secondNav.slideToggle("fast");
                    $(this).closest(".parent-category").toggleClass("active");
                });
            });
            $(".parent-subcategory").each(function () {
                const menuSubcatToggle = $(this).find(".menu-subcategory-toggle");
                const thirdNav = $(this).find(".third-nav");

                menuSubcatToggle.on("click", function () {
                    menuSubcatToggle.toggleClass("active");
                    thirdNav.slideToggle("fast");
                    $(this).closest(".parent-subcategory").toggleClass("active");
                });
            });
        });
    </script>

    <script>
        var menu = new MmenuLight(document.querySelector("#menu"), "all");

        var navigator = menu.navigation({
            selectedClass: "Selected",
            slidingSubmenus: true,
            // theme: 'dark',
            title: "ক্যাটাগরি",
        });

        var drawer = menu.offcanvas({
            // position: 'left'
        });

        //  Open the menu.
        document.querySelector('a[href="#menu"]').addEventListener("click", (evnt) => {
            evnt.preventDefault();
            drawer.open();
        });
    </script>

    <script>
        // document.addEventListener("DOMContentLoaded", function () {
        //     window.addEventListener("scroll", function () {
        //         if (window.scrollY > 200) {
        //             document.getElementById("navbar_top").classList.add("fixed-top");
        //         } else {
        //             document.getElementById("navbar_top").classList.remove("fixed-top");
        //             document.body.style.paddingTop = "0";
        //         }
        //     });
        // });
        /*=== Main Menu Fixed === */
        // document.addEventListener("DOMContentLoaded", function () {
        //     window.addEventListener("scroll", function () {
        //         if (window.scrollY > 0) {
        //             document.getElementById("m_navbar_top").classList.add("fixed-top");
        //             // add padding top to show content behind navbar
        //             navbar_height = document.querySelector(".navbar").offsetHeight;
        //             document.body.style.paddingTop = navbar_height + "px";
        //         } else {
        //             document.getElementById("m_navbar_top").classList.remove("fixed-top");
        //             // remove padding top from body
        //             document.body.style.paddingTop = "0";
        //         }
        //     });
        // });
        /*=== Main Menu Fixed === */

        $(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $(".scrolltop:hidden").stop(true, true).fadeIn();
            } else {
                $(".scrolltop").stop(true, true).fadeOut();
            }
        });
        $(function () {
            $(".scroll").click(function () {
                $("html,body").animate({ scrollTop: $(".gotop").offset().top }, "1000");
                return false;
            });
        });
    </script>
    <script>
        $(".filter_btn").click(function () {
            $(".filter_sidebar").addClass('active');
            $("body").css("overflow-y", "hidden");
        })
        $(".filter_close").click(function () {
            $(".filter_sidebar").removeClass('active');
            $("body").css("overflow-y", "auto");
        })
    </script>
 
   <script>
    $(document).ready(function() {
        let timeSpent = 0;
        let maxScroll = 0;
        let currentUrl = window.location.href;

        setInterval(function() { timeSpent++; }, 1000);

        $(window).scroll(function() {
            let scrollTop = $(window).scrollTop();
            let docHeight = $(document).height();
            let winHeight = $(window).height();
            let scrollPercent = Math.round((scrollTop / (docHeight - winHeight)) * 100);
            if (scrollPercent > maxScroll) { maxScroll = scrollPercent; }
        });

        setInterval(function() {
            if(document.visibilityState === 'visible') {
                $.ajax({
                    url: "{{ route('ajax.track.activity') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        url: currentUrl,
                        time_spent: timeSpent,
                        scroll_depth: maxScroll
                    },
                    success: function(response) {
                        console.log("Tracking Status: ", response.status); // এটি কনসোলে দেখা যাবে
                    },
                    error: function(xhr) {
                        console.log("Tracking Error: ", xhr.responseText); // এরর থাকলে এখানে আসবে
                    }
                });
            }
        }, 5000); 
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // ১. লগইন ইউজার ডাটা (সার্ভার থেকে)
        var loggedInUser = {
            name: "{{ Auth::check() ? Auth::user()->name : '' }}",
            email: "{{ Auth::check() ? Auth::user()->email : '' }}",
            phone: "{{ Auth::check() ? Auth::user()->phone : '' }}",
            id: "{{ Auth::check() ? Auth::user()->id : '' }}",
            dob: "{{ Auth::check() ? Auth::user()->dob : '' }}"
        };

        // ২. কুকি রিডার হেল্পার
        function getCookie(name) {
            var value = `; ${document.cookie}`;
            var parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        // ৩. ইভেন্ট আইডি জেনারেটর
        function generateEventId() {
            return 'evt_' + Date.now() + '_' + Math.floor(Math.random() * 1000000);
        }

        // ৪. সব ডাটা সংগ্রহ (ইনপুট + লগইন + ব্রাউজার মেমোরি)
        function getUserData() {
            // ইনপুট ফিল্ড সিলেক্টর
            var phoneInput = document.querySelector('input[name="phone"]') || document.querySelector('#phone');
            var nameInput = document.querySelector('input[name="name"]') || document.querySelector('#name');
            var emailInput = document.querySelector('input[name="email"]') || document.querySelector('#email');
            var cityInput = document.querySelector('select[name="district"] option:checked') || document.querySelector('input[name="city"]');
            var zipInput = document.querySelector('input[name="zip"]');
            var countryInput = document.querySelector('input[name="country"]');

            // বর্তমান পেজ থেকে ডাটা নেওয়া
            var phone = phoneInput ? phoneInput.value : loggedInUser.phone;
            var name = nameInput ? nameInput.value : loggedInUser.name;
            var email = emailInput ? emailInput.value : loggedInUser.email;
            
            // 🔥 SMART MEMORY: ব্রাউজার মেমোরি থেকে ডাটা চেক করা (এটিই নতুন অংশ)
            if(!phone) phone = localStorage.getItem('sst_phone') || '';
            if(!email) email = localStorage.getItem('sst_email') || '';
            if(!name)  name  = localStorage.getItem('sst_name') || '';

            // 🔥 ডাটা সেভ করা (ভবিষ্যতের ইভেন্টের জন্য)
            if(phone && phone.length > 5) localStorage.setItem('sst_phone', phone);
            if(email && email.length > 5) localStorage.setItem('sst_email', email);
            if(name && name.length > 2)  localStorage.setItem('sst_name', name);

            // ফোন ফরম্যাটিং (+88)
            if(phone) {
                phone = phone.replace(/[^0-9]/g, ''); 
                if(phone.length === 11 && phone.startsWith('01')) {
                    phone = '+88' + phone;
                } else if (phone.startsWith('880')) {
                    phone = '+' + phone;
                }
            }

            var city = cityInput ? (cityInput.text || cityInput.value) : null;
            var zip = zipInput ? zipInput.value : null;
            var country = countryInput ? countryInput.value : 'bd';
            var dob = document.querySelector('input[name="dob"]') ? document.querySelector('input[name="dob"]').value : loggedInUser.dob;

            var fbp = getCookie('_fbp');
            var fbc = getCookie('_fbc');
            var ttp = getCookie('_ttp');

            return { 
                phone: phone, name: name, email: email, 
                city: city, zip: zip, country: country, dob: dob,
                fbp: fbp, fbc: fbc, ttp: ttp,
                external_id: loggedInUser.id 
            };
        }

        // ৫. মেইন ট্র্যাকিং ফাংশন
        window.trackServerSide = function(eventName, params) {
            var eventID = generateEventId();
            var userData = getUserData(); 

            // --- Browser Pixel (Facebook) ---
            if(typeof fbq !== 'undefined') {
                var firstName = userData.name ? userData.name.split(' ').slice(0, -1).join(' ') : '';
                var lastName = userData.name ? userData.name.split(' ').pop() : '';
                if(!firstName && lastName) { firstName = lastName; lastName = ''; }

                fbq('init', '{{ $pixels->first()->code ?? "" }}', {
                    em: userData.email,
                    ph: userData.phone,
                    fn: firstName,
                    ln: lastName,
                    ct: userData.city,
                    zp: userData.zip,
                    country: userData.country,
                    external_id: userData.external_id,
                    fbc: userData.fbc,
                    fbp: userData.fbp
                });
                fbq('track', eventName, params, { eventID: eventID });
            }

            // --- TikTok Pixel ---
            if(typeof ttq !== 'undefined') {
                if(userData.email || userData.phone) {
                    ttq.identify({
                        email: userData.email,
                        phone_number: userData.phone,
                        external_id: userData.external_id
                    });
                }
                
                if (eventName === 'PageView') {
                     ttq.track('Pageview', {}, { event_id: eventID });
                } else {
                     ttq.track(eventName, params, { event_id: eventID });
                }
            }

            // --- Server Side Data Sending ---
            var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('event_name', eventName);
            formData.append('event_id', eventID);
            formData.append('source_url', window.location.href);
            formData.append('value', params.value || 0);
            
            if(userData.phone) formData.append('user_data[phone]', userData.phone);
            if(userData.name) formData.append('user_data[name]', userData.name);
            if(userData.email) formData.append('user_data[email]', userData.email);
            if(userData.city) formData.append('user_data[city]', userData.city);
            if(userData.zip) formData.append('user_data[zip]', userData.zip);
            if(userData.country) formData.append('user_data[country]', userData.country);
            if(userData.dob) formData.append('user_data[dob]', userData.dob);
            if(userData.fbp) formData.append('user_data[fbp]', userData.fbp);
            if(userData.fbc) formData.append('user_data[fbc]', userData.fbc);
            if(userData.ttp) formData.append('user_data[ttp]', userData.ttp);
            if(userData.external_id) formData.append('user_data[external_id]', userData.external_id);

            if(params.content_ids) {
                params.content_ids.forEach((id, index) => formData.append(`content_ids[${index}]`, id));
            }
            if(params.contents) {
                formData.append('contents', JSON.stringify(params.contents));
            }

            fetch("{{ route('ajax.track.event') }}", { method: "POST", body: formData, keepalive: true })
            .catch(err => console.error('SST Error:', err));
        }

        // ৬. পেজভিউ (৫০০ মিলিসেকেন্ড ডিলে যাতে কুকি সেট হতে পারে)
        setTimeout(function() {
            trackServerSide('PageView', {});
        }, 500); 
    });
</script>

{{-- Dynamic Footer Script --}}
{!! $generalsetting->footer_script !!}
</body>

</html>