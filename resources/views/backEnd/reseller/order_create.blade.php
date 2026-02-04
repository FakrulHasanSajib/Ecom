@extends('backEnd.reseller.layout.master')
@section('title', 'Create Order')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Place Dropshipping Order</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-3">Product Information</h5>
                        <div class="d-flex align-items-start mb-3">
                            <img class="d-flex me-3 rounded-circle avatar-lg" src="{{ asset($product->image->image) }}"
                                alt="Generic placeholder image">
                            <div class="w-100">
                                <h4 class="mt-0 mb-1">{{ $product->name }}</h4>
                                <p class="text-muted mb-1">Buy Price: <strong>৳{{ $dealer_price }}</strong></p>
                                <p class="text-muted">Stock: {{ $product->stock }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reseller.order.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <h5 class="header-title mb-3">Customer Details & Price</h5>

                            <div class="mb-3">
                                <label for="name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="Enter customer name">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Customer Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required
                                    placeholder="Enter customer phone">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Delivery Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required
                                    placeholder="Full address"></textarea>
                            </div>
<div class="alert alert-info mt-3">
    <strong><i class="mdi mdi-information-outline"></i> Shipping Rules:</strong>
    <ul class="mb-0 ps-3">
        <li>Inside Dhaka: 70 Tk, Outside Dhaka: 130 Tk (Base Charge).</li>
        <li>Extra 30 Tk added for every additional item (Quantity > 1).</li>
    </ul>
</div>

<div class="mb-3">
    <label class="form-label">Delivery Area</label>
    <div class="d-flex gap-3">
        <div class="form-check">
            {{-- লক্ষ্য করুন: এখানে shipping-input ক্লাস যোগ করা হয়েছে --}}
            <input class="form-check-input shipping-input" type="radio" name="delivery_charge" id="inside_dhaka" value="70">
            <label class="form-check-label" for="inside_dhaka">Inside Dhaka (70 Tk)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input shipping-input" type="radio" name="delivery_charge" id="outside_dhaka" value="130" checked>
            <label class="form-check-label" for="outside_dhaka">Outside Dhaka (130 Tk)</label>
        </div>
    </div>
</div>

                            <div class="mb-3">
                                <label for="custom_amount" class="form-label">Selling Price (Invoice Amount)</label>
                                <input type="number" class="form-control" id="custom_amount" name="custom_amount"
                                    min="{{ $dealer_price }}" required placeholder="e.g. 600">
                                <small class="text-muted">Your Buy Price: ৳{{ $dealer_price }}. Profit: calculated
                                    automatically.</small>
                            </div>

                            <div class="mb-3">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="qty" name="qty" value="1" min="1" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                        value="Cash On Delivery" checked>
                                    <label class="form-check-label" for="cod">Cash On Delivery</label>
                                </div>
                            </div>
    <div class="card bg-light border-primary">
    <div class="card-body p-3">
        <h5 class="card-title text-primary">Order Summary (Customer Invoice)</h5>
        
        <div class="d-flex justify-content-between mb-1">
            <span>Subtotal (Price x <span id="summ_qty">1</span>):</span>
            <span class="fw-bold">৳<span id="summ_subtotal">0</span></span>
        </div>

        <div class="d-flex justify-content-between mb-1">
            <span>Delivery Charge:</span>
            <span class="fw-bold text-danger">+ ৳<span id="summ_shipping">130</span></span>
        </div>

        <hr class="my-2">

        <div class="d-flex justify-content-between">
            <strong class="text-dark">Customer Will Pay:</strong>
            <strong class="text-success fs-5">৳<span id="summ_total">0</span></strong>
        </div>

        <div class="mt-2 text-end">
            <small class="text-muted">
                Cost: ৳<span id="summ_cost">0</span> | 
                <span class="text-primary fw-bold">Profit: ৳<span id="summ_profit">0</span></span>
            </small>
        </div>
    </div>
</div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // ডাটাবেস থেকে কেনা দাম (Dealer Price) নেওয়া
        var buyPricePerUnit = {{ $dealer_price }}; 
        var extraChargePerItem = 30; // অতিরিক্ত আইটেম চার্জ

        function calculateTotal() {
            // ১. ইনপুট ভ্যালু নেওয়া
            var qty = parseInt($('#qty').val()) || 1;
            
            // এই লাইনটি পরিবর্তন করা হয়েছে: এখন ইনপুট বক্স থেকে সেলিং প্রাইস নেওয়া হচ্ছে
            var sellingPricePerUnit = parseFloat($('#custom_amount').val()) || 0; 

            var baseShipping = parseInt($('input[name="delivery_charge"]:checked').val()) || 130;

            // ২. শিপিং চার্জ লজিক
            var totalShipping = baseShipping;
            if (qty > 1) {
                totalShipping = baseShipping + ((qty - 1) * extraChargePerItem);
            }

            // ৩. ক্যালকুলেশন
            var subTotal = sellingPricePerUnit * qty; // কাস্টমারের পণ্যের দাম
            var totalPayable = subTotal + totalShipping; // কাস্টমারের মোট বিল

            // ৪. প্রফিট হিসাব (আপনার দেখার জন্য)
            var totalBuyCost = (buyPricePerUnit * qty) + totalShipping; // আপনার খরচ
            var profit = totalPayable - totalBuyCost; // লাভ

            // ৫. HTML এ ভ্যালু আপডেট করা
            $('#summ_qty').text(qty);
            $('#summ_subtotal').text(subTotal);
            $('#summ_shipping').text(totalShipping);
            $('#summ_total').text(totalPayable);
            
            // কস্ট এবং প্রফিট আপডেট
            $('#summ_cost').text(totalBuyCost);
            $('#summ_profit').text(profit.toFixed(0)); // দশমিক বাদ দেওয়া হয়েছে
        }

        // ইভেন্ট লিসেনার: যখনই টাইপ করবেন বা চেঞ্জ করবেন তখনই হিসাব হবে
        $('#qty, #custom_amount').on('input change', function() {
            calculateTotal();
        });

        $('.shipping-input').on('change', function() {
            calculateTotal();
        });

        // পেজ লোড হওয়ার সাথে সাথে একবার রান হবে
        calculateTotal();
    });
</script>
@endsection