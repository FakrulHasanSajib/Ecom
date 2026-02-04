@extends('backEnd.dealer.panel.layout.master')
@section('title', 'Withdrawals')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Withdrawals & Payment Requests</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-bordered mb-3">
                            <li class="nav-item">
                                <a href="#my_withdrawals" data-bs-toggle="tab" aria-expanded="false"
                                    class="nav-link active">
                                    <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                    <span class="d-none d-md-block">My Withdrawals</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#reseller_requests" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                    <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                    <span class="d-none d-md-block">Reseller Requests</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane show active" id="my_withdrawals">
                                <!-- Withdrawal Form -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h5>Request New Withdrawal</h5>
                                        <form action="{{route('dealer.withdrawals.store')}}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="number" name="amount" class="form-control"
                                                        placeholder="Amount" min="10" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <select name="method" class="form-control" required>
                                                        <option value="">Select Method</option>
                                                        <option value="bkash">Bkash</option>
                                                        <option value="nagad">Nagad</option>
                                                        <option value="bank">Bank</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="account_info" class="form-control"
                                                        placeholder="Account Number / Info" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-success w-100">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="mt-2">
                                            <strong>Current Balance: {{ Auth::guard('dealer')->user()->balance }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- My History Table -->
                                <table class="table table-striped dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Account</th>
                                            <th>Status</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($my_withdrawals as $withdrawal)
                                            <tr>
                                                <td>{{$withdrawal->created_at->format('d-m-Y')}}</td>
                                                <td>{{$withdrawal->amount}}</td>
                                                <td>{{$withdrawal->method}}</td>
                                                <td>{{$withdrawal->account_info}}</td>
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

                            <div class="tab-pane" id="reseller_requests">
                                <!-- Reseller Requests Table -->
                                <table class="table table-striped dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Reseller</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Account</th>
                                            <th>Status</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reseller_withdrawals as $r_withdrawal)
                                            <tr>
                                                <td>{{$r_withdrawal->created_at->format('d-m-Y')}}</td>
                                                <td>{{$r_withdrawal->reseller->name ?? 'N/A'}}</td>
                                                <td>{{$r_withdrawal->amount}}</td>
                                                <td>{{$r_withdrawal->method}}</td>
                                                <td>{{$r_withdrawal->account_info}}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{$r_withdrawal->status == 'approved' ? 'success' : ($r_withdrawal->status == 'rejected' ? 'danger' : 'warning')}}">
                                                        {{ucfirst($r_withdrawal->status)}}
                                                    </span>
                                                </td>
                                                <td>{{$r_withdrawal->note}}</td>
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
@endsection