@extends('backEnd.layouts.master')
@section('title','TikTok Pixel Create')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/css/switchery.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('tiktok_pixels.index')}}" class="btn btn-primary rounded-pill">Manage TikTok Pixels</a>
                </div>
                <h4 class="page-title">TikTok Pixel Create</h4>
            </div>
        </div>
    </div>       
    <div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{route('tiktok_pixels.store')}}" method="POST" class=row data-parsley-validate=""  enctype="multipart/form-data">
                    @csrf
                    
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="pixel_id" class="form-label">TikTok Pixel ID *</label>
                            <input type="text" class="form-control @error('pixel_id') is-invalid @enderror" name="pixel_id" value="{{ old('pixel_id') }}" id="pixel_id" required="">
                            @error('pixel_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12">
    <div class="form-group mb-3">
        <label for="ad_account_id" class="form-label">Ad Account ID *</label>
        <input type="text" class="form-control" name="ad_account_id" id="ad_account_id" required="" value="{{old('ad_account_id')}}" placeholder="Enter TikTok Ad Account ID">
        @error('ad_account_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="access_token" class="form-label">TikTok Access Token (Conversion API)</label>
                            <input type="text" class="form-control @error('access_token') is-invalid @enderror" name="access_token" value="{{ old('access_token') }}" id="access_token">
                            @error('access_token')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="status" checked>
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
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/switchery.min.js"></script>
<script>
    $(document).ready(function(){
        var elem = document.querySelector('.js-switch');
        var init = new Switchery(elem);
    });
</script>
@endsection