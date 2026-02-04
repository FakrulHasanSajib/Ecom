@extends('backEnd.layouts.master')
@section('title'.' All order')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/css/order_oage_design.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection
@section('content')
@include('backEnd.layouts.b2b_menu')
<div class="container p-0" >
  <div class="row m-0 pt-1 ">
    <div class="col-12 col-md-3 mb-1 p-0 col-lg-2 text-center">
       

      <div class="col-md-12">
        <h3 class="fs-5 fs-md-6">{{$order_status->name}} Order ({{$order_status->orders_count}})</h3> <!-- Bigger font size on mobile -->
      </div>
      <div class="col-md-12 mt-2">
        <a href="{{route('admin.order.whosalescreate')}}" class="btn btn-primary btn-lg w-100">
          <i class="fas fa-cart-plus cart-icon-small"></i> Add New
        </a>
      </div>
    </div>

    <div class="col-12 col-md-9 col-lg-10 m-0 px-0 ps-md-2">

        <div class="row mb-0 mb-md-1 px-0 d-flex justify-content-between flex-wrap ">
          
          <div class="col-6 col-md-auto mb-1 mb-md-0 ps-1 pe-2 ps-md-0 pe-md-0">
              <a data-bs-toggle="modal" data-bs-target="#changeStatus" class="btn btn-primary w-100">
                  <i class="fas fa-arrow-circle-up pe-1"></i>Status
              </a>
          </div>
          <div class="col-6 col-md-auto mb-1 mb-md-0 ps-2 pe-1 ps-md-0 pe-md-0">
              <button type="button" class="btn btn-primary w-100" onclick="printSelectedOrders()">
    <i class="fas fa-print pe-1"></i>Print
</button>
          </div>
          
      </div>

        <div class="row mb-1 ps-md-2  d-flex justify-content-between flex-wrap gap-0 gap-md-1">
          <form id="filter_form" action="{{ request()->url() }}" method="get" class="d-flex flex-wrap align-items-center gap-2">
              <!-- Date Dropdown -->
   <div class="col-6 col-md-auto mb-1 mb-md-0 ps-2 pe-1 ps-md-0 pe-md-0">
    <select name="date_filter" class="form-control w-100" onchange="this.form.submit()">
        <option value="">Select Date</option>
        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
        <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
    </select>
</div>
              
              <!-- Assign Dropdown -->
              <div class="col-6 col-md-auto mb-1 mb-md-0 ps-1 pe-2 ps-md-0 pe-md-0">
                  <select name="whosales_order" class="form-control w-100">
                      <option value="">Select Whosales</option>
                      @foreach($wholesaes as $key=>$value)
                          <option value="{{$value->id}}">{{$value->business_name}}</option>
                      @endforeach
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
             @php 
          $assigin =0;
          @endphp
            @foreach($show_data as $key=>$value)
     @php
        $product = optional(optional($value->orderdetails->first())->product);
       $productImage = optional($product->image)->image ?? 'default.jpg';
        $payadvance = $value->payment->first()->advance ?? null;
        $products = $value->orderdetails->map(function($orderDetail) {
            return [
                'id' => $orderDetail->product->id ?? '',
                'name' => $orderDetail->product->name ?? '',
                'size' => $orderDetail->product_size ?? '',
                'price' => $orderDetail->product->new_price ?? '',
                'image' => asset($orderDetail->product->image->image ?? 'default.jpg'),
            ];
        });
        if(!empty($value->shipping->district)){
        
          $cityname = \App\Models\City::where('id', $value->shipping->district)->first()->name;
        }else{
         $cityname = null;
        }
        
      $assigin = $value->user->id ?? null;
    @endphp
          <tr>
            <td class="action-icons-row">
                <span class="action-icons">
                  <input id="{{$value->id}}" type="checkbox" class="checkbox" value="{{$value->id}}" >
                
                  @if (auth()->user()->can('order-view'))
                 <a href="{{ route('admin.whosales.invoice', ['invoice_id' => $value->invoice_id]) }}" title="Invoice"> <i class="fas fa-eye"></i>  </a>
                 @endif
                  @if (auth()->user()->can('order-edit'))
                 <a href="{{route('admin.whosalesorder.edit',['invoice_id'=>$value->invoice_id])}}" title="Edit"> <i class="fas fa-pen"></i> </a>
                 @endif
                 
                 @if (auth()->user()->can('order-delete'))
                 <form method="post" action="{{route('admin.order.destroy')}}" class="d-inline">
                                        @csrf
                    <input type="hidden" value="{{$value->id}}" name="id">
                    <button type="submit" title="Delete" style="border:none;">
                             <i class="fas fa-trash"></i>
                             </button>
                             </form>
                             @endif
                              
                </span> </td>
            <td class="product-img">
              <img
                src="{{ asset($productImage) }}"
                alt="Product Image"
                class="img-fluid"
              />
            </td>
            <td class="stacked-text">
              <span>{{$value->invoice_id}}</span>
              <span>৳{{$value->amount}}</span>
              <span>{{$value->order_source}}</span>
            </td>
            <td class="stacked-text">
              <span>{{$value->shipping?$value->shipping->name:''}}</span>
              <span>
        @if($value->shipping && $value->shipping->phone)
            <a href="tel:{{ $value->shipping->phone }}">
                {{ $value->shipping->phone }}
            </a>
        @endif
    </span>
              <span>{{$product->product_code}}</span>
            </td>
            <td class="stacked-text">
              <span> {{ Str::limit(strip_tags($value->shipping?->address ?? ''), 30) }}</span>
              <span>{{$value->shipping?$value->shipping->area:''}}</span>
              <span>{{$cityname}} → {{ $value->shipping?->thana ?? '' }}</span>

              <!-- <p> <br> <br> </p> -->
            </td>
            <td class="stacked-text">
              <span>{{$value->courier}}</span>
              <span>@if ($value->courier == 'pathao')
        <a href="https://merchant.pathao.com/public-tracking?consignment_id={{ $value->tracking_id }}" target="_blank">Link</a>
    @elseif ($value->courier == 'steadfast')
        <a href="https://steadfast.com.bd/t/{{ $value->tracking_id }}" target="_blank">Link</a>
    @else
        <span>No Tracking Link</span>
    @endif</span>
              <span>@if($value->user) {{$value->user->name}} @else No User @endif</span>
            </td>
            <td class="stacked-text">
    <span>{{ date('d-m-Y', strtotime($value->updated_at)) }}</span>
    <span>{{ date('h:i A', strtotime($value->updated_at)) }}</span>
    
    <div class="dropdown">
        <button class="btn btn-sm dropdown-toggle text-white mt-1" type="button" 
                data-bs-toggle="dropdown" 
                style="background-color: {{ optional($value->status)->colorcode ?? '#6c757d' }}; font-size: 10px; padding: 2px 8px; border: none;">
            {{ optional($value->status)->name ?? 'No Status' }}
        </button>
        <ul class="dropdown-menu shadow">
            @foreach($orderstatus as $status)
            <li>
                <a class="dropdown-item single_status_update" 
                   data-id="{{ $value->id }}" 
                   data-status="{{ $status->id }}" 
                   href="javascript:void(0);">
                    {{ $status->name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</td>
            <td>
              <span> {{ Str::words($value->note ?? '', 50, '...') }}</span>
            </td>
          </tr>

          @endforeach

          <!-- Add more rows as needed -->
        </tbody>
      </table>
     {{-- <div class="custom-paginate">
                    {{$show_data->links('pagination::bootstrap-4')}}
                </div> --}}
    </div>


</div>


<!-- Assign User End -->
<div class="modal fade" id="asignUser" tabindex="-1" aria-labelledby="asignUserLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Assign User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('admin.order.assign')}}" id="order_assign">
      <div class="modal-body">
        <div class="form-group">
            <select name="user_id" id="user_id" class="form-control">
                <option value="">Select..</option>
                @foreach($users as $key=>$value)
                <option value="{{$value->id}}">{{$value->name}}</option>
                @endforeach
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Assign User End-->

<!-- Assign User End -->
<div class="modal fade" id="changeStatus" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('admin.order.status')}}" id="order_status_form">
      <div class="modal-body">
        <div class="form-group">
            <select name="order_status" id="order_status" class="form-control">
                <option value="">Select..</option>
                @foreach($orderstatus as $key=>$value)
                <option value="{{$value->id}}">{{$value->name}}</option>
                @endforeach
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="smsmodal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">SMS User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
          <form action="{{ route('admin.order.singlesms') }}" method="post">
             @csrf
             <input type="hidden" name="order_id" id="modal_order_id">
       <div class="modal-body">
    <div class="form-group mb-3">
        <label for="sms_template">Select Template</label>
        <select id="sms_template" class="form-select">
            <option value="">-- Select SMS Template --</option>
            @foreach($smsteamplate as $teamplate)
            <option value="{{$teamplate->smsteamplate}}">{{$teamplate->type}}</option>
            @endforeach
            
        </select>
    </div>

    <div class="form-group">
        <label for="sms_text">SMS Message</label>
        <textarea id="sms_text" class="form-control" rows="4" name="sms_text" placeholder="Message will appear here..."></textarea>
    </div>
</div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
</form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="multisms" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Multi Sms User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <form action="{{ route('admin.order.multisms') }}" method="post">
    @csrf
    <input type="hidden" name="banknumber[]" id="selectedBanknumbers" value="">
    <div class="modal-body">
        <div class="form-group mb-3">
            <label for="sms_template">Select Template</label>
            <select id="multisms_template" class="form-select" name="sms_template">
                <option value="">-- Select SMS Template --</option>
                @foreach($smsteamplate as $teamplate)
                    <option value="{{ $teamplate->smsteamplate }}">{{ $teamplate->type }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="sms_text">SMS Message</label>
            <textarea id="multisms_text" class="form-control" rows="4" name="sms_text" placeholder="Message will appear here..."></textarea>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</form>

            </div>
        </div>
    </div>

<div class="modal fade" id="courierModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pathao Courier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('admin.order.pathao')}}" id="order_sendto_pathao">
          <input type="hidden" name="selected_order_ids" id="selected_order_ids">
<input type="hidden" name="courier_type" id="courier_type">
      <div class="modal-body">

  {{-- Shown only when courier is pathao --}}
  <div class="pathao-fields">
    <div class="form-group">
      <label for="pathaostore" class="form-label">Store</label>
      <select name="pathaostore" id="pathaostore" class="form-control">
        <option value="">Select Store...</option>
        @if(isset($pathaostore['data']['data']))
          @foreach($pathaostore['data']['data'] as $store)
            <option value="{{ $store['store_id'] }}">{{ $store['store_name'] }}</option>
          @endforeach
        @endif
      </select>
    </div>

    <div class="form-group mt-3">
      <label for="pathaocity">City</label>
      <select name="pathaocity" id="pathaocity" class="chosen-select pathaocity form-control" style="width:100%">
        <option value="">Select City...</option>
        @if(isset($pathaocities['data']['data']))
          @foreach($pathaocities['data']['data'] as $city)
            <option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
          @endforeach
        @endif
      </select>
    </div>

    <div class="form-group mt-3">
      <label for="pathaozone">Zone</label>
      <select name="pathaozone" id="pathaozone" class="pathaozone chosen-select form-control  {{ $errors->has('pathaozone') ? ' is-invalid' : '' }}" value="{{ old('pathaozone') }}"  style="width:100%"></select>
    </div>

    <div class="form-group mt-3">
      <label for="pathaoarea">Area</label>
      <select name="pathaoarea" id="pathaoarea" class="pathaoarea chosen-select form-control  {{ $errors->has('pathaoarea') ? ' is-invalid' : '' }}" value="{{ old('pathaoarea') }}"  style="width:100%"></select>
    </div>
  </div>

  {{-- Shown only when courier is steadfast --}}
  <div class="steadfast-message" style="display: none;">
    <h5 class="text-center text-danger">Are you sure you want to send the selected orders to <strong>Steadfast Courier</strong>?</h5>
  </div>

</div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
      </form>
    </div>
  </div>

@endsection
  @section('script')
  <script>
$(document).ready(function() {
    // স্ট্যাটাস পরিবর্তনের ফাংশন
    $(document).on('click', '.single_status_update', function(e) {
        e.preventDefault();
        e.stopPropagation(); // এটি ড্রপডাউন বন্ধ করবে না, কিন্তু অন্য ইভেন্ট আটকাবে

        var id = $(this).data('id');
        var status = $(this).data('status');
        var url = "{{ route('admin.order.status') }}"; // আপনার রাউট
        
        var clickedButton = $(this).closest('.dropdown').find('.dropdown-toggle');

        $.ajax({
            type: "GET",
            url: url,
            data: { id: id, status: status },
            success: function(res) {
                if (res.status == 'success') {
                    toastr.success(res.message);
                    
                    // পেজ রিলোড না করে বাটনের নাম এবং রঙ আপডেট করা
                    if(res.new_name) {
                        clickedButton.text(res.new_name);
                    }
                    if(res.new_color) {
                        clickedButton.css('background-color', res.new_color);
                    }
                } else {
                    toastr.error(res.message);
                }
            },
            error: function() {
                toastr.error('Something went wrong!');
            }
        });
    });
});
</script>
 @if(session('courier_results'))
    <script>
        @foreach(session('courier_results') as $result)
            @if($result['status'] === 'success')
                toastr.success("Order #{{ $result['order_id'] }} — Tracking ID: {{ $result['tracking_id'] }}");
            @else
                toastr.error("Order #{{ $result['order_id'] }} — {{ $result['message'] }}");
            @endif
        @endforeach
    </script>
@endif
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script>
// Global variable to hold DataTable instance
let dataTable;

// Multi SMS Template functionality
document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('multisms_template');
    const smsTextArea = document.getElementById('multisms_text');

    if (templateSelect && smsTextArea) {
        templateSelect.addEventListener('change', function () {
            smsTextArea.value = this.value;
        });
    }

    // SMS Modal functionality
    const smsModal = document.getElementById('smsmodal');
    if (smsModal) {
        smsModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const customerName = button.getAttribute('data-customer-name');
            const orderId = button.getAttribute('data-order-id');

            const modalCustomerName = document.getElementById('modal_customer_name');
            const modalOrderId = document.getElementById('modal_order_id');
            
            if (modalCustomerName) modalCustomerName.value = customerName;
            if (modalOrderId) modalOrderId.value = orderId;
        });
    }
});

// Update selected banknumbers function
function updateSelectedBanknumbers() {
    const checkedIds = [];
    if (dataTable) {
        dataTable.$('input.checkbox:checked').each(function() {
            checkedIds.push(this.id);
        });
    } else {
        $('input.checkbox:checked').each(function() {
            checkedIds.push(this.id);
        });
    }
    const orderIdJSON = JSON.stringify(checkedIds);
    $('#selectedBanknumbers').val(orderIdJSON);
    console.log("Selected Banknumbers:", orderIdJSON);
}

// Check all functionality
function checkClickFunc(e) {
    if ($('#checkAll').is(':checked')) {
        if (dataTable) {
            dataTable.$('input[type=checkbox]').prop('checked', true);
        } else {
            $('input[type=checkbox]').prop('checked', true);
        }
        $('.dude').removeAttr('style');
    } else {
        if (dataTable) {
            dataTable.$('input[type=checkbox]').prop('checked', false);
        } else {
            $('input[type=checkbox]').prop('checked', false);
        }
        $('.dude').attr('style', 'display:none');
    }
    updateSelectedBanknumbers();
}

// Export button functionality
document.getElementById('export-btn').addEventListener('click', function () {
    let selectedIds = [];
    if (dataTable) {
        dataTable.$('.checkbox:checked').each(function() {
            selectedIds.push(this.value);
        });
    } else {
        document.querySelectorAll('.checkbox:checked').forEach(function (checkbox) {
            selectedIds.push(checkbox.value);
        });
    }

    if (selectedIds.length === 0) {
        alert('Please select at least one order.');
        return;
    }

    let url = `{{ route('admin.order.order_csv') }}?ids=${selectedIds.join(',')}`;
    window.location.href = url;
});

// Print selected orders function
function printSelectedOrders() {
    let selectedOrderIds = [];
    if (dataTable) {
        dataTable.$('.checkbox:checked').each(function() {
            selectedOrderIds.push(this.value);
        });
    } else {
        document.querySelectorAll('.checkbox:checked').forEach((checkbox) => {
            selectedOrderIds.push(checkbox.value);
        });
    }

    if (selectedOrderIds.length === 0) {
        alert("Please select at least one order.");
        return;
    }

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

// Courier modal functionality
$('.openCourierModal').on('click', function () {
    const courier = $(this).data('courier');
    $('#courier_type').val(courier);

    let selected = [];
    if (dataTable) {
        dataTable.$('.checkbox:checked').each(function() {
            selected.push($(this).val());
        });
    } else {
        selected = $('.checkbox:checked').map(function () {
            return $(this).val();
        }).get();
    }

    if (selected.length === 0) {
        alert('Please select at least one order to assign.');
        $('#courierModal').modal('hide');
        return false;
    }

    $('#selected_order_ids').val(selected.join(','));

    let title = '';
    switch (courier) {
        case 'pathao':
            title = 'Pathao Courier';
            $('.pathao-fields').show();
            $('.steadfast-message').hide();
            break;
        case 'steadfast':
            title = 'Steadfast Courier';
            $('.pathao-fields').hide();
            $('.steadfast-message').show();
            break;
        case 'redx':
            title = 'Redx Courier';
            $('.pathao-fields').hide();
            $('.steadfast-message').hide();
            break;
        default:
            title = 'Courier Service';
            $('.pathao-fields').hide();
            $('.steadfast-message').hide();
    }

    $('.modal-title').text(title);
});

// JSON data from server
const users = @json($users);
const orderAssign = @json($assigin);
const orderstutas = @json($orderstatus);
const cityms = @json($cityg) || [];
const allProducts = @json($producthf) || [];
const productsArray = Array.isArray(allProducts) ? allProducts : Object.values(allProducts);
const validProducts = productsArray.filter(product => product && product.id);

console.log(productsArray);

$(document).ready(function() {
   
    
    // Initialize DataTable
    dataTable = $('#datatable-buttons').DataTable({
        "pageLength": 20,
        "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "All"]],
        "order": [],
        "columnDefs": [
            {
                "orderable": false,
                "targets": 0
            }
        ],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Search:",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            },
            "info": "Showing _START_ to _END_ of _TOTAL_ entries"
        },
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        "responsive": true,
        "autoWidth": false
    });

    // Check All functionality with DataTable support
    $('#checkAll').on('click', function() {
        const isChecked = $(this).is(':checked');
        dataTable.$('input.checkbox').prop('checked', isChecked);
        
        if(isChecked) {
            $('.dude').removeAttr('style'); 
        } else {
            $('.dude').attr('style','display:none'); 
        }
        updateSelectedBanknumbers();
    });

    // Individual checkbox changes
    $(document).on('change', '.checkbox', function() {
        updateSelectedBanknumbers();
        
        const anyChecked = dataTable.$('input.checkbox:checked').length > 0;
        if(anyChecked) {
            $('.dude').removeAttr('style');
        } else {
            $('.dude').attr('style','display:none');
        }
    });

    // Dropdown accordion functionality
    $(document).on("click", ".dropdown", function () {
        const row = $(this).closest("tr");
        const nextRow = row.next(".accordion-row");

        $(".accordion-row").remove();

        if (nextRow.length === 0) {
            const customername = row.data("customer-name");
            const customerphone = row.data("customer-phone");
            const customeraddress = row.data("customer-address"); 
            const customerId = row.data("customer-id"); 
            const products = row.data("products");
            const orderactivestatus = row.data("order-status");
            const orderadvance = row.data("order-advance");
            const orderId = row.data("order-id");
            const customerdistrict = row.data("customer-district");
            const customerthana = row.data("customer-thana");
            const notes = row.data("order-notes");

            let total = 0;
            console.log(products);

            const accordionRow = $("<tr>").addClass("accordion-row");
            const accordionCell = $("<td>").attr("colspan", 9).html(`
                <div class="accordion-content">
                    <div class="left-panel">
                        <div class="form-grid-first-row">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" id="customer_name" value="">
                            </div>
                            <div class="form-group">
                                <label>Delivery Partner</label>
                                <select id="delivery_partner">
                                    <option value="steadfast">Steadfast</option>
                                    <option value="pathao">Pathao</option>
                                    <option value="redx">Redx</option>
                                    <option value="e-courier">e-Courier</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Assign</label>
                                <select id="assign">
                                ${users.map(user => `
                                    <option value="${user.id}" ${user.id === orderAssign ? 'selected' : ''}>${user.name}</option>
                                `).join('')}
                                </select>
                            </div>
                        </div>
                        <div class="form-grid-2nd-row">
                            <div class="left-section">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" id="phone_number" value="">
                                </div>
                                <div class="form-group">
                                    <label>Address District</label>
                                    <select id="address_district" class="select2" style="width: 100%;">
                                    <option>Select A City</option>
                                    ${cityms.map(citym => `
                                        <option value="${citym.id}" ${citym.id === customerdistrict ? 'selected' : ''}>${citym.name}</option>
                                    `).join('')}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Order Status</label>
                                    <select id="order_status">
                                    ${orderstutas.map(orderstuta => `
                                        <option value="${orderstuta.id}" ${orderstuta.id === orderactivestatus ? 'selected' : ''}>${orderstuta.name}</option>
                                    `).join('')}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Address Thana</label>
                                    <select id="address_thana" class="select2" style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                            <div class="right-section">
                                <div class="form-group">
                                    <label>Add Comment</label>
                                    <textarea id="comment" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group" style="width:100%;">
                                <label>Address</label>
                                <input type="text" id="address_info" value="Tejgaon">
                            </div>
                        </div>
                        <div class="order-summary">
                            Delivered: <span id="succdelivery">0</span> | Cancelled: <span id="canceldel">0</span> | Delivery Success Rate: <span id="ratingsuccse"></span>
                        </div>
                        <button class="update-btn" id="saveBtn">Save</button>
                    </div>
                    <div class="right-panel">
                        <select id="add_product" class="select2" style="width: 100%;">
                        ${validProducts.map(producthf => `
                            <option value="${producthf.id}" 
                                data-name="${producthf.name}" 
                                data-size="" 
                                data-price="${producthf.new_price}" 
                                data-image="">
                                ${producthf.name}
                            </option>
                        `).join('')}
                        </select>
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Sell Price</th>
                                    <th>Discount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${products.map(product => {
                                    total += product.price;
                                    return `
                                        <tr>
                                            <td><img src="${product.image}" alt="" width="50"></td>
                                            <td>${product.name}</td>
                                            <td><input type="text" value="${product.size}" style="width:50px;"></td>
                                            <td>${product.price}</td>
                                            <td><input type="text" name="" style="width:50px;"></td>
                                            <td><button class="delete-btn" data-product-id="${product.id}">X</button></td>
                                            <input type="hidden" name="product_ids[]" value="${product.id}" />
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                        <div class="totals">
                            <span>Subtotal</span>
                            <span>Delivery Charge</span>
                            <span>Total</span>
                            <span>Advance</span>
                            <span>Due</span>
                        </div>
                        <div class="total-values">
                            <span id="sub">${total}</span>
                            <input id="delivery_charge" type="text" value="50">
                            <span id="maintotal">${total + 50}</span>
                            <span id="order_advancse"></span>
                            <span id="dueamount"></span>
                        </div>
                    </div>
                </div>
            `);
            
            accordionRow.append(accordionCell);
            row.after(accordionRow);
            
            $('.select2').select2();
            $('#customer_name').val(customername);
            $('#phone_number').val(customerphone);
            $('#address_info').val(customeraddress);
            $('#address_district').val(customerdistrict);
            $('#address_thana').val(customerthana);
            $('#order_advancse').val(orderadvance);
            $('#comment').val(notes);

            // Add product functionality
            $('#add_product').change(function() {
                const selectedOption = $(this).find('option:selected');
                const productId = selectedOption.val();
                const productName = selectedOption.data('name');
                const productSize = selectedOption.data('size');
                const productPrice = selectedOption.data('price');
                const productImage = selectedOption.data('image');

                const newRow = `
                    <tr>
                        <td><img src="${productImage}" alt="" width="50"></td>
                        <td>${productName}</td>
                        <td><input type="text" name="size_pro" value="${productSize}" style="width:50px;"></td>
                        <td>${productPrice}</td>
                        <td><input type="text" name="" style="width:50px;"></td>
                        <td><button class="delete-btn" data-product-id="${productId}">X</button></td>
                        <input type="hidden" name="product_ids[]" value="${productId}" />
                    </tr>
                `;

                $('.product-table tbody').append(newRow);
                updateTotal();
            });

            // Delete product functionality
            $(document).on('click', '.delete-btn', function() {
                const $row = $(this).closest('tr');
                const productId = $(this).data('product-id');
                const ordermyId = row.data("order-id");
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
            
                $.ajax({
                    url: '{{ route('remove-product-from') }}',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        ordersId: ordermyId,
                        _token: csrfToken
                    },
                    success: function (response) {
                        $row.remove();
                        updateTotal();
                    },
                    error: function (error) {
                        alert("Error removing product from order.");
                    }
                });
            });

            // Update total function
            function updateTotal() {
                let total = 0;
                $('.product-table tbody tr').each(function() {
                    const price = parseFloat($(this).find('td:nth-child(4)').text()) || 0;
                    total += price;
                });
                $('#sub').text(total);
                let deliveryCharge = parseFloat($('#delivery_charge').val()) || 50;
                $('#maintotal').text(total + deliveryCharge);
                let orderadvance = parseFloat($('#order_advancse').text())||0;
                $('#dueamount').text(total + deliveryCharge - orderadvance);
            }

            $('#delivery_charge').on('input', updateTotal);
            $('#order_advancse').on('input', updateTotal);

            // Fraud check AJAX
            $.ajax({
                url: '/admin/fraud-check/combined',
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({
                    number: customerphone
                }),
                success: function(response) {
                    console.log(response);
                    const steadfast = response.providers.steadfast;
                    if (steadfast?.success && steadfast.data) {
                        $('#succdelivery').text(steadfast.data.total_delivered);
                        $('#canceldel').text(steadfast.data.total_cancelled);
                        let successRate = 0;
                        if (steadfast.data.total_cancelled !== 0) {
                            successRate = (steadfast.data.total_delivered / steadfast.data.total_parcels) * 100;
                        } else {
                            successRate = 100;
                        }
                        $('#ratingsuccse').text(successRate.toFixed(2) + '%');
                    } else {
                        console.error('Missing expected properties: total_delivered or total_cancel');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching delivery data: ', error);
                    if (xhr.status === 0) {
                        alert('Network error: Unable to reach the server. Please check your connection.');
                    } else if (xhr.status >= 500) {
                        alert('Server error: Please try again later.');
                    } else if (xhr.status === 404) {
                        alert('Resource not found.');
                    } else {
                        alert('Error: ' + xhr.status + ' - ' + error);
                    }
                }
            });

            // District change handler
            $('#address_district').on('change', function () {
                const districtId = $(this).val();
                if (!districtId) return;

                $.ajax({
                    url: '{{ route('get-thana-by-district') }}',
                    type: 'GET',
                    data: { district_id: districtId },
                    success: function (response) {
                        const thanaSelect = $('#address_thana');
                        thanaSelect.empty();
                        thanaSelect.append(`<option value="">Select a thana</option>`);
                        response.forEach(function (thana) {
                            thanaSelect.append(`<option value="${thana.name}" ${thana.name === customerthana ? 'selected' : ''}>${thana.name}</option>`);
                        });
                        thanaSelect.trigger('change');
                    },
                    error: function (xhr, status, error) {
                        alert('Failed to load areas (thana).');
                    }
                });
            });

            // Save button functionality
            $('#saveBtn').on('click', function() {
                const customerName = $('#customer_name').val();
                const deliveryPartner = $('#delivery_partner').val();
                const assign = $('#assign').val();
                const phoneNumber = $('#phone_number').val();
                const addressDistrict = $('#address_district').val();
                const orderStatus = $('#order_status').val();
                const addressThana = $('#address_thana').val();
                const address_info = $('#address_info').val();
                const comment = $('#comment').val();
                const delivery_charge = $('#delivery_charge').val();

                const productData = [];
                $(".product-table tbody tr").each(function() {
                    const product = {
                        id: $(this).find("input[type='hidden']").val(),
                        name: $(this).find("td:nth-child(2)").text(),
                        size: $(this).find("td:nth-child(3) input").val(),
                        sellPrice: $(this).find("td:nth-child(4)").text(),
                        discount: $(this).find("td:nth-child(5) input").val(),
                        subTotal: $(this).find("td:nth-child(6)").text()
                    };
                    productData.push(product);
                });

                $.ajax({
                    url: '{{ route('testsave-order') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        customerName,
                        deliveryPartner,
                        customerId,
                        orderId,
                        assign,
                        phoneNumber,
                        addressDistrict,
                        orderStatus,
                        addressThana,
                        address_info,
                        delivery_charge,
                        comment,
                        products: productData
                    },
                    success: function(response) {
                        alert('Data saved successfully!');
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Error saving data: ' + error);
                    }
                });
            });
        }
    });

    // Check all functionality
    $(".checkall").on('change', function(){
        $(".checkbox").prop('checked', $(this).is(":checked"));
    });

    // Order assign
    $(document).on('submit', 'form#order_assign', function(e){
        e.preventDefault();
        const url = $(this).attr('action');
        const user_id = $(document).find('select#user_id').val();

        let order_ids = [];
        dataTable.$('input.checkbox:checked').each(function() {
            order_ids.push($(this).val());
        });

        if(order_ids.length == 0){
            toastr.error('Please Select An Order First !');
            return;
        }

        $.ajax({
            type: 'GET',
            url: url,
            data: {user_id, order_ids},
            success: function(res){
                if(res.status == 'success'){
                    toastr.success(res.message);
                    window.location.reload();
                } else {
                    toastr.error('Failed something wrong');
                }
            }
        });
    });

    // Order status change
    $(document).on('submit', 'form#order_status_form', function(e){
        e.preventDefault();
        const url = $(this).attr('action');
        const order_status = $(document).find('select#order_status').val();

        let order_ids = [];
        dataTable.$('input.checkbox:checked').each(function() {
            order_ids.push($(this).val());
        });

        if(order_ids.length == 0){
            toastr.error('Please Select An Order First !');
            return;
        }

        $.ajax({
            type: 'GET',
            url: url,
            data: {order_status, order_ids},
            success: function(res){
                if(res.status == 'success'){
                    toastr.success(res.message);
                    window.location.reload();
                } else {
                    toastr.error('Failed something wrong');
                }
            }
        });
    });

    // Order delete
    $(document).on('click', '.order_delete', function(e){
        e.preventDefault();
        const url = $(this).attr('href');
        
        let order_ids = [];
        dataTable.$('input.checkbox:checked').each(function() {
            order_ids.push($(this).val());
        });

        if(order_ids.length == 0){
            toastr.error('Please Select An Order First !');
            return;
        }

        $.ajax({
            type: 'GET',
            url: url,
            data: {order_ids},
            success: function(res){
                if(res.status == 'success'){
                    toastr.success(res.message);
                    window.location.reload();
                } else {
                    toastr.error('Failed something wrong');
                }
            }
        });
    });

    // Multiple print
    $(document).on('click', '.multi_order_print', function(e){
        e.preventDefault();
        const url = $(this).attr('href');
        
        let order_ids = [];
        dataTable.$('input.checkbox:checked').each(function() {
            order_ids.push($(this).val());
        });

        if(order_ids.length == 0){
            toastr.error('Please Select Atleast One Order!');
            return;
        }

        $.ajax({
            type: 'GET',
            url: url,
            data: {order_ids},
            success: function(res){
                if(res.status == 'success'){
                    console.log(res.items, res.info);
                    const myWindow = window.open("", "_blank");
                    myWindow.document.write(res.view);
                } else {
                    toastr.error('Failed something wrong');
                }
            }
        });
    });

    // CSV download
    $(document).on('click', '.multi_order_csv', function(e){
        e.preventDefault();
        const url = $(this).attr('href');
        
        let order_ids = [];
        dataTable.$('input.checkbox:checked').each(function() {
            order_ids.push($(this).val());
        });

        if(order_ids.length == 0){
            toastr.error('Please Select Atleast One Order!');
            return;
        }

        $.ajax({
            type: 'GET',
            url: url,
            data: {order_ids},
            success: function(res){
                if(res.status == 'success'){
                    const csvUrl = "/public/csv/orders.csv";
                    window.location.href = csvUrl;
                } else {
                    toastr.error('Failed something wrong');
                }
            }
        });
    });

    // Multiple courier
    $(document).on('click', '.multi_order_courier', function(e){
        e.preventDefault();
        const url = $(this).attr('href');
        
        let order_ids = [];
        dataTable.$('input.checkbox:checked').each(function() {
            order_ids.push($(this).val());
        });

        if(order_ids.length == 0){
            toastr.error('Please Select An Order First !');
            return;
        }

        $.ajax({
            type: 'GET',
            url: url,
            data: {order_ids},
            success: function(res){
                if(res.status == 'success'){
                    toastr.success(res.message);
                    window.location.reload();
                } else {
                    toastr.error('Failed something wrong');
                }
            }
        });
    });
});
</script>
@endsection