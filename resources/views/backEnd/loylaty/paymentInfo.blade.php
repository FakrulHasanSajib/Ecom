@extends('backEnd.layouts.master')
@section('title', 'Wholesaler Payment Summary - ' . $edit_data->name)
@section('content')

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

.progress-thin {
    height: 8px;
}

.export-btn {
    font-size: 0.85rem;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
}

.status-active {
    background-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.3);
}

.status-due {
    background-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.3);
}

@media print {
    .no-print {
        display: none !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>

<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fe-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fe-alert-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Wholesaler Info Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="status-indicator {{ $summaryData['due_amount'] > 0 ? 'status-due' : 'status-active' }}"></span>
                                <h4 class="mb-0">{{ $edit_data->name }}</h4>
                            </div>
                            <p class="text-muted mb-1">{{ $edit_data->business_name ?? $edit_data->name }} | {{ $edit_data->phone }}</p>
                            <small class="text-muted">{{ $edit_data->address }}</small>
                            @if(isset($summaryData['last_payment_date']) && $summaryData['last_payment_date'])
                                <div class="mt-2">
                                    <small class="badge bg-light text-dark">
                                        Last Payment: {{ $summaryData['last_payment_date']->format('d M Y') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <div class="btn-group no-print">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addpay">
                                    <i class="fe-plus"></i> Add Payment
                                </button>
                                <button class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" 
                                        data-bs-toggle="dropdown">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="exportToCSV()">
                                        <i class="fe-download me-2"></i>Export CSV
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="window.print()">
                                        <i class="fe-printer me-2"></i>Print Summary
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="">
                                        <i class="fe-file-text me-2"></i>View Orders
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Progress Bar -->
                    @if($summaryData['net_invoice_amount'] > 0)
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">Payment Progress</small>
                                <small class="text-muted">{{ number_format($summaryData['payment_percentage'] ?? 0, 1) }}%</small>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ min($summaryData['payment_percentage'] ?? 0, 100) }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Orders -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card summary-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ $summaryData['total_invoices'] }}</h3>
                            <p class="mb-0">Total Orders</p>
                            <small>৳{{ number_format($summaryData['total_qty']) }} pcs</small>
                           
                        </div>
                        <div class="stat-icon">
                            <i class="fe-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Loyalty Points -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card summary-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ $summaryData['total_loyalty'] }}</h3>
                            <p class="mb-0">Loyalty Income</p>
                           
                        </div>
                        <div class="stat-icon">
                            <i class="fe-star"></i>
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
                            <p class="mb-0">Withdraw</p>
                            @if(isset($summaryData['total_transactions']))
                                <br><small>{{ $summaryData['total_transactions'] }} transactions</small>
                            @endif
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
                            @if($summaryData['due_amount'] > 0)
                                <br><small class="text-warning"><i class="fe-clock"></i> Due</small>
                            @else
                                <br><small><i class="fe-check"></i> Paid in advance</small>
                            @endif
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
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary">{{ $summaryData['payment_history']->count() }} Transactions</span>
                            @if($summaryData['payment_history']->count() > 0)
                                <button class="btn btn-sm btn-outline-primary export-btn no-print" 
                                        onclick="exportToCSV()">
                                    <i class="fe-download"></i> Export
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($summaryData['payment_history']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover payment-history-table" id="paymentTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                        <th>Reference</th>
                                        <th>Status</th>
                                        <th class="no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($summaryData['payment_history'] as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge badge-payment bg-light text-dark">
                                                {{ ucfirst($payment->payment_method) }}
                                            </span>
                                        </td>
                                        <td class="text-success fw-bold">৳{{ number_format($payment->pay_amount, 2) }}</td>
                                        <td>
                                            <small class="text-muted">{{ $payment->paynote ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Completed</span>
                                        </td>
                                        <td class="no-print">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-info btn-sm" 
                                                        onclick="viewPayment({{ $payment->id }})"
                                                        title="View Details">
                                                    <i class="fe-eye"></i>
                                                </button>
                                                @if(auth()->user()->can('edit-payments'))
                                                <button class="btn btn-outline-warning btn-sm"
                                                        onclick="editPayment({{ $payment->id }})"
                                                        title="Edit Payment">
                                                    <i class="fe-edit"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3">Total Payments</th>
                                        <th class="text-success">৳{{ number_format($summaryData['total_paid'], 2) }}</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if(method_exists($summaryData['payment_history'], 'links'))
                            <div class="d-flex justify-content-center no-print">
                                {{ $summaryData['payment_history']->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fe-credit-card text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No payment history found</h5>
                            <p class="text-muted">Payment transactions will appear here once recorded.</p>
                            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addpay">
                                <i class="fe-plus"></i> Add First Payment
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addpay" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fe-credit-card me-2"></i>Add Author Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('author.paymenthistory') }}" method="post" id="paymentForm">
                @csrf
                <div class="modal-body">
                    <!-- Auto-populate wholesaler ID -->
                    <input type="hidden" name="author_id" value="{{ $edit_data->id }}">
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Wholesaler</label>
                            <input type="text" class="form-control" 
                                   value="{{ $edit_data->business_name ?? $edit_data->name }} ({{ $edit_data->name }})" readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-select" name="payment_method" id="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="bkash">Bkash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="advance">Advance Payment</option>
                                <option value="withdrawal">Withdrawal</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online Payment</option>
                            </select>
                            <div class="invalid-feedback">Please select a payment method.</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="pay_amount" class="form-label">Pay Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" class="form-control" name="pay_amount" 
                                       id="pay_amount" required min="0.01">
                                <div class="invalid-feedback">Please enter a valid amount.</div>
                            </div>
                            <small class="text-muted">
                                @if($summaryData['due_amount'] > 0)
                                    <span class="text-danger">
                                        <i class="fe-alert-triangle"></i> Due Amount: ৳{{ number_format($summaryData['due_amount'], 2) }}
                                    </span>
                                @else
                                    <span class="text-info">
                                        <i class="fe-info"></i> Current Advance: ৳{{ number_format(abs($summaryData['due_amount']), 2) }}
                                    </span>
                                @endif
                            </small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Pay Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date" id="payment_date"
                                   value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                            <div class="invalid-feedback">Please select a valid date.</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="paynote" class="form-label">Payment Reference/Note</label>
                            <input type="text" class="form-control" name="paynote" id="paynote"
                                   placeholder="Transaction ID, Check number, etc." maxlength="500">
                            <small class="text-muted">Optional reference information</small>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="mb-2">Payment Summary:</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted">Due Royalty :</small><br>
                                        <strong>৳{{ number_format($summaryData['total_loyalty'], 2) - number_format($summaryData['total_advance'], 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Total Advance:</small><br>
                                        <strong>৳{{ number_format($summaryData['total_advance'], 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Already Paid:</small><br>
                                        <strong>৳{{ number_format($summaryData['total_paid'], 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">{{ $summaryData['due_amount'] > 0 ? 'Due' : 'Advance' }}:</small><br>
                                        <strong class="{{ $summaryData['due_amount'] > 0 ? 'text-danger' : 'text-success' }}">
                                            ৳{{ number_format(abs($summaryData['due_amount']), 2) }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fe-save"></i> Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for additional functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            const payAmount = parseFloat(document.getElementById('pay_amount').value);
            const paymentMethod = document.getElementById('payment_method').value;
            
            if (!paymentMethod) {
                e.preventDefault();
                alert('Please select a payment method');
                return false;
            }
            
            if (payAmount <= 0) {
                e.preventDefault();
                alert('Payment amount must be greater than 0');
                return false;
            }
            
            // Confirmation for large payments
            const dueAmount = {{ $summaryData['due_amount'] }};
            if (payAmount > dueAmount && dueAmount > 0) {
                const confirm = window.confirm('Payment amount is greater than due amount. This will create an advance balance. Continue?');
                if (!confirm) {
                    e.preventDefault();
                    return false;
                }
            }
            
            return true;
        });
    }
});

// Export to CSV function
function exportToCSV() {
    const table = document.getElementById('paymentTable');
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length - 1; j++) { // Exclude actions column
            let cellText = cols[j].innerText.replace(/,/g, ';'); // Replace commas to avoid CSV issues
            row.push('"' + cellText + '"'); // Wrap in quotes
        }
        csv.push(row.join(','));
    }
    
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = 'wholesaler_payment_history_{{ $edit_data->name }}_{{ date("Y-m-d") }}.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// View payment details
function viewPayment(paymentId) {
    // You can implement a modal or redirect to payment details page
    window.location.href = '/admin/payments/' + paymentId;
}

// Edit payment
function editPayment(paymentId) {
    // You can implement a modal or redirect to payment edit page
    window.location.href = '/admin/payments/' + paymentId + '/edit';
}

// Print functionality
window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});
</script>

@endsection