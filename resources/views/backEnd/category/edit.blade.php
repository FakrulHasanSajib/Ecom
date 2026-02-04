@extends('backEnd.layouts.master')
@section('title','Category Edit')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
<style>
    /* গ্যালারি প্রিভিউ বক্সের স্টাইল */
    .gallery-preview-box {
        width: 150px;
        height: 120px;
        border: 1px dashed #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-top: 5px;
        margin-bottom: 5px;
        background: #f8f9fa;
    }
    .gallery-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('categories.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Category Edit</h4>
            </div>
        </div>
    </div>       
    <div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{route('categories.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$edit_data->id}}" name="id">
                    
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $edit_data->name}}" id="name" required="">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="image" class="form-label">Image *</label>
                            
                            <div class="input-group">
                                <input type="text" class="form-control @error('image') is-invalid @enderror" name="image" id="category_image_url" placeholder="Select from Gallery" readonly value="{{ $edit_data->image }}" required>
                                <button type="button" class="btn btn-info" onclick="openGallery('category_image_url')">Browse Gallery</button>
                            </div>
                            
                            <div id="preview-category_image_url" class="gallery-preview-box">
                                <img src="{{asset($edit_data->image)}}" alt="" class="edit-image">
                            </div>

                            @error('image')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ $edit_data->meta_title }}" id="meta_title">
                            @error('meta_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="summernote form-control @error('meta_description') is-invalid @enderror" name="meta_description" rows="6" id="meta_description">{!!$edit_data->meta_description!!}</textarea>
                            @error('meta_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col mb-3">
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
                    <div class="col mb-3">
                        <div class="form-group">
                            <label for="front_view" class="d-block">Front View</label>
                            <label class="switch">
                                <input type="checkbox" value="1" name="front_view" @if($edit_data->front_view==1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                            @error('front_view')
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

@include('backEnd.layouts.media_manager_modal')

@endsection


@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/summernote/summernote-lite.min.js"></script>
<script>
    $(document).ready(function(){
        $(".summernote").summernote({
            placeholder: "Enter Your Text Here",
            height: 200,
        });
    });
</script>
@endsection