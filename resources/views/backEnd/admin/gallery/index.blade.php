@extends('backEnd.layouts.master')

@section('title', 'Photo Gallery')

@section('content')

<style>
    .gallery-wrapper {
        padding: 15px;
    }
    
    /* ১. গ্যালারি কার্ড ফিক্স (Flexbox) - বাটন নিচে রাখার জন্য */
    .gallery-card {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
        transition: all 0.3s;
        margin-bottom: 25px;
        overflow: hidden;
        
        /* নতুন কোড: কার্ডের হাইট সমান রাখবে এবং বাটন নিচে পাঠাবে */
        display: flex;
        flex-direction: column;
        height: 100%; 
    }

    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58,59,69,.25);
    }
    
    /* ২. ছবির বক্স ডিজাইন */
    .img-box {
        height: 160px; /* ফিক্সড হাইট */
        width: 100%;
        background-color: #f8f9fc;
        border-bottom: 1px solid #f1f1f1;
        position: relative;
        
        /* ফ্লেক্স চাইল্ড সেটিংস */
        flex-shrink: 0; 
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* ছবি বক্সের সাইজ অনুযায়ী ফিট হবে */
        display: block;
    }

    /* চেকবক্স */
    .select-check {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 5;
        transform: scale(1.3);
        cursor: pointer;
    }

    /* ৩. বাটন এরিয়া ফিক্স */
    .card-actions {
        padding: 10px;
        background: #fff;
        border-top: 1px solid #f1f1f1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        
        /* বাটন এরিয়া যাতে সবসময় নিচে থাকে */
        margin-top: auto; 
    }
</style>

<div class="container-fluid gallery-wrapper">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Media Gallery</h1>
        <div>
            <a href="{{ route('media.sync') }}" class="btn btn-info btn-sm shadow-sm">
                <i class="fas fa-sync-alt"></i> Sync Files
            </a>
            <button onclick="copySelectedLinks()" class="btn btn-secondary btn-sm shadow-sm ml-2">
                <i class="fas fa-copy"></i> Copy Selected
            </button>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body text-center p-5" style="border: 2px dashed #4e73df; background: #f8f9fc; cursor: pointer;">
            <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <i class="fas fa-cloud-upload-alt fa-3x text-gray-400 mb-3"></i>
                <h5>Drag & Drop Images Here</h5>
                <p class="text-muted small">Max size: 2MB | Types: jpg, png, webp</p>
                <input type="file" name="files[]" multiple style="position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer;" onchange="document.getElementById('uploadForm').submit()">
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row display-flex">
        @foreach($media as $image)
        <div class="col-xl-2 col-lg-3 col-md-4 col-6 d-flex align-items-stretch"> <div class="gallery-card w-100">
                <div class="img-box">
                    <input type="checkbox" class="select-check" value="{{ asset('public/storage/'.$image->path) }}">
                    <a href="{{ asset('public/storage/'.$image->path) }}" target="_blank">
                        <img src="{{ asset('public/storage/'.$image->path) }}" alt="{{ $image->filename }}">
                    </a>
                </div>
                
                <div class="card-actions">
                    <button class="btn btn-sm btn-light text-primary" 
                            onclick="copyLink('{{ asset('public/storage/'.$image->path) }}', this)" 
                            title="Copy Link">
                        <i class="fas fa-copy"></i>
                    </button>

                    <form action="{{ route('media.delete', $image->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-center">
            {{ $media->links('pagination::bootstrap-4') }}
        </div>
    </div>

</div>

<script>
    // ১. সিঙ্গেল লিংক কপি ফাংশন
    function copyLink(url, btn) {
        // টেম্পোরারি ইনপুট তৈরি
        const tempInput = document.createElement("input");
        tempInput.value = url;
        document.body.appendChild(tempInput);
        
        // সিলেক্ট এবং কপি
        tempInput.select();
        document.execCommand("copy");
        
        // রিমুভ ইনপুট
        document.body.removeChild(tempInput);
        
        // বাটন আইকন চেঞ্জ এফেক্ট
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        
        setTimeout(() => { 
            btn.innerHTML = '<i class="fas fa-copy"></i>'; 
        }, 1000);
    }

    // ২. মাল্টিপল লিংক কপি ফাংশন
    function copySelectedLinks() {
        let links = [];
        document.querySelectorAll('.select-check:checked').forEach((cb) => links.push(cb.value));
        
        if(links.length === 0) return alert('Please select images first!');
        
        const temp = document.createElement("input");
        document.body.appendChild(temp);
        // কমা এবং স্পেস দিয়ে লিংক আলাদা হবে
        temp.value = links.join(', ');
        temp.select();
        document.execCommand("copy");
        document.body.removeChild(temp);
        
        alert(links.length + ' links copied to clipboard!');
        
        // সব আনচেক করে দেওয়া
        document.querySelectorAll('.select-check').forEach(cb => cb.checked = false);
    }
</script>

@endsection