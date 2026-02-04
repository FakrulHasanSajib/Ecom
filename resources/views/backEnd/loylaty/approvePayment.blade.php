@extends('backEnd.layouts.master')
@section('title', 'Manage Withdrawal Requests')
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
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Withdrawal Request Management</h4>
                </div>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body">
                        <h5 class="text-white-50 mb-2">Pending Requests</h5>
                        <h2 class="text-white mb-0">{{ $stats['pending_count'] ?? 0 }}</h2>
                        <small class="text-white-50">৳{{ number_format($stats['pending_amount'] ?? 0, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-success">
                    <div class="card-body">
                        <h5 class="text-white-50 mb-2">Approved</h5>
                        <h2 class="text-white mb-0">{{ $stats['approved_count'] ?? 0 }}</h2>
                        <small class="text-white-50">৳{{ number_format($stats['approved_amount'] ?? 0, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-info">
                    <div class="card-body">
                        <h5 class="text-white-50 mb-2">Processing</h5>
                        <h2 class="text-white mb-0">{{ $stats['processing_count'] ?? 0 }}</h2>
                        <small class="text-white-50">৳{{ number_format($stats['processing_amount'] ?? 0, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-primary">
                    <div class="card-body">
                        <h5 class="text-white-50 mb-2">Completed</h5>
                        <h2 class="text-white mb-0">{{ $stats['completed_count'] ?? 0 }}</h2>
                        <small class="text-white-50">৳{{ number_format($stats['completed_amount'] ?? 0, 2) }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
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

        <!-- Filter Tabs -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-bordered mb-3">
                            <li class="nav-item">
                                <a href="#pending-tab" data-bs-toggle="tab" class="nav-link active">
                                    <i class="mdi mdi-clock-outline me-1"></i>Pending ({{ $stats['pending_count'] ?? 0 }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#approved-tab" data-bs-toggle="tab" class="nav-link">
                                    <i class="mdi mdi-check-circle me-1"></i>Approved ({{ $stats['approved_count'] ?? 0 }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#processing-tab" data-bs-toggle="tab" class="nav-link">
                                    <i class="mdi mdi-sync me-1"></i>Processing ({{ $stats['processing_count'] ?? 0 }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#completed-tab" data-bs-toggle="tab" class="nav-link">
                                    <i class="mdi mdi-check-all me-1"></i>Completed ({{ $stats['completed_count'] ?? 0 }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#rejected-tab" data-bs-toggle="tab" class="nav-link">
                                    <i class="mdi mdi-close-circle me-1"></i>Rejected
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#all-tab" data-bs-toggle="tab" class="nav-link">
                                    <i class="mdi mdi-format-list-bulleted me-1"></i>All Requests
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Pending Tab -->
                            <div class="tab-pane show active" id="pending-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100 withdrawal-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Author</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Account Details</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingRequests ?? [] as $withdrawal)
                                                @include('backEnd.loylaty.partial.withdrawal', ['withdrawal' => $withdrawal])
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Approved Tab -->
                            <div class="tab-pane" id="approved-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100 withdrawal-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Author</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Account Details</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($approvedRequests ?? [] as $withdrawal)
                                                @include('backEnd.loylaty.partial.withdrawal', ['withdrawal' => $withdrawal])
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Processing Tab -->
                            <div class="tab-pane" id="processing-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100 withdrawal-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Author</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Account Details</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($processingRequests ?? [] as $withdrawal)
                                                @include('backEnd.loylaty.partial.withdrawal', ['withdrawal' => $withdrawal])
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Completed Tab -->
                            <div class="tab-pane" id="completed-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100 withdrawal-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Author</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Account Details</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($completedRequests ?? [] as $withdrawal)
                                                @include('backEnd.loylaty.partial.withdrawal', ['withdrawal' => $withdrawal])
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Rejected Tab -->
                            <div class="tab-pane" id="rejected-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100 withdrawal-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Author</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Account Details</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rejectedRequests ?? [] as $withdrawal)
                                                @include('backEnd.loylaty.partial.withdrawal', ['withdrawal' => $withdrawal])
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- All Tab -->
                            <div class="tab-pane" id="all-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap w-100 withdrawal-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Author</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Account Details</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allRequests ?? [] as $withdrawal)
                                                @include('backEnd.loylaty.partial.withdrawal', ['withdrawal' => $withdrawal])
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Modal -->
    <div class="modal fade" id="actionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="actionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="actionModalTitle">Withdrawal Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="status" id="action_status">
                        
                        <div class="mb-3">
                            <h6 id="withdrawal_details"></h6>
                        </div>

                        <div class="mb-3">
                            <label for="admin_note" class="form-label">Admin Note <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="admin_note" name="admin_note" rows="4" 
                                      placeholder="Enter note for author..." required></textarea>
                            <small class="form-text text-muted">This note will be visible to the author.</small>
                        </div>

                        <div id="reject_reason_group" style="display: none;">
                            <div class="alert alert-warning">
                                <strong>Note:</strong> Please provide a clear reason for rejection.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="action_submit_btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('backEnd/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('backEnd/') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('backEnd/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.withdrawal-table').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                pageLength: 25
            });

            // Handle action buttons
            window.handleWithdrawalAction = function(id, action, amount, author) {
                const actionForm = $('#actionForm');
                const actionUrl = "{{ route('admin.withdrawal.update', ':id') }}".replace(':id', id);
                actionForm.attr('action', actionUrl);

                $('#action_status').val(action);
                
                let title, btnClass, btnText;
                
                if(action == '2') {
                    title = 'Approve Withdrawal Request';
                    btnClass = 'btn-success';
                    btnText = 'Approve';
                    $('#reject_reason_group').hide();
                } else if(action == '1') {
                    title = 'Mark as Processing';
                    btnClass = 'btn-info';
                    btnText = 'Mark Processing';
                    $('#reject_reason_group').hide();
                } else if(action == '3') {
                    title = 'Mark as Completed';
                    btnClass = 'btn-primary';
                    btnText = 'Mark Completed';
                    $('#reject_reason_group').hide();
                } else {
                    title = 'Reject Withdrawal Request';
                    btnClass = 'btn-danger';
                    btnText = 'Reject';
                    $('#reject_reason_group').show();
                }
                
                $('#actionModalTitle').text(title);
                $('#withdrawal_details').html(`<strong>Amount:</strong> ৳${amount}<br><strong>Author:</strong> ${author}`);
                $('#action_submit_btn').removeClass('btn-success btn-danger btn-info btn-primary').addClass(btnClass).text(btnText);
                $('#admin_note').val('');
                
                $('#actionModal').modal('show');
            };
        });
    </script>
@endsection