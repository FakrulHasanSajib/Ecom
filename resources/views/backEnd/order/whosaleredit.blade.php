@extends('backEnd.layouts.master') 
@section('title','Order Edit') 
@section('css')
<style>
 .increment_btn,
 .remove_btn {
  margin-top: -17px;
  margin-bottom: 10px;
 }
</style>
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
@endsection 

@section('content')
<div class="container-fluid">
 <div class="row">
  <div class="col-12">
   <div class="page-title-box">
    <div class="page-title-right">
     <form method="post" action="{{route('admin.order.cart_clear')}}" class="d-inline">
      @csrf
      <button type="submit" class="btn btn-danger rounded-pill delete-confirm" title="Delete"><i class="fas fa-trash-alt"></i> Cart Clear</button>
     </form>
    </div>
    <h4 class="page-title">Edit To Invoice</h4>
   </div>
  </div>
 </div>
 <div class="row justify-content-center">
  <div class="col-lg-12">
   <div class="card">
    <div class="card-body">
     <form action="{{route('admin.order.update')}}" method="POST" class="row pos_form" data-parsley-validate="" enctype="multipart/form-data">
      @csrf
      <div class="col-sm-12">
          <input type="hidden" value="{{$order->id}}" name="order_id">
       <div class="form-group mb-3">
        <label for="product_id" class="form-label">Products *</label>
        <select id="cart_add" class="form-control select2 @error('product_id') is-invalid @enderror" value="{{ old('product_id') }}">
         <option value="">Select..</option>
         @foreach($products as $value)
         <option value="{{$value->id}}">{{$value->name}}</option>
         @endforeach
        </select>
        @error('product_id')
        <span class="invalid-feedback" role="alert">
         <strong>{{ $message }}</strong>
        </span>
        @enderror
       </div>
      </div>
      <div class="col-sm-12">
       <table class="table table-bordered table-responsive-sm">
        <thead>
         <tr>
          <th style="width: 10%;">Image</th>
          <th style="width: 25%;">Name</th>
          <th style="width: 15%;">Quantity</th>
          <th style="width: 15%;">Sell Price</th>
          <th style="width: 15%;">Discount</th>
          <th style="width: 15%;">Sub Total</th>
          <th style="width: 15%;">Action</th>
         </tr>
        </thead>
        <tbody id="cartTable">
         @php $product_discount = 0; @endphp 
         @foreach($cartinfo as $key=>$value)
         <tr id="row-{{ $value->rowId }}">
          {{-- 1. Image --}}
          <td><img height="30" src="{{asset($value->options->image)}}" /></td>
          
          {{-- 2. Name --}}
          <td>{{$value->name}}</td>
          
          {{-- 3. Quantity (Updated Structure) --}}
          <td>
            <input type="number" 
                   class="cart_update_qty form-control" 
                   value="{{ $value->qty }}" 
                   data-id="{{ $value->rowId }}"
                   style="width: 80px;"
                   autocomplete="off">
          </td>
          
          {{-- 4. Price --}}
          <td>
            <input type="number" 
                   class="product_price form-control" 
                   value="{{ $value->price }}" 
                   data-id="{{ $value->rowId }}" 
                   style="width: 100px;">
          </td>
          
          {{-- 5. Discount (Updated with product-id) --}}
          <td class="discount">
            <input type="number" 
                   class="product_discount form-control" 
                   value="{{ $value->options->product_discount }}" 
                   data-id="{{ $value->rowId }}" 
                   data-product-id="{{ $value->id }}" {{-- [CRITICAL FIX] --}}
                   style="width: 100px;" 
                   placeholder="0.00" />
          </td>
          
          {{-- 6. Subtotal --}}
          <td class="row-total" style="font-weight: bold;">
              {{($value->price - $value->options->product_discount)*$value->qty}}
          </td>
          
          {{-- 7. Action --}}
          <td>
           <button type="button" class="btn btn-danger btn-xs cart_remove" data-id="{{$value->rowId}}"><i class="fa fa-times"></i></button>
          </td>
         </tr>
         @endforeach
        </tbody>
       </table>
      </div>

      <div class="col-sm-6">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group mb-3">
            <label for="whosale_add" class="form-label">Wholesale Customer *</label>
            <select name="whosale_customer_id" id="whosale_add" class="form-control select2 @error('whosale_customer_id') is-invalid @enderror">
                        <option value="">Select..</option>
                        @foreach($customers as $customer)
                        <option value="{{$customer->id}}" 
                            {{ $order->customer_id == $customer->id ? 'selected' : '' }}>
                            {{$customer->name}} ({{$customer->phone}})
                        </option>
                        @endforeach
                    </select>
            @error('whosale_customer_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        </div>
    </div>
    
    <div class="row" id="customer_form_section">
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Customer Name" name="name" 
                           value="{{ old('name', $shippinginfo->name ?? $order->name) }}" />
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <input type="number" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                           placeholder="Customer Number" name="phone" 
                           value="{{ old('phone', $shippinginfo->phone ?? $order->phone) }}" />
                @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group mb-3">
                <input type="text" placeholder="Address" id="address" 
                           class="form-control @error('address') is-invalid @enderror" name="address" 
                           value="{{ old('address', $shippinginfo->address ?? $order->address) }}" />
                @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        
        </div>
</div>
      <div class="col-sm-6">
       <table class="table table-bordered">
        <tbody id="cart_details">
         @php 
            $subtotal = Cart::instance('pos_shopping')->subtotal(); 
            $subtotal = str_replace(',','',$subtotal); 
            $subtotal = str_replace('.00', '',$subtotal); 

            $shipping = Session::get('pos_shipping') ?? 0; 
            $pos_discount = Session::get('pos_discount') ?? 0;

            // কার্ট থেকে ডিসকাউন্ট ক্যালকুলেশন
            $total_product_discount = 0;
            foreach(Cart::instance('pos_shopping')->content() as $item){
                $total_product_discount += (($item->options->product_discount ?? 0) * $item->qty);
            }

            $total_discount = $pos_discount + $total_product_discount;
        @endphp
         <tr>
          <td>Sub Total</td>
          <td>{{$subtotal}}</td>
         </tr>
         
         <tr>
          <td>Discount</td>
          <td>{{$total_discount}}</td>
         </tr>
         <tr>
          <td>Total</td>
          <td>{{($subtotal + $shipping)- $total_discount}}</td>
         </tr>
        </tbody>
       </table>
      </div>
      <div>
       <input type="submit" class="btn btn-success" value="Order Submit" />
      </div>
     </form>
    </div>
    </div>
   </div>
  </div>
</div>
@endsection 

@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>

<script>
 $(".summernote").summernote({
  placeholder: "Enter Your Text Here",
 });
</script>

<script type="text/javascript">
 $(document).ready(function () {
  $(".select2").select2();
  
  // Wholesale Customer Logic
  $('#whosale_add').on('change', function() {
        var customerId = $(this).val();
        if (customerId) {
            $(this).prop('disabled', true);
            $.ajax({
                type: "GET",
                url: "{{ route('admin.order.whosales_customer_info') }}", 
                data: { id: customerId },
                dataType: "json",
                success: function(response) {
                    if (response.success && response.customer) {
                        $('#name').val(response.customer.name || '');
                        $('#phone').val(response.customer.phone || '');
                        $('#address').val(response.customer.address || '');
                    }
                },
                complete: function() {
                    $('#whosale_add').prop('disabled', false);
                }
            });
        }
    });
 });
</script>

{{-- UPDATED CART LOGIC SCRIPT --}}
<script>
 function cart_content() {
  $.ajax({
   type: "GET",
   url: "{{route('admin.orderwhosales.cart_content')}}",
   dataType: "html",
   success: function (cartinfo) {
    $("#cartTable").html(cartinfo);
   },
  });
 }
 
 function cart_details() {
  $.ajax({
   type: "GET",
   url: "{{route('admin.orderwhosales.cart_details')}}",
   dataType: "html",
   success: function (cartinfo) {
    $("#cart_details").html(cartinfo);
   },
  });
 }

 function updateTotalsOnly() {
    $.ajax({
        type: "GET",
        url: "{{ route('admin.orderwhosales.cart_details') }}",
        success: function(res) {
            $('#cart_details').html(res);
        }
    });
 }

 // HELPER: Update IDs after discount/price change
 function updateRowIds(row, newId) {
    row.attr('id', 'row-' + newId);
    row.find('.product_price').data('id', newId).attr('data-id', newId);
    row.find('.product_discount').data('id', newId).attr('data-id', newId);
    row.find('.cart_update_qty').data('id', newId).attr('data-id', newId);
    row.find('.cart_remove').data('id', newId).attr('data-id', newId);
 }

 // Add Product
 $("#cart_add").on("change", function (e) {
  var id = $(this).val();
  if (id) {
   $.ajax({
    cache: "false",
    type: "GET",
    data: { id: id },
    url: "{{route('admin.orderwhosales.cart_add')}}",
    dataType: "json",
    success: function (cartinfo) {
     cart_content(); 
     cart_details();
    },
   });
  }
 });

 // Remove Product
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

 // Clear Cart
 $(".cartclear").click(function (e) {
  $.ajax({
   cache: false,
   type: "GET",
   url: "{{route('admin.order.cart_clear')}}",
   dataType: "json",
   success: function (cartinfo) {
    cart_content(); 
    cart_details();
   },
  });
 });

 var qtyTimer;

 // Smooth Quantity Update
 $(document).on('input', '.cart_update_qty', function() {
    let input = $(this);
    let qty = input.val();
    let rowId = input.data('id');
    let row = input.closest('tr');

    let price = parseFloat(row.find('.product_price').val()) || 0;
    let disc = parseFloat(row.find('.product_discount').val()) || 0;
    let total = (price - disc) * (qty || 0);
    row.find('.row-total').text(total.toFixed(2));

    clearTimeout(qtyTimer);
    if (qty === "" || qty < 0) return;

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

 // Smooth Price & Discount Update (Fixes ID issue)
 $(document).on('input', '.product_price, .product_discount', function() {
    let row = $(this).closest('tr');
    let id = $(this).data('id');
    let product_id = $(this).data('product-id');
    
    let price = parseFloat(row.find('.product_price').val()) || 0;
    let discount = parseFloat(row.find('.product_discount').val()) || 0;
    let qty = parseFloat(row.find('.cart_update_qty').val()) || 0;
    
    // UI update
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
                if(response.status === 'success' && response.new_rowId) {
                    updateRowIds(row, response.new_rowId);
                }
                updateTotalsOnly();
            }
        });
    }, 800);
 });

</script>
@endsection