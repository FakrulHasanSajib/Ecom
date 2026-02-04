@extends('frontEnd.layouts.master') @section('title', 'Home') @push('seo')
<meta name="app-url" content="" />
<meta name="robots" content="index, follow" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<!-- Open Graph data -->
<meta property="og:title" content="" />
<meta property="og:type" content="website" />
<meta property="og:url" content="" />
<meta property="og:image" content="{{ asset($generalsetting->white_logo) }}" />
<meta property="og:description" content="" />
@endpush @push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" rel="stylesheet" />
<style>
  .cat_img {
        position: relative;
        padding-top: 60%;  /* Adjust the aspect ratio for the images */
        max-width: 120px;  /* Control the size of the image container */
        margin: 0 auto; /* Center the container */
    }

    .cat_img img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .banner-img {
    width: 365px;  /* Set the width to 245px */
    height: 100px;  /* Set the height to 75px */
    object-fit: cover;  /* Ensure the image fills the space without distortion */
    margin-bottom: 10px;  /* Optional: add space between banners */
    display: block;  /* Ensure the images are displayed as block elements */
    margin-left: auto;
    margin-right: auto;
}

/* Free Shipping Badge Styles */
.product_item_inner {
    position: relative;
}

.free-shipping-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}

.free-shipping-icon {
    background: linear-gradient(45deg, #28a745, #20c997);
    border-radius: 50px;
    padding: 5px 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 5px;
    animation: pulse 2s infinite;
}

.free-shipping-icon img {
    width: 16px;
    height: 16px;
    filter: brightness(0) invert(1); /* Makes icon white */
}

.free-shipping-text {
    color: white;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}

/* Pulse animation for attention */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .free-shipping-badge {
        top: 5px;
        right: 5px;
    }
    
    .free-shipping-icon {
        padding: 3px 6px;
    }
    
    .free-shipping-text {
        font-size: 8px;
    }
    
    .free-shipping-icon img {
        width: 12px;
        height: 12px;
    }
}

/* Ensure proper positioning when both badges are present */
.product_item_inner .sale-badge {
    top: 10px;
    left: 10px;
}

.product_item_inner .free-shipping-badge {
    top: 10px;
    right: 10px;
}

    /* For medium-sized screens (tablet) */
    @media (max-width: 992px) {
        .cat_img {
            max-width: 100px;  /* Reduce the container size on tablet */
        }
    }

    /* For small screens (mobile) */
    @media (max-width: 576px) {
        .cat_img {
            max-width: 80px;  /* Reduce size further for small mobile screens */
        }
    }
</style>
@endpush @section('content')
<section class="slider-section">
    <div class="container">
        <div class="row">

            <div class="col-sm-3 hidetosm">
                <div class="sidebar-menu">
                    <ul class="hideshow">
                        @foreach ($menucategories as $key => $category)
                            <li>
                                <a href="{{ route('category', $category->slug) }}">
                                    <img src="{{ asset($category->image) }}" alt="" />
                                    {{ $category->name }}
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                                <ul class="sidebar-submenu">
                                    @foreach ($category->subcategories as $key => $subcategory)
                                        <li>
                                            <a href="{{ route('subcategory', $subcategory->slug) }}">
                                                {{ $subcategory->subcategoryName }} <i
                                                    class="fa-solid fa-chevron-right"></i> </a>
                                            <ul class="sidebar-childmenu">
                                                @foreach ($subcategory->childcategories as $key => $childcat)
                                                    <li>
                                                        <a href="{{ route('products', $childcat->slug) }}">
                                                            {{ $childcat->childcategoryName }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>




    <!-- Right side: Slider Section -->
    <div class="col-md-9">
        <div class="home-slider-container">
            <div class="main_slider owl-carousel">
                @foreach ($sliders as $key => $value)
                    <div class="slider-item">
                        <img src="{{ asset($value->image) }}" alt="" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>


        </div>
    </div>
</section>
<!-- slider end -->
<section class="homeproduct">
    <div class="container">
        <div class="row">
            @foreach($sliderbanner as $value)
                <div class="col-md-3 mt-1 col-sm-6">
                    <a href="{{$value->link}}">
                        <img class="banner-img" src="{{asset($value->image)}}" alt="Banner 1">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="homeproduct">
    <div class="container">
        <div class="row">
            <!-- Section Title -->
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name">Top Categories</span>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>

            <!-- Category Carousel -->
        <div class="col-sm-12">
                <div id="categoryCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
            @php
                $categoriesChunks = $menucategories->chunk(6); // Break categories into chunks of 7
            @endphp
            @foreach ($categoriesChunks as $key => $chunk)
                <div class="carousel-item @if ($key == 0) active @endif">
                    <div class="row">
                        @foreach ($chunk as $value)
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="cat_item">
                                    <div class="cat_img">
                                        <a href="{{ route('category', $value->slug) }}">
                                            <img src="{{ asset($value->image) }}" alt="{{ $value->name }}" />
                                        </a>
                                    </div>
                                    <div class="cat_name">
                                        <a href="{{ route('category', $value->slug) }}">
                                            {{ $value->name }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name"> Free Shipping </span>
                            </div>

                            <div class="">
                                <div ></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
    <div class="product_slider owl-carousel">
        @foreach ($new_freeshiping as $key => $value)
            <div class="product_item wist_item">
                <div class="product_item_inner">
                    {{-- Sale Badge --}}
                    @if($value->old_price)
                    <div class="sale-badge">
                        <div class="sale-badge-inner">
                            <div class="sale-badge-box">
                                <span class="sale-badge-text">
                                    <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}%</p>
                                    ছাড়
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Free Shipping Badge --}}
                    @if($value->free_shipping)
                    <div class="free-shipping-badge">
                        <div class="free-shipping-icon">
                        <span class="free-shipping-text">ফ্রি ডেলিভারি</span>
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
                                href="{{ route('product', $value->slug) }}">{{ \Illuminate\Support\Str::limit($value->name, 25) }}</a>
                        </div>
                        <div class="pro_price">
                            <p>
                                @if ($value->old_price)
                                 <del>৳ {{ $value->old_price }}</del>
                                @endif
                                ৳ {{ $value->new_price }}
                            </p>
                        </div>
                    </div>
                </div>
               @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
    <div class="pro_btn">
        <div class="cart_btn order_button">
            <a href="{{ route('product', $value->slug) }}" class="addcartbutton" style="background: {{ $generalsetting->headercolor }};">
                অর্ডার করুন
            </a>
        </div>
    </div>
@else
    <div class="pro_btn">
        <div class="cart_btn order_button">
            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $value->id }}" />
                <input type="hidden" name="qty" value="1" />
                <button type="submit" class="addcartbutton" style="background: {{ $generalsetting->headercolor }};">
                    <span class="left-text">অর্ডার করুন</span>
                    <span class="plus">+</span>
                </button>
            </form>
        </div>
    </div>
@endif

            </div>
        @endforeach
    </div>
</div>
{{--
<div class="col-sm-12">
   <a href="{{ route('hotdeals') }}" class="view_more_btn" style="float:left">View More</a>
</div>
--}}
        </div>
    </div>
</section>


<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name"> New Arrival </span>
                            </div>

                            <div class="">
                                <div ></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
    <div class="product_slider owl-carousel">
        @foreach ($new_arrival as $key => $value)
            <div class="product_item wist_item">
                <div class="product_item_inner">
                    {{-- Sale Badge --}}
                    @if($value->old_price)
                    <div class="sale-badge">
                        <div class="sale-badge-inner">
                            <div class="sale-badge-box">
                                <span class="sale-badge-text">
                                    <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}%</p>
                                    ছাড়
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Free Shipping Badge --}}
                    @if($value->free_shipping)
                    <div class="free-shipping-badge">
                        <div class="free-shipping-icon">
                       <span class="free-shipping-text">ফ্রি ডেলিভারি</span>
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
                                href="{{ route('product', $value->slug) }}">{{ \Illuminate\Support\Str::limit($value->name, 25) }}</a>
                        </div>
                        <div class="pro_price">
                            <p>
                                @if ($value->old_price)
                                 <del>৳ {{ $value->old_price }}</del>
                                @endif
                                ৳ {{ $value->new_price }}
                            </p>
                        </div>
                    </div>
                </div>
                 @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
    <div class="pro_btn">
        <div class="cart_btn order_button">
            <a href="{{ route('product', $value->slug) }}" class="addcartbutton" style="background: {{ $generalsetting->headercolor }};">
                অর্ডার করুন
            </a>
        </div>
    </div>
@else
    <div class="pro_btn">
        <div class="cart_btn order_button">
            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $value->id }}" />
                <input type="hidden" name="qty" value="1" />
                <button type="submit" class="addcartbutton" style="background: {{ $generalsetting->headercolor }};">
                    <span class="left-text">অর্ডার করুন</span>
                    <span class="plus">+</span>
                </button>
            </form>
        </div>
    </div>
@endif

            </div>
        @endforeach
    </div>
</div>
{{--
<div class="col-sm-12">
   <a href="{{ route('hotdeals') }}" class="view_more_btn" style="float:left">View More</a>
</div>
--}}
        </div>
    </div>
</section>
@if(!empty($middlebanner))
<section class="banner-section">
    <div class="container">
        <div class="row">
            <!-- Single Banner Section -->
            <div class="col-12 banner-col">
                <div class="banner-item" style="background-image: url('{{ asset($middlebanner->image) }}');">
                    <div class="banner-content">
                        <h3 class="banner-title">Hot Deal</h3>
                        <p class="banner-description">Shop Now and Save Big</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endif
@if(!empty($hotdeal_top))
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name"> Hot Deal </span>
                            </div>

                            <div class="">
                                <div class="offer_timer" id="simple_timer"></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($hotdeal_top as $key => $value)
                        <div class="product_item wist_item">
                            <div class="product_item_inner">
                                @if($value->old_price)
                                <div class="sale-badge">
                                    <div class="sale-badge-inner">
                                        <div class="sale-badge-box">
                                            <span class="sale-badge-text">
                                                <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}%</p>
                                                ছাড়
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                {{-- Free Shipping Badge --}}
                    @if($value->free_shipping)
                    <div class="free-shipping-badge">
                        <div class="free-shipping-icon">
                        <span class="free-shipping-text">ফ্রি ডেলিভারি</span>
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
                                            href="{{ route('product', $value->slug) }}">{{ \Illuminate\Support\Str::limit($value->name, 25) }}</a>
                                    </div>
                                    <div class="pro_price">
                                        <p>
                                            @if ($value->old_price)
                                             <del>৳ {{ $value->old_price }}</del>
                                            @endif

                                            ৳ {{ $value->new_price }}

                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
    <div class="pro_btn">
        <div class="cart_btn order_button">
            <a href="{{ route('product', $value->slug) }}" class="addcartbutton" style="background: {{ $generalsetting->headercolor }};">
                অর্ডার করুন
            </a>
        </div>
    </div>
@else
    <div class="pro_btn">
        <div class="cart_btn order_button">
            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $value->id }}" />
                <input type="hidden" name="qty" value="1" />
                <button type="submit" class="addcartbutton" style="background: {{ $generalsetting->headercolor }};">
                    <span class="left-text">অর্ডার করুন</span>
                    <span class="plus">+</span>
                </button>
            </form>
        </div>
    </div>
@endif

                        </div>
                    @endforeach
                </div>
            </div>
           {{--
            <div class="col-sm-12">
               <a href="{{ route('hotdeals') }}" class="view_more_btn" style="float:left">View More</a>
            </div>
            --}}
        </div>
    </div>
</section>
@endif
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name"> Best Seling </span>
                            </div>

                            <div class="">
                                <div class="" ></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($bestSellingProducts as $key => $value)
                        <div class="product_item wist_item">
                            <div class="product_item_inner">
                                @if($value->old_price)
                                <div class="sale-badge">
                                    <div class="sale-badge-inner">
                                        <div class="sale-badge-box">
                                            <span class="sale-badge-text">
                                                <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}%</p>
                                                ছাড়
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                   {{-- Free Shipping Badge --}}
                                    @if($value->free_shipping)
                                    <div class="free-shipping-badge">
                                        <div class="free-shipping-icon">
                                         <span class="free-shipping-text">ফ্রি ডেলিভারি</span>
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
                                            href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 25) }}</a>
                                    </div>
                                    <div class="pro_price">
                                        <p>
                                            @if ($value->old_price)
                                             <del>৳ {{ $value->old_price }}</del>
                                            @endif

                                            ৳ {{ $value->new_price }}

                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
    <div class="pro_btn">
        <div class="cart_btn order_button">
            <a href="{{ route('product', $value->slug) }}" class="addcartbutton" style="background: {{ $generalsetting->headercolor }};">
                অর্ডার করুন
            </a>
        </div>
    </div>
@else
    <div class="pro_btn">
        <div class="cart_btn order_button">
            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $value->id }}" />
                <input type="hidden" name="qty" value="1" />
                <button type="submit" class="addcartbutton" style="background: {{ $generalsetting->headercolor }};">
                    <span class="left-text">অর্ডার করুন</span>
                    <span class="plus">+</span>
                </button>
            </form>
        </div>
    </div>
@endif

                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

<section class="homeproduct">
    <div class="container">
    <div class="row">
    <div class="col-sm-12">
                <div class="sec_title d-flex justify-content-between align-items-center">
                    <h3 class="section-title-header">
                        <span class="section-title-name">All Product</span>
                    </h3>

                </div>
            </div>
      
</div>
<div id="product-list" class="row">
    @foreach($products as $product)
    <div class="col-lg-2 col-md-3 col-sm-6 col-6" style="padding: 10px;">
        <div class="product_item wist_item">
            <div class="product_item_inner">
                <!-- Free Shipping Badge -->
                @if($product->free_shipping == 1)
                <div class="free-shipping-badge">
                    <div class="free-shipping-icon">
                        <img src="{{ asset('images/free-shipping-icon.png') }}" alt="Free Shipping" />
                      <span class="free-shipping-text">ফ্রি ডেলিভারি</span>
                    </div>
                </div>
                @endif
                
                <!-- Product Content -->
                <div class="pro_img">
                    <a href="{{ route('product', $product->slug) }}">
                        <img src="{{ asset($product->image ? $product->image->image : '') }}" alt="{{ $product->name }}" class="img-fluid" />
                    </a>
                </div>
                <div class="pro_des">
                    <div class="pro_name">
                        <a href="{{ route('product', $product->slug) }}"> {{ Str::limit($product->name, 25) }}</a>
                    </div>
                    <div class="pro_price">
                        <p><del class="old-price">৳{{ $product->old_price }}</del></p>
                        <p><span class="new-price">৳{{ $product->new_price }}</span></p>
                    </div>
                </div>
                <div class="pro_btn">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}" />
                        <input type="hidden" name="qty" value="1" />
                        <button type="submit" class="btn btn-primary" style="background: {{ $generalsetting->headercolor }};">অর্ডার করুন +</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>


<div class="col-sm-12 text-center" id="pro_more">
        <a href="javascript:void(0);" class="view_more_btn" id="more_products" style="background:{{$generalsetting->headercolor}} !important;">View More</a>
    </div>

    </div>
</section>



@endsection @push('script')
<script src="{{ asset('frontEnd/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontEnd/js/jquery.syotimer.min.js') }}"></script>
<script>
    let page = 1; // To keep track of the current page

    // When 'View More' is clicked
    $('#more_products').click(function() {
        page++; // Increment page number for the next request
        loadMoreProducts(page);
    });

    function loadMoreProducts(page) {
        $.ajax({
            url: "{{ route('products.loadMore') }}?page=" + page,
            method: "GET",
            success: function(response) {
                // If more products are returned, append them to the product list
                if (response.trim()) {
                    $('#product-list').append(response);
                } else {
                    $('#more_products').hide(); // Hide the 'View More' button if no more products
                }
            }
        });
    }
</script>


<script>
    $(document).ready(function() {
        $(".main_slider").owlCarousel({
            items: 1,
            loop: true,
            dots: false,
            autoplay: true,
            nav: true,
            autoplayHoverPause: false,
            margin: 0,
            mouseDrag: true,
            smartSpeed: 8000,
            autoplayTimeout: 3000,
            animateOut: "fadeOutDown",
            animateIn: "slideInDown",

            navText: ["<i class='fa-solid fa-angle-left'></i>",
                "<i class='fa-solid fa-angle-right'></i>"
            ],
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".hotdeals-slider").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 3,
                    nav: true,
                },
                600: {
                    items: 3,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: true,
                    loop: false,
                },
            },
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".category-slider").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 5,
                    nav: true,
                },
                600: {
                    items: 3,
                    nav: false,
                },
                1000: {
                    items: 8,
                    nav: true,
                    loop: false,
                },
            },
        });

        $(".product_slider").owlCarousel({
            margin: 15,
            items: 6,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                },
                600: {
                    items: 5,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: false,
                },
            },
        });
    });
</script>

<script>
    $("#simple_timer").syotimer({
        date: new Date(2015, 0, 1),
        layout: "hms",
        doubleNumbers: false,
        effectType: "opacity",

        periodUnit: "d",
        periodic: true,
        periodInterval: 1,
    });
</script>
@endpush