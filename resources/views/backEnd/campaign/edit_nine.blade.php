@extends('backEnd.layouts.master')
@section('title', 'Edit Theme 9 (Video & 4 Images)')
@section('css')
    <link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .testi-box { background: #fff; border: 1px solid #e3e6f0; padding: 20px; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .img-preview-box { width: 100px; height: 80px; border: 1px dashed #ccc; margin-top: 5px; overflow: hidden; display: flex; align-items: center; justify-content: center;}
        .img-preview-box img { width: 100%; height: 100%; object-fit: cover; }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-lg mt-3">
        <div class="card-header bg-white">
            <h4 class="text-primary fw-bold">Edit Campaign (Theme 9)</h4>
        </div>
        <div class="card-body">
            <form action="{{route('campaign.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$edit_data->id}}">
                <input type="hidden" name="theme_id" value="9">
                <input type="hidden" name="status" value="{{$edit_data->status}}">
                <input type="hidden" name="review" value="{{$edit_data->review ?? 5}}">

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="fw-bold">ক্যাম্পেইন টাইটেল (Product Name) *</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{$edit_data->name}}" required placeholder="Example: প্রিমিয়াম পাঞ্জাবি কালেকশন">
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">ভিডিও সেকশন (YouTube Video ID) *</label>
                            <input type="text" class="form-control" name="video" value="{{$edit_data->video}}" placeholder="Ex: eIrRj6vDddU">
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">পণ্যের বিস্তারিত বর্ণনা (Description)</label>
                            <textarea name="description" class="summernote form-control">{!! $edit_data->description !!}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold">কেন আমাদের থেকে কিনবেন? (Short Description)</label>
                            <textarea name="short_description" class="summernote form-control">{!! $edit_data->short_description !!}</textarea>
                        </div>

                        <div class="card bg-light border-0 mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-dark">Customer Reviews (Video/Image/Text)</h5>
                                <button type="button" class="btn btn-primary btn-sm" id="addTesti"><i class="fas fa-plus"></i> Add New</button>
                            </div>
                            <div class="card-body" id="testi_container">
                                @php
                                    $testimonials = json_decode($edit_data->testimonials, true);
                                @endphp
                                @if($testimonials)
                                    @foreach($testimonials as $key => $testi)
                                    <div class="testi-box">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">Review #{{$key+1}}</span>
                                            <button type="button" class="btn btn-danger btn-sm remove-item">X</button>
                                        </div>
                                        <div class="mb-2">
                                            <select name="testimonials[{{$key+1}}][type]" class="form-control form-control-sm type-sw">
                                                <option value="text" {{$testi['type']=='text'?'selected':''}}>Text</option>
                                                <option value="video" {{$testi['type']=='video'?'selected':''}}>Video (Youtube)</option>
                                                <option value="image" {{$testi['type']=='image'?'selected':''}}>Image</option>
                                            </select>
                                        </div>
                                        <div class="content-area" style="display: {{$testi['type']=='image'?'none':'block'}}">
                                            <textarea name="testimonials[{{$key+1}}][content]" class="form-control" placeholder="{{$testi['type']=='video' ? 'Enter Youtube Video ID' : 'Write review...'}}">{{$testi['content'] ?? ''}}</textarea>
                                        </div>
                                        <div class="image-area" style="display: {{$testi['type']=='image'?'block':'none'}}">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="testimonials[{{$key+1}}][image]" id="t_img_{{$key+1}}" value="{{$testi['image'] ?? ''}}" readonly>
                                                <button type="button" class="btn btn-secondary" onclick="openGallery('t_img_{{$key+1}}')">Browse</button>
                                            </div>
                                            <div class="img-preview-box"><img id="view_t_img_{{$key+1}}" src="{{asset($testi['image'] ?? '')}}"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-bold">Product Select *</label>
                            <select class="form-control select2" name="product_id[]" multiple required>
                                @foreach($products as $product)
                                <option value="{{$product->id}}" @if(in_array($product->id, $select_products)) selected @endif>{{$product->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header fw-bold">গ্যালারি ইমেজ (৪টি ছবি দিন)</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label>Image 1</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="image_one" id="img1" value="{{$edit_data->image_one}}" readonly>
                                        <button type="button" class="btn btn-secondary" onclick="openGallery('img1')">Browse</button>
                                    </div>
                                    <div class="img-preview-box"><img id="view_img1" src="{{asset($edit_data->image_one)}}"></div>
                                </div>
                                <div class="mb-2">
                                    <label>Image 2</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="image_two" id="img2" value="{{$edit_data->image_two}}" readonly>
                                        <button type="button" class="btn btn-secondary" onclick="openGallery('img2')">Browse</button>
                                    </div>
                                    <div class="img-preview-box"><img id="view_img2" src="{{asset($edit_data->image_two)}}"></div>
                                </div>
                                <div class="mb-2">
                                    <label>Image 3</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="image_three" id="img3" value="{{$edit_data->image_three}}" readonly>
                                        <button type="button" class="btn btn-secondary" onclick="openGallery('img3')">Browse</button>
                                    </div>
                                    <div class="img-preview-box"><img id="view_img3" src="{{asset($edit_data->image_three)}}"></div>
                                </div>
                                <div class="mb-2">
                                    <label>Image 4</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="image_four" id="img4" value="{{$edit_data->image_four}}" readonly>
                                        <button type="button" class="btn btn-secondary" onclick="openGallery('img4')">Browse</button>
                                    </div>
                                    <div class="img-preview-box"><img id="view_img4" src="{{asset($edit_data->image_four)}}"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Slug (URL)</label>
                            <input type="text" class="form-control" name="slug" id="slug" value="{{$edit_data->slug}}" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2">Update Campaign</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('backEnd.layouts.media_manager_modal')
@endsection

@section('script')
<script src="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.js"></script>
<script src="{{asset('public/backEnd')}}/assets/libs/select2/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('.summernote').summernote({height: 150});
        $('.select2').select2();

        // Slug Auto Gen
        $('#name').on('input', function(){
            let slug = $(this).val().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
            $('#slug').val(slug);
        });

        // Testimonial Logic - Count existing items first
        let x = {{ $testimonials ? count($testimonials) : 0 }};
        
        $('#addTesti').click(function(){
            x++;
            let html = `
            <div class="testi-box">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Review #${x}</span>
                    <button type="button" class="btn btn-danger btn-sm remove-item">X</button>
                </div>
                <div class="mb-2">
                    <select name="testimonials[${x}][type]" class="form-control form-control-sm type-sw">
                        <option value="text">Text</option>
                        <option value="video">Video (Youtube)</option>
                        <option value="image">Image</option>
                    </select>
                </div>
                <div class="content-area">
                    <textarea name="testimonials[${x}][content]" class="form-control" placeholder="Write review..."></textarea>
                </div>
                <div class="image-area" style="display:none;">
                    <div class="input-group">
                        <input type="text" class="form-control" name="testimonials[${x}][image]" id="t_img_${x}" readonly>
                        <button type="button" class="btn btn-secondary" onclick="openGallery('t_img_${x}')">Browse</button>
                    </div>
                    <div class="img-preview-box"><img id="view_t_img_${x}" src=""></div>
                </div>
            </div>`;
            $('#testi_container').append(html);
        });

        $(document).on('click', '.remove-item', function(){ $(this).closest('.testi-box').remove(); });

        $(document).on('change', '.type-sw', function(){
            let type = $(this).val();
            let box = $(this).closest('.testi-box');
            if(type === 'image'){
                box.find('.content-area').hide();
                box.find('.image-area').show();
            } else {
                box.find('.content-area').show();
                box.find('.image-area').hide();
                let holder = (type === 'video') ? 'Enter Youtube Video ID' : 'Write Review';
                box.find('textarea').attr('placeholder', holder);
            }
        });

        // Image Preview Interval
        setInterval(function(){
            // Main Images
            ['img1', 'img2', 'img3', 'img4'].forEach(id => {
                let val = $('#'+id).val();
                if(val) $('#view_'+id).attr('src', val);
            });
            // Testimonial Images
            $('input[id^="t_img_"]').each(function(){
                let val = $(this).val();
                let id = $(this).attr('id');
                if(val) $('#view_'+id).attr('src', val);
            });
        }, 1000);
    });
</script>
@endsection