<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $generalsetting->name }}</title>
        <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}" type="image/x-icon" />
        <!-- fot awesome -->
        <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/all.css" />
        <!-- core css -->
        <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/bootstrap.min.css" />
        <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/animate.css" />
        <!-- owl carousel -->
        <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/owl.theme.default.css" />
        <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/owl.carousel.min.css" />
        <!-- owl carousel -->
        <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/select2.min.css" />
        <!-- common css -->
        <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/style.css" />
        <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/responsive.css" />
        
        
        
        <meta name="app-url" content="{{route('campaign',$campaign_data->slug)}}" />
        <meta name="robots" content="index, follow" />
        <meta name="description" content="{{$campaign_data->name}}" />
        <meta name="keywords" content="{{ $campaign_data->slug }}" />
        
        <!-- Twitter Card data -->
    <meta name="twitter:card" content="product" />
    <meta name="twitter:site" content="{{$campaign_data->name}}" />
    <meta name="twitter:title" content="{{$campaign_data->name}}" />
    <meta name="twitter:description" content="{{ strip_tags($campaign_data->short_description ?? '') }}" />
    <meta name="twitter:creator" content="Rakibul Islam" />
    <meta property="og:url" content="{{route('campaign',$campaign_data->slug)}}" />
    <meta name="twitter:image" content="{{asset($campaign_data->image_one)}}" />
        
        <!-- Open Graph data -->
    <meta property="og:title" content="{{$campaign_data->name}}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{route('campaign',$campaign_data->slug)}}" />
    <meta property="og:image" content="{{asset($campaign_data->image_one)}}" />
    <meta property="og:description" content="{{ strip_tags($campaign_data->short_description ?? '') }}" />
    <meta property="og:site_name" content="{{$campaign_data->name}}" />
        <style type="text/css">
        	.hero-image-container {
  text-align: center; /* Center the image horizontally */
}

/* Image Styles */
img.img-fluid {
  max-width: 100%;
  height: auto;
  object-fit: contain; /* Ensures the aspect ratio is maintained */
}

img.rounded {
  border-radius: 8px; /* Optional: Adds rounded corners */
}

    .area-options {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    input.form-control {
        height: auto; /* reset */
        padding: 8px 12px; /* adjust as needed */
        font-size: 16px;
        line-height: 1.5;}
    
    .area-options {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                margin-top: 10px;
            }
    
            .area-option {
                border: 2px solid #ddd;
                padding: 12px 25px;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
                background-color: #fff;
                position: relative;
                min-width: 120px;
                text-align: center;
                font-weight: 500;
                color: #333;
            }
    
            .area-option input[type="radio"] {
                display: none;
            }
    
            /* Hover effect */
            .area-option:hover {
                border-color: #007bff;
                background-color: #f8f9fa;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
            }
    
            /* Selected state - this is the key part you want to customize */
            .area-option:has(input[type="radio"]:checked) {
                border-color: #28a745; /* Green border for selected */
                background-color: #d4edda; /* Light green background */
                color: #155724; /* Dark green text */
                font-weight: bold;
                box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
            }
    
            /* Alternative color schemes - uncomment the one you prefer */
            
            /* Blue Theme */
            /*
            .area-option:has(input[type="radio"]:checked) {
                border-color: #007bff;
                background-color: #e7f1ff;
                color: #004085;
                font-weight: bold;
                box-shadow: 0 4px 12px rgba(0, 123, 255, 0.25);
            }
            */
    
            /* Purple Theme */
            /*
            .area-option:has(input[type="radio"]:checked) {
                border-color: #6f42c1;
                background-color: #f3e8ff;
                color: #4c1d95;
                font-weight: bold;
                box-shadow: 0 4px 12px rgba(111, 66, 193, 0.25);
            }
            */
    
            /* Orange Theme */
            /*
            .area-option:has(input[type="radio"]:checked) {
                border-color: #fd7e14;
                background-color: #fff3cd;
                color: #856404;
                font-weight: bold;
                box-shadow: 0 4px 12px rgba(253, 126, 20, 0.25);
            }
            */
    
            /* Red Theme */
            /*
            .area-option:has(input[type="radio"]:checked) {
                border-color: #dc3545;
                background-color: #f8d7da;
                color: #721c24;
                font-weight: bold;
                box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
            }
            */
    
            /* For older browsers that don't support :has() selector */
            .area-option.selected {
                border-color: #28a745;
                background-color: #d4edda;
                color: #155724;
                font-weight: bold;
                box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
            }
    
            /* Optional: Add a checkmark icon for selected items */
            .area-option:has(input[type="radio"]:checked)::after,
            .area-option.selected::after {
                content: "✓";
                position: absolute;
                top: -8px;
                right: -8px;
                background-color: #28a745;
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: bold;
            }
    
            /* Form styling */
            .form-container {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background: #f8f9fa;
                border-radius: 10px;
            }
    
            .form-group {
                margin-bottom: 20px;
            }
    
            label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: #333;
            }
.order_place {
        font-size: 18px;  /* Larger text */
        padding: 15px 30px;  /* Increased padding */
        border: none;  /* Remove any border */
        border-radius: 5px;  /* Rounded corners */
        cursor: pointer;  /* Change cursor to pointer */
        transition: all 0.3s ease;  /* Smooth transition for hover */
    }

    .order_place:hover {
        background-color: #0056b3;  /* Darker blue on hover */
    }

  
        </style>
    </head>

    <body>
        <div>
             @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
        </div>
         @php
            $subtotal = Cart::instance('shopping')->subtotal();
            $subtotal=str_replace(',','',$subtotal);
            $subtotal=str_replace('.00', '',$subtotal);
            $shipping = Session::get('shipping')?Session::get('shipping'):0;
        @endphp
       <section class="hero-section py-3">
  <div class="container">
       
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="hero-image-container">
          <a href="/">
           <img src="{{asset($generalsetting->white_logo)}}" alt="" />
          </a>
          <img 
        </div>
         
      </div>
    </div>
  </div>
</section>


        <section style="background-color: green; background-repeat: no-repeat; background-size: cover; background-position: center;">
    <div class="container">
        <div class="row text-center">
            <!-- Heading Section -->
            <div class="col-12">
                <h2 class="text-white mb-4">{{$campaign_data->banner_title}} </h2>
            </div>
        </div>
        <div class="row">
            <!-- Video Section -->
            <div class="col-12 mb-4">
    <div class="camp_vid">
           <iframe width="853" height="480" src="https://www.youtube.com/embed/{{$campaign_data->video}}" title="{{$campaign_data->banner_title}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen=""></iframe>
                        </div>
</div>

            <!-- Another Heading Section (Optional, you can remove this if not needed) -->
            <div class="col-12 mb-4">
                <h2 class="text-white">{{Str::limit($product->name,40)}}</h2>
            </div>
        </div>
        <!-- Order Button Section -->
        <div class="row">
            <div class="col-12 ord_btn">
                <a href="#order_form" class="cam_order_now" id="cam_order_now"><i class="fa-solid fa-cart-shopping"></i> অর্ডার করুন </a>
            </div>
        </div>
    </div>
</section>


  <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="rev_inn">
                            <div class="border_line">
                            	<h3 class="text-primary">Cilent Review</h3>
                            </div>
                            <div class="review_slider owl-carousel">
                            @foreach($campaign_data->images as $key=>$value)
                            <div class="review_item">
                                <img src="{{asset($value->image)}}" alt="">
                            </div>
                            @endforeach
                           </div>
                            <div class="col-sm-12">
                                <div class="ord_btn">
                                    <a href="#order_form" class="cam_order_now" id="cam_order_now"> অর্ডার করতে ক্লিক করুন <i class="fa-solid fa-hand-point-right"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
       
 <section class="why_choose_sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="why_choose_inn border border-success shadow p-4 rounded">
                    <div class="why_choose">
                        <!-- Header Section -->
                        <div class="why_choose_head text-center">
                            <h2>{{$campaign_data->section_title}}</h2>
                        </div>
                        <!-- Middle Section with List and Image -->
                        <div class="why_choose_midd d-flex flex-wrap justify-content-between">
                            <!-- Left Column: List -->
                            <div class="why_choose_widget col-md-6 mb-4">
                              {!! $campaign_data->description !!}
                                
                            </div>

                            <!-- Right Column: Image -->
                            <div class="why_choose_widget col-md-6 mb-4">
                                <div class="why_img">
                                    <img src="{{asset($campaign_data->image_section)}}" alt="Honey Image" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

        

    <section class="form_sec">
        <div class="container">
           <div class="row">
             <div class="col-sm-12">
                <div class="form_inn">
                    <div class="col-sm-12">
                        <div class="row">
                <div class="col-sm-12">
                   <h2 class="campaign_offer">অফারটি সীমিত সময়ের জন্য, তাই অফার শেষ হওয়ার আগেই অর্ডার করুন</h2>
                </div>
            </div>
            <div class="row order_by">
            <div class="col-sm-5 cus-order-2">
                <div class="checkout-shipping" id="order_form">
                    <form action="{{route('customer.ordersave')}}" method="POST" data-parsley-validate="">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5 class="potro_font">আপনার ইনফরমেশন  দিন </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="name">আপনার নাম লিখুন * </label>
                                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}"  required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- col-end -->
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="phone">আপনার নাম্বার লিখুন *</label>
                                        <input type="text" minlength="11" id="number" maxlength="11"
                       pattern="0[0-9]+"
                       title="please enter number only and 0 must first character"
                       title="Please enter an 11-digit number." id="phone"
                       class="form-control @error('phone') is-invalid @enderror" name="phone"
                       value="{{ old('phone') }}"
                       required/>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- col-end -->
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="address">ঠিকানা লিখুন * জেলা,উপজেলা,থানা,পৌরসভা*</label>
                                        
                                        <textarea id="address" class="form-control border-address @error('address') is-invalid @enderror" name="address" required style="border-radius: 5px;">{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="area">ডেলিভারি এরিয়া নিবার্চন করুন * *</label>
                                        <div class="area-options">
                                        @foreach($shippingcharge as $key => $value)
                                            <label class="area-option">
                                                <input id="area" type="radio" name="area" value="{{ $value->id }}" required>
                                                {{ $value->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                        @error('area')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button class="order_place" type="submit"> অর্ডারটি কনফার্ম করুন </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- card end -->
                </form>
                </div>
            </div>
            <!-- col end -->
            <div class="col-sm-7 cust-order-1">
                <div class="cart_details">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="potro_font"> পণ্যের বিবারণ  </h5>
                        </div>
                        <div class="card-body cartlist  table-responsive">
                            <table class="cart_table table table-bordered table-striped text-center mb-0">
                                <thead>
                                   <tr>
                                    <th style="width: 20%;">ডিলিট</th>
                                      <th style="width: 40%;">প্রোডাক্ট</th>
                                      <th style="width: 20%;">পরিমাণ</th>
                                      <th style="width: 20%;">মূল্য</th>
                                     </tr>
                                </thead>

                                <tbody>
                                    @foreach(Cart::instance('shopping')->content() as $value)
                                    <tr>
                                        <td>
                                            <a href="{{route('product',$value->options->slug)}}"><i class="fas fa-trash text-danger"></i></a>
                                        </td>
                                        <td class="text-left">
                                             <a style="font-size: 14px;" href="{{route('product',$value->options->slug)}}"><img src="{{asset($value->options->image)}}" height="30" width="30"> {{Str::limit($value->name,20)}}</a>
                                        </td>
                                        <td width="15%" class="cart_qty">
                                            <div class="qty-cart vcart-qty">
                                                <div class="quantity">
                                                    <button class="minus cart_decrement"  data-id="{{$value->rowId}}">-</button>
                                                    <input type="text" value="{{$value->qty}}" readonly />
                                                    <button class="plus  cart_increment" data-id="{{$value->rowId}}">+</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>৳{{$value->price*$value->qty}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                     <tr>
                                      <th colspan="3" class="text-end px-4">মোট</th>
                                      <td>
                                       <span id="net_total"><span class="alinur">৳ </span><strong>{{$subtotal}}</strong></span>
                                      </td>
                                     </tr>
                                     <tr>
                                       <th colspan="3" class="text-end px-4">ডেলিভারি চার্জ</th>
                                      <td>
                                       <span id="cart_shipping_cost"><span class="alinur">৳ </span><strong>{{$shipping}}</strong></span>
                                      </td>
                                     </tr>
                                     <tr>
                                      <th colspan="3" class="text-end px-4">সর্বমোট  </th>
                                      <td>
                                       <span id="grand_total"><span class="alinur"> </span><strong>{{$subtotal+$shipping}}</strong></span>
                                      </td>
                                     </tr>
                                    </tfoot>
                            </table>
                        
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->
            </div>
                    </div>
                </div>

             </div>
            </div>
        </div>
    </section>

    <section class="bg-light py-4 text-center footer">
  <div class="container">
    <h4 class="mb-0">
            Copyright © {{ date('Y') }} {{$generalsetting->name}}
      Powered by: <a href="https://eiconbd.com" class="text-warning font-weight-bold">EiconBD</a>
    </h4>
  </div>
</section>

        <script src="{{ asset('public/frontEnd/campaign/js') }}/jquery-2.1.4.min.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/all.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/bootstrap.min.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/owl.carousel.min.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/select2.min.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/script.js"></script>
        
        <!-- bootstrap js -->
        {!! Toastr::message() !!}
    @stack('script')
    <script src="{{ asset('backEnd/assets/js/toastr.min.js') }}"></script>
        <script>


// Handle form submission with loading state
$(document).ready(function() {
    $('form').on('submit', function(e) {
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> প্রক্রিয়াধীন...');
    });
});
</script>

<script>
            // 1. AddToCart Event (অর্ডার করুন বাটনে ক্লিক করলে)
            $(".cam_order_now").on("click", function (e) {
                // Facebook AddToCart Event Track
                fbq('track', 'AddToCart', {
                    content_name: '{{ $campaign_data->name }} - {{ $product->name }}',
                    content_ids: ['LP_{{ $product->id }}'],
                    content_type: 'product',
                    value: {{ $product->new_price }},
                    currency: 'BDT' 
                });

                // ফর্মে স্ক্রল করার ফাংশনটি যেহেতু আগেই ছিল, তাই এটি এখানে থাকুক
                $('html, body').animate({
                    scrollTop: $("#order_form").offset().top - 20
                }, 1000);
            });
            
            // 2. InitiateCheckout Event (অর্ডার কনফার্ম করুন বাটনে ক্লিক করলে/ফর্ম সাবমিট হলে)
            $('#order_form form').on('submit', function(e) {
                // InitiateCheckout Event
                // মোট মূল্য এবং আইটেম সংখ্যা ট্র্যাক করা হচ্ছে
                var cartValue = parseFloat("{{ $subtotal + $shipping }}");
                var numItems = {{ Cart::instance('shopping')->count() }}; 

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
            
        </script>
        <script>
         
            $(document).ready(function () {
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
        </script>
        <script>
            $(document).ready(function() {
                $('.select2').select2();
            });
        </script>
        <script>
             $('input[name="area"]').on("change", function () {
                var id = $(this).val(); // get selected area's ID
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{ route('shipping.charge') }}",
                    dataType: "html",
                    success: function(response) {
                        $('.cartlist').html(response); // update cartlist area
                    }
                });
            });

        </script>
           <script>
            $(".cart_remove").on("click", function () {
                var id = $(this).data("id");
                $("#loading").show();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.remove')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                $("#loading").hide();
                                return cart_count() + mobile_cart() + cart_summary();
                            }
                        },
                    });
                }
            });
            $(".cart_increment").on("click", function () {
                var id = $(this).data("id");
                $("#loading").show();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.increment')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                $("#loading").hide();
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });

            $(".cart_decrement").on("click", function () {
                var id = $(this).data("id");
                $("#loading").show();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.decrement')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                $("#loading").hide();
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });

        </script>
        <script>
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
                    300: {
                        items: 1,
                    },
                    480: {
                        items: 2,
                    },
                    768: {
                        items: 5,
                    },
                    1170: {
                        items: 5,
                    },
                }
            });
        </script>

        <script>
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
                    300: {
                        items: 1,
                    },
                    480: {
                        items: 2,
                    },
                    768: {
                        items: 3,
                    },
                    1170: {
                        items: 3,
                    },
                }
            });
            function checkPhoneLength(input) {
        const maxLength = 11;
        if (input.value.length >= maxLength) {
            input.value = input.value.slice(0, maxLength); // ensure no extra digits
            input.disabled = true;
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('.area-option input[type="radio"]');
    
    radioButtons.forEach(function(radio) {
        radio.addEventListener('change', function() {
            // Remove selected class from all options
            document.querySelectorAll('.area-option').forEach(function(option) {
                option.classList.remove('selected');
            });
            
            // Add selected class to the parent of the checked radio
            if (this.checked) {
                this.closest('.area-option').classList.add('selected');
            }
        });
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