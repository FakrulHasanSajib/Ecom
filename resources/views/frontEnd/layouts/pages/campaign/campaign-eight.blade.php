<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign_data->name }} - {{ $generalsetting->name }}</title>
    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}" type="image/x-icon" />
    
    <meta name="app-url" content="{{ route('campaign', $campaign_data->slug) }}" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ strip_tags($campaign_data->short_description ?? '') }}" />
    <meta property="og:title" content="{{ $campaign_data->name }}" />
    <meta property="og:image" content="{{ asset($campaign_data->image_one) }}" />

    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/owl.theme.default.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/toastr.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-color: #ff4757;
            --text-main: #2f3542;
            --bg-light: #f1f2f6;
            --white: #ffffff;
            --shadow-soft: 0 10px 30px rgba(0,0,0,0.08);
            --shadow-hover: 0 15px 40px rgba(0,0,0,0.12);
        }

        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Hero Section */
        .modern-hero {
            background: var(--primary-gradient);
            padding: 80px 0 100px;
            color: white;
            position: relative;
            text-align: center;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            /* ভিডিও না থাকলে প্যাডিং কম হবে */
            padding-bottom: {{ $campaign_data->video ? '120px' : '60px' }};
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .hero-desc {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Video Section */
        .video-card {
            background: white;
            border-radius: 20px;
            padding: 10px;
            box-shadow: var(--shadow-hover);
            margin-top: -80px; 
            position: relative;
            z-index: 10;
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border-radius: 15px;
            background: #000;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
        }

        /* Main Image (If Video is Missing) */
        .main-image-card {
            margin-top: -50px;
            z-index: 10;
            position: relative;
            box-shadow: var(--shadow-hover);
            border-radius: 20px;
            overflow: hidden;
            border: 5px solid #fff;
        }

        /* Buttons */
        .btn-cta {
            background: var(--accent-color);
            color: white;
            font-weight: 700;
            padding: 15px 40px;
            border-radius: 50px;
            border: none;
            box-shadow: 0 5px 15px rgba(255, 71, 87, 0.4);
            transition: transform 0.3s ease;
            font-size: 1.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            background-color: #ff6b81;
            color: white;
            box-shadow: 0 8px 20px rgba(255, 71, 87, 0.6);
        }

        .pulse { animation: pulse-animation 2s infinite; }
        @keyframes pulse-animation {
            0% { box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(255, 71, 87, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 71, 87, 0); }
        }

        /* Features */
        .feature-img-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: 0.3s;
        }
        .content-box {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* Pricing */
        .price-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: var(--shadow-soft);
            border: 2px solid #e1e1e1;
            position: relative;
            overflow: hidden;
        }
        .price-card.active {
            border-color: #667eea;
            background: linear-gradient(to bottom, #fff, #f4f6ff);
        }
        .price-badge {
            background: #ff4757;
            color: white;
            padding: 5px 20px;
            position: absolute;
            top: 20px;
            right: -30px;
            transform: rotate(45deg);
            font-size: 0.8rem;
            width: 120px;
        }

        /* Order Form */
        .order-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow-hover);
            border-top: 5px solid #764ba2;
        }

        .form-control {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 10px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #764ba2;
            background: white;
        }

        .shipping-option {
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .shipping-option.selected {
            border-color: #667eea;
            background: #f0f3ff;
        }

        /* Sticky Footer */
        .sticky-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            padding: 10px 20px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
            display: none;
            z-index: 999;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 1.8rem; }
            .sticky-footer { display: flex; }
            .order-container { padding: 20px; }
            .content-section { padding-bottom: 70px; }
            .video-card { margin-top: -40px; }
        }
    </style>
</head>
<body>

    <section class="modern-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="hero-title animate__animated animate__fadeInDown">
                        {{ $campaign_data->name }}
                    </h1>
                    <div class="hero-desc text-white-50 animate__animated animate__fadeInUp">
                        {!! $campaign_data->short_description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                {{-- ১. যদি ভিডিও থাকে তবেই ভিডিও কার্ড দেখাবে --}}
                @if($campaign_data->video)
                <div class="video-card animate__animated animate__zoomIn">
                    <div class="video-wrapper">
                        <iframe src="https://www.youtube.com/embed/{{ $campaign_data->video }}?autoplay=1&mute=1&controls=1&rel=0" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                
                {{-- ২. যদি ভিডিও না থাকে, কিন্তু ছবি থাকে, তবে ছবি দেখাবে (বিকল্প হিসেবে) --}}
                @elseif($campaign_data->image_one)
                <div class="main-image-card animate__animated animate__zoomIn">
                     <img src="{{ asset($campaign_data->image_one) }}" class="img-fluid w-100" alt="Product Image">
                </div>
                @endif
                
                <div class="text-center mt-4 mb-5">
                    <a href="#order_form" class="btn-cta pulse cam_order_now">
                        <i class="fas fa-shopping-cart me-2"></i> অর্ডার করতে চাই
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="feature-img-card">
                        <img src="{{ asset($campaign_data->image_section ?? $campaign_data->image_two) }}" class="img-fluid w-100" alt="Features">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="content-box">
                        <h3 class="mb-3 fw-bold" style="color: #764ba2;">পণ্য সম্পর্কে বিস্তারিত:</h3>
                        {!! $campaign_data->description !!}
                    </div>
                </div>
            </div>

            @if($campaign_data->section_desc)
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center p-4 rounded" style="background: #eef2ff; border: 1px dashed #667eea;">
                        <h4 class="fw-bold mb-3 text-primary">কেন এটি স্পেশাল?</h4>
                        <div>{!! $campaign_data->section_desc !!}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    {{-- এখানে ফিল্টার উঠিয়ে দেওয়া হয়েছে যাতে সব ইমেজ শো করে --}}
    @if(count($campaign_data->images) > 0)
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">গ্যালারি ও রিভিউ</h2>
                <div style="height: 4px; width: 60px; background: var(--accent-color); margin: 10px auto;"></div>
            </div>
            
            <div class="owl-carousel testimonial-slider">
                @foreach($campaign_data->images as $review)
                <div class="item p-2">
                    <div class="rounded shadow-sm overflow-hidden border">
                        <img src="{{ asset($review->image) }}" class="img-fluid" alt="Review Image">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="py-5" style="background: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="price-card active">
                        <div class="price-badge">Offer</div>
                        @if($product->old_price)
                        <h4 class="text-muted text-decoration-line-through">রেগুলার প্রাইস: {{ $product->old_price }} ৳</h4>
                        @endif
                        <h2 class="fw-bold text-danger my-3 display-4">{{ $product->new_price }} ৳</h2>
                        <p class="mb-0 text-success fw-bold"><i class="fas fa-check-circle"></i> ক্যাশ অন ডেলিভারি</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 content-section" id="order_form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-container">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">অর্ডার কনফার্ম করুন</h3>
                            <p class="text-muted">আপনার সঠিক তথ্য দিয়ে নিচের ফর্মটি পূরণ করুন</p>
                        </div>

                        <form action="{{ route('customer.ordersave') }}" method="POST" class="checkout-form">
                            @csrf
                            <input type="hidden" name="order_type" value="landing">

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="fw-bold mb-1">আপনার নাম *</label>
                                    <input type="text" class="form-control" name="name" required placeholder="নাম লিখুন">
                                </div>
                                
                                <div class="col-12">
                                    <label class="fw-bold mb-1">মোবাইল নাম্বার *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required placeholder="017xxxxxxxx">
                                    <small id="phone-error" class="text-danger d-none mt-1">সঠিক ১১ সংখ্যার মোবাইল নাম্বার দিন</small>
                                </div>

                                <div class="col-12">
                                    <label class="fw-bold mb-1">ঠিকানা *</label>
                                    <textarea class="form-control" name="address" rows="2" required placeholder="বাসা নং, রোড নং, এলাকা..."></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="fw-bold mb-2">ডেলিভারি এরিয়া *</label>
                                    @foreach($shippingcharge as $key => $charge)
                                    <div class="shipping-option {{ $key == 0 ? 'selected' : '' }}" onclick="selectShipping(this)">
                                        <div class="d-flex align-items-center">
                                            <input type="radio" name="area" value="{{ $charge->id }}" {{ $key == 0 ? 'checked' : '' }} class="me-2 d-none shipping-radio">
                                            <i class="far {{ $key == 0 ? 'fa-check-circle text-primary' : 'fa-circle text-muted' }} me-2 check-icon"></i>
                                            <span class="fw-bold">{{ $charge->name }}</span>
                                        </div>
                                        <span class="fw-bold text-primary">{{ $charge->amount }} ৳</span>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="col-12 mt-3">
                                    <div class="cartlist border rounded p-3 bg-light">
                                        @include('frontEnd.layouts.ajax.cart')
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn-cta w-100 border-0">
                                        অর্ডার কনফার্ম করুন
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="sticky-footer">
        <div>
            <small class="d-block text-muted" style="font-size: 0.8rem;">Total Price</small>
            <span class="fw-bold fs-5 text-danger">৳ {{ $product->new_price }}</span>
        </div>
        <a href="#order_form" class="btn-cta py-2 px-4" style="font-size: 1rem;">অর্ডার করুন</a>
    </div>

    <footer class="text-center py-4 mt-5" style="background: #2f3542; color: #a4b0be;">
        <div class="container">
            <p class="mb-1 text-white fw-bold">{{ $generalsetting->name }}</p>
            <p class="mb-0 small">হটলাইন: {{ $contact->phone }}</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('public/frontEnd/campainFive/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/js/toastr.min.js') }}"></script>
    {!! Toastr::message() !!}

    <script>
        // Shipping UI Logic
        function selectShipping(element) {
            $('.shipping-option').removeClass('selected');
            $('.check-icon').removeClass('fa-check-circle text-primary').addClass('fa-circle text-muted');
            
            $(element).addClass('selected');
            $(element).find('.check-icon').removeClass('fa-circle text-muted').addClass('fa-check-circle text-primary');
            $(element).find('input[type="radio"]').prop('checked', true).trigger('change');
        }

        $(document).ready(function(){
            // Slider Init
            $('.testimonial-slider').owlCarousel({
                loop:true, margin:15, nav:false, autoplay:true,
                responsive:{ 0:{items:1}, 600:{items:2}, 1000:{items:3} }
            });

            // Smooth Scroll
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top - 80 }, 600);
            });

            // Shipping AJAX
            $('.shipping-radio').change(function(){
                var id = $(this).val();
                $.ajax({
                    type: "GET", url: "{{ route('shipping.charge') }}", data: { id: id },
                    success: function(res) { $('.cartlist').html(res); }
                });
            });
            $(".shipping-radio:checked").trigger("change");

            // Phone Validation
            $('.checkout-form').submit(function(e){
                var phone = $('#phone').val();
                var regex = /^01[3-9][0-9]{8}$/;
                
                if(!regex.test(phone)){
                    e.preventDefault();
                    $('#phone').addClass('is-invalid');
                    $('#phone-error').removeClass('d-none');
                    // Reset Button
                    var btn = $(this).find('button[type="submit"]');
                    btn.prop('disabled', false).text('অর্ডার কনফার্ম করুন');
                } else {
                    var btn = $(this).find('button[type="submit"]');
                    btn.prop('disabled', true).text('প্রসেস হচ্ছে...');
                }
            });

            $('#phone').on('input', function() {
                var phone = $(this).val();
                var regex = /^01[3-9][0-9]{8}$/;
                if(regex.test(phone)) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $('#phone-error').addClass('d-none');
                }
            });

            // Cart Logic
             $(document).on("click", ".cart_remove", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({ type: "GET", data: { id: id }, url: "{{route('cart.remove')}}",
                        success: function (data) { if (data) $(".cartlist").html(data); }
                    });
                }
            });
             $(document).on("click", ".cart_increment", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({ type: "GET", data: { id: id }, url: "{{route('cart.increment')}}",
                        success: function (data) { if (data) $(".cartlist").html(data); }
                    });
                }
            });
            $(document).on("click", ".cart_decrement", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({ type: "GET", data: { id: id }, url: "{{route('cart.decrement')}}",
                        success: function (data) { if (data) $(".cartlist").html(data); }
                    });
                }
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