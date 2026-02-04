@extends('frontEnd.layouts.master')
@section('title', $author_page->title ?? 'Author Products')
@push('css')
<link rel="stylesheet" href="{{asset('public/frontEnd/css/jquery-ui.css')}}" />
<style>
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

.time {
    font-weight: bold;
}

#timer .time {
    margin: 0 5px;
}

#timer div {
    font-size: 1.5em;
}

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
</style>
@endpush

@section('content')
<section class="product-section">
    <div class="container">
        <!-- Promotional Banner -->
        <div class="row align-items-center">
            <div class="col-md-8">
                @if($author_page->banner)
                <a href="" class="banner-link">
                    <img src="{{asset($author_page->banner)}}" alt="Banner Image" class="banner-image full-width-image">
                </a>
                @endif
            </div>
            <div class="col-md-4 text-right">
                <div>
                    <h1>{{$author_page->title ?? 'Author Products'}}</h1>
                    @if($author)
                    <p>By: {{$author->name}}</p>
                    @endif
                </div>
                @if($author_page->end_date)
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
                @endif
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
                                            <p>
                                                @php 
                                                    $discount = (((($value->old_price) - ($value->new_price)) * 100) / ($value->old_price));
                                                @endphp 
                                                {{number_format($discount, 0)}}%
                                            </p>
                                            ছাড়
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="pro_img">
                                <a href="{{ route('product', $value->slug) }}">
                                    <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{$value->name}}" />
                                </a>
                            </div>
                            
                            <div class="pro_des">
                                <div class="pro_name">
                                    <a href="{{ route('product', $value->slug) }}">{{Str::limit($value->name, 80)}}</a>
                                </div>
                                <div class="pro_price">
                                    <p>
                                        @if($value->old_price)
                                        <del>৳ {{ $value->old_price}}</del>
                                        @endif
                                        ৳ {{ $value->new_price}}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if(! $value->prosizes->isEmpty() || ! $value->procolors->isEmpty())
                        <div class="pro_btn">
                            <div class="cart_btn order_button">
                                <a href="{{ route('product', $value->slug) }}" class="addcartbutton">অর্ডার</a>
                            </div>
                        </div>
                        @else
                        <div class="pro_btn">
                            <form action="{{route('cart.store')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$value->id}}" />
                                <input type="hidden" name="qty" value="1" />
                                <button type="submit">অর্ডার</button>
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
    });
</script>

@if($author_page->end_date)
<script>
$(document).ready(function() {
    function updateCountdown() {
        let now = new Date();
        let targetTime = new Date("{{$author_page->end_date}}");

        let timeLeft = targetTime.getTime() - now.getTime();

        if (timeLeft <= 0) {
            $("#hours").text("00");
            $("#minutes").text("00");
            $("#seconds").text("00");
            return;
        }

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
@endif
@endpush