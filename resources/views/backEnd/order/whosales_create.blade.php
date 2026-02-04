@extends('backEnd.layouts.master') @section('title','Order Create') @section('css')
<style>
 .increment_btn,
 .remove_btn {
  margin-top: -17px;
  margin-bottom: 10px;
 }
</style>
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
@endsection @section('content')

<div class="container-fluid">
 <!-- start page title -->
 <div class="row">
  <div class="col-12">
   <div class="page-title-box">
    <div class="page-title-right">
     <form method="post" action="{{route('admin.order.cart_clear')}}" class="d-inline">
      @csrf
      <button type="submit" class="btn btn-danger rounded-pill delete-confirm" title="Delete"><i class="fas fa-trash-alt"></i> Cart Clear</button>
     </form>
    </div>
    <h4 class="page-title">Ready To Invoice</h4>
   </div>
  </div>
 </div>
 <!-- end page title -->
 <div class="row justify-content-center">
  <div class="col-lg-12">
   <div class="card">
    <div class="card-body">
     <form action="{{route('admin.order.store')}}" method="POST" class="row pos_form" data-parsley-validate="" enctype="multipart/form-data">
      @csrf
      <div class="col-sm-12">
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
      <!-- col end -->
      <div class="col-sm-12">
       <table class="table table-bordered table-responsive-sm">
        <thead>
         <tr></tr>
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
         @php $product_discount = 0; @endphp @foreach($cartinfo as $key=>$value)
         <tr>
          <td><img height="30" src="{{asset($value->options->image)}}" /></td>
          <td>{{$value->name}}</td>
          <td>
           <div class="qty-cart vcart-qty">
            <div class="quantity">
             <button class="minus cart_decrement" value="{{$value->qty}}" data-id="{{$value->rowId}}">-</button>
             
             

             <input type="number" class="cart_update_qty" value="{{$value->qty}}" min="1" data-id="{{$value->rowId}}" style="width: 60px; text-align: center; border: 1px solid #ddd;" />
             <button class="plus cart_increment" value="{{$value->qty}}" data-id="{{$value->rowId}}">+</button>
            </div>
           </div>
          </td>
          <td><input type="number" class="product_price" value="{{$value->price}}" placeholder="0.00" data-id="{{$value->rowId}}"></td>
          <td class="discount">
    <input type="number" 
           class="product_discount" 
           value="{{$value->options->product_discount}}" 
           placeholder="0.00" 
           data-id="{{$value->rowId}}" 
           data-product-id="{{$value->id}}" {{-- এই লাইনটি যোগ করুন --}}
    />
</td>
          <td>{{($value->price - $value->options->product_discount)*$value->qty}}</td>
          <td>
           <button type="button" class="btn btn-danger btn-xs cart_remove" data-id="{{$value->rowId}}"><i class="fa fa-times"></i></button>
          </td>
         </tr>
         @php $product_discount += $value->options->product_discount*$value->qty; Session::put('product_discount',$product_discount); @endphp @endforeach
        </tbody>
       </table>
      </div>
     


      <!-- custome address -->
     <div class="col-sm-6">
    <!-- Wholesale Customer Selection Section -->
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group mb-3">
            <label for="whosale_add" class="form-label">Wholesale Customer *</label>
            <select name="whosale_customer_id" id="whosale_add" class="form-control select2 @error('whosale_customer_id') is-invalid @enderror">
                <option value="">Select..</option>
                @foreach($customers as $customer)
                <option value="{{$customer->id}}">{{$customer->name}} ({{$customer->phone}})</option>
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
    
    <!-- Customer Information Form Section -->
    <div class="row" id="customer_form_section">
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Customer Name" name="name" value="{{ old('name') }}" />
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <!-- col-end -->
        <div class="col-sm-12">
            <div class="form-group mb-2">
                <input type="number" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Customer Number" name="phone" value="{{ old('phone') }}" />
                @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <!-- col-end -->
        <div class="col-sm-12">
            <div class="form-group mb-3">
                <input type="text" placeholder="Address" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" />
                @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        
        <!-- col-end -->
    </div>
</div>
      <!-- cart total -->
      <div class="col-sm-6">
       <table class="table table-bordered">
        <tbody id="cart_details">
         @php $subtotal = Cart::instance('pos_shopping')->subtotal(); $subtotal = str_replace(',','',$subtotal); $subtotal = str_replace('.00', '',$subtotal); $shipping = Session::get('pos_shipping'); $total_discount =
         Session::get('pos_discount')+Session::get('product_discount'); @endphp
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
    <!-- end card-body-->
   </div>
   <!-- end card-->
  </div>
  <!-- end col-->
 </div>
</div>
@endsection @section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<!-- Plugins js -->
<script src="{{asset('public/backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>
<script>
 $(".summernote").summernote({
  placeholder: "Enter Your Text Here",
 });
</script>

<script type="text/javascript">
 $(document).ready(function () {
  $(".select2").select2();
 });
</script>
<script>
   // Add this to your existing script section
$(document).ready(function() {
    // Initialize select2
    $(".select2").select2();
    
    // Handle wholesale customer selection
    $('#whosale_add').on('change', function() {
        var customerId = $(this).val();
        
        if (customerId) {
            // Show loading state
            $(this).prop('disabled', true);
            
            // Load customer information via AJAX
            $.ajax({
                type: "GET",
                url: "{{ route('admin.order.whosales_customer_info') }}", 
                data: { id: customerId },
                dataType: "json",
                success: function(response) {
                    if (response.success && response.customer) {
                        // Populate customer information fields
                        $('#name').val(response.customer.name || '');
                        $('#phone').val(response.customer.phone || '');
                        $('#address').val(response.customer.address || '');
                        
                        // If you have an area field, populate it too
                        if (response.customer.area_id && $('#area').length) {
                            $('#area').val(response.customer.area_id).trigger('change');
                        }
                        
                        // Optional: Show success message
                        console.log('Customer information loaded successfully');
                    } else {
                        alert('Customer information not found');
                        clearCustomerFields();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Error loading customer information. Please try again.');
                    clearCustomerFields();
                },
                complete: function() {
                    // Re-enable the select
                    $('#whosale_add').prop('disabled', false);
                }
            });
        } else {
            // Clear fields if no customer selected
            clearCustomerFields();
        }
    });
    
    // Function to clear customer fields
    function clearCustomerFields() {
        $('#name').val('');
        $('#phone').val('');
        $('#address').val('');
        if ($('#area').length) {
            $('#area').val('').trigger('change');
        }
    }
    
   
    
    // Call this if you need to load customers dynamically
    // loadWhosalesCustomers();
});
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
     return cart_content() + cart_details();
    },
   });
  }
 });
 $(".cart_increment").click(function (e) {
  e.preventDefault();
  var id = $(this).data("id");
  var qty = $(this).val();
  if (id) {
   $.ajax({
    cache: false,
    data: { id: id, qty: qty },
    type: "GET",
    url: "{{route('admin.order.cart_increment')}}",
    dataType: "json",
    success: function (cartinfo) {
     return cart_content() + cart_details();
    },
   });
  }
 });
 $(".cart_decrement").click(function (e) {
  e.preventDefault();
  var id = $(this).data("id");
  var qty = $(this).val();
  if (id) {
   $.ajax({
    cache: false,
    type: "GET",
    data: { id: id, qty: qty },
    url: "{{route('admin.order.cart_decrement')}}",
    dataType: "json",
    success: function (cartinfo) {
     return cart_content() + cart_details();
    },
   });
  }
 });
 $(".cart_remove").click(function (e) {
  e.preventDefault();
  var id = $(this).data("id");
  if (id) {
   $.ajax({
    cache: false,
    type: "GET",
    data: { id: id },
    url: "{{route('admin.order.cart_remove')}}",
    dataType: "json",
    success: function (cartinfo) {
     return cart_content() + cart_details();
    },
   });
  }
 });
 $(".product_price").change(function(){
        var id = $(this).data("id");
        var price = $(this).val();
          $.ajax({
           cache: false,
           type:"GET",
           data:{'id':id,'price':price},
           url:"{{route('admin.order.product_price')}}",
           dataType: "json",
          success: function(cartinfo){
            return cart_content()+cart_details();
          }
        });
   });
// এই কোডটি ব্যবহার করুন (Old code replace করে)
$(document).on("change", ".product_discount", function () {
    var id = $(this).data("id");
    var discount = $(this).val();
    
    $.ajax({
        cache: false,
        type: "GET",
        data: { id: id, discount: discount },
        url: "{{route('admin.order.product_discount')}}",
        dataType: "json",
        success: function (cartinfo) {
            // কার্ট এবং ডিটেইলস সেকশন রিফ্রেশ করা
            return cart_content() + cart_details();
        },
    });
});
 $(".cartclear").click(function (e) {
  $.ajax({
   cache: false,
   type: "GET",
   url: "{{route('admin.order.cart_clear')}}",
   dataType: "json",
   success: function (cartinfo) {
    return cart_content() + cart_details();
   },
  });
 }); // pshippingfee from total




// whosales_create.blade.php এর স্ক্রিপ্ট সেকশনে এটি যোগ করুন
$(document).on("blur change keypress", ".cart_update_qty", function (e) {
    // যদি এন্টার বাটন চাপেন অথবা ইনপুট ফিল্ড থেকে মাউস সরিয়ে নেন (blur)
    if (e.type === 'keypress' && e.which !== 13) return;

    var rowId = $(this).data("id");
    var qty = $(this).val();

    if (qty < 1 || qty == '') {
        $(this).val(1);
        qty = 1;
    }

    $.ajax({
        cache: false,
        type: "GET",
        url: "{{ route('admin.order.cart_update') }}",
        data: { 
            rowId: rowId, 
            qty: qty 
        },
        dataType: "json",
        success: function (response) {
            // কার্টের টেবিল এবং টোটাল হিসাব রিফ্রেশ করা
            cart_content(); 
            cart_details();
        }
    });
});
</script>
@endsection