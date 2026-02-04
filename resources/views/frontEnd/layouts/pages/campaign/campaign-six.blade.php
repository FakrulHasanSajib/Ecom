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






    @php
    // কন্ট্রোলার থেকে না আসলে সরাসরি ডাটাবেস থেকে একটিভ পিক্সেল নিয়ে আসা
    $activeTiktokPixel = $activeTiktokPixel ?? \App\Models\TiktokPixel::where('status', 1)->first();
    // যদি ডাটাবেসে না থাকে তবে আপনার দেওয়া হার্ডকোড আইডিটি ব্যবহার হবে
    $pixelId = ($activeTiktokPixel) ? trim($activeTiktokPixel->pixel_id) : 'D4UM0TJC77U1VCCPO300';
@endphp



    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ strip_tags($campaign_data->short_description ?? '') }}" />
    <meta name="keywords" content="{{ $campaign_data->slug }}" />

    <meta property="og:title" content="{{ $campaign_data->name ?? 'Campaign Title' }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset($campaign_data->image ?? '') }}" />
    <meta property="og:description" content="{{ strip_tags($campaign_data->short_description ?? '') }}" />

    {{-- AL-QURAN THEME STYLES (Islamic Green & Gold Palette) --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;500;600;700&display=swap');

        :root {
            --islamic-green: {{ $campaign_data->primary_color ?? '#0f3d2e' }};
            --emerald-green: #1e4d3b;
            --soft-gold: #d4af37;
            --antique-gold: #c5a028;
            --cream-bg: #fdfbf7;
            --white: #ffffff;
            --text-dark: #1a1a1a;
        }

        body {
            background-color: var(--cream-bg);
            font-family: 'Noto Serif Bengali', serif;
            color: var(--text-dark);
        }

        /* Hero Section Gradient - Green to Emerald */
        .hero-section {
            background: radial-gradient(circle at center, var(--emerald-green) 0%, var(--islamic-green) 100%);
            color: var(--white);
            border-bottom: 5px solid var(--soft-gold);
            padding: 40px 0;
            position: relative;
            overflow: hidden;
        }

        /* Hero overlay pattern (optional SVG pattern could be added here) */
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.3;
        }

        .hero-title {
            color: var(--soft-gold);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-weight: 700;
        }

        .timer-box {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--soft-gold);
            border-radius: 10px;
            padding: 10px;
            backdrop-filter: blur(5px);
        }

        .counter-card span {
            color: var(--soft-gold);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .counter-card div {
            font-size: 2rem;
            font-weight: bold;
            color: var(--white);
        }

        /* Section Headings */
        .islamic-heading {
            color: var(--islamic-green);
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .islamic-heading::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--soft-gold);
        }

        /* Product Image Borders */
        .product-frame {
            border: 8px double var(--soft-gold);
            padding: 5px;
            background: var(--white);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        /* Price Section */
        .price-section {
            background-color: var(--islamic-green);
            background-image: linear-gradient(135deg, var(--islamic-green) 0%, var(--emerald-green) 100%);
            color: var(--white);
            border-top: 3px solid var(--soft-gold);
            border-bottom: 3px solid var(--soft-gold);
            padding: 40px 0;
        }

        .regular-price {
            font-size: 24px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: line-through;
            margin-bottom: 10px;
        }

        .offer-price {
            font-size: 48px;
            font-weight: 700;
            color: var(--soft-gold);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Order Button - Islamic Gold */
        .btn-islamic {
            background: linear-gradient(to bottom, var(--soft-gold), var(--antique-gold));
            color: var(--text-dark);
            border: 2px solid #b89520;
            font-weight: 700;
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(197, 160, 40, 0.4);
            transition: all 0.3s ease;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .btn-islamic:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(197, 160, 40, 0.6);
            background: linear-gradient(to bottom, #e5c34d, #d4af37);
            color: #000;
        }

        /* Form Styling */
        .order-card {
            border: 1px solid var(--soft-gold);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            background: var(--white);
        }

        .order-card-header {
            background-color: var(--islamic-green);
            color: var(--white);
            border-bottom: 3px solid var(--soft-gold);
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }

        /* Info Alerts */
        .alert-update {
            background-color: #fff8e1;
            border: 1px solid var(--soft-gold);
            border-left: 5px solid var(--islamic-green);
            color: var(--islamic-green);
        }

        /* Animations */
        @keyframes pulse-gold {
            0% {
                box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(212, 175, 55, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(212, 175, 55, 0);
            }
        }

        .pulse-btn {
            animation: pulse-gold 2s infinite;
        }

        /* Footer */
        footer {
            background-color: #0a2b20;
            border-top: 1px solid var(--soft-gold);
        }

        /* Mobile Adjustments */
        @media (max-width: 678px) {
            .offer-price {
                font-size: 36px;
            }

            .hero-section {
                padding: 20px 0;
            }
        }

        .cam_order_now {
            background: linear-gradient(to bottom, var(--soft-gold), var(--antique-gold));
            color: var(--text-dark);
            border: 2px solid #b89520;
            font-weight: 700;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(197, 160, 40, 0.4);
            transition: all 0.3s ease;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.3);
            display: inline-block;
            text-decoration: none;
            margin-top: 10px;
        }

        .cam_order_now:hover {
            color: #000;
        }
    </style>
</head>

<body>
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-md-7 text-center text-md-start">
                    <h2 class="hero-title display-5 mb-3">
                        {{ $campaign_data->name }}
                    </h2>
                    <h4 class="mb-4" style="color: #e0e0e0; font-weight: 300;">
                        পবিত্র কোরআন শরীফ - প্রতিটি অক্ষরের সাথে মিশে থাকুক আপনার ইমানী জজবা
                    </h4>
                    <p class="mb-2 text-warning fw-bold"><i class="fas fa-clock"></i> অফারটি শেষ হতে বাকি:</p>
                </div>
                <!-- Countdown -->
                <div class="col-md-5">
                    <div class="timer-box">
                        <div class="countdown" id="countdown">
                            <div class="row g-2 text-center">
                                <div class="col-3">
                                    <div class="counter-card">
                                        <div id="days">00</div>
                                        <span>দিন</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="counter-card">
                                        <div id="hours">00</div>
                                        <span>ঘণ্টা</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="counter-card">
                                        <div id="minutes">00</div>
                                        <span>মিনিট</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="counter-card">
                                        <div id="seconds">00</div>
                                        <span>সেকেন্ড</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TITLE SECTION -->
    <section class="py-5">
        <div class="container text-center">
            <div class="p-4 rounded"
                style="border: 2px dashed var(--islamic-green); background-color: rgba(30, 77, 59, 0.05);">
                <h2 class="islamic-heading mb-0" style="color: var(--islamic-green); font-weight: bold;">
                    {{ $campaign_data->name }}
                </h2>
            </div>
        </div>
    </section>

    <!-- MAIN IMAGE & CALL TO ACTION -->
    <section class="mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="product-frame mb-4">
                        <img class="img-fluid rounded" src="{{ asset($campaign_data->image_one) }}"
                            alt="{{ $campaign_data->name }}">
                    </div>

                    <div class="p-3 rounded mb-4"
                        style="background: rgba(212, 175, 55, 0.1); border: 1px solid var(--soft-gold);">
                        <h3 class="mb-3" style="color: var(--islamic-green); font-weight: bold;">
                            {{ $campaign_data->name }}
                        </h3>
                        <a href="#order_form" class="btn-islamic pulse-btn text-decoration-none d-inline-block">
                            <i class="fas fa-shopping-cart me-2"></i> অর্ডার করতে ক্লিক করুন
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING BANNER -->
    <section class="price-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    @if($product->old_price)
                        <p class="regular-price">
                            রেগুলার মূল্য: {{ $product->old_price }} টাকা
                        </p>
                    @endif

                    <div class="offer-price">
                        অফার মূল্য: {{ $product->new_price }} টাকা
                    </div>

                    <div class="mt-3">
                        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fs-6">
                            <i class="fas fa-truck"></i> দ্রুত ডেলিভারি ইনশাল্লাহ
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DESCRIPTION / RULES -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 text-justify" style="font-size: 1.1rem; line-height: 1.8;">
                            @if($campaign_data->section_desc)
                                <div class="alert alert-update text-center fw-bold mb-4">
                                    {!! $campaign_data->section_desc !!}
                                </div>
                            @endif

                            <!-- Description Content -->
                            <div class="description-content">
                                {!! $campaign_data->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GALLERY SLIDER -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="islamic-heading">আরও ছবি দেখুন</h2>
            </div>
            <div class="campro_inn">
                <div class="campro_img_slider owl-carousel">
                    @foreach($campaign_data->images as $image)
                        <div class="campro_img_item product-frame m-2">
                            <img src="{{ asset($image->image) }}" alt="{{ $campaign_data->name }}">
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="#order_form" class="btn-islamic text-decoration-none">
                        অর্ডার করতে এখানে ক্লিক করুন
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- REVIEWS (If any) -->
    @if($campaign_data->images->where('type', 'review')->count() > 0)
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-4">
                    <h2 class="islamic-heading">গ্রাহকদের মন্তব্য (রিভিউ)</h2>
                </div>
                <div class="review_slider owl-carousel">
                    @foreach($campaign_data->images->where('type', 'review') as $review)
                        <div class="review_item p-2">
                            <img src="{{ asset($review->image) }}" class="shadow-sm rounded" alt="Review">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SHORT DESCRIPTION / WARNING -->
    @if($campaign_data->short_description)
        <section class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="p-4 rounded shadow-sm text-center"
                            style="background-color: #fff8e1; border: 2px dashed #f57f17;">
                            {!! $campaign_data->short_description !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- ORDER FORM SECTION -->
    <section class="py-5" style="background-color: rgba(30, 77, 59, 0.05);" id="order_form">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 text-center">
                    <h2 class="fw-bold mb-3" style="color: var(--islamic-green);">অফারটি সীমিত সময়ের জন্য!</h2>
                    <div class="alert alert-danger shadow-sm">
                        <i class="fas fa-exclamation-triangle"></i>
                        বিঃদ্রঃ নিশ্চিত হয়ে অর্ডার করুন। অহেতুক অর্ডার করে আমাদের এবং আপনার মূল্যবান সময় নষ্ট করবেন না।
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- Shopping Cart Summary -->
                <div class="col-lg-5 order-lg-2 mb-4">
                    <div class="order-card h-100">
                        <div class="order-card-header text-center">
                            <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> আপনার অর্ডার</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="cartlist" style="padding: 15px;">
                                @include('frontEnd.layouts.ajax.cart')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="col-lg-7 order-lg-1">
                    <div class="checkout-shipping">
                        <form action="{{ route('customer.ordersave') }}" method="POST">
                            @csrf
                            <div class="order-card">
                                <div class="order-card-header">
                                    <h5 class="mb-0 text-center">আপনার তথ্য দিন (Billing Info)</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="name" class="form-label fw-bold">নাম *</label>
                                            <input type="text" id="name"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" placeholder="আপনার নাম লিখুন" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="phone" class="form-label fw-bold">মোবাইল নাম্বার *</label>
                                            <input type="text" id="phone"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                value="{{ old('phone') }}" placeholder="017xxxxxxxx" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="address" class="form-label fw-bold">ঠিকানা *</label>
                                            <textarea name="address" id="address" rows="3"
                                                class="form-control @error('address') is-invalid @enderror"
                                                placeholder="রোড নং, বাসা নং, থানা, জেলা"
                                                required>{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 mt-4">
                                            <label
                                                class="form-label fw-bold text-success border-bottom border-success pb-1 d-block mb-3">ডেলিভারি
                                                এরিয়া সিলেক্ট করুন *</label>
                                            <div class="bg-light p-3 rounded border">
                                                @foreach($shippingcharge as $key => $charge)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input shipping-area-radio" type="radio"
                                                            name="area" id="area_{{ $charge->id }}"
                                                            value="{{ $charge->id }}" {{ $key == 0 ? 'checked' : '' }}
                                                            required>
                                                        <label class="form-check-label fw-bold" for="area_{{ $charge->id }}"
                                                            style="cursor: pointer;">
                                                            {{ $charge->name }} - {{ $charge->amount }} টাকা
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <button class="btn-islamic w-100 py-3 pulse-btn" type="submit">
                                                <i class="fas fa-check-circle"></i> অর্ডার কনফার্ম করুন
                                            </button>
                                            <p class="text-center text-muted mt-2 small">
                                                <i class="fas fa-lock"></i> ১০০% নিরাপদ এবং বিশ্বাসযোগ্য কেনাকাটা
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CUSTOMER SUPPORT -->
    <section class="py-5" style="background: linear-gradient(to right, #0f3d2e, #1e4d3b);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center text-white">
                    <h2 class="mb-4">প্রয়োজনে কল করুন</h2>
                    <div class="p-4 rounded border border-warning" style="background: rgba(255,255,255,0.1);">
                        <h3 class="mb-3 text-warning">
                            <i class="fas fa-headset"></i> {{ $contact->phone ?? '01898807307' }}
                        </h3>
                        <p class="lead mb-4">যেকোনো সমস্যায় আমরা আছি আপনার পাশে (সকাল ১০টা - রাত ১০টা)</p>

                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="tel:{{ $contact->phone ?? '01898807307' }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-phone-alt"></i> কল করুন
                            </a>

                            @if($contact->whatsapp ?? null)
                                <a href="https://api.whatsapp.com/send?phone={{ $contact->whatsapp }}&text=আমি {{ $campaign_data->name }} অর্ডার করতে চাচ্ছি"
                                    target="_blank" class="btn btn-success btn-lg border-2">
                                    <i class="fab fa-whatsapp"></i> হোয়াটসঅ্যাপ
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="text-white text-center py-4">
        <div class="container">
            <p class="mb-0 opacity-75">Copyright © {{ date('Y') }} {{ $generalsetting->name }} | All Rights Reserved</p>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="{{ asset('public/frontEnd/campainFive/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/all.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/select2.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/script.js') }}"></script>

    <script>
        $(document).ready(function () {
            $(".owl-carousel").owlCarousel({
                margin: 15,
                loop: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 4000,
                autoplayHoverPause: true,
                items: 1,
            });
            $('.owl-nav').remove();

            $('.select2').select2();
        });

        // Shipping charge update logic
        $(document).on("change", ".shipping-area-radio", function () {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('shipping.charge') }}",
                data: { id: id },
                dataType: "html",
                success: function (response) {
                    $('.cartlist').html(response);
                },
                error: function () {
                    alert("Something went wrong. Please try again.");
                }
            });
        });

        // Trigger default shipping logic
        $(document).ready(function () {
            $(".shipping-area-radio:checked").trigger("change");
        });

        // Additional Carousel configs
        $('.review_slider').owlCarousel({
            dots: false,
            autoplay: true,
            loop: true,
            margin: 10,
            smartSpeed: 1000,
            items: 3,
            responsive: {
                0: { items: 1 },
                600: { items: 2 },
                1000: { items: 3 }
            }
        });

        $('.campro_img_slider').owlCarousel({
            dots: false,
            autoplay: true,
            loop: true,
            margin: 10,
            smartSpeed: 1000,
            items: 3,
            responsive: {
                0: { items: 1 },
                600: { items: 2 },
                1000: { items: 3 }
            }
        });

        // Cart Actions
        $(".cart_remove").on("click", function () {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{ route('cart.remove') }}",
                    success: function (data) {
                        if (data) $(".cartlist").html(data);
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
                    url: "{{ route('cart.increment') }}",
                    success: function (data) {
                        if (data) $(".cartlist").html(data);
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
                    url: "{{ route('cart.decrement') }}",
                    success: function (data) {
                        if (data) $(".cartlist").html(data);
                    },
                });
            }
        });

        // 24 Hour Countdown
        const deadline = new Date().getTime() + 24 * 60 * 60 * 1000;
        const x = setInterval(function () {
            const now = new Date().getTime();
            const t = deadline - now;
            const days = Math.floor(t / (1000 * 60 * 60 * 24));
            const hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((t % (1000 * 60)) / 1000);

            document.getElementById("days").innerHTML = days;
            document.getElementById("hours").innerHTML = hours;
            document.getElementById("minutes").innerHTML = minutes;
            document.getElementById("seconds").innerHTML = seconds;

            if (t < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "OFFER EXPIRED";
            }
        }, 1000);
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