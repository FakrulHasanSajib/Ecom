@extends('backEnd.layouts.master')
@section('title', 'Order List')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dealer & Reseller Orders</h4>
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
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>Type</th>
                                    <th>Name</th>
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
                                        <td>{{ucfirst($order->order_type)}}</td>
                                        <td>
                                            {{ $order->customer ? $order->customer->name : 'Guest' }}
                                        </td>
                                        <td>{{$order->amount}}</td>
                                        <td>{{$order->status->name}}</td>
                                        <td>
                                            <a href="{{route('admin.order.invoice', $order->invoice_id)}}"
                                                class="btn btn-info btn-sm" target="_blank"><i class="fe-eye"></i></a>
                                            <a href="{{route('admin.order.process', $order->invoice_id)}}"
                                                class="btn btn-primary btn-sm"><i class="fe-edit"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection