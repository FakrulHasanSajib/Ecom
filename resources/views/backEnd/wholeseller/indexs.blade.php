@extends('backEnd.layouts.master')
@section('title', 'Product Order Report')
@section('content')
@include('backEnd.layouts.b2b_menu')
@section('css')
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      rel="stylesheet"
    />
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
        <!-- Left Column -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <!-- Row with Image and QR Code -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <img src="image/ab.jpg" alt="Profile" class="profile-img img-thumbnail" />
                        </div>
                        <div class="col-6">
                            <img src="image/qrcode.png" alt="QR Code" class="qr-img img-thumbnail" />
                        </div>
                    </div>

                    <!-- Wholeseller Details with Two Columns -->
                    <div class="details">
                        <div class="row">
                            <div class="col-6"><strong>Account Type:</strong></div>
                            <div class="col-6">Wholesaler</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Name:</strong></div>
                            <div class="col-6">MD ALAMIN</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Mobile:</strong></div>
                            <div class="col-6">+8801723546492</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Business Name:</strong></div>
                            <div class="col-6">AMR SHOP</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Email:</strong></div>
                            <div class="col-6">info@amrshop.com.bd</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Website:</strong></div>
                            <div class="col-6">amrshop.com.bd</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Facebook:</strong></div>
                            <div class="col-6">fb.com/amrshopbd20</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Payment Method:</strong></div>
                            <div class="col-6">Bank</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Bank Name:</strong></div>
                            <div class="col-6">Dutch Bangla Bank</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Account Name:</strong></div>
                            <div class="col-6">MD ALAMIN BEPARY</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Account Number:</strong></div>
                            <div class="col-6">10101011100000</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Branch Name:</strong></div>
                            <div class="col-6">Tejgaon</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Routing Number:</strong></div>
                            <div class="col-6">89585555</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Address:</strong></div>
                            <div class="col-6">
                                362 East Nakhalpara, Tejgaon, Dhaka, 1215
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-8">
            <!-- 5 Cards in One Row -->
            <div class="row row-cols-2 row-cols-md-5 g-3 mb-4">
                <!-- Card 1 -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"
                            class="text-primary mb-2">
                            <path
                                d="m670-140 160-100-160-100v200ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                        </svg>
                        <h6>Invoice</h6>
                        <h4>50</h4>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px"
                            fill="currentColor" class="text-primary">
                            <path
                                d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                        </svg>
                        <h6>Total Order</h6>
                        <h4>100100/-</h4>
                        <small>1250 pcs</small>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px"
                            fill="currentColor" class="text-primary">
                            <path
                                d="m480-320 56-56-63-64h167v-80H473l63-64-56-56-160 160 160 160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm280-590q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790ZM200-200v-560 560Z" />
                        </svg>
                        <h6>Return</h6>
                        <h4>10100/-</h4>
                        <small>125 pcs</small>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px"
                            fill="currentColor" class="text-primary">
                            <path
                                d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                        </svg>
                        <h6>Total Paid</h6>
                        <h4>8500/-</h4>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="col">
                    <div class="card dashboard-card text-center p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px"
                            fill="currentColor" class="text-primary">
                            <path
                                d="M480-320 640-480 480-640l-56 56 63 64H320v80h167l-63 64 56 56Zm280 200H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120ZM200-200h560v-560H200v560Zm280-590q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790Z" />
                        </svg>

                        <h6>Total Due</h6>
                        <h4>5000/-</h4>
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
                            <tr>
                                <td>1</td>
                                <td>1051</td>
                                <td>Alamin</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>25</td>
                                <td>16250</td>
                                <td>08-03-2025</td>
                            </tr>
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
                            <tr>
                                <td>1</td>
                                <td>1054</td>
                                <td>Alamin</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>5</td>
                                <td>3560</td>
                                <td>08-03-2025</td>
                            </tr>
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
                        <th>RR</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Example Product</td>
                        <td>
                            <img src="image/apple.jpg" alt="Product Image"
                                style="height: 40px; width: 40px; object-fit: cover" class="rounded" />
                        </td>
                        <td>SKU12345</td>
                        <td>100</td>
                        <td>5</td>
                        <td>95</td>
                        <td>15000</td>
                    </tr>
                    <!-- More rows as needed -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
