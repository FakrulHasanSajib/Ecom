@extends('backEnd.layouts.master')
@section('title', 'Payment Requests')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dealer & Reseller Payment Requests</h4>
                </div>
            </div>
        </div>

        @include('backEnd.dealer.nav')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Account</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawals as $withdrawal)
                                    <tr>
                                        <td>{{$withdrawal->created_at->format('d-m-Y')}}</td>
                                        <td>
                                            <span class="badge bg-{{$withdrawal->type == 'dealer' ? 'purple' : 'info'}}">
                                                {{ucfirst($withdrawal->type)}}
                                            </span>
                                        </td>
                                        <td>
                                            @if($withdrawal->type == 'dealer')
                                                D{{$withdrawal->dealer_id}}
                                            @elseif($withdrawal->type == 'reseller' && $withdrawal->reseller)
                                                D{{$withdrawal->reseller->dealer_id}}R{{$withdrawal->reseller_id}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($withdrawal->type == 'dealer')
                                                {{ $withdrawal->dealer->name ?? 'N/A' }}
                                            @else
                                                {{ $withdrawal->reseller->name ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>{{$withdrawal->amount}}</td>
                                        <td>{{$withdrawal->method}}</td>
                                        <td>{{$withdrawal->account_info}}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{$withdrawal->status == 'approved' ? 'success' : ($withdrawal->status == 'rejected' ? 'danger' : 'warning')}}">
                                                {{ucfirst($withdrawal->status)}}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{$withdrawal->id}}">
                                                <i class="fe-edit"></i>
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="editModal{{$withdrawal->id}}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Status</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('admin.dealer.payment_status')}}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="{{$withdrawal->id}}">
                                                                <div class="form-group mb-3">
                                                                    <label>Status</label>
                                                                    <select name="status" class="form-control">
                                                                        <option value="pending" {{$withdrawal->status == 'pending' ? 'selected' : ''}}>Pending</option>
                                                                        <option value="approved"
                                                                            {{$withdrawal->status == 'approved' ? 'selected' : ''}}>Approved</option>
                                                                        <option value="rejected"
                                                                            {{$withdrawal->status == 'rejected' ? 'selected' : ''}}>Rejected</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Note</label>
                                                                    <textarea name="note"
                                                                        class="form-control">{{$withdrawal->note}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $withdrawals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection