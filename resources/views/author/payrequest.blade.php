@extends('backEnd.layouts.master')
@section('title', 'Withdrawal Request')
@section('css')
    <link href="{{ asset('backEnd/') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backEnd/') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <style>
        .status-badge {
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 12px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #842029;
        }
        .status-processing {
            background-color: #cfe2ff;
            color: #084298;
        }
        .status-completed {
            background-color: #d1e7dd;
            color: #0a3622;
        }
        .balance-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Withdrawal Request</h4>
                </div>
            </div>
        </div>

        <!-- Balance Overview -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card balance-card">
                    <div class="card-body">
                        <h5 class="text-white-50 mb-2">Available Balance</h5>
                        <h2 class="text-white mb-0">৳{{ number_format($availableBalance ?? 0, 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="text-white-50 mb-2">Total Earned</h5>
                        <h2 class="text-white mb-0">৳{{ number_format($totalLoyalty ?? 0, 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="text-white-50 mb-2">Total Withdrawn</h5>
                        <h2 class="text-white mb-0">৳{{ number_format($totalWithdrawn ?? 0, 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="text-white-50 mb-2">Pending Requests</h5>
                        <h2 class="text-white mb-0">৳{{ number_format($pendingAmount ?? 0, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawal Request Form -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Request Withdrawal</h4>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('author.withdrawal.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="amount" class="form-label">Withdrawal Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="pay_amount" name="pay_amount" 
                                       placeholder="Enter amount" min="100" step="0.01" 
                                       max="{{ $availableBalance ?? 0 }}" required>
                                <small class="form-text text-muted">
                                    Minimum: ৳100 | Maximum: ৳{{ number_format($availableBalance ?? 0, 2) }}
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="rocket">Rocket</option>
                                    <option value="mobile_banking">Mobile Banking</option>
                                </select>
                            </div>

                            <div class="mb-3" id="bank_details" style="display: none;">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                       placeholder="Enter bank name">
                            </div>

                            <div class="mb-3">
                                <label for="account_number" class="form-label">Account Number / Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="account_number" name="account_number" 
                                       placeholder="Enter account/mobile number" required>
                            </div>

                            <div class="mb-3">
                                <label for="account_name" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="account_name" name="account_name" 
                                       placeholder="Enter account holder name" required>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Note (Optional)</label>
                                <textarea class="form-control" id="note" name="note" rows="3" 
                                          placeholder="Add any additional information"></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-cash-plus me-1"></i> Submit Withdrawal Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Recent Withdrawals</h4>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentWithdrawals ?? [] as $withdrawal)
                                        <tr>
                                            <td>{{ $withdrawal->created_at->format('d M, Y') }}</td>
                                            <td><strong>৳{{ number_format($withdrawal->amount, 2) }}</strong></td>
                                            <td>
                                                @if($withdrawal->status == 'pending')
                                                    <span class="status-badge status-pending">Pending</span>
                                                @elseif($withdrawal->status == 'approved')
                                                    <span class="status-badge status-approved">Approved</span>
                                                @elseif($withdrawal->status == 'processing')
                                                    <span class="status-badge status-processing">Processing</span>
                                                @elseif($withdrawal->status == 'completed')
                                                    <span class="status-badge status-completed">Completed</span>
                                                @else
                                                    <span class="status-badge status-rejected">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No recent withdrawals</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawal History Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Withdrawal History</h4>
                        
                        <div class="table-responsive">
                            <table id="withdrawal-table" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Account Details</th>
                                        <th>Status</th>
                                        <th>Processed Date</th>
                                        <th>Note</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($withdrawalHistory ?? [] as $withdrawal)
                                        <tr>
                                            <td>#{{ $withdrawal->id }}</td>
                                            <td>{{ $withdrawal->created_at->format('d M, Y h:i A') }}</td>
                                            <td><strong>৳{{ number_format($withdrawal->amount, 2) }}</strong></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ ucwords(str_replace('_', ' ', $withdrawal->payment_method)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>
                                                    <strong>{{ $withdrawal->account_name }}</strong><br>
                                                    {{ $withdrawal->account_number }}
                                                    @if($withdrawal->bank_name)
                                                        <br>{{ $withdrawal->bank_name }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                @if($withdrawal->status == 0)
                                                    <span class="status-badge status-pending">
                                                        <i class="mdi mdi-clock-outline me-1"></i>Pending
                                                    </span>
                                                @elseif($withdrawal->status == 2)
                                                    <span class="status-badge status-approved">
                                                        <i class="mdi mdi-check-circle me-1"></i>Approved
                                                    </span>
                                                @elseif($withdrawal->status == 1)
                                                    <span class="status-badge status-processing">
                                                        <i class="mdi mdi-sync me-1"></i>Processing
                                                    </span>
                                                @elseif($withdrawal->status == 3)
                                                    <span class="status-badge status-completed">
                                                        <i class="mdi mdi-check-all me-1"></i>Completed
                                                    </span>
                                                @else
                                                    <span class="status-badge status-rejected">
                                                        <i class="mdi mdi-close-circle me-1"></i>Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $withdrawal->processed_at ? $withdrawal->processed_at->format('d M, Y') : '-' }}
                                            </td>
                                            <td>
                                                @if($withdrawal->admin_note)
                                                    <small class="text-muted">{{ Str::limit($withdrawal->admin_note, 30) }}</small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detailModal{{ $withdrawal->id }}">
                                                    <i class="mdi mdi-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detailModal{{ $withdrawal->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Withdrawal Details #{{ $withdrawal->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <th width="40%">Amount:</th>
                                                                <td><strong>৳{{ number_format($withdrawal->pay_amount, 2) }}</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status:</th>
                                                                <td>
                                                                    @if($withdrawal->status == 0)
                                                                        <span class="status-badge status-pending">Pending</span>
                                                                    @elseif($withdrawal->status == 2)
                                                                        <span class="status-badge status-approved">Approved</span>
                                                                    @elseif($withdrawal->status == 1)
                                                                        <span class="status-badge status-processing">Processing</span>
                                                                    @elseif($withdrawal->status == 3)
                                                                        <span class="status-badge status-completed">Completed</span>
                                                                    @else
                                                                        <span class="status-badge status-rejected">Rejected</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Payment Method:</th>
                                                                <td>{{ ucwords(str_replace('_', ' ', $withdrawal->payment_method)) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Account Name:</th>
                                                                <td>{{ $withdrawal->account_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Account Number:</th>
                                                                <td>{{ $withdrawal->account_number }}</td>
                                                            </tr>
                                                            @if($withdrawal->bank_name)
                                                                <tr>
                                                                    <th>Bank Name:</th>
                                                                    <td>{{ $withdrawal->bank_name }}</td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                <th>Request Date:</th>
                                                                <td>{{ $withdrawal->created_at->format('d M, Y h:i A') }}</td>
                                                            </tr>
                                                            @if($withdrawal->processed_at)
                                                                <tr>
                                                                    <th>Processed Date:</th>
                                                                    <td>{{ $withdrawal->processed_at->format('d M, Y h:i A') }}</td>
                                                                </tr>
                                                            @endif
                                                            @if($withdrawal->note)
                                                                <tr>
                                                                    <th>Your Note:</th>
                                                                    <td>{{ $withdrawal->note }}</td>
                                                                </tr>
                                                            @endif
                                                            @if($withdrawal->admin_note)
                                                                <tr>
                                                                    <th>Admin Note:</th>
                                                                    <td class="text-danger">{{ $withdrawal->admin_note }}</td>
                                                                </tr>
                                                            @endif
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">No withdrawal history found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(isset($withdrawalHistory) && $withdrawalHistory->hasPages())
                            <div class="mt-3">
                                {{ $withdrawalHistory->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <!-- Datatables js -->
    <script src="{{ asset('backEnd/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('backEnd/') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('backEnd/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#withdrawal-table').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                pageLength: 25
            });

            // Show/Hide Bank Details
            $('#payment_method').change(function() {
                if($(this).val() == 'bank_transfer') {
                    $('#bank_details').show();
                    $('#bank_name').prop('required', true);
                } else {
                    $('#bank_details').hide();
                    $('#bank_name').prop('required', false);
                }
            });

            // Form validation
            $('form').submit(function(e) {
                var amount = parseFloat($('#amount').val());
                var maxAmount = parseFloat($('#amount').attr('max'));
                
                if(amount < 100) {
                    e.preventDefault();
                    alert('Minimum withdrawal amount is ৳100');
                    return false;
                }
                
                if(amount > maxAmount) {
                    e.preventDefault();
                    alert('Insufficient balance. Maximum withdrawal amount is ৳' + maxAmount.toFixed(2));
                    return false;
                }
            });
        });
    </script>
@endsection