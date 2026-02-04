<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice</title>
  <link href="{{asset('public/frontEnd/css/bootstrap.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/invoice.css">
  <link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/timeline.css">
<style>
.container .container-custom{
  width: 60%;
}
.status-button.pending {
    background-color: {{$order->status?$order->status->colorcode:''}};
}
</style>

</head>

<body>
<div class="headprint" >
  <div class="no-print">
<div class="container container-custom ">

        <div class="row row-custom">
            <div class="col-xs-12">
                <button class="btn btn-custom" onclick="printFunction()">Print</button>
                <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#reportIssueModal">Report Issue</button>
                <button class="btn btn-custom" id="historyButton">History</button>

            </div>
        </div>
</div>
</div>
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
  <div class="invoice-to-right-first">
    <span class="invoice-text">Invoice</span>@php $text = ($order->amount == $order->payment->advance) ? 'paid' : 'Partial'; @endphp
    <span class="unpaid-button"> @if(empty($order->payment->advance)) {{$order->payment?$order->payment->payment_status:''}} @else {{$text}} @endif</span>
    <div id="qrcode" class="qr-code-image"></div>
  </div>
  <div class="invoice-container">
    <div class="invoice-no">
      <span class="label">Invoice No</span>
      <span class="value">{{$order->invoice_id}}</span>
    </div>
    <div class="invoice-date">
      <span class="label">Invoice Date</span>
      <span class="value">{{$order->created_at->format('d-m-y')}}</span>
    </div>
    <div class="invoice-due">
      <span class="label">Assignee</span>
      <span class="value">@if($order->user) {{$order->user->name}} @else No User @endif</span>
    </div>
    <div class="order-status">
      <span class="label">Order Status</span>
      <button class="status-button pending">
        {{$order->status?$order->status->name:''}}
      </button>
    </div>
  </div>
</div>

    </div>
    <form action="{{route('payment_invoice')}}" method="post">
      @csrf
    <div class="payment-row">
      <div class="payment-column1 amount-due">
          @php
          $subtotal = $order->orderdetails->sum(function($item) {
        return $item->sale_price * $item->qty;
          });
          $charge = $subtotal + $order->shipping_charge;
          @endphp
        <span class="value">Amount Due:{{$charge - $order->payment->advance}} ৳</span>
        <input type="hidden" name="order_id" value="{{$order->id}}">
      </div>
      <div class="payment-column2 payment-method">
        <select id="payment-method-dropdown" class="dropdown" name="payment_method">
          <option value="cash-on">Cash On Delivery</option>
          <option value="bkash">Bkash</option>
        </select>
      </div>
      <div class="payment-column3 pay-now">
        <button class="pay-now-button">Pay Now</button>
      </div>
    </div>
    </form>
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
  <td >
  {{$value->product_name}}
    <div class="size-sku">
        @if($value->product->product_code)
      <span>SKU: {{$value->product->product_code}}</span>
      @endif
    @if($value->product_size)
      <span>Size: {{$value->product_size}}</span>
      @endif   @if($value->product_color)
      <span>SKU: {{$value->product_color}}</span>
      @endif
    </div>
  </td>
  <td><img src="{{ asset($value->product->image ? $value->product->image->image : '') }}" alt="Product" class="product-image"></td>
  <td>৳{{$value->sale_price}}</td>
  <td>{{$value->qty}}</td>
  <td>৳{{$value->sale_price*$value->qty}}</td>
</tr>
@endforeach


          <tr class="table-subtotal-left">
            <td>
            </td>
            <td colspan="3"><strong>Subtotal:</strong></td>
            <td><strong>৳{{$subtotal}}</strong></td>
          </tr>
          <tr class="table-subtotal-left">
            <td>
              Order Note:
            </td>
            <td colspan="3">Discount:</td>
            <td>৳{{$order->discount}}</td>
          </tr>
          <tr class="table-subtotal-left">
            <td>
            </td>
            <td colspan="3">Advance</td>
            <td>{{$order->payment?$order->payment->advance:0}}</td>
          </tr>
          <tr class="table-subtotal-left">
            <td>
            </td>
            <td colspan="3">Grand Total:</td>
            <td>৳{{$charge - $order->payment->advance}}</td>
          </tr>
        </tbody>
      </table>
      <div class="barcode-invoice-number">
        <!-- Barcode Section -->
        <div class="total-section-left11" id="barqcode-image">
          <span style="display: block; margin-bottom: 5px;">Barcode Invoice Number:</span>
          <svg id="barcode"></svg>
        </div>
      </div>
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
          <span>@if($order->user) {{$order->user->name}}: {{$order->user->email}} @endif</span>
        </div>
      </div>
      <div class="footer-right">
          <a href="{{route('home')}}"><img src="{{asset($generalsetting->white_logo)}}" alt="Company Logo" class="company-logo"></a>

      </div>
    </div>
  </div>
  <div class="modal fade" id="reportIssueModal" tabindex="-1" aria-labelledby="reportIssueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reportIssueModalLabel">Report Issue</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form inside the modal -->
          <form action="{{route('report.issue')}}" method="post">
            @csrf
            <input type="hidden" name="invoice_id" value="{{$order->invoice_id}}">
            <div class="mb-3">
              <label for="issueDescription" class="form-label">Issue Description</label>
              <textarea class="form-control" name="issueDescription" rows="3" placeholder="Describe the issue..."></textarea>
            </div>
            <div class="mb-3">
              <label for="issueCategory" class="form-label">Category</label>
              <select class="form-select" name="issueCategory">
                <option selected>Select a category</option>
                <option value="1">Billing</option>
                <option value="2">Shipping</option>
                <option value="3">Product Issue</option>
              </select>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        </form>
      </div>
    </div>
  </div>
<div >




</div>

  <script src="{{asset('frontEnd/js/jquery-3.6.3.min.js')}}"></script>
  <script src="{{asset('backEnd/')}}/assets/js/qrcode.min.js"></script>
  <script src="{{asset('backEnd/')}}/assets/js/brcode.min.js"></script>
  <script src="{{asset('frontEnd/js/bootstrap.min.js')}}"></script>
  <script>
    document.getElementById('historyButton').addEventListener('click', function() {
    var timeline = document.getElementById('timelineContainer');
    // Toggle visibility of the timeline
    if (timeline.style.display === 'none' || timeline.style.display === '') {
      timeline.style.display = 'block'; // Show the timeline
    } else {
      timeline.style.display = 'none'; // Hide the timeline
    }
  });
    function printFunction() {
        window.print();
    }
    const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ route('front.invoice', ['id' => $order->id, 'day' => \Carbon\Carbon::parse($order->created_at)->format('md')]) }}",
            width: 100,
            height: 83,
        });
        const invoiceNumber = "{{$order->invoice_id}}";

    // Generate barcode for the invoice number
    JsBarcode("#barcode", invoiceNumber, {
      format: "CODE128",   // You can change the barcode format here
      width: 2,            // Adjust barcode width
      height: 50,          // Adjust barcode height
      displayValue: false  // Set to true if you want to display the invoice number below the barcode
    });
</script>
</body>
</html>