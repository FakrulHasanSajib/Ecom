<div class="modal fade" id="globalMediaModal" tabindex="-1" aria-labelledby="globalMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            
            {{-- HEADER: Tabs --}}
            <div class="modal-header border-bottom-0 pb-0">
                <ul class="nav nav-tabs" id="modal_mediaTab" role="tablist" style="border-bottom: none; width: 100%;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="modal-select-tab" data-bs-toggle="tab" data-bs-target="#modal_select_file" type="button" role="tab">
                            Select File
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="modal-upload-tab" data-bs-toggle="tab" data-bs-target="#modal_upload_new" type="button" role="tab">
                            Upload New
                        </button>
                    </li>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </ul>
            </div>

            <div class="modal-body bg-light">
                <div class="tab-content" id="modalMediaTabContent">
                    
                    {{-- TAB 1: SELECT FILE (GALLERY) --}}
                    <div class="tab-pane fade show active" id="modal_select_file" role="tabpanel">
                        
                        {{-- Filters Bar --}}
                        <div class="bg-white p-3 rounded shadow-sm mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold text-primary">Media Library</span>
                            </div>
                            <div>
                                <input type="text" id="modal_search_input" class="form-control form-control-sm" placeholder="Search files..." style="width: 250px;" onkeyup="filterGallery(this.value)">
                            </div>
                        </div>

                        {{-- Gallery Grid --}}
                        <div class="bg-white p-3 rounded shadow-sm" style="min-height: 400px;">
                            <div id="modal_galleryLoader" class="text-center p-5">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <div class="row g-3" id="modal_mediaGalleryGrid" style="max-height: 380px; overflow-y: auto;">
                                {{-- Images will be loaded here via AJAX --}}
                            </div>
                        </div>
                    </div>

                    {{-- TAB 2: UPLOAD NEW (LOCAL UPLOAD) --}}
                    <div class="tab-pane fade" id="modal_upload_new" role="tabpanel">
                        <div class="bg-white p-5 rounded shadow-sm text-center d-flex flex-column align-items-center justify-content-center" style="height: 450px; border: 2px dashed #ddd;">
                            <div class="mb-4">
                                <i class="bi bi-cloud-arrow-up text-primary" style="font-size: 4rem;"></i>
                            </div>
                            <h5>Drag & Drop files here or click to browse</h5>
                            <p class="text-muted small">Max file size: 10MB | Allowed: jpg, png, webp</p>
                            
                            <input type="file" 
                                   id="ajax_gallery_upload_input" 
                                   class="d-none" 
                                   accept="image/*" 
                                   multiple 
                                   form="fake_form_to_prevent_submit_conflict"
                                   onchange="handleGalleryUpload(this)"
                                   onclick="this.value=null;">
                            
                            {{-- Trigger Button --}}
                            <button type="button" class="btn btn-primary px-4 mt-3" onclick="document.getElementById('ajax_gallery_upload_input').click()">
                                Browse Files (Multiple)
                            </button>

                            {{-- Upload Progress --}}
                            <div id="modal_upload_progress" class="mt-4 w-50" style="display: none;">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer justify-content-between bg-white">
                <div class="d-flex align-items-center gap-3">
                    <span class="small fw-bold"><span id="modal_count_selected">0</span> File(s) selected</span>
                    <button type="button" class="btn btn-link text-danger text-decoration-none small p-0" onclick="clearModalSelection()">Clear Selection</button>
                </div>
                <button type="button" class="btn btn-warning text-white px-4" onclick="useSelectedImage()">Add Files</button>
            </div>
        </div>
    </div>
</div>

{{-- CSS STYLES --}}
<style>
    /* Tabs Design */
    .nav-tabs .nav-link { color: #555; font-weight: 500; border: none; padding: 10px 20px; border-radius: 5px 5px 0 0; background: transparent; }
    .nav-tabs .nav-link.active { color: #000; background: #f8f9fa; border-bottom: 2px solid white; font-weight: bold; box-shadow: 0 -2px 5px rgba(0,0,0,0.05); }
    
    /* Gallery Items */
    .modal-media-item { cursor: pointer; border: 2px solid #eee; border-radius: 4px; transition: 0.2s; position: relative; }
    .modal-media-item:hover { border-color: #ff9f43; } 
    /* Selected State */
    .modal-media-item.selected { border-color: #28c76f !important; background: #e8fadf; transform: scale(0.95); }
    .modal-media-item img { height: 130px; object-fit: cover; width: 100%; border-radius: 2px; }
    
    /* Check Icon */
    .modal-check-icon { display: none; position: absolute; top: 5px; right: 5px; background: #28c76f; color: white; border-radius: 50%; width: 25px; height: 25px; text-align: center; line-height: 25px; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
    .modal-media-item.selected .modal-check-icon { display: block; }
</style>

{{-- JAVASCRIPT LOGIC --}}
<script>
    // Global Variables (Namespaced to avoid conflicts)
    let modalTargetInput = ''; 
    let modalSelectedUrls = []; // Array for multiple selection

    // 1. OPEN MODAL & LOAD GALLERY
    function openGallery(inputId) {
        modalTargetInput = inputId;
        
        // Reset previous selection
        clearModalSelection();
        
        // Open Modal
        $('#globalMediaModal').modal('show'); 
        
        // Set 'Select File' tab active
        var firstTabEl = document.querySelector('#modal_mediaTab button[data-bs-target="#modal_select_file"]');
        if(firstTabEl){
            var tab = new bootstrap.Tab(firstTabEl);
            tab.show();
        }
        
        loadModalGalleryImages();
    }

    // 2. LOAD IMAGES FROM SERVER (AJAX) - UPDATED WITH BUTTONS
    function loadModalGalleryImages() {
        $('#modal_galleryLoader').show();
        $('#modal_mediaGalleryGrid').html('');

        $.ajax({
            url: "{{ route('media.get_list') }}",
            type: "GET",
            success: function(response) {
                $('#modal_galleryLoader').hide();
                let html = '';
                
                // ডিলিট রাউটের বেস URL
                let baseDeleteUrl = "{{ route('media.delete', ':id') }}";

                if(response.length > 0){
                    response.forEach(image => {
                        let fullPath = "{{ asset('public/storage') }}/" + image.path;
                        
                        // ডিলিট URL তৈরি
                        let deleteUrl = baseDeleteUrl.replace(':id', image.id);

                        html += `
                            <div class="col-6 col-md-3 col-lg-2 search-item">
                                <div class="card modal-media-item h-100" onclick="toggleModalSelection(this, '${fullPath}')">
                                    <div class="p-2 position-relative">
                                        <img src="${fullPath}" class="img-fluid" loading="lazy">
                                        <div class="modal-check-icon"><i class="fas fa-check"></i></div>
                                        <p class="small text-muted text-truncate mt-2 mb-2 file-name" title="${image.filename}">${image.filename}</p>
                                        
                                        <div class="d-flex justify-content-between gap-1 mt-auto border-top pt-2">
                                            <button type="button" class="btn btn-sm btn-outline-info flex-fill py-0" 
                                                onclick="copyToClipboard('${fullPath}', event)" 
                                                title="Copy Link">
                                                <i class="fas fa-copy" style="font-size: 12px;"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-outline-danger flex-fill py-0" 
                                                onclick="deleteMediaImage('${deleteUrl}', event)" 
                                                title="Delete Image">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                    });
                } else {
                    html = '<div class="col-12 text-center py-5"><h5 class="text-muted">No files found</h5></div>';
                }
                $('#modal_mediaGalleryGrid').html(html);
            },
            error: function() {
                $('#modal_galleryLoader').hide();
                $('#modal_mediaGalleryGrid').html('<p class="text-danger text-center">Failed to load images.</p>');
            }
        });
    }

    // 3. UPLOAD NEW IMAGE LOGIC (FIXED FOR 10MB)
    function handleGalleryUpload(inputElement) {
        
        let files = inputElement.files;
        // 10MB Limit
        let maxFileSize = 10 * 1024 * 1024; 
        
        // ফাইল না থাকলে রিটার্ন
        if (files.length === 0) return;

        let formData = new FormData();
        let valid = true;
        
        // --- VALIDATION START ---
        for(let i=0; i<files.length; i++){
            // সাইজ চেক (Size Check)
            if(files[i].size > maxFileSize){
                alert('⚠️ Error: "' + files[i].name + '" ফাইলটি অনেক বড়! সর্বোচ্চ সাইজ ১০ এমবি।');
                valid = false;
                
                // ইনপুট রিসেট
                inputElement.value = ''; 
                break; 
            }
            formData.append('files[]', files[i]);
        }

        if(!valid) return;
        // --- VALIDATION END ---
        
        formData.append('_token', "{{ csrf_token() }}");

        // প্রোগ্রেস বার রিসেট এবং শো করা
        $('#modal_upload_progress').show();
        $('.progress-bar').css('width', '0%').text('0%').removeClass('bg-success bg-danger').addClass('progress-bar-animated');

        $.ajax({
            url: "{{ route('media.store') }}", 
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            
            // --- PROGRESS BAR LOGIC ---
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        $('.progress-bar').width(percentComplete + '%');
                        $('.progress-bar').html(Math.round(percentComplete) + '%');
                    }
                }, false);
                return xhr;
            },

            success: function(response) {
                $('.progress-bar').text('Uploaded!').addClass('bg-success').removeClass('progress-bar-animated');
                
                setTimeout(function(){
                    $('#modal_upload_progress').hide();
                    
                    // Switch back to Select Tab
                    var selectTabBtn = document.querySelector('#modal_mediaTab button[data-bs-target="#modal_select_file"]');
                    if(selectTabBtn) {
                        var selectTab = new bootstrap.Tab(selectTabBtn);
                        selectTab.show();
                    }
                    
                    loadModalGalleryImages(); // Reload gallery with new images (and buttons!)
                    inputElement.value = ''; 
                }, 1000);
            },
            error: function(xhr) {
                $('.progress-bar').text('Failed').addClass('bg-danger').removeClass('progress-bar-animated');
                
                setTimeout(function(){ $('#modal_upload_progress').hide(); }, 2000);
                
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    alert('Upload Failed: ' + xhr.responseJSON.message);
                } else {
                    alert('Upload failed. Check php.ini limits.');
                }
                inputElement.value = ''; 
            }
        });
    }

    // 4. MULTIPLE SELECTION LOGIC
    function toggleModalSelection(element, url) {
        $(element).toggleClass('selected');

        if ($(element).hasClass('selected')) {
            modalSelectedUrls.push(url);
        } else {
            modalSelectedUrls = modalSelectedUrls.filter(item => item !== url);
        }

        $('#modal_count_selected').text(modalSelectedUrls.length);
    }

    function clearModalSelection() {
        $('.modal-media-item').removeClass('selected');
        modalSelectedUrls = [];
        $('#modal_count_selected').text('0');
    }

    // 5. INSERT IMAGE TO INPUT
    function useSelectedImage() {
        if (modalSelectedUrls.length === 0) {
            alert('Please select at least one image!');
            return; 
        }

        if(modalTargetInput) {
            
            // CASE 1: Multiple
            if(modalTargetInput === 'multi_image_manager') {
                let container = $('#product_gallery_container');
                modalSelectedUrls.forEach(url => {
                    let html = `
                        <div class="gallery-item-card position-relative">
                            <button type="button" class="remove-btn-small" onclick="$(this).parent().remove(); updateGalleryCountText();">
                                <i class="bi bi-x"></i>
                            </button>
                            <div class="preview-image-box" style="width: 100%; height: 100%;">
                                <img src="${url}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <input type="hidden" name="gallery_images[]" value="${url}">
                        </div>`;
                    container.append(html);
                });
                if(typeof updateGalleryCountText === 'function') updateGalleryCountText();

            } 
            // CASE 2: Single
            else {
                let url = modalSelectedUrls[0]; 
                $('#' + modalTargetInput).val(url);
                
                let previewBox = $('#preview-' + modalTargetInput);
                let imgTag = previewBox.find('img');
                
                if(imgTag.length > 0) {
                    imgTag.attr('src', url).show();
                } else {
                    previewBox.html(`<img src="${url}" style="width: 100%; height: 100%; object-fit: cover;">`);
                }

                if(modalTargetInput === 'thumbnail_img'){
                    $('#preview-thumbnail_img_card').show().css('display', 'flex');
                    $('#thumb_status').text('1 File selected');
                }
            }

            $('#globalMediaModal').modal('hide');
            clearModalSelection();
        }
    }

    // Helper: Count Update
    function updateGalleryCountText() {
        let count = $('#product_gallery_container .gallery-item-card').length;
        if($('#gallery_count_display').length){
            $('#gallery_count_display').text(count + ' Files selected');
        }
    }

    // Helper: Search
    function filterGallery(query) {
        query = query.toLowerCase();
        $('.search-item').each(function() {
            let text = $(this).find('.file-name').text().toLowerCase();
            if(text.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Helper: Copy to Clipboard
    function copyToClipboard(text, event) {
        event.stopPropagation();
        navigator.clipboard.writeText(text).then(function() {
            alert('Link copied!'); 
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }

    // Helper: Delete Image (AJAX)
    function deleteMediaImage(url, event) {
        event.stopPropagation();
        
        if(!confirm('Are you sure you want to delete this image?')) return;

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _method: 'DELETE',
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                // alert('Image deleted successfully!'); // চাইলে কমেন্ট আউট করতে পারেন
                loadModalGalleryImages(); // Refresh Grid
            },
            error: function(xhr) {
                alert('Failed to delete image.');
            }
        });
    }
</script>

{{-- Optional: For non-modal standalone forms --}}
<script>
$('#mediaUploadForm').on('submit', function(e) {
    e.preventDefault(); 
    let formData = new FormData(this);
    $('#uploadMessage').removeClass('d-none alert-danger').addClass('alert-info').text('Uploading...');

    $.ajax({
        url: "{{ route('media.store') }}",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(res) {
            $('#uploadMessage').removeClass('alert-info').addClass('alert-success').text(res.message);
            $('#mediaUploadForm')[0].reset();
        },
        error: function(xhr) {
            $('#uploadMessage').removeClass('alert-info').addClass('alert-danger').text('Upload failed!');
        }
    });
});
</script>