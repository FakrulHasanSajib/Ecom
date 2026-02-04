@extends('backEnd.layouts.master')
@section('title', 'Purchase History')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{route('admin.purchase.create')}}" class="btn btn-primary rounded-pill"><i
                                class="fe-plus"></i> New Purchase</a>
                    </div>
                    <h4 class="page-title">Purchase History</h4>
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
                                        <th>Challan No</th>
                                        <th>Supplier</th>
                                        <th>Items</th>
                                        <th>Amount</th>
                                        <th>Note</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchases as $purchase)
                                        <tr>
                                            <td>{{ date('d M Y', strtotime($purchase->purchase_date)) }}</td>
                                            <td><a href="{{route('admin.purchase.show', $purchase->id)}}"
                                                    class="text-body fw-bold">#{{$purchase->invoice_no}}</a></td>
                                            <td>{{$purchase->supplier_name}}</td>
                                            <td>{{$purchase->details_count ?? $purchase->details->count()}} Items</td>
                                            <td>৳{{number_format($purchase->total_amount, 2)}}</td>
                                            <td>{{Str::limit($purchase->note, 30)}}</td>
                                            <td>
                                                <a href="{{route('admin.purchase.show', $purchase->id)}}"
                                                    class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                                                <a href="{{route('admin.purchase.edit', $purchase->id)}}"
                                                    class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{$purchases->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection