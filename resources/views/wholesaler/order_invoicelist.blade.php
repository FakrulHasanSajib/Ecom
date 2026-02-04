@extends('wholesaler.master')
@section('title'.' All order')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/css/order_oage_design.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
@include('backEnd.layouts.b2b_menu')

<div class="container p-0" >
  <div class="row m-0 pt-1 ">
    <div class="col-12 col-md-3 mb-1 p-0 col-lg-2 text-center">
       

      <div class="col-md-12">
        <h3 class="fs-5 fs-md-6"> Order ({{$order_count}})</h3> <!-- Bigger font size on mobile -->
      </div>
      
    </div>

    <div class="col-12 col-md-9 col-lg-10 m-0 px-0 ps-md-2">

        <div class="row mb-0 mb-md-1 px-0 d-flex justify-content-between flex-wrap ">
          
          <div class="col-6 col-md-auto mb-1 mb-md-0 ps-2 pe-1 ps-md-0 pe-md-0">
              <button type="button" class="btn btn-primary w-100" onclick="printSelectedOrders()">
    <i class="fas fa-print pe-1"></i>Print
</button>
          </div>
          <div class="col-6 col-md-auto mb-1 mb-md-0 ps-1 pe-2 ps-md-0 pe-md-0">
              <button id="export-btn" class="btn btn-primary w-100">
          <i class="fas fa-file-export pe-1"></i>Export
        </button>

          </div>
         
      </div>

        <div class="row mb-1 ps-md-2  d-flex justify-content-between flex-wrap gap-0 gap-md-1">
          <form action="{{ route('admin.order.all_order') }}" method="get" class="d-flex flex-wrap align-items-center gap-2">
              <!-- Date Dropdown -->
              <div class="col-6 col-md-auto mb-1 mb-md-0 ps-2 pe-1 ps-md-0 pe-md-0">
                  <select name="date_filter" class="form-control w-100">
                      <option value="">Select Date</option>
                      <option value="today">Today</option>
                      <option value="this_week">This Week</option>
                      <option value="this_month">This Month</option>
                  </select>
              </div>
              
              <!-- Search Input -->
              <div class="col-6 col-md-auto mb-1 mb-md-0 ps-2 pe-1 ps-md-0 pe-md-0">
                  <input type="text" name="keyword" class="form-control w-100" placeholder="Search phone Name Or Invoice Id" />
              </div>
              <!-- Filter Button -->
              <div class="col-6 col-md-auto mb-1 mb-md-0 ps-1 pe-2 ps-md-0 pe-md-0">
                  <button class="btn btn-primary w-100">
                      <i class="fas fa-filter"></i> Filter
                  </button>
              </div>
          </form>
      </div>


    </div>
  </div>

  <div class="table-responsive">
      <table id="datatable-buttons" class="table table-bordered table-responsive table-striped">
        <thead class="table-primary text-center">
          <tr>
            <th class="action"><input type="checkbox" id="checkAll" onclick="checkClickFunc(event)"/> Action</th>
            <th class="product">Product</th>
            <th class="order">Order</th>
            <th class="name">Name Phone</th>
            <th class="address">Address</th>
            <th class="assign">Assign</th>
            <th class="status">Status</th>
            <th class="combo">Comments</th>
          </tr>
        </thead>
        <tbody>
            0;
@endphp

@foreach($show_data as $key => $value)
@php
    // Safe access to product using optional() and null coalescing
    $product = optional(optional($value->orderdetails)->first())->product;
    $productImage = optional(optional($product)->image)->image ?? 'default.jpg';
    
    // Safe access to payment advance
    $payadvance = optional(optional($value->payment)->first())->advance ?? null;
    
    // Safe mapping of products with proper null checks
    $products = collect();
    if ($value->orderdetails) {
        $products = $value->orderdetails->map(function($orderDetail) {
            return [
                'id' => optional($orderDetail->product)->id ?? '',
                'name' => optional($orderDetail->product)->name ?? '',
                'size' => $orderDetail->product_size ?? '',
                'price' => optional($orderDetail->product)->new_price ?? '',
                'image' => asset(optional(optional($orderDetail->product)->image)->image ?? 'default.jpg'),
            ];
        });
    }
    
    // Safe city name retrieval
    $cityname = null;
    if (!empty(optional($value->shipping)->district)) {
        $city = \App\Models\City::where('id', $value->shipping->district)->first();
        $cityname = $city ? $city->name : null;
    }
    
    // Safe user ID assignment
    $assigin = optional($value->user)->id ?? null;
@endphp

<tr>
    
    <td class="action-icons-row">
        <span class="action-icons">
            <input id="{{ $value->id }}" type="checkbox" class="checkbox" value="{{ $value->id }}">
            
         <a href="{{ route('wholesaler.order.invoice', ['invoice_id' => $value->invoice_id]) }}" title="Invoice">
                <i class="fas fa-eye"></i>
            </a>
                
        </span>
    </td>
    
    <td class="product-img">
        <img src="{{ asset($productImage) }}" alt="Product Image" class="img-fluid" />
    </td>
    
    <td class="stacked-text">
        <span>{{ $value->invoice_id }}</span>
        <span>৳{{ $value->amount }}</span>
        <span>{{ $value->order_source }}</span>
    </td>
    
    <td class="stacked-text">
        <span>{{ optional($value->shipping)->name ?? '' }}</span>
        <span>
            @if(optional($value->shipping)->phone)
                <a href="tel:{{ $value->shipping->phone }}">
                    {{ $value->shipping->phone }}
                </a>
            @endif
        </span>
        <span>{{ optional($product)->product_code ?? '' }}</span>
    </td>
    
    <td class="stacked-text">
        <span>{{ Str::limit(strip_tags(optional($value->shipping)->address ?? ''), 30) }}</span>
        <span>{{ optional($value->shipping)->area ?? '' }}</span>
        <span>{{ $cityname }} → {{ optional($value->shipping)->thana ?? '' }}</span>
    </td>
    
    <td class="stacked-text">
        <span>{{ $value->courier }}</span>
        <span>
            @if ($value->courier == 'pathao')
                <a href="https://merchant.pathao.com/public-tracking?consignment_id={{ $value->tracking_id }}" target="_blank">Link</a>
            @elseif ($value->courier == 'steadfast')
                <a href="https://steadfast.com.bd/t/{{ $value->tracking_id }}" target="_blank">Link</a>
            @else
                <span>No Tracking Link</span>
            @endif
        </span>
        <span>{{ optional($value->user)->name ?? 'No User' }}</span>
    </td>
    
    <td class="stacked-text">
        <span>{{ date('d-m-Y', strtotime($value->updated_at)) }}</span>
        <span>{{ date('h:i A', strtotime($value->updated_at)) }}</span>
        <span class="status-badge" style="background-color: {{ optional($value->status)->colorcode ?? '' }};">
            {{ optional($value->status)->name ?? '' }}
        </span>
    </td>
    
    <td>
        <span>{{ Str::words($value->note ?? '', 50, '...') }}</span>
    </td>
</tr>

@endforeach

          <!-- Add more rows as needed -->
        </tbody>
      </table>
      <div class="custom-paginate">
                    {{$show_data->links('pagination::bootstrap-4')}}
                </div>
    </div>


</div>




@endsection
  @section('script')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>

    function updateSelectedBanknumbers() {
    const checkedIds = [];
    $('input.checkbox:checked').each(function() {
        checkedIds.push(this.id);
    });
    const orderIdJSON = JSON.stringify(checkedIds);
    $('#selectedBanknumbers').val(orderIdJSON);
    console.log("Selected Banknumbers:", orderIdJSON); // For debugging
}
    function checkClickFunc(e)
   {
 
   if($('#checkAll').is(':checked')){
           $('input[type=checkbox]').each(function(){
               $('#'+this.id).prop('checked',true);
               $('.dude').removeAttr('style'); 
           });
       }else{
           $('input[type=checkbox]').each(function(){
               $('#'+this.id).prop('checked',false);
               $('.dude').attr('style','display:none'); 
           });
       }
       updateSelectedBanknumbers();
    
 }
 
 


  
document.getElementById('export-btn').addEventListener('click', function () {
    let selectedIds = [];
    document.querySelectorAll('.checkbox:checked').forEach(function (checkbox) {
        selectedIds.push(checkbox.value);
    });

    if (selectedIds.length === 0) {
        alert('Please select at least one order.');
        return;
    }

    // Send selected IDs to server (GET or POST — example with GET)
    let url = `{{ route('admin.order.order_csv') }}?ids=${selectedIds.join(',')}`;
    window.location.href = url;
});

function printSelectedOrders() {
    // Collect all checked checkboxes
    let selectedOrderIds = [];
    document.querySelectorAll('.checkbox:checked').forEach((checkbox) => {
        selectedOrderIds.push(checkbox.value);
    });

    if (selectedOrderIds.length === 0) {
        alert("Please select at least one order.");
        return;
    }

    // Send AJAX request
    fetch("{{ route('admin.order.order_print') }}", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            order_ids: selectedOrderIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            // Open a new window/tab with the rendered HTML
            const printWindow = window.open('', '_blank');
            printWindow.document.write(data.view);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        } else {
            alert("Something went wrong!");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error printing orders.");
    });
}
</script>
@endsection