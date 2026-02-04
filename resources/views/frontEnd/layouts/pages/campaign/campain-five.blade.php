<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $campaign_data->name ?? 'Campaign' }} - {{ $generalsetting->name ?? 'Shop' }}</title>
    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon ?? '') }}" type="image/x-icon" />
    
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/all.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/owl.theme.default.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/responsive.css') }}" />

   

    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ strip_tags($campaign_data->short_description ?? '') }}" />
    <meta name="keywords" content="{{ $campaign_data->slug }}" />
    
    <meta property="og:title" content="{{ $campaign_data->name ?? 'Campaign Title' }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset($campaign_data->image ?? '') }}" />
    <meta property="og:description" content="{{ strip_tags($campaign_data->short_description ?? '') }}" />

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .price-banner-style {
            font-family: 'Noto Sans Bengali', sans-serif;
            color: #fff;
        }

        .regular-price {
            font-size: 24px;
            font-weight: 400;
            color: #fff;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .cross-price {
            position: relative;
            color: #fff;
            padding: 0 6px;
            display: inline-block;
        }

        .cross-price::before,
        .cross-price::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 3px;
            background-color: orange;
            top: 50%;
            left: 0;
            transform-origin: center;
            transform: scaleX(0);
            animation: crossX 0.8s forwards;
        }

        .cross-price::before {
            transform: rotate(20deg) scaleX(0);
            animation-delay: 0.1s;
        }

        .cross-price::after {
            transform: rotate(-20deg) scaleX(0);
            animation-delay: 0.3s;
        }

        @keyframes crossX {
            to {
                transform: rotate(var(--angle)) scaleX(1);
            }
        }

        .offer-price {
            font-size: 42px;
            font-weight: 700;
            color: orange;
            margin-top: 15px;
            position: relative;
        }

        .offer-price .underline {
            position: relative;
            color: #fff;
            padding: 0 8px;
        }

        .offer-price .underline::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 4px;
            background: orange;
            border-radius: 2px;
            transform: scaleX(0);
            transform-origin: left;
            animation: underlineDraw 1s forwards;
            animation-delay: 0.5s;
        }

        @keyframes underlineDraw {
            to {
                transform: scaleX(1);
            }
        }

        .alert-warning {
            background-color: #fff8e1;
            border-left: 5px solid #f44336;
        }

        .price-banner-style .regular-price {
            font-size: 30px;
        }

        .price-banner-style .offer-price {
            font-size: 60px;
        }

        @media (max-width: 678px) {
            .price-banner-style .regular-price {
                font-size: 24px;
            }

            .price-banner-style .offer-price {
                font-size: 30px;
                margin-top: 0px;
            }
        }

        .warning-container {
            margin: 30px auto;
            border: 3px dashed red;
            background-color: white;
        }

        .warning-header {
            background-color: #0b1e36;
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            padding: 10px;
        }

        .warning-body {
            padding: 20px;
            font-size: 1.1rem;
            color: #000;
            text-align: justify;
            line-height: 1.9;
        }

        button.order_place {
            background: linear-gradient(135deg, #28a745, #2ecc71);
            color: #fff;
            display: block;
            width: 100%;
            padding: 12px 0;
            font-size: 20px;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        button.order_place:hover {
            background: linear-gradient(135deg, #2ecc71, #28a745);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            transform: translateY(-2px);
        }
        
        .price-banner-style {
            text-align: center;
            color: #fff;
        }

        .price-banner-style .regular-price {
            text-decoration: line-through;
            color: #ffcccc;
            font-weight: bold;
        }

        .price-banner-style .offer-price {
            font-size: 48px;
            font-weight: bold;
            margin-top: 10px;
        }

        .price-banner-style .offer-label {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 15px;
            background-color: #ff5722;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            border-radius: 25px;        
            
        }
    </style>
</head>

<body>
    <section style="background-image: radial-gradient(at center center, #1877F2 28%, #0a4898 79%)">
        <div class="container py-2 py-md-4">
            <div class="row gy-2">
                <div class="col-md-7">
                    <h4 class="text-light text-center py-2 py-md-4 fw-bolder">
                        {{ $campaign_data->name }}
                        <span class="text-warning"> সময় শেষ হলে অফার আর আসবে না — অর্ডার করুন এখনই!</span>
                    </h4>
                </div>
                <div class="col-md-5">
                    <div class="countdown-container">
                        <div class="countdown" id="countdown">
                            <div class="row g-1">
                                <div class="col-3">
                                    <div class="counter-card">
                                        <div id="days"></div>
                                        <span>Days</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="counter-card">
                                        <div id="hours"></div>
                                        <span>Hours</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="counter-card">
                                        <div id="minutes"></div>
                                        <span>Minutes</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="counter-card">
                                        <div id="seconds"></div>
                                        <span>Seconds</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container py-2 py-md-4">
            <div class="py-2 py-md-4 rounded" style="border:2px dashed green">
                <h2 class="animated-heading text-center">{{ $campaign_data->name }}</h2>
            </div>
        </div>
    </section>

    <section>
        <div class="container py-2 py-md-4">
            <div class="row gy-2">
                <div class="col-sm-6" style="border:1px dashed green; padding:10px;">
                    <img class="img-fluid shadow" src="{{ asset($campaign_data->image_one) }}" alt="{{ $campaign_data->name }}">

                    <div class="py-2 py-md-4 rounded" style="border:1px dashed green; margin:10px;">
                        <h2 class="text-center">
                            {{ $campaign_data->name }}
                            <a href="#order_form" class="cam_order_now" id="cam_order_now"> 
                                অর্ডার করতে ক্লিক করুন
                                <i class="fa-solid fa-hand-point-right"></i>
                            </a>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="background-color: #1a5970;">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-sm-12 text-center">
                    <div class="price-banner-style">
                        @if($product->old_price)
                        <p class="regular-price">
                            রেগুলার মূল্য:
                            <span class="cross-price">{{ $product->old_price }} টাকা</span>
                        </p>
                        @endif
                        
                        <p class="offer-price">
                            অফার মূল্য: <span class="underline">{{ $product->new_price }} টাকা</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="rules_sec">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            @if($campaign_data->section_desc)
                            <div class="py-2 py-md-4 rounded" style="border:2px dashed green; text-align: center; font-weight: bold;">
                                {!! $campaign_data->section_desc !!}
                            </div>
                            <br><br>
                            @endif
                            
                            {!! $campaign_data->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="campro_inn">
                        <div class="campro_head">
                            <h2>{{ $campaign_data->name }}</h2>
                        </div>
                        <div class="campro_img_slider owl-carousel">
                            @foreach($campaign_data->images as $image)
                            <div class="campro_img_item">
                                <img src="{{ asset($image->image) }}" alt="{{ $campaign_data->name }}">
                            </div>
                            @endforeach
                        </div>
                        <div class="col-sm-12">
                            <div class="ord_btn">
                                <a href="#order_form" class="cam_order_now" style="padding:10px" id="cam_order_now">
                                    অর্ডার করতে ক্লিক করুন
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    @if($campaign_data->images->where('type', 'review')->count() > 0)
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="rev_inn">
                        <h2 class="campaign_offer">সম্মানিত কাস্টমারদের রিভিউ👇</h2>
                        <div class="review_slider owl-carousel">
                            @foreach($campaign_data->images->where('type', 'review') as $review)
                            <div class="review_item">
                                <img src="{{ asset($review->image) }}" alt="Review">
                            </div>
                            @endforeach
                        </div>
                        <div class="col-sm-12">
                            <div class="ord_btn">
                                <a href="#order_form" class="cam_order_now" id="cam_order_now"> 
                                    অর্ডার করতে ক্লিক করুন 
                                    <i class="fa-solid fa-hand-point-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    
    @if($campaign_data->short_description)
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="warning-container shadow-sm rounded">
                        {!! $campaign_data->short_description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <section class="form_sec">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form_inn">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2 class="campaign_offer text-center text-warning fw-bold mb-4">
                                        অফারটি সীমিত সময়ের জন্য, তাই অফার শেষ হওয়ার আগেই অর্ডার করুন
                                    </h2>

                                    <div class="alert alert-warning border border-danger rounded-3 shadow-sm p-4">
                                        <h5 class="mb-2 fw-bold text-danger">⚠️ বিঃদ্রঃ</h5>
                                        <p class="mb-0 text-dark">
                                            দয়া করে ১০০% নিশ্চিত হয়ে অর্ডার করবেন। ছবি এবং বর্ণনার সাথে পণ্যের মিল থাকা সত্ত্বেও আপনি পণ্য গ্রহণ করতে না চাইলে,
                                            কুরিয়ার চার্জ <strong>১০০ টাকা</strong> কুরিয়ার ডেলিভারি ম্যানকে প্রদান করে পণ্য সাথে সাথে রিটার্ন করবেন।
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row order_by">
                                <div class="col-sm-5 cus-order-2">
                                    <div class="checkout-shipping" id="order_form">
                                        <form action="{{ route('customer.ordersave') }}" method="POST">
                                            @csrf
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="potro_font">আপনার ইনফরমেশন দিন</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group mb-3">
                                                                <label for="name">আপনার নাম লিখুন *</label>
                                                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                                                @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12">
                                                            <div class="form-group mb-3">
                                                                <label for="phone">আপনার মোবাইল নাম্বার লিখুন*</label>
                                                                <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                                                                <span id="phoneError" class="text-danger d-none">
                                                                    ⚠️ সঠিক ১১ সংখ্যার মোবাইল নাম্বার লিখুন (যেমন: 017xxxxxxxx)
                                                                </span>
                                                                @error('phone')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12">
                                                            <div class="form-group mb-3">
                                                                <label for="address">আপনার ঠিকানা লিখুন *</label>
                                                                <textarea name="address" id="address" cols="2" rows="2" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                                                                @error('address')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">ডেলিভারি এরিয়া নিবার্চন করুন *</label>
                                                                <div class="row">
                                                                    @foreach($shippingcharge as $key => $charge)
                                                                    <div class="col-md-12">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input shipping-area-radio" 
                                                                                   type="radio" 
                                                                                   name="area" 
                                                                                   id="area_{{ $charge->id }}" 
                                                                                   value="{{ $charge->id }}"
                                                                                   {{ $key == 0 ? 'checked' : '' }}
                                                                                   required>
                                                                            <label class="form-check-label" for="area_{{ $charge->id }}">
                                                                                {{ $charge->name }} {{ $charge->amount }} টাকা
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <button class="order_place button-animated-border" type="submit">
                                                                    অর্ডার কনফার্ম করুন
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-sm-7 cust-order-1">
                                    <div class="cart_details">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="potro_font">পণ্যের বিবরণ</h5>
                                            </div>
                                            <div class="card-body cartlist table-responsive">
                                                @include('frontEnd.layouts.ajax.cart')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-2 py-md-4" style="background: linear-gradient(to bottom, #89b5ee, #1877F2);">
        <div class="container my-2 my-md-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h2 class="text-center p-2 p-md-4 rounded" style="background-color:#FBEFF7;border:2px dashed #F1ACE7">
                        দিন-রাত ২৪ ঘণ্টা আমাদের প্রতিনিধি সক্রিয় থাকেন। যেকোনো সমস্যায় সাথে সাথে আমাদের সঙ্গে যোগাযোগ করতে পারেন।
                        {{ $contact->phone ?? '01898807307' }}
                    </h2>
                    <div class="row justify-content-center my-3 gy-2">
                        <div class="col-md-6">
                            <div class="shadow-lg">
                                <a href="tel:{{ $contact->phone ?? '01898807307' }}" target="_blank"
                                   class="btn btn-danger btn-lg d-block py-3 fs-5 fw-bolder button-3d button-animated-border">
                                    <i class="fa-solid fa-phone"></i> আমাদের কল করুন
                                </a>
                            </div>
                        </div>

                        @if($contact->whatsapp ?? null)
                        <div class="col-md-6">
                            <div class="shadow-lg">
                                <a href="https://api.whatsapp.com/send?phone={{ $contact->whatsapp }}&text=আমি {{ $campaign_data->name }} অর্ডার করতে চাচ্ছি"
                                   target="_blank"
                                   class="btn btn-success btn-lg d-block py-3 fs-5 fw-bolder button-3d button-animated-border">
                                    <i class="fa-brands fa-whatsapp"></i> হোয়াটসঅ্যাপ
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($contact->messenger ?? null)
                        <div class="col-md-6">
                            <div class="shadow-lg">
                                <a href="{{ $contact->messenger }}" target="_blank"
                                   class="btn btn-primary btn-lg d-block py-3 fs-5 fw-bolder button-3d button-animated-border"
                                   style="background-color: #1a5970;">
                                    <i class="fa-brands fa-facebook-messenger"></i> মেসেঞ্জার
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-0">Copyright © {{ date('Y') }} {{ $generalsetting->name }} | 
                Powered by: <a href="https://eiconbd.com" class="text-warning font-weight-bold">EiconBD</a>
            </p>
        </div>
    </footer>

    <script src="{{ asset('public/frontEnd/campainFive/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/all.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/select2.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/script.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".owl-carousel").owlCarousel({
                margin: 15,
                loop: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                items: 1,
            });
            $('.owl-nav').remove();
        });

        $(document).ready(function() {
            $('.select2').select2();
        });

        // Shipping charge update
        $(document).on("change", ".shipping-area-radio", function() {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('shipping.charge') }}",
                data: { id: id },
                dataType: "html",
                success: function(response) {
                    $('.cartlist').html(response);
                },
                error: function() {
                    alert("Something went wrong. Please try again.");
                }
            });
        });

        $(document).ready(function() {
            $(".shipping-area-radio:checked").trigger("change");
        });

        $('.review_slider').owlCarousel({
            dots: false,
            arrow: false,
            autoplay: true,
            loop: true,
            margin: 10,
            smartSpeed: 1000,
            mouseDrag: true,
            touchDrag: true,
            items: 6,
            responsiveClass: true,
            responsive: {
                300: { items: 1 },
                480: { items: 2 },
                768: { items: 5 },
                1170: { items: 5 },
            }
        });

        $('.campro_img_slider').owlCarousel({
            dots: false,
            arrow: false,
            autoplay: true,
            loop: true,
            margin: 10,
            smartSpeed: 1000,
            mouseDrag: true,
            touchDrag: true,
            items: 3,
            responsiveClass: true,
            responsive: {
                300: { items: 1 },
                480: { items: 2 },
                768: { items: 3 },
                1170: { items: 3 },
            }
        });

        // Cart operations
        $(".cart_remove").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{ route('cart.remove') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            $("#loading").hide();
                        }
                    },
                });
            }
        });

        $(".cart_increment").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{ route('cart.increment') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            $("#loading").hide();
                        }
                    },
                });
            }
        });

        $(".cart_decrement").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{ route('cart.decrement') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            $("#loading").hide();
                        }
                    },
                });
            }
        });

        // Countdown timer
        const deadline = new Date().getTime() + 24 * 60 * 60 * 1000;
        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = deadline - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").innerHTML = days;
            document.getElementById("hours").innerHTML = hours;
            document.getElementById("minutes").innerHTML = minutes;
            document.getElementById("seconds").innerHTML = seconds;
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML =
                    "<span class='countdown-expired'>⏰ This campaign has expired!</span>";
            }
        }, 1000);

        // Phone validation
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form[action='{{ route('customer.ordersave') }}']");
            const submitBtn = form.querySelector(".order_place");
            const phoneInput = document.getElementById("phone");
            const errorEl = document.getElementById("phoneError");

            // Updated regex for common 11-digit Bangladeshi mobile numbers starting with 01
            const regex = /^01[3-9][0-9]{8}$/; 

            function validatePhone() {
                let phone = phoneInput.value.replace(/\D/g, '');
                phoneInput.value = phone;

                if (regex.test(phone)) {
                    errorEl.classList.add("d-none");
                    phoneInput.classList.remove("is-invalid");
                    phoneInput.classList.add("is-valid");
                    return true;
                } else {
                    errorEl.classList.remove("d-none");
                    phoneInput.classList.remove("is-valid");
                    phoneInput.classList.add("is-invalid");
                    return false;
                }
            }

            // Real-time validation
            phoneInput.addEventListener("input", validatePhone);

            form.addEventListener("submit", function(e) {
                // Perform a final validation
                if (!validatePhone()) {
                    e.preventDefault();
                    phoneInput.focus();
                    return;
                }
                
                // Prevent double submission and show loading
                submitBtn.disabled = true;
                submitBtn.innerText = "প্রসেস হচ্ছে...";

                form.querySelectorAll("input, textarea").forEach(el => {
                    el.readOnly = true;
                });
            });
        });

        // Smooth scrolling for anchor links
        $(document).ready(function() {
            $('.cam_order_now').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                $('html, body').animate({
                    'scrollTop': $(target).offset().top - 20
                }, 1000);
            });
        });

    </script>
<script>
    // ১. পিক্সেল এবং প্রোডাক্ট ডাটা (অপ্টিমাইজড - ক্যাশ মেমোরি থেকে আসবে)
    @php
        // ২৪ ঘন্টার (৮৬৪০০ সেকেন্ড) জন্য পিক্সেল ডাটা ক্যাশ করা হচ্ছে
        $dbPixels = \Illuminate\Support\Facades\Cache::remember('active_fb_pixels', 86400, function () {
            return \App\Models\EcomPixel::where('status', 1)->get();
        });

        $dbTiktokPixels = \Illuminate\Support\Facades\Cache::remember('active_tiktok_pixels', 86400, function () {
            return \App\Models\TiktokPixel::where('status', 1)->get();
        });
        
        // প্রোডাক্ট ডাটা সেটআপ
        $p_id = isset($product) ? $product->id : (isset($campaign_data) ? $campaign_data->id : 0);
        $p_name = isset($product) ? $product->name : (isset($campaign_data) ? $campaign_data->name : 'Campaign Product');
        $p_price = isset($product) ? $product->new_price : 0;
    @endphp

    // ================= FACEBOOK BASE CODE (একবার লোড হবে) =================
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');

    // Facebook Init (লুপ)
    @foreach($dbPixels as $pixel)
        fbq('init', '{{ $pixel->code }}');
    @endforeach

    // ================= TIKTOK BASE CODE (একবার লোড হবে) =================
    !function (w, d, t) {
        w.TiktokAnalyticsObject = t; var ttq = w[t] = w[t] || []; 
        ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie", "holdConsent", "revokeConsent", "grantConsent"]; 
        ttq.setAndDefer = function (t, e) { t[e] = function () { t.push([e].concat(Array.prototype.slice.call(arguments, 0))) } }; 
        for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]); 
        ttq.instance = function (t) { for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]); return e }; 
        ttq.load = function (e, n) { 
            var r = "https://analytics.tiktok.com/i18n/pixel/events.js"; 
            ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = r, ttq._t = ttq._t || {}, ttq._t[e] = +new Date, ttq._o = ttq._o || {}, ttq._o[e] = n || {}; 
            n = document.createElement("script"); n.type = "text/javascript"; n.async = !0; n.src = r + "?sdkid=" + e + "&lib=" + t; 
            e = document.getElementsByTagName("script")[0]; e.parentNode.insertBefore(n, e) 
        };
        
        // TikTok Init (লুপ)
        @foreach($dbTiktokPixels as $tiktok)
            ttq.load('{{ $tiktok->pixel_id }}');
        @endforeach
    }(window, document, 'ttq');

    // ================= MAIN TRACKING LOGIC =================
    document.addEventListener("DOMContentLoaded", function() {
        
        function getCookie(name) {
            var value = `; ${document.cookie}`;
            var parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }
        function generateEventId() {
            return 'evt_' + Date.now() + '_' + Math.floor(Math.random() * 1000000);
        }

        // --- USER DATA COLLECTION ---
        function getUserData() {
            var phoneInput = document.querySelector('input[name="phone"]') || document.querySelector('#phone');
            var nameInput = document.querySelector('input[name="name"]') || document.querySelector('#name');
            
            var phone = phoneInput ? phoneInput.value : localStorage.getItem('sst_phone');
            var name = nameInput ? nameInput.value : localStorage.getItem('sst_name');

            if(phone && phone.length > 5) localStorage.setItem('sst_phone', phone);
            if(name && name.length > 2) localStorage.setItem('sst_name', name);

            if(phone) {
                phone = phone.replace(/[^0-9]/g, ''); 
                if(phone.length === 11 && phone.startsWith('01')) phone = '88' + phone;
            }

            return { 
                phone: phone, name: name, 
                fbp: getCookie('_fbp'), fbc: getCookie('_fbc'), ttp: getCookie('_ttp') 
            };
        }

        // --- TRACKING FUNCTION (Hybrid: Browser + Server) ---
        function trackServerSide(eventName, params) {
            var eventID = generateEventId();
            var userData = getUserData();

            // FB Track (Browser)
            if(typeof fbq !== 'undefined') fbq('track', eventName, params, { eventID: eventID });

            // TikTok Track (Browser)
            if(typeof ttq !== 'undefined') {
                if(userData.phone) {
                    ttq.identify({ phone_number: userData.phone, email: userData.name, external_id: userData.phone });
                }
                
                if (eventName === 'PageView') {
                     ttq.track('Pageview', {}, { event_id: eventID });
                } else if (eventName === 'Contact') {
                     ttq.track('Contact', {}, { event_id: eventID });
                } else {
                     // Product Events
                     ttq.track(eventName, {
                       content_id: params.content_ids ? params.content_ids[0] : null,
                       content_type: 'product',
                       content_name: params.content_name,
                       quantity: 1,
                       price: params.value,
                       value: params.value,
                       currency: params.currency
                     }, { event_id: eventID });
                }
            }

            // Server Side API (CAPI)
            var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('event_name', eventName);
            formData.append('event_id', eventID);
            formData.append('source_url', window.location.href);
            formData.append('value', params.value || 0);
            
            if(userData.phone) formData.append('user_data[phone]', userData.phone);
            if(userData.name) formData.append('user_data[name]', userData.name);
            if(userData.fbp) formData.append('user_data[fbp]', userData.fbp);
            if(userData.fbc) formData.append('user_data[fbc]', userData.fbc);
            if(userData.ttp) formData.append('user_data[ttp]', userData.ttp);
            if(userData.phone) formData.append('user_data[external_id]', userData.phone);

            if(params.content_ids) params.content_ids.forEach((id, index) => formData.append(`content_ids[${index}]`, id));
            if(params.contents) {
                formData.append('contents', JSON.stringify(params.contents));
                formData.append('contents_tiktok', JSON.stringify(params.contents));
            }

            fetch("{{ route('ajax.track.event') }}", { method: "POST", body: formData, keepalive: true }).catch(e=>{});
        }

        // 1. PageView
        setTimeout(function() { trackServerSide('PageView', {}); }, 500);

        // 2. ViewContent
        setTimeout(function() {
            trackServerSide('ViewContent', {
                content_name: '{{ $p_name }}',
                content_ids: ['{{ $p_id }}'],
                content_type: 'product',
                value: {{ $p_price }},
                currency: 'BDT',
                contents: [{ content_id: "{{ $p_id }}", content_type: 'product', quantity: 1, price: {{ $p_price }} }]
            });
        }, 1000);

        // 3. AddToCart (Button Click)
        document.body.addEventListener('click', function(e) {
            var target = e.target.closest('.cam_order_now, .order_place, .order-btn-royal, .btn-order, .btn-islamic, .btn-cta, .btn-pulse, a[href="#order_form"]');
            if (target) {
                console.log('AddToCart Fired');
                trackServerSide('AddToCart', {
                    content_name: '{{ $p_name }}',
                    content_ids: ['{{ $p_id }}'],
                    content_type: 'product',
                    value: {{ $p_price }},
                    currency: 'BDT',
                    contents: [{ content_id: "{{ $p_id }}", content_type: 'product', quantity: 1, price: {{ $p_price }} }]
                });
            }
        });

        // 4. InitiateCheckout (Input Focus)
        var checkoutFired = false;
        var inputs = document.querySelectorAll('input[name="name"], input[name="phone"]');
        inputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                if(!checkoutFired) {
                    checkoutFired = true;
                    console.log('InitiateCheckout Fired');
                    trackServerSide('InitiateCheckout', {
                        content_name: '{{ $p_name }} - Checkout',
                        content_ids: ['{{ $p_id }}'],
                        content_type: 'product',
                        value: {{ $p_price }},
                        currency: 'BDT',
                        num_items: 1,
                        contents: [{ content_id: "{{ $p_id }}", content_type: 'product', quantity: 1, price: {{ $p_price }} }]
                    });
                }
            });
        });

        // 5. WhatsApp Contact (NEW)
        document.body.addEventListener('click', function(e) {
            var target = e.target.closest('a[href*="wa.me"], a[href*="api.whatsapp.com"], a[href*="whatsapp.com"], .whatsapp_float, .btn-whatsapp');
            
            if (target) {
                console.log('WhatsApp Contact Fired');
                trackServerSide('Contact', {
                    content_name: 'WhatsApp Chat',
                    content_category: 'Lead',
                    value: 0,
                    currency: 'BDT'
                });
            }
        });

    });
</script>

</body>
</html>