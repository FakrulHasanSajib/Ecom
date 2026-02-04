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
    <meta name="keywords" content="{{ $campaign_data->slug }}" />
    
    <meta name="twitter:card" content="product" />
    <meta name="twitter:title" content="{{$campaign_data->name}}" />
    <meta name="twitter:description" content="{{ strip_tags($campaign_data->banner_title) }}" />
    <meta name="twitter:creator" content="hellodinajpur.com" />
    <meta property="og:url" content="{{route('campaign',$campaign_data->slug)}}" />
    <meta name="twitter:image" content="{{asset($campaign_data->image_one)}}" />
    
    <meta name="twitter:card" content="product" />
    <meta name="twitter:site" content="{{$campaign_data->name}}" />
    <meta name="twitter:title" content="{{$campaign_data->name}}" />
    <meta name="twitter:description" content="{{ strip_tags($campaign_data->banner_title) }}" />
    <meta name="twitter:creator" content="Rakibul Islam" />
    <meta property="og:url" content="{{route('campaign',$campaign_data->slug)}}" />
    <meta name="twitter:image" content="{{asset($campaign_data->image_one)}}" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <style>
        :root {
            --primary-color: #c42831;
            --secondary-color: #007bff;
            --success-color: #28a745;
            --dark-color: #242424;
            --light-bg: #f8f9fa;
        }

        * {
            font-family: 'Noto Sans Bengali', Arial, sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-color);
        }

        .hero-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 5rem 0;
        }

        .main-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .highlight {
            color: var(--primary-color);
        }

        .subtitle {
            font-size: 1.3rem;
            font-weight: 500;
            color: #666;
        }

        .price-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }

        .current-price {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-color);
            text-shadow: 2px 2px 4px rgba(196, 40, 49, 0.2);
        }

        .btn-primary-custom {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: #fff;
    font-weight: 600;
    padding: 12px 30px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-primary-custom:hover {
    background-color: #000;
    border-color: #000;
    color: #fff; 
    transform: translateY(-2px);
}

        .btn-secondary-custom {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
        }

        .features-section {
            padding: 5rem 0;
            background: white;
        }

        .feature-item {
            padding: 1.5rem;
            background: var(--light-bg);
            border-radius: 10px;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }

        .feature-item:hover {
            transform: translateX(10px);
            background: #e9ecef;
        }

        .contact-section {
            background: var(--primary-color);
            color: white;
            padding: 4rem 0;
        }

        .testimonials-section {
            padding: 5rem 0;
            background: white;
        }

        .testimonial-img {
            border-radius: 15px;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .testimonial-img:hover {
            transform: scale(1.05);
        }

        .order-section {
            padding: 5rem 0;
            background: white;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }

        .product-card.selected {
            border-color: var(--primary-color);
            background: #fff5f5;
        }
        /* Modern Price Section Styles */
.price-section-modern {
    background: transparent;
    padding: 0;
}

.product-name {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 1.5rem;
}

.product-desc {
    font-size: 1.1rem;
    color: #555;
    line-height: 1.7;
}

.price-box {
    background: white;
    padding: 2.5rem 2rem;
    border-radius: 20px;
    border: 3px solid #f0f0f0;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.offer-label {
    font-size: 1.1rem;
}

.price-label {
    font-size: 1rem;
    color: #666;
    font-weight: 500;
}

.final-price {
    font-size: 3.5rem;
    font-weight: 800;
    color: var(--primary-color);
    line-height: 1;
    text-shadow: 2px 2px 4px rgba(196, 40, 49, 0.15);
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-order {
    flex: 1;
    min-width: 200px;
    background: #d97917;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 16px 30px;
    border-radius: 12px;
    border: none;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(217, 121, 23, 0.3);
}

.btn-order:hover {
    background: #c06a15;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(217, 121, 23, 0.4);
    color: white;
}

.btn-call {
    flex: 1;
    min-width: 200px;
    background: #0056b3;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 16px 30px;
    border-radius: 12px;
    border: none;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0, 86, 179, 0.3);
}

.btn-call:hover {
    background: #004494;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 86, 179, 0.4);
    color: white;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .product-name {
        font-size: 1.5rem;
    }

    .final-price {
        font-size: 2.8rem;
    }

    .cta-buttons {
        flex-direction: column;
    }

    .btn-order,
    .btn-call {
        min-width: 100%;
        padding: 14px 20px;
        font-size: 1rem;
    }

    .price-box {
        padding: 2rem 1.5rem;
    }
}

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .qty-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .qty-input {
            width: 70px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 8px;
        }

        .area-option {
            padding: 1rem;
            background: white;
            border: 2px solid #ddd;
            border-radius: 10px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .area-option:has(input:checked) {
            border-color: var(--primary-color);
            background: #fff5f5;
        }

        .area-option.selected {
            border-color: var(--primary-color);
            background: #fff5f5;
        }

        .area-option input[type="radio"] {
            display: none;
        }

        .order-summary {
            background: var(--light-bg);
            padding: 2rem;
            border-radius: 15px;
            border: 2px solid #ddd;
        }

        .total-row {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            border-top: 3px solid var(--primary-color);
            padding-top: 1rem;
        }

        .carousel-indicators [data-bs-target] {
            background-color: var(--primary-color);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: var(--primary-color);
            border-radius: 50%;
            padding: 20px;
        }

        .video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            height: 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .success-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content-custom {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            animation: fadeInUp 0.3s ease;
        }
        
        /* Add this CSS to your existing styles */

/* Rounded filled background for section title */
.section-title-rounded {
    display: inline-block;
    padding: 20px 40px;
    background: var(--primary-color);
    color: white;
    border-radius: 50px;
    box-shadow: 0 8px 25px rgba(196, 40, 49, 0.3);
    position: relative;
    transition: all 0.3s ease;
    margin-bottom: 2rem;
}

.section-title-rounded:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(196, 40, 49, 0.4);
    background: var(--dark-color);
}

/* Alternative gradient background */
.section-title-gradient {
    display: inline-block;
    padding: 20px 40px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border-radius: 50px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    position: relative;
    transition: all 0.3s ease;
    margin-bottom: 2rem;
}

.section-title-gradient:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
}

/* Light background version */
.section-title-light {
    display: inline-block;
    padding: 20px 40px;
    background: var(--light-bg);
    color: var(--primary-color);
    border-radius: 50px;
    border: 3px solid var(--primary-color);
    box-shadow: 0 8px 25px rgba(196, 40, 49, 0.15);
    position: relative;
    transition: all 0.3s ease;
    margin-bottom: 2rem;
}

.section-title-light:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(196, 40, 49, 0.3);
}


.carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        background-color: rgba(0,0,0,0.1);
        border-radius: 50%;
    }
    
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: var(--primary-color);
        border-radius: 50%;
        padding: 15px;
        width: 40px;
        height: 40px;
    }
    
    .carousel-indicators {
        bottom: -50px;
    }
    
    .carousel-indicators [data-bs-target] {
        background-color: var(--primary-color);
        border-radius: 50%;
        width: 12px;
        height: 12px;
    }
    
    .campaign-slider-img {
        transition: transform 0.3s ease;
    }
    
    .campaign-slider-img:hover {
        transform: scale(1.02);
    }
    
    /* Auto-play pause on hover */
    .carousel:hover {
        animation-play-state: paused;
    }
    
    @media (max-width: 768px) {
        .carousel-control-prev,
        .carousel-control-next {
            display: none;
        }
        
        .campaign-slider-img {
            max-width: 100% !important;
            max-height: 300px !important;
        }
    }

/* Responsive adjustments */
@media (max-width: 768px) {
    .section-title-rounded,
    .section-title-gradient,
    .section-title-light {
        padding: 15px 25px;
        font-size: 1.8rem;
    }
}

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .main-title {
                font-size: 2rem;
            }
            
            .current-price {
                font-size: 2.5rem;
            }
            
            .hero-section {
                padding: 3rem 0;
            }
        }
        /* Add to your existing <style> section */

/* Carousel 3-column layout improvements */
@media (max-width: 768px) {
    #campaignCarousel .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    #campaignCarousel .row {
        flex-direction: column;
    }
    
    .campaign-slider-img {
        max-height: 300px !important;
    }
}

@media (min-width: 769px) and (max-width: 992px) {
    #campaignCarousel .col-md-4 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

/* Carousel styling enhancements */
#campaignCarousel .carousel-item {
    padding: 20px 0;
}

#campaignCarousel .campaign-slider-img {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

#campaignCarousel .campaign-slider-img:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
    </style>
</head>
<body>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="margin: 0; border-radius: 0;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="margin: 0; border-radius: 0;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <section class="hero-section">
        <div class="container">
            <div class="text-center mb-5">
                
                <h1 class="main-title">{{ $campaign_data->banner_title }}<br>
                    <span class="highlight">{{ $campaign_data->name }}</span>
                </h1>
                <h2 class="subtitle"></h2>
            </div>
            
            <div class="row align-items-center g-4">
                <div class="col-lg-6 mb-4">
                    <div class="text-center">
                        <img src="{{ asset($campaign_data->image_one) }}" 
                             alt="{{ $campaign_data->name }}" 
                             class="img-fluid rounded-4 shadow-lg" 
                             style="max-width: 500px; width: 100%;">
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="price-section-modern">
                        <h3 class="product-name mb-3">{{ $product->name }}</h3>
                        <div class="product-desc mb-4">
                            {{ $campaign_data->section_desc }}
                        </div>
                        
                        <div class="price-box">
                            @if($product->old_price > $product->new_price)
                                <div class="offer-label mb-2">
                                    <span class="text-dark fw-semibold">অফার প্রাইসঃ</span> 
                                    <del class="text-muted ms-2" style="font-size: 1.1rem;">৳{{ number_format($product->old_price) }}</del>
                                </div>
                                <div class="price-label mb-3">মাত্র</div>
                            @endif
                            <div class="final-price mb-4">৳{{ number_format($product->new_price) }}</div>
                            
                            <div class="cta-buttons">
                                {{-- .cam_order_now class added for Facebook AddToCart pixel tracking --}}
                                <a href="#order" class="btn btn-order cam_order_now">
                                    <i class="fas fa-shopping-cart me-2"></i> অর্ডার করতে চাই
                                </a>
                                <a href="tel:{{ $contact->phone }}" class="btn btn-call">
                                    <i class="fas fa-phone me-2"></i> কল করুন
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
       @if($campaign_data->images && count($campaign_data->images) > 0)
    <section class="testimonials-section">
        <div class="container">
            <h2 class="display-5 text-center mb-5">আমাদের সম্পর্কে পাঠকদের অনুভূতি</h2>
            <div class="row">
                @foreach($campaign_data->images->take(4) as $image)
                <div class="col-md-3 mb-4">
                    <img src="{{ asset($image->image) }}" 
                         class="img-fluid testimonial-img" alt="Review {{ $loop->iteration }}">
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    
    
    @if($campaign_data->video)
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/{{ $campaign_data->video }}" 
                                title="{{ $campaign_data->name }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif 

<section class="features-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="lead text-center mb-3">
                        {!! $campaign_data->short_description !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="text-center">
    <h2 class="display-4 section-title-rounded">{{ $campaign_data->section_title }}</h2>
</div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="lead text-center mb-5">
                        {!! $campaign_data->description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-section">
        <div class="container text-center">
            <h2 class="display-5 mb-4">যেকোন প্রয়োজনে ফোন করুন</h2>
            <a href="tel:{{ $contact->phone }}" class="btn btn-light btn-lg px-5 py-3">
                <i class="fas fa-phone me-2"></i>{{ $contact->phone }}
            </a>
        </div>
    </section>

 

    
    <section class="py-5 bg-white">
    <div class="container">
        @if($campaign_data->bookimage && count($campaign_data->bookimage) > 0)
        <div id="campaignCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @php
                    $chunks = $campaign_data->bookimage->chunk(3);
                @endphp
                
                @foreach($chunks as $key => $chunk)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <div class="row justify-content-center g-4">
                        @foreach($chunk as $image)
                        <div class="col-md-4">
                            <img src="{{ asset($image->bookimage) }}" 
                                 class="img-fluid rounded shadow campaign-slider-img" 
                                 alt="{{ $campaign_data->name }}" 
                                 style="max-height: 400px; width: 100%; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            
            @if(count($campaign_data->bookimage) > 3)
            <button class="carousel-control-prev" type="button" data-bs-target="#campaignCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#campaignCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            @endif
            
            @if(count($chunks) > 1)
            <div class="carousel-indicators">
                @foreach($chunks as $key => $chunk)
                <button type="button" data-bs-target="#campaignCarousel" 
                        data-bs-slide-to="{{ $key }}" 
                        class="{{ $key == 0 ? 'active' : '' }}" 
                        aria-label="Slide {{ $key + 1 }}"></button>
                @endforeach
            </div>
            @endif
        </div>
        @else
        <div class="text-center">
            <img src="{{ asset($campaign_data->image_one) }}" 
                 alt="{{ $campaign_data->name }}" 
                 class="img-fluid rounded shadow-lg" 
                 style="max-width: 400px;">
        </div>
        @endif
    </div>
</section>


    <section class="order-section" id="order">
        <div class="container">
            <h2 class="display-5 text-center mb-5">অর্ডার করতে নিচের ফর্মটি পূরণ করুন</h2>
            
            @php
                $subtotal = Cart::instance('shopping')->subtotal();
                $subtotal = str_replace(',', '', $subtotal);
                $subtotal = str_replace('.00', '', $subtotal);
                $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
            @endphp
            
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="product-card selected">
                        <div class="d-flex align-items-center mb-3">
                            <input type="radio" id="product-{{ $product->id }}" name="product" value="{{ $product->id }}" checked class="form-check-input me-3">
                            <h5 class="mb-0">{{ Str::limit($product->name, 30) }}</h5>
                        </div>
                        
                        <div class="text-center mb-3">
                            <img src="{{ asset($product->image->image) }}" 
                                 alt="{{ $product->name }}" class="img-fluid rounded" style="max-width: 150px;">
                        </div>
                        
                        <h6 class="text-center mb-3">{{ $product->name }}</h6>
                        
                        <div class="text-center">
                            <span class="product-price h5 fw-bold text-primary">৳{{ number_format($product->new_price) }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <form action="{{ route('customer.ordersave') }}" method="POST" class="checkout-form" data-parsley-validate="">
                        @csrf
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>আপনার তথ্য দিন</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="name" class="form-label">আপনার নাম লিখুন *</label>
                                        <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   style="height: 50px;" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">আপনার পূর্ণ ঠিকানা লিখুন *</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3" required 
                                                  placeholder="বাড়ি/ফ্ল্যাট নাম্বার, রোড, এলাকা, থানা, জেলা">{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="phone" class="form-label">আপনার মোবাইল নাম্বার *</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}" 
                                               minlength="11" maxlength="11" pattern="0[0-9]+" 
                                               title="Please enter an 11-digit number starting with 0" style="height: 50px;" required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-truck me-2"></i>ডেলিভারি এরিয়া নির্বাচন করুন</h5>
                            </div>
                            <div class="card-body">
                                @foreach($shippingcharge as $key => $value)
                                <label class="area-option">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="area" 
                                               id="area_{{ $value->id }}" value="{{ $value->id }}" 
                                               {{ $loop->first ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="area_{{ $value->id }}">
                                            {{ $value->name }}: ৳{{ number_format($value->amount) }}
                                        </label>
                                    </div>
                                </label>
                                @endforeach
                                @error('area')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary-custom btn-lg w-100 mt-3">
                                <i class="fas fa-check me-2"></i>অর্ডার কনফার্ম করুন
                            </button>
                        </div>

                        <div class="order-summary">
                            <h5 class="mb-3"><i class="fas fa-clipboard-list me-2"></i>অর্ডার সামারি</h5>
                            <div class="table-responsive cartlist">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>প্রোডাক্ট</th>
                                            <th>পরিমাণ</th>
                                            <th>মূল্য</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(Cart::instance('shopping')->content() as $value)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($value->options->image) }}" height="40" width="40" class="me-2 rounded">
                                                    {{ Str::limit($value->name, 30) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="qty-cart">
                                                    <div class="quantity-controls">
                                                        <button type="button" class="qty-btn cart_decrement" data-id="{{ $value->rowId }}">-</button>
                                                        <input type="text" class="qty-input" value="{{ $value->qty }}" readonly />
                                                        <button type="button" class="qty-btn cart_increment" data-id="{{ $value->rowId }}">+</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>৳{{ number_format($value->price * $value->qty) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2"><strong>সাবটোটাল:</strong></td>
                                            <td><strong id="net_total">৳{{ number_format($subtotal) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>ডেলিভারি চার্জ:</strong></td>
                                            <td><strong id="cart_shipping_cost">৳{{ number_format($shipping) }}</strong></td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="2"><strong>মোট:</strong></td>
                                            <td><strong id="grand_total">৳{{ number_format($subtotal + $shipping) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            
                        </div>
                    </form>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('public/frontEnd/campaign/js') }}/jquery-2.1.4.min.js"></script>
    <script src="{{ asset('public/backEnd/assets/js/toastr.min.js') }}"></script>
    
    {!! Toastr::message() !!}
    
    <script>
    
    // **===================================================**
    // ** Facebook Pixel Event Tracking Code (Updated)   **
    // **===================================================**
    
    // 1. AddToCart Event (যখন "অর্ডার করুন" বাটনে ক্লিক করা হবে)
    // সমস্ত 'cam_order_now' ক্লাসযুক্ত বাটনে ক্লিক ইভেন্ট যোগ করা হচ্ছে
    $(".cam_order_now").on("click", function (e) {
        
        // Facebook AddToCart Event Track
        fbq('track', 'AddToCart', {
            content_name: '{{ $campaign_data->name }} - {{ $product->name }}',
            content_ids: ['LP_{{ $product->id }}'],
            content_type: 'product',
            // $product->new_price না থাকলে ডিফল্ট 0 ব্যবহার করা হচ্ছে
            value: {{ $product->new_price ?? 0 }}, 
            currency: 'BDT' 
        });

        // ফর্মে স্ক্রল করার ফাংশন 
        $('html, body').animate({
            scrollTop: $("#order_form").offset().top - 20
        }, 1000);
    });
    
    // 2. InitiateCheckout Event (যখন অর্ডার ফর্ম সাবমিট করা হবে)
    // Undefined variable $subtotal error fix এর জন্য isset() ব্যবহার করা হয়েছে।
    $('.checkout-form').on('submit', function(e) {
        
        // **মূল্য গণনা (Price Calculation):**
        // যদি $subtotal এবং $shipping ভেরিয়েবলগুলো সেট করা থাকে, তবে তাদের যোগফল ব্যবহার করা হবে।
        // অন্যথায় (ত্রুটি এড়াতে), শুধু $product->new_price ব্যবহার করা হবে।
        var cartValue = parseFloat("{{ (isset($subtotal) && isset($shipping)) ? ($subtotal + $shipping) : ($product->new_price ?? 0) }}");
        
        // **আইটেম সংখ্যা (Number of Items):**
        // যদি $subtotal সেট করা থাকে (অর্থাৎ কার্ট ব্যবহার হচ্ছে), তবে কার্টের আইটেম সংখ্যা ব্যবহার করা হবে।
        // অন্যথায়, ডিফল্ট ১ (কারণ এটি একটি ল্যান্ডিং পেজ) ব্যবহার করা হবে।
        var numItems = {{ isset($subtotal) ? (Cart::instance('shopping')->count() ?? 1) : 1 }}; 

        // InitiateCheckout Event
        fbq('track', 'InitiateCheckout', {
            content_name: '{{ $campaign_data->name }} - Checkout',
            content_ids: ['LP_{{ $product->id }}'],
            content_type: 'product',
            num_items: numItems, 
            value: cartValue, 
            currency: 'BDT' 
        });
        
        // ফর্ম সাবমিট হতে দিন।
    });
    
    // **===================================================**
    
        // Area selection change handler
        $('input[name="area"]').on("change", function () {
            var id = $(this).val();
            var mycart = 2;
            $.ajax({
                type: "GET",
                data: { id: id,mycart:mycart },
                url: "{{ route('shipping.charge') }}",
                dataType: "html",
                success: function(response) {
                    $('.cartlist').html(response);
                }
            });
        });

        // Cart operations
        $(document).on('click', '.cart_remove', function() {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{ route('cart.remove') }}",
                    success: function (data) {
                        if (data) {
                            $(".cartlist").html(data);
                        }
                    },
                });
            }
        });

        $(document).on('click', '.cart_increment', function() {
            var id = $(this).data("id");
            var mycart = 2;
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id,mycart:mycart },
                    url: "{{ route('cart.increment') }}",
                    success: function (data) {
                        if (data) {
                            $(".cartlist").html(data);
                        }
                    },
                });
            }
        });

        $(document).on('click', '.cart_decrement', function() {
            var id = $(this).data("id");
            var mycart = 2;
            if (id) {
                $.ajax({
                    type: "GET",
                    data: { id: id,mycart:mycart },
                    url: "{{ route('cart.decrement') }}",
                    success: function (data) {
                        if (data) {
                            $(".cartlist").html(data);
                        }
                    },
                });
            }
        });

        // Form submission handling
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                var submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>প্রক্রিয়াধীন...');
            });
        });

        // Area option selection styling
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('.area-option input[type="radio"]');
            
            radioButtons.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.area-option').forEach(function(option) {
                        option.classList.remove('selected');
                    });
                    
                    if (this.checked) {
                        this.closest('.area-option').classList.add('selected');
                    }
                });
            });
        });

        // Smooth scrolling for anchor links
        $(document).ready(function() {
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                var target = this.hash;
                var $target = $(target);
                
                if ($target.length) {
                    $('html, body').animate({
                        'scrollTop': $target.offset().top - 100
                    }, 1000, 'swing');
                }
            });
        });

        // Phone number validation
        $('#phone').on('input', function() {
            var value = $(this).val();
            // Remove any non-digit characters except for initial +
            value = value.replace(/[^\d+]/g, '');
            
            // Ensure it starts with 0 for Bangladesh numbers
            if (value.length > 0 && !value.startsWith('0') && !value.startsWith('+880')) {
                value = '0' + value;
            }
            
            $(this).val(value);
        });

        // Auto-resize textarea
        $('#address').on('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Add loading animation for images
        $(document).ready(function() {
            $('img').each(function() {
                if (!this.complete) {
                    $(this).css('opacity', '0');
                    $(this).on('load', function() {
                        $(this).animate({ opacity: 1 }, 300);
                    });
                }
            });
        });

        // Intersection Observer for animations
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            // Observe elements for animation
            $('.feature-item, .testimonial-img, .product-card').each(function() {
                $(this).css({
                    'opacity': '0',
                    'transform': 'translateY(30px)',
                    'transition': 'opacity 0.6s ease, transform 0.6s ease'
                });
                observer.observe(this);
            });
        }

        // Add scroll-to-top functionality
        $(document).ready(function() {
            // Create scroll to top button
            $('body').append(`
                <button id="scrollToTop" class="btn btn-primary-custom" style="
                    position: fixed;
                    bottom: 30px;
                    right: 30px;
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    display: none;
                    z-index: 1000;
                    transition: all 0.3s ease;
                ">
                    <i class="fas fa-arrow-up"></i>
                </button>
            `);
            
            // Show/hide scroll to top button
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('#scrollToTop').fadeIn();
                } else {
                    $('#scrollToTop').fadeOut();
                }
            });
            
            // Scroll to top functionality
            $('#scrollToTop').click(function() {
                $('html, body').animate({scrollTop: 0}, 800);
            });
        });

        // Form validation enhancement
        $('.checkout-form').on('submit', function(e) {
            let isValid = true;
            const form = this;
            
            // Clear previous validation states
            $(form).find('.is-invalid').removeClass('is-invalid');
            $(form).find('.invalid-feedback').remove();
            
            // Validate required fields
            $(form).find('input[required], textarea[required]').each(function() {
                if (!$(this).val().trim()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                    $(this).after('<div class="invalid-feedback">এই ক্ষেত্রটি পূরণ করা আবশ্যক</div>');
                }
            });
            
            // Validate phone number
            const phone = $('#phone').val();
            const phoneRegex = /^0[0-9]{10}$/;
            if (phone && !phoneRegex.test(phone)) {
                isValid = false;
                $('#phone').addClass('is-invalid');
                $('#phone').after('<div class="invalid-feedback">সঠিক মোবাইল নাম্বার দিন (১১ ডিজিট, ০ দিয়ে শুরু)</div>');
            }
            
            // Validate area selection
            if (!$('input[name="area"]:checked').length) {
                isValid = false;
                $('.card-body').last().append('<div class="invalid-feedback d-block">ডেলিভারি এরিয়া নির্বাচন করুন</div>');
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = $('.is-invalid').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 500);
                    firstError.focus();
                }
                
                // Reset submit button
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', false);
                submitBtn.html('<i class="fas fa-check me-2"></i>অর্ডার কনফার্ম করুন');
            }
        });

        // Real-time validation feedback
        $('input[required], textarea[required]').on('blur', function() {
            const $this = $(this);
            const value = $this.val().trim();
            
            $this.removeClass('is-invalid is-valid');
            $this.next('.invalid-feedback').remove();
            
            if (!value) {
                $this.addClass('is-invalid');
                $this.after('<div class="invalid-feedback">এই ক্ষেত্রটি পূরণ করা আবশ্যক</div>');
            } else if ($this.attr('type') === 'tel') {
                const phoneRegex = /^0[0-9]{10}$/;
                if (!phoneRegex.test(value)) {
                    $this.addClass('is-invalid');
                    $this.after('<div class="invalid-feedback">সঠিক মোবাইল নাম্বার দিন (১১ ডিজিট, ০ দিয়ে শুরু)</div>');
                } else {
                    $this.addClass('is-valid');
                }
            } else {
                $this.addClass('is-valid');
            }
        });

        // Prevent form double submission
        let formSubmitted = false;
        $('.checkout-form').on('submit', function(e) {
            if (formSubmitted) {
                e.preventDefault();
                return false;
            }
            formSubmitted = true;
            
            // Reset flag after 3 seconds to allow resubmission if needed
            setTimeout(function() {
                formSubmitted = false;
            }, 3000);
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