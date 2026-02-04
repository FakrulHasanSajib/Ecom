@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal=str_replace(',','',$subtotal);
    $subtotal=str_replace('.00', '',$subtotal);
    $shipping = Session::get('shipping')?Session::get('shipping'):0;
    $discount = Session::get('discount')?Session::get('discount'):0;
@endphp
<div class="table-responsive cartlist">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>প্রোডাক্ট</th>
                                            <th>পরিমাণ</th>
                                            <th>মূল্য</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(Cart::instance('shopping')->content() as $value)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($value->options->image) }}" height="40" width="40" class="me-2 rounded">
                                                    {{ Str::limit($value->name, 30) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="qty-cart">
                                                    <div class="quantity-controls">
                                                        <button type="button" class="qty-btn cart_decrement" data-id="{{ $value->rowId }}">-</button>
                                                        <input type="text" class="qty-input" value="{{ $value->qty }}" readonly />
                                                        <button type="button" class="qty-btn cart_increment" data-id="{{ $value->rowId }}">+</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>৳{{ number_format($value->price * $value->qty) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2"><strong>সাবটোটাল:</strong></td>
                                            <td><strong id="net_total">৳{{ number_format($subtotal) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>ডেলিভারি চার্জ:</strong></td>
                                            <td><strong id="cart_shipping_cost">৳{{ number_format($shipping) }}</strong></td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="2"><strong>মোট:</strong></td>
                                            <td><strong id="grand_total">৳{{ number_format($subtotal + $shipping) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>