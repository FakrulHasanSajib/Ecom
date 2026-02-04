@extends('backEnd.reseller.layout.master')
@section('title', 'My Referrals')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">My Referrals</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Store Name</th>
                                        <th>Joined At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($referrals as $key => $referral)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>
                                                <img src="{{asset($referral->image ?? 'backEnd/assets/images/users/user-1.jpg')}}"
                                                    class="rounded-circle avatar-xs me-1">
                                                {{$referral->name}}
                                            </td>
                                            <td>{{$referral->phone}}</td>
                                            <td>{{$referral->store_name ?? 'N/A'}}</td>
                                            <td>{{$referral->created_at->format('d M, Y')}}</td>
                                            <td>
                                                <a href="{{route('reseller.referral.profile', $referral->id)}}"
                                                    class="btn btn-xs btn-blue" title="View Profile">
                                                    <i data-feather="eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{$referrals->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection