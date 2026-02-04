@extends('backEnd.layouts.master')
@section('title', 'Inventory Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Inventory</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Inventory Overview</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xl-4">
                <div class="widget-rounded-circle card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                    <i class="fe-shopping-bag font-22 avatar-title text-primary"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$total_stock_qty}}</span>
                                    </h3>
                                    <p class="text-muted mb-1 text-truncate">Total Stock Qty</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="widget-rounded-circle card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                    <i class="fe-dollar-sign font-22 avatar-title text-success"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <h3 class="text-dark mt-1">৳<span
                                            data-plugin="counterup">{{number_format($total_stock_value)}}</span></h3>
                                    <p class="text-muted mb-1 text-truncate">Stock Value</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="widget-rounded-circle card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-soft-danger border-danger border">
                                    <i class="fe-alert-circle font-22 avatar-title text-danger"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <h3 class="text-dark mt-1"><span
                                            data-plugin="counterup">{{$low_stock_products->total()}}</span></h3>
                                    <p class="text-muted mb-1 text-truncate">Low Stock Items</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Low Stock Products</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Current Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($low_stock_products as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->image)
                                                        <img src="{{asset($product->image->image)}}" alt="Img" height="40"
                                                            class="me-2">
                                                    @endif
                                                    <div>
                                                        <h5 class="m-0 font-14">{{ $product->name }}</h5>
                                                        <span class="font-12 text-muted">ID: {{ $product->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>৳{{ $product->new_price }}</td>
                                            <td><span class="badge bg-danger">{{ $product->stock }}</span></td>
                                            <td>
                                                <a href="{{route('products.edit', $product->id)}}"
                                                    class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-2">
                                {{ $low_stock_products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection