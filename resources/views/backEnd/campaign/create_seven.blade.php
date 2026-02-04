@extends('backEnd.layouts.master')
@section('title', 'Create Campaign (Theme 7 - Royal Islamic)')
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
                            <h4 class="card-title">Create Campaign (Theme 7 - Royal Islamic)</h4>
                            <a href="{{route('campaign.index')}}" class="btn btn-primary"><i class="fas fa-list"></i>
                                Manage</a>
                        </div>

                        <form action="{{route('campaign.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- Force Theme 7 --}}
                            <input type="hidden" name="theme_id" value="7">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Campaign Title (Headline) *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" id="name" value="{{ old('name') }}"
                                            placeholder="Ex: রাজকীয় কুরআন শিক্ষা প্যাকেজ" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Highlight Text (Banner Title) *</label>
                                        <input type="text" class="form-control" name="banner_title"
                                            value="{{ old('banner_title') }}" placeholder="Ex: মাত্র ৫ দিনে কুরআন শিখুন">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Islamic Heading (Middle Section Title)</label>
                                        <input type="text" class="form-control" name="section_title"
                                            value="{{ old('section_title') }}" placeholder="Ex: কেন এই কুরআন বিশেষ?">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">YouTube Video ID (Optional)</label>
                                        <input type="text" class="form-control" name="video" value="{{ old('video') }}"
                                            placeholder="Ex: XXXXXX (ID Only)">
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label">Key Benefits / Features (Bulleted List)</label>
                                        <textarea name="description"
                                            class="summernote form-control @error('description') is-invalid @enderror"
                                            required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Special Review / Quote (Short Description)</label>
                                        <textarea name="short_description" class="summernote form-control"
                                            required>{{ old('short_description') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Quran Benefits (Extra Text)</label>
                                        <textarea name="section_desc"
                                            class="summernote form-control">{{ old('section_desc') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Product *</label>
                                        <select class="select2 form-control @error('product_id') is-invalid @enderror"
                                            name="product_id[]" multiple="multiple" data-placeholder="Choose ..." required>
                                            @foreach($products as $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('product_id')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
    <label class="form-label">Main Image (Hero Image) *</label>
    <div class="input-group">
        {{-- name="image_one" ঠিক রাখবেন --}}
        <input type="text" class="form-control" name="image_one" id="image_one" required readonly placeholder="Select Image">
        <button type="button" class="btn btn-primary" onclick="openGallery('image_one')">
            <i class="fas fa-image"></i> Choose
        </button>
    </div>
    {{-- প্রিভিউ সেকশন --}}
    <div id="preview-image_one" class="mt-2" style="display:none; width: 120px;">
        <img src="" class="img-fluid rounded border">
    </div>
    @error('image_one')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>

                                   <div class="mb-3">
    <label class="form-label">Product Show Image (3D Card) *</label>
    <div class="input-group">
        <input type="text" class="form-control" name="image_two" id="image_two" readonly placeholder="Select Image">
        <button type="button" class="btn btn-secondary" onclick="openGallery('image_two')">Choose</button>
    </div>
    <div id="preview-image_two" class="mt-2" style="display:none; width: 100px;">
        <img src="" class="img-fluid rounded border">
    </div>
</div>

                                    <div class="mb-3">
    <label class="form-label">Background / Extra Image</label>
    <div class="input-group">
        <input type="text" class="form-control" name="image_three" id="image_three" readonly placeholder="Select Image">
        <button type="button" class="btn btn-secondary" onclick="openGallery('image_three')">Choose</button>
    </div>
    <div id="preview-image_three" class="mt-2" style="display:none; width: 100px;">
        <img src="" class="img-fluid rounded border">
    </div>
</div>
                                    
                                    <div class="mb-3">
    <label class="form-label">Second Background Image</label>
    <div class="input-group">
        <input type="text" class="form-control" name="image_four" id="image_four" readonly placeholder="Select Image">
        <button type="button" class="btn btn-secondary" onclick="openGallery('image_four')">Choose</button>
    </div>
    <div id="preview-image_four" class="mt-2" style="display:none; width: 100px;">
        <img src="" class="img-fluid rounded border">
    </div>
</div>

                                    <div class="mb-3">
    <label class="form-label fw-bold">Gallery Images (Select Multiple)</label>
    
    {{-- বাটন --}}
    <button type="button" class="btn btn-outline-info w-100" onclick="openGallery('multi_image_manager')">
        <i class="fas fa-images"></i> Open Media Gallery
    </button>

    {{-- কন্টেইনার (যেখানে ছবিগুলো দেখাবে) --}}
    <div id="product_gallery_container" class="d-flex flex-wrap gap-2 mt-3 p-2 bg-light border rounded">
        {{-- জাভাস্ক্রিপ্ট দিয়ে এখানে ছবি অ্যাপেন্ড হবে --}}
    </div>
    <small id="gallery_count_display" class="text-muted d-block mt-1">0 Files selected</small>
</div>

                                    {{-- ================================================== --}}
                                    {{-- NEW TESTIMONIAL SECTION START --}}
                                    {{-- ================================================== --}}
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0">Testimonials</label>
                                            <button type="button" class="btn btn-sm btn-info text-white" id="addTestimonial">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>
                                        
                                        <div id="testimonial_container">
                                            {{-- Dynamic Testimonial Items will appear here --}}
                                        </div>
                                        <small class="text-muted d-block mt-1">Select Type: Text, Video (Youtube Link/Iframe), or Image.</small>
                                    </div>
                                    {{-- NEW TESTIMONIAL SECTION END --}}
                                    {{-- ================================================== --}}

                                    <div class="mb-3">
                                        <label class="form-label">Review Rating (Star Count)</label>
                                        <input type="number" class="form-control" name="review" value="5">
                                    </div>

                                    <div class="mb-3">
    <label class="form-label d-block">Campaign Status</label>
    
    <div style="border: 1px solid #ced4da; padding: 10px; border-radius: 5px; background: #fff;">
        {{-- Active Option --}}
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="status" id="status_active" value="1" 
                {{ (isset($edit_data) && $edit_data->status == 1) || !isset($edit_data) ? 'checked' : '' }}
                style="cursor: pointer;">
            <label class="form-check-label text-success fw-bold" for="status_active" style="cursor: pointer; font-size: 15px; font-weight: 600;">
                ✅ Active (ক্যাম্পেইন চালু)
            </label>
        </div>

        {{-- Inactive Option --}}
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0"
                {{ (isset($edit_data) && $edit_data->status == 0) ? 'checked' : '' }}
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
                                            name="slug" id="slug" value="{{ old('slug') }}" required>
                                        <small class="text-muted">টাইটেল সিলেক্ট করলে এটি অটোমেটিক পূরণ হবে।</small>
                                        @error('slug')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save Campaign
                                            (Theme 7)</button>
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
            // Plugins Init
            $('.select2').select2();
            $('.summernote').summernote({
                height: 200
            });

            // ==========================================
            // TESTIMONIAL DYNAMIC LOGIC START
            // ==========================================
            let testimonialIndex = 0;

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
                    // এই লাইনটি আপডেট করুন
let placeholder = (type === 'video') ? "YouTube Video ID (Ex: eIrRj6vDddU) OR Full Iframe Code (<iframe...)" : "Write review text here...";
                    row.find('.content-field textarea').attr('placeholder', placeholder);
                }
            });
            // ==========================================
            // TESTIMONIAL DYNAMIC LOGIC END
            // ==========================================

            // SLUG GENERATION LOGIC
            function generateSlug(text) {
                return text.toString().toLowerCase().trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }

            $('#name').on('input', function() {
                let slugField = $('#slug');
                let titleValue = $(this).val();
                if (slugField.data('manual') !== true) {
                    let slug = generateSlug(titleValue);
                    slugField.val(slug);
                    if(titleValue !== '') {
                        slugField.data('from-title', true);
                    } else {
                        slugField.data('from-title', false);
                    }
                }
            });

            $('select[name="product_id[]"]').on('change', function() {
                let slugField = $('#slug');
                let titleInput = $('#name').val();
                if (slugField.data('manual') !== true && (titleInput === '' || slugField.data('from-title') !== true)) {
                    var data = $(this).select2('data');
                    if (data && data.length > 0) {
                        var productName = data[0].text; 
                        let slug = generateSlug(productName);
                        slugField.val(slug);
                    }
                }
            });

            $('#slug').on('input', function() {
                if ($(this).val() !== '') {
                    $(this).data('manual', true);
                } else {
                    $(this).data('manual', false);
                    $(this).data('from-title', false);
                }
            });
        });
    </script>
@endsection