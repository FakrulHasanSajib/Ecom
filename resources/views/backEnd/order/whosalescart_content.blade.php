@php $product_discount = 0; @endphp

@foreach($cartinfo as $value)
<tr id="row-{{ $value->rowId }}">
    {{-- 1. Image Column --}}
    <td><img height="30" src="{{ asset($value->options->image) }}"></td>
    
    {{-- 2. Name Column --}}
    <td>{{ $value->name }}</td>
    
    {{-- 3. Quantity Column --}}
    <td>
        <input type="number" 
               class="cart_update_qty form-control" 
               value="{{ $value->qty }}" 
               data-id="{{ $value->rowId }}"
               style="width: 80px;"
               autocomplete="off">
    </td>

    {{-- 4. Sell Price Column --}}
    <td>
        <input type="number" 
               class="product_price form-control" 
               value="{{ $value->price }}" 
               data-id="{{ $value->rowId }}" 
               style="width: 100px;">
    </td>

    {{-- 5. Discount Column --}}
    <td>
        <input type="number" 
               class="product_discount form-control" 
               value="{{ $value->options->product_discount }}" 
               data-id="{{ $value->rowId }}" 
               data-product-id="{{ $value->id }}" {{-- [MUST HAVE THIS] --}}
               style="width: 100px;">
    </td>

    {{-- 6. Subtotal Column --}}
    <td class="row-total" style="font-weight: bold;">
        {{ ($value->price - $value->options->product_discount) * $value->qty }}
    </td>

    {{-- 7. Action Column --}}
    <td>
        <button type="button" class="btn btn-danger btn-xs cart_remove" data-id="{{ $value->rowId }}">✕</button>
    </td>
</tr>

@php
    $product_discount += $value->options->product_discount * $value->qty;
    Session::put('product_discount', $product_discount);
@endphp
@endforeach

<script>
var qtyTimer;

/* ==========================================
   ONLY UPDATE TOTALS (No Table Reload)
=========================================== */
function updateTotalsOnly() {
    $.ajax({
        type: "GET",
        url: "{{ route('admin.orderwhosales.cart_details') }}",
        success: function(res) {
            $('#cart_details').html(res);
        }
    });
}

/* ==========================================
   HELPER: UPDATE ALL ROW IDs (CRITICAL FIX)
   ডিসকাউন্ট বা প্রাইস পরিবর্তনের পর সব বাটনের আইডি আপডেট করার জন্য
=========================================== */
function updateRowIds(row, newId) {
    // 1. Row এর নিজের ID আপডেট
    row.attr('id', 'row-' + newId);
    
    // 2. সব ইনপুট ফিল্ডের data-id আপডেট
    row.find('.product_price').data('id', newId).attr('data-id', newId);
    row.find('.product_discount').data('id', newId).attr('data-id', newId);
    row.find('.cart_update_qty').data('id', newId).attr('data-id', newId);
    
    // 3. সব বাটনের data-id আপডেট
    row.find('.cart_remove').data('id', newId).attr('data-id', newId);
    
    console.log("DOM Updated with new ID: " + newId);
}

/* ==========================================
   SMOOTH TYPING LOGIC (QUANTITY)
=========================================== */
$(document).on('input', '.cart_update_qty', function() {
    let input = $(this);
    let qty = input.val();
    let rowId = input.data('id'); // এটি এখন সবসময় আপডেটেড ID পাবে
    let row = input.closest('tr');

    // 1. Instant calculation (UI)
    let price = parseFloat(row.find('.product_price').val()) || 0;
    let disc = parseFloat(row.find('.product_discount').val()) || 0;
    let total = (price - disc) * (qty || 0);
    row.find('.row-total').text(total.toFixed(2));

    // 2. Clear previous timer
    clearTimeout(qtyTimer);
    
    // 3. Validation
    if (qty === "" || qty < 0) return;

    // 4. Server Request
    qtyTimer = setTimeout(function() {
        $.ajax({
            type: "POST",
            url: "{{ route('admin.order.cart_update') }}",
            data: {
                _token: "{{ csrf_token() }}",
                rowId: rowId,
                qty: qty
            },
            success: function() {
                updateTotalsOnly();
            }
        });
    }, 800); 
});

/* ==========================================
   PRICE & DISCOUNT CHANGE
=========================================== */
$(document).on('input', '.product_price, .product_discount', function() {
    let row = $(this).closest('tr');
    let id = $(this).data('id');
    let product_id = $(this).data('product-id');
    
    let price = parseFloat(row.find('.product_price').val()) || 0;
    let discount = parseFloat(row.find('.product_discount').val()) || 0;
    let qty = parseFloat(row.find('.cart_update_qty').val()) || 0;
    
    // UI update instantly
    row.find('.row-total').text(((price - discount) * qty).toFixed(2));

    let isPrice = $(this).hasClass('product_price');
    
    clearTimeout(qtyTimer);
    qtyTimer = setTimeout(function() {
        $.ajax({
            type: "GET",
            url: isPrice ? "{{ route('admin.order.product_price') }}" : "{{ route('admin.order.product_discount') }}",
            data: { 
                id: id, 
                product_id: product_id,
                [isPrice ? 'price' : 'discount']: isPrice ? price : discount 
            },
            success: function(response) {
                // [FIX] সার্ভার থেকে নতুন ID আসলে আমরা টেবিলের সব ফিল্ড আপডেট করে দেব
                if(response.status === 'success' && response.new_rowId) {
                    updateRowIds(row, response.new_rowId);
                }
                updateTotalsOnly();
            }
        });
    }, 800);
});

/* ==========================================
   REMOVE ITEM
=========================================== */
$(document).on('click', '.cart_remove', function() {
    let id = $(this).data('id');
    if(confirm('Are you sure?')) {
        $.ajax({
            type: "GET",
            url: "{{ route('admin.order.cart_remove') }}",
            data: { id: id },
            success: function() {
                location.reload(); 
            }
        });
    }
});
</script>