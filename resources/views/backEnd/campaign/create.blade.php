@extends('backEnd.layouts.master')
@section('title', 'Landing Page Create')
@section('css')
    <link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />

    <link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/backEnd')}}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <style>
        /* Custom styles for the file upload section */
        .land-image-preview .card {
            transition: transform 0.2s;
        }

        .land-image-preview .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .input-group-append .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .control-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        /* Gallery Preview Styles */
        .gallery-preview-box {
            width: 100px;
            height: 80px;
            border: 1px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: 5px;
            margin-bottom: 5px;
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
                        <a href="{{route('campaign.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                    </div>
                    <h4 class="page-title">Landing Page Create</h4>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('campaign.store')}}" method="POST" class=row data-parsley-validate=""
                            enctype="multipart/form-data">
                            @csrf

                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="banner_title" class="form-label">Banner Title *</label>
                                    <input type="text" class="form-control @error('banner_title') is-invalid @enderror"
                                        name="banner_title" value="{{ old('banner_title') }}" id="banner_title" required="">
                                    @error('banner_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="banner" class="form-label">Banner *</label>
                                    
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control @error('banner') is-invalid @enderror" name="banner" id="banner_url" placeholder="Select from Gallery" readonly value="{{ old('banner') }}" required>
                                        <button type="button" class="btn btn-info" onclick="openGallery('banner_url')">Browse Gallery</button>
                                    </div>
                                    <div id="preview-banner_url" class="gallery-preview-box mb-2" style="{{ old('banner') ? 'display:flex' : 'display:none' }}">
                                        @if(old('banner')) <img src="{{ asset(old('banner')) }}"> @endif
                                    </div>

                                    @error('banner')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="video" class="form-label">Video </label>
                                    <input type="text" class="form-control @error('video') is-invalid @enderror"
                                        name="video" value="{{ old('video') }}" id="video">
                                    @error('video')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="theme_id" class="form-label">Theme Use *</label>
                                    <select name="theme_id" class="form-control">
                                        <option value="1">Default</option>
                                        <option value="2">Second Theme</option>
                                        <option value="3">Third Theme</option>
                                        <option value="4">Four Theme</option>
                                        <option value="5">Five Theme</option>
                                        <option value="6">Six Theme (Al-Quran)</option>
                                        <option value="8">Theme 8 (Video & Form Focus)</option>
                                        <option value="10">Ten Theme (Fashion - Multi Product)</option>
                                        <option value="11">Eleven Theme (Combo)</option>

                                    </select>
                                    @error('theme_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Landing Page Title *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" id="name" required="">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Info Product </label>
                                    <input type="text" class="form-control @error('section_desc') is-invalid @enderror"
                                        name="section_desc" value="{{ old('section_desc') }}" id="section_desc">
                                    @error('section_desc')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="product_id" class="form-label">Products *</label>
                                    <select class="select2 form-control @error('product_id') is-invalid @enderror"
                                        name="product_id[]" data-placeholder="Choose ..." required multiple>
                                        <option value="">Select...</option>
                                        @foreach($products as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-sm-6">
    <div class="form-group mb-3">
        <label for="primary_color" class="form-label">Theme Primary Color</label>
        <div class="d-flex align-items-center">
            <input type="color" class="form-control form-control-color" name="primary_color" value="#c42831" id="primary_color" title="Choose your color" style="width: 100px;">
            <span class="ms-2 text-muted">ডিফল্ট কালার পরিবর্তন করতে ক্লিক করুন</span>
        </div>
    </div>
</div>



                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="image_one" class="form-label">Image One</label>
                                    
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control @error('image_one') is-invalid @enderror" name="image_one" id="image_one_url" placeholder="Select from Gallery" readonly value="{{ old('image_one') }}">
                                        <button type="button" class="btn btn-info" onclick="openGallery('image_one_url')">Browse Gallery</button>
                                    </div>
                                    <div id="preview-image_one_url" class="gallery-preview-box mb-2" style="{{ old('image_one') ? 'display:flex' : 'display:none' }}">
                                        @if(old('image_one')) <img src="{{ asset(old('image_one')) }}"> @endif
                                    </div>

                                    @error('image_one')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="image_two" class="form-label">Image Two</label>

                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control @error('image_two') is-invalid @enderror" name="image_two" id="image_two_url" placeholder="Select from Gallery" readonly value="{{ old('image_two') }}">
                                        <button type="button" class="btn btn-info" onclick="openGallery('image_two_url')">Browse Gallery</button>
                                    </div>
                                    <div id="preview-image_two_url" class="gallery-preview-box mb-2" style="{{ old('image_two') ? 'display:flex' : 'display:none' }}">
                                        @if(old('image_two')) <img src="{{ asset(old('image_two')) }}"> @endif
                                    </div>

                                    @error('image_two')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="image_three" class="form-label">Image Three</label>

                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control @error('image_three') is-invalid @enderror" name="image_three" id="image_three_url" placeholder="Select from Gallery" readonly value="{{ old('image_three') }}">
                                        <button type="button" class="btn btn-info" onclick="openGallery('image_three_url')">Browse Gallery</button>
                                    </div>
                                    <div id="preview-image_three_url" class="gallery-preview-box mb-2" style="{{ old('image_three') ? 'display:flex' : 'display:none' }}">
                                        @if(old('image_three')) <img src="{{ asset(old('image_three')) }}"> @endif
                                    </div>

                                    @error('image_three')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="review" class="form-label">Review Images (Title/Text) *</label>
                                    <input type="text" class="form-control @error('review') is-invalid @enderror"
                                        name="review" value="{{ old('review') }}" id="review" required="">
                                    @error('review')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="image">Review Image (Gallery) *</label>

                                <div class="mb-3 p-2 bg-light border rounded">
                                    <button type="button" class="btn btn-primary btn-sm mb-2" onclick="openGallery('multi_image_manager')">
                                        <i class="fas fa-images"></i> Add from Gallery
                                    </button>
                                    <div id="product_gallery_container" class="d-flex flex-wrap gap-2"></div>
                                    
                                </div>

                                @error('image')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="land_image">Image Landing (Gallery & File)</label>

                                <div class="mb-3 p-2 bg-light border rounded">
                                    <button type="button" class="btn btn-primary btn-sm mb-2" onclick="openGallery('landing_multi_images')">
                                        <i class="fas fa-images"></i> Add from Gallery
                                    </button>
                                    <div id="landing_gallery_container" class="d-flex flex-wrap gap-2"></div>
                                </div>


                                @error('land_image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div class="land-image-preview mt-3" style="display: none;">
                                    <label class="form-label">Selected Local Files Preview:</label>
                                    <div class="row" id="land-image-preview-container">
                                        </div>
                                </div>
                            </div>
                            <div class="col-sm-12 my-3">
                                <div class="form-group">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    <textarea name="short_description" rows="6"
                                        class="summernote form-control @error('short_description') is-invalid @enderror"></textarea>
                                    @error('short_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 my-3">
                                <div class="form-group">
                                    <label for="section_title" class="form-label">Section Title</label>
                                    <input type="text" class="form-control" placeholder="Section Name" name="section_title"
                                        value="{{ old('section_title') }}">
                                    @error('section_titile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" rows="6"
                                        class="summernote form-control @error('description') is-invalid @enderror"></textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="image_section" class="form-label">Image Section</label>

                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control @error('image_section') is-invalid @enderror" name="image_section" id="image_section_url" placeholder="Select from Gallery" readonly value="{{ old('image_section') }}">
                                        <button type="button" class="btn btn-info" onclick="openGallery('image_section_url')">Browse Gallery</button>
                                    </div>
                                    <div id="preview-image_section_url" class="gallery-preview-box mb-2" style="{{ old('image_section') ? 'display:flex' : 'display:none' }}">
                                        @if(old('image_section')) <img src="{{ asset(old('image_section')) }}"> @endif
                                    </div>

                                    @error('image_section')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 mb-3">
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
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="slug" class="form-label">Slug (URL) *</label>
                                    <input type="text" class="form-control" name="slug" id="slug" required value="{{ old('slug') }}">
                                    <small class="text-muted">টাইটেল বা প্রোডাক্ট সিলেক্ট করলে এটি অটোমেটিক পূরণ হবে।</small>
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
    $(document).ready(function () {
        // ১. ডাইনামিক ফিল্ড ইনক্রিমেন্ট
        $(".btn-increment").click(function () {
            var html = $(".clone").html();
            $(".increment").after(html);
        });

        // ২. ডাইনামিক ফিল্ড রিমুভ
        $("body").on("click", ".btn-danger", function () {
            $(this).parents(".control-group").remove();
        });

        // ৩. Select2 এবং স্লাগ জেনারেশন লজিক
        $('.select2').select2();

        // স্লাগ তৈরির কমন ফাংশন
        function generateSlug(text) {
            return text.toString().toLowerCase().trim()
                .replace(/[^\w\s-]/g, '')    // বিশেষ চিহ্ন বাদ
                .replace(/[\s_-]+/g, '-')    // স্পেসকে হাইফেন করা
                .replace(/^-+|-+$/g, '');    // শুরু ও শেষের হাইফেন বাদ
        }

        // ৪. টাইটেল (Title) ইনপুট দিলে স্লাগ জেনারেট হবে
        $('#name').on('input', function() {
            let slugField = $('#slug');
            let titleValue = $(this).val();

            // যদি ইউজার নিজে স্লাগ বক্সে হাত না দিয়ে থাকে (Manual mode off)
            if (slugField.data('manual') !== true) {
                let slug = generateSlug(titleValue);
                slugField.val(slug);

                // টাইটেল যদি খালি না হয়, তবে একটি ফ্ল্যাগ সেট করি যাতে প্রোডাক্ট একে চেঞ্জ না করতে পারে
                if(titleValue !== '') {
                    slugField.data('from-title', true);
                } else {
                    slugField.data('from-title', false);
                }
            }
        });

        // ৫. প্রোডাক্ট সিলেক্ট করলে স্লাগ জেনারেট হবে (শর্তসাপেক্ষ)
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

        // ৬. ইউজার যদি নিজে স্লাগ এডিট করে, তবে সব অটো আপডেট চিরতরে বন্ধ হয়ে যাবে
        $('#slug').on('input', function() {
            if ($(this).val() !== '') {
                $(this).data('manual', true);
            } else {
                $(this).data('manual', false);
                $(this).data('from-title', false);
            }
        });

        // ==========================================
        //  MEDIA MANAGER OVERRIDE FOR LANDING PAGE & REVIEW IMAGES
        // ==========================================
        
if (typeof useSelectedImage === 'function') {
    useSelectedImage = function() {
        
        // ১. মাল্টিপল ইমেজের জন্য (Review & Landing)
        if (modalTargetInput === 'landing_multi_images' || modalTargetInput === 'multi_image_manager') {
            
            let containerId = '';
            let inputName = '';

            if(modalTargetInput === 'landing_multi_images'){
                containerId = '#landing_gallery_container';
                // এই নামটা (land_image[]) ঠিক থাকা সবচেয়ে জরুরি
                inputName = 'land_image[]'; 
            } else {
                containerId = '#product_gallery_container';
                inputName = 'image[]'; 
            }

            let container = $(containerId);
            
            if (typeof modalSelectedUrls !== 'undefined' && modalSelectedUrls.length > 0) {
                modalSelectedUrls.forEach(url => {
                    let html = `
                        <div class="gallery-item-card position-relative" style="width: 100px; height: 100px; border:1px solid #ddd; padding:2px; border-radius:5px;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0" style="width: 20px; height: 20px; line-height: 1; z-index:99;" onclick="$(this).parent().remove()">×</button>
                            <div class="preview-image-box" style="width: 100%; height: 100%;">
                                <img src="${url}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
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
        // ২. সিঙ্গেল ইমেজের জন্য (Banner, Image One, Image Section, etc.)
        else {
            if (typeof modalSelectedUrls !== 'undefined' && modalSelectedUrls.length > 0) {
                var url = modalSelectedUrls[0];
                
                $('#' + modalTargetInput).val(url);
                
                // প্রিভিউ বক্স আপডেট (সব সিঙ্গেল ইমেজের জন্য)
                var previewDiv = $('#preview-' + modalTargetInput);
                if(previewDiv.length > 0) {
                    previewDiv.html('<img src="' + url + '">');
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

            // Add more land image inputs
            $(document).on('click', '.add-land-image', function () {
                var html = $('.clone-land-image').html();
                $('.land-image-container').append(html);
            });

            // Remove land image input
            $(document).on('click', '.remove-land-image', function () {
                $(this).closest('.control-group').remove();
                updateImagePreviews();
            });

            // Image preview functionality
            $(document).on('change', 'input[name="land_image[]"]', function () {
                updateImagePreviews();
            });

            function updateImagePreviews() {
                var previewContainer = $('#land-image-preview-container');
                var previewSection = $('.land-image-preview');
                previewContainer.empty();

                var hasImages = false;

                $('input[name="land_image[]"]').each(function (index) {
                    var file = this.files[0];
                    if (file) {
                        hasImages = true;
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var imageHtml = `
                            <div class="col-md-3 mb-2">
                                <div class="card">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Land Image ${index + 1}">
                                    <div class="card-body p-2">
                                        <small class="text-muted">Image ${index + 1}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                            previewContainer.append(imageHtml);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                if (hasImages) {
                    previewSection.show();
                } else {
                    previewSection.hide();
                }
            }
        });
    </script>
@endsection