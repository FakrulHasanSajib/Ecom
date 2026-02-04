<tr class="withdrawal-row">
    <td class="align-middle">
        <span class="text-muted fw-semibold">#{{ $withdrawal->id }}</span>
    </td>
    <td class="align-middle">
        <div class="d-flex align-items-center gap-3">
            @if($withdrawal->author->avatar ?? false)
                <img src="{{ asset($withdrawal->author->avatar) }}" 
                     alt="{{ $withdrawal->author->name }}" 
                     class="rounded-circle shadow-sm" 
                     width="40" 
                     height="40"
                     style="object-fit: cover;">
            @else
                <div class="rounded-circle bg-gradient d-flex align-items-center justify-content-center shadow-sm" 
                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <span class="text-white fw-bold">{{ strtoupper(substr($withdrawal->author->name ?? 'U', 0, 1)) }}</span>
                </div>
            @endif
            <div>
                <div class="fw-semibold text-dark mb-1">{{ $withdrawal->author->name ?? 'N/A' }}</div>
                <small class="text-muted">{{ $withdrawal->author->email ?? '' }}</small>
            </div>
        </div>
    </td>
    <td class="align-middle">
        <div class="fw-medium text-dark">{{ $withdrawal->created_at->format('M d, Y') }}</div>
        <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
    </td>
    <td class="align-middle">
        <div class="d-flex align-items-center gap-2">
            <i class="mdi mdi-currency-bdt text-success"></i>
            <span class="fs-5 fw-bold text-success">{{ number_format($withdrawal->pay_amount, 2) }}</span>
        </div>
    </td>
    <td class="align-middle">
        <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
            <i class="mdi mdi-wallet me-1"></i>
            {{ ucfirst($withdrawal->payment_method ?? 'N/A') }}
        </span>
    </td>
    <td class="align-middle">
        @if($withdrawal->account_details)
            <button type="button" 
                    class="btn btn-sm btn-light border rounded-pill px-3" 
                    data-bs-toggle="modal" 
                    data-bs-target="#accountDetailsModal{{ $withdrawal->id }}">
                <i class="mdi mdi-eye text-primary me-1"></i>
                <span class="small">View Details</span>
            </button>
        @else
            <span class="text-muted small">No details</span>
        @endif
    </td>
    <td class="align-middle">
        @php
            $statusConfig = [
                0 => ['label' => 'Pending', 'class' => 'warning', 'icon' => 'mdi-clock-outline', 'bg' => '#fff3cd', 'color' => '#997404'],
                1 => ['label' => 'Processing', 'class' => 'info', 'icon' => 'mdi-sync', 'bg' => '#cff4fc', 'color' => '#055160'],
                2 => ['label' => 'Approved', 'class' => 'success', 'icon' => 'mdi-check-circle', 'bg' => '#d1e7dd', 'color' => '#0a3622'],
                3 => ['label' => 'Completed', 'class' => 'primary', 'icon' => 'mdi-check-all', 'bg' => '#cfe2ff', 'color' => '#084298'],
                4 => ['label' => 'Rejected', 'class' => 'danger', 'icon' => 'mdi-close-circle', 'bg' => '#f8d7da', 'color' => '#58151c'],
            ];
            $status = $statusConfig[$withdrawal->status] ?? ['label' => 'Unknown', 'class' => 'secondary', 'icon' => 'mdi-help', 'bg' => '#e2e3e5', 'color' => '#41464b'];
        @endphp
        <span class="badge rounded-pill px-3 py-2 fw-semibold" 
              style="background-color: {{ $status['bg'] }}; color: {{ $status['color'] }}; border: 1px solid {{ $status['color'] }}33;">
            <i class="mdi {{ $status['icon'] }} me-1"></i>
            {{ $status['label'] }}
        </span>
    </td>
    <td class="align-middle">
        <div class="d-flex gap-2">
            @if($withdrawal->status == 0)
                <button type="button" 
                        class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                        style="width: 36px; height: 36px;"
                        onclick="handleWithdrawalAction({{ $withdrawal->id }}, '2', '{{ number_format($withdrawal->pay_amount, 2) }}', '{{ $withdrawal->author->name ?? 'N/A' }}')"
                        title="Approve"
                        data-bs-toggle="tooltip">
                    <i class="mdi mdi-check fs-5"></i>
                </button>
                <button type="button" 
                        class="btn btn-sm btn-info rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                        style="width: 36px; height: 36px;"
                        onclick="handleWithdrawalAction({{ $withdrawal->id }}, '1', '{{ number_format($withdrawal->pay_amount, 2) }}', '{{ $withdrawal->author->name ?? 'N/A' }}')"
                        title="Set to Processing"
                        data-bs-toggle="tooltip">
                    <i class="mdi mdi-sync fs-5"></i>
                </button>
                <button type="button" 
                        class="btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                        style="width: 36px; height: 36px;"
                        onclick="handleWithdrawalAction({{ $withdrawal->id }}, '4', '{{ number_format($withdrawal->pay_amount, 2) }}', '{{ $withdrawal->author->name ?? 'N/A' }}')"
                        title="Reject"
                        data-bs-toggle="tooltip">
                    <i class="mdi mdi-close fs-5"></i>
                </button>
            @elseif($withdrawal->status == 1)
                <button type="button" 
                        class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                        style="width: 36px; height: 36px;"
                        onclick="handleWithdrawalAction({{ $withdrawal->id }}, '2', '{{ number_format($withdrawal->pay_amount, 2) }}', '{{ $withdrawal->author->name ?? 'N/A' }}')"
                        title="Approve"
                        data-bs-toggle="tooltip">
                    <i class="mdi mdi-check fs-5"></i>
                </button>
                <button type="button" 
                        class="btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                        style="width: 36px; height: 36px;"
                        onclick="handleWithdrawalAction({{ $withdrawal->id }}, '4', '{{ number_format($withdrawal->pay_amount, 2) }}', '{{ $withdrawal->author->name ?? 'N/A' }}')"
                        title="Reject"
                        data-bs-toggle="tooltip">
                    <i class="mdi mdi-close fs-5"></i>
                </button>
            @elseif($withdrawal->status == 2)
                <button type="button" 
                        class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                        style="width: 36px; height: 36px;"
                        onclick="handleWithdrawalAction({{ $withdrawal->id }}, '3', '{{ number_format($withdrawal->pay_amount, 2) }}', '{{ $withdrawal->author->name ?? 'N/A' }}')"
                        title="Mark as Completed"
                        data-bs-toggle="tooltip">
                    <i class="mdi mdi-check-all fs-5"></i>
                </button>
            @endif
            <button type="button" 
                    class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                    style="width: 36px; height: 36px;"
                    data-bs-toggle="modal" 
                    data-bs-target="#withdrawalDetailsModal{{ $withdrawal->id }}"
                    title="View Details"
                    data-bs-toggle="tooltip">
                <i class="mdi mdi-eye fs-5"></i>
            </button>
        </div>
    </td>
</tr>

<!-- Account Details Modal - Modern Design -->
<div class="modal fade" id="accountDetailsModal{{ $withdrawal->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="mdi mdi-wallet text-primary me-2"></i>Account Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="mdi mdi-credit-card text-muted"></i>
                                    <small class="text-muted text-uppercase fw-semibold">Payment Method</small>
                                </div>
                                <div class="fs-5 fw-semibold">{{ ucfirst($withdrawal->payment_method ?? 'N/A') }}</div>
                            </div>
                            @if($withdrawal->account_details)
                                @php
                                    $details = is_string($withdrawal->account_details) 
                                        ? json_decode($withdrawal->account_details, true) 
                                        : $withdrawal->account_details;
                                @endphp
                                @if(is_array($details))
                                    @foreach($details as $key => $value)
                                        <div class="col-12">
                                            <div class="border-top pt-3">
                                                <small class="text-muted text-uppercase fw-semibold d-block mb-1">
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                </small>
                                                <div class="fw-medium text-dark">{{ $value }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="border-top pt-3">
                                            <small class="text-muted text-uppercase fw-semibold d-block mb-1">Details</small>
                                            <div class="fw-medium text-dark">{{ $withdrawal->account_details }}</div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Details Modal - Enhanced Design -->
<div class="modal fade" id="withdrawalDetailsModal{{ $withdrawal->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 bg-gradient pb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div>
                    <h5 class="modal-title text-white fw-bold mb-1">Withdrawal Request Details</h5>
                    <small class="text-white opacity-75">Request #{{ $withdrawal->id }}</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Author Information Card -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="mdi mdi-account-circle text-primary fs-5"></i>
                                    <h6 class="card-subtitle text-muted fw-semibold mb-0">Author Information</h6>
                                </div>
                                <div class="d-flex align-items-center mb-4">
                                    @if($withdrawal->author->avatar ?? false)
                                        <img src="{{ asset($withdrawal->author->avatar) }}" 
                                             alt="{{ $withdrawal->author->name }}" 
                                             class="rounded-circle shadow me-3" 
                                             width="60" 
                                             height="60"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-gradient d-flex align-items-center justify-content-center shadow me-3" 
                                             style="width: 60px; height: 60px; font-size: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <span class="text-white fw-bold">{{ strtoupper(substr($withdrawal->author->name ?? 'U', 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold fs-6 mb-1">{{ $withdrawal->author->name ?? 'N/A' }}</div>
                                        <small class="text-muted d-block">{{ $withdrawal->author->email ?? '' }}</small>
                                        <small class="text-muted">ID: {{ $withdrawal->author->id ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Withdrawal Information Card -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="mdi mdi-cash-multiple text-success fs-5"></i>
                                    <h6 class="card-subtitle text-muted fw-semibold mb-0">Withdrawal Information</h6>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted text-uppercase d-block mb-2">Amount</small>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="mdi mdi-currency-bdt text-success fs-4"></i>
                                        <span class="h3 text-success fw-bold mb-0">{{ number_format($withdrawal->pay_amount, 2) }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted text-uppercase d-block mb-2">Status</small>
                                    <span class="badge rounded-pill px-3 py-2 fw-semibold" 
                                          style="background-color: {{ $status['bg'] }}; color: {{ $status['color'] }}; border: 1px solid {{ $status['color'] }}33;">
                                        <i class="mdi {{ $status['icon'] }} me-1"></i>
                                        {{ $status['label'] }}
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted text-uppercase d-block mb-2">Payment Method</small>
                                    <div class="fw-semibold">
                                        <i class="mdi mdi-wallet me-1"></i>{{ ucfirst($withdrawal->payment_method ?? 'N/A') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Details Card -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="mdi mdi-bank text-info fs-5"></i>
                                    <h6 class="card-subtitle text-muted fw-semibold mb-0">Account Details</h6>
                                </div>
                                @if($withdrawal->account_details)
                                    @php
                                        $details = is_string($withdrawal->account_details) 
                                            ? json_decode($withdrawal->account_details, true) 
                                            : $withdrawal->account_details;
                                    @endphp
                                    @if(is_array($details))
                                        <div class="row g-3">
                                            @foreach($details as $key => $value)
                                                <div class="col-md-6">
                                                    <div class="bg-light rounded p-3">
                                                        <small class="text-muted text-uppercase d-block mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</small>
                                                        <div class="fw-semibold text-dark">{{ $value }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-light rounded p-3">
                                            <p class="mb-0 fw-medium">{{ $withdrawal->account_details }}</p>
                                        </div>
                                    @endif
                                @else
                                    <p class="text-muted mb-0">No account details provided</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Request Note -->
                    @if($withdrawal->request_note)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <i class="mdi mdi-note-text text-warning fs-5"></i>
                                        <h6 class="card-subtitle text-muted fw-semibold mb-0">Request Note</h6>
                                    </div>
                                    <p class="mb-0">{{ $withdrawal->request_note }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Admin Note -->
                    @if($withdrawal->admin_note)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm" style="background-color: #fff8e6;">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <i class="mdi mdi-shield-account text-danger fs-5"></i>
                                        <h6 class="card-subtitle text-muted fw-semibold mb-0">Admin Note</h6>
                                    </div>
                                    <p class="mb-0">{{ $withdrawal->admin_note }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Timeline Card -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="mdi mdi-timeline-clock text-secondary fs-5"></i>
                                    <h6 class="card-subtitle text-muted fw-semibold mb-0">Timeline</h6>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="bg-light rounded p-3">
                                            <small class="text-muted text-uppercase d-block mb-1">Request Date</small>
                                            <div class="fw-semibold">{{ $withdrawal->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                        </div>
                                    </div>
                                    @if($withdrawal->updated_at && $withdrawal->updated_at != $withdrawal->created_at)
                                        <div class="col-md-4">
                                            <div class="bg-light rounded p-3">
                                                <small class="text-muted text-uppercase d-block mb-1">Last Updated</small>
                                                <div class="fw-semibold">{{ $withdrawal->updated_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $withdrawal->updated_at->format('h:i A') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                    @if($withdrawal->processed_at)
                                        <div class="col-md-4">
                                            <div class="bg-light rounded p-3">
                                                <small class="text-muted text-uppercase d-block mb-1">Processed Date</small>
                                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('h:i A') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                @if(in_array($withdrawal->status, [0, 1, 2]))
                    <button type="button" 
                            class="btn btn-primary rounded-pill px-4 shadow-sm" 
                            onclick="$('#withdrawalDetailsModal{{ $withdrawal->id }}').modal('hide'); handleWithdrawalAction({{ $withdrawal->id }}, '{{ $withdrawal->status == 0 ? 2 : ($withdrawal->status == 1 ? 2 : 3) }}', '{{ number_format($withdrawal->pay_amount, 2) }}', '{{ $withdrawal->author->name ?? 'N/A' }}')">
                        <i class="mdi mdi-check-circle me-2"></i>Take Action
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for improved design */
.withdrawal-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f0f0f0;
}

.withdrawal-row:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.badge {
    font-weight: 600;
    letter-spacing: 0.3px;
}

.btn-sm {
    transition: all 0.2s ease;
}

.btn-sm:hover {
    transform: scale(1.1);
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.modal-content {
    border-radius: 1rem;
    overflow: hidden;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Initialize tooltips */
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</style>