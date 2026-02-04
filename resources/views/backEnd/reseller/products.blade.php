@extends('backEnd.reseller.layout.master')
@section('title', 'Reseller Products')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Available Products</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Price (Your Buy Price)</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <img src="{{ asset($product->image->image) }}" alt="product-img"
                                                    title="product-img" class="rounded me-3" height="48" />
                                            </td>
                                            <td>
                                                <h5 class="m-0 d-inline-block align-middle"><a href="#"
                                                        class="text-dark">{{ $product->name }}</a></h5>
                                            </td>
                                            <td>
                                                ৳{{ $product->new_price }}
                                            </td>
                                            <td>
                                                <span class="badge badge-soft-success">{{ $product->stock }} Available</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('reseller.order.create', $product->id) }}"
                                                    class="btn btn-primary btn-sm">Order Now (Dropship)</a>
                                            </td>
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
@endsection