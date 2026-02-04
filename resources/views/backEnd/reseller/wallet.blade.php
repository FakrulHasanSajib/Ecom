@extends('backEnd.reseller.layout.master')
@section('title', 'My Wallet')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">My Wallet</h4>
                </div>
            </div>
        </div>

        <!-- Balance and Request Form -->
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Current Balance</h4>
                        <div class="widget-detail-1 text-center">
                            <h2 class="fw-normal pt-2 mb-1"> {{$reseller->balance}} </h2>
                            <p class="text-muted mb-3">Available Balance</p>
                        </div>

                        <h4 class="header-title mb-3 mt-4">Withdrawal Request</h4>
                        <form action="{{route('reseller.withdrawal.request')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" name="amount" class="form-control" min="50"
                                    max="{{$reseller->balance}}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Method</label>
                                <select name="method" class="form-control" required>
                                    <option value="bkash">Bkash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="bank">Bank</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Account Details</label>
                                <textarea name="account_info" class="form-control" rows="3" placeholder="Number / Bank Info"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- History -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Withdrawal History</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals as $withdrawal)
                                        <tr>
                                            <td>{{$withdrawal->created_at->format('d M, Y')}}</td>
                                            <td>{{ucfirst($withdrawal->method)}} <br>
                                                <small>{{$withdrawal->account_info}}</small>
                                            </td>
                                            <td>{{$withdrawal->amount}}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{$withdrawal->status == 'approved' ? 'success' : ($withdrawal->status == 'rejected' ? 'danger' : 'warning')}}">
                                                    {{ucfirst($withdrawal->status)}}
                                                </span>
                                            </td>
                                            <td>{{$withdrawal->note}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{$withdrawals->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection