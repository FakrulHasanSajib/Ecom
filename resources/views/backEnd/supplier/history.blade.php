@extends('backEnd.layouts.master')
@section('title', 'Supplier Purchase History')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('admin.supplier.index') }}" class="btn btn-secondary rounded-pill"><i
                                class="fe-list"></i> Back to List</a>
                    </div>
                    <h4 class="page-title">Purchase History: {{ $supplier->name }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Phone:</strong> {{ $supplier->phone }}
                            </div>
                            <div class="col-md-4">
                                <strong>Address:</strong> {{ $supplier->address }}
                            </div>
                            <div class="col-md-4">
                                <strong>Total Purchases:</strong> {{ $supplier->purchases->count() }}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice No</th>
                                        <th>Amount</th>
                                        <th>Items</th>
                                        <th>Note</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplier->purchases as $purchase)
                                        <tr>
                                            <td>{{ date('d M Y', strtotime($purchase->purchase_date)) }}</td>
                                            <td>#{{ $purchase->invoice_no }}</td>
                                            <td>{{ $purchase->total_amount }}</td>
                                            <td>{{ $purchase->details->count() }} Items</td>
                                            <td>{{ $purchase->note }}</td>
                                            <td>
                                                <a href="{{ route('admin.purchase.show', $purchase->id) }}"
                                                    class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
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