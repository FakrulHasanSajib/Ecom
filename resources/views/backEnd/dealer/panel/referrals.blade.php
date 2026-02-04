@extends('backEnd.dealer.panel.layout.master')
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
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($referrals as $key => $reseller)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>
                                                <img src="{{asset($reseller->image ?? 'backEnd/assets/images/users/user-1.jpg')}}"
                                                    alt="user" class="rounded-circle avatar-xs me-1">
                                                {{$reseller->name}}
                                            </td>
                                            <td>{{$reseller->phone}}</td>
                                            <td>{{$reseller->store_name ?? 'N/A'}}</td>
                                            <td>
                                                <span class="badge bg-{{$reseller->status == 'active' ? 'success' : 'danger'}}">
                                                    {{ucfirst($reseller->status)}}
                                                </span>
                                            </td>
                                            <td>{{$reseller->created_at->format('d M, Y')}}</td>
                                            <td>
                                                <a href="{{route('dealer.reseller.profile', $reseller->id)}}"
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