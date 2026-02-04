@extends('wholesaler.master')
@section('title', 'Wholease')
@section('content')
@section('css')
   
    <style>
        .invoice-table th,
      .invoice-table td {
        text-align: center;
        vertical-align: middle;
      }
      .profile-img {
        width: 100%;
        max-width: 140px;
        border-radius: 10px;
      }
      .qr-img {
        width: 100%;
        max-width: 140px;
      }
      .details p {
        margin-bottom: 5px;
        font-size: 14px;
      }
      .dashboard-card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease-in-out;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }

      .dashboard-card:hover {
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
      }

      .dashboard-card svg {
        width: 40px;
        height: 40px;
      }

      .dashboard-card h6 {
        margin-top: 10px;
        font-weight: 600;
      }

      .dashboard-card h4 {
        margin-bottom: 0;
        font-weight: bold;
      }
    </style>
@endsection

<div class="container mt-4">
    <div class="row">
        

        <!-- Right Column -->
        <div class="col-md-12">
            <!-- 5 Cards in One Row -->
            <div class="row row-cols-2 row-cols-md-5 g-3 mb-4">
                <!-- Card 1: Invoice Count -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"
                            class="text-primary mb-2">
                            <path
                                d="m670-140 160-100-160-100v200ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                        </svg>
                        <h6>Invoice</h6>
                        <h4>{{ $totalinvoice }}</h4>
                    </div>
                </div>

                <!-- Card 2: Total Order -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px"
                            fill="currentColor" class="text-primary">
                            <path
                                d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                        </svg>
                        <h6>Total Order</h6>
                        <h4>{{ number_format($totalOrderAmount ?? 0) }}/-</h4>
                        <small>{{ number_format($totalOrderItems ?? 0) }} pcs</small>
                    </div>
                </div>

                <!-- Card 3: Return -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px"
                            fill="currentColor" class="text-primary">
                            <path
                                d="m480-320 56-56-63-64h167v-80H473l63-64-56-56-160 160 160 160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm280-590q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790ZM200-200v-560 560Z" />
                        </svg>
                        <h6>Return</h6>
                        <h4>{{ number_format($totalReturnAmount ?? 0) }}/-</h4>
                        <small>{{ number_format($totalReturnItems ?? 0) }} pcs</small>
                    </div>
                </div>

                <!-- Card 4: Total Paid -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px"
                            fill="currentColor" class="text-primary">
                            <path
                                d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                        </svg>
                        <h6>Total Paid</h6>
                        <h4>{{ number_format($totalPaid ?? 0) }}/-</h4>
                    </div>
                </div> 

                <!-- Card 5: Total Due -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px"
                            fill="currentColor" class="text-primary">
                            <path
                                d="M480-320 640-480 480-640l-56 56 63 64H320v80h167l-63 64 56 56Zm280 200H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120ZM200-200h560v-560H200v560Zm280-590q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790Z" />
                        </svg>
                        @if (($totalDue ?? 0) < 0)
                        <h6>Advance</h6>
                        <h4>{{ number_format(abs($totalDue)) }}/-</h4>
                    @else
                        <h6>Total Due</h6>
                        <h4>{{ number_format($totalDue ?? 0) }}/-</h4>
                    @endif
                    </div>
                </div>
            </div>

            <!-- Recent Order Invoices Table -->
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Header Line with Title Left, All Invoice + 3 Dots Right -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Recent Order Invoices</h6>
                        <div class="d-flex align-items-center gap-2">
                            <span>All Invoice</span>
                            <span
                                style="
                      font-size: 24px;
                      cursor: pointer;
                      user-select: none;
                      line-height: 1;
                    ">
                                &#8942;
                            </span>
                        </div>
                    </div>

                    <!-- Table -->
                    <table class="table table-bordered table-sm invoice-table mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>SL</th>
                                <th>Invoice</th>
                                <th>Assigning</th>
                                <th>Status</th>
                                <th>Item Qty</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($show_data as $key => $order)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusBadge = 'secondary';
                                        $statusText = 'Unknown';
                                        
                                        switch($order->order_status) {
                                            case 1:
                                                $statusBadge = 'warning';
                                                $statusText = 'Pending';
                                                break;
                                            case 2:
                                                $statusBadge = 'info';
                                                $statusText = 'Processing';
                                                break;
                                            case 3:
                                                $statusBadge = 'primary';
                                                $statusText = 'Shipped';
                                                break;
                                            case 4:
                                                $statusBadge = 'success';
                                                $statusText = 'Delivered';
                                                break;
                                            case 5:
                                                $statusBadge = 'danger';
                                                $statusText = 'Returned';
                                                break;
                                            case 6:
                                                $statusBadge = 'dark';
                                                $statusText = 'Cancelled';
                                                break;
                                            default:
                                                $statusBadge = 'secondary';
                                                $statusText = 'Unknown';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $statusBadge }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>{{ $order->orderdetails->sum('qty') }}</td>
                                <td>{{ number_format($order->amount) }}</td>
                                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Return Invoices Table -->
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Header Line -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Recent Return Invoices</h6>
                        <div class="d-flex align-items-center gap-2">
                            <span>All Return Invoice</span>
                            <span
                                style="
                      font-size: 24px;
                      cursor: pointer;
                      user-select: none;
                      line-height: 1;
                    ">
                                &#8942;
                            </span>
                        </div>
                    </div>

                    <!-- Table -->
                    <table class="table table-bordered table-sm invoice-table mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>SL</th>
                                <th>Invoice</th>
                                <th>Assigning</th>
                                <th>Status</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($return_data as $key => $return)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $return->id }}</td>
                                <td>{{ $return->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-danger">
                                        Returned
                                    </span>
                                </td>
                                <td>{{ $return->orderdetails->sum('qty') }}</td>
                                <td>{{ number_format($return->amount) }}</td>
                                <td>{{ $return->created_at->format('d-m-Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No return orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Row for SellReport -->
    <div class="card mb-4">
        <div class="card-body">
            <!-- Header Row -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">SellReport</h6>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" style="width: 120px">
                        <option selected>All Time</option>
                        <option value="1">Last 7 Days</option>
                        <option value="2">Last 30 Days</option>
                        <option value="3">This Year</option>
                    </select>
                    <span
                        style="
                  font-size: 24px;
                  cursor: pointer;
                  user-select: none;
                  line-height: 1;
                ">
                        &#8942;
                    </span>
                </div>
            </div>

            <!-- SellReport Table -->
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Order</th>
                        <th>Return</th>
                        <th>Net Qty</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellReport as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>
                            <img src="{{ $item->image ? asset($item->image) : asset('public/image/apple.jpg') }}" 
                                 alt="Product Image"
                                 style="height: 40px; width: 40px; object-fit: cover" 
                                 class="rounded" />
                        </td>
                        <td>{{ $item->product_code ?? 'N/A' }}</td>
                        <td>{{ $item->total_ordered }}</td>
                        <td>{{ $item->total_returned }}</td>
                        <td>{{ $item->net_quantity }}</td>
                        <td>{{ number_format($item->total_revenue) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No sales data found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection