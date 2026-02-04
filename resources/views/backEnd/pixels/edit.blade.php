@extends('backEnd.layouts.master')
@section('title','Pixels Edit')
@section('css')
<link href="{{asset('backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('pixels.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Pixels Edit</h4>
            </div>
        </div>
    </div>       
    <div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{route('pixels.update')}}" method="POST" class=row data-parsley-validate=""  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$edit_data->id}}" name="id">
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="code" class="form-label">Pixels ID *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $edit_data->code}}" id="code" required="">
                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="meta_access_token" class="form-label">Meta Access Token</label>
                            <input type="text" class="form-control @error('meta_access_token') is-invalid @enderror" name="meta_access_token" value="{{ $edit_data->meta_access_token }}" id="meta_access_token">
                            @error('meta_access_token')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- ✅ Ad Account ID-এর জন্য নতুন ইনপুট ফিল্ড যোগ করা হলো --}}
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="ad_account_id" class="form-label">Ad Account ID (act_xxxxxxx) *</label>
                            <input type="text" class="form-control @error('ad_account_id') is-invalid @enderror" name="ad_account_id" value="{{ $edit_data->ad_account_id}}" id="ad_account_id" required="">
                            @error('ad_account_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    {{-- ✅ নতুন ফিল্ড যোগ শেষ --}}
                    
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="status" @if($edit_data->status==1)checked @endif>
                              <span class="slider round"></span>
                            </label>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>

                </form>

            </div> </div> </div> </div>
</div>
@endsection


@section('script')
<script src="{{asset('backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
@endsection