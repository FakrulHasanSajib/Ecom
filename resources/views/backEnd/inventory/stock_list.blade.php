@extends('backEnd.layouts.master')
@section('title', 'Stock Product List')
@section('css')
    <link href="{{asset('public/backEnd')}}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('public/backEnd')}}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{route('admin.purchase.create')}}" class="btn btn-primary rounded-pill"><i
                                class="fe-plus"></i> Add Stock</a>
                    </div>
                    <h4 class="page-title">Stock Product List</h4>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="row">
            <div class="col-md-4">
                <div class="card widget-flat text-bg-primary">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-currency-usd widget-icon bg-white-light-fill text-white"></i>
                        </div>
                        <h5 class="fw-normal mt-0" title="Revenue">Total Inventory Value</h5>
                        <h3 class="mt-3 mb-3 text-white">{{ number_format($grand_total, 2) }}</h3>
                        <p class="mb-0">Based on Purchase Price</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Purchase Price</th>
                                        <th>Stock</th>
                                        <th>Total Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $key => $value)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <img src="{{asset($value->image->image ?? 'backEnd/assets/images/product_image_placeholder.jpg')}}"
                                                    alt="" class="rounded" height="40" width="40">
                                            </td>
                                            <td title="{{$value->name}}">
                                                {{ Str::limit($value->name, 20) }}
                                            </td>
                                            <td>{{$value->product_code}}</td>
                                            <td>{{ number_format($value->purchase_price, 2) }}</td>
                                            <td>
                                                @if($value->stock < 10)
                                                    <span class="badge bg-danger">{{ $value->stock }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ $value->stock }}</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ number_format($value->total_value, 2) }}</strong></td>
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

@section('script')
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            "use strict";
            $("#datatable").DataTable({
                "pageLength": 50,
                "order": [[5, "desc"]] // Sort by Stock (index 5) descending by default
            });
        });
    </script>
@endsection