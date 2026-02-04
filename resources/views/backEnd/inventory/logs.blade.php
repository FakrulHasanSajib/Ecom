@extends('backEnd.layouts.master')
@section('title', 'Inventory Logs')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Stock Movement Logs</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Reference</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('d M Y h:i A') }}</td>
                                            <td>
                                                @if($log->product)
                                                    <a href="{{route('products.edit', $log->product_id)}}"
                                                        class="text-dark">{{ $log->product->name }}</a>
                                                @else
                                                    Unknown Product
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->type == 'purchase' || $log->type == 'return')
                                                    <span class="badge bg-success">{{ ucfirst($log->type) }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ ucfirst($log->type) }}</span>
                                                @endif
                                            </td>
                                            <td class="{{ $log->quantity > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                {{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}
                                            </td>
                                            <td>{{ $log->ref_id ?? 'N/A' }}</td>
                                            <td>{{ $log->current_stock }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection