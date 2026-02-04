@extends('frontEnd.layouts.master')
@section('title', $details->name)
@push('seo')
    <meta name="app-url" content="{{ route('product', $details->slug) }}" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ $details->meta_description }}" />
    <meta name="keywords" content="{{ $details->slug }}" />

    <meta name="twitter:card" content="product" />
    <meta name="twitter:site" content="{{ $details->name }}" />
    <meta name="twitter:title" content="{{ $details->name }}" />
    <meta name="twitter:description" content="{{ $details->meta_description }}" />
    <meta name="twitter:creator" content="gomobd.com" />
    <meta property="og:url" content="{{ route('product', $details->slug) }}" />
    <meta name="twitter:image" content="{{ asset($details->image->image) }}" />

    <meta property="og:title" content="{{ $details->name }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('product', $details->slug) }}" />
    <meta property="og:image" content="{{ asset($details->image->image) }}" />
    <meta property="og:description" content="{{ $details->meta_description }}" />
    <meta property="og:site_name" content="{{ $details->name }}" />
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/zoomsl.css') }}">
    <style>
        .d-flex.single_product {
            display: flex;
            justify-content: space-between;
            /* Space out buttons */
            align-items: center;
            margin-bottom: 30px; 
            margin-top: 30px;
            /* Center buttons vertically */
        }

        /* Add to Cart Button Styling */
        .single_product .add_cart_btn {
            height: 60px;
            font-size: 20px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            background-color: rgb(213, 141, 25);
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            position: relative;
        }

        /* Order Now Button Styling */
        .single_product .order_now_btn {
            height: 60px;
            font-size: 20px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            position: relative;
            /* Required for absolute positioning of icon */
        }

        /* Hover Effect for both buttons */
        .single_product .add_cart_btn:hover,
        .single_product .order_now_btn:hover {
            background-color: #0056b3;
        }

        /* Add icon to the 'Add to Cart' button */
        .single_product .add_cart_btn::before {
            content: "\f07a";
            /* Font Awesome cart icon */
            font-family: "Font Awesome 5 Free";
            /* Ensure the font-family is correct */
            font-weight: 900;
            /* Make the icon bold */
            margin-right: 10px;
            /* Space between the icon and text */
        }

        /* Add icon to the 'Order Now' button */
        .single_product .order_now_btn::before {
            content: "\f2df";
            /* Font Awesome shopping bag icon */
            font-family: "Font Awesome 5 Free";
            /* Ensure the font-family is correct */
            font-weight: 900;
            margin-right: 10px;
            /* Space between the icon and text */
        }

        .banner-container {
            position: relative;
            width: 100%;
            /* Make the container full width */
            height: 100px;
            /* Set the height to 100px */
            overflow: hidden;
            /* Ensure the image doesn't overflow the container */
        }

        /* Style the image inside the container */
        .banner-container img {
            width: 100%;
            /* Make the image fill the container width */
            height: 100%;
            /* Ensure the image height matches the container height */
            object-fit: cover;
            /* Ensure the image covers the container without distortion */
        }

        .free-shipping {
            background-color: #e0f7e9;
            color: #2e7d32;
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
            display: inline-block;
            transition: background-color 0.3s, color 0.3s;
            cursor: default;
        }

        .free-shipping:hover {
            background-color: #2e7d32;
            color: #ffffff;
        }

/* ================= PRODUCT DETAILS AREA ================= */
/* ================= PRODUCT DETAILS AREA ================= */
.product_details_area,
.pro_details_area {
    padding-top: 40px;
    padding-bottom: 40px;
    background: #fafafa;
}

/* ================= TABS ================= */
.product-tabs {
    display: flex;
    gap: 15px;
    border-bottom: 1px solid #e5e5e5;
    margin-bottom: 25px;
}

.product-tab-item {
    padding: 12px 20px;
    cursor: pointer;
    font-weight: 600;
    color: #666;
    position: relative;
    transition: all 0.3s ease;
}

.product-tab-item:hover {
    color: #d32f2f;
}

.product-tab-item.active {
    color: #d32f2f;
}

.product-tab-item.active::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -1px;
    width: 100%;
    height: 3px;
    background: #d32f2f;
    border-radius: 3px 3px 0 0;
}

/* ================= TAB CONTENT ================= */
.tab-pane {
    display: none;
    animation: fadeIn 0.3s ease;
}

.tab-pane.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ================= ADD TO CART SECTION ================= */
.d-flex.single_product {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    background: #fff;
    padding: 20px;
    border-radius: 14px;
    border: 1px solid #eee;
    box-shadow: 0 8px 22px rgba(0,0,0,0.05);
}

/* Buttons */
.single_product .add_cart_btn,
.single_product .order_now_btn {
    height: 60px;
    font-size: 18px;
    padding: 0 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    border-radius: 8px;
    cursor: pointer;
    border: none;
    color: #fff;
}

/* Add to cart */
.single_product .add_cart_btn {
    background: rgb(213, 141, 25);
}
.single_product .add_cart_btn:hover {
    background: rgb(190, 120, 15);
}

/* Order now */
.single_product .order_now_btn {
    background: #007bff;
}
.single_product .order_now_btn:hover {
    background: #0056b3;
}

/* ================= ADD TO CART NICHER INFO BLOCK ================= */
.single_product + * {
    margin-top: 20px;
    background: #fff;
    padding: 18px 20px;
    border-radius: 12px;
    border: 1px solid #eee;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}

/* ================= FREE DELIVERY ================= */
.free-shipping {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f1fdf5;
    border: 1px solid #c8efd7;
    color: #1b7d3a;
    padding: 12px 16px;
    border-radius: 10px;
    font-weight: 600;
    margin-top: 10px;
}

.free-shipping::before {
    content: "🚚";
    font-size: 18px;
}

/* ================= DESCRIPTION CONTENT ================= */
.description-content {
    background: #ffffff !important;
    border: 1px solid #e9e9e9;
    border-radius: 12px;
    padding: 25px !important;
    font-size: 15.5px !important;
    line-height: 1.8 !important;
    color: #444 !important;
}

/* Reset editor junk */
.description-content * {
    box-sizing: border-box;
    max-width: 100%;
}

/* Headings */
.description-content h1,
.description-content h2,
.description-content h3,
.description-content h4,
.description-content strong,
.description-content b {
    display: block;
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #222 !important;
    margin: 18px 0 10px !important;
}

/* Paragraph */
.description-content p {
    margin-bottom: 10px !important;
    color: #555 !important;
}

/* Lists */
.description-content ul {
    list-style: none !important;
    padding: 0 !important;
    margin: 10px 0 25px !important;
}

.description-content ul li {
    position: relative !important;
    padding-left: 30px !important;
    margin-bottom: 8px !important;
    color: #444 !important;
}

.description-content ul li::before {
    content: "✔";
    position: absolute;
    left: 0;
    top: 2px;
    color: #2e7d32;
    font-size: 14px;
    font-weight: 700;
}

/* ================= REVIEW SECTION ================= */
.review-section-head,
.comment-card,
.review-container .d-flex {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #eee;
}

/* ================= VIDEO ================= */
#evp_wrapper {
    background: #000;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .d-flex.single_product {
        flex-direction: column;
        gap: 10px; /* দুই button-এর মধ্যে স্পেস যোগ */
    }

    .single_product .add_cart_btn,
    .single_product .order_now_btn {
        width: 100%;
        margin-bottom: 10px; /* যদি gap কাজ না করে */
    }

    .product-tabs {
        flex-wrap: wrap;
        gap: 8px;
    }

    /* স্লাইডারের অ্যারো বাটন কাস্টমাইজেশন */
    .slick-prev:before, .slick-next:before {
        color: #d32f2f; /* আপনার থিমের সাথে মিল রেখে লালচে রঙ */
        font-size: 25px;
    }
    .testimonial-slider {
        margin: 0 -10px; /* স্লাইডারের গ্যাপ ঠিক করার জন্য */
    }
    .testimonial-item {
        padding: 10px;
    }
    .testimonial_slider .card {
    display: flex;
    flex-direction: column;
}
}

/* Carousel Custom Pink Button */
.slider_order_btn {
    background-color: #e91e63 !important; /* পিংক কালার */
    color: white !important;
    border: none !important;
    width: 100% !important;
    padding: 10px 0 !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    border-radius: 5px !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important; /* আইকন এবং লেখার মাঝখানে ফাঁকা */
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2) !important;
}

.slider_order_btn:hover {
    background-color: #c2185b !important; /* হোভারে গাঢ় পিংক */
    color: white !important;
    transform: translateY(-2px);
}

/* আইকন সাইজ */
.slider_order_btn i {
    font-size: 16px;
}

/* বাটন কন্টেইনার ফিক্স */
.pro_btn {
    width: 100%;
    padding: 5px 10px 15px 10px;
}
.pro_btn form {
    width: 100%;
}



        /* reveiw end */
    </style>
@endpush

@section('content')
    <div class="homeproduct main-details-page">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <section class="product-section">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6 position-relative">
                                    @if ($details->old_price)
                                        <div class="product-details-discount-badge">
                                            <div class="sale-badge">
                                                <div class="sale-badge-inner">
                                                    <div class="sale-badge-box">
                                                        <span class="sale-badge-text">
                                                            <p> @php $discount=(((($details->old_price)-($details->new_price))*100) / ($details->old_price)) @endphp {{ number_format($discount, 0) }}%</p>
                                                            ছাড়
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="details_slider owl-carousel">
                                        @foreach ($details->images as $value)
                                            <div class="dimage_item">
                                                <img src="{{ asset($value->image) }}" class="block__pic" />
                                            </div>
                                        @endforeach
                                    </div>
                                    <div
                                        class="indicator_thumb @if ($details->images->count() > 4) thumb_slider owl-carousel @endif">
                                        @foreach ($details->images as $key => $image)
                                            <div class="indicator-item" data-id="{{ $key }}">
                                                <img src="{{ asset($image->image) }}" />
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="details_right">

                                        <div class="product">
                                            <div class="product-cart">
                                                <p class="name">{{ $details->name }}</p>
                                                <p class="details-price">
                                                    @if ($details->old_price)
                                                        <del>৳{{ $details->old_price }}</del>
                                                    @endif ৳{{ $details->new_price }}
                                                </p>

                                                <form action="{{ route('cart.store') }}"data-fb-no-track method="POST"
                                                    name="formName">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $details->id }}" />
                                                    @if ($productcolors->count() > 0)
                                                        <div class="pro-color" style="width: 100%;">
                                                            <div class="color_inner">
                                                                <p>Color -</p>
                                                                <div class="size-container">
                                                                    <div class="selector">
                                                                        @foreach ($productcolors as $procolor)
                                                                            <div class="selector-item">
                                                                                <input type="radio"
                                                                                    id="fc-option{{ $procolor->id }}"
                                                                                    value="{{ $procolor->color ? $procolor->color->colorName : '' }}"
                                                                                    name="product_color"
                                                                                    class="selector-item_radio emptyalert"
                                                                                    required />
                                                                                <label
                                                                                    for="fc-option{{ $procolor->id }}"
                                                                                    style="background-color: {{ $procolor->color ? $procolor->color->color : '' }}"
                                                                                    class="selector-item_label">
                                                                                    <span>
                                                                                        <img src="{{ asset('public/frontEnd/images') }}/check-icon.svg"
                                                                                            alt="Checked Icon" />
                                                                                    </span>
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif @if ($productsizes->count() > 0)
                                                            <div class="pro-size" style="width: 100%;">
                                                                <div class="size_inner">
                                                                    <p>Size - <span class="attibute-name"></span></p>
                                                                    <div class="size-container">
                                                                        <div class="selector">
                                                                            @foreach ($productsizes as $prosize)
                                                                                <div class="selector-item">
                                                                                    <input type="radio"
                                                                                        id="f-option{{ $prosize->id }}"
                                                                                        value="{{ $prosize->size ? $prosize->size->sizeName : '' }}"
                                                                                        name="product_size"
                                                                                        class="selector-item_radio emptyalert"
                                                                                        required />
                                                                                    <label
                                                                                        for="f-option{{ $prosize->id }}"
                                                                                        class="selector-item_label">{{ $prosize->size ? $prosize->size->sizeName : '' }}</label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if ($details->pro_unit)
                                                            <div class="pro_unig">
                                                                <label>Unit: {{ $details->pro_unit }}</label>
                                                                <input type="hidden" name="pro_unit"
                                                                    value="{{ $details->pro_unit }}" />
                                                            </div>
                                                        @endif


                                                        <div class="row">
                                                            <input type="hidden" name="qty" value="1" />

                                                            <div class="d-flex single_product col-sm-12">
                                                                {{-- 'onclick' এবং 'cart_store' ক্লাস মুছে দেওয়া হলো --}}
                                                                <input type="button"
                                                                    class="btn px-4 add_cart_btn product_add_to_cart_btn"
                                                                    name="add_cart" value="কার্টে যোগ করুন "
                                                                    data-id="{{ $details->id }}" />

                                                                {{-- 'onclick' এবং 'cart_store' ক্লাস মুছে দেওয়া হলো --}}
                                                                <input type="button"
                                                                    class="btn px-4 order_now_btn product_add_to_cart_btn"
                                                                    name="order_now" value="অর্ডার করুন"
                                                                    data-fb-no-track data-id="{{ $details->id }}" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            {!! $details->short_dec !!}
                                                            <div class="pro_brand">
                                                                <p>Category :
                                                                    <a
                                                                        href="{{ url('category/' . $details->category->slug) }}">{{ $details->category->name }}</a>
                                                                </p>
                                                                <p>Brand :
                                                                    {{ $details->brand ? $details->brand->name : 'N/A' }}
                                                                </p>
                                                                <p>SKU:
                                                                    {{ $details->product_code }}
                                                                </p>
                                                                <p>Stock:
                                                                    {{ $details->stock }}

                                                                </p>

                                                                @if ($details->free_shipping)
                                                                    <div class="free-shipping">
                                                                        🚚 Free Delivary
                                                                    </div>
                                                                @endif
                                                            </div>




                                                        </div>

                                                        <div class="mt-md-2 mt-2">
                                                            <div class="banner-container">
                                                                @php
                                                                    $flashPro = \App\Models\Flashdealpro::with('flashdeal')
                                                                        ->where('product_id', $details->id)
                                                                        ->first();

                                                                    $banner_category = \App\Models\BannerCategory::where('name', 'Free Shipping')->first();

                                                                    if ($banner_category != null) {
                                                                        $banaer_shipping = \App\Models\Banner::where('category_id', $banner_category->id)->first();
                                                                    } else {
                                                                        $banaer_shipping = '';
                                                                    }
                                                                @endphp

                                                                @if ($flashPro && $flashPro->flashdeal)
                                                                    <a
                                                                        href="{{ route('flashdeal', $flashPro->flashdeal->slug) }}">
                                                                        <img src="{{ asset($flashPro->flashdeal->banner) }}"
                                                                            alt=" ">
                                                                    </a>
                                                                @elseif ($details->free_shipping && $banaer_shipping)
                                                                    <a href="{{ route('freeshipping') }}">
                                                                        <img src="{{ asset($banaer_shipping->image) }}"
                                                                            alt=" ">
                                                                    </a>
                                                                @endif
                                                            </div>

                                                        </div>
                                                        <div class="mt-md-2 mt-2">

                                                            <div class="elementor-element elementor-element-0e9393c elementor-widget elementor-widget-html"
                                                                data-id="0e9393c" data-element_type="widget"
                                                                data-widget_type="html.default">
                                                                <div class="elementor-widget-container">
                                                                    <style>
                                                                        /* WhatsApp */
                                                                        .safe-floating_btn a {
                                                                            text-decoration: none;
                                                                            display: flex;
                                                                            align-items: center;
                                                                        }

                                                                        .safe-floating_btn a img {
                                                                            max-width: 45px;
                                                                        }

                                                                        @keyframes pulsing {
                                                                            to {
                                                                                box-shadow: 0 0 0 30px rgba(232, 76, 61, 0);
                                                                            }
                                                                        }

                                                                        .safe-floating_btn .contact_icon {
                                                                            background-color: #f0fff7;
                                                                            color: #fff;
                                                                            width: 60px;
                                                                            height: 60px;
                                                                            font-size: 30px;
                                                                            border-radius: 60px;
                                                                            text-align: center;
                                                                            box-shadow: 2px 2px 3px #999;
                                                                            display: flex;
                                                                            align-items: center;
                                                                            justify-content: center;
                                                                            transform: translatey(0px);
                                                                            animation: pulse 1.5s infinite;
                                                                            box-shadow: 0 0 0 0 #42db87;
                                                                            -webkit-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
                                                                            -moz-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
                                                                            -ms-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
                                                                            animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
                                                                            font-weight: normal;
                                                                            font-family: sans-serif;
                                                                            text-decoration: none !important;
                                                                            transition: all 300ms ease-in-out;
                                                                        }

                                                                        .safe-floating_btn .text_icon {
                                                                            color: #222;
                                                                            font-size: 22px;
                                                                            margin: 0;
                                                                            margin-left: 6px;
                                                                            font-weight: 700;
                                                                            text-shadow: 0px 2px #b1b2bb;
                                                                            line-height: 1.5;
                                                                        }

                                                                        .safe-floating_btn .text_icon small {
                                                                            color: #e52c23;
                                                                            text-shadow: 0px 0px #ffffff;
                                                                            border-radius: 3px;
                                                                            color: #fff;
                                                                            box-shadow: inset 0 -2px 0 rgba(0, 0, 0, .15);
                                                                            background-color: rgb(85 205 108);
                                                                            padding: 10px 25px;
                                                                            display: inline-block;
                                                                            font-size: 15px;
                                                                            transition: .3s;
                                                                        }

                                                                        .safe-floating_btn .text_icon small:hover {
                                                                            background-color: #FF0000;
                                                                        }
                                                                    </style>
                                                                    <div class="safe-floating_btn">
                                                                        @php
                                                                            $ismobile = is_numeric(
                                                                                strpos(
                                                                                    strtolower(
                                                                                        $_SERVER['HTTP_USER_AGENT'],
                                                                                    ),
                                                                                    'mobile',
                                                                                ),
                                                                            );
                                                                            $contactinfo = \App\Models\Contact::where(
                                                                                'id',
                                                                                1,
                                                                            )->first();
                                                                        @endphp
                                                                        @if ($ismobile)
                                                                            <a href="https://wa.me/{{ $contactinfo->whatsapp }}?text={{ url()->full() }} Hello! I am interested in this product"
                                                                                target="_blank" data-wpel-link="external"
                                                                                rel="nofollow external noopener noreferrer sponsored">
                                                                                <div class="contact_icon"><img
                                                                                        src="/banner/whatsapp.png"
                                                                                        alt="Icon"></div>
                                                                                <p class="text_icon">
                                                                                    {{ $contactinfo->whatsapp }} <br>
                                                                                    <small>Order Via Whatsapp</small>
                                                                                </p>
                                                                            </a>
                                                                        @else
                                                                            <a href="https://web.whatsapp.com/send?phone={{ $contactinfo->whatsapp }}&amp;text={{ url()->full() }} Hello! I am interested in this product"
                                                                                target="_blank" data-wpel-link="external"
                                                                                rel="nofollow external noopener noreferrer sponsored">
                                                                                <div class="contact_icon"><img
                                                                                        src="/banner/whatsapp.png"
                                                                                        alt="Icon"></div>
                                                                                <p class="text_icon">
                                                                                    {{ $contactinfo->whatsapp }} <br>
                                                                                    <small>Order Via Whatsapp</small>
                                                                                </p>
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                </form>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <section class="pro_details_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-tabs">
                        <div class="product-tab-item active" onclick="openTab(event, 'Description')">Description</div>
                        @if ($details->pro_video)
                            <div class="product-tab-item" onclick="openTab(event, 'Video')">Video</div>
                        @endif
                        <div class="product-tab-item" onclick="openTab(event, 'ReturnPolicy')">Return & Policy</div>
                    </div>

                    <div id="Description" class="tab-pane active">
                        <div class="description-content">
                            {!! $details->description !!}
                        </div>

                        <div class="mt-5 pt-4 border-top">
                            <h3 style="font-size: 20px; font-weight: 700; color: #333; margin-bottom: 20px;">রিভিউ
                                ({{ $reviews->count() }})</h3>
                            <div class="review-section-head mb-4">
                                <div class="review-section-info">
                                    <div class="review-logo">
                                        {{ mb_substr(Auth::guard('customer')->user()->name ?? 'G', 0, 1) }}
                                    </div>
                                    <div class="review-stars">
                                        @php $avgRating = round($reviews->avg('ratting')); @endphp
                                        @for ($i = 0; $i < $avgRating; $i++)
                                            <span style="color: gold; font-size: 20px;">★</span>
                                        @endfor
                                        @for ($i = $avgRating; $i < 5; $i++)
                                            <span style="color: #ccc; font-size: 20px;">★</span>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <div class="review-container">
                                <div class="comment-card"
                                    style="margin-left: 0; box-shadow: none; border: 1px solid #eee; padding: 20px; border-radius: 8px;">
                                    <form action="{{ route('customer.review') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $details->id }}">
                                        <div class="mb-3">
                                            <label class="mb-2 fw-bold">আপনার রেটিং দিন:</label>
                                            <div class="rating">
                                                <label title="Excellent">☆<input required type="radio" name="ratting"
                                                        value="5" /></label>
                                                <label title="Best">☆<input required type="radio" name="ratting"
                                                        value="4" /></label>
                                                <label title="Better">☆<input required type="radio" name="ratting"
                                                        value="3" /></label>
                                                <label title="Good">☆<input required type="radio" name="ratting"
                                                        value="2" /></label>
                                                <label title="Bad">☆<input required type="radio" name="ratting"
                                                        value="1" /></label>
                                            </div>
                                        </div>
                                        <textarea required class="form-control" name="review" rows="3" placeholder="আপনার মতামত লিখুন..."
                                            style="border: 1px solid #ddd;"></textarea>
                                        <div class="mt-3 text-end">
                                            @if (Auth::guard('customer')->user())
                                                <button type="submit" class="btn btn-danger text-white px-4">Submit
                                                    Review</button>
                                            @else
                                                <a href="{{ route('customer.login') }}"
                                                    class="btn btn-danger text-white px-4">Login to Review</a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if ($reviews->count() > 0)
                                <div class="mt-4">
                                    @foreach ($reviews as $review)
                                        <div class="d-flex mb-3 border-bottom pb-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    style="width: 40px; height: 40px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                    {{ mb_substr($review->customer->name ?? 'C', 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1 fw-bold">{{ $review->customer->name ?? 'Customer' }}</h6>
                                                <div class="mb-1">
                                                    @for ($i = 0; $i < $review->ratting; $i++)
                                                        <span style="color: gold; font-size: 14px;">★</span>
                                                    @endfor
                                                </div>
                                                <p class="mb-0 text-muted small">{{ $review->review }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($details->pro_video)
    <div id="Video" class="tab-pane">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- এই evp_wrapper এবং myVideo আইডি থাকতে হবে --}}
                <div id="evp_wrapper" style="width: 100%; max-width: 900px; margin: 0 auto;">
                    <div id="myVideo" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
@endif

                    <div id="ReturnPolicy" class="tab-pane">
                        @php
                            $returnPolicy = \App\Models\CreatePage::where('slug', 'return-policy')->first();
                        @endphp
                        <div class="description-content">
                            @if ($returnPolicy)
                                <h3 class="mb-3">{{ $returnPolicy->title }}</h3>
                                {!! $returnPolicy->description !!}
                            @else
                                <p>No return policy found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
 @php
    $testimonials = json_decode($details->testimonials, true) ?? [];
@endphp

@if(count($testimonials) > 0)
<div class="product-testimonials mt-5 container">
    <h3 class="mb-4 text-center" style="font-weight: 700;">Customer Reviews</h3>
    
    <div class="testimonial_slider owl-carousel">
        @foreach($testimonials as $item)
            <div class="testimonial-item px-2">
                <div class="card h-100 shadow-sm border-0 bg-white p-3" style="border-radius: 15px; min-height: 300px;">
                    <div class="card-body p-0 d-flex align-items-center justify-content-center">
                        @if($item['type'] == 'text')
                            <div class="p-3 text-center">
                                <i class="fas fa-quote-left text-muted mb-3 fa-2x"></i>
                                <p class="card-text fs-5 italic">"{{ $item['content'] }}"</p>
                            </div>
                        @elseif($item['type'] == 'video')
                            <div class="ratio ratio-16x9 rounded overflow-hidden w-100">
                                <iframe src="https://www.youtube.com/embed/{{ $item['content'] }}" allowfullscreen></iframe>
                            </div>
                        @elseif($item['type'] == 'image')
                            <div class="review-image-container text-center w-100">
                                <img src="{{ asset($item['image']) }}" 
                                     class="img-fluid rounded shadow-sm" 
                                     alt="Review Image" 
                                     style="max-height: 350px; width: auto; object-fit: contain; display: inline-block;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
    </section>

    <section class="related-product-section">
        <div class="container">
            <div class="row">
                <div class="related-title">
                    <h5>Related Product</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="product-inner owl-carousel related_slider">
                        @foreach ($products as $key => $value)
                            <div class="product_item wist_item wow fadeInDown" data-wow-duration="1.5s"
                                data-wow-delay="0.{{ $key }}s">
                                <div class="product_item_inner">
                                    @if ($value->old_price)
                                        <div class="sale-badge">
                                            <div class="sale-badge-inner">
                                                <div class="sale-badge-box">
                                                    <span class="sale-badge-text">
                                                        <p>@php
                                                            $discount = (($value->old_price - $value->new_price) * 100) / $value->old_price;
                                                        @endphp
                                                            {{ number_format($discount, 0) }}%</p>
                                                        ছাড়
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="pro_img">
                                        <a href="{{ route('product', $value->slug) }}">
                                            <img src="{{ asset($value->image ? $value->image->image : '') }}"
                                                alt="{{ $value->name }}" />
                                        </a>
                                    </div>
                                    <div class="pro_des">
                                        <div class="pro_name">
                                            <a
                                                href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
                                        </div>
                                        <div class="pro_price">
                                            <p>
                                                <del>৳ {{ $value->old_price }}</del>
                                                ৳ {{ $value->new_price }} @if ($value->old_price)
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                               @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
    {{-- ভেরিয়েন্ট সহ প্রোডাক্টের জন্য --}}
    <div class="pro_btn">
        <div class="cart_btn order_button" style="width: 100%;">
            <a href="{{ route('product', $value->slug) }}" class="slider_order_btn">
                <i class="fa-solid fa-cart-shopping"></i> অর্ডার করুন
            </a>
        </div>
    </div>
@else
    {{-- সাধারণ প্রোডাক্টের জন্য --}}
    <div class="pro_btn">
        <form action="{{ route('cart.store') }}" method="POST" style="width: 100%;">
            @csrf
            <input type="hidden" name="id" value="{{ $value->id }}" />
            <input type="hidden" name="qty" value="1" />
            
            <button type="submit" class="slider_order_btn">
                <i class="fa-solid fa-cart-shopping"></i> অর্ডার করুন
            </button>
        </form>
    </div>
@endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="margin-top: 200px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Your review</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="insert-review">
                        @if (Auth::guard('customer')->user())
                            <form action="{{ route('customer.review') }}" id="review-form" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $details->id }}">
                                <div class="fz-12 mb-2">
                                    <div class="rating">
                                        <label title="Excelent">
                                            ☆
                                            <input required type="radio" name="ratting" value="5" />
                                        </label>
                                        <label title="Best">
                                            ☆
                                            <input required type="radio" name="ratting" value="4" />
                                        </label>
                                        <label title="Better">
                                            ☆
                                            <input required type="radio" name="ratting" value="3" />
                                        </label>
                                        <label title="Very Good">
                                            ☆
                                            <input required type="radio" name="ratting" value="2" />
                                        </label>
                                        <label title="Good">
                                            ☆
                                            <input required type="radio" name="ratting" value="1" />
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Message:</label>
                                    <textarea required class="form-control radius-lg" name="review" id="message-text"></textarea>
                                    <span id="validation-message" style="color: red;"></span>
                                </div>
                                <div class="form-group">
                                    <button class="details-review-button" type="submit">Submit
                                        Review</button>
                                </div>

                            </form>
                        @else
                            <a class="customer-login-redirect" href="{{ route('customer.login') }}">Login
                                to Post
                                Your Review</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')



<script>
    // পেজ লোড হওয়ার পর কোড রান হবে (যাতে trackServerSide ফাংশনটি পাওয়া যায়)
    window.addEventListener('load', function() {
        
        console.log("ViewContent Event Triggering..."); // কনসোলে চেক করার জন্য

        if (typeof trackServerSide === 'function') {
            trackServerSide('ViewContent', {
                content_name: "{{ $details->name }}",
                content_ids: ["{{ $details->id }}"],
                content_type: 'product',
                value: {{ $details->new_price }},
                currency: 'BDT',
                // TikTok এবং Server Side এর জন্য এই অংশটি জরুরি
                contents: [{
                    content_id: "{{ $details->id }}",
                    content_type: 'product',
                    quantity: 1,
                    price: {{ $details->new_price }}
                }]
            });
        } else {
            console.error("Error: trackServerSide function not found! Check master.blade.php");
        }
    });
</script>
    {{-- 
    ================================================================
    ✅ TIKTOK & PIXEL DATA PREPARATION (PHP)
    ================================================================
--}}
    @php
        // TikTok & Facebook Pixel Data Preparation
        $product_id = $details->id ?? 0;
        $product_name = $details->name ?? 'Unknown Product';
        $price_value = $details->new_price ?? $details->selling_price ?? $details->price ?? 0;
        $category_name = $details->category->name ?? 'N/A';
    @endphp

    @if (isset($details))
        <script type="text/javascript">
            // *** View Content Tracking (GA4 & Facebook Pixel) ***
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                ecommerce: null
            });
            dataLayer.push({
                event: "view_item",
                ecommerce: {
                    items: [{
                        item_name: "{{ $details->name }}",
                        item_id: "{{ $details->id }}",
                        price: "{{ $details->new_price }}",
                        item_brand: "{{ $details->brand ? $details->brand->name : '' }}",
                        item_category: "{{ $details->category->name }}",
                        item_variant: "{{ $details->pro_unit }}",
                        currency: "BDT",
                        quantity: {{ $details->stock ?? 0 }}
                    }],
                    // Impression data for related products (assumes $products variable is available)
                    impression: [
                        @foreach ($products as $value)
                            {
                                item_name: "{{ $value->name }}",
                                item_id: "{{ $value->id }}",
                                price: "{{ $value->new_price }}",
                                item_brand: "{{ $details->brand ? $details->brand->name : '' }}",
                                item_category: "{{ $value->category ? $value->category->name : '' }}",
                                item_variant: "{{ $value->pro_unit }}",
                                currency: "BDT",
                                quantity: {{ $value->stock ?? 0 }}
                            },
                        @endforeach
                    ]
                }
            });

            // Add Facebook Pixel ViewContent Event
           // fbq('track', 'ViewContent', {
               // content_name: "{{ $details->name }}",
               // content_ids: ["{{ $details->id }}"], // Array format is preferred
               // content_type: 'product',
               // value: "{{ $details->new_price }}",
                //currency: 'BDT'
           // });
    
        </script>
    @endif

    {{-- 
    ================================================================
    ✅ TIKTOK ViewContent Event (Added back)
    ================================================================
--}}
    <script>
        if (typeof ttq !== 'undefined') {
            ttq.track('ViewContent', {
                content_id: '{{ $product_id }}',
                content_name: '{{ $product_name }}',
                content_type: 'product',
                content_category: '{{ $category_name }}',
                value: '{{ $price_value }}',
                currency: 'BDT'
            });
            console.log('TikTok ViewContent Fired');
        }
    </script>

    {{-- 
    ================================================================
    ✅ AJAX Cart Action and AddToCart Pixel Event Handler (Updated)
    ================================================================
--}}
<script type="text/javascript">
    function handleCartAction(event) {
        event.preventDefault();

        // ১. ভ্যালিডেশন এবং ডাটা সংগ্রহ
        var form = document.forms["formName"];
        var sizeField = form["product_size"];
        var colorField = form["product_color"];
        
        var size = sizeField ? sizeField.value : '';
        var color = colorField ? colorField.value : '';
        var qty = 1;
        var id = form["id"].value;
        var pro_unit = form["pro_unit"] ? form["pro_unit"].value : '';
        var price = parseFloat("{{ $details->new_price }}");
        var productName = "{{ $details->name }}";
        const total_value = price * qty;

        if (sizeField && size == "") {
            toastr.warning("Please select any size");
            return false;
        }
        if (colorField && color == "") {
            toastr.error("Please select any color");
            return false;
        }

        const button = $(event.target);
        const isOrderNow = button.hasClass('order_now_btn');

        // বাটন ডিজেবল রাখা যাতে ডাবল ক্লিক না হয়
        button.prop('disabled', true);

        // ২. কার্টে পণ্য যোগ করা (AJAX) - এটি এখন সবার আগে হবে
        $.ajax({
            type: "POST",
            url: '{{ route('cart.single') }}',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                qty: qty,
                product_size: size,
                product_color: color,
                pro_unit: pro_unit
            },
            success: function(data) {
                button.prop('disabled', false); // কাজ শেষ, বাটন আবার সচল

                if (data && data.success) {
                    toastr.success('Success', 'Product added to cart successfully');

                    // আপডেট কার্ট কাউন্ট
                    if (typeof cart_count === 'function') cart_count();
                    if (typeof mobile_cart === 'function') mobile_cart();

                    // ৩. 🔥 ট্র্যাকিং ফায়ার (শুধুমাত্র কার্ট সাকসেস হলে)
                    if (typeof trackServerSide === 'function') {
                        trackServerSide('AddToCart', {
                            content_name: productName,
                            content_ids: [id],
                            content_type: 'product',
                            value: total_value,
                            currency: 'BDT',
                            contents: [{ content_id: id, content_type: 'product', quantity: qty, price: price }]
                        });
                        console.log('AddToCart Sent After Cart Success');
                    }

                    // ৪. GA4 ট্র্যাকিং
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({ ecommerce: null });
                    window.dataLayer.push({
                        event: "add_to_cart",
                        ecommerce: {
                            currency: 'BDT',
                            value: total_value,
                            items: [{
                                'item_id': id,
                                'item_name': productName,
                                'price': price,
                                'quantity': qty,
                            }]
                        }
                    });

                    // ৫. অর্ডার নাউ বাটন হলে রিডাইরেক্ট
                    if (isOrderNow) {
                        setTimeout(function() {
                            window.location.href = "{{ route('customer.checkout') }}";
                        }, 800); 
                    }
                } else {
                    toastr.error('Error', 'Failed to add product.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                button.prop('disabled', false);
                toastr.error('Error', 'An error occurred during cart addition.');
            }
        });

        return false;
    }

    // বাটন ক্লিক হ্যান্ডলার
    $(document).ready(function() {
        $(".product_add_to_cart_btn, #add_to_cart, #order_now").off('click').on('click', handleCartAction);
    });
</script>


    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/zoomsl.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/player/java/FWDEVPlayer.js') }}"></script>



    <script>
        // Tab switching logic
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-pane");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("product-tab-item");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.className += " active";

            // Lazy Load Video
            if (tabName === 'Video') {
                initVideoPlayer();
            }
        }

        // Lazy Video Player Initialization
        var playerInitialized = false;

        function initVideoPlayer() {
            if (playerInitialized) return;

            if (typeof FWDEVPlayer !== 'undefined') {
                console.log("Lazy initializing Video Player...");
                new FWDEVPlayer({
                    instanceName: "player1",
                    parentId: "myVideo",
                    mainFolderPath: "{{ asset('public/frontEnd/player/content') }}/",
                    skinPath: "minimal_skin_dark",
                    displayType: "responsive",
                    autoScale: "yes",
                    maxWidth: 900,
                    maxHeight: 506,
                    videoSource: [{
                        source: "https://www.youtube.com/watch?v={{ $details->pro_video }}"
                    }],
                    // Colors matching the theme
                    useHEXColorsForSkin: "yes",
                    normalHEXButtonsColor: "#555555",
                    selectedHEXButtonsColor: "#00204a",
                });

                var wrapper = document.getElementById('evp_wrapper');
                if (wrapper) wrapper.style.display = 'block';

                playerInitialized = true;
            } else {
                console.log("FWDEVPlayer not loaded.");
            }
        }

        // Owl Carousel
        $(document).ready(function() {
            $(".details_slider").owlCarousel({
                margin: 15,
                items: 1,
                loop: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
            });
            $(".indicator-item").on("click", function() {
                var slideIndex = $(this).data("id");
                $(".details_slider").trigger("to.owl.carousel", slideIndex);
            });
            $(".thumb_slider").owlCarousel({
                margin: 15,
                items: 4,
                loop: true,
                dots: false,
                nav: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
            });
            $(".related_slider").owlCarousel({
                margin: 10,
                items: 6,
                loop: true,
                dots: true,
                nav: true,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 2
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 6
                    }
                },
            });
            // Zoom
            $(".block__pic").imagezoomsl({
                zoomrange: [3, 3]
            });
        });

      $('.testimonial_slider').owlCarousel({
    margin: 20,
    items: 3,           // এখানে ২ এর বদলে ৩ করে দিন
    loop: true,
    dots: true,
    nav: false,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1    // মোবাইলে ১টিই থাকবে
        },
        768: {
            items: 2    // ট্যাবে ২টি
        },
        1200: {
            items: 3    // বড় স্ক্রিনে বা পিসিতে ৩টি
        }
    }
});

        // Add to Cart / Order Now Logic
    </script>
    <script>
        // Details Slider
        $(document).ready(function() {
            $(".details_slider").owlCarousel({
                margin: 15,
                items: 1,
                loop: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
            });
            $(".indicator-item").on("click", function() {
                var slideIndex = $(this).data("id");
                $(".details_slider").trigger("to.owl.carousel", slideIndex);
            });
        });

        // Quantity Counter (Minus/Plus buttons)
        $(document).ready(function() {
            $(".minus").click(function() {
                var $input = $(this).parent().find("input");
                var count = parseInt($input.val()) - 1;
                count = count < 1 ? 1 : count;
                $input.val(count);
                $input.change();
                return false;
            });
            $(".plus").click(function() {
                var $input = $(this).parent().find("input");
                $input.val(parseInt($input.val()) + 1);
                $input.change();
                return false;
            });
        });

        // Review Rating Click Handler
        $(document).ready(function() {
            $(".rating label").click(function() {
                $(".rating label").removeClass("active");
                $(this).addClass("active");
            });
        });

        // Thumbnail Slider
        $(document).ready(function() {
            $(".thumb_slider").owlCarousel({
                margin: 15,
                items: 4,
                loop: true,
                dots: false,
                nav: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
            });
        });
    </script>

    <script type="text/javascript">
        // Image Zoom functionality
        $(".block__pic").imagezoomsl({
            zoomrange: [3, 3]
        });
    </script>
    <script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'event': 'view_item',
        'ecommerce': {
            'currency': 'BDT',
            'value': '{{ $details->new_price }}',
            'items': [{
                'item_id': '{{ $details->id }}',
                'item_name': '{{ $details->name }}',
                'price': '{{ $details->new_price }}',
                'item_brand': '{{ $details->brand->name ?? "" }}',
                'item_category': '{{ $details->category->name ?? "" }}'
            }]
        }
    });
</script>
<script>
    $(document).ready(function() {
        
        // ১. ইউনিক ইভেন্ট আইডি জেনারেটর
        function generateEventId() {
            return 'evt_' + Date.now() + '_' + Math.floor(Math.random() * 1000000);
        }

        // ২. স্মার্ট ডাটা ফাইন্ডার (ইনপুট বা স্টোরেজ থেকে ডাটা খুঁজবে)
        function getUserData() {
            // ইনপুট ফিল্ড থেকে ডাটা খোঁজা (যদি পেজে থাকে)
            var phone = $('input[name="phone"]').val() || localStorage.getItem('sst_phone');
            var name  = $('input[name="name"]').val()  || localStorage.getItem('sst_name');
            var email = $('input[name="email"]').val() || localStorage.getItem('sst_email');

            return { phone: phone, name: name, email: email };
        }

        // ৩. কুকি রিডার
        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return match[2];
        }

        // WhatsApp বাটন ক্লিক হ্যান্ডলার
        $(document).on('click', 'a[href*="wa.me"], .safe-floating_btn a', function() {
            
            var eventID = generateEventId(); // একটি ইউনিক আইডি তৈরি হলো
            var userData = getUserData();    // ইউজারের নাম/ফোন সংগ্রহ হলো

            console.log('WhatsApp Clicked! Event ID:', eventID);

            // A. ব্রাউজার সাইড ট্র্যাকিং (Pixel & TikTok) - সেইম আইডি সহ
            if(typeof fbq !== 'undefined') {
                fbq('track', 'Contact', {}, { eventID: eventID });
            }
            if(typeof ttq !== 'undefined') {
                ttq.track('Contact', {}, { event_id: eventID });
            }

            // B. সার্ভার সাইড ট্র্যাকিং (AJAX) - সেইম আইডি ও ইউজার ডাটা সহ
            var data = {
                _token: '{{ csrf_token() }}',
                event_name: 'Contact',
                event_id: eventID,      // ✅ আইডি পাঠানো হচ্ছে
                source_url: window.location.href,
                // ✅ EMQ বাড়ানোর জন্য ইউজার ডাটা পাঠানো হচ্ছে
                phone: userData.phone,
                name: userData.name,
                email: userData.email,
                // কুকি ডাটা
                fbp: getCookie('_fbp'),
                fbc: getCookie('_fbc'),
                ttp: getCookie('_ttp')
            };

            $.ajax({
                url: "{{ route('ajax.track.event') }}",
                type: "POST",
                data: data,
                success: function(response) {
                    console.log('✅ Server Event Sent Successfully');
                },
                error: function(xhr) {
                    console.log('❌ Server Event Failed', xhr.responseText);
                }
            });
        });
    });
</script>

@endpush