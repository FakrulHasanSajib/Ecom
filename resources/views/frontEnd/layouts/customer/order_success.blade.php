@extends('frontEnd.layouts.master')
@section('title','Order Success')
@section('content')
<style>
    :root {
        --royal-blue: #00204a;
        --royal-gold: #d4af37;
        --soft-white: #f9fafb;
    }
    .success-wrapper {
        background: #f3f4f6;
        min-height: 100vh;
        padding: 60px 0;
        font-family: 'Hind Siliguri', sans-serif;
    }
    .royal-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(0,32,74, 0.1);
        overflow: hidden;
        border: 2px solid rgba(212, 175, 55, 0.2);
        position: relative;
    }
    .royal-header {
        background: var(--royal-blue);
        color: #fff;
        text-align: center;
        padding: 40px 20px;
        position: relative;
        overflow: hidden;
    }
    .royal-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.2) 0%, transparent 70%);
    }
    .success-icon-box {
        width: 90px;
        height: 90px;
        background: var(--royal-gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 0 0 10px rgba(212, 175, 55, 0.2);
        animation: pulse 2s infinite;
    }
    .success-icon-box i {
        font-size: 40px;
        color: var(--royal-blue);
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 20px rgba(212, 175, 55, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
    }
    .order-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--royal-gold);
    }
    
    /* Improved Note Box */
    .success-note-box {
        background: rgba(255, 255, 255, 0.1);
        border: 1px dashed rgba(212, 175, 55, 0.4);
        padding: 15px 25px;
        border-radius: 10px;
        margin-top: 20px;
        display: inline-block;
    }
    .order-subtitle {
        color: #fff;
        font-size: 18px;
        font-weight: 500;
        margin: 0;
        line-height: 1.6;
    }
    
    /* Timeline */
    .steps {
        display: flex;
        justify-content: space-between;
        margin: 30px 0;
        position: relative;
    }
    .steps::before {
        content: "";
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 0;
    }
    .step-item {
        position: relative;
        z-index: 1;
        text-align: center;
        width: 33.33%;
    }
    .step-circle {
        width: 35px;
        height: 35px;
        background: #fff;
        border: 2px solid #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: #adb5bd;
        font-weight: 700;
    }
    .step-item.active .step-circle {
        border-color: var(--royal-gold);
        background: var(--royal-gold);
        color: var(--royal-blue);
    }
    .step-text {
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
    }
    .step-item.active .step-text {
        color: var(--royal-blue);
    }

    /* Details Grid */
    .details-box {
        background: #fff;
        border-radius: 15px;
        border: 1px solid #eee;
        padding: 25px;
        margin-bottom: 25px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #eee;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .label {
        color: #6c757d;
        font-weight: 500;
    }
    .value {
        color: var(--royal-blue);
        font-weight: 700;
    }
    
    .btn-continue {
        background: var(--royal-blue);
        color: var(--royal-gold);
        border: 2px solid var(--royal-blue);
        padding: 12px 40px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-continue:hover {
        background: var(--royal-gold);
        color: var(--royal-blue);
        border-color: var(--royal-gold);
    }

    /* Whatsapp Button Styles */
    .whatsapp-section {
        margin-top: 30px;
        text-align: center;
    }
    .whatsapp-btn-container {
        display: inline-flex;
        align-items: center;
        background: #fff;
        padding: 10px 20px;
        border-radius: 50px;
        border: 2px solid #25D366;
        box-shadow: 0 5px 15px rgba(37, 211, 102, 0.2);
        text-decoration: none;
        transition: all 0.3s;
    }
    .whatsapp-btn-container:hover {
        background: #f0fff4;
        transform: translateY(-2px);
    }
    .whatsapp-icon-circle {
        width: 45px;
        height: 45px;
        background: #25D366;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        font-size: 24px;
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(37, 211, 102, 0); }
        100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
    }
    .whatsapp-text {
        text-align: left;
    }
    .whatsapp-number {
        display: block;
        font-weight: 700;
        color: #212529;
        font-size: 18px;
    }
    .whatsapp-label {
        font-size: 13px;
        color: #25D366;
        font-weight: 600;
        text-transform: uppercase;
    }

    @media (max-width: 576px) {
        .royal-header { padding: 30px 15px; }
        .order-title { font-size: 22px; }
        .success-icon-box { width: 70px; height: 70px; }
        .success-icon-box i { font-size: 30px; }
    }
</style>

<div class="success-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="royal-card animate__animated animate__fadeInUp">
                    
                    <!-- Header -->
                    <div class="royal-header">
                        <div class="success-icon-box">
                            <i class="fas fa-check"></i>
                        </div>
                        <h1 class="order-title">আলহামদুলিল্লাহ! আপনার অর্ডার কনফার্ম হয়েছে</h1>
                        
                        <!-- Beautified Note -->
                        <div class="success-note-box">
                            <p class="order-subtitle">
                                <i class="fas fa-headset me-2"></i> 
                                আমাদের একজন প্রতিনিধি শীঘ্রই আপনার নাম্বারে কল করে অর্ডারটি নিশ্চিত করবেন।
                            </p>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        
                        <!-- Timeline -->
                        <div class="steps">
                            <div class="step-item active">
                                <div class="step-circle"><i class="fas fa-check"></i></div>
                                <div class="step-text">অর্ডার প্লেসড</div>
                            </div>
                            <div class="step-item">
                                <div class="step-circle">2</div>
                                <div class="step-text">প্রসেসিং</div>
                            </div>
                            <div class="step-item">
                                <div class="step-circle">3</div>
                                <div class="step-text">ডেলিভারি</div>
                            </div>
                        </div>

                        <!-- Order Info Box -->
                        <div class="details-box">
                            <div class="detail-row">
                                <span class="label">Invoice No:</span>
                                <span class="value">#{{$order->invoice_id}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Date:</span>
                                <span class="value">{{$order->created_at->format('d M, Y')}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Mobile:</span>
                                <span class="value">{{$order->shipping?$order->shipping->phone:''}}</span>
                            </div>
                            @php 
                                $payment = App\Models\Payment::where('order_id',$order->id)->first();
                            @endphp
                            <div class="detail-row">
                                <span class="label">Method:</span>
                                <span class="value">{{$payment->payment_method}}</span>
                            </div>
                        </div>

                        <!-- Product List -->
                        <div class="mb-4">
                            <h5 style="color: var(--royal-blue); font-weight: 700; margin-bottom: 20px; border-left: 4px solid var(--royal-gold); padding-left: 10px;">Item Summary</h5>
                            <div class="table-responsive">
                                <table class="table table-hover" style="vertical-align: middle;">
                                    <thead style="background: #f8f9fa;">
                                        <tr>
                                            <th style="border:none; color: #666;">Product</th>
                                            <th style="border:none; color: #666; text-align:right;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderdetails as $key=>$value)
                                        <tr>
                                            <td style="border-bottom: 1px solid #f0f0f0;">
                                                <div style="font-weight: 600; color: #333;">{{$value->product_name}}</div>
                                                <small class="text-muted">Qty: {{$value->qty}}</small>
                                            </td>
                                            <td style="text-align:right; font-weight: 700; color: #333; border-bottom: 1px solid #f0f0f0;">
                                                ৳{{$value->sale_price * $value->qty}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot style="border-top: 2px solid var(--royal-gold);">
                                        <tr>
                                            <td colspan="2" style="padding: 0;">
                                                <div class="d-flex justify-content-between p-2">
                                                    <span>Subtotal</span>
                                                    <span>৳{{$order->amount - $order->shipping_charge}}</span>
                                                </div>
                                                <div class="d-flex justify-content-between p-2">
                                                    <span>Shipping</span>
                                                    <span>৳{{$order->shipping_charge}}</span>
                                                </div>
                                                <div class="d-flex justify-content-between p-3" style="background: rgba(212, 175, 55, 0.1); border-radius: 5px; color: var(--royal-blue); font-weight: 800; font-size: 18px;">
                                                    <span>Total Amount</span>
                                                    <span>৳{{$order->amount}}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Dynamic WhatsApp Contact Section -->
                        @php
                            $contactinfo = \App\Models\Contact::where('id', 1)->first();
                            $ismobile = is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile'));
                            $whatsapp_url = $ismobile 
                                ? "https://wa.me/{$contactinfo->whatsapp}?text=Hello! I have a question about my order #{$order->invoice_id}"
                                : "https://web.whatsapp.com/send?phone={$contactinfo->whatsapp}&text=Hello! I have a question about my order #{$order->invoice_id}";
                        @endphp
                        
                        <div class="whatsapp-section">
                            <p class="mb-2 text-muted">যেকোনো প্রয়োজনে আমাদের সাথে যোগাযোগ করুন</p>
                            <a href="{{ $whatsapp_url }}" target="_blank" class="whatsapp-btn-container">
                                <div class="whatsapp-icon-circle">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div class="whatsapp-text">
                                    <span class="whatsapp-label">WhatsApp Support</span>
                                    <span class="whatsapp-number">{{ $contactinfo->whatsapp }}</span>
                                </div>
                            </a>
                        </div>

                        <!-- Shipping Address -->
                        <div class="text-center mt-4 mb-4 p-3 rounded" style="background: #f8f9fa; border: 1px dashed #ccc;">
                            <h6 style="color: #666; margin-bottom: 5px;">Delivery Address</h6>
                            <p style="margin:0; font-weight: 600; color: #333;">
                                {{$order->shipping?$order->shipping->address:''}}, {{$order->shipping?$order->shipping->area:''}}
                            </p>
                        </div>

                        <div class="text-center">
                            <a href="{{route('home')}}" class="btn-continue">
                                <i class="fas fa-home me-2"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
@php
    // ১. অর্ডার ডিটেইলস ডেটাবেস থেকে আনা হচ্ছে
    $order_details = App\Models\OrderDetails::where('order_id', $order->id)->get();
    
    // ২. লুপের জন্য ডেটা অ্যারে তৈরি
    $content_ids = []; 
    $contents_data = []; 

    foreach($order_details as $item) { 
        $content_ids[] = (string)$item->product_id; 
        
        $contents_data[] = [
            'id' => (string)$item->product_id,
            'quantity' => $item->qty,
            'item_price' => $item->sale_price // আপনার কোড থেকে sale_price ব্যবহৃত হলো
        ];
    }
    $content_ids_json = json_encode($content_ids);
    $contents_json = json_encode($contents_data);
@endphp

@if(isset($order))
    @php
        // ২. অর্ডারের মোট মূল্য ও ID সংগ্রহ
        $total_amount = $order->amount ?? 0;
        $invoice_id = $order->invoice_id ?? '';
        
        // ৩. অর্ডার ডিটেইলস সংগ্রহ (লুপের জন্য)
        // OrderDetails মডেল থেকে ডেটা আনা
        $order_details = App\Models\OrderDetails::where('order_id', $order->id)->get();
        
        $content_ids = []; 
        $contents_data = []; 

        // ৪. লুপ চালিয়ে JSON ডেটা তৈরি করুন
        foreach($order_details as $item) { 
            $content_ids[] = (string)($item->product_id ?? ''); 
            
            $contents_data[] = [
                'id' => (string)($item->product_id ?? ''),
                'quantity' => $item->qty ?? 0,
                'item_price' => $item->sale_price ?? 0
            ];
        }
        // ৫. JSON স্ট্রিং-এ রূপান্তর
        $content_ids_json = json_encode($content_ids);
        $contents_json = json_encode($contents_data);
    @endphp





@php
    // ১. অর্ডার ডিটেইলস এবং সেটিংস আনা
    $order_details = App\Models\OrderDetails::where('order_id', $order->id)->get();
    $gs = App\Models\GeneralSetting::first(); // সেটিংস আনা হলো
    
    $content_ids = []; 
    $contents_data = []; 

    foreach($order_details as $item) { 
        $content_ids[] = (string)$item->product_id; 
        $contents_data[] = [
            'id' => (string)$item->product_id,
            'quantity' => $item->qty,
            'item_price' => $item->sale_price 
        ];
    }
@endphp

{{-- শর্ত: শুধুমাত্র যদি সেটিংসে 'customer' মোড সিলেক্ট করা থাকে, তবেই ব্রাউজার পিক্সেল ফায়ার হবে --}}
@if(isset($order) && $gs->pixel_trigger_type == 'customer')

    <script>
        // ১. Facebook Pixel Purchase Event (Browser Side)
        if(typeof fbq !== 'undefined') {
            fbq('track', 'Purchase', {
                value: {{ number_format($order->amount, 2, '.', '') }},
                currency: 'BDT',
                content_ids: {!! json_encode($content_ids) !!},
                content_type: 'product'
            }, { eventID: '{{ (string)$order->id }}' }); // Deduplication এর জন্য Order ID
        }

        // ২. TikTok Pixel CompletePayment Event (Browser Side)
        if (typeof ttq !== 'undefined') {
            ttq.track('CompletePayment', {
                content_type: 'product',
                quantity: {{ (int)$order->orderdetails->sum('qty') }}, 
                value: {{ number_format($order->amount, 2, '.', '') }}, 
                currency: 'BDT',
                content_id: '{{ $order->invoice_id }}',
                contents: [
                    @foreach($order->orderdetails as $item)
                    {
                        content_id: '{{ $item->product_id }}',
                        content_name: '{{ addslashes($item->product_name) }}',
                        quantity: {{ (int)$item->qty }},
                        price: {{ number_format($item->sale_price, 2, '.', '') }}
                    }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ]
            });
        }
    </script>
@endif






<script>
    // ১. আগের ডাটা ক্লিয়ার করা (Standard Practice)
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ ecommerce: null }); 

    // ২. পারচেজ ডাটা পুশ করা
    window.dataLayer.push({
       // 'event': 'purchase', // এই নামটিই GTM খুঁজবে
        'ecommerce': {
            'transaction_id': '{{ $invoice_id }}',
            'value': {{ number_format($total_amount, 2, '.', '') }},
            'currency': 'BDT',
            'items': [
                @foreach($order->orderDetails as $index => $item)
                {
                    'item_id': '{{ $item->product_id }}',
                    'item_name': '{{ addslashes($item->product_name) }}',
                    'price': {{ number_format($item->sale_price, 2, '.', '') }},
                    'quantity': {{ $item->qty }}
                }{{ $index < count($order->orderDetails) - 1 ? ',' : '' }}
                @endforeach
            ]
        }
    });
</script>




<script>
    if (typeof ttq !== 'undefined') {
        ttq.track('CompletePayment', {
            content_type: 'product',
            quantity: {{ (int)$order->orderDetails->sum('qty') }}, 
            value: {{ number_format($order->amount, 2, '.', '') }}, 
            currency: 'BDT',
            content_id: '{{ $order->invoice_id }}',
            contents: [
                @foreach($order->orderDetails as $item)
                {
                    content_id: '{{ $item->product_id }}',
                    content_name: '{{ addslashes($item->product_name) }}',
                    quantity: {{ (int)$item->qty }},
                    price: {{ number_format($item->product_price, 2, '.', '') }}
                }{{ !$loop->last ? ',' : '' }}
                @endforeach
            ]
        });
        console.log('TikTok CompletePayment Fired');
    }
</script>
@endif
@endpush