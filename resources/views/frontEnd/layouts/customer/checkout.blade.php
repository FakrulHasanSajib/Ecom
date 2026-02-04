@extends('frontEnd.layouts.master') @section('title', 'Customer Checkout') @push('css')
    <link rel="stylesheet" href="{{ asset('frontEnd/css/select2.min.css') }}" />
    <style>
        .border-address {
            border: 2px solid #000000 !important;
            /* Orange border */
            border-radius: 5px;
            /* Rounded corners */
            padding: 10px;
            /* Optional padding */
        }

        .form-check {
            display: inline-block;
        }

        .order_place {
            font-size: 18px;
            /* Larger text */
            padding: 15px 30px;
            /* Increased padding */
            border: none;
            /* Remove any border */
            border-radius: 5px;
            /* Rounded corners */
            cursor: pointer;
            /* Change cursor to pointer */
            transition: all 0.3s ease;
            /* Smooth transition for hover */
        }

        .order_place:hover {
            background-color: #0056b3;
            /* Darker blue on hover */
            color: white;
            /* White text on hover */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            /* Add a shadow effect */
        }

        .order_place:focus {
            background-color: #0056b3;
            /* Ensure blue background remains on focus */
            color: white;
            outline: none;
            /* Remove the default focus outline */
            box-shadow: 0px 0px 10px rgba(0, 0, 255, 0.5);
            /* Optional shadow on focus */
        }

        .card {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        .card-body {
            display: flex;
            align-items: center;
        }

        .card input[type="checkbox"] {
            margin-right: 10px;
        }

        .card label {
            font-weight: bold;
        }

        .area-checkbox:checked+label {
            font-weight: bold;
            color: #0066cc;
        }

        /* Example to change the background color of the card when checked */
        .area-checkbox:checked {
            background-color: #cce5ff;
            border-color: #0066cc;
        }
    </style>
@endpush


@section('content')
    <section class="chheckout-section">
        @php
            $subtotal = Cart::instance('shopping')->subtotal();
            $subtotal = str_replace(',', '', $subtotal);
            $subtotal = str_replace('.00', '', $subtotal);
            $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
        @endphp
        <div class="container">
            <div class="row">
                <div class="col-sm-5 cus-order-2">
                    <div class="checkout-shipping">
                     <form action="{{ route('customer.ordersave') }}" method="POST" class="checkout-form" data-parsley-validate="">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h6>আপনার অর্ডারটি কনফার্ম করতে তথ্যগুলো পূরণ করে <span style="color:#fe5200;">"অর্ডার
                                            কনফার্ম করুন"</span> বাটন এ ক্লিক করুন। </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="name">আপনার নাম লিখুন *</label>
                                                <input type="text" id="name"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ old('name') }}" required />
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
                                                <input type="text" minlength="11"  maxlength="11"
                                                    pattern="0[0-9]+"
                                                    
                                                    title="Please enter an 11-digit number." id="phone"
                                                    class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                    value="{{ old('phone') }}" required />
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
                                                <label for="address">ঠিকানা লিখুন * জেলা,উপজেলা,থানা,পৌরসভা</label>
                                                <textarea id="address"
                                                    class="form-control border-address @error('address') is-invalid @enderror"
                                                    name="address" required
                                                    style="border-radius: 5px;">{{ old('address') }}</textarea>
                                                @error('address')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <div class="col-sm-12">
                                            <div class="form-group mb-3">
                                                <label for="area">ডেলিভারি এরিয়া নিবার্চন করুন *</label>
                                                <div class="row">
                                                    @php
                                                        $frist = 0;
                                                    @endphp
                                                    @foreach ($shippingcharge as $key => $value)
                                                        @php
                                                            $frist++;
                                                        @endphp
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <input type="checkbox" id="area_{{ $value->id }}"
                                                                        name="area[]" value="{{ $value->id }}"
                                                                        class="area-checkbox" @if($frist == 1) checked @endif>
                                                                    <label
                                                                        for="area_{{ $value->id }}">{{ $value->name }}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- col-end -->
                                        <div class="col-sm-12">
                                            <div class="radio_payment">
                                                <label id="payment_method">পেমেন্ট মেথড</label>
                                                <div class="payment_option">
                                                    <!-- Optionally add more payment methods here -->
                                                </div>
                                            </div>
                                            <div class="payment-methods">
                                                <div class="form-check p_cash">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                        id="inlineRadio1" value="Cash On Delivery" checked required />
                                                    <label class="form-check-label" for="inlineRadio1">
                                                        Cash On Delivery
                                                    </label>
                                                </div>
                                                @if($bkash_gateway)
                                                    <div class="form-check p_bkash">
                                                        <input class="form-check-input" type="radio" name="payment_method"
                                                            id="inlineRadio2" value="bkash" required />
                                                        <label class="form-check-label" for="inlineRadio2">
                                                            Bkash
                                                        </label>
                                                    </div>
                                                @endif
                                                @if($shurjopay_gateway)
                                                    <div class="form-check p_shurjo">
                                                        <input class="form-check-input" type="radio" name="payment_method"
                                                            id="inlineRadio3" value="shurjopay" required />
                                                        <label class="form-check-label" for="inlineRadio3">
                                                            Shurjopay
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- col-end -->
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button class="btn btn-primary btn-lg order_place" type="submit">অর্ডার
                                                    কনফার্ম করুন</button>
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
                    <div class="cart_details table-responsive-sm">
                        <div class="card">
                            <div class="card-header">
                                <h5>অর্ডারের তথ্য</h5>
                            </div>
                            <div class="card-body cartlist">
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
                                        @foreach (Cart::instance('shopping')->content() as $value)
                                            <tr>
                                                <td>
                                                    <a class="cart_remove" data-id="{{ $value->rowId }}"><i
                                                            class="fas fa-trash text-danger"></i></a>
                                                </td>
                                                <td class="text-left">
                                                    <a href="{{ route('product', $value->options->slug) }}"> <img
                                                            src="{{ asset($value->options->image) }}" />
                                                        {{ Str::limit($value->name, 20) }}</a>
                                                    @if ($value->options->product_size)
                                                        <p>Size: {{ $value->options->product_size }}</p>
                                                    @endif
                                                    @if ($value->options->product_color)
                                                        <p>Color: {{ $value->options->product_color }}</p>
                                                    @endif
                                                </td>
                                                <td class="cart_qty">
                                                    <div class="qty-cart vcart-qty">
                                                        <div class="quantity">
                                                            <button class="minus cart_decrement"
                                                                data-id="{{ $value->rowId }}">-</button>
                                                            <input type="text" value="{{ $value->qty }}" readonly />
                                                            <button class="plus cart_increment"
                                                                data-id="{{ $value->rowId }}">+</button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="alinur">৳ </span><strong>{{ $value->price }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end px-4">মোট</th>
                                            <td class="px-4">
                                                <span id="net_total"><span class="alinur">৳
                                                    </span><strong>{{ $subtotal }}</strong></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end px-4">ডেলিভারি চার্জ</th>
                                            <td class="px-4">
                                                <span id="cart_shipping_cost"><span class="alinur">৳
                                                    </span><strong>{{ $shipping }}</strong></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end px-4">সর্বমোট</th>
                                            <td class="px-4">
                                                <span id="grand_total"><span class="alinur">৳
                                                    </span><strong>{{ $subtotal + $shipping }}</strong></span>
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
    </section>
@endsection 



@push('script')



   <script>
    $(document).ready(function() {
        // ১. পিএইচপি থেকে কার্টের ডাটা জাভাস্ক্রিপ্টে আনা
        @php
            $cart_content = Cart::instance('shopping')->content();
            $content_ids = [];
            $contents = [];
            $total_val = 0;

            foreach($cart_content as $item) {
                $content_ids[] = (string)$item->id;
                $contents[] = [
                    'content_id' => (string)$item->id,
                    'content_type' => 'product',
                    'content_name' => $item->name,
                    'quantity' => $item->qty,
                    'price' => $item->price
                ];
                $total_val += ($item->price * $item->qty);
            }
            
            // শিপিং চার্জ যোগ করা (যদি থাকে)
            $shipping_cost = Session::get('shipping') ? Session::get('shipping') : 0;
            $grand_total = $total_val + $shipping_cost;
        @endphp

        // ২. InitiateCheckout ইভেন্ট ফায়ার করা (Server + Browser)
        // ✅ ফিক্স: শুরুতে setTimeout যোগ করা হয়েছে
        setTimeout(function() {
            if (typeof trackServerSide === 'function') {
                console.log('Firing InitiateCheckout...');
                
                trackServerSide('InitiateCheckout', {
                    content_ids: {!! json_encode($content_ids) !!},
                    contents: {!! json_encode($contents) !!},
                    content_type: 'product',
                    value: {{ $grand_total }},
                    currency: 'BDT',
                    num_items: {{ Cart::instance('shopping')->count() }}
                });
            } else {
                console.warn('trackServerSide function not found! Check master.blade.php');
            }
        }, 1000); // ১ সেকেন্ড ডিলে, যাতে মেইন ফাংশন লোড হতে পারে
    });
</script>
{{-- <script>
    // 🔥 SCORE BOOSTER: ফোন নাম্বার টাইপ করার পর ইভেন্ট পাঠানো
    $('#phone').on('blur', function() {
        var phoneNumber = $(this).val();
        
        // নাম্বার ১১ ডিজিটের বেশি হলে (ভ্যালিড নাম্বার) ডাটা পাঠাবে
        if(phoneNumber.length >= 11) {
            
            // ১. কার্টের ডাটা জাভাস্ক্রিপ্ট ভেরিয়েবলে নেওয়া
            var content_ids = [];
            var contents = [];
            @foreach(Cart::instance('shopping')->content() as $item)
                content_ids.push('{{ $item->id }}');
                contents.push({
                    content_id: '{{ $item->id }}',
                    content_type: 'product',
                    content_name: '{{ $item->name }}',
                    quantity: {{ $item->qty }},
                    price: {{ $item->price }}
                });
            @endforeach

            // ২. ইভেন্ট ফায়ার করা (এখন ফোন নাম্বার সহ যাবে)
            if (typeof trackServerSide === 'function') {
                trackServerSide('InitiateCheckout', {
                    content_ids: content_ids,
                    contents: contents,
                    content_type: 'product',
                    value: {{ $subtotal + ($shipping ?? 0) }}, // গ্র্যান্ড টোটাল
                    currency: 'BDT',
                    num_items: {{ Cart::instance('shopping')->count() }}
                });
                console.log('InitiateCheckout Sent with Phone Number for Better Score!');
            }
        }
    });
</script> --}}
    
    
    
    {{-- <script>
    if (typeof ttq !== 'undefined') {
        ttq.track('InitiateCheckout', {
            content_type: 'product',
            quantity: {{ Cart::instance('shopping')->count() }}, 
            value: {{ $subtotal + ($shipping ?? 0) }}, 
            currency: 'BDT',
            // ডাটাগুলোকে সঠিকভাবে ম্যাপ করা হয়েছে যেন এরর না আসে
            contents: [
                @foreach(Cart::instance('shopping')->content() as $item)
                {
                    content_id: '{{ $item->id }}',
                    content_type: 'product',
                    content_name: '{{ $item->name }}',
                    quantity: {{ $item->qty }},
                    price: {{ $item->price }}
                },
                @endforeach
            ]
        });
        console.log('TikTok InitiateCheckout Fired Successfully');
    }
</script> --}}

    <script src="{{ asset('frontEnd/') }}/js/parsley.min.js"></script>
    <script src="{{ asset('frontEnd/') }}/js/form-validation.init.js"></script>
    <script src="{{ asset('frontEnd/') }}/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
        });
    </script>
    <script>
        $(".area-checkbox").on("change", function () {
            var selectedArea = null;

            // If this checkbox is checked, we set selectedArea to its value
            if ($(this).prop("checked")) {
                selectedArea = $(this).val();

                // Uncheck all other checkboxes
                $(".area-checkbox").not(this).prop("checked", false);
            }

            // If an area is selected, make the AJAX request
            if (selectedArea) {
                let productIds = [];
                @foreach (Cart::instance('shopping')->content() as $value)
                    productIds.push("{{ $value->options->slug }}");
                @endforeach
                $.ajax({
                    type: "GET",
                    data: {
                        id: selectedArea,
                        proid: productIds
                    },
                    url: "{{ route('shipping.charge') }}",
                    dataType: "html",
                    success: function (response) {
                        $(".cartlist").html(response);
                    },
                });
            }
        });


    </script>
    <script type="text/javascript">
        dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
        dataLayer.push({
            event: "view_cart",
            ecommerce: {
                items: [@foreach (Cart::instance('shopping')->content() as $cartInfo){
                    item_name: "{{$cartInfo->name}}",
                    item_id: "{{$cartInfo->id}}",
                    price: "{{$cartInfo->price}}",
                    item_brand: "{{$cartInfo->options->brand}}",
                    item_category: "{{$cartInfo->options->category}}",
                    item_size: "{{$cartInfo->options->size}}",
                    item_color: "{{$cartInfo->options->color}}",
                    currency: "BDT",
                    quantity: {{$cartInfo->qty ?? 0}}
                }, @endforeach]
            }
        });
    </script>
    <script type="text/javascript">
        // Clear the previous ecommerce object.
        dataLayer.push({ ecommerce: null });

        // Push the begin_checkout event to dataLayer.
        dataLayer.push({
            event: "begin_checkout",
            ecommerce: {
                items: [@foreach (Cart::instance('shopping')->content() as $cartInfo)
                    {
                        item_name: "{{$cartInfo->name}}",
                        item_id: "{{$cartInfo->id}}",
                        price: "{{$cartInfo->price}}",
                        item_brand: "{{$cartInfo->options->brands}}",
                        item_category: "{{$cartInfo->options->category}}",
                        item_size: "{{$cartInfo->options->size}}",
                        item_color: "{{$cartInfo->options->color}}",
                        currency: "BDT",
                        quantity: {{$cartInfo->qty ?? 0}}
                    },
                @endforeach]
            }
        });
    </script>

    <script>
    $(document).ready(function() {
        // ফর্ম সাবমিট কন্ট্রোল (Race Condition Fix + Validation Check)
        $('.checkout-form').on('submit', function(e) {
            var form = $(this);
            var btn = form.find('button[type="submit"]');

            // ১. Parsley দিয়ে চেক করা ফর্ম ভ্যালিড কি না
            if (form.parsley().isValid()) {
                
                // যদি বাটন আগেই ক্লিক করা থাকে, থামাও
                if (btn.prop('disabled')) {
                    e.preventDefault();
                    return;
                }

                // ২. ফর্মের স্বাভাবিক সাবমিট থামানো
                e.preventDefault();

                // ৩. বাটন ডিজেবল করা এবং প্রসেসিং দেখানো
                btn.prop('disabled', true);
                btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                // ৪. ১ সেকেন্ড অপেক্ষা করে সাবমিট করা (যাতে Race Condition না হয়)
                setTimeout(function() {
                    form[0].submit(); // ফর্ম সাবমিট
                }, 1000); // ১০০০ মি.সে. = ১ সেকেন্ড

            } else {
                // ফর্ম ভ্যালিড না হলে বাটন ডিজেবল হবে না, ইউজার ঠিক করতে পারবে
                // Parsley অটোমেটিক এরর মেসেজ দেখাবে
            }
        });
    });
</script>
@endpush