@extends('wholesaler.master')
@section('title','Profile Update')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@include('backEnd.layouts.b2b_menu')
<div class="container-fluid">
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('wholesaler.dashboard') }}">Dashboard</a></li>
                    </ol>
                </div>
                <h4 class="page-title">Profile Update</h4>
            </div>
        </div>
    </div>       
    <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('wholesaler.profile.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $wholesaler->name) }}" id="name" required="">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Mobile Number *</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $wholesaler->phone) }}" id="phone" required="">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $wholesaler->email) }}" id="email" required="">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="business_name" class="form-label">Business Name *</label>
                            <input type="text" class="form-control @error('business_name') is-invalid @enderror" name="business_name" value="{{ old('business_name', $wholesaler->business_name) }}" id="business_name" required="">
                            @error('business_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $wholesaler->address) }}" id="address" required="">
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <input type="submit" class="btn btn-success" value="Update Profile">
                    </div>

                </form>

            </div> </div> </div> </div>
</div>
@endsection


@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
@endsection