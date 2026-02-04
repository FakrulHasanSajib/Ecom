@extends('backEnd.layouts.master')
@section('title', 'Dealer Manage')
@section('css')
    <link href="{{asset('public/backEnd')}}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('public/backEnd')}}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{asset('public/backEnd')}}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('public/backEnd')}}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Dealer Manage</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Dealer Manage</h4>
                </div>
            </div>
        </div>

        @include('backEnd.dealer.nav')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Store Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->store_name}}</td>
                                        <td>{{$value->phone}}</td>
                                        <td>{{$value->email}}</td>
                                        <td>{{$value->balance}}</td>
                                        <td>@if($value->status == 'active')<span
                                        class="badge bg-soft-success text-success">Active</span> @else <span
                                                class="badge bg-soft-danger text-danger">Inactive</span> @endif</td>
                                        <td>
                                            <div class="button-list">
                                                @if($value->status == 'active')
                                                    <form method="post" action="{{route('admin.dealer.inactive')}}"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                        <button type="button"
                                                            class="btn btn-xs  btn-secondary waves-effect waves-light change-confirm"><i
                                                                class="fe-thumbs-down"></i></button>
                                                    </form>
                                                @else
                                                    <form method="post" action="{{route('admin.dealer.active')}}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                        <button type="button"
                                                            class="btn btn-xs  btn-success waves-effect waves-light change-confirm"><i
                                                                class="fe-thumbs-up"></i></button>
                                                    </form>
                                                @endif

                                                <a href="{{route('admin.dealer.edit', $value->id)}}"
                                                    class="btn btn-xs btn-primary waves-effect waves-light"><i
                                                        class="fe-edit-1"></i></a>

                                                <form method="post" action="{{route('admin.dealer.destroy')}}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                    <button type="submit"
                                                        class="btn btn-xs btn-danger waves-effect waves-light delete-confirm"><i
                                                            class="mdi mdi-close"></i></button>
                                                </form>
                                                <a href="{{route('admin.dealer.profile', $value->id)}}"
                                                    class="btn btn-xs btn-purple waves-effect waves-light"
                                                    title="View Profile"><i class="fe-eye"></i></a>
                                                <a href="{{route('admin.dealer.products', $value->id)}}"
                                                    class="btn btn-xs btn-info waves-effect waves-light"
                                                    title="Assign Products"><i class="fe-shopping-bag"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
@endsection


@section('script')
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/js/pages/datatables.init.js"></script>
@endsection