@extends('backEnd.layouts.master')
@section('title','Tag Manager Edit')
@section('css')
<link href="{{asset('backEnd')}}/assets/css/switchery.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('tagmanagers.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Tag Manager Edit</h4>
            </div>
        </div>
    </div>       
   <div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <form action="{{route('tagmanagers.update')}}" method="POST" class="row" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$edit_data->id}}" name="id">
                    
                    {{-- GTM ID --}}
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="code" class="form-label">Google Tag Manager ID</label>
                            <input type="text" class="form-control" name="code" value="{{ $edit_data->code }}" id="code" placeholder="Optional">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <h5 class="mb-3 mt-2 text-uppercase bg-light p-2"><i class="mdi mdi-google-ads"></i> Google Ads Server Side Tracking</h5>
                    </div>

                    {{-- Google Ads Fields --}}
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Client ID</label>
                            <input type="text" name="google_client_id" class="form-control" value="{{ $edit_data->google_client_id }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Client Secret</label>
                            <input type="text" name="google_client_secret" class="form-control" value="{{ $edit_data->google_client_secret }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Refresh Token</label>
                            <textarea name="google_refresh_token" class="form-control" rows="2">{{ $edit_data->google_refresh_token }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>Developer Token</label>
                            <input type="text" name="google_developer_token" class="form-control" value="{{ $edit_data->google_developer_token }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>Customer ID</label>
                            <input type="text" name="google_ads_customer_id" class="form-control" value="{{ $edit_data->google_ads_customer_id }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>Conversion Action ID</label>
                            <input type="text" name="google_conversion_action_id" class="form-control" value="{{ $edit_data->google_conversion_action_id }}">
                        </div>
                    </div>

                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="status" @if($edit_data->status==1)checked @endif>
                              <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>

                </form>

            </div>
        </div>
    </div>
   </div>
</div>
@endsection

@section('script')
<script src="{{asset('public/backEnd/')}}/assets/js/switchery.min.js"></script>
<script>
    $(document).ready(function(){
        var elem = document.querySelector('.js-switch');
        var init = new Switchery(elem);
    });
</script>
@endsection