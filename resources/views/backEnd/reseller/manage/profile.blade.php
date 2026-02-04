@extends('backEnd.layouts.master')
@section('title', 'Reseller Profile')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{route('admin.reseller.index')}}" class="btn btn-secondary rounded-pill">Back to List</a>
                    </div>
                    <h4 class="page-title">Reseller Profile: {{$reseller->name}}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card text-center">
                    <div class="card-body">
                        @if($reseller->image)
                            <img src="{{asset($reseller->image)}}" class="rounded-circle avatar-lg img-thumbnail"
                                alt="profile-image">
                        @else
                            <img src="{{asset('public/backEnd/assets/images/users/user-1.jpg')}}"
                                class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                        @endif

                        <h4 class="mb-0 mt-2">{{$reseller->name}}</h4>
                        <p class="text-muted font-14">{{$reseller->store_name}}</p>

                        <div class="text-start mt-3">
                            <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span
                                    class="ms-2">{{$reseller->name}}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span
                                    class="ms-2">{{$reseller->phone}}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span
                                    class="ms-2">{{$reseller->email ?? 'N/A'}}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Dealer :</strong> <span
                                    class="ms-2 badge bg-info">{{$reseller->dealer ? $reseller->dealer->name : 'N/A'}}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Wallet Info</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Current Balance</h5>
                                    <h5 class="mb-1 text-success">৳ {{$reseller->balance}}</h5>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Total Orders</h5>
                                    <small class="text-primary font-14">{{$reseller->orders->count()}}</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Order History</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reseller->orders as $order)
                                        <tr>
                                            <td>{{$order->invoice_id}}</td>
                                            <td>{{$order->amount}}</td>
                                            <td>{{$order->order_status}}</td>
                                            <td>{{$order->created_at->format('d M, Y')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <h4 class="header-title mb-3 mt-4">Payment History</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reseller->payments as $payment)
                                        <tr>
                                            <td>{{$payment->created_at->format('d M, Y')}}</td>
                                            <td>{{$payment->payment_method}}</td>
                                            <td>{{$payment->amount}}</td>
                                            <td>{{$payment->payment_status}}</td>
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