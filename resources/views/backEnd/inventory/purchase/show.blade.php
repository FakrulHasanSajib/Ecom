@extends('backEnd.layouts.master')
@section('title', 'Purchase Details')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('admin.purchase.index') }}" class="btn btn-secondary rounded-pill"><i
                                class="fe-list"></i> Back to List</a>
                    </div>
                    <h4 class="page-title">Purchase Details #{{ $purchase->invoice_no }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Supplier:</strong> {{ $purchase->supplier_name }}
                            </div>
                            <div class="col-md-4">
                                <strong>Date:</strong> {{ date('d M Y', strtotime($purchase->purchase_date)) }}
                            </div>
                            <div class="col-md-4">
                                <strong>Challan No:</strong> {{ $purchase->invoice_no }}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->details as $item)
                                        <tr>
                                            <td>
                                                {{ $item->product->name }} <br>
                                                <small class="text-muted">SKU: {{ $item->product->product_code }}</small>
                                            </td>
                                            <td>{{ $item->purchase_price }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ $item->total_price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
                                        <td><strong>{{ $purchase->total_amount }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if ($purchase->note)
                            <div class="mt-3">
                                <strong>Note:</strong>
                                <p>{{ $purchase->note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection