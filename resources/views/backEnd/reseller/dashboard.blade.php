@extends('backEnd.reseller.layout.master')
@section('title', 'Reseller Dashboard')
@section('content')
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Reseller Dashboard</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end mt-2">
                            <div id="total-revenue-chart"></div>
                        </div>
                        <div>
                            <h4 class="mb-1 mt-1"><span data-plugin="counterup">৳
                                    {{ Auth::guard('reseller')->user()->balance ?? 0 }}</span></h4>
                            <p class="text-muted mb-0">Balance</p>
                        </div>
                        <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i
                                    class="mdi mdi-arrow-up-bold me-1"></i>0%</span> since last week
                        </p>
                    </div>
                </div>
            </div> <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end mt-2">
                            <div id="orders-chart"> </div>
                        </div>
                        <div>
                            <h4 class="mb-1 mt-1"><span
                                    data-plugin="counterup">{{ \App\Models\Order::where('customer_id', Auth::guard('reseller')->id())->count() }}</span>
                            </h4>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                        <p class="text-muted mt-3 mb-0"><span class="text-danger me-1"><i
                                    class="mdi mdi-arrow-down-bold me-1"></i>0%</span> since last week
                        </p>
                    </div>
                </div>
            </div> 
            
            {{-- 
                Logic Update: 
                Checking if referrer_id is explicitly NULL.
                If NULL -> Level 1 Reseller (Can refer).
                If NOT NULL -> Level 2 Reseller (Cannot refer).
            --}}
            @if(Auth::guard('reseller')->user()->referrer_id === null)
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">My Referral Link (Recruit Resellers)</h4>
                        <div class="input-group">
                            <input type="text" class="form-control" id="refLink"
                                value="{{ url('ref/' . Auth::guard('reseller')->id()) }}" readonly>
                            <button class="btn btn-primary" onclick="copyRefLink()">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
        </div>

    {{-- Script will only load if the user is allowed to refer --}}
    @if(Auth::guard('reseller')->user()->referrer_id === null)
    <script>
        function copyRefLink() {
            var copyText = document.getElementById("refLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            // toastr.success('Referral Link Copied');
            alert("Referral Link Copied: " + copyText.value);
        }
    </script>
    @endif

@endsection