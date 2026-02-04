@extends('backEnd.layouts.master')
@section('title', 'Edit Recruitment Campaign')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .card-header { background: #7c3aed; color: white; }
        .form-label { font-weight: 600; }
        
        /* Photo Gallery Bar Styles - */
        .file-manager-bar {
            display: flex; align-items: center; background: #fff; border: 1px solid #dee2e6;
            border-radius: 6px; overflow: hidden; margin-bottom: 10px;
        }
        .file-browse-btn {
            background: #f1f5f9; border: none; border-right: 1px solid #dee2e6;
            padding: 10px 25px; color: #475569; font-weight: 600; transition: all 0.2s;
        }
        .file-browse-btn:hover { background: #e2e8f0; }
        .file-info-text { padding: 0 15px; color: #64748b; font-size: 0.9rem; }

        /* Preview Card Styles - */
        .gallery-item-card {
            width: 150px; height: 150px; position: relative; border: 1px solid #e2e8f0;
            border-radius: 8px; background: #fff; padding: 5px; display: flex; 
            align-items: center; justify-content: center;
        }
        .gallery-item-card img { width: 100%; height: 100%; object-fit: cover; border-radius: 4px; }
        
        .remove-btn-small {
            position: absolute; top: -10px; right: -10px; width: 25px; height: 25px;
            background: #fff; border: 1px solid #ef4444; color: #ef4444; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            box-shadow: 0 3px 6px rgba(0,0,0,0.15); z-index: 5;
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

    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border border-primary">
                <div class="card-header">
                    <h5 class="mb-0 text-white">Update Campaign Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.recruitment.update') }}" method="POST">
                        @csrf
                        {{-- আপডেট লজিকের জন্য হিডেন আইডি - --}}
                        <input type="hidden" name="hidden_id" value="{{ $edit_data->id }}">
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Campaign Title *</label>
                                <input type="text" name="title" id="name" class="form-control" value="{{ $edit_data->title }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug (Unique URL) *</label>
                                <input type="text" name="slug" id="slug" class="form-control" value="{{ $edit_data->slug }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-primary">Assign Dealer (Optional)</label>
                                <select name="assign_dealer_id" class="form-control select2">
                                    <option value="">Select Dealer (Leave empty for Global)</option>
                                    @foreach($dealers as $dealer)
                                        <option value="{{ $dealer->id }}" {{ $edit_data->referral_code == $dealer->id ? 'selected' : '' }}>
                                            {{ $dealer->name }} ({{ $dealer->store_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Banner Image (From Gallery)</label>
                                <div class="file-manager-bar">
                                    <button type="button" class="file-browse-btn" onclick="openGallery('campaign_banner')">
                                        <i class="fa fa-folder-open me-2"></i>Change Image
                                    </button>
                                    <span class="file-info-text" id="banner_status">
                                        {{ $edit_data->banner ? '1 Image Selected' : 'No image selected' }}
                                    </span>
                                </div>

                                <div class="gallery-item-card" id="preview-campaign_banner_card" style="{{ $edit_data->banner ? '' : 'display:none;' }}">
                                    <button type="button" class="remove-btn-small" onclick="clearBanner()">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <img src="{{ asset($edit_data->banner) }}" id="banner_img_tag">
                                    <input type="hidden" name="banner" id="campaign_banner" value="{{ $edit_data->banner }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Youtube Video ID (Optional)</label>
                                <input type="text" name="video_url" class="form-control" value="{{ $edit_data->video_url }}" placeholder="Only ID, e.g: dQw4w9WgXcQ">
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label">Full Description *</label>
                                <textarea name="description" class="form-control summernote" required>{!! $edit_data->description !!}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $edit_data->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $edit_data->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12 text-center mt-3">
                                <button type="submit" class="btn btn-success btn-lg px-5">Update Campaign</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Summernote Initialization
            $('.summernote').summernote({ height: 350 });

            $('.select2').select2({ width: '100%' });

            // Auto Slug Logic -
            $('#name').on('input keyup', function() {
                let text = $(this).val();
                let slug = text.toLowerCase().trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                $('#slug').val(slug);
            });

            // Photo Gallery Preview Engine
            setInterval(function() {
                let bannerUrl = $('#campaign_banner').val();
                if(bannerUrl && bannerUrl.trim() !== '') {
                    if($('#banner_img_tag').attr('src') !== bannerUrl) {
                        $('#banner_img_tag').attr('src', bannerUrl);
                        $('#preview-campaign_banner_card').css('display', 'flex');
                        $('#banner_status').text('1 Image Selected');
                    }
                }
            }, 500);
        });

        function clearBanner() {
            $('#campaign_banner').val('');
            $('#banner_img_tag').attr('src', '');
            $('#preview-campaign_banner_card').hide();
            $('#banner_status').text('No image selected');
        }
    </script>
@endsection