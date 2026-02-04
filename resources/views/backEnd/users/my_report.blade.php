@extends('backEnd.layouts.master')
@section('title', 'Performance Report')

@section('content')
<div class="container-fluid">
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Report For: <span id="report_user_name" class="text-primary">{{ $user->name }}</span></h4>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-2 align-items-center">
                        
                        @if(auth()->user()->user_type == 'admin')
                        <div class="col-md-3">
                            <label class="fw-bold">Select Moderator:</label>
                            <select id="filter_user_id" class="form-control select2">
                                <option value="">Select User</option>
                                @foreach($moderators as $moderator)
                                    <option value="{{ $moderator->id }}" {{ $user->id == $moderator->id ? 'selected' : '' }}>
                                        {{ $moderator->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-auto">
                            <label class="fw-bold">From:</label>
                            <input type="date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        
                        <div class="col-auto">
                            <label class="fw-bold">To:</label>
                            <input type="date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        
                        <div class="col-auto" style="margin-top: 28px;">
                            <button type="button" id="reset_btn" class="btn btn-danger">
                                <i class="fas fa-sync"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="stats_area">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h3 class="text-white"><span id="display_balance">{{ $user->balance }}</span> BDT</h3>
                        <p class="mb-0">Wallet Balance</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h3 class="text-white" id="display_total">{{ $total_orders }}</h3>
                        <p class="mb-0">Total Orders Taken</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h3 class="text-white" id="display_success">{{ $success_orders }}</h3>
                        <p class="mb-0">Successfully Delivered</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h3 class="text-white"><span id="display_rate">{{ number_format($success_rate, 2) }}</span>%</h3>
                        <p class="mb-0">Success Rate</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card border-danger border">
                    <div class="card-body">
                        <h4 class="header-title text-danger">Returned Orders</h4>
                        <h2 class="text-danger" id="display_return">{{ $return_orders }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-secondary border">
                    <div class="card-body">
                        <h4 class="header-title text-secondary">Cancelled Orders</h4>
                        <h2 class="text-secondary" id="display_cancel">{{ $cancel_orders }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    
    // ১. মডারেটর চেঞ্জ করলে অটোমেটিক লোড হবে
    $('#filter_user_id').on('change', function() {
        fetchReportData();
    });

    // ২. তারিখ চেঞ্জ করলে অটোমেটিক লোড হবে
    $('#start_date, #end_date').on('change', function() {
        fetchReportData();
    });

    // ৩. রিসেট বাটন ইভেন্ট
    $('#reset_btn').on('click', function() {
        $('#start_date').val('');
        $('#end_date').val('');
        $('#filter_user_id').val('').trigger('change'); // Select2 রিসেট এবং ডাটা লোড হবে
    });

    // মেইন ফাংশন (AJAX)
    function fetchReportData() {
        // লোডিং বোঝানোর জন্য পুরো এরিয়া একটু ঝাপসা (Opacity) করা হলো
        $('#stats_area').css('opacity', '0.5');
        
        var user_id = $('#filter_user_id').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        $.ajax({
            url: "{{ route('admin.moderator.report') }}", // Route URL
            type: "GET",
            data: {
                user_id: user_id,
                start_date: start_date,
                end_date: end_date,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // লোডিং শেষ, অপাসিটি নরমাল করা হলো
                $('#stats_area').css('opacity', '1');

                if(response.status == 'success') {
                    // Update View with new Data
                    $('#report_user_name').text(response.user_name);
                    $('#display_balance').text(response.balance);
                    $('#display_total').text(response.total_orders);
                    $('#display_success').text(response.success_orders);
                    $('#display_return').text(response.return_orders);
                    $('#display_cancel').text(response.cancel_orders);
                    $('#display_rate').text(response.success_rate);
                }
            },
            error: function(xhr) {
                // এরর হলেও অপাসিটি নরমাল হবে
                $('#stats_area').css('opacity', '1');
                console.log(xhr.responseText);
                alert('Error loading data');
            }
        });
    }
});
</script>
@endsection