@extends('backEnd.dealer.panel.layout.master')
@section('title', 'Order History')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Order History (Resellers)</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Order ID</th>
                                        <th>Reseller</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $key => $order)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$order->created_at->format('d-m-Y')}}</td>
                                            <td>{{$order->invoice_id}}</td>
                                            <td>
                                                @php
                                                    $reseller = \App\Models\Reseller::find($order->customer_id);
                                                @endphp
                                                {{ $reseller ? $reseller->name : 'N/A' }}
                                            </td>
                                            <td>{{$order->amount}}</td>
                                            <td>{{$order->status->name}}</td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection