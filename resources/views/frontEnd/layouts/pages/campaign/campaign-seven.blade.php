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

    {{-- ROYAL ISLAMIC THEME STYLES (Royal Blue & Gold) --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;500;600;700&family=Cinzel:wght@600;700&display=swap');

        :root {
            --royal-blue: #00204a;
            --deep-blue: #00122e;
            --royal-gold: #d4af37;
            --light-gold: #f3e5ab;
            --white: #ffffff;
            --text-dark: #1a1a1a;
        }

        body {
            background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTEgMWgydjJIMUMxeiIgZmlsbD0iI2Q0YWYzNyIgZmlsbC1vcGFjaXR5PSIwLjA1Ii8+PC9zdmc+');
            background-color: #f8f6f2;
            font-family: 'Noto Serif Bengali', serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* --- HERO SECTION --- */
        .royal-hero {
            background: linear-gradient(145deg, var(--deep-blue) 0%, var(--royal-blue) 100%);
            color: var(--white);
            padding: 80px 0 100px 0;
            position: relative;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 50% 100%, 0 90%);
        }

        .bismillah-text {
            font-family: 'Cinzel', serif;
            color: var(--royal-gold);
            letter-spacing: 5px;
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-transform: uppercase;
        }

        .highlight-badge {
            background: linear-gradient(to right, #bf953f, #fcf6ba, #b38728);
            color: var(--deep-blue);
            padding: 8px 25px;
            border-radius: 50px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
        }

        .main-title {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        /* --- 3D PRODUCT CARD --- */
        .product-card-3d {
            position: relative;
            z-index: 10;
            margin-top: -80px;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .card-inner {
            background: var(--white);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--royal-gold);
            position: relative;
        }

        .card-inner::after {
            content: "";
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border: 1px dashed var(--royal-gold);
            border-radius: 25px;
            pointer-events: none;
            z-index: -1;
        }

        /* --- ISLAMIC DIVIDER --- */
        .islamic-divider {
            text-align: center;
            margin: 50px 0;
            position: relative;
        }

        .islamic-divider::before {
            content: "♦";
            color: var(--royal-gold);
            font-size: 2rem;
            background: #f8f6f2;
            padding: 0 15px;
            z-index: 1;
            position: relative;
        }

        .islamic-divider::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 20%;
            right: 20%;
            height: 1px;
            background: var(--royal-gold);
            z-index: 0;
        }

        /* --- FEATURES --- */
        .feature-box {
            background: var(--white);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border-bottom: 3px solid var(--royal-blue);
            transition: transform 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            border-bottom-color: var(--royal-gold);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--royal-gold);
            margin-bottom: 20px;
        }

        /* --- PRICE & ORDER --- */
        .price-tag-large {
            font-size: 3.5rem;
            color: var(--royal-blue);
            font-weight: 800;
        }

        .price-tag-old {
            font-size: 1.5rem;
            color: #999;
            text-decoration: line-through;
        }

        .order-btn-royal {
            background: linear-gradient(135deg, var(--royal-blue) 0%, var(--deep-blue) 100%);
            color: var(--royal-gold);
            padding: 18px 40px;
            font-size: 1.5rem;
            border-radius: 5px;
            border: 1px solid var(--royal-gold);
            text-decoration: none;
            display: inline-block;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(0, 32, 74, 0.3);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .order-btn-royal:hover {
            transform: scale(1.05);
            color: var(--white);
            box-shadow: 0 15px 30px rgba(0, 32, 74, 0.4);
        }

        /* --- FORM --- */
        .royal-form-card {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            border-top: 5px solid var(--royal-gold);
        }

        .form-control {
            border: 2px solid #eee;
            padding: 12px;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--royal-gold);
            box-shadow: none;
        }

        /* --- FOOTER --- */
        .royal-footer {
            background: var(--deep-blue);
            color: rgba(255, 255, 255, 0.7);
            padding: 40px 0;
            text-align: center;
            border-top: 3px solid var(--royal-gold);
        }

        /* --- ISLAMIC PATTERN BACKGROUND --- */
        body {
            background-color: #fdfbf7;
            background-image: radial-gradient(#d4af37 0.5px, transparent 0.5px), radial-gradient(#d4af37 0.5px, #fdfbf7 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            background-attachment: fixed;
            /* Parallax feel */
        }

        /* --- SECTION SPACING --- */
        section {
            position: relative;
            z-index: 2;
        }

        /* --- DECORATIVE HEADINGS --- */
        .islamic-title {
            position: relative;
            display: inline-block;
            padding: 10px 30px;
            color: var(--royal-blue);
            font-family: 'Cinzel', serif;
            background: url('https://img.freepik.com/free-vector/luxury-gold-border-element_53876-118544.jpg');
            /* Concept Only - using CSS border instead */
            border: 2px solid var(--royal-gold);
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2);
        }

        .islamic-title::before,
        .islamic-title::after {
            content: "♦";
            color: var(--royal-gold);
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
        }

        .islamic-title::before {
            left: -15px;
        }

        .islamic-title::after {
            right: -15px;
        }

        /* --- TESTIMONIAL EQUAL HEIGHT FIX --- */
.testimonial_slider .owl-stage {
    display: flex;
    flex-wrap: wrap;
}

.testimonial_slider .owl-item {
    display: flex;
    height: auto !important;
}

.testimonial_slider .item {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.testimonial_slider .card {
    width: 100%;
    height: 100%; /* এটি বক্সকে পুরো উচ্চতা নিতে বাধ্য করবে */
}

/* টেক্সট যদি মাঝখানে সুন্দরভাবে দেখাতে চান */
.testimonial_slider .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center; /* লেখাগুলো বক্সের মাঝখানে থাকবে */
    align-items: center;
}

        @media(max-width: 768px) {
            .main-title {
                font-size: 2rem;
            }

            .royal-hero {
                padding: 40px 0 60px 0;
            }

            .product-card-3d {
                margin-top: -40px;
            }
        }

    </style>
</head>

<body>

    <header class="royal-hero text-center">
        <div class="container">
            <div class="bismillah-text">
                ﷽ <br>বিসমিল্লাহির রহমানির রহিম
            </div>

            @if($campaign_data->banner_title)
                <div class="highlight-badge animate__animated animate__fadeInDown">
                    {{ $campaign_data->banner_title }}
                </div>
            @endif

            <h1 class="main-title animate__animated animate__zoomIn">
                {{ $campaign_data->name }}
            </h1>

            <p class="lead mb-4 text-white-50">
                শুধুমাত্র আপনার জন্য বিশেষ অফার
            </p>
        </div>
    </header>

    <section class="mb-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="product-card-3d animate__animated animate__fadeInUp">
                        <div class="card-inner text-center">
    
    {{-- ১. মেইন ইমেজ (এটি স্লাইডারের বাইরে, উপরে স্থির থাকবে) --}}
    @if($campaign_data->image_one)
        <img src="{{ asset($campaign_data->image_one) }}" class="img-fluid rounded mb-3"
            alt="Main Product Image" style="max-height: 400px;">
    @endif

    {{-- ২. স্লাইডার (এখানে শুধু ২য়, ৩য় এবং ৪র্থ ছবি স্লাইড করবে) --}}
    <div class="owl-carousel campro_img_slider mt-3">
        
        {{-- Image Two --}}
        @if($campaign_data->image_two)
            <div class="item p-1">
                <img src="{{ asset($campaign_data->image_two) }}" class="rounded shadow-sm border img-fluid" alt="Image Two">
            </div>
        @endif
        
        {{-- Image Three --}}
        @if($campaign_data->image_three)
            <div class="item p-1">
                <img src="{{ asset($campaign_data->image_three) }}" class="rounded shadow-sm border img-fluid" alt="Image Three">
            </div>
        @endif

        {{-- Image Four (যেহেতু আপনি মাইগ্রেশন করেছেন, তাই image_four ব্যবহার হবে) --}}
        @if($campaign_data->image_four)
            <div class="item p-1">
                <img src="{{ asset($campaign_data->image_four) }}" class="rounded shadow-sm border img-fluid" alt="Image Four">
            </div>
        {{-- যদি আপনি মাইগ্রেশন করার পরেও আগের image_section এ ডাটা সেভ হয়ে থাকে --}}
        @elseif(isset($campaign_data->image_section) && $campaign_data->image_section)
            <div class="item p-1">
                <img src="{{ asset($campaign_data->image_section) }}" class="rounded shadow-sm border img-fluid" alt="Image Four">
            </div>
        @endif

    </div>

</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

  <section class="py-5" style="background-color: #fdfbf7;">
        <div class="container">
            <div class="islamic-divider"></div>

            {{-- ফিক্সড স্টাইল (CSS) --}}
            <style>
                /* সাধারণ লিস্ট স্টাইল রিসেট */
                .styled-content ul {
                    list-style: none !important;
                    padding-left: 0 !important;
                    margin-top: 10px;
                }
                
                /* প্রতিটি পয়েন্টকে একটি সুন্দর কার্ডের মতো দেখানো */
                .styled-content ul li {
                    background: #ffffff;
                    margin-bottom: 12px;
                    padding: 15px;
                    border-radius: 8px;
                    border-left: 4px solid var(--royal-gold); /* বাম পাশে গোল্ড বর্ডার */
                    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
                    font-size: 1.05rem;
                    line-height: 1.6;
                    transition: transform 0.2s;
                    display: flex;
                    align-items: start;
                }

                .styled-content ul li:hover {
                    transform: translateX(5px);
                    background: #fffcf5;
                }

                .styled-content p {
                    margin-bottom: 15px;
                    text-align: justify;
                    font-size: 1.1rem;
                }

                .styled-content strong {
                    color: var(--royal-blue);
                }
            </style>

            @if($campaign_data->section_title)
                <h2 class="text-center mb-5" style="color: var(--royal-blue); font-weight: bold; font-family: 'Cinzel', serif;">
                    {{ $campaign_data->section_title }}
                </h2>
            @endif

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="feature-box h-100" style="border-top: 4px solid var(--royal-blue);">
                        <h4 class="mb-4" style="color: var(--royal-blue); padding-bottom: 10px; border-bottom: 1px dashed #ddd;">
                            <i class="fas fa-align-left me-2"></i> বিস্তারিত বিবরণ
                        </h4>
                        
                        <div class="styled-content" style="color: #444;">
                            {!! $campaign_data->description !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    @if($campaign_data->section_desc)
                        <div class="feature-box h-100" 
                             style="background: #fff; border: 2px solid var(--royal-gold); border-radius: 15px;">
                            
                            <h4 class="mb-4 text-center"
                                style="background: var(--royal-gold); color: #fff; padding: 10px; border-radius: 5px; margin-top: -10px;">
                                <i class="fas fa-star me-2"></i> বিশেষ বৈশিষ্ট্য
                            </h4>
                            
                            <div class="styled-content">
                                {!! $campaign_data->section_desc !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($campaign_data->short_description)
                <div class="mt-5 p-4 rounded text-center shadow-sm animate__animated animate__fadeInUp"
                    style="background: white; border: 2px dashed var(--royal-blue); position: relative;">
                    <i class="fas fa-quote-left text-warning fa-2x mb-3"></i>
                    <div style="font-size: 1.25rem; font-weight: 600; color: var(--royal-blue);">
                        {!! $campaign_data->short_description !!}
                    </div>
                </div>
            @endif

            <div class="text-center mt-5 animate__animated animate__pulse animate__infinite">
                <a href="#order_form" class="order-btn-royal">
                    <i class="fas fa-shopping-bag me-2"></i> অর্ডার করতে চাই
                </a>
            </div>
        </div>
    </section> 

    {{-- ================================================== --}}
    {{-- IMAGE FOUR PARALLAX SECTION (Moved Here) --}}
    {{-- ================================================== --}}
    @if($campaign_data->image_section)
    <section class="py-5" style="
        background: url('{{ asset($campaign_data->image_section) }}') no-repeat center center; 
        background-size: cover; 
        background-attachment: fixed; 
        position: relative;
        min-height: 300px;
        display: flex;
        align-items: center;">
        
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 32, 74, 0.7);"></div>
        
        <div class="container position-relative text-center text-white" data-aos="fade-up">
            <h2 class="islamic-title" style="color: #fff; border-color: #fff;">আমাদের গ্রাহকদের সন্তুষ্টি</h2>
            <p class="lead mt-3 text-white-50">আলহামদুলিল্লাহ, হাজারো গ্রাহক আমাদের ওপর আস্থা রেখেছেন</p>
        </div>
    </section>
    @endif


    @if(isset($campaign_data->images) && count($campaign_data->images) > 0)
    <section class="py-5" style="background: #f8f6f2;">
        <div class="container">
            <div class="text-center mb-5 animate__animated animate__fadeInUp">
                <h2 class="islamic-title">ফটো গ্যালারি</h2>
            </div>
            
            <div class="owl-carousel bottom_gallery_slider">
                @foreach($campaign_data->images as $image)
                    <div class="item p-3">
                        <div class="product-card-3d" style="margin-top: 0;">
                             <div class="card-inner p-2">
                                <img src="{{ asset($image->image) }}" class="rounded shadow-sm img-fluid" alt="Gallery Image">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ================================================== --}}
    {{-- DYNAMIC TESTIMONIAL SECTION --}}
    {{-- ================================================== --}}
    @php
        // Check if column exists or handle error if migration wasn't run
        $testimonials = [];
        if(isset($campaign_data->testimonials)){
             $testimonials = json_decode($campaign_data->testimonials, true) ?? [];
        }
    @endphp

    @if(count($testimonials) > 0)
    <section class="py-5" style="background: #fdfbf7;">
        <div class="container">
            <h2 class="text-center mb-5" style="color: var(--royal-blue); font-family: 'Cinzel', serif;">
                গ্রাহকদের মূল্যবান মতামত
            </h2>

            <div class="owl-carousel testimonial_slider">
                @foreach($testimonials as $item)
                    <div class="item h-100"> <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                            {{-- card-body তে d-flex এবং justify-content-center দিলে লেখাগুলো বক্সের মাঝখানে থাকবে --}}
                            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4" 
                                 style="background: #fff; border-top: 4px solid var(--royal-gold);">
                                
                                {{-- 1. IMAGE TYPE --}}
                                @if($item['type'] == 'image' && !empty($item['image']))
                                    <div class="mb-3 w-100">
                                        <img src="{{ asset($item['image']) }}" class="img-fluid rounded border" 
                                             alt="Customer Review" style="max-height: 250px; width: auto; margin: 0 auto;">
                                    </div>
                                
                                {{-- 2. VIDEO TYPE --}}
                                @elseif($item['type'] == 'video')
                                    <div class="ratio ratio-16x9 mb-3 rounded overflow-hidden border w-100">
                                        @if(Str::contains($item['content'], '<iframe'))
                                            {!! $item['content'] !!}
                                        @else
                                            <iframe src="https://www.youtube.com/embed/{{ trim($item['content']) }}" allowfullscreen></iframe>
                                        @endif
                                    </div>

                                {{-- 3. TEXT TYPE --}}
                                @else
                                    <div class="mb-3">
                                        <i class="fas fa-quote-left fa-2x text-muted mb-3"></i>
                                        <p class="card-text" style="font-size: 1.2rem; color: #555; font-style: italic; font-weight: 500;">
                                            "{{ $item['content'] }}"
                                        </p>
                                        <div class="mt-3 text-warning">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="py-5" style="background: #fdfbf7;">
        <div class="container">
            <h2 class="text-center mb-5" style="color: var(--royal-blue); font-family: 'Cinzel', serif;">
                ✨ কেন ‘হেরার আলো’ আল-কোরআন অনন্য? ✨
            </h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-box h-100 text-center">
                        <div class="feature-icon"><i class="fas fa-qrcode"></i></div>
                        <h4 style="color: var(--royal-blue);">স্মার্ট কিউআর (QR) প্রযুক্তি</h4>
                        <p>প্রতি পৃষ্ঠায় থাকা কিউআর কোড স্ক্যান করলেই শুনতে পাবেন বিখ্যাত ক্বারীদের বিশুদ্ধ তেলাওয়াত ও
                            ভিডিও গাইডলাইন।</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-box h-100 text-center">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                        <h4 style="color: var(--royal-blue);">জার্নি প্ল্যানার (ফ্রি)</h4>
                        <p>প্রতিদিনের পাঠ ও আমল ট্র্যাক করার জন্য এক্সক্লুসিভ ‘জার্নি প্ল্যানার’ টুল।</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-box h-100 text-center">
                        <div class="feature-icon"><i class="fas fa-book-open"></i></div>
                        <h4 style="color: var(--royal-blue);">সহজ ক্বারিয়ানা</h4>
                        <p>প্রতিটি আয়াতের নিচে ১০০% বিশুদ্ধ বাংলা উচ্চারণ ও সহজ অর্থ রয়েছে।</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="feature-box h-100 text-center"
                        style="background: rgba(212, 175, 55, 0.05); border: 1px dashed var(--royal-gold);">
                        <div class="feature-icon"><i class="fas fa-gift"></i></div>
                        <h4 style="color: var(--royal-blue);">প্রিমিয়াম গিলাফ (ফ্রি গিফট)</h4>
                        <p>পবিত্র কোরআনের আদব ও সুরক্ষার জন্য উন্নতমানের ও দৃষ্টিনন্দন ‘কোরআন গিলাফ’—একেবারে বিনামূল্যে।
                        </p>
                        <span class="badge bg-warning text-dark">BONUS</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="feature-box h-100 text-center"
                        style="background: rgba(212, 175, 55, 0.05); border: 1px dashed var(--royal-gold);">
                        <div class="feature-icon"><i class="fas fa-truck"></i></div>
                        <h4 style="color: var(--royal-blue);">ফ্রি হোম ডেলিভারি</h4>
                        <p>অর্ডার করলেই কোনো ডেলিভারি চার্জ ছাড়া এটি পৌঁছে যাবে।</p>
                        <span class="badge bg-success">FREE DELIVERY</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 text-center" style="background: #fdfbf7; ">
        <div class="container">
            <h3 class="text-uppercase text-secondary mb-2">অফার মূল্য</h3>
            <div class="mb-4">
                @if($product->old_price)
                    <span class="price-tag-old mx-2">{{ $product->old_price }} ৳</span>
                @endif
                <span class="price-tag-large mx-2">{{ $product->new_price }} ৳</span>
            </div>

            <a href="#order_form" class="order-btn-royal pulse-btn">
                <i class="fas fa-shopping-bag me-2"></i> অর্ডার করতে চাই
            </a>

            <p class="mt-3 text-success fw-bold">
                <i class="fas fa-truck"></i> ক্যাশ অন ডেলিভারি সুবিধা আছে
            </p>
        </div>
    </section>

    @if($campaign_data->video)
        <section class="py-5 bg-light">
            <div class="container">
                <div class="islamic-divider"></div>
                <h3 class="text-center mb-4" style="color: var(--royal-blue); font-weight: bold;">ভিডিও রিভিউ দেখুন</h3>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="evp_wrapper" class="shadow-lg rounded"
                            style="border: 3px solid var(--royal-gold); overflow: hidden; display: none;">
                            <div id="myVideo" style="width: 100%;"></div>
                        </div>

                        <div id="youtube_fallback" class="ratio ratio-16x9 shadow-lg rounded"
                            style="border: 3px solid var(--royal-gold);">
                            <iframe src="https://www.youtube.com/embed/{{ $campaign_data->video }}?rel=0"
                                title="YouTube video" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- EASY VIDEO PLAYER SCRIPTS --}}
        <link rel="stylesheet" href="{{ asset('public/frontEnd/player/content/global.css') }}">
        <script src="{{ asset('public/frontEnd/player/java/FWDEVPlayer.js') }}"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Check if Easy Video Player is loaded
                if (typeof FWDEVPlayer !== 'undefined') {
                    console.log("Easy Video Player Loaded. Initializing...");
                    document.getElementById('youtube_fallback').style.display = 'none';
                    document.getElementById('evp_wrapper').style.display = 'block';

                    new FWDEVPlayer({
                        // Main Settings
                        instanceName: "royalPlayer",
                        parentId: "myVideo",
                        mainFolderPath: "{{ asset('public/frontEnd/player/content') }}/",
                        skinPath: "minimal_skin_dark",
                        // Layout & Responsiveness
                        displayType: "responsive",
                        autoScale: "yes",
                        fillEntireVideoScreen: "no",
                        maxWidth: 1000, 
                        maxHeight: 563,

                        // Performance
                        initializeOnlyWhenVisible: "yes",

                        // Theme Styling (Royal Islamic)
                        useHEXColorsForSkin: "yes",
                        normalHEXButtonsColor: "#d4af37", // Royal Gold
                        selectedHEXButtonsColor: "#ffffff", // White
                        backgroundColor: "#00204a",
                        videoBackgroundColor: "#00204a",
                        posterBackgroundColor: "#00204a",

                        // Youtube Source
                        videoSource: [{ source: "https://www.youtube.com/watch?v={{ $campaign_data->video }}" }],

                        // Visuals
                        posterPath: "{{ asset($campaign_data->image_one) }}",
                        showControllerWhenVideoIsStopped: "yes",
                        showVolumeScrubber: "yes",
                        showTime: "yes",
                        showLoopButton: "no",
                        showContextMenu: "no",
                        showShareButton: "no",
                    });
                } else {
                    console.warn("Easy Video Player scripts not found. Using Standard YouTube Fallback.");
                }
            });
        </script>
    @endif

    <div class="container text-center my-4">
        <a href="#order_form" class="order-btn-royal">
            <i class="fas fa-shopping-bag me-2"></i> অর্ডার করতে চাই
        </a>
    </div>

    <section class="py-5" style="background: white;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="feature-box"
                        style="border: 2px solid var(--royal-gold); position: relative; overflow: hidden;">
                        <div
                            style="position: absolute; top: 0; right: 0; background: var(--royal-gold); color: white; padding: 5px 15px; font-weight: bold; border-bottom-left-radius: 10px;">
                            SPECIAL OFFER
                        </div>

                        <h3 class="text-center mb-4"
                            style="color: var(--royal-blue); border-bottom: 2px dashed #eee; padding-bottom: 15px;">
                            📦 স্পেশাল প্যাকেজে যা যা থাকছে:
                        </h3>

                        <ul class="list-unstyled" style="font-size: 1.1rem; line-height: 2;">
                            <li><i class="fas fa-check-circle text-success me-2"></i> <strong>১টি স্মার্ট
                                    আল-কোরআন:</strong> (সহজ বাংলা উচ্চারণ, অর্থ, শানে নযুল ও QR কোড সুবিধা সহ)</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> <strong>১টি রিসার্চ টুলস:</strong>
                                (কোরআন জার্নি প্ল্যানার - আপনার পাঠ ও গবেষণার সঙ্গী)</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> <strong>১টি প্রিমিয়াম
                                    গিলাফ:</strong> (আপনার কোরআনের সুরক্ষা ও আভিজাত্যের জন্য)</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> <strong>ফ্রি ডেলিভারি:</strong>
                                (সমগ্র বাংলাদেশে, আপনার ঠিকানায়)</li>
                        </ul>

                        <div class="text-center mt-4 p-3 rounded" style="background: rgba(0, 32, 74, 0.05);">
                            <p class="mb-0 text-muted text-decoration-line-through">রেগুলার প্রাইস: ৪০০০+ টাকা</p>
                            <h2 class="mb-0 animate__animated animate__pulse animate__infinite"
                                style="color: var(--royal-blue); font-weight: bold;">
                                🔥 আজকের অফার প্রাইস: {{ $product->new_price }} টাকা মাত্র!
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" id="order_form" style="background-color: #f0f2f5;">
        <div class="container">
            <div class="islamic-divider"></div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="royal-form-card">
                        <h3 class="text-center mb-4" style="color: var(--royal-blue);">অর্ডার কনফার্ম করতে নিচের ফর্মটি
                            পূরণ করুন</h3>

                        <form action="{{ route('customer.ordersave') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_type" value="landing">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">আপনার নাম *</label>
                                    <input type="text" class="form-control" name="name" required
                                        placeholder="নাম লিখুন">
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">মোবাইল নাম্বার *</label>
                                    <input type="tel" class="form-control" name="phone" required
                                        placeholder="১১ ডিজিটের নাম্বার">
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">সম্পূর্ণ ঠিকানা *</label>
                                    <textarea class="form-control" name="address" rows="2" required
                                        placeholder="বাসা নং, রোড, থানা, জেলা..."></textarea>
                                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold mb-3 d-block">ডেলিভারি এরিয়া *</label>
                                    <div class="row">
                                        @foreach($shippingcharge as $key => $charge)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check p-3 border rounded bg-light">
                                                    <input class="form-check-input shipping-area-radio" type="radio"
                                                        name="area" id="area_{{$charge->id}}" value="{{$charge->id}}" {{ $key == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="area_{{$charge->id}}">
                                                        {{ $charge->name }} ({{ $charge->amount }} ৳)
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="cartlist p-3 border rounded bg-white">
                                        @include('frontEnd.layouts.ajax.cart')
                                    </div>
                                </div>

                                <div class="col-12 mt-4 text-center">
                                    <button type="submit" class="order-btn-royal w-100">
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

    <footer class="royal-footer">
        <div class="container">
            <h3>{{ $generalsetting->name }}</h3>
            <p>আমাদের সাথে থাকার জন্য ধন্যবাদ</p>
            <p class="small mb-0">Copyright © {{ date('Y') }} All Rights Reserved</p>
        </div>
    </footer>

    <script src="{{ asset('public/frontEnd/campainFive/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/all.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/select2.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/campainFive/script.js') }}"></script>

    <script>
        $(document).ready(function () {
            // ১. টপ স্লাইডার (Top Slider)
            $('.campro_img_slider').owlCarousel({
                dots: false,
                autoplay: true,
                loop: true,
                margin: 10,
                smartSpeed: 1000,
                items: 3, 
                nav: false,
                responsive: {
                    0: { items: 2 },
                    600: { items: 3 },
                    1000: { items: 3 }
                }
            }); // <-- এই সেমিকোলন এবং ব্রাকেট টি মিসিং ছিল

            // ২. নিচের গ্যালারি স্লাইডার
            $('.bottom_gallery_slider').owlCarousel({
                dots: false,
                autoplay: true,
                loop: true,
                margin: 20,
                smartSpeed: 1000,
                nav: true,
                navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
                responsive: {
                    0: { items: 1 },
                    600: { items: 2 },
                    1000: { items: 3 }
                }
            });

            // ৩. টেস্টিমোনিয়াল স্লাইডার
            $('.testimonial_slider').owlCarousel({
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                loop: false,
                margin: 20,
                smartSpeed: 1000,
                nav: false,
                responsive: {
                    0: { items: 1 },
                    768: { items: 2 },
                    1000: { items: 3 }
                }
            });
            
            // ৪. শিপিং চার্জ লজিক
            $(document).on("change", ".shipping-area-radio", function () {
                var id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('shipping.charge') }}",
                    data: { id: id },
                    dataType: "html",
                    success: function (response) {
                        $('.cartlist').html(response);
                    }
                });
            });

            $(".shipping-area-radio:checked").trigger("change");
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