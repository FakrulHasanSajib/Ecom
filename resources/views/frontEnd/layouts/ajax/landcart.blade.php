@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal=str_replace(',','',$subtotal);
    $subtotal=str_replace('.00', '',$subtotal);
    $shipping = Session::get('shipping')?Session::get('shipping'):0;
    $discount = Session::get('discount')?Session::get('discount'):0;
@endphp
<div class="card-body cartlist">
                            <div class="row ">
                                @foreach(Cart::instance('shopping')->content() as $value)
                                    <div class="col-md-6 col-12 d-flex align-items-center mb-3">
                                        <!-- Checkbox for item removal -->
                                        <div class="form-check mr-3">
                                            <input type="checkbox" class="remove-item-checkbox form-check-input" data-id="{{ $value->rowId }}" checked />
                                        </div>

                                        <!-- Product Image and Name Section -->
                                        <div class="d-flex flex-column align-items-start col-md-8 col-7">
                                            <a href="{{ route('product', $value->options->slug) }}" class="d-flex flex-column align-items-start" style="font-size: 14px;">
                                                <img src="{{ asset($value->options->image) }}" height="80" width="80" class="mr-2" alt="{{ $value->name }}">
                                                <span>{{ Str::limit($value->name, 40) }}</span>
                                            </a>
                                        </div>

                                        <!-- Price Section (Aligned below the product name) -->
                                        <div class="col-md-4 col-5 text-left mt-2">
                                            <span class="font-weight-bold">৳{{ $value->price * $value->qty }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Cart Summary -->
                            <div class="cart-summary">
                                <div class="d-flex justify-content-between">
                                    <span>মোট</span>
                                    <span id="net_total">৳{{ $subtotal }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>ডেলিভারি চার্জ</span>
                                    <span id="cart_shipping_cost">৳{{ $shipping }}</span>
                                </div>
                                <div class="d-flex justify-content-between font-weight-bold">
                                    <span>সর্বমোট</span>
                                    <span id="grand_total">৳{{ $subtotal + $shipping }}</span>
                                </div>
                            </div>
                        </div>

<script src="{{asset('frontEnd/js/jquery-3.6.3.min.js')}}"></script>
<!-- cart js start -->
<script>
    $('.remove-item-checkbox').on('change', function () {
        var rowId = $(this).data('id');
        console.log(rowId);

        var action = $(this).is(':checked') ? 'add' : 'remove'; // If checked, it's remove, else add

        // Send AJAX request to remove item
        $.ajax({
            url: "{{ route('cart.remove') }}", // Your route to handle removal
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // CSRF token for security
                id: rowId,
                action: action
            },
            success: function (response) {
                console.log(response);
                // Update the cart content (if needed)
                // Optionally, refresh the cart display or update the total price
                $('#cart-content').html(response); // Assuming you have a div with id="cart-content"
            }
        });
    });
    $('.cart_store').on('click',function(){
    var id = $(this).data('id');
    var qty = $(this).parent().find('input').val();
    if(id){
        $.ajax({
           type:"GET",
           data:{'id':id,'qty':qty?qty:1},
           url:"{{route('cart.store')}}",
           success:function(data){
            if(data){
                return cart_count();
            }
           }
        });
     }
   });

    $('.cart_remove').on('click',function(){
    var id = $(this).data('id');
    if(id){
        $.ajax({
           type:"GET",
           data:{'id':id},
           url:"{{route('cart.remove')}}",
           success:function(data){
            if(data){
                $(".cartlist").html(data);
                return cart_count();
            }
           }
        });
     }
   });

    $('.cart_increment').on('click',function(){
    var id = $(this).data('id');
    if(id){
        $.ajax({
           type:"GET",
           data:{'id':id},
           url:"{{route('cart.increment')}}",
           success:function(data){
            if(data){
                $(".cartlist").html(data);
                return cart_count();
            }
           }
        });
     }
   });

    $('.cart_decrement').on('click',function(){
    var id = $(this).data('id');
    if(id){
        $.ajax({
           type:"GET",
           data:{'id':id},
           url:"{{route('cart.decrement')}}",
           success:function(data){
            if(data){
                $(".cartlist").html(data);
                return cart_count();
            }
           }
        });
     }
   });

    function cart_count(){
        $.ajax({
           type:"GET",
           url:"{{route('cart.count')}}",
           success:function(data){
            if(data){
                $("#cart-qty").html(data);
            }else{
               $("#cart-qty").empty();
            }
           }
        });
   };
</script>
<!-- cart js end -->