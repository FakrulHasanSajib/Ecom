@extends('backEnd.layouts.master')
@section('title', 'Dealer Edit')
@section('css')
    <link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{route('admin.dealer.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                    </div>
                    <h4 class="page-title">Dealer Edit</h4>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.dealer.update')}}" method="POST" class="row" data-parsley-validate=""
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{$edit_data->id}}" name="hidden_id">

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ $edit_data->name }}" id="name" required="">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="store_name" class="form-label">Store Name *</label>
                                    <input type="text" class="form-control @error('store_name') is-invalid @enderror"
                                        name="store_name" value="{{ $edit_data->store_name }}" id="store_name" required="">
                                    @error('store_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Phone *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ $edit_data->phone }}" id="phone" required="">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ $edit_data->email }}" id="email" required="">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Password (Leave empty if not changing)</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" id="password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                        name="address" value="{{ $edit_data->address }}" id="address">
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        name="image" id="image">
                                    <img src="{{asset($edit_data->image)}}" class="backend-image" alt="">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="active" {{$edit_data->status == 'active' ? 'selected' : ''}}>Active
                                        </option>
                                        <option value="inactive" {{$edit_data->status == 'inactive' ? 'selected' : ''}}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('public/backEnd')}}/assets/libs/parsleyjs/parsley.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/js/pages/form-validation.init.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/select2/js/select2.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/js/pages/form-advanced.init.js"></script>
@endsection