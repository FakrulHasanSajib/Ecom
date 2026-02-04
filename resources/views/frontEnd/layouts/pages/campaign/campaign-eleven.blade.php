<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign_data->name }}</title>
    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}" type="image/x-icon" />
    
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/all.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campainFive/owl.theme.default.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/js/toastr.min.css') }}" />

    <style>
        /* === DYNAMIC COLORS & STYLES === */
        :root {
            --brand-color: {{ $campaign_data->primary_color ?? '#c42831' }};
            --text-black: #222;
            --bg-light: #fff0f5;
        }

        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #f9f9f9;
            color: var(--text-black);
            overflow-x: hidden;
        }

        /* HEADER */
        .rozy-header {
            background: var(--brand-color);
            background: linear-gradient(180deg, var(--brand-color) 0%, #000 100%);
            padding: 10px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        /* === BANNER FIX (SMALLER & CENTERED) === */
        .banner-container {
            max-width: 800px; /* এই ভ্যালু কমালে ছবি আরও ছোট হবে */
            margin: 0 auto;
            text-align: center;
            background: #fff; /* ছবির পাশে খালি জায়গায় সাদা থাকবে */
        }
        .banner-image {
            width: 100%;
            height: auto; /* রেশিও ঠিক রেখে ছোট হবে */
            display: block;
            margin: 0 auto;
        }

        /* TITLE BOX */
        .pink-title-box {
            border: 2px dashed var(--brand-color);
            padding: 15px;
            text-align: center;
            margin: 20px auto;
            border-radius: 10px;
            background: #fff;
            max-width: 900px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .pink-title-text {
            color: var(--brand-color);
            font-size: 28px;
            font-weight: 800;
            line-height: 1.3;
        }

        /* BUTTONS */
        .btn-order-now {
            background: var(--brand-color);
            color: #fff;
            font-size: 24px;
            font-weight: 800;
            padding: 14px 50px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 3px solid #fff;
            width: 100%;
            max-width: 450px;
            text-align: center;
            transition: 0.3s;
        }
        .btn-order-now:hover { 
            color: #fff; 
            transform: translateY(-3px); 
            filter: brightness(1.1);
        }
        
        .pulse-animation { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 var(--brand-color); }
            70% { box-shadow: 0 0 0 15px rgba(255, 255, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
        }

        /* DYNAMIC ELEMENTS */
        .description-header {
            color: var(--brand-color);
            font-weight: 800;
            font-size: 26px;
            margin-bottom: 25px;
            text-transform: uppercase;
            position: relative;
            display: inline-block;
        }
        .description-header::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: var(--brand-color);
            margin: 8px auto 0;
            border-radius: 2px;
        }
        
        .premium-quality-section {
            border: 2px solid var(--brand-color);
            background: #fff;
            border-radius: 15px;
            margin: 30px auto;
            max-width: 750px;
            overflow: hidden;
        }
        .premium-header {
            background: var(--brand-color);
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
        }
        .premium-list li i { color: var(--brand-color); }
        .premium-list li:hover { border-color: var(--brand-color); }
        
        .offer-container {
            border: 2px dashed var(--brand-color);
            background: var(--bg-light);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin: 40px 0;
        }
        .digit-val { color: var(--brand-color); font-size: 30px; font-weight: 800; }
        
        /* CHECKOUT BOX - CLICK FIX */
        .checkout-box {
            border: 1px solid #e0e0e0; border-radius: 10px; padding: 15px; margin-bottom: 12px;
            display: flex; align-items: center; background: #fff; cursor: pointer; transition: 0.2s;
            position: relative;
        }
        .checkout-box:hover { background-color: #fdfdfd; }
        .checkout-box.active {
            background: #fff9fc;
            border: 2px solid var(--brand-color);
        }
        
        .form-title { color: var(--brand-color); border-bottom: 2px solid #ddd; }
        .dotted-input:focus { border-color: var(--brand-color); box-shadow: 0 0 0 3px rgba(0,0,0,0.1); }
        
        /* Modern Cart Quantity Buttons */
        .vcart-qty .quantity button { background: #eee; color: var(--brand-color); }
        .vcart-qty .quantity button:hover { background: var(--brand-color); color: #fff; }

        /* GENERAL STYLES */
        .main-slider .item img { width: 100%; border-radius: 8px; border: 1px solid #eee; }
        .owl-nav .owl-prev, .owl-nav .owl-next {
            background: rgba(0,0,0,0.5) !important; color: #fff !important; font-size: 30px !important;
            padding: 5px 15px !important; border-radius: 50%; position: absolute; top: 40%;
        }
        .owl-nav .owl-prev { left: 10px; }
        .owl-nav .owl-next { right: 10px; }
        
        .description-wrapper {
            background: #fff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 30px; margin-top: 20px; border: 1px solid #eee;
        }
        .offer-container {
            border: 2px dashed var(--brand-color);
            background: var(--bg-light);
            border-radius: 15px;
            padding: 30px 15px; /* বামে-ডানে প্যাডিং দেওয়া হলো */
            text-align: center;
            margin: 40px 0;
            overflow: visible; /* লেখা যাতে কেটে না যায় */
        }
        .price-text {
            font-size: 22px; /* একটু ছোট করা হলো সেইফটির জন্য */
            font-weight: 700;
            margin-bottom: 5px;
            line-height: 1.5; /* লাইনের মাঝে ফাঁকা */
            padding: 0 5px; /* লেখার আশেপাশে জায়গা */
        }
        
        .dynamic-feature-container img { max-width: 100%; height: auto; border-radius: 8px; margin: 10px 0; }
        .contact-btn {
            display: block; width: 100%; max-width: 400px; margin: 8px auto; padding: 12px;
            font-size: 18px; font-weight: 700; color: #fff; text-decoration: none;
            border-radius: 50px; text-align: center; transition: 0.3s;
        }
        .contact-btn:hover { color: #fff; opacity: 0.9; transform: scale(1.02); }
        .btn-green { background: #25d366; }
        .btn-blue { background: #0084ff; }
        .footer-bar { background: #222; color: #fff; padding: 25px 0; text-align: center; margin-top: 50px; font-size: 14px; }
        
        /* LIST STYLES */
        .premium-list { list-style: none; padding: 20px 30px; margin: 0; background: #fff5f8; }
        .premium-list li {
            background: #fff; margin-bottom: 12px; padding: 15px 15px 15px 45px;
            border-radius: 8px; font-size: 18px; font-weight: 600; color: #333;
            position: relative; box-shadow: 0 2px 5px rgba(0,0,0,0.03); border: 1px solid #ffeef5; transition: all 0.3s ease;
        }
        .premium-list li i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 22px; width: 25px; text-align: center; }
        
        .timer-wrapper { display: flex; justify-content: center; gap: 15px; margin: 25px 0; }
        .timer-digit { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 10px 15px; min-width: 80px; box-shadow: 0 3px 6px rgba(0,0,0,0.1); }
        .digit-label { font-size: 12px; color: #555; text-transform: uppercase; margin-top: 5px; }
        
        .prod-thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; margin: 0 15px; border: 1px solid #eee; }
        .dotted-input { width: 100%; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; margin-bottom: 15px; background: #fff; font-size: 16px; transition: 0.3s; }
        .vcart-qty .quantity { display: flex !important; align-items: center !important; justify-content: center !important; border: 1px solid #ddd; border-radius: 50px; padding: 4px; width: 130px; margin: 0 auto; background: #fff; }
        .vcart-qty .quantity button { width: 32px; height: 32px; border-radius: 50%; border: none; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; }
        .vcart-qty .quantity input { width: 45px; border: none; text-align: center; font-weight: 700; font-size: 18px; color: #444; background: transparent; padding: 0; margin: 0 5px; }
        .vcart-qty .quantity input:focus { outline: none; }
    </style>
</head>

<body>
    
    @php
        // ল্যান্ডিং পেজে ঢোকার সাথে সাথে কার্ট ক্লিয়ার করে দেওয়া হচ্ছে, যাতে ফ্রেশ ১টা সিলেক্ট হয়
        Cart::instance('shopping')->destroy();
    @endphp

    <div class="rozy-header">
        <div class="container">
            <h5 class="fw-bold text-white m-0"> {{ $campaign_data->banner_title }}</h5>
        </div>
    </div>

    @if($campaign_data->banner)
    <div class="container mt-2">
        <div class="banner-container">
            <img src="{{ asset($campaign_data->banner) }}" alt="Banner" class="banner-image">
        </div>
    </div>
    @endif

    <div class="container mt-4">
        <div class="pink-title-box">
            <h1 class="pink-title-text">{{ $campaign_data->name }}</h1>
        </div>
        
        @if($campaign_data->section_desc)
        <div class="text-center mt-2">
             <h5 class="fw-bold" style="color: #555;">{{ $campaign_data->section_desc }}</h5>
        </div>
        @endif
    </div>

    <div class="container mt-3">
        <div class="main-slider owl-carousel owl-theme">
            @if($campaign_data->image_one)
            <div class="item"><img src="{{ asset($campaign_data->image_one) }}"></div>
            @endif
            @if($campaign_data->image_two)
            <div class="item"><img src="{{ asset($campaign_data->image_two) }}"></div>
            @endif
            @if($campaign_data->image_three)
            <div class="item"><img src="{{ asset($campaign_data->image_three) }}"></div>
            @endif
            @foreach($campaign_data->images as $img)
                @if($img->type != 'review')
                <div class="item"><img src="{{ asset($img->image) }}"></div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="description-wrapper">
                    <div class="text-center">
                        <h3 class="description-header">পণ্যের বিস্তারিত বিবরণ</h3>
                    </div>
                    <div class="dynamic-feature-container mt-3">
                        {!! $campaign_data->description !!} 
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($campaign_data->section_title || $campaign_data->image_section)
    <div class="container mt-5">
        <div class="row align-items-center justify-content-center">
             <div class="col-md-10">
                @if($campaign_data->section_title)
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color: var(--brand-color);">{{ $campaign_data->section_title }}</h2>
                </div>
                @endif
                
                @if($campaign_data->image_section)
                <div class="mb-4 text-center">
                    <img src="{{ asset($campaign_data->image_section) }}" class="img-fluid rounded border" alt="Section Image">
                </div>
                @endif
             </div>
        </div>
    </div>
    @endif

    <div class="container text-center mt-5">
        <a href="#order_form" class="btn-order-now mb-5 pulse-animation">অর্ডার করতে চাই</a>

        <div class="premium-quality-section">
            <div class="premium-header">
                <i class="fas fa-medal me-2"></i> প্রিমিয়াম কোয়ালিটির নিশ্চয়তা
            </div>
            <ul class="premium-list">
                
                    
                    <span style="display:inline-block;">{!! $campaign_data->short_description !!}</span>
                
                {{-- <li><i class="fas fa-check-circle"></i> ১০০% কালার গ্যারান্টি এবং প্রিমিয়াম ফেব্রিক্স</li>
                <li><i class="fas fa-check-circle"></i> ছবি এবং ভিডিও এর সাথে প্রোডাক্টের ১০০% মিল থাকবে</li> --}}
            </ul>
        </div>

        @if($campaign_data->video)
        <div class="mt-5" style="background: var(--brand-color); color: #fff; font-weight: 700; padding: 12px; font-size: 22px; max-width: 650px; margin: 0 auto; border-radius: 10px 10px 0 0;">
            <i class="fab fa-youtube me-2"></i> ড্রেসের লাইভ ভিডিও দেখুন
        </div>
        <div style="max-width: 650px; margin: 0 auto; border: 4px solid var(--brand-color); border-radius: 0 0 10px 10px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/{{ $campaign_data->video }}?rel=0&modestbranding=1" allowfullscreen></iframe>
            </div>
        </div>
        @endif
        
        <div class="container mt-5">
            @if($campaign_data->review)
            <div class="text-center mb-4">
                 <h3 class="description-header" style="font-size: 24px;">{{ $campaign_data->review }}</h3>
            </div>
            @endif
            <div class="row justify-content-center">
                @foreach($campaign_data->images as $img)
                    @if($img->type == 'review')
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ asset($img->image) }}" class="card-img-top rounded" alt="Customer Review">
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="mt-5 mb-5">
            <div class="p-4 bg-white rounded" style="border: 2px dashed var(--brand-color); display: inline-block; width: 100%; max-width: 650px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                {{-- <h4 class="fw-bold mb-4" style="color: var(--brand-color);">প্রিয় মানুষকে মনের মতো সাজিয়ে নিন এই সুন্দর ড্রেস টি গিফট দিয়ে।</h4> --}}
                <a href="#order_form" class="btn-order-now mb-4">অর্ডার করতে চাই</a>
                <div class="mt-2">
                    <a href="tel:{{ $contact->phone ?? '' }}" class="contact-btn btn-green"><i class="fas fa-phone-alt"></i> {{ $contact->phone }}</a>
                    <a href="{{ $socialicons->first()->link ?? '#' }}" class="contact-btn btn-blue">
    <i class="fab fa-facebook-messenger"></i> ফেসবুকে মেসেজ করুন
</a>
                    <a href="https://wa.me/{{ $contact->whatsapp ?? '' }}" class="contact-btn btn-green"><i class="fab fa-whatsapp"></i> {{ $contact->whatsapp }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="offer-container">
            <h3 class="price-text"> ১ সেট- রেগুলার প্রাইজ <span class="old-price">{{ $product->old_price }} টাকা</span></h3>
            <h2 class="price-text"> ১ সেট- অফার প্রাইজ <span class="new-price-badge">{{ $product->new_price }} টাকা</span></h2>
            <h2 class="mt-3 fw-bold">অফার <span class="text-danger">আজই শেষ!</span></h2>

            <div class="timer-wrapper">
                <div class="timer-digit">
                    <div class="digit-val" id="hours">23</div>
                    <div class="digit-label">Hours</div>
                </div>
                <div class="timer-digit">
                    <div class="digit-val" id="minutes">59</div>
                    <div class="digit-label">Minutes</div>
                </div>
                <div class="timer-digit">
                    <div class="digit-val" id="seconds">59</div>
                    <div class="digit-label">Seconds</div>
                </div>
            </div>
            
            <div class="mt-3">
                <h3 class="fw-bold">২ সেট- অফার প্রাইজ <span class="new-price-badge" style="font-size: 35px;">{{ $product->new_price * 2 }} টাকা</span></h3>
            </div>

            <div class="mt-4 text-info fw-bold" style="font-size: 18px;">
                @foreach($shippingcharge as $charge)
                    {{ $charge->name }} চার্জ <span class="text-danger">{{ $charge->amount }}</span> টাকা<br>
                @endforeach
            </div>
        </div>
    </div>

    <div id="order_form" class="container pb-5">
        <div class="row justify-content-center">
            <form action="{{ route('customer.ordersave') }}" method="POST" class="checkout-form row">
                @csrf
                <input type="hidden" name="order_type" value="landing">

                <div class="col-12 text-center mb-5">
                    <h3 class="fw-bold border-bottom d-inline-block pb-2" style="border-width: 3px !important; color: var(--brand-color);">অর্ডার কনফার্ম করতে নিচের ফর্মটি পূরণ করুন</h3>
                </div>

                <div class="col-12 mb-4">
                    <h5 class="fw-bold mb-3">কয়টি ড্রেস নিতে চান সিলেক্ট করুন:</h5>
                    <div class="row g-3">
                        @foreach($products as $key => $item)
                        <div class="col-md-6">
                            <label class="checkout-box {{ $key == 0 ? 'active' : '' }}" style="cursor: pointer;">
                                <input type="checkbox" id="product_check_{{$item->id}}" value="{{$item->id}}" 
                                       class="form-check-input" style="transform: scale(1.4); margin-left: 5px;"
                                       onchange="toggleProduct(this, '{{$item->id}}')"
                                       {{ $key == 0 ? 'checked' : '' }}>
                                
                                <img src="{{ asset($item->image->image) }}" class="prod-thumb">
                                
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1" style="font-size: 18px;">{{ $item->name }} × 1</h6>
                                    <h6 class="fw-bold mb-0 text-danger" style="font-size: 18px;">{{ $item->new_price }} টাকা</h6>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6">
                    <h4 class="form-title">কাস্টমার ইনফরমেশন</h4>
                    <div class="mb-3">
                        <label class="fw-bold mb-2">আপনার নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="dotted-input" placeholder="আপনার নাম লিখুন" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold mb-2">আপনার মোবাইল নাম্বার <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" id="phoneInput" class="dotted-input" placeholder="017xxxxxxxx" required>
                        <small id="phone-error" class="text-danger d-none fw-bold">১১ সংখ্যার সঠিক নাম্বার দিন</small>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold mb-2">আপনার সম্পূর্ণ ঠিকানা <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="dotted-input" placeholder="বাসা নং, রোড নং, থানা, জেলা" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4 class="form-title">অর্ডার ইনফরমেশন</h4>
                    <div class="bg-white p-4 rounded" style="box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #eee;">
                        
                        <div class="cartlist">
                            @include('frontEnd.layouts.ajax.cart')
                        </div>

                        <div class="mt-4">
                            <label class="fw-bold mb-2">শিপিং মেথড সিলেক্ট করুন:</label>
                            @foreach($shippingcharge as $key => $charge)
                            <div class="form-check mb-2 p-3 border rounded">
                                <input class="form-check-input shipping-area-radio" type="radio" name="area" id="ship_{{$charge->id}}" value="{{$charge->id}}" {{ $key == 0 ? 'checked' : '' }} style="margin-top: 5px;">
                                <label class="form-check-label fw-bold ms-2" for="ship_{{$charge->id}}">
                                    {{$charge->name}} - {{$charge->amount}} ৳
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn-order-now w-100 mt-4">অর্ডার কনফার্ম করুন</button>
                    </div>

                    <div class="alert alert-warning mt-4 text-center small" style="border: 2px dashed #f0ad4e; background: #fff8e1;">
                        <i class="fas fa-exclamation-circle text-danger"></i> <span class="text-danger fw-bold">বিঃদ্রঃ</span> ১০০% কনফার্ম হয়ে অর্ডার করুন। <span class="text-danger fw-bold">ছবি এবং ভিডিও এর সাথে প্রডাক্ট এর মিল থাকবে।</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="footer-bar">
        <p class="mb-0">Copyright © {{ date('Y') }} {{ $generalsetting->name }}. All Rights Reserved.</p>
    </div>

    <script src="{{ asset('public/frontEnd/campainFive/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/js/toastr.min.js') }}"></script>
    {!! Toastr::message() !!}

    <script>
        $(document).ready(function() {
            $('.main-slider').owlCarousel({
                items: 2,
                loop: true,
                margin: 15,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
                responsive: {
                    0: { items: 1 },
                    600: { items: 2 }
                }
            });

            $(document).on("change", ".shipping-area-radio", function() {
                let id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('shipping.charge') }}",
                    data: {id: id},
                    success: function(res) { $('.cartlist').html(res); }
                });
            });
            $(".shipping-area-radio:checked").trigger("change");

            $('.checkout-form').submit(function(e) {
                let phone = $('#phoneInput').val().trim();
                if (!/^01[3-9][0-9]{8}$/.test(phone)) {
                    e.preventDefault();
                    $('#phoneInput').css('border', '1px solid red');
                    $('#phone-error').removeClass('d-none');
                    toastr.error('দয়া করে সঠিক ১১ সংখ্যার মোবাইল নাম্বার দিন');
                }
            });

            // FIRST TIME AUTO ADD TO CART:
            // Since we destroyed the cart in PHP, we MUST add the first product via AJAX now.
            @php
                if($products->count() > 0) {
                     $firstId = $products->first()->id;
                     echo "$.ajax({ type: 'GET', url: '" . route('campaign.cart.add') . "', data: {id: $firstId}, success: function(data) { if(data) $('.cartlist').html(data); } });";
                }
            @endphp
        });

        // EVERGREEN TIMER LOGIC (Resets to 24 hours on every refresh)
        var countDownDate = new Date().getTime() + (24 * 60 * 60 * 1000);

        function startTimer() {
            var now = new Date().getTime();
            var distance = countDownDate - now;

            // Time calculations
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Add leading zeros
            document.getElementById("hours").innerText = hours < 10 ? "0" + hours : hours;
            document.getElementById("minutes").innerText = minutes < 10 ? "0" + minutes : minutes;
            document.getElementById("seconds").innerText = seconds < 10 ? "0" + seconds : seconds;
        }
        setInterval(startTimer, 1000);
        startTimer();

        // Updated Toggle Function
        function toggleProduct(el, id) {
            let checkbox = $(el); 
            let box = checkbox.closest('.checkout-box');
            
            // Visual Update
            if(checkbox.is(':checked')) {
                box.addClass('active');
            } else {
                box.removeClass('active');
            }

            // AJAX Update
            let url = checkbox.is(':checked') ? "{{ route('campaign.cart.add') }}" : "{{ route('campaign.cart.remove_item') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {id: id},
                success: function(data) { if(data) $(".cartlist").html(data); }
            });
        }
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