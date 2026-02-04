@extends('backEnd.layouts.master')
@section('title', 'Author Orders & Royalty')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    .summary-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .summary-card h3 {
        font-size: 2rem;
        margin-bottom: 5px;
    }
    .summary-card p {
        opacity: 0.9;
        margin: 0;
    }
    .product-summary-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        transition: box-shadow 0.3s;
    }
    .product-summary-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .product-img-small {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
    }
    .royalty-badge {
        background: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    .table-responsive {
        background: white;
        padding: 20px;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <h3>৳{{ number_format($totalRoyalty, 2) }}</h3>
                <p>Total Royalty Income</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3>{{ $totalQuantity }}</h3>
                <p>Total Products Sold</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h3>{{ $totalOrders }}</h3>
                <p>Total Orders</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <h3>{{ $productSummary->count() }}</h3>
                <p>Products Ordered</p>
            </div>
        </div>
    </div>

    <!-- Product Summary Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">Product-wise Summary</h4>
            <div class="row">
                @foreach($productSummary as $summary)
                @php
                    $product = $summary->product;
                    $royaltyPerUnit = $product->loyalty ?? 0;
                    $totalProductRoyalty = $royaltyPerUnit * $summary->total_quantity;
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="product-summary-card">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset(optional($product->image)->image ?? 'default.jpg') }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-img-small me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($product->name, 40) }}</h6>
                                <p class="mb-1 text-muted small">Code: {{ $product->product_code }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">Qty: {{ $summary->total_quantity }}</span>
                                    <span class="royalty-badge">৳{{ number_format($totalProductRoyalty, 2) }}</span>
                                </div>
                                <small class="text-muted">Royalty: ৳{{ $royaltyPerUnit }}/unit</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Detailed Orders Table -->
    <div class="table-responsive">
        <h4 class="mb-3">All Orders Details</h4>
        <table id="ordersTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Product Details</th>
                    <th>Customer Info</th>
                    <th>Quantity</th>
                    <th>Royalty/Unit</th>
                    <th>Total Royalty</th>
                    <th>Order Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                $grandTotalRoyalty=0;
                @endphp
                @foreach($orderDetails as $detail)
                @php
                    $product = $detail->product;
                    $order = $detail->order;
                    $shipping = optional($order)->shipping;
                    $royaltyPerUnit = optional($product)->loyalty ?? 0;
                    $totalRoyalty = $royaltyPerUnit * $detail->qty;
                    $grandTotalRoyalty += $totalRoyalty;
                @endphp
                <tr>
                    <td>
                        <strong>{{ optional($order)->invoice_id }}</strong>
                    </td>
                    <td>
                        <img src="{{ asset(optional($product->image)->image ?? 'default.jpg') }}" 
                             alt="{{ optional($product)->name }}" 
                             width="50" 
                             class="img-thumbnail">
                    </td>
                    <td>
                        <strong>{{ Str::limit(optional($product)->name, 40) }}</strong><br>
                        <small class="text-muted">Code: {{ optional($product)->product_code }}</small><br>
                        @if($detail->product_size)
                        <small>Size: {{ $detail->product_size }}</small>
                        @endif
                    </td>
                    <td>
                        <strong>{{ optional($shipping)->name ?? 'N/A' }}</strong><br>
                        @if(optional($shipping)->phone)
                        <a href="tel:{{ $shipping->phone }}">
                            <i class="fas fa-phone"></i> {{ $shipping->phone }}
                        </a><br>
                        @endif
                        <small class="text-muted">
                            {{ Str::limit(optional($shipping)->address, 30) }}
                        </small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info">{{ $detail->qty }}</span>
                    </td>
                    <td class="text-end">
                        ৳{{ number_format($royaltyPerUnit, 2) }}
                    </td>
                    <td class="text-end">
                        <strong class="text-success">৳{{ number_format($totalRoyalty, 2) }}</strong>
                    </td>
                    <td>
                        <span class="badge" style="background-color: {{ optional($order->status)->colorcode ?? '#6c757d' }}">
                            {{ optional($order->status)->name ?? 'Unknown' }}
                        </span>
                    </td>
                    <td>
                        {{ $detail->created_at->format('d M Y') }}<br>
                        <small class="text-muted">{{ $detail->created_at->format('h:i A') }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-success">
                    <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                    <td class="text-center"><strong>{{ $totalQuantity }}</strong></td>
                    <td></td>
                    <td class="text-end"><strong>৳{{ number_format($grandTotalRoyalty, 2) }}</strong></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#ordersTable').DataTable({
        "pageLength": 25,
        "order": [[8, "desc"]], // Sort by date descending
        "language": {
            "search": "Search Orders:",
            "lengthMenu": "Show _MENU_ orders per page"
        }
    });
});
</script>
@endsection