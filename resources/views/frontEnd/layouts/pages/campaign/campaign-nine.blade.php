<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign_data->name }}</title>
    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}" type="image/x-icon" />
    
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/owl.theme.default.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/js/toastr.min.css') }}" />

    <style>
        :root {
            --primary: #7c3aed;
            --primary-dark: #6d28d9;
            --secondary: #db2777;
            --accent: #fbbf24;
            --dark: #0a0f1a;
            --glass: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.14);
            --shadow-sm: 0 15px 50px rgba(0,0,0,0.35);
            --shadow-lg: 0 40px 100px rgba(124,58,237,0.45);
            --gradient-hero: linear-gradient(135deg, #4f46e5 0%, #7c3aed 40%, #db2777 100%);
        }

        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: var(--dark);
            color: #e2e8f0;
            overflow-x: hidden;
        }

        .glass {
            background: var(--glass);
            backdrop-filter: blur(28px) saturate(260%);
            -webkit-backdrop-filter: blur(28px) saturate(260%);
            border: 1px solid var(--glass-border);
            border-radius: 36px;
            box-shadow: var(--shadow-sm);
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .glass:hover {
            transform: translateY(-12px);
            box-shadow: var(--shadow-lg);
        }

        /* Hero Section */
        .hero-section {
            background: var(--gradient-hero);
            background-size: 200% 200%;
            animation: gradientFlow 18s ease infinite;
            padding: 140px 0 180px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero-title {
            font-family: 'Poppins', sans-serif;
            font-size: 4.8rem;
            font-weight: 900;
            letter-spacing: -2px;
            background: linear-gradient(to right, white, #f3e8ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 40px rgba(0,0,0,0.6);
            margin-bottom: 1.5rem;
        }

        /* Video Wrapper */
        .video-wrapper {
            margin-top: -160px;
            position: relative;
            z-index: 10;
            padding: 0 30px;
        }

        .video-box {
            border-radius: 40px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 3px solid rgba(255,255,255,0.18);
            background: rgba(0,0,0,0.6);
            padding-bottom: 56.25%;
            position: relative;
        }

        .video-box iframe, .video-box img {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
        }

        /* Pulse Button */
        .btn-pulse {
            background: linear-gradient(45deg, var(--secondary), var(--primary), #a855f7);
            background-size: 300% 300%;
            animation: liquidFlow 12s ease infinite;
            color: white;
            font-weight: 800;
            padding: 20px 70px;
            border-radius: 80px;
            box-shadow: 0 20px 60px rgba(219,39,119,0.5);
            transition: all 0.4s;
        }

        .btn-pulse:hover {
            transform: translateY(-8px) scale(1.06);
            box-shadow: 0 35px 90px rgba(124,58,237,0.7);
        }

        @keyframes liquidFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Gallery */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 2rem;
            padding: 60px 0;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            box-shadow: var(--shadow-sm);
            aspect-ratio: 3 / 4;
            background: #111827;
            transition: all 0.7s ease;
        }

        .gallery-item:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 40px 100px rgba(124,58,237,0.5);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 1s ease, filter 0.6s ease;
            filter: brightness(0.92) contrast(1.05);
        }

        .gallery-item:hover img {
            transform: scale(1.15);
            filter: brightness(1.05) contrast(1.1);
        }

        /* Info Card, Pricing etc. */
        .info-card, .order-box, .testi-card {
            padding: 50px;
            border-radius: 36px;
        }

        .info-title {
            font-size: 2.4rem;
            font-weight: 900;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .price-big {
            font-size: 4.5rem;
            font-weight: 900;
            background: linear-gradient(90deg, var(--accent), #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ====================== Improved Order Form ====================== */
        .form-group {
            position: relative;
            margin-bottom: 2.5rem;
        }

        .form-control {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.18);
            color: white;
            border-radius: 16px;
            padding: 1.75rem 1.25rem 0.75rem;
            font-size: 1.1rem;
            height: 62px;
            transition: all 0.35s ease;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.12);
            border-color: var(--primary);
            box-shadow: 0 0 0 8px rgba(124,58,237,0.25);
            outline: none;
        }

        .form-label {
            position: absolute;
            top: 1.3rem;
            left: 1.25rem;
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
            transition: all 0.35s ease;
            transform-origin: left top;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: 0.4rem;
            left: 1.1rem;
            font-size: 0.82rem;
            color: var(--primary);
            transform: scale(0.9);
            background: var(--dark);
            padding: 0 6px;
        }

        textarea.form-control {
            height: auto;
            padding-top: 1.75rem;
        }

        /* Custom Radio Buttons */
        .shipping-radio {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .shipping-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.5rem;
            background: rgba(255,255,255,0.05);
            border: 2px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.35s ease;
            margin-bottom: 1rem;
        }

        .shipping-label:hover {
            border-color: rgba(124,58,237,0.4);
            background: rgba(124,58,237,0.08);
        }

        .shipping-radio:checked + .shipping-label {
            border-color: var(--primary);
            background: rgba(124,58,237,0.12);
            box-shadow: 0 0 25px rgba(124,58,237,0.3);
        }

        .radio-circle {
            width: 24px;
            height: 24px;
            border: 2px solid #64748b;
            border-radius: 50%;
            position: relative;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .shipping-radio:checked + .shipping-label .radio-circle {
            border-color: var(--primary);
        }

        .shipping-radio:checked + .shipping-label .radio-circle::after {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            width: 10px;
            height: 10px;
            background: var(--primary);
            border-radius: 50%;
            animation: checkPulse 0.6s ease;
        }

        @keyframes checkPulse {
            0% { transform: scale(0); }
            60% { transform: scale(1.4); }
            100% { transform: scale(1); }
        }

        /* Submit Button */
        .btn-submit {
            background: linear-gradient(45deg, var(--primary), var(--primary-dark));
            color: white;
            font-size: 1.35rem;
            font-weight: 700;
            padding: 1.4rem;
            border-radius: 80px;
            border: none;
            box-shadow: 0 20px 60px rgba(124,58,237,0.5);
            transition: all 0.4s ease;
            width: 100%;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-6px);
            box-shadow: 0 35px 90px rgba(124,58,237,0.7);
        }

        .btn-submit:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 3.2rem; }
            .video-wrapper { margin-top: -100px; }
            .order-section { padding-bottom: 120px; }
        }
    </style>
</head>
<body>

    <header class="hero-section">
        <div class="container position-relative">
            <h1 class="hero-title animate__animated animate__fadeInDown">{{ $campaign_data->name }}</h1>
            <p class="lead opacity-90 mb-5">{{ strip_tags($campaign_data->short_description) }}</p>
        </div>
    </header>

    <div class="container video-wrapper">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($campaign_data->video)
                <div class="video-box glass animate__animated animate__zoomIn">
                    <iframe src="https://www.youtube.com/embed/{{ $campaign_data->video }}?autoplay=1&mute=1&controls=1&rel=0" allowfullscreen></iframe>
                </div>
                @elseif($campaign_data->image_one)
                <div class="video-box glass animate__animated animate__zoomIn">
                    <img src="{{ asset($campaign_data->image_one) }}" style="width:100%; height:100%; object-fit:cover;">
                </div>
                @endif
                
                <div class="text-center mt-5 mb-4">
                    <a href="#order_form" class="btn-pulse">
                        <i class="fas fa-shopping-bag me-2"></i> অর্ডার করতে চাই
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="gallery-grid mb-5">
                @if($campaign_data->image_one) <div class="gallery-item glass"><img src="{{ asset($campaign_data->image_one) }}" alt="প্রোডাক্ট"></div> @endif
                @if($campaign_data->image_two) <div class="gallery-item glass"><img src="{{ asset($campaign_data->image_two) }}" alt="প্রোডাক্ট"></div> @endif
                @if($campaign_data->image_three) <div class="gallery-item glass"><img src="{{ asset($campaign_data->image_three) }}" alt="প্রোডাক্ট"></div> @endif
                @if($campaign_data->image_four) <div class="gallery-item glass"><img src="{{ asset($campaign_data->image_four) }}" alt="প্রোডাক্ট"></div> @endif
            </div>

            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="info-card glass">
                        <h3 class="info-title">✨ পণ্যের বিবরণ</h3>
                        <div style="font-size: 1.1rem; line-height: 1.8; color: #cbd5e1;">
                            {!! $campaign_data->description !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    @if($campaign_data->short_description)
                    <div class="info-card glass" style="border-left: 6px solid var(--accent);">
                        <h3 class="info-title" style="color: var(--accent);">🌟 কেন সেরা?</h3>
                        <div style="font-size: 1.05rem;">
                            {!! $campaign_data->short_description !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @php $reviews = json_decode($campaign_data->testimonials, true) ?? []; @endphp
    @if(count($reviews) > 0)
    <section class="testi-sec">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6">কাস্টমার রিভিউ</h2>
                <div style="width: 80px; height: 5px; background: linear-gradient(to right, #ec4899, #8b5cf6); margin: 15px auto; border-radius: 50px;"></div>
            </div>
            
            <div class="owl-carousel testimonial-slider">
                @foreach($reviews as $item)
                <div class="item">
                    <div class="testi-card glass">
                        @if($item['type'] == 'video')
                            <div class="ratio ratio-16x9 mb-3 rounded-3 overflow-hidden shadow-sm">
                                <iframe src="https://www.youtube.com/embed/{{$item['content']}}" allowfullscreen></iframe>
                            </div>
                        @elseif($item['type'] == 'image')
                            <img src="{{ asset($item['image']) }}" class="img-fluid rounded-3 mb-3">
                        @else
                            <i class="fas fa-quote-left text-primary fs-1 mb-3 opacity-25"></i>
                            <p class="fst-italic text-secondary">"{{$item['content']}}"</p>
                        @endif
                        <div class="text-warning small"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <div class="pricing-strip glass my-5 py-4">
        <div class="container">
            <h4 class="mb-2 opacity-75">🔥 সীমিত সময়ের অফার 🔥</h4>
            <div class="d-flex justify-content-center align-items-center gap-3">
                @if($product->old_price) <span class="text-decoration-line-through fs-4 opacity-50">৳{{ $product->old_price }}</span> @endif
                <span class="price-big">৳{{ $product->new_price }}</span>
            </div>
            <p class="mb-0 mt-2 small"><i class="fas fa-truck me-1"></i> দ্রুত ডেলিভারি সুবিধা</p>
        </div>
    </div>

    <section class="order-section py-5" id="order_form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-box glass p-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold display-5">অর্ডার কনফার্ম করুন</h2>
                            <p class="text-secondary fs-5">নিচের তথ্য পূরণ করুন</p>
                        </div>

                        <form action="{{ route('customer.ordersave') }}" method="POST" class="checkout-form">
                            @csrf
                            <input type="hidden" name="order_type" value="landing">
                            
                            <div class="row g-4">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" class="form-control" required placeholder=" " id="nameInput">
                                    <label for="nameInput" class="form-label">আপনার নাম</label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="tel" name="phone" id="phoneInput" class="form-control" required placeholder=" " maxlength="11">
                                    <label for="phoneInput" class="form-label">মোবাইল নাম্বার</label>
                                    <small id="phone-error" class="text-danger d-none mt-1">১১ সংখ্যার সঠিক নাম্বার দিন</small>
                                </div>
                                <div class="col-12 form-group">
                                    <textarea name="address" class="form-control" rows="3" required placeholder=" " id="addressInput"></textarea>
                                    <label for="addressInput" class="form-label">সম্পূর্ণ ঠিকানা</label>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label fw-bold mb-3 fs-5">ডেলিভারি এরিয়া</label>
                                    @foreach($shippingcharge as $key => $charge)
                                    <div>
                                        <input type="radio" name="area" value="{{$charge->id}}" id="ship_{{$key}}" {{$key==0?'checked':''}} class="shipping-radio">
                                        <label for="ship_{{$key}}" class="shipping-label">
                                            <div class="d-flex align-items-center">
                                                <span class="radio-circle me-3"></span>
                                                <span class="fw-bold">{{$charge->name}}</span>
                                            </div>
                                            <span class="fw-bold text-primary">{{$charge->amount}} ৳</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="col-12">
                                    <div class="cartlist bg-dark p-4 rounded-4 border border-secondary">
                                        @include('frontEnd.layouts.ajax.cart')
                                    </div>
                                </div>

                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn-submit">
                                        অর্ডার কনফার্ম করুন <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-black text-white text-center py-4 pb-5 mb-5 mb-md-0">
        <p class="mb-0 small opacity-75">&copy; {{ date('Y') }} {{ $generalsetting->name }}. All rights reserved.</p>
        <p class="mb-0 small">Helpline: <a href="tel:{{ $contact->phone }}" class="text-warning text-decoration-none">{{ $contact->phone }}</a></p>
    </footer>

    <div class="mobile-footer glass">
        <div>
            <small class="text-muted d-block" style="font-size: 10px;">TOTAL PRICE</small>
            <span class="fw-bold fs-5 text-primary">৳{{ $product->new_price }}</span>
        </div>
        <a href="#order_form" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">অর্ডার করুন</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('public/frontEnd/campainFive/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/js/toastr.min.js') }}"></script>
    {!! Toastr::message() !!}

    <script>
        $(document).ready(function(){
            // Owl Carousel
            $('.testimonial-slider').owlCarousel({
                loop:true, margin:20, nav:false, autoplay:true, autoplayTimeout:3000,
                responsive:{ 0:{items:1}, 768:{items:2}, 1000:{items:3} }
            });

            // Shipping Charge Update
            $('input[name="area"]').change(function(){
                let id = $(this).val();
                $.ajax({ url: "{{ route('shipping.charge') }}", data: {id:id}, success: function(res){ $('.cartlist').html(res); } });
            });

            // Form Validation & Animation
            $('.checkout-form').submit(function(e){
                let phone = $('#phoneInput').val().trim();
                if(!/^01[3-9][0-9]{8}$/.test(phone)) {
                    e.preventDefault();
                    $('#phoneInput').addClass('is-invalid');
                    $('#phone-error').removeClass('d-none');
                    var btn = $(this).find('button[type="submit"]');
                    btn.prop('disabled', false).html('অর্ডার কনফার্ম করুন <i class="fas fa-arrow-right ms-2"></i>');
                } else {
                    var btn = $(this).find('button[type="submit"]');
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> প্রসেস হচ্ছে...');
                }
            });
            
            $('#phoneInput').on('input', function() {
                let val = $(this).val().trim();
                if(/^01[3-9][0-9]{8}$/.test(val)) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $('#phone-error').addClass('d-none');
                } else {
                    $(this).removeClass('is-valid');
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