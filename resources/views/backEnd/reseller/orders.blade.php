@extends('backEnd.reseller.layout.master')
@section('title', 'My Orders')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">My Orders</h4>
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
                                        <th>Invoice ID</th>
                                        <th>Customer Info</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $key => $order)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{$order->invoice_id}}</td>
                                            <td>
                                                @php $shipping = $order->shipping; @endphp
                                                @if($shipping)
                                                    {{$shipping->name}}<br>
                                                    {{$shipping->phone}}<br>
                                                    {{$shipping->address}}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{$order->amount}}</td>
                                            <td>{{$order->status ? $order->status->name : 'Pending'}}</td>
                                            <td>{{$order->created_at->format('d M, Y')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{$orders->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection