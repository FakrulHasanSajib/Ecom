<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <meta name="twitter:creator" content="hellodinajpur.com" />
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
.product-row {
    border: 1px solid #ddd;  /* Gray border */
    padding: 15px;            /* Space inside the card */
    border-radius: 8px;       /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  /* Subtle shadow for depth */
    background-color: #fff;   /* White background */
    transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for hover effects */
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

.area-option {
    border: 2px solid #ccc;
    padding: 10px 35px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.area-option input[type="radio"] {
    display: none;
}

.area-option:hover {
    border-color: #888;
}

.area-option input[type="radio"]:checked + label,
.area-option input[type="radio"]:checked ~ span {
    border-color: #007bff;
    background-color: #e7f1ff;
    font-weight: bold;
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

.product-row:hover {
    transform: translateY(-5px);  /* Slight lift effect on hover */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);  /* Darker shadow on hover */
}

.remove-item-checkbox.disabled {
    opacity: 0.5;  /* Make disabled checkboxes look faded */
    pointer-events: none;  /* Prevent any further interactions */
}
        </style>
    </head>

    <body>
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
            <div class="col-12 mt-1">
                <h2 class="text-white mb-3">{{$campaign_data->banner_title}} </h2>
            </div>
        </div>
        <div class="row">
            <!-- Video Section -->
       <div class="col-12 mb-4">
  <div class="camp_vid">
    <iframe width="853" height="480" 
            src="https://www.youtube.com/embed/{{$campaign_data->video}}?autoplay=1&mute=1" 
            title="{{$campaign_data->banner_title}}" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            allowfullscreen>
    </iframe>
  </div>
</div>


            <!-- Another Heading Section (Optional, you can remove this if not needed) -->
            <div class="col-12 mb-4">
                <h2 class="text-white">{{$product->name}}</h2>
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
            <div class="col-md-12">
                <div class="rev_inn">
                    <!-- Add your image here -->
                    <img src="{{asset($campaign_data->image_one)}}" alt="Banner Image" class="img-fluid" />
                </div>
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
<section>
    <div class="container">
        <div class="row">
                             <div class="col-sm-12">
                   <h2 class="campaign_offer">অফারটি সীমিত সময়ের জন্য, তাই অফার শেষ হওয়ার আগেই অর্ডার করুন</h2>
                </div>
                <div class="cart_details">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="potro_font">পণ্যের বিবরণ</h5>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                @php
                                 $myid=0;
                                 $myprice =0;
                                @endphp
                                @foreach(Cart::instance('shopping')->content() as $key => $value)
                        @php
                            $myid++;
                        @endphp
                        <div class="col-md-6 col-12 d-flex align-items-center mb-3 product-row" data-row-id="{{ $value->rowId }}" 
                        data-price="{{ $value->price * $value->qty }}">
                    <!-- Checkbox for item removal -->
                    <div class="form-check mr-3">
                        <input type="checkbox" class="remove-item-checkbox form-check-input disabled" 
                            data-id="{{ $value->rowId }}" 
                            data-price="{{ $value->price * $value->qty }}" 
                            @if($myid == 1) checked @endif />
                    </div>
                
                    <!-- Product Image and Name Section -->
                    <div class="d-flex flex-column align-items-start col-md-8 col-7">
                        <a class="d-flex flex-column align-items-start" style="font-size: 14px;">
                            <img src="{{ asset($value->options->image) }}" height="80" width="80" class="mr-2" alt="{{ $value->name }}">
                            <span>{{ Str::limit($value->name, 40) }}</span>
                        </a>
                    </div>
                
                    <!-- Price Section (Aligned below the product name) -->
                    <div class="col-md-4 col-5 text-left mt-2">
                        <span class="font-weight-bold">৳{{ $value->price * $value->qty }}</span>
                    </div>
                </div>

                    @endforeach
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
             <!-- Cart Details Section (will be first on mobile) -->
            <div class="col-sm-12 col-md-7 order-sm-1 order-md-2">
                <div class="cart_details">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="potro_font">পণ্যের বিবরণ</h5>
                        </div>
                       <div class="card-body cartlist  table-responsive">
                           <div class="carmys">

                           <div class="cart-summary">
                                <div class="d-flex justify-content-between">
                                    <span>মোট</span>
                                    <span id="net_total">৳{{ $myprice }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>ডেলিভারি চার্জ</span>
                                    <span id="cart_shipping_cost">৳{{ $shipping }}</span>
                                </div>
                                <div class="d-flex justify-content-between font-weight-bold">
                                    <span>সর্বমোট</span>
                                    <span id="total_price">৳{{ $myprice + $shipping }}</span>
                                </div>
                            </div>
                           </div>



                        </div>
                    </div>
                </div>
            </div>
            <!-- Customer Information Section -->
            <div class="col-sm-12 col-md-5">
                <div class="checkout-shipping" id="order_form">
                    <form action="{{ route('order_newsave') }}" method="POST" data-parsley-validate="">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h5 class="potro_font">আপনার ইনফরমেশন দিন</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="name">আপনার নাম লিখুন *</label>
                                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="নাম" required>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <!-- col-end -->
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="phone">আপনার মোবাইল লিখুন *</label>
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
                                        <label for="address">আপনার ঠিকানা লিখুন   *</label>
                                        <textarea id="address" class="form-control border-address @error('address') is-invalid @enderror" name="address" required style="border-radius: 5px;">{{ old('address') }}</textarea>>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="area">আপনার এরিয়া সিলেক্ট করুন  *</label>
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
                                <input type="hidden" id="temid" name="temid[]" value=""/> 
                                <!-- col-end -->
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button class="order_place" type="submit">  অর্ডার কনফার্ম করুন   </button>
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



    <section class="bg-light py-4 text-center footer">
  <div class="container">
    <h4 class="mb-0">
            Copyright © {{ date('Y') }} {{$generalsetting->name}}
      Powered by: <a href="https://eiconbd.com" class="text-warning font-weight-bold">EiconBD</a>
      <a href="/" class="text-warning font-weight-bold"></a>
    </h4>
  </div>
</section>

        <script src="{{ asset('public/frontEnd/campaign/js') }}/jquery-2.1.4.min.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/all.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/bootstrap.min.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/owl.carousel.min.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/select2.min.js"></script>
        <script src="{{ asset('public/frontEnd/campaign/js') }}/script.js"></script>
        
        <script>
    // Toastr options
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right", // Options: toast-top-center, toast-bottom-right, etc.
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>
        <!-- bootstrap js -->
        {!! Toastr::message() !!}
    @stack('script')
    <script src="{{ asset('backEnd/assets/js/toastr.min.js') }}"></script>

        
        <script>
            // 1. AddToCart Event (অর্ডার করুন বাটনে ক্লিক করলে)
            // এটি ট্রিগার হবে যখন ইউজার "অর্ডার করুন" (cam_order_now) বাটনে ক্লিক করবে
            $(".cam_order_now").on("click", function (e) {
                
                // Facebook AddToCart Event Track
                fbq('track', 'AddToCart', {
                    content_name: '{{ $campaign_data->name }} - {{ $product->name }}',
                    content_ids: ['LP_{{ $product->id }}'],
                    content_type: 'product',
                    value: {{ $product->new_price }},
                    currency: 'BDT' // আপনার কারেন্সি কোড
                });

                // ফর্মে স্ক্রল করার ফাংশন
                $('html, body').animate({
                    scrollTop: $("#order_form").offset().top - 20
                }, 1000);
            });
            
            // 2. InitiateCheckout Event (অর্ডার কনফার্ম করুন বাটনে ক্লিক করলে/ফর্ম সাবমিট হলে)
            // এটি ট্রিগার হবে যখন ইউজার অর্ডার ফর্ম সাবমিট করার চেষ্টা করবে
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
           
             $(document).ready(function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            let myprice = 0;
            let selectedIds = [];

        // Function to update total price and selected IDs
        function updateTotalPrice() {
            // Update displayed total prices
            $('#net_total').text('৳' + myprice);
            $('#total_price').text('৳' + (myprice + {{ $shipping }}));  // Ensure $shipping is correctly passed
            $('#temid').val(selectedIds.join(','));
    
            // Call updateCart to reflect changes
            updateCart();
        }

        // Initialize selected items and total price based on the initial checkbox state
        $('.remove-item-checkbox').each(function() {
            let itemPrice = $(this).data('price');
            let id = $(this).data("id");
            if ($(this).is(':checked')) {
                myprice += itemPrice;
                selectedIds.push(id);
                 
            }
            updateTotalPrice();
            
        });
       updateCart();
    // Update the displayed total prices after page load
    

    // Handle checkbox state changes
    $('.remove-item-checkbox').on('change', function() {
         
        let itemPrice = $(this).data('price');
        var id = $(this).data("id");
      
        if ($(this).is(':checked')) {
            // If checked, add price and ID
            myprice += itemPrice;
            if (!selectedIds.includes(id)) {  // Prevent adding the same ID multiple times
                selectedIds.push(id);
            }
        } else {
            // If unchecked, subtract price and remove ID
            myprice -= itemPrice;
            selectedIds = selectedIds.filter(item => item !== id);  // Remove ID from selectedIds
        }
    
        // Update total prices after checkbox change
        updateTotalPrice();
    });

    // Handle row click to toggle checkbox and update price
    $('.product-row').on('click', function() {
        var checkbox = $(this).find('.remove-item-checkbox');
        var isChecked = checkbox.prop('checked');
        var itemPrice = checkbox.data('price');
        var id = checkbox.data("id");

        // Toggle checkbox state
        checkbox.prop('checked', !isChecked);

        // Update total price based on the checkbox state
        if (!isChecked) {
            // If checkbox was unchecked, add price and ID
            myprice += itemPrice;
            if (!selectedIds.includes(id)) {  // Prevent adding the same ID multiple times
                selectedIds.push(id);
            }
        } else {
            // If checkbox was checked, subtract price and remove ID
            myprice -= itemPrice;
            selectedIds = selectedIds.filter(item => item !== id);  // Remove ID from selectedIds
        }

        // Update the displayed total prices after row click
        updateTotalPrice();

        // Add or remove "selected" class to change card style (optional visual feedback)
        $(this).toggleClass('selected', !isChecked);
    });

    // Function to update cart based on selected items (AJAX request)
    function updateCart() {
        $.ajax({
            type: "POST",
            url: "{{ route('cart.newpro') }}",  // Ensure this is the correct route
            data: {
                temid: selectedIds.join(','),   // Send selected item IDs
                _token: csrfToken               // Include CSRF token for security
            },
            success: function(data) {
                if (data) {
                    $(".carmys").html(data);  // Update the cart contents
                    $("#loading").hide();      // Hide loading spinner if it exists
                }
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }
    
});




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
                console.log('hello');
                $("#loading").show();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.myincrement')}}",
                        success: function (data) {
                             return updatethisCart();
                            
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
                        url: "{{route('cart.mydecrement')}}",
                        success: function (data) {
                            if (data) {
                                
                                updateCart();
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
            <script>
    function checkPhoneLength(input) {
        const maxLength = 11;
        if (input.value.length >= maxLength) {
            input.value = input.value.slice(0, maxLength); // ensure no extra digits
            input.disabled = true;
        }
    }
</script>
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