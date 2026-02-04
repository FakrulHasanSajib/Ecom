@extends('wholesaler.master')
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
 $subtotal = $order->orderdetails->sum(function($item) {
        return $item->sale_price * $item->qty;
          });
 // ডিসকাউন্ট মাইনাস করা হয়েছে
 $mainprice = ($subtotal + $order->shipping_charge) - ($order->discount ?? 0);
@endphp
<div class="headprint">
<div class="no-print">
<div class="container container-custom">
        <div class="row row-custom">
            <div class="col-xs-12">
                <label class="label-custom">Download Invoice</label>
                <label class="label-custom">Print</label>
                <label class="label-custom">Report Issue</label>
            </div>
        </div>
        <div class="row row-custom">
            <div class="col-xs-12">
                <button class="btn btn-custom" onclick="printFunction()">Print</button>
                <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#reportIssueModal">Report Issue</button>
                <button class="btn btn-custom" id="historyButton">History</button>

            </div>
        </div>
        <div class="row row-custom">
            <div class="col-xs-12">
                <label>Admin</label>
            </div>
        </div>
        <div class="row row-custom">
            <div class="col-xs-12">
              <button type="button"
                class="btn btn-custom"
                data-bs-toggle="modal"
                data-bs-target="#smsModal"
                data-message-type="sms"
                data-customer-name="{{$order->shipping?$order->shipping->name:''}}"
                data-order-amount="{{$mainprice - $order->payment->advance}}"
                data-invoice-url="{{ route('front.invoice', ['id' => $order->id, 'day' => \Carbon\Carbon::parse($order->created_at)->format('md')]) }}"
                data-business-name="{{ config('app.name') }}"
                data-customer-number="{{$order->shipping?$order->shipping->phone:''}}">
  Send SMS
</button>

        <!-- WhatsApp Button -->
        <button type="button"
            class="btn btn-custom"
            data-bs-toggle="modal"
            data-bs-target="#smsModal"
            data-message-type="whatsapp"
            data-customer-name="{{$order->shipping?$order->shipping->name:''}}"
            data-order-amount="{{$mainprice - $order->payment->advance}}"
            data-invoice-url="{{ route('front.invoice', ['id' => $order->id, 'day' => \Carbon\Carbon::parse($order->created_at)->format('md')]) }}"
            data-business-name="{{ config('app.name') }}"
            data-customer-number="{{$order->shipping?$order->shipping->phone:''}}">
          WhatsApp
          </button>
                <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#paymentModal">Payment</button>
                <a href="{{ route('front.invoice', ['id' => $order->id, 'day' => \Carbon\Carbon::parse($order->created_at)->format('md')]) }}" class="btn btn-custom" >Preview</a>
                <a href="{{route('admin.order.edit',['invoice_id'=>$order->invoice_id])}}" class="btn btn-custom" >Edit</a>
            </div>
        </div>
    </div>
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
         
        <span class="value">Amount Due:{{$mainprice - $order->payment->advance}} ৳</span>
        <input type="hidden" name="order_id" value="{{$order->id}}">
      </div>
      <div class="payment-column2 payment-method">
        <select id="payment-method-dropdown" class="dropdownmy" name="payment_method">
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
                <td colspan="3"><strong>Shipping(+)</strong></td>
           <td><strong>৳{{$order->shipping_charge}}</strong></td>
           </tr>
          <tr class="table-subtotal-left">
            <td>
            </td>
            <td colspan="3">Advance</td>
            <td>{{$order->payment->advance}}</td>
          </tr>
          <tr class="table-subtotal-left">
            <td>
            </td>
            <td colspan="3">Grand Total:</td>
            <td>৳{{$mainprice - $order->payment->advance}}</td>
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
          <span>@if($order->user) {{$order->user->name}}: {{$order->user->mobile}} @endif</span>
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
            <div class="mb-3">
              <label for="invoice_id" class="form-label">Invoice Id</label>
             <input type="mobile" class="form-control" name="invoice_id" value="{{$order->invoice_id}}">
            </div>
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
  </div>

  <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="reportIssueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reportIssueModalLabel">Payment Section</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form inside the modal -->
          <form action="{{route('pay.invoice')}}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{$order->id}}">
            <input type="hidden" name="customer_id" value="{{$order->customer_id}}">
            <input type="hidden" name="amount" value="{{$order->amount}}">
            <div>Due: {{$order->amount - $order->payment->advance}} tk</div>
            <div class="mb-3">
              <label for="payment_method" class="form-label">Payment Method</label>
              <select class="form-select" name="payment_method">
                <option selected>Select a Payment</option>
                <option value="Cash On Delivery">Cash On Delivery</option>
                <option value="In Courier">In Courier</option>
                <option value="Bkash">Bkash</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="advance" class="form-label">Payment</label>
              <input type="text" class="form-control" name="advance">
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
  </div>
  <div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="reportIssueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smsModalLabel">SMS</h5>
          <p>Remagin SMS Balance: </p>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form inside the modal -->
          <form action="{{route('sms.invoice')}}" method="post">
            @csrf
            <input type="hidden" id="message_type" name="message_type" value=""> <!-- updated by JS -->

            <input type="hidden" name="id" value="{{$order->id}}">
            <input type="hidden" name="customer_id" value="{{$order->customer_id}}">
            <input type="hidden" name="amount" value="{{$order->amount}}">
            <div class="form-group mb-3">
        <label for="sms_template">Select Template</label>
        <select id="sms_template" class="form-select">
            <option value="">-- Select SMS Template --</option>
            @foreach($smsteamplate as $teamplate)
            <option value="{{$teamplate->smsteamplate}}">{{$teamplate->type}}</option>
            @endforeach
            
        </select>
    </div>
            <div class="mb-3">
              <label for="customer_number" class="form-label">Number</label>
             <input type="mobile" class="form-control" name="customer_number" value="{{$order->shipping?$order->shipping->phone:''}}">
            </div>
            <div class="mb-3">
              <label for="bodysms" class="form-label">SMS</label>
              <textarea class="form-control" id="sms_text" name="bodysms" rows="3"></textarea>
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
  
  
  


<script src="{{asset('public/backEnd/')}}/assets/js/qrcode.min.js"></script>
  <script src="{{asset('public/backEnd/')}}/assets/js/brcode.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const smsModal = document.getElementById('smsModal');
    const templateSelect = document.getElementById('sms_template');
    const smsTextArea = document.getElementById('sms_text');
    const messageTypeInput = document.getElementById('message_type');

    function populateTemplate(template, data) {
        return template
            .replace(/\[\[name\]\]/g, data.customerName || '')
            .replace(/\[\[DueAmount\]\]/g, data.orderAmount || '')
            .replace(/\[\[invoiceURL\]\]/g, data.invoiceUrl || '')
            .replace(/\[\[BusinessName\]\]/g, data.businessName || '');
    }

    smsModal.addEventListener('show.bs.modal', function (event) {
        const triggerButton = event.relatedTarget;

        const type = triggerButton.dataset.messageType || 'sms';
        messageTypeInput.value = type;

        // Update modal title
        smsModal.querySelector('.modal-title').textContent = type.toUpperCase();

        const data = {
            customerName: triggerButton.dataset.customerName,
            orderAmount: triggerButton.dataset.orderAmount,
            invoiceUrl: triggerButton.dataset.invoiceUrl,
            businessName: triggerButton.dataset.businessName,
            customerNumber: triggerButton.dataset.customerNumber,
        };

        // Store data on the modal
        Object.assign(smsModal.dataset, data);

        // If a template is selected, fill textarea
        const selectedTemplate = templateSelect.value;
        if (selectedTemplate) {
            const populatedMessage = populateTemplate(selectedTemplate, data);
            smsTextArea.value = populatedMessage;
        }

        // Optional: Pre-fill customer number
        const numberInput = smsModal.querySelector('[name="customer_number"]');
        numberInput.value = data.customerNumber || '';
    });

    templateSelect.addEventListener('change', function () {
        const selectedTemplate = this.value;
        const populatedMessage = populateTemplate(selectedTemplate, smsModal.dataset);
        smsTextArea.value = populatedMessage;
    });
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

@endsection