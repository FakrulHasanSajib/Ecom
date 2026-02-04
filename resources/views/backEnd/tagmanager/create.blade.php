@extends('backEnd.layouts.master')
@section('title','Tag Manager Create')
@section('css')
<link href="{{asset('backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
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
                <h4 class="page-title">Tag Manager Create</h4>
            </div>
        </div>
    </div>       
   <div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <form action="{{route('tagmanagers.store')}}" method="POST" class="row" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- GTM ID --}}
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="code" class="form-label">Google Tag Manager ID (GTM-XXXX)</label>
                            <input type="text" class="form-control" name="code" value="{{ old('code') }}" id="code" placeholder="Optional">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <h5 class="mb-3 mt-2 text-uppercase bg-light p-2"><i class="mdi mdi-google-ads"></i> Google Ads Server Side Tracking (Optional)</h5>
                    </div>

                    {{-- Google Ads Fields --}}
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Client ID</label>
                            <input type="text" name="google_client_id" class="form-control" placeholder="Optional">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Client Secret</label>
                            <input type="text" name="google_client_secret" class="form-control" placeholder="Optional">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Refresh Token</label>
                            <textarea name="google_refresh_token" class="form-control" rows="2" placeholder="Optional"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>Developer Token</label>
                            <input type="text" name="google_developer_token" class="form-control" placeholder="Optional">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>Customer ID (No Hyphen)</label>
                            <input type="text" name="google_ads_customer_id" class="form-control" placeholder="e.g. 1234567890">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>Conversion Action ID</label>
                            <input type="text" name="google_conversion_action_id" class="form-control" placeholder="Optional">
                        </div>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="status" checked>
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