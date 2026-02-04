@extends('backEnd.layouts.master')
@section('title', 'Edit Campaign (Theme 7 - Royal Islamic)')
@section('css')
    <link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
    <style>
        .testimonial-item {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .testimonial-item label {
            font-size: 12px;
            margin-bottom: 2px;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title">Edit Campaign (Theme 7 - Royal Islamic)</h4>
                            <a href="{{route('campaign.index')}}" class="btn btn-primary"><i class="fas fa-list"></i>
                                Manage</a>
                        </div>

                        <form action="{{route('campaign.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="hidden_id" value="{{$edit_data->id}}">
                            <input type="hidden" name="theme_id" value="7">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Campaign Title (Headline) *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" id="name" value="{{ $edit_data->name }}" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Highlight Text (Banner Title) *</label>
                                        <input type="text" class="form-control" name="banner_title"
                                            value="{{ $edit_data->banner_title }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Islamic Heading (Middle Section Title)</label>
                                        <input type="text" class="form-control" name="section_title"
                                            value="{{ $edit_data->section_title }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">YouTube Video ID (Optional)</label>
                                        <input type="text" class="form-control" name="video" value="{{ $edit_data->video }}"
                                            placeholder="Ex: XXXXXX (ID Only)">
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label">Key Benefits / Features (Bulleted List)</label>
                                        <textarea name="description"
                                            class="summernote form-control @error('description') is-invalid @enderror"
                                            required>{{ $edit_data->description }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Special Review / Quote (Short Description)</label>
                                        <textarea name="short_description" class="summernote form-control"
                                            required>{{ $edit_data->short_description }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Quran Benefits (Extra Text)</label>
                                        <textarea name="section_desc"
                                            class="summernote form-control">{{ $edit_data->section_desc }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Product *</label>
                                        <select class="select2 form-control @error('product_id') is-invalid @enderror"
                                            name="product_id[]" multiple="multiple" data-placeholder="Choose ..." required>
                                            @php
                                                $product_ids = json_decode($edit_data->product_id);
                                            @endphp
                                            @foreach($products as $value)
                                                <option value="{{$value->id}}" @if(in_array($value->id, $product_ids)) selected
                                                @endif>{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('product_id')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                  <div class="mb-3">
    <label class="form-label">Main Image (Hero Image) *</label>
    <div class="input-group">
        <input type="text" class="form-control" name="image_one" id="image_one" value="{{ $edit_data->image_one }}" readonly>
        <button type="button" class="btn btn-primary" onclick="openGallery('image_one')">
            <i class="fas fa-image"></i> Change
        </button>
    </div>
    <div id="preview-image_one" class="mt-2" style="{{ $edit_data->image_one ? 'display:block;' : 'display:none;' }} width: 120px;">
        @if($edit_data->image_one)
            <img src="{{ asset($edit_data->image_one) }}" class="img-fluid rounded border">
        @else
            <img src="" class="img-fluid rounded border">
        @endif
    </div>
</div>

                                    <div class="mb-3">
    <label class="form-label">Product Show Image (3D Card) *</label>
    <div class="input-group">
        <input type="text" class="form-control" name="image_two" id="image_two" value="{{ $edit_data->image_two }}" readonly>
        <button type="button" class="btn btn-secondary" onclick="openGallery('image_two')">Change</button>
    </div>
    <div id="preview-image_two" class="mt-2" style="{{ $edit_data->image_two ? 'display:block;' : 'display:none;' }} width: 100px;">
        @if($edit_data->image_two)
            <img src="{{ asset($edit_data->image_two) }}" class="img-fluid rounded border">
        @else
            <img src="" class="img-fluid rounded border">
        @endif
    </div>
</div>

                                    <div class="mb-3">
    <label class="form-label">Background / Extra Image</label>
    <div class="input-group">
        <input type="text" class="form-control" name="image_three" id="image_three" value="{{ $edit_data->image_three }}" readonly>
        <button type="button" class="btn btn-secondary" onclick="openGallery('image_three')">Change</button>
    </div>
    <div id="preview-image_three" class="mt-2" style="{{ $edit_data->image_three ? 'display:block;' : 'display:none;' }} width: 100px;">
        @if($edit_data->image_three)
            <img src="{{ asset($edit_data->image_three) }}" class="img-fluid rounded border">
        @else
            <img src="" class="img-fluid rounded border">
        @endif
    </div>
</div>

                                    {{-- NEW: Second Background Image --}}
                                   <div class="mb-3">
    <label class="form-label">Second Background Image (Extra 2)</label>
    <div class="input-group">
        <input type="text" class="form-control" name="image_four" id="image_four" value="{{ $edit_data->image_four }}" readonly>
        <button type="button" class="btn btn-secondary" onclick="openGallery('image_four')">Change</button>
    </div>
    <div id="preview-image_four" class="mt-2" style="{{ $edit_data->image_four ? 'display:block;' : 'display:none;' }} width: 100px;">
        @if($edit_data->image_four)
            <img src="{{ asset($edit_data->image_four) }}" class="img-fluid rounded border">
        @else
            <img src="" class="img-fluid rounded border">
        @endif
    </div>
</div>


                                    <div class="mb-3">
    <label class="form-label fw-bold">Gallery Images</label>
    
    {{-- ১. নতুন ছবি অ্যাড করার বাটন --}}
    <button type="button" class="btn btn-outline-info w-100 mb-2" onclick="openGallery('multi_image_manager')">
        <i class="fas fa-images"></i> Add More Images from Gallery
    </button>

    {{-- ২. নতুন সিলেক্ট করা ছবির কন্টেইনার --}}
    <div id="product_gallery_container" class="d-flex flex-wrap gap-2 p-2 bg-light border rounded mb-3">
        {{-- নতুন ছবি এখানে আসবে --}}
    </div>
    <small id="gallery_count_display" class="text-muted d-block mb-2">0 New Files selected</small>

    {{-- ৩. ডাটাবেসে থাকা আগের ছবিগুলো (যা ডিলিট করা যাবে না এই বাটন দিয়ে, ডিলিটের জন্য আলাদা লজিক লাগে) --}}
    @if(isset($edit_data->images) && count($edit_data->images) > 0)
        <div class="card card-body bg-light border">
            <p class="mb-2 fw-bold small text-muted">Currently Active Images:</p>
            <div class="d-flex flex-wrap gap-2">
                @foreach($edit_data->images as $gallery_img)
                    <div class="position-relative border p-1 bg-white rounded">
                        <img src="{{ asset($gallery_img->image) }}" style="height: 60px; width: 60px; object-fit: cover;">
                        {{-- ডিলিট বাটন (যদি আপনার রাউট থাকে) --}}
                        {{-- <a href="{{ route('campaign.image.delete', $gallery_img->id) }}" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" onclick="return confirm('Delete?')">x</a> --}}
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
                                    {{-- ================================================== --}}
                                    {{-- EDIT TESTIMONIAL SECTION START --}}
                                    {{-- ================================================== --}}
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0">Testimonials</label>
                                            <button type="button" class="btn btn-sm btn-info text-white" id="addTestimonial">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>
                                        
                                        <div id="testimonial_container">
                                            @php
                                                $testimonials = json_decode($edit_data->testimonials, true) ?? [];
                                            @endphp

                                            @foreach($testimonials as $key => $item)
                                                <div class="testimonial-item">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-secondary">Review #{{ $key + 1 }}</span>
                                                        <button type="button" class="btn btn-danger btn-sm p-0 px-2 remove-testimonial"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                    
                                                    <div class="mb-2">
                                                        <label>Type</label>
                                                        <select name="testimonials[{{$key}}][type]" class="form-control form-control-sm testimonial-type">
                                                            <option value="text" {{ (isset($item['type']) && $item['type'] == 'text') ? 'selected' : '' }}>Text Review</option>
                                                            <option value="video" {{ (isset($item['type']) && $item['type'] == 'video') ? 'selected' : '' }}>Video (Link/Iframe)</option>
                                                            <option value="image" {{ (isset($item['type']) && $item['type'] == 'image') ? 'selected' : '' }}>Image Review</option>
                                                        </select>
                                                    </div>

                                                    {{-- Text/Video Content --}}
                                                    <div class="mb-2 content-field" style="{{ (isset($item['type']) && $item['type'] == 'image') ? 'display:none;' : '' }}">
                                                        <label>Content</label>
                                                        <textarea name="testimonials[{{$key}}][content]" class="form-control form-control-sm" rows="2" 
                                                            placeholder="Write review or Paste Video Link...">{{ $item['content'] ?? '' }}</textarea>
                                                    </div>

                                                    {{-- Image Content --}}
                                                    <div class="mb-2 image-field" style="{{ (!isset($item['type']) || $item['type'] != 'image') ? 'display:none;' : '' }}">
                                                        <label>Upload Image</label>
                                                        <input type="file" name="testimonials[{{$key}}][image]" class="form-control form-control-sm">
                                                        
                                                        {{-- Hidden input to keep old image if no new upload --}}
                                                        <input type="hidden" name="testimonials[{{$key}}][old_image]" value="{{ $item['image'] ?? '' }}">
                                                        
                                                        @if(isset($item['image']) && $item['type'] == 'image')
                                                            <div class="mt-1">
                                                                <img src="{{ asset($item['image']) }}" height="40" class="rounded">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted d-block mt-1">Select Type: Text, Video (Youtube Link/Iframe), or Image.</small>
                                    </div>
                                    {{-- EDIT TESTIMONIAL SECTION END --}}
                                    {{-- ================================================== --}}


                                    <div class="mb-3">
                                        <label class="form-label">Review Rating (Star Count)</label>
                                        <input type="number" class="form-control" name="review"
                                            value="{{ $edit_data->review }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label d-block">Campaign Status</label>
                                        
                                        <div style="border: 1px solid #ced4da; padding: 10px; border-radius: 5px; background: #fff;">
                                            {{-- Active Option --}}
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="status" id="status_active" value="1" 
                                                    {{ $edit_data->status == 1 ? 'checked' : '' }}
                                                    style="cursor: pointer;">
                                                <label class="form-check-label text-success fw-bold" for="status_active" style="cursor: pointer; font-size: 15px; font-weight: 600;">
                                                    ✅ Active (ক্যাম্পেইন চালু)
                                                </label>
                                            </div>

                                            {{-- Inactive Option --}}
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0"
                                                    {{ $edit_data->status == 0 ? 'checked' : '' }}
                                                    style="cursor: pointer;">
                                                <label class="form-check-label text-danger" for="status_inactive" style="cursor: pointer; font-size: 15px;">
                                                    ❌ Inactive (ক্যাম্পেইন বন্ধ)
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="slug" class="form-label">Slug (URL) *</label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                            name="slug" id="slug" value="{{ $edit_data->slug }}" required>
                                        <small class="text-muted">টাইটেল বা প্রোডাক্ট পরিবর্তন করলে এটি আপডেট হবে, অথবা আপনি নিজেও এডিট করতে পারেন।</small>
                                        @error('slug')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Update
                                            Campaign (Theme 7)</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
     @include('backEnd.layouts.media_manager_modal')
@endsection

@section('script')
    <script src="{{asset('public/backEnd')}}/assets/libs/select2/js/select2.min.js"></script>
    <script src="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
            $('.summernote').summernote({
                height: 200
            });

            // ==========================================
            // TESTIMONIAL DYNAMIC LOGIC (EDIT)
            // ==========================================
            
            // Start index from existing count to avoid ID conflicts
            let testimonialIndex = {{ isset($testimonials) ? count($testimonials) : 0 }};

            $('#addTestimonial').on('click', function() {
                let row = `
                    <div class="testimonial-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary">Review #${testimonialIndex + 1}</span>
                            <button type="button" class="btn btn-danger btn-sm p-0 px-2 remove-testimonial"><i class="fas fa-trash"></i></button>
                        </div>
                        
                        <div class="mb-2">
                            <label>Type</label>
                            <select name="testimonials[${testimonialIndex}][type]" class="form-control form-control-sm testimonial-type">
                                <option value="text">Text Review</option>
                                <option value="video">Video (Link/Iframe)</option>
                                <option value="image">Image Review</option>
                            </select>
                        </div>

                        <div class="mb-2 content-field">
                            <label>Content</label>
                            <textarea name="testimonials[${testimonialIndex}][content]" class="form-control form-control-sm" rows="2" placeholder="Write review or Paste Video Link..."></textarea>
                        </div>

                        <div class="mb-2 image-field" style="display:none;">
                            <label>Upload Image</label>
                            <input type="file" name="testimonials[${testimonialIndex}][image]" class="form-control form-control-sm">
                        </div>
                    </div>
                `;
                $('#testimonial_container').append(row);
                testimonialIndex++;
            });

            // Remove Item
            $(document).on('click', '.remove-testimonial', function() {
                $(this).closest('.testimonial-item').remove();
            });

            // Show/Hide Fields based on Type
            $(document).on('change', '.testimonial-type', function() {
                let type = $(this).val();
                let row = $(this).closest('.testimonial-item');

                if(type === 'image') {
                    row.find('.content-field').hide();
                    row.find('.image-field').show();
                } else {
                    // text or video
                    row.find('.content-field').show();
                    row.find('.image-field').hide();
                    
                    // Change placeholder based on type
                    let placeholder = (type === 'video') ? "YouTube Video ID (Ex: eIrRj6vDddU) OR Full Iframe Code (<iframe...)" : "Write review text here...";
                    row.find('.content-field textarea').attr('placeholder', placeholder);
                }
            });

            // ==========================================
            // SLUG LOGIC
            // ==========================================
            $('#name').on('input', function() {
                let slugField = $('#slug');
                if (slugField.data('manual') !== true) {
                    let slug = $(this).val()
                        .toLowerCase().trim()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    slugField.val(slug);
                }
            });

            $('select[name="product_id[]"]').on('change', function() {
                let slugField = $('#slug');
                if (slugField.data('manual') !== true) {
                    var data = $(this).select2('data');
                    if (data && data.length > 0) {
                        var productName = data[0].text;
                        let slug = productName
                            .toLowerCase().trim()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                        slugField.val(slug);
                    }
                }
            });

            $('#slug').on('input', function() {
                $(this).data('manual', true);
            });
        });
    </script>
@endsection