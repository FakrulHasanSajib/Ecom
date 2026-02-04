@extends('backEnd.layouts.master')
@section('title', 'Dealer Profile')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{route('admin.dealer.index')}}" class="btn btn-secondary rounded-pill">Back to List</a>
                    </div>
                    <h4 class="page-title">Dealer Profile: {{$dealer->name}}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card text-center">
                    <div class="card-body">
                        @if($dealer->image)
                            <img src="{{asset($dealer->image)}}" class="rounded-circle avatar-lg img-thumbnail"
                                alt="profile-image">
                        @else
                            <img src="{{asset('public/backEnd/assets/images/users/user-1.jpg')}}"
                                class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                        @endif

                        <h4 class="mb-0 mt-2">{{$dealer->name}}</h4>
                        <p class="text-muted font-14">{{$dealer->store_name}}</p>

                        <div class="text-start mt-3">
                            <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span
                                    class="ms-2">{{$dealer->name}}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span
                                    class="ms-2">{{$dealer->phone}}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span
                                    class="ms-2">{{$dealer->email}}</span></p>
                            <p class="text-muted mb-1 font-13"><strong>Location :</strong> <span
                                    class="ms-2">{{$dealer->address}}</span></p>
                        </div>

                        <ul class="social-list list-inline mt-3 mb-0">
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i
                                        class="mdi mdi-facebook"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i
                                        class="mdi mdi-google"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-info text-info"><i
                                        class="mdi mdi-twitter"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i
                                        class="mdi mdi-github"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Wallet Info</h4>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Current Balance</h5>
                                    <h5 class="mb-1 text-success">৳ {{$dealer->balance}}</h5>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Total Referrals</h5>
                                    <small class="text-primary font-14">{{$dealer->customers->count()}}</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills navtab-bg nav-justified">
                            <li class="nav-item">
                                <a href="#products" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                    Assigned Products
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#referrals" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                    Referral History
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="products">
                                <div class="table-responsive">
                                    <table class="table table-hover table-centered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Reg. Price</th>
                                                <th>Dealer Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dealer->products as $item)
                                                <tr>
                                                    <td>{{$item->name}}</td>
                                                    <td>{{$item->new_price}}</td>
                                                    <td>{{$item->pivot->dealer_price}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="referrals">
                                <div class="table-responsive">
                                    <table class="table table-hover table-centered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Reseller Name</th>
                                                <th>Store Name</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Balance</th>
                                                <th>Joined At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dealer->resellers as $reseller)
                                                <tr>
                                                    <td>
                                                        <img src="{{asset($reseller->image)}}" alt="user"
                                                            class="rounded-circle avatar-sm me-1">
                                                        {{$reseller->name}}
                                                    </td>
                                                    <td>{{$reseller->store_name ?? 'N/A'}}</td>
                                                    <td>{{$reseller->phone}}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{$reseller->status == 'active' ? 'success' : 'danger'}}">
                                                            {{ucfirst($reseller->status)}}
                                                        </span>
                                                    </td>
                                                    <td>{{$reseller->balance}}</td>
                                                    <td>{{$reseller->created_at->format('d M, Y')}}</td>
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
        </div>
    </div>
@endsection