@extends('backEnd.layouts.master')
@section('title', 'Dealer Order List')
@section('css')
    <link href="{{ asset('public/backEnd/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/backEnd/assets/libs/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.dealer.index') }}">Dealer Manage</a></li>
                            <li class="breadcrumb-item active">Order List</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Dealer Manage</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        @include('backEnd.dealer.nav')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="datatable-buttons">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Invoice</th>
                                        <th>Reseller Info</th>
                                        <th>Customer Info</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key => $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->created_at->format('d-m-Y') }}<br>{{ $value->created_at->format('h:i A') }}</td>
                                            <td>D{{ $value->reseller->dealer_id ?? '?' }}R{{ $value->reseller->id ?? '?' }}</td>
                                            <td>{{ $value->invoice_id }}</td>
                                            <td>
                                                {{ $value->reseller->name ?? 'N/A' }}<br>
                                                {{ $value->reseller->phone ?? '' }}
                                            </td>
                                            <td>
                                                {{ $value->shipping->name ?? 'Guest' }}<br>
                                                {{ $value->shipping->phone ?? '' }}
                                            </td>
                                            <td>{{ $value->amount }}</td>
                                            <td>{{ $value->status->name ?? 'Pending' }}</td>
                                            <td>
                                                <div class="button-list">
                                                    <a href="{{ route('admin.order.invoice', $value->invoice_id) }}" class="btn btn-blue waves-effect waves-light btn-xs" target="_blank" title="Invoice"><i class="fe-file-text"></i></a>
                                                    <a href="{{ route('admin.order.process', $value->invoice_id) }}" class="btn btn-primary waves-effect waves-light btn-xs" title="Process"><i class="fe-settings"></i></a>
                                                    <form method="POST" action="{{ route('admin.order.destroy') }}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" value="{{ $value->id }}" name="id">
                                                        <button type="submit" class="btn btn-danger waves-effect waves-light btn-xs" onclick="return confirm('Are you sure?')"><i class="fe-trash-2"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('public/backEnd/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('public/backEnd/assets/js/pages/datatables.init.js') }}"></script>
@endsection
