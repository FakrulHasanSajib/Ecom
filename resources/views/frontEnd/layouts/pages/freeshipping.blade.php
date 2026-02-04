@extends('frontEnd.layouts.master')
@section('title','Free Shipping')
@push('css')
<link rel="stylesheet" href="{{asset('public/frontEnd/css/jquery-ui.css')}}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

<style>
    /* --- TIMER STYLES START --- */
    .full-width-image {width: 100%; height: auto;}
    .offer_timer {
        font-size: 1.5em;
        color: #000;
        text-align: right;
    }

    #timer {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .time { font-weight: bold; }
    #timer .time { margin: 0 5px; }
    #timer div { font-size: 1.5em; }

    .aiz-count-down-circle {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.5em;
    }

    .countdown-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .countdown-item {
        text-align: center;
        padding: 10px;
        background: #f5f5f5;
        border-radius: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        width: 60px;
    }

    .countdown-item span {
        display: block;
        font-size: 0.8em;
        color: #333;
        margin-top: 5px;
    }

    .countdown-item div {
        font-size: 2em;
        font-weight: bold;
        color: #000;
    }
    /* --- TIMER STYLES END --- */


    /* --- FINAL BUTTON FIX START --- */
    .pro_btn {
        width: 100% !important;
        display: block !important;
        padding: 5px 10px 15px 10px !important; 
        box-sizing: border-box !important; 
        margin: 0 !important;
        text-align: center !important; 
    }

    .cart_btn, .order_button {
        width: 100% !important;
        display: block !important;
        margin: 0 !important;
        float: none !important; 
        background: transparent !important;
    }

    .pro_btn form {
        width: 100% !important;
        display: block !important;
        margin: 0 !important;
    }

    .custom_order_btn {
        width: 100% !important; 
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        background-color: #e91e63 !important; 
        background-image: none !important; 
        color: #ffffff !important;
        border: none !important;
        padding: 10px 0 !important;
        border-radius: 5px !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        cursor: pointer !important;
        gap: 8px;
        margin-top: 5px !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.3s ease !important; 
    }

    .custom_order_btn:hover {
        background-color: #9c0f3e !important; 
        background-image: none !important; 
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.4) !important;
        transform: translateY(-3px); 
    }
    
    .custom_order_btn i {
        margin-right: 5px;
    }
    /* --- FINAL BUTTON FIX END --- */

</style>
@endpush

@section('content')
<section class="product-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <a href="" class="banner-link">
                    <img src="{{asset($banaer->image)}}" alt="Banner Image" class="banner-image full-width-image">
                </a>
            </div>
            <div class="col-md-4 text-right">
                <div>
                    <h1>{{$banaer->title ?? 'Free Shipping'}}</h1>
                </div>
                <div class="aiz-count-down-circle">
                    <div class="countdown-container">
                        <div class="countdown-item">
                            <div id="hours"></div>
                        </div>
                        <div class="countdown-item">
                            <div id="minutes"></div>
                        </div>
                        <div class="countdown-item">
                            <div id="seconds"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-sm-12">
                <div class="category-product main_product_inner">
                    @foreach ($products as $key => $value)
                    
                    <div class="product_item wist_item wow fadeInDown" data-wow-duration="1.5s" data-wow-delay="0.{{$key}}s">
                        <div class="product_item_inner">
                            @if($value->old_price)
                            <div class="sale-badge">
                                <div class="sale-badge-inner">
                                    <div class="sale-badge-box">
                                        <span class="sale-badge-text">
                                            <p> @php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{number_format($discount,0)}}%</p>
                                            ছাড়
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="pro_img">
                                <a href="{{ route('product',$value->slug) }}">
                                    <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{$value->name}}" />
                                </a>
                            </div>
                            <div class="pro_des">
                                <div class="pro_name">
                                    <a href="{{ route('product',$value->slug) }}">{{Str::limit($value->name,80)}}</a>
                                </div>
                                <div class="pro_price">
                                    <p>
                                        <del>৳ {{ $value->old_price}}</del>
                                        ৳ {{ $value->new_price}}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if(! $value->prosizes->isEmpty() || ! $value->procolors->isEmpty())
                        <div class="pro_btn">
                            <div class="cart_btn order_button">
                                <a href="{{ route('product',$value->slug) }}" class="custom_order_btn">
                                    <i class="fa-solid fa-cart-shopping"></i> অর্ডার করুন
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="pro_btn">
                            <form action="{{route('cart.store')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$value->id}}" />
                                <input type="hidden" name="qty" value="1" />
                                <button type="submit" class="custom_order_btn">
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
@endsection

@push('script')
<script>
    $(".sort").change(function(){
        $('#loading').show();
        $(".sort-form").submit();
    })
</script>
<script>
    $(document).ready(function() {
        function updateCountdown() {
            let now = new Date();
            let targetTime = new Date(now);
            targetTime.setHours(23, 59, 0, 0);

            if (now.getTime() > targetTime.getTime()) {
                targetTime.setDate(targetTime.getDate() + 1); 
            }

            let timeLeft = targetTime.getTime() - now.getTime();

            let hours = Math.floor(timeLeft / (1000 * 60 * 60));
            let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            $("#hours").text(hours.toString().padStart(2, '0'));
            $("#minutes").text(minutes.toString().padStart(2, '0'));
            $("#seconds").text(seconds.toString().padStart(2, '0'));
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
</script>
@endpush