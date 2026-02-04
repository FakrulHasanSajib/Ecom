@extends('backEnd.reseller.layout.master')
@section('title', 'Referral Profile')
@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{route('reseller.referrals')}}" class="btn btn-secondary btn-sm"> <i
                                data-feather="arrow-left" class="me-1"></i> Back to List</a>
                    </div>
                    <h4 class="page-title">Referral Profile: {{$referral->name}}</h4>
                </div>
            </div>
        </div>

        <!-- Profile Overview -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{asset($referral->image ?? 'backEnd/assets/images/users/user-1.jpg')}}"
                            class="rounded-circle avatar-lg img-thumbnail mb-2" alt="profile-image">

                        <h4 class="mb-0">{{$referral->name}}</h4>
                        <p class="text-muted">{{$referral->store_name ?? 'N/A'}}</p>

                        <div class="text-start mt-3">
                            <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span
                                    class="ms-2">{{$referral->name}}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span
                                    class="ms-2">{{$referral->phone}}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span
                                    class="ms-2">{{$referral->email}}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Joined At :</strong> <span
                                    class="ms-2">{{$referral->created_at->format('d M, Y')}}</span></p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Wallet Info</h4>
                        <div class="border p-2 rounded mb-2">
                            <p class="mb-1">Current Balance</p>
                            <h5 class="text-success">{{$referral->balance}}</h5>
                        </div>
                        <div class="border p-2 rounded">
                            <p class="mb-1">Total Orders</p>
                            <h5>{{$referral->orders->count()}}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Order History ({{$referral->orders->count()}})</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($referral->orders as $order)
                                        <tr>
                                            <td>{{$order->invoice_id}}</td>
                                            <td>{{$order->amount}}</td>
                                            <td>{{$order->status ? $order->status->name : 'Pending'}}</td>
                                            <td>{{$order->created_at->format('d M, Y')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection