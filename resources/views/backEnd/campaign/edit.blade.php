@extends('backEnd.layouts.master')
@section('title','Landing Page Edit')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
<style>
    /* Common Gallery Item Style for Single & Multi */
    .gallery-preview-box {
        width: 100px;
        height: 100px; /* Uniform Height */
        border: 1px dashed #ccc;
        display: none; /* Controlled by JS/Blade */
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-top: 5px;
        margin-bottom: 5px;
        position: relative;
    }

    .gallery-item-card {
        width: 100px;
        height: 100px;
        border: 1px solid #ddd;
        padding: 2px;
        border-radius: 5px;
        position: relative;
        overflow: hidden;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .gallery-item-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Close Button Style */
    .btn-remove-image {
        position: absolute;
        top: 0;
        right: 0;
        width: 20px;
        height: 20px;
        padding: 0;
        line-height: 20px;
        text-align: center;
        font-size: 14px;
        border-radius: 0 0 0 5px;
        z-index: 10;
        cursor: pointer;
    }

    .edit-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 1px solid #ddd;
        margin: 5px;
        padding: 2px;
        border-radius: 5px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('campaign.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Landing Page Edit</h4>
            </div>
        </div>
    </div>       
    <div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <form action="{{route('campaign.update')}}" method="POST" class=row data-parsley-validate=""  enctype="multipart/form-data" name="editForm">
                    @csrf
                    <input type="hidden" value="{{$edit_data->id}}" name="hidden_id">

                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="banner" class="form-label">Image</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control @error('banner') is-invalid @enderror" name="banner" id="banner_url" placeholder="Select from Gallery" readonly value="{{ $edit_data->banner }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" onclick="openGallery('banner_url')">Browse Gallery</button>
                                </div>
                            </div>
                            <div id="preview-banner_url" class="gallery-preview-box" style="{{ $edit_data->banner ? 'display:flex' : 'display:none' }}">
                                @if($edit_data->banner)
                                    <div class="gallery-item-card w-100 h-100 border-0 m-0">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-image" onclick="removeSingleImage('preview-banner_url', 'banner_url')">×</button>
                                        <img src="{{ asset($edit_data->banner) }}">
                                    </div>
                                @endif
                            </div>
                            @error('banner')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="banner_title" class="form-label">Banner Title</label>
                            <input type="text" class="form-control @error('banner_title') is-invalid @enderror" name="banner_title" value="{{ $edit_data->banner_title}}"  id="banner_title">
                            @error('banner_title')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Landing Page Title *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $edit_data->name}}"  id="name" required="">
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="section_desc" class="form-label">Info Product </label>
                            <input type="text" class="form-control @error('section_desc') is-invalid @enderror" name="section_desc" value="{{ $edit_data->section_desc}}"  id="section_desc">
                            @error('section_desc')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="video" class="form-label">Video </label>
                            <input type="text" class="form-control @error('video') is-invalid @enderror" name="video" value="{{ $edit_data->video}}"  id="video">
                            @error('video')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="theme_id" class="form-label">Theme Use *</label>
                            <select name="theme_id" class="form-control" >
                                <option value="1" @if($edit_data->theme_id == 1) selected @endif>Default</option>
                                <option value="2" @if($edit_data->theme_id == 2) selected @endif>Second Theme</option>
                                <option value="3" @if($edit_data->theme_id == 3) selected @endif>Third Theme</option>
                                <option value="4" @if($edit_data->theme_id == 4) selected @endif>Four Theme</option>
                                <option value="5" @if($edit_data->theme_id == 5) selected @endif>Five Theme</option>
                                <option value="6" @if($edit_data->theme_id == 6) selected @endif>Six Theme (Al-Quran)</option>
                                <option value="8" @if($edit_data->theme_id == 8) selected @endif>Theme 8 (Video & Form Focus)</option>
                                <option value="10" @if($edit_data->theme_id == 10) selected @endif>Ten Theme (Fashion - Multi Product)</option>
                                <option value="11" @if($edit_data->theme_id == 11) selected @endif>Eleven Theme (Fashion - Rozy)</option>
                            </select>
                            @error('theme_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="image_one" class="form-label">Image One</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control @error('image_one') is-invalid @enderror" name="image_one" id="image_one_url" placeholder="Select from Gallery" readonly value="{{ $edit_data->image_one }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" onclick="openGallery('image_one_url')">Browse Gallery</button>
                                </div>
                            </div>
                            <div id="preview-image_one_url" class="gallery-preview-box" style="{{ $edit_data->image_one ? 'display:flex' : 'display:none' }}">
                                @if($edit_data->image_one)
                                    <div class="gallery-item-card w-100 h-100 border-0 m-0">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-image" onclick="removeSingleImage('preview-image_one_url', 'image_one_url')">×</button>
                                        <img src="{{ asset($edit_data->image_one) }}">
                                    </div>
                                @endif
                            </div>
                            @error('image_one')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="image_two" class="form-label">Image Two</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control @error('image_two') is-invalid @enderror" name="image_two" id="image_two_url" placeholder="Select from Gallery" readonly value="{{ $edit_data->image_two }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" onclick="openGallery('image_two_url')">Browse Gallery</button>
                                </div>
                            </div>
                            <div id="preview-image_two_url" class="gallery-preview-box" style="{{ $edit_data->image_two ? 'display:flex' : 'display:none' }}">
                                @if($edit_data->image_two)
                                    <div class="gallery-item-card w-100 h-100 border-0 m-0">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-image" onclick="removeSingleImage('preview-image_two_url', 'image_two_url')">×</button>
                                        <img src="{{ asset($edit_data->image_two) }}">
                                    </div>
                                @endif
                            </div>
                            @error('image_two')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="image_three" class="form-label">Image Three</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control @error('image_three') is-invalid @enderror" name="image_three" id="image_three_url" placeholder="Select from Gallery" readonly value="{{ $edit_data->image_three }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" onclick="openGallery('image_three_url')">Browse Gallery</button>
                                </div>
                            </div>
                            <div id="preview-image_three_url" class="gallery-preview-box" style="{{ $edit_data->image_three ? 'display:flex' : 'display:none' }}">
                                @if($edit_data->image_three)
                                    <div class="gallery-item-card w-100 h-100 border-0 m-0">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-image" onclick="removeSingleImage('preview-image_three_url', 'image_three_url')">×</button>
                                        <img src="{{ asset($edit_data->image_three) }}">
                                    </div>
                                @endif
                            </div>
                            @error('image_three')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    @php
                      $productIds = is_string($edit_data->product_id) ? explode(',', $edit_data->product_id) : $edit_data->product_id;
                      if (is_array($productIds) && count($productIds) === 1 && str_starts_with($productIds[0], '[')) {
                        $productIds = json_decode($productIds[0], true); 
                     }
                    @endphp
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="product_id" class="form-label">Products *</label>
                             <select class="select2 form-control @error('product_id') is-invalid @enderror" name="product_id[]" data-placeholder="Choose ..." multiple>
                                <option value="">Select...</option>
                                @foreach($products as $value)
                                    <option value="{{$value->id}}"
                                            @if(in_array($value->id, old('product_id', $productIds ?? []))) selected @endif>
                                            {{$value->name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="primary_color" class="form-label">Theme Primary Color</label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color" name="primary_color" 
                                       value="{{ $edit_data->primary_color ?? '#c42831' }}" 
                                       id="primary_color" title="Choose your color" style="width: 100px;">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="image_one" class="form-label">Image One (Repeated?)</label>
                             <div class="input-group mb-2">
                                <input type="text" class="form-control" name="image_one" id="image_one_url_2" placeholder="Select from Gallery" readonly value="{{ $edit_data->image_one }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" onclick="openGallery('image_one_url_2')">Browse Gallery</button>
                                </div>
                            </div>
                            <div id="preview-image_one_url_2" class="gallery-preview-box" style="{{ $edit_data->image_one ? 'display:flex' : 'display:none' }}">
                                @if($edit_data->image_one)
                                    <div class="gallery-item-card w-100 h-100 border-0 m-0">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-image" onclick="removeSingleImage('preview-image_one_url_2', 'image_one_url_2')">×</button>
                                        <img src="{{ asset($edit_data->image_one) }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <label for="image">Review Image *</label>
                        <div class="mb-3 p-2 bg-light border rounded">
                             <button type="button" class="btn btn-primary btn-sm mb-2" onclick="openGallery('multi_image_manager')">
                                <i class="fas fa-images"></i> Add New from Gallery
                            </button>

                            <div id="product_gallery_container" class="d-flex flex-wrap gap-2"></div>
                            
                            @error('image')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="product_img mt-2 d-flex flex-wrap gap-2">
                            @foreach($edit_data->images as $image)
                            <div class="gallery-item-card position-relative" style="margin:0;">
                                <a href="{{route('campaign.image.destroy',['id'=>$image->id])}}" class="btn btn-danger btn-sm btn-remove-image" onclick="return confirm('Are you sure?')">×</a>
                                <img src="{{asset($image->image)}}" alt="" />
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="review" class="form-label">Review *</label>
                            <input type="text" class="form-control @error('review') is-invalid @enderror" name="review" value="{{ $edit_data->review}}"  id="review" required="">
                            @error('review')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-sm-6 mb-3">
                        <label for="land_image">Image Landing *</label>
                        
                        <div class="mb-3 p-2 bg-light border rounded">
                             <button type="button" class="btn btn-primary btn-sm mb-2" onclick="openGallery('landing_multi_images')">
                                <i class="fas fa-images"></i> Add New from Gallery
                            </button>

                            <div id="landing_gallery_container" class="d-flex flex-wrap gap-2"></div>
                            
                            @error('land_image')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        @if($edit_data->bookimage()->exists())
                        <div class="existing-land-images mb-3">
                            <label class="form-label text-muted">Existing Land Images:</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($edit_data->bookimage as $image)
                                    <div class="gallery-item-card existing-image-box position-relative" data-image-id="{{$image->id}}" style="margin:0;">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-image delete-existing-land-image" data-image-id="{{$image->id}}">×</button>
                                        <img src="{{asset($image->bookimage)}}" alt="Land Image">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        <input type="hidden" name="delete_land_images" id="delete_land_images" value="">
                    </div>

                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="short_description" class="form-label">Short Description</label>
                            <textarea name="short_description"  rows="6" class="summernote form-control @error('short_description') is-invalid @enderror">{{$edit_data->short_description}}</textarea>
                            @error('short_description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 mb-3">
                      <div class="form-group">
                            <label for="section_title" class="form-label">Section Title</label>
                            <input type="text" class="form-control" placeholder="Section Name" name="section_title" value="{{ $edit_data->section_title }}">
                            @error('section_title')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description"  rows="6" class="summernote form-control @error('description') is-invalid @enderror">{{$edit_data->description}}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                      <div class="form-group">
                            <label for="image_section" class="form-label">Section Image </label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control @error('image_section') is-invalid @enderror" name="image_section" id="image_section_url" placeholder="Select from Gallery" readonly value="{{ $edit_data->image_section }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" onclick="openGallery('image_section_url')">Browse Gallery</button>
                                </div>
                            </div>
                            <div id="preview-image_section_url" class="gallery-preview-box" style="{{ $edit_data->image_section ? 'display:flex' : 'display:none' }}">
                                @if($edit_data->image_section)
                                    <div class="gallery-item-card w-100 h-100 border-0 m-0">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-image" onclick="removeSingleImage('preview-image_section_url', 'image_section_url')">×</button>
                                        <img src="{{ asset($edit_data->image_section) }}">
                                    </div>
                                @endif
                            </div>
                            @error('image_section')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                                <input type="checkbox" value="1" name="status" @if($edit_data->status==1)checked @endif>
                                <span class="slider round"></span>
                            </label>
                            @error('status')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="slug" class="form-label">Slug (URL) *</label>
                            <input type="text" class="form-control" name="slug" id="slug" value="{{ $edit_data->slug }}" required>
                            <small class="text-muted">টাইটেল বা প্রোডাক্ট পরিবর্তন করলে এটি আপডেট হবে, অথবা আপনি নিজেও এডিট করতে পারেন।</small>
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
<script src="{{asset('public/backEnd/')}}/assets/libs/flatpickr/flatpickr.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-pickers.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>
<script>
  $(".summernote").summernote({
    placeholder: "Enter Your Text Here",
  });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

        var productIds = <?php 
            if (is_array($productIds)) {
                echo json_encode($productIds); 
            } elseif (!empty($productIds)) { 
                echo json_encode([$productIds]); 
            } else {
                echo '[]'; 
            }
        ?>;
        $('select[name="product_id[]"]').val(productIds).trigger('change');  
    });
</script>
<script type="text/javascript">
    // Global Function to Remove Single Image
    window.removeSingleImage = function(previewId, inputId) {
        $('#' + inputId).val(''); // Clear hidden input
        $('#' + previewId).hide().html(''); // Clear and hide preview box
    }

    $(document).ready(function () {
        // Slug Logic
        $('#name').on('input', function() {
            let slugField = $('#slug');
            if (slugField.data('manual') !== true) {
                let slug = $(this).val().toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
                slugField.val(slug);
            }
        });

        $('select[name="product_id[]"]').on('change', function() {
            let slugField = $('#slug');
            if (slugField.data('manual') !== true && $('#name').val() === '') {
                var data = $(this).select2('data');
                if (data && data.length > 0) {
                    var productName = data[0].text; 
                    let slug = productName.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
                    slugField.val(slug);
                }
            }
        });

        $('#slug').on('input', function() {
            $(this).data('manual', true);
        });

        // ==========================================
        //  MEDIA MANAGER OVERRIDE
        // ==========================================
      if (typeof useSelectedImage === 'function') {
        useSelectedImage = function() {
            
            // 1. Multiple Images (Review & Landing New)
            if (modalTargetInput === 'landing_multi_images' || modalTargetInput === 'multi_image_manager') {
                
                let containerId = '';
                let inputName = '';

                if(modalTargetInput === 'landing_multi_images'){
                    containerId = '#landing_gallery_container';
                    inputName = 'land_image[]'; 
                } else {
                    containerId = '#product_gallery_container';
                    inputName = 'image[]'; 
                }

                let container = $(containerId);
                
                if (typeof modalSelectedUrls !== 'undefined' && modalSelectedUrls.length > 0) {
                    modalSelectedUrls.forEach(url => {
                        let html = `
                            <div class="gallery-item-card" style="width: 100px; height: 100px; border:1px solid #ddd; padding:2px; border-radius:5px; position:relative;">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-image" onclick="$(this).parent().remove()">×</button>
                                <img src="${url}" style="width: 100%; height: 100%; object-fit: cover;">
                                <input type="hidden" name="${inputName}" value="${url}">
                            </div>`;
                        container.append(html);
                    });
                    
                    $('#globalMediaModal').modal('hide');
                    if(typeof clearModalSelection === 'function') clearModalSelection();
                } else {
                    alert('Please select images first');
                }
            } 
            // 2. Single Images (Banner, Image One, Image Section, etc.)
            else {
                if (typeof modalSelectedUrls !== 'undefined' && modalSelectedUrls.length > 0) {
                    var url = modalSelectedUrls[0];
                    
                    $('#' + modalTargetInput).val(url);
                    
                    // Update Preview Box with X button
                    var previewDiv = $('#preview-' + modalTargetInput);
                    if(previewDiv.length > 0) {
                        let html = `
                            <div class="gallery-item-card w-100 h-100 border-0 m-0">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-image" onclick="removeSingleImage('preview-${modalTargetInput}', '${modalTargetInput}')">×</button>
                                <img src="${url}">
                            </div>`;
                        previewDiv.html(html);
                        previewDiv.css('display', 'flex');
                    }

                    $('#globalMediaModal').modal('hide');
                    if(typeof clearModalSelection === 'function') clearModalSelection();
                }
            }
        }
    }
    });
</script>
<script type="text/javascript">
$(document).ready(function () {
    var imagesToDelete = [];
    
    // Deletion Logic for Existing Land Images (Matches Review Image style)
    $(document).on('click', '.delete-existing-land-image', function (e) {
        e.preventDefault();
        var btn = $(this);
        var imageId = btn.data('image-id');
        var card = btn.closest('.existing-image-box');
        
        if (card.hasClass('marked-for-deletion')) {
            // Restore
            card.removeClass('marked-for-deletion');
            btn.html('×'); // Back to X
            
            imagesToDelete = imagesToDelete.filter(id => id !== imageId);
        } else {
            // Delete
            card.addClass('marked-for-deletion');
            // We can hide it or just mark it. Matching Review Image behavior usually removes it from view
            // But since these are existing, we should probably just visual mark it
            // Or hide it? 
            // Let's keep the visual mark to allow undo, but change icon
            btn.html('<i class="fas fa-undo"></i>'); 
            
            if (!imagesToDelete.includes(imageId)) {
                imagesToDelete.push(imageId);
            }
        }
        
        $('#delete_land_images').val(JSON.stringify(imagesToDelete));
    });
});
</script>
@endsection