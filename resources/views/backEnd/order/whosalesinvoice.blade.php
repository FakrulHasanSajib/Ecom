@extends('backEnd.layouts.master')
@section('title','Order Invoice')
@section('css')
<link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/invoice.css">
<style>
  .status-button.pending {
    background-color: {{$order->status?$order->status->colorcode:''}};
}
</style>
@endsection
@section('content')

@php
    // ১. সাবটোটাল হিসাব (Sale Price * Quantity)
    $subtotal = $order->orderdetails->sum(function($item) {
        return $item->sale_price * $item->qty;
    });

    // ২. অন্যান্য চার্জ ভেরিয়েবলে নেওয়া
    $shipping = $order->shipping_charge;
    $discount = $order->discount;

    // ৩. গ্র্যান্ড টোটাল হিসাব (Subtotal + Shipping - Discount)
    $grand_total = ($subtotal + $shipping) - $discount;

    // ৪. পেমেন্ট বা এডভান্স চেক (যদি পেমেন্ট ডাটা না থাকে তবে ০ ধরবে)
    $advance = $order->payment ? $order->payment->advance : 0;

    // ৫. ডিউ বা বকেয়া হিসাব
    $due_amount = $grand_total - $advance;
@endphp

<div class="headprint">
    <div class="no-print">
    </div>
</div>

<div class="invoice-body">
  <div class="invoice-box">
    <div class="invoice-header">
      <div class="invoice-logo">
        <img src="{{asset($generalsetting->white_logo)}}" alt="Logo" />
      </div>
      <div class="contact-info">
        <div class="contact-item">
          <div>
            <i class="fas fa-envelope"></i>
          </div>
          <span>{{$contact->email}}</span>
        </div>
        <div class="contact-item">
          <i class="fas fa-phone"></i>
          <span>{{$contact->phone}}</span>
        </div>
        <div class="contact-item">
          <i class="fas fa-map-marker-alt"></i>
          <span>{{$contact->address}}</span>
        </div>
      </div>
    </div>

    <div class="invoice-to">
      <div class="invoice-to-left">
        <div class="invoice-to-left-row">
          <h5>Invoice To :</h5>
          <span>{{$order->shipping?$order->shipping->name:''}}</span>
        </div>
        <span class="invoice-to-left-address">
        {{$order->shipping?$order->shipping->address:''}}<br>
        {{$order->shipping?$order->shipping->area:''}}<br>
        {{$order->shipping?$order->shipping->phone:''}}
        </span>
      </div>
      <div class="invoice-to-right">
          <div class="invoice-container">
            <div class="invoice-no">
              <span class="label">Invoice No</span>
              <span class="value">{{$order->invoice_id}}</span>
            </div>
          </div>
      </div>
    </div>
    
    <div class="invoice-body">
      <table>
        <thead>
          <tr class="table-heading">
            <th>Item</th>
            <th>Image</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
        @foreach($order->orderdetails as $key=>$value)
        <tr>
          <td>
          {{$value->product_name}}
            <div class="size-sku">
                @if($value->product && $value->product->product_code)
              <span>SKU: {{$value->product->product_code}}</span>
              @endif
            @if($value->product_size)
              <span>Size: {{$value->product_size}}</span>
              @endif   
              @if($value->product_color)
              <span>Color: {{$value->product_color}}</span>
              @endif
            </div>
          </td>
          <td>
              <img src="{{ asset($value->product && $value->product->image ? $value->product->image->image : 'default.jpg') }}" alt="Product" class="product-image">
          </td>
          <td>৳{{$value->sale_price}}</td>
          <td>{{$value->qty}}</td>
          <td>৳{{$value->sale_price * $value->qty}}</td>
        </tr>
        @endforeach

          <tr class="table-subtotal-left">
            <td></td>
            <td colspan="3"><strong>Subtotal:</strong></td>
            <td><strong>৳{{$subtotal}}</strong></td>
          </tr>

          <tr class="table-subtotal-left">
            <td>Order Note: {{ $order->note }}</td>
            <td colspan="3">Discount:</td>
            <td>৳{{$discount}}</td>
          </tr>

          <tr class="table-subtotal-left">
            <td></td>
            <td colspan="3"><strong>Shipping (+)</strong></td>
            <td><strong>৳{{$shipping}}</strong></td>
          </tr>

           <tr class="table-subtotal-left">
            <td></td>
            <td colspan="3"><strong>Grand Total:</strong></td>
            <td><strong>৳{{$grand_total}}</strong></td>
          </tr>

          <tr class="table-subtotal-left">
            <td></td>
            <td colspan="3">Advance / Paid:</td>
            <td>৳{{$advance}}</td>
          </tr>

          <tr class="table-subtotal-left">
            <td></td>
            <td colspan="3"><strong>Due / Receivable:</strong></td>
            <td><strong>৳{{$due_amount}}</strong></td>
          </tr>

        </tbody>
      </table>
    </div>

    <div class="header-section">
      <div style="text-align: left;">
        <p style="margin: 0; font-weight: bold; font-size: 9px;">Invoice Last Update: {{$order->updated_at}} @if($order->user) {{$order->user->name}} @endif</p>
      </div>
      <div style="text-align: right;">
        <p style="margin: 0; font-weight: bold;">Account Manager</p>
      </div>
    </div>

    <div class="footer-section">
      <div class="footer-left">
        <div class="footer-left-one">
          <span>Thank You For Your Order</span>
          <span>Terms & Condition</span>
        </div>
        <div class="footer-left-two">
          <span>Your Account Manager</span>
          <span>@if($order->user) {{$order->user->name}}: {{$order->user->mobile}} @endif</span>
        </div>
      </div>
      <div class="footer-right">
          <a href="{{route('home')}}"><img src="{{asset($generalsetting->white_logo)}}" alt="Company Logo" class="company-logo"></a>
      </div>
    </div>
  </div>

  <script src="{{asset('public/backEnd/')}}/assets/js/qrcode.min.js"></script>
  <script src="{{asset('public/backEnd/')}}/assets/js/brcode.min.js"></script>
  <script>
    function printFunction() {
        window.print();
    }
    
    // QR Code Check
    var qrElement = document.getElementById("qrcode");
    if(qrElement){
        const qrcode = new QRCode(qrElement, {
            text: "{{ route('front.invoice', ['id' => $order->id, 'day' => \Carbon\Carbon::parse($order->created_at)->format('md')]) }}",
            width: 100,
            height: 83,
        });
    }

    // Barcode Check
    var barElement = document.getElementById("barcode");
    if(barElement){
        const invoiceNumber = "{{$order->invoice_id}}";
        JsBarcode("#barcode", invoiceNumber, {
          format: "CODE128",
          width: 2,
          height: 50,
          displayValue: false
        });
    }
  </script>

@endsection