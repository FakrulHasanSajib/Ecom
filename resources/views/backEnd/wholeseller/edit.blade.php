@extends('backEnd.layouts.master')
@section('title','Wholesaler Edit')
@section('css')
<link href="{{asset('public/public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('wholeseller.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Wholesaler Edit</h4>
            </div>
    </div>       
    <!-- end page title --> 
   <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                            <form action="{{ route('wholeseller.update') }}" method="POST" class="row" data-parsley-validate
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $edit_data->id }}" name="hidden_id">

                                <!-- Name -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Name *</span>
                                        <input type="text"
                                            class="form-control border border-dark rounded-3 @error('name') is-invalid @enderror"
                                            name="name" value="{{ $edit_data->name }}" id="name" required>
                                        @error('name')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Email *</span>
                                        <input type="text"
                                            class="form-control border border-dark rounded-3 @error('email') is-invalid @enderror"
                                            name="email" value="{{ $edit_data->email }}" id="email" required>
                                        @error('email')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Mobile -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Mobile *</span>
                                        <input type="mobile"
                                            class="form-control border border-dark rounded-3 @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ $edit_data->phone }}" id="phone" required>
                                        @error('phone')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Password *</span>
                                        <input type="password"
                                            class="form-control border border-dark rounded-3 @error('password') is-invalid @enderror"
                                            name="password" id="password">
                                        @error('password')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-sm-6">
                                    <div class="position-relative mb-3">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Confirm Password *</span>
                                        <input type="password"
                                            class="form-control border border-dark rounded-3 @error('confirm-password') is-invalid @enderror"
                                            name="confirm-password" id="confirm-password">
                                        @error('confirm-password')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                <div class="position-relative mb-3">
                                    <span class="position-absolute top-0 ms-2 start-0 bg-white px-1 fw-bold fs-6"
                                        style="z-index: 1; transform: translateY(-50%);">
                                        Bussiness Name
                                    </span>
                                    <input type="text" class="form-control border border-1 border-dark rounded-3 @error('business_name') is-invalid @enderror"
                                        name="business_name" value="{{ old('business_name') }}" id="business_name" value="{{$edit_data->business_name}}">
                                    @error('business_name')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="position-relative mb-3">
                                    <span class="position-absolute top-0 ms-2 start-0 bg-white px-1 fw-bold fs-6"
                                        style="z-index: 1; transform: translateY(-50%);">
                                        Bussiness Address
                                    </span>
                                    <input type="text" class="form-control border border-1 border-dark rounded-3 @error('address') is-invalid @enderror"
                                        name="address" value="{{ old('address') }}" id="address" value="{{$edit_data->address}}">
                                    @error('address')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                                <!-- Image -->
                                <div class="col-sm-6 mb-3">
                                    <div class="position-relative">
                                        <span class="position-absolute top-0 start-0 ms-2 bg-white px-1 fw-bold fs-6"
                                            style="z-index: 1; transform: translateY(-50%);">Image *</span>
                                        <input type="file"
                                            class="form-control border border-dark rounded-3 @error('image') is-invalid @enderror"
                                            name="image" id="image">
                                        <img src="{{ asset($edit_data->image) }}" alt="" class="mt-2"
                                            height="50">
                                        @error('image')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-sm-6 mb-3">
                                    <div class="form-group">
                                        <label for="status" class="d-block fw-bold fs-6">Status</label>
                                        <label class="switch">
                                            <input type="checkbox" value="1" name="status"
                                                @if ($edit_data->status == "active") checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                        @error('status')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="col-12">
                                    <input type="submit" class="btn btn-success px-4" value="Submit">
                                </div>
                            </form>


                        </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
   </div>
</div>
@endsection


@section('script')
<script src="{{asset('public/public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
@endsection