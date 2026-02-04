@extends('backEnd.layouts.master')
@section('title','Product Edit')
@section('css')
<style>
    .increment_btn,
    .remove_btn {
        margin-top: -17px;
        margin-bottom: 10px;
    }

    .form-switch .form-check-input {
        width: 3.2rem;
        height: 1.7rem;
        background-color: white;
        border: 4px solid #dc3545;
        position: relative;
        transition: all 0.3s ease-in-out;
        background-image: none;
    }

    .form-switch .form-check-input::before {
        content: "";
        position: absolute;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background-color: #dc3545;
        top: 0.15rem;
        left: 0.15rem;
        transition: all 0.3s ease-in-out;
    }

    .form-switch .form-check-input:checked {
        border-color: #20c997;
    }

    .form-switch .form-check-input:checked::before {
        transform: translateX(1.45rem);
        background-color: #20c997;
    }

    .custom-file-input {
        position: relative;
        display: flex;
        align-items: center;
        cursor: pointer;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.5rem;
        transition: all 0.3s ease;
        min-height: 45px;
    }

    .custom-file-input:hover {
        border-color: #28c76f;
        background-color: #f0f9f4;
    }

    .custom-file-input input[type="file"] {
        opacity: 0;
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        cursor: pointer;
    }

    .custom-file-input span {
        color: #6c757d;
        font-weight: 400;
        font-size: 0.9rem;
    }

    .upload-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #28c76f;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0 0.375rem 0.375rem 0;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .upload-btn:hover {
        background-color: #1f9954;
    }

    .upload-btn i {
        font-size: 1.2rem;
    }

    .image-box {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f8f9fa;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .image-box img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .image-box.empty {
        border-style: dashed;
        color: #6c757d;
    }

    /* =========================
       CUSTOM GALLERY STYLES 
    ============================ */
    .file-manager-bar {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 20px;
        width: 100%;
    }
    
    .file-browse-btn {
        background: #f1f5f9;
        border: none;
        border-right: 1px solid #dee2e6;
        padding: 10px 25px;
        color: #475569;
        font-weight: 600;
        transition: all 0.2s;
        white-space: nowrap;
    }
    
    .file-browse-btn:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .file-info-text {
        padding: 0 15px;
        color: #64748b;
        font-size: 0.9rem;
    }

    /* Grid Layout for Images */
    .gallery-grid-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px; /* গ্যাপ একটু বাড়ানো হলো */
        padding-top: 10px; /* উপরে ফাঁকা রাখা হলো যাতে বাটন কেটে না যায় */
        padding-right: 10px; /* ডানে ফাঁকা রাখা হলো */
    }

    .gallery-item-card {
        width: 120px;
        height: 120px;
        position: relative;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        padding: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: visible; /* এটি খুবই জরুরি: বাটন যেন কেটে না যায় */
        margin-top: 5px; /* সেফটি মার্জিন */
    }

    .gallery-item-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
        display: block;
    }

    /* Remove Button (Red X) - FIXED POSITION */
    .remove-btn-small {
        position: absolute;
        top: -10px;   /* কার্ডের উপরে ভেসে থাকবে */
        right: -10px; /* কার্ডের ডানে ভেসে থাকবে */
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
        font-size: 14px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.15); /* শ্যাডো দেওয়া হলো যাতে ভেসে থাকে */
        z-index: 50; /* অন্য কিছুর নিচে যেন না পড়ে */
        transition: 0.2s;
    }

    .remove-btn-small:hover {
        background: #ef4444;
        color: #fff;
        transform: scale(1.1); /* হোভার করলে একটু বড় হবে */
    }

    /* Standard Form Styles */
    .form-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
        margin-bottom: 25px;
    }
    .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 15px 20px;
    }
    .form-control-enhanced {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
    }
    .form-control-enhanced:focus {
        border-color: #28c76f;
        box-shadow: 0 0 0 3px rgba(40, 199, 111, 0.1);
    }
    .required-star { color: #ef4444; }

    /* Size & Color Chips */
    .size-chip { min-width: 45px; border-radius: 6px; }
    .color-circle-label {
        width: 35px; height: 35px; border-radius: 50%; 
        cursor: pointer; border: 2px solid #ddd; position: relative;
    }
    .color-input-hidden:checked + .color-circle-label {
        border-color: #333; transform: scale(1.1);
    }
    .selection-tick {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        color: white; font-size: 1rem; display: none; text-shadow: 0 0 2px rgba(0,0,0,0.5);
    }
    .color-input-hidden:checked + .color-circle-label .selection-tick { display: block; }
    /* New design improvements */
    .product-form-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .form-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    /* overflow: hidden;  <-- এই লাইনটি মুছে দিন বা কমেন্ট করুন, নতুবা ড্রপডাউন কেটে যাবে */
}

.form-card:hover {
    /* transform: translateY(-5px); <-- এই লাইনটি মুছে দিন বা কমেন্ট করুন */
    box-shadow: 0 15px 35px rgba(0,0,0,0.1); /* চাইলে শ্যাডো বাড়াতে পারেন, কিন্তু পজিশন নাড়াবেন না */
}

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #28c76f;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section-title i {
        color: #28c76f;
    }

    .floating-label-container {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .floating-label {
        position: absolute;
        top: 0;
        left: 15px;
        transform: translateY(-50%);
        background: white;
        padding: 0 10px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #2c3e50;
        z-index: 10;
        transition: all 0.3s ease;
    }

    .form-control-enhanced {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #fff;
    }

    .form-control-enhanced:focus {
        border-color: #28c76f;
        box-shadow: 0 0 0 0.25rem rgba(40, 199, 111, 0.25);
        background: #fff;
    }

    .toggle-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .toggle-item {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .toggle-item:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.3);
        transform: translateY(-2px);
    }

    .toggle-item.active {
        background: rgba(255,255,255,0.25);
        border-color: #28c76f;
    }

    .toggle-label {
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .price-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .price-card {
        background: #fff;
        border-radius: 10px;
        padding: 15px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .price-card:hover {
        border-color: #28c76f;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(40, 199, 111, 0.1);
    }

    .price-label {
        font-size: 0.8rem;
        color: #666;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .price-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .image-upload-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
    }

    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .preview-image-box {
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        aspect-ratio: 1;
        background: white;
    }

    .preview-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .seo-generator-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 25px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .seo-generator-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .submit-btn {
        background: linear-gradient(135deg, #28c76f 0%, #20b35e 100%);
        border: none;
        border-radius: 12px;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        display: block;
        width: 100%;
        max-width: 300px;
        margin: 30px auto;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(40, 199, 111, 0.3);
    }

    .required-star {
        color: #dc3545;
    }

    .help-text {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 5px;
    }

    /* Size Chip Styling */
    .size-chip {
        min-width: 50px;
        border-radius: 8px;
        padding: 8px 15px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    /* Color Swatch Styling */
    .color-selection-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 70px;
        margin-bottom: 15px;
    }

    .color-input-hidden {
        display: none;
    }

    .color-circle-label {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: 1px solid #ddd;
        position: relative;
    }

    /* Hover & Selected state */
    .color-circle-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0,0,0,0.2) !important;
    }

    .color-input-hidden:checked + .color-circle-label {
        border: 3px solid #333;
        transform: scale(1.1);
    }

    .selection-tick {
        color: white;
        font-size: 1.3rem;
        display: none;
        text-shadow: 0 0 4px rgba(0,0,0,0.6);
    }

    .color-input-hidden:checked + .color-circle-label .selection-tick {
        display: block;
    }

    .color-text-display {
        font-size: 11px;
        margin-top: 8px;
        color: #666;
        text-align: center;
        word-break: break-word;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .thumbnail-section,
        .gallery-section,
        .preview-section {
            border: none;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .price-grid {
            grid-template-columns: 1fr;
        }
        
        .toggle-card {
            padding: 10px;
        }
    }
</style>
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container-fluid product-form-container">
    <!-- start page title -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('products.index')}}" class="btn btn-primary rounded-pill">
                        <i class="bi bi-grid me-1"></i> Manage Products
                    </a>
                </div>
                <h4 class="page-title">
                    <i class="bi bi-pencil me-2"></i>Edit Product
                </h4>
            </div>
        </div>
    </div>

    <form action="{{route('products.update')}}" method="POST" data-parsley-validate enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{$edit_data->id}}" name="id" />
        
        <!-- Basic Information Card -->
        <div class="card form-card mb-4">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Basic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Product Name -->
                    <div class="col-lg-12 mb-3">
                        <div class="floating-label-container">
                            <span class="floating-label">
                                Product Name <span class="required-star">*</span>
                            </span>
                            <input class="form-control form-control-enhanced @error('name') is-invalid @enderror" 
                                   type="text" 
                                   name="name" 
                                   value="{{ $edit_data->name }}" 
                                   id="name" 
                                   required
                                   placeholder="Enter product name">
                            @error('name')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Category Selection -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="floating-label-container">
                            <span class="floating-label">
                                Category <span class="required-star">*</span>
                            </span>
                            <select class="form-control form-control-enhanced select2 @error('category_id') is-invalid @enderror" 
                                    name="category_id" 
                                    id="category_id" 
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{$category->id}}" {{ $edit_data->category_id == $category->id ? 'selected' : '' }}>
                                    {{$category->name}}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Subcategory -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="floating-label-container">
                            <span class="floating-label">Subcategory</span>
                            <select class="form-control form-control-enhanced select2 @error('subcategory_id') is-invalid @enderror" 
                                    id="subcategory_id" 
                                    name="subcategory_id">
                                <option value="">Select Subcategory</option>
                            </select>
                            @error('subcategory_id')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Child Category -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="floating-label-container">
                            <span class="floating-label">Child Category</span>
                            <select class="form-control form-control-enhanced select2 @error('childcategory_id') is-invalid @enderror" 
                                    id="childcategory_id" 
                                    name="childcategory_id">
                                <option value="">Select Child Category</option>
                            </select>
                            @error('childcategory_id')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Brand -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="floating-label-container">
                            <span class="floating-label">Brand</span>
                            <select class="form-control form-control-enhanced select2 @error('brand_id') is-invalid @enderror" 
                                    name="brand_id" 
                                    id="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                <option value="{{$brand->id}}" {{ $edit_data->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{$brand->name}}
                                </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Information Card -->
        <div class="card form-card mb-4">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0">
                    <i class="bi bi-tag me-2"></i>Pricing & Inventory
                </h5>
            </div>
            <div class="card-body">
                <div class="price-grid">
                    <!-- Purchase Price -->
                    <div class="price-card">
                        <div class="price-label">Purchase Price <span class="required-star">*</span></div>
                        <input type="text" 
                               class="form-control form-control-enhanced @error('purchase_price') is-invalid @enderror" 
                               name="purchase_price" 
                               value="{{ $edit_data->purchase_price }}" 
                               id="purchase_price"
                               placeholder="0.00">
                        @error('purchase_price')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <!-- Wholesale Price -->
                    <div class="price-card">
                        <div class="price-label">Wholesale Price</div>
                        <input type="text" 
                               class="form-control form-control-enhanced @error('offer_price') is-invalid @enderror" 
                               name="offer_price" 
                               value="{{ $edit_data->offer_price }}" 
                               id="offer_price"
                               placeholder="0.00">
                        @error('offer_price')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <!-- Reseller Price -->
                    <div class="price-card">
                        <div class="price-label">Reseller Price</div>
                        <input type="text" 
                               class="form-control form-control-enhanced @error('reseller_price') is-invalid @enderror" 
                               name="reseller_price" 
                               value="{{ $edit_data->reseller_price }}" 
                               id="reseller_price"
                               placeholder="0.00">
                        @error('reseller_price')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <!-- Regular Price -->
                    <div class="price-card">
                        <div class="price-label">Regular Price <span class="required-star">*</span></div>
                        <input type="text" 
                               class="form-control form-control-enhanced @error('old_price') is-invalid @enderror" 
                               name="old_price" 
                               value="{{ $edit_data->old_price }}" 
                               id="old_price"
                               placeholder="0.00">
                        @error('old_price')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <!-- Offer Price -->
                    <div class="price-card">
                        <div class="price-label">Offer Price <span class="required-star">*</span></div>
                        <input type="text" 
                               class="form-control form-control-enhanced @error('new_price') is-invalid @enderror" 
                               name="new_price" 
                               value="{{ $edit_data->new_price }}" 
                               id="new_price"
                               placeholder="0.00">
                        @error('new_price')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    
                </div>
            </div>
        </div>

        <!-- Product Attributes Card -->
        <div class="card form-card mb-4">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Product Attributes
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Product Unit -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="floating-label-container">
                            <span class="floating-label">Product Unit</span>
                            <input type="text" 
                                   class="form-control form-control-enhanced @error('pro_unit') is-invalid @enderror" 
                                   name="pro_unit" 
                                   value="{{ $edit_data->pro_unit }}" 
                                   id="pro_unit"
                                   placeholder="e.g., Piece, Kg, Liter">
                            @error('pro_unit')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- SKU -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="floating-label-container">
                            <span class="floating-label">SKU Code</span>
                            <input type="text" 
                                   class="form-control form-control-enhanced @error('product_code') is-invalid @enderror" 
                                   name="product_code" 
                                   value="{{ $edit_data->product_code }}" 
                                   id="product_code"
                                   placeholder="Product SKU">
                            @error('product_code')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                    <!-- Stock Quantity -->
                   <div class="col-md-6 col-lg-4 mb-3">
    <div class="floating-label-container">
        <span class="floating-label">
            Stock Quantity <span class="required-star">*</span>
        </span>
        <input type="number" 
               class="form-control form-control-enhanced @error('stock') is-invalid @enderror" 
               name="stock" 
               value="{{ $edit_data->stock }}" 
               id="stock"
               placeholder="Stock Quantity"
               required>
        @error('stock')
        <div class="invalid-feedback">
            <strong>{{ $message }}</strong>
        </div>
        @enderror
    </div>
</div>

                   

                   
                </div>
            </div>
        </div>

        <!--product variation card-->
        
<div class="card form-card mb-4">
    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-palette2 me-2"></i>Product Variations
        </h5>

        @php
            // -----------------------------------------------------
            // ১. স্মার্ট ডিটেকশন লজিক (PHP)
            // -----------------------------------------------------
            $selectedSizeIds = [];
            $selectedColorIds = [];
            $isOn = false;

            // A. যদি ভ্যালিডেশন এরর হয় (সাবমিটের পর), তখন old ইনপুট চেক করবে
            if (old('_token')) {
                $selectedSizeIds = old('proSize', []);
                $selectedColorIds = old('proColor', []);
                $isOn = !empty($selectedSizeIds) || !empty($selectedColorIds);
            } 
            // B. এডিট মোড: ডাটাবেস চেক করা
            else {
                // সেফটি চেক: ভেরিয়েবলগুলো আছে কিনা
                if(isset($selectsizes)) {
                    // পিভট টেবিলের ক্ষেত্রে সাধারণত 'size_id' থাকে
                    $selectedSizeIds = $selectsizes->pluck('size_id')->toArray();
                    // যদি পিভট না হয়ে ডাইরেক্ট রিলেশন হয়, তখন 'id' চেক করব
                    if(empty($selectedSizeIds) && $selectsizes->isNotEmpty()){
                        $selectedSizeIds = $selectsizes->pluck('id')->toArray();
                    }
                }

                if(isset($selectcolors)) {
                    $selectedColorIds = $selectcolors->pluck('color_id')->toArray();
                    if(empty($selectedColorIds) && $selectcolors->isNotEmpty()){
                        $selectedColorIds = $selectcolors->pluck('id')->toArray();
                    }
                }

                // যদি সাইজ বা কালার পাওয়া যায়, তাহলে অন হবে
                $isOn = !empty($selectedSizeIds) || !empty($selectedColorIds);
            }
        @endphp

        <div class="form-check form-switch">
            {{-- name অ্যাট্রিবিউট নেই, তাই SQL error হবে না --}}
            <input class="form-check-input" 
                   type="checkbox" 
                   id="variationToggle" 
                   value="1"
                   {{-- PHP যদি দেখে ডাটা আছে, তাহলে HTML-এই চেকড করে দিবে --}}
                   {{ $isOn ? 'checked' : '' }}>
                   
            <label class="form-check-label fw-semibold" for="variationToggle">
                Enable
            </label>
        </div>
    </div>

    {{-- PHP লজিক অনুযায়ী ডিসপ্লে সেট করা হচ্ছে --}}
    <div class="card-body" id="variationSection" style="{{ $isOn ? '' : 'display:none;' }}">
        <div class="row">

            <div class="col-md-12 mb-4">
                <label class="form-label d-block fw-bold mb-3">Available Sizes</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($totalsizes as $size)
                        <input type="checkbox"
                               class="btn-check"
                               name="proSize[]"
                               id="size-{{$size->id}}"
                               value="{{$size->id}}"
                               autocomplete="off"
                               {{ in_array($size->id, $selectedSizeIds) ? 'checked' : '' }}>

                        <label class="btn btn-outline-primary size-chip"
                               for="size-{{$size->id}}">
                            {{$size->sizeName}}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="col-md-12 mb-4">
                <label class="form-label d-block fw-bold text-muted mb-3">
                    Available Colors
                </label>

                <div class="d-flex flex-wrap gap-4">
                    @foreach($totalcolors as $color)
                        @if(!empty($color->color))
                            <div class="color-selection-item">
                                <input type="checkbox"
                                       name="proColor[]"
                                       id="color-{{$color->id}}"
                                       value="{{$color->id}}"
                                       class="color-input-hidden"
                                       {{ in_array($color->id, $selectedColorIds) ? 'checked' : '' }}>

                                <label for="color-{{$color->id}}"
                                       class="color-circle-label shadow-sm"
                                       style="background-color: {{ $color->color }} !important;">
                                    <i class="bi bi-check-lg selection-tick"></i>
                                </label>

                                <span class="color-text-display">
                                    {{ $color->colorName }}
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- 
       [EDIT PAGE INLINE SCRIPT]
       এখানে আমরা LocalStorage চেক করব না।
       আমরা শুধু দেখব PHP বাটনটিকে 'checked' করে পাঠিয়েছে কিনা।
       যদি checked থাকে, জাভাস্ক্রিপ্ট সেকশন ওপেন রাখবে।
    --}}
    <script>
        (function() {
            try {
                var section = document.getElementById('variationSection');
                var toggle = document.getElementById('variationToggle');
                
                // এডিট পেজে PHP এর সিদ্ধান্তই চূড়ান্ত
                if (toggle.checked) {
                    // ডাটা আছে, তাই ওপেন রাখো এবং ইনপুট এনাবল করো
                    section.style.display = 'block';
                    var inputs = section.getElementsByTagName('input');
                    for (var i = 0; i < inputs.length; i++) {
                        inputs[i].disabled = false;
                    }
                } else {
                    // ডাটা নেই, তাই বন্ধ রাখো
                    section.style.display = 'none';
                    var inputs = section.getElementsByTagName('input');
                    for (var i = 0; i < inputs.length; i++) {
                        inputs[i].disabled = true;
                    }
                }
            } catch (e) {
                console.error("Edit Variation Script Error:", e);
            }
        })();
    </script>
</div>


        <!-- Visibility & Status Card -->
        <div class="card form-card mb-4">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0">
                    <i class="bi bi-eye me-2"></i>Visibility & Status
                </h5>
            </div>
            <div class="card-body">
                <div class="toggle-card">
                    <div class="row g-3">
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Continue Selling</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" name="continue_sell" {{ $edit_data->continue_sell == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Top Sale</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" name="topsale" {{ $edit_data->topsale == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Reselling</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" name="reselling_sell" {{ $edit_data->reselling_sell == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Hot Deals</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" name="hot_deal" {{ $edit_data->hot_deal == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Featured</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" name="feature_product" {{ $edit_data->feature_product == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Status</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status" {{ $edit_data->status == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Section -->
                <div class="row mt-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <div class="row align-items-center">
                    {{-- ১. ফ্রি শিপিং টগল --}}
                    <div class="col-md-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="freeShippingToggle" name="free_shipping" {{ $edit_data->free_shipping == 1 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="freeShippingToggle">
                                Free Shipping
                            </label>
                        </div>
                    </div>

                    <div class="col-md-8" id="shippingChargesSection">
                        <div class="row">
                            {{-- ২. এরিয়া সিলেকশন --}}
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Shipping Area</label>
                                    <div class="form-check">
                                        {{-- ডিফল্ট হিসেবে ইনসাইড চেক করা থাকবে, যদি না চার্জ ১৫০ হয় --}}
                                        <input class="form-check-input" type="radio" name="shipping_area" id="insideDhaka" value="inside" 
                                            {{ ($edit_data->shipping_charge != 150) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="insideDhaka">Inside Dhaka</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="shipping_area" id="outsideDhaka" value="outside"
                                            {{ ($edit_data->shipping_charge == 150) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="outsideDhaka">Outside Dhaka</label>
                                    </div>
                                </div>
                            </div>

                            {{-- ৩. চার্জ এমাউন্ট ইনপুট --}}
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Shipping Charge (৳)</label>
                                    
                                    {{-- ইনসাইড চার্জ ইনপুট --}}
                                    <div id="chargeInsideInput">
                                        <input type="number" class="form-control" name="charge_inside" value="80">
                                    </div>
                                    
                                    {{-- আউটসাইড চার্জ ইনপুট (শুরুতে হাইড থাকবে যদি ইনসাইড সিলেক্ট থাকে) --}}
                                    <div id="chargeOutsideInput" style="display: none;">
                                        <input type="number" class="form-control" name="charge_outside" value="150">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>

        <!-- Product Images Card -->
      <div class="card form-card mb-4">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0">
            <i class="bi bi-images me-2"></i>Product Images
        </h5>
    </div>
    <div class="card-body">
        
        {{-- ১. গ্যালারি ইমেজ --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Gallery Images</label>
            
            <div class="file-manager-bar">
                <button type="button" class="file-browse-btn" onclick="openGallery('multi_image_manager')">
                    <i class="bi bi-folder2-open me-2"></i>Browse Gallery
                </button>
            </div>

            {{-- প্রিভিউ কন্টেইনার --}}
            <div class="d-flex flex-wrap gap-3 mt-3" id="product_gallery_container">
                
                {{-- লুপ চালিয়ে আগের ছবিগুলো শো করানো --}}
                @if(isset($edit_data->images) && count($edit_data->images) > 0)
                    @foreach($edit_data->images as $key => $image)
                        <div class="gallery-item-card position-relative">
                            <button type="button" class="remove-btn-small" onclick="$(this).parent().remove()">
                                <i class="bi bi-x"></i>
                            </button>
                            <div class="preview-image-box" style="width: 100%; height: 100%;">
                                <img src="{{ asset($image->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <input type="hidden" name="gallery_images[]" value="{{($image->image) }}">
                        </div>
                    @endforeach
                @endif
                
            </div>
        </div>

        <hr>

        {{-- ২. থাম্বনেইল ইমেজ --}}
        <div class="mb-2">
            <label class="form-label fw-bold">Thumbnail Image (290x300)</label>
            <div class="file-manager-bar" style="max-width: 400px;">
                <button type="button" class="file-browse-btn" onclick="openGallery('thumbnail_img')">Browse</button>
                <span class="file-info-text" id="thumb_status">{{ $edit_data->banner ? '1 File selected' : 'No file selected' }}</span>
            </div>

            <div class="gallery-item-card" id="preview-thumbnail_img_card" style="{{ $edit_data->banner ? '' : 'display:none;' }}">
                <button type="button" class="remove-btn-small" onclick="clearThumbnail()">
                    <i class="bi bi-x"></i>
                </button>
                <div class="preview-image-box" id="preview-thumbnail_img" style="width: 100%; height: 100%;">
                    @if($edit_data->banner)
                        <img src="{{ asset($edit_data->banner) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src="" style="display:none;">
                    @endif
                </div>
                <input type="hidden" id="thumbnail_img" name="banner" value="{{ $edit_data->banner ? asset($edit_data->banner) : '' }}">
            </div>
        </div>

    </div>
</div>
        <!-- Description Card -->
        <div class="card form-card mb-4">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0">
                    <i class="bi bi-text-paragraph me-2"></i>Product Description
                </h5>
            </div>
            <div class="card-body">
                <!-- Short Description -->
                <div class="mb-4">
                    <label for="short_dec" class="form-label fw-bold mb-2">
                        Short Description <span class="required-star">*</span>
                    </label>
                    <textarea name="short_dec" id="short_dec" rows="6" 
                              class="summernote form-control @error('short_dec') is-invalid @enderror" 
                              required
                              placeholder="Enter brief product description">{{ $edit_data->short_dec }}</textarea>
                    @error('short_dec')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                </div>

                <!-- Full Description -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold mb-2">
                        Full Description <span class="required-star">*</span>
                    </label>
                    <textarea name="description" rows="6" 
                              class="summernote form-control @error('description') is-invalid @enderror" 
                              required
                              placeholder="Enter detailed product description">{{ $edit_data->description }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                </div>

                <!-- Video Link -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="floating-label-container">
                            <span class="floating-label">Product Video Link</span>
                            <input class="form-control form-control-enhanced" 
                                   type="text" 
                                   name="pro_video" 
                                   value="{{ $edit_data->pro_video }}" 
                                   id="pro_video"
                                   placeholder="https://youtube.com/...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card form-card mb-4">
    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-chat-quote me-2"></i>Product Testimonials
        </h5>
        <button type="button" class="btn btn-sm btn-primary" id="addTestimonialBtn">
            <i class="bi bi-plus-circle"></i> Add Review
        </button>
    </div>
    <div class="card-body">
        <div id="testimonial_container">
            @php
                $testimonials = json_decode($edit_data->testimonials, true) ?? [];
                // ইনডেক্স ঠিক রাখার জন্য
                $lastIndex = count($testimonials); 
            @endphp

            @foreach($testimonials as $key => $item)
                @php $uniqueId = 'testi_img_edit_'.$key; @endphp
                <div class="testimonial-item border p-3 rounded mb-3 bg-white position-relative">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-testi">
                        <i class="bi bi-trash"></i>
                    </button>
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Type</label>
                            <select name="testimonials[{{$key}}][type]" class="form-control form-control-sm testi-type">
                                <option value="text" {{ ($item['type'] == 'text') ? 'selected' : '' }}>Text Review</option>
                                <option value="video" {{ ($item['type'] == 'video') ? 'selected' : '' }}>Video (Youtube ID)</option>
                                <option value="image" {{ ($item['type'] == 'image') ? 'selected' : '' }}>Image Review</option>
                            </select>
                        </div>

                        {{-- Text/Video Field --}}
                        <div class="col-md-9 content-field" style="{{ ($item['type'] == 'image') ? 'display:none;' : '' }}">
                            <label class="form-label small fw-bold">Content</label>
                            <textarea name="testimonials[{{$key}}][content]" class="form-control form-control-sm" rows="2">{{ $item['content'] ?? '' }}</textarea>
                        </div>

                        {{-- Image Field (Gallery) --}}
                        <div class="col-md-9 image-field" style="{{ ($item['type'] != 'image') ? 'display:none;' : '' }}">
                            <label class="form-label small fw-bold">Select Image from Gallery</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="testimonials[{{$key}}][image]" id="{{$uniqueId}}" value="{{ $item['image'] ?? '' }}" readonly>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="openGallery('{{$uniqueId}}')">
                                    Browse
                                </button>
                            </div>
                            <div class="mt-2" style="width: 80px; height: 80px; border: 1px dashed #ddd; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if(isset($item['image']))
                                    <img src="{{ $item['image'] }}" id="preview-{{$uniqueId}}" style="max-width: 100%; max-height: 100%;">
                                @else
                                    <img src="" id="preview-{{$uniqueId}}" style="max-width: 100%; max-height: 100%; display: none;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

        <!-- SEO Card -->
        <div class="card form-card mb-4">
            <div class="card-header bg-light py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-search me-2"></i>SEO Settings
                    </h5>
                    <button type="button" class="btn seo-generator-btn" id="seoGenerateBtn">
                        <i class="bi bi-magic me-1"></i> AI Generate SEO
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Meta Title -->
                <div class="mb-3">
                    <div class="floating-label-container">
                        <span class="floating-label">Meta Title</span>
                        <input class="form-control form-control-enhanced" 
                               placeholder="Men Maroon Floral Embroidered Kurta with Trousers" 
                               type="text" 
                               name="meta_title" 
                               value="{{ $edit_data->meta_title }}" 
                               id="meta_title">
                    </div>
                </div>

                <!-- Meta Description -->
                <div class="mb-3">
                    <div class="floating-label-container">
                        <span class="floating-label">Meta Description</span>
                        <textarea class="form-control form-control-enhanced" 
                                  placeholder="Men Maroon Floral Embroidered Kurta with Trousers" 
                                  name="meta_description" 
                                  id="meta_description" 
                                  rows="4">{{ $edit_data->meta_description }}</textarea>
                    </div>
                </div>

                <!-- Tags -->
                <div class="mb-3">
                    <div class="floating-label-container">
                        <span class="floating-label">Tags</span>
                        <input class="form-control form-control-enhanced" 
                               placeholder="Men Maroon Floral Embroidered Kurta with Trousers" 
                               type="text" 
                               name="tag" 
                               value="{{ $edit_data->tag }}" 
                               id="tag">
                    </div>
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <div class="floating-label-container">
                        <span class="floating-label">Slug <span class="required-star">*</span></span>
                        <input class="form-control form-control-enhanced" 
                               placeholder="men-maroon-floral-embroidered-kurta" 
                               type="text" 
                               name="slug" 
                               value="{{ $edit_data->slug }}" 
                               id="slug" 
                               required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row">
            <div class="col-12 text-center">
                <button type="submit" class="btn submit-btn">
                    <i class="bi bi-check-circle me-2"></i> Update Product
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/summernote/summernote-lite.min.js"></script>

<script>
    $(document).ready(function () {
        // 1. Initialize Summernote
        $(".summernote").summernote({
            placeholder: "Enter your text here...",
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // 2. Initialize Select2
        $(".select2").select2({
            theme: "bootstrap-5",
            width: '100%'
        });

        // 3. Category Dependency AJAX
        // Load subcategories and childcategories on page load
        var selectedCategory = '{{ $edit_data->category_id }}';
        var selectedSubcategory = '{{ $edit_data->subcategory_id }}';
        var selectedChildcategory = '{{ $edit_data->childcategory_id }}';

        if (selectedCategory) {
            // Load subcategories
            $.ajax({
                type: "GET",
                url: "{{url('ajax-product-subcategory')}}?category_id=" + selectedCategory,
                success: function (res) {
                    $("#subcategory_id").empty().append('<option value="">Select Subcategory</option>');
                    if (res) {
                        $.each(res, function (key, value) {
                            var selected = key == selectedSubcategory ? 'selected' : '';
                            $("#subcategory_id").append('<option value="' + key + '" ' + selected + '>' + value + '</option>');
                        });
                    }

                    // Load childcategories if subcategory exists
                    if (selectedSubcategory) {
                        $.ajax({
                            type: "GET",
                            url: "{{url('ajax-product-childcategory')}}?subcategory_id=" + selectedSubcategory,
                            success: function (res) {
                                $("#childcategory_id").empty().append('<option value="">Select Child Category</option>');
                                if (res) {
                                    $.each(res, function (key, value) {
                                        var selected = key == selectedChildcategory ? 'selected' : '';
                                        $("#childcategory_id").append('<option value="' + key + '" ' + selected + '>' + value + '</option>');
                                    });
                                }
                            }
                        });
                    }
                }
            });
        }

        // Category to Subcategory AJAX (On Change)
        $("#category_id").on("change", function () {
            var categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    type: "GET",
                    url: "{{url('ajax-product-subcategory')}}?category_id=" + categoryId,
                    success: function (res) {
                        $("#subcategory_id").empty().append('<option value="">Select Subcategory</option>');
                        if (res) {
                            $.each(res, function (key, value) {
                                $("#subcategory_id").append('<option value="' + key + '">' + value + "</option>");
                            });
                        }
                    }
                });
            } else {
                $("#subcategory_id").empty().append('<option value="">Select Subcategory</option>');
            }
            $("#childcategory_id").empty().append('<option value="">Select Child Category</option>');
        });

        // Subcategory to Childcategory AJAX (On Change)
        $("#subcategory_id").on("change", function () {
            var subcategoryId = $(this).val();
            if (subcategoryId) {
                $.ajax({
                    type: "GET",
                    url: "{{url('ajax-product-childcategory')}}?subcategory_id=" + subcategoryId,
                    success: function (res) {
                        $("#childcategory_id").empty().append('<option value="">Select Child Category</option>');
                        if (res) {
                            $.each(res, function (key, value) {
                                $("#childcategory_id").append('<option value="' + key + '">' + value + "</option>");
                            });
                        }
                    }
                });
            } else {
                $("#childcategory_id").empty().append('<option value="">Select Child Category</option>');
            }
        });

        // 4. MEDIA MANAGER INITIALIZATION
        // নতুন গ্যালারি রো অ্যাড করার জন্য কাউন্টার সেট করা।
        // ১০০ থেকে শুরু করছি যাতে existing ডাটার সাথে ID কনফ্লিক্ট না হয়।
        if(typeof galleryCount !== 'undefined') {
            galleryCount = 100; 
        } else {
            window.galleryCount = 100;
        }

// ==========================================
    // DYNAMIC TESTIMONIAL LOGIC (EDIT)
    // ==========================================
    
    // PHP থেকে বর্তমান টেস্টিমোনিয়াল সংখ্যা নেওয়া হচ্ছে যাতে ID কনফ্লিক্ট না হয়
    let testiIndex = {{ isset($lastIndex) ? $lastIndex : 0 }};

    $('#addTestimonialBtn').on('click', function() {
        testiIndex++;
        let uniqueId = `testi_img_new_${testiIndex}`; 

        let row = `
            <div class="testimonial-item border p-3 rounded mb-3 bg-white position-relative">
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-testi">
                    <i class="bi bi-trash"></i>
                </button>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Type</label>
                        <select name="testimonials[${testiIndex}][type]" class="form-control form-control-sm testi-type">
                            <option value="text">Text Review</option>
                            <option value="video">Video (Youtube ID)</option>
                            <option value="image">Image Review</option>
                        </select>
                    </div>

                    <div class="col-md-9 content-field">
                        <label class="form-label small fw-bold">Content</label>
                        <textarea name="testimonials[${testiIndex}][content]" class="form-control form-control-sm" rows="2" placeholder="Write review text..."></textarea>
                    </div>

                    <div class="col-md-9 image-field" style="display:none;">
                        <label class="form-label small fw-bold">Select Image from Gallery</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" name="testimonials[${testiIndex}][image]" id="${uniqueId}" readonly>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openGallery('${uniqueId}')">
                                Browse
                            </button>
                        </div>
                        <div class="mt-2" style="width: 80px; height: 80px; border: 1px dashed #ddd; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="" id="preview-${uniqueId}" style="max-width: 100%; max-height: 100%; display: none;">
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#testimonial_container').append(row);
    });

    // Remove Item
    $(document).on('click', '.remove-testi', function() {
        $(this).closest('.testimonial-item').remove();
    });

    // Toggle Fields based on Type
    $(document).on('change', '.testi-type', function() {
        let type = $(this).val();
        let row = $(this).closest('.testimonial-item');

        if(type === 'image') {
            row.find('.content-field').hide();
            row.find('.image-field').show();
        } else {
            row.find('.content-field').show();
            row.find('.image-field').hide();
            
            let placeholder = (type === 'video') ? "YouTube Video ID (Ex: eIrRj6vDddU)" : "Write review text here...";
            row.find('.content-field textarea').attr('placeholder', placeholder);
        }
    });

    // Gallery Image Preview Check
    setInterval(function() {
        $('#testimonial_container .image-field input').each(function() {
            let id = $(this).attr('id');
            let val = $(this).val();
            if(val && val !== '') {
                let imgTag = $('#preview-' + id);
                if(imgTag.attr('src') !== val) {
                    imgTag.attr('src', val).show();
                }
            }
        });
    }, 1000);
});
    // ================= SEO Logic =================
    document.getElementById('seoGenerateBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        const productName = document.getElementById('name').value.trim();
        let shortDesc = $('#short_dec').summernote('code');
        if(!shortDesc) shortDesc = document.getElementById('short_dec').value;
        
        if (!productName) {
            alert('Please enter product name first');
            document.getElementById('name').focus();
            return;
        }
        
        // Button Loading State
        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Generating...';
        btn.disabled = true;

        $.ajax({
            url: "{{ route('products.ai_seo') }}",
            type: "POST",
            data: {
                name: productName,
                description: shortDesc,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if(response.status === 'success' && response.data) {
                    document.getElementById('meta_title').value = response.data.meta_title;
                    document.getElementById('meta_description').value = response.data.meta_description;
                    document.getElementById('tag').value = response.data.tags;
                    
                    const currentSlug = document.getElementById('slug').value;
                    if(!currentSlug) {
                         const slug = productName.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-').trim();
                        document.getElementById('slug').value = slug;
                    }

                    showNotification('SEO content generated by AI!', 'success');
                } else {
                    showNotification(response.message || 'Failed to generate content', 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                showNotification('Something went wrong. Check console.', 'error');
            },
            complete: function() {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        });
    });

    // ================= Shipping Logic =================
    // Shipping Toggle Script
document.addEventListener('DOMContentLoaded', function () {
    const freeShippingToggle = document.getElementById('freeShippingToggle');
    const shippingSection = document.getElementById('shippingChargesSection');
    const insideRadio = document.getElementById('insideDhaka');
    const outsideRadio = document.getElementById('outsideDhaka');
    
    // ডিভ আইডি গুলো ধরছি
    const divInside = document.getElementById('chargeInsideInput');
    const divOutside = document.getElementById('chargeOutsideInput');

    function toggleShippingSection() {
        if (freeShippingToggle.checked) {
            shippingSection.style.opacity = '0.5';
            shippingSection.style.pointerEvents = 'none';
        } else {
            shippingSection.style.opacity = '1';
            shippingSection.style.pointerEvents = 'auto';
        }
    }

    function toggleChargeFields() {
        if (insideRadio.checked) {
            divInside.style.display = 'block';
            divOutside.style.display = 'none';
        } else {
            divInside.style.display = 'none';
            divOutside.style.display = 'block';
        }
    }

    // Event Listeners
    if(freeShippingToggle){
        freeShippingToggle.addEventListener('change', toggleShippingSection);
        toggleShippingSection(); // Run on load
    }

    if(insideRadio && outsideRadio){
        insideRadio.addEventListener('change', toggleChargeFields);
        outsideRadio.addEventListener('change', toggleChargeFields);
        toggleChargeFields(); // Run on load
    }
});

    // ================= Helper Functions =================

    // Notification Helper
    function showNotification(message, type = 'success') {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-info';
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 300px;
            animation: slideIn 0.3s ease;
        `;
        
        notification.innerHTML = `
            <i class="bi bi-check-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Add CSS animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);

    // Auto generate slug from product name
    document.getElementById('name').addEventListener('input', function() {
        const slugField = document.getElementById('slug');
        if (!slugField.value || slugField.dataset.auto === 'true') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/--+/g, '-')
                .trim();
            slugField.value = slug;
            slugField.dataset.auto = 'true';
        }
    });

    // Reset slug auto-generation when user manually edits it
    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.auto = 'false';
    });
</script>
<script>
// ১. নতুন গ্যালারি আইটেম বক্স তৈরি করা
function addNewGalleryItem() {
    if(typeof galleryCount === 'undefined') window.galleryCount = 100;
    window.galleryCount++;
    let id = window.galleryCount;
    
    // গ্রিড স্টাইলের HTML
    let html = `
        <div class="gallery-item-card" id="row_${id}">
            <button type="button" class="remove-btn-small" onclick="removeGalleryRow(${id})">
                <i class="bi bi-x"></i>
            </button>
            
            <div class="preview-image-box" id="preview-gallery_input_${id}" style="width: 100%; height: 100%;">
                <img src="" style="display:none;"> 
            </div>
            
            <input type="hidden" id="gallery_input_${id}" name="gallery_images[]">
        </div>
    `;
    
    $('#gallery_container').append(html);
    openGallery(`gallery_input_${id}`); // সাথে সাথে মডাল ওপেন হবে
    updateGalleryCount();
}

// ২. রিমুভ ফাংশন
function removeGalleryRow(id) {
    $('#row_' + id).remove();
    updateGalleryCount();
}

// ৩. কাউন্ট আপডেট
function updateGalleryCount() {
    let count = $('#gallery_container .gallery-item-card').length;
    $('#gallery_count_display').text(count + ' Files selected');
}

// ৪. থাম্বনেইল ক্লিয়ার
function clearThumbnail() {
    $('#thumbnail_img').val('');
    $('#preview-thumbnail_img img').attr('src', '');
    $('#preview-thumbnail_img_card').hide();
    $('#thumb_status').text('No file selected');
}

// ৫. থাম্বনেইল চেক (অটো শো করার জন্য)
setInterval(function() {
    if($('#thumbnail_img').val() !== '') {
        $('#preview-thumbnail_img_card').show();
        $('#thumb_status').text('1 File selected');
    }
}, 1000);
</script>
<script>
    // ==========================================
    // VARIATION TOGGLE (CLICK HANDLER)
    // ==========================================
    $('#variationToggle').on('change', function() {
        var isChecked = $(this).is(':checked');
        var $section = $('#variationSection');

        // Save user preference to Browser Memory
        localStorage.setItem('variation_toggle_state', isChecked ? '1' : '0');

        if (isChecked) {
            $section.slideDown(300); // Smooth Open
            $section.find('input').prop('disabled', false); // Enable inputs
        } else {
            $section.slideUp(300); // Smooth Close
            $section.find('input').prop('disabled', true); // Disable inputs
        }
    });
</script>
@endsection