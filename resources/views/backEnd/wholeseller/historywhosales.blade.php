@extends('backEnd.layouts.master')
@section('title', 'Wholesaler Payment Summary - ' . $edit_data->business_name)
@section('content')
@include('backEnd.layouts.b2b_menu')


<style>
.summary-card {
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.summary-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 2rem;
    opacity: 0.8;
}

.text-success { color: #28a745 !important; }
.text-danger { color: #dc3545 !important; }
.text-warning { color: #ffc107 !important; }
.text-info { color: #17a2b8 !important; }
.text-primary { color: #007bff !important; }

.payment-history-table {
    font-size: 0.9rem;
}

.badge-payment {
    padding: 0.5em 0.75em;
    border-radius: 10px;
}
</style>

<div class="container-fluid">
    <!-- Wholesaler Info Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">{{ $edit_data->business_name }}</h4>
                            <p class="text-muted mb-0">{{ $edit_data->name }} | {{ $edit_data->phone }}</p>
                            <small class="text-muted">{{ $edit_data->address }}</small>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addpay">
                                <i class="fe-plus"></i> Add Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Invoices -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card summary-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ $summaryData['total_invoices'] }}</h3>
                            <p class="mb-0">Total Invoices</p>
                            <small>৳{{ number_format($summaryData['total_invoice_amount'], 2) }}</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fe-file-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Returns -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card summary-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ $summaryData['total_returns'] }}</h3>
                            <p class="mb-0">Total Returns</p>
                            <small>৳{{ number_format($summaryData['total_return_amount'], 2) }}</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fe-rotate-ccw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Paid -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card summary-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">৳{{ number_format($summaryData['total_paid'], 2) }}</h3>
                            <p class="mb-0">Total Paid</p>
                            <small>Advance: ৳{{ number_format($summaryData['total_advance'], 2) }}</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fe-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Due Amount -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card summary-card {{ $summaryData['due_amount'] > 0 ? 'bg-danger' : 'bg-info' }} text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">৳{{ number_format(abs($summaryData['due_amount']), 2) }}</h3>
                            <p class="mb-0">{{ $summaryData['due_amount'] > 0 ? 'Due Amount' : 'Advance Balance' }}</p>
                            <small>Net: ৳{{ number_format($summaryData['net_invoice_amount'], 2) }}</small>
                        </div>
                        <div class="stat-icon">
                            <i class="{{ $summaryData['due_amount'] > 0 ? 'fe-alert-circle' : 'fe-dollar-sign' }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Payment History</h5>
                        <span class="badge bg-secondary">{{ $summaryData['payment_history']->count() }} Transactions</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($summaryData['payment_history']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover payment-history-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                        <th>Reference</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($summaryData['payment_history'] as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d M Y', strtotime($payment->date ?? $payment->created_at)) }}</td>
                                        <td>
                                            <span class="badge badge-payment bg-light text-dark">
                                                {{ $payment->payment_method }}
                                            </span>
                                        </td>
                                        <td class="text-success fw-bold">৳{{ number_format($payment->pay_amount, 2) }}</td>
                                        <td>
                                            <small class="text-muted">{{ $payment->paynote ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Completed</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3">Total Payments</th>
                                        <th class="text-success">৳{{ number_format($summaryData['total_paid'], 2) }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fe-credit-card text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-2">No payment history found</h5>
                            <p class="text-muted">Payment transactions will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addpay" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wholesaler Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('wholeseller.payupdate')}}" method="post">
                @csrf
                <div class="modal-body">
                    <!-- Auto-populate wholesaler ID -->
                    <input type="hidden" name="whosaler_id" value="{{ $edit_data->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Wholesaler</label>
                        <input type="text" class="form-control" value="{{ $edit_data->business_name }} ({{ $edit_data->name }})" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="Cash">Cash</option>
                            <option value="Bkash">Bkash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Advance">Advance Payment</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="pay_amount" class="form-label">Pay Amount</label>
                        <input type="number" step="0.01" class="form-control" name="advance" required>
                        <small class="text-muted">
                            Due Amount: ৳{{ number_format($summaryData['due_amount'], 2) }}
                            @if($summaryData['due_amount'] > 0)
                                <span class="text-danger">(Outstanding)</span>
                            @else
                                <span class="text-info">(Advance Balance)</span>
                            @endif
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date" class="form-label">Pay Date</label>
                        <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="paynote" class="form-label">Payment Reference/Note</label>
                        <textarea class="form-control" name="paynote" rows="3" placeholder="Enter payment reference or notes..."></textarea>
                    </div>
                    
                    <!-- Display current payment status -->
                    <div class="alert alert-info">
                        <small>
                            <strong>Current Status:</strong><br>
                            Total Invoices: ৳{{ number_format($summaryData['total_invoice_amount'], 2) }}<br>
                            Total Paid: ৳{{ number_format($summaryData['total_paid'], 2) }}<br>
                            Balance: ৳{{ number_format($summaryData['due_amount'], 2) }}
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fe-check"></i> Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection