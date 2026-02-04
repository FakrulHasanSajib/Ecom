@extends('backEnd.layouts.master')
@section('title', 'Reseller Manage')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Reseller Manage</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Reseller Manage</h4>
                </div>
            </div>
        </div>

        @include('backEnd.dealer.nav')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                         <form action="" method="get" class="d-flex justify-content-end">
                            <div class="form-group mr-2">
                                <input type="text" name="dealer_id" class="form-control" placeholder="Dealer ID" value="{{request()->dealer_id}}">
                            </div>
                            <div class="form-group mr-2 ml-2">
                                <input type="text" name="reseller_id" class="form-control" placeholder="Reseller ID" value="{{request()->reseller_id}}">
                            </div>
                            <div class="form-group ml-2">
                                <button type="submit" class="btn btn-primary"><i class="fe-search"></i></button>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Store Name</th>
                                        <th>Phone</th>
                                        <th>Dealer</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $key => $value)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <img src="{{asset($value->image)}}" class="backend_image" alt="" width="50"
                                                    height="50" style="object-fit: cover; border-radius: 50%;">
                                                {{$value->name}}
                                            </td>
                                            <td>D{{$value->dealer_id}}R{{$value->id}}</td>
                                            <td>{{$value->store_name}}</td>
                                            <td>{{$value->phone}}</td>
                                            <td>{{$value->dealer ? $value->dealer->name : 'N/A'}}</td>
                                            <td>
                                                @if($value->status == 'active')
                                                    <span class="badge bg-soft-success text-success">Active</span>
                                                @else
                                                    <span class="badge bg-soft-danger text-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="button-list">
                                                    @if($value->status == 'active')
                                                        <form method="post" action="{{route('admin.reseller.inactive')}}"
                                                            style="display:inline">
                                                            @csrf
                                                            <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                            <button type="button" class="change-confirm btn btn-xs  btn-success"><i
                                                                    class="fe-thumbs-down"></i></button>
                                                        </form>
                                                    @else
                                                        <form method="post" action="{{route('admin.reseller.active')}}"
                                                            style="display:inline">
                                                            @csrf
                                                            <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                            <button type="button" class="change-confirm btn btn-xs  btn-danger"><i
                                                                    class="fe-thumbs-up"></i></button>
                                                        </form>
                                                    @endif

                                                    <a href="{{route('admin.reseller.profile', $value->id)}}"
                                                        class="btn btn-xs btn-blue"><i class="fe-eye"></i></a>

                                                    <form method="post" action="{{route('admin.reseller.destroy')}}"
                                                        style="display:inline">
                                                        @csrf
                                                        <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                        <button type="submit" class="delete-confirm btn btn-xs btn-danger"><i
                                                                class="fe-trash-2"></i></button>
                                                    </form>
                                                </div>
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
@section('script')
    <script>
        $('.change-confirm').on('click', function (event) {
            event.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to change the status?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
        $('.delete-confirm').on('click', function (event) {
            event.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to delete this?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
    </script>
@endsection