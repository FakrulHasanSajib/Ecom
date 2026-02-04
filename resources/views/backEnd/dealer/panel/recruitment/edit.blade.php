@extends('backEnd.dealer.panel.layout.master')
@section('title', 'Edit Campaign')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    
    <style>
        .file-manager-bar {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .file-browse-btn {
            background: #f1f5f9;
            border: none;
            border-right: 1px solid #dee2e6;
            padding: 10px 25px;
            color: #475569;
            font-weight: 600;
            transition: all 0.2s;
        }
        .file-browse-btn:hover { background: #e2e8f0; }
        .file-info-text { padding: 0 15px; color: #64748b; font-size: 0.9rem; }

        .gallery-item-card {
            width: 150px;
            height: 150px;
            position: relative;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            padding: 5px;
            display: none; 
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        .gallery-item-card img { width: 100%; height: 100%; object-fit: cover; border-radius: 4px; }
        
        .remove-btn-small {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 25px;
            height: 25px;
            background: #fff;
            border: 1px solid #ef4444;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
            z-index: 5;
        }
        .card-box-border {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            background: #f8fafc;
            height: 100%;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Edit Recruitment Campaign</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">Update Campaign Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dealer.recruitment.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="hidden_id" value="{{ $edit_data->id }}">
                        
                        <div class="row">
                            {{-- Basic Info --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Campaign Title (Badge Text) *</label>
                                <input type="text" name="title" id="name" class="form-control" value="{{ $edit_data->title }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Slug (URL) *</label>
                                <input type="text" name="slug" id="slug" class="form-control" value="{{ $edit_data->slug }}" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Header Main Text (Optional)</label>
                                <textarea name="header_text" class="form-control" rows="2">{{ $edit_data->header_text }}</textarea>
                            </div>

                            {{-- Video Section --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Agent Video ID (YouTube) *</label>
                                <input type="text" name="agent_video_id" class="form-control" value="{{ $edit_data->agent_video_id }}" placeholder="e.g: dQw4w9WgXcQ">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Khadem Video ID (YouTube) *</label>
                                <input type="text" name="khadem_video_id" class="form-control" value="{{ $edit_data->khadem_video_id }}" placeholder="e.g: dQw4w9WgXcQ">
                            </div>

                            {{-- Link Section --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Custom Login URL (Optional)</label>
                                <input type="text" name="login_url" class="form-control" value="{{ $edit_data->login_url }}" placeholder="Default is /login">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Custom Register URL (Optional)</label>
                                <input type="text" name="register_url" class="form-control" value="{{ $edit_data->register_url }}" placeholder="Default is /register">
                            </div>

                            <hr class="my-4">
                            
                            {{-- Gallery Images --}}
                            <h5 class="mb-3 text-secondary">Gallery Images & Feature Image</h5>

                            {{-- Image 1 --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Image One (Library)</label>
                                <div class="file-manager-bar">
                                    <button type="button" class="file-browse-btn" onclick="openGallery('image_one')">
                                        <i class="fa fa-image me-2"></i>Change
                                    </button>
                                    <span class="file-info-text" id="status_image_one">{{ $edit_data->image_one ? 'Selected' : 'Empty' }}</span>
                                </div>
                                <div class="gallery-item-card" id="preview_card_image_one" style="{{ $edit_data->image_one ? 'display:flex' : '' }}">
                                    <button type="button" class="remove-btn-small" onclick="clearImage('image_one')"><i class="fa fa-times"></i></button>
                                    <img src="{{ asset($edit_data->image_one) }}" id="img_tag_image_one">
                                    <input type="hidden" name="image_one" id="image_one" value="{{ $edit_data->image_one }}">
                                </div>
                            </div>

                            {{-- Image 2 --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Image Two (Reading)</label>
                                <div class="file-manager-bar">
                                    <button type="button" class="file-browse-btn" onclick="openGallery('image_two')">
                                        <i class="fa fa-image me-2"></i>Change
                                    </button>
                                    <span class="file-info-text" id="status_image_two">{{ $edit_data->image_two ? 'Selected' : 'Empty' }}</span>
                                </div>
                                <div class="gallery-item-card" id="preview_card_image_two" style="{{ $edit_data->image_two ? 'display:flex' : '' }}">
                                    <button type="button" class="remove-btn-small" onclick="clearImage('image_two')"><i class="fa fa-times"></i></button>
                                    <img src="{{ asset($edit_data->image_two) }}" id="img_tag_image_two">
                                    <input type="hidden" name="image_two" id="image_two" value="{{ $edit_data->image_two }}">
                                </div>
                            </div>

                            {{-- Image 3 --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Image Three (Delivery)</label>
                                <div class="file-manager-bar">
                                    <button type="button" class="file-browse-btn" onclick="openGallery('image_three')">
                                        <i class="fa fa-image me-2"></i>Change
                                    </button>
                                    <span class="file-info-text" id="status_image_three">{{ $edit_data->image_three ? 'Selected' : 'Empty' }}</span>
                                </div>
                                <div class="gallery-item-card" id="preview_card_image_three" style="{{ $edit_data->image_three ? 'display:flex' : '' }}">
                                    <button type="button" class="remove-btn-small" onclick="clearImage('image_three')"><i class="fa fa-times"></i></button>
                                    <img src="{{ asset($edit_data->image_three) }}" id="img_tag_image_three">
                                    <input type="hidden" name="image_three" id="image_three" value="{{ $edit_data->image_three }}">
                                </div>
                            </div>

                            {{-- Feature Section Image (Man With Quran) --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold text-success">Feature Image (Man)</label>
                                <div class="file-manager-bar">
                                    <button type="button" class="file-browse-btn" onclick="openGallery('feature_section_image')">
                                        <i class="fa fa-image me-2"></i>Change
                                    </button>
                                    <span class="file-info-text" id="status_feature_section_image">{{ $edit_data->feature_section_image ? 'Selected' : 'Empty' }}</span>
                                </div>
                                <div class="gallery-item-card" id="preview_card_feature_section_image" style="{{ $edit_data->feature_section_image ? 'display:flex' : '' }}">
                                    <button type="button" class="remove-btn-small" onclick="clearImage('feature_section_image')"><i class="fa fa-times"></i></button>
                                    <img src="{{ asset($edit_data->feature_section_image) }}" id="img_tag_feature_section_image">
                                    <input type="hidden" name="feature_section_image" id="feature_section_image" value="{{ $edit_data->feature_section_image }}">
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Feature Cards Settings --}}
                            <h5 class="mb-3 text-secondary">Feature Cards Settings (4 Cards)</h5>
                            <div class="row">
                                {{-- Card 1 --}}
                                <div class="col-md-6 mb-3">
                                    <div class="card-box-border">
                                        <h6 class="fw-bold text-primary mb-2">Card 1 (Default: কোরআন খাদেম)</h6>
                                        <div class="mb-2">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="card_1_title" class="form-control" value="{{ $edit_data->card_1_title }}" placeholder="Title here...">
                                        </div>
                                        <div>
                                            <label class="form-label">Description</label>
                                            <textarea name="card_1_desc" class="form-control" rows="2" placeholder="Description here...">{{ $edit_data->card_1_desc }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 2 --}}
                                <div class="col-md-6 mb-3">
                                    <div class="card-box-border">
                                        <h6 class="fw-bold text-primary mb-2">Card 2 (Default: লাইব্রেরিয়ান)</h6>
                                        <div class="mb-2">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="card_2_title" class="form-control" value="{{ $edit_data->card_2_title }}" placeholder="Title here...">
                                        </div>
                                        <div>
                                            <label class="form-label">Description</label>
                                            <textarea name="card_2_desc" class="form-control" rows="2" placeholder="Description here...">{{ $edit_data->card_2_desc }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 3 --}}
                                <div class="col-md-6 mb-3">
                                    <div class="card-box-border">
                                        <h6 class="fw-bold text-primary mb-2">Card 3 (Default: এজেন্ট)</h6>
                                        <div class="mb-2">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="card_3_title" class="form-control" value="{{ $edit_data->card_3_title }}" placeholder="Title here...">
                                        </div>
                                        <div>
                                            <label class="form-label">Description</label>
                                            <textarea name="card_3_desc" class="form-control" rows="2" placeholder="Description here...">{{ $edit_data->card_3_desc }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 4 --}}
                                <div class="col-md-6 mb-3">
                                    <div class="card-box-border">
                                        <h6 class="fw-bold text-primary mb-2">Card 4 (Default: কর্পোরেট গিফট)</h6>
                                        <div class="mb-2">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="card_4_title" class="form-control" value="{{ $edit_data->card_4_title }}" placeholder="Title here...">
                                        </div>
                                        <div>
                                            <label class="form-label">Description</label>
                                            <textarea name="card_4_desc" class="form-control" rows="2" placeholder="Description here...">{{ $edit_data->card_4_desc }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Description --}}
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold">Full Description (Footer/Extra Text) *</label>
                                <textarea name="description" class="form-control summernote" required>{!! $edit_data->description !!}</textarea>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $edit_data->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $edit_data->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12 text-center mt-3">
                                <button type="submit" class="btn btn-success px-5 btn-lg">
                                    <i class="fa fa-sync-alt me-1"></i> Update Campaign
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            // Summernote
            $('.summernote').summernote({
                height: 200,
                tabsize: 2,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            // Auto Slug
            $('#name').on('input', function() {
                let text = $(this).val();
                let slug = text.toLowerCase().trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                $('#slug').val(slug);
            });

            // 🔥 Multi-Image Preview Engine (Check every 500ms)
            setInterval(function() {
                checkImageInput('image_one');
                checkImageInput('image_two');
                checkImageInput('image_three');
                checkImageInput('feature_section_image'); // নতুন ইমেজের জন্য
            }, 500);
        });

        // Open Gallery (আপনি আপনার মিডিয়া ম্যানেজার ফাংশন এখানে কল করবেন)
        function openGallery(inputId) {
            // Example:
            // $('#mediaModal').modal('show');
            // localStorage.setItem('target_input', inputId);
        }

        function checkImageInput(id) {
            let url = $('#' + id).val();
            let previewCard = $('#preview_card_' + id);
            let imgTag = $('#img_tag_' + id);
            let statusText = $('#status_' + id);

            // যদি ইনপুটে ভ্যালু থাকে এবং তা ইমেজের সোর্স থেকে ভিন্ন হয় (নতুন ইমেজ সিলেক্ট করা হলে)
            if(url && url.trim() !== '' && imgTag.attr('src') !== url) {
                imgTag.attr('src', url);
                previewCard.css('display', 'flex');
                statusText.text('Selected');
                statusText.css('color', 'green');
            }
        }

        // Clear Image
        function clearImage(id) {
            $('#' + id).val('');
            $('#img_tag_' + id).attr('src', '');
            $('#preview_card_' + id).hide();
            $('#status_' + id).text('Empty');
            $('#status_' + id).css('color', '#64748b');
        }
    </script>
@endsection