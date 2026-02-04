@extends('frontEnd.layouts.master') @section('title','Shopping Cart') @section('content')
<section class="breadcrumb-section">
 <div class="container">
  <div class="row">
   <div class="col-sm-12">
    <div class="custom-breadcrumb">
     <ul>
      <li><a href="{{route('home')}}">Home </a></li>
      <li>
       <a><i class="fa-solid fa-angles-right"></i> </a>
      </li>
      <li><a href="">Shopping Cart</a></li>
     </ul>
    </div>
   </div>
  </div>
 </div>
</section>
<section class="vcart-section">
 @php 
// final_total ক্যালকুলেশন যোগ করা হয়েছে
$subtotal = Cart::instance('shopping')->subtotal(); 
$subtotal=str_replace(',','',$subtotal); 
$subtotal=str_replace('.00', '',$subtotal); 
view()->share('subtotal',$subtotal); 
$shipping = Session::get('shipping')?Session::get('shipping'):0; 
$discount = Session::get('discount')?Session::get('discount'):0; 
$final_total = ($subtotal+$shipping) - $discount; // **নতুন ভ্যারিয়েবল**
@endphp
 <div class="container">
  <div class="row" id="cartlist">
   <div class="col-sm-9">
    <div class="vcart-inner">
     <div class="cart-title">
      <h4>Shopping Cart</h4>
     </div>
     <div class="vcart-content">
      <div class="table-responsive">
       <table class="table">
        <thead>
         <tr>
          <th>Image</th>
          <th>Product</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Total</th>
          <th>Remove</th>
         </tr>
        </thead>
        <tbody>
         @foreach($data as $value)
         <tr>
          <td><img height="30" src="{{asset($value->options->image)}}" alt="" /></td>
          <td class="cart_name">{{$value->name}}</td>
          <td>{{$value->price}} ৳</td>
          <td>
           <div class="qty-cart vcart-qty">
            <div class="quantity">
             <button class="minus cart_decrement" data-id="{{$value->rowId}}">-</button>
             <input type="text" value="{{$value->qty}}" readonly />
             <button class="plus cart_increment" data-id="{{$value->rowId}}">+</button>
            </div>
           </div>
          </td>
          <td>{{$value->price*$value->qty}} ৳</td>
          <td>
           <button class="remove-cart cart_remove" data-id="{{$value->rowId}}"><i data-feather="x"></i></button>
          </td>
         </tr>
         @endforeach
        </tbody>
       </table>
      </div>
     </div>
    </div>
    <div class="coupon-form">
     <form action="">
      <input type="text" placeholder="apply coupon" />
      <button>Apply</button>
     </form>
    </div>
   </div>
   <div class="col-sm-3">
  
    <div class="cart-summary" data-fb-no-track>
     <h5>Cart Summary</h5>
     <table class="table">
      <tbody>
       <tr>
        <td>Items</td>
        <td>{{Cart::instance('shopping')->count()}} (qty)</td>
       </tr>
       <tr>
        <td>Total</td>
        <td>৳{{$subtotal}}</td>
       </tr>
       <tr>
        <td>Shipping</td>
        <td>৳{{$shipping}}</td>
       </tr>
       <tr>
        <td>Discount</td>
        <td>৳{{$discount}}</td>
       </tr>
       <tr>
        <td>Total</td>
        <td>৳{{$final_total}}</td> 
       </tr>
      </tbody>
     </table>
     
     <a href="{{route('customer.checkout')}}" 
       class="go_cart"
       onclick="fbq('track', 'InitiateCheckout', {value: {{ $final_total }}, currency: 'BDT', num_items: {{Cart::instance('shopping')->count()}}});"
     >PROCESS TO CHECKOUT</a>
    </div>
   </div>
  </div>
 </div>
</section>
@endsection

@push('script')
@php
    // Cart::content() থেকে সব আইটেম সংগ্রহ করা
    $cart_contents = Cart::instance('shopping')->content();
    
    // লুপের জন্য ডেটা অ্যারে তৈরি
    $content_ids = []; 
    $contents_data = []; 
    // AddToCart-এর জন্য ফাইনাল টোটাল ভ্যালু ব্যবহার করা হয়েছে
    $total_value_for_pixel = $final_total; 

    // লুপের মাধ্যমে সব পণ্যের আইডি ও বিবরণ সংগ্রহ করা
    foreach($cart_contents as $item) {
        $content_ids[] = (string)$item->id; // প্রতিটি পণ্যের ID
        $contents_data[] = [
            'id' => (string)$item->id,
            'quantity' => $item->qty,
            'item_price' => $item->price
        ];
    }
    $content_ids_json = json_encode($content_ids);
    $contents_json = json_encode($contents_data);
@endphp

<script>
    // AddToCart ইভেন্ট (Page Load)
    fbq('track', 'AddToCart', {
        value: {{ $total_value_for_pixel }}, // এখন ফাইনাল টোটাল
        currency: 'BDT',
        content_ids: {!! $content_ids_json !!},
        contents: {!! $contents_json !!},
        content_type: 'product'
    });
</script>

<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'event': 'view_cart',
        'ecommerce': {
            'currency': 'BDT',
            'value': '{{ $final_total }}',
            'items': [
                @foreach(Cart::instance('shopping')->content() as $item)
                {
                    'item_id': '{{ $item->id }}',
                    'item_name': '{{ $item->name }}',
                    'price': '{{ $item->price }}',
                    'quantity': {{ $item->qty }}
                },
                @endforeach
            ]
        }
    });
</script>
@endpush