@extends('backEnd.dealer.panel.layout.master')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-sm bg-blue rounded">
                                    <i data-feather="users" class="avatar-title font-22 text-white"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <h3 class="text-dark my-1">{{$total_referrals}}</h3>
                                    <p class="text-muted mb-1 text-truncate">Total Referrals</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-sm bg-success rounded">
                                    <i data-feather="shopping-bag" class="avatar-title font-22 text-white"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <h3 class="text-dark my-1">{{$assigned_products}}</h3>
                                    <p class="text-muted mb-1 text-truncate">Assigned Products</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-sm bg-warning rounded">
                                    <i data-feather="dollar-sign" class="avatar-title font-22 text-white"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <h3 class="text-dark my-1">{{$balance}}</h3>
                                    <p class="text-muted mb-1 text-truncate">My Balance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Your Referral Link</h4>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{url('ref/' . Auth::guard('dealer')->id())}}"
                                id="refLink" readonly>
                            <button class="btn btn-primary" onclick="copyLink()">Copy Link</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function copyLink() {
            var copyText = document.getElementById("refLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            toastr.success('Link copied to clipboard');
        }
    </script>
@endsection