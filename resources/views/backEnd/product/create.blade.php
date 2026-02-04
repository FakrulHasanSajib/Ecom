@extends('backEnd.layouts.master')

@section('title','Product Create')

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
        gap: 20px;
        padding-top: 10px;
        padding-right: 10px;
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
        overflow: visible;
        margin-top: 5px;
    }

    .gallery-item-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
        display: block;
    }

    /* Remove Button (Red X) */
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
        font-size: 14px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        z-index: 50;
        transition: 0.2s;
    }

    .remove-btn-small:hover {
        background: #ef4444;
        color: #fff;
        transform: scale(1.1);
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
    
    .product-form-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    
    /* Toggle Switch Fix */
    .form-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }




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
    border: 1px solid #ddd; /* Halka border jate white color-o bojha jay */
    position: relative;
}

/* Hover & Selected state */
.color-circle-label:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.2) !important;
}

.color-input-hidden:checked + .color-circle-label {
    border: 3px solid #333; /* Select korle dark border */
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
                    <i class="bi bi-plus-circle me-2"></i>Create New Product
                </h4>
            </div>
        </div>
    </div>

    <form action="{{route('products.store')}}" method="POST" data-parsley-validate enctype="multipart/form-data">
        @csrf
        
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
                                   value="{{ old('name') }}" 
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
                                <option value="{{$category->id}}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                <option value="{{$brand->id}}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
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
                               value="{{ old('purchase_price') }}" 
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
                               value="{{ old('offer_price') }}" 
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
                               value="{{ old('reseller_price') }}" 
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
                               value="{{ old('old_price') }}" 
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
                               value="{{ old('new_price') }}" 
                               id="new_price"
                               placeholder="0.00">
                        @error('new_price')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                   
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
                                   value="{{ old('pro_unit') }}" 
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
                                   value="{{ old('product_code') }}" 
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
               value="{{ old('stock') }}" 
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





        <!-- Product Variations Card -->
 <div class="card form-card mb-4">
            <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-palette2 me-2"></i>Product Variations
                </h5>

                <div class="form-check form-switch">
                    {{-- ডিফল্টভাবে unchecked --}}
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="variationToggle" 
                           value="1">
                           
                    <label class="form-check-label fw-semibold" for="variationToggle">
                        Enable
                    </label>
                </div>
            </div>

            {{-- style="display:none;" দিয়ে ডিফল্টভাবে লুকিয়ে রাখা হলো --}}
            <div class="card-body" id="variationSection" style="display:none;">
                <div class="row">

                    <div class="col-md-12 mb-4">
                        <label class="form-label d-block fw-bold mb-3">Available Sizes</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($sizes as $size)
                                <input type="checkbox"
                                       class="btn-check"
                                       name="proSize[]"
                                       id="size-{{$size->id}}"
                                       value="{{$size->id}}"
                                       autocomplete="off"
                                       {{ is_array(old('proSize')) && in_array($size->id, old('proSize')) ? 'checked' : '' }}>

                                <label class="btn btn-outline-primary size-chip"
                                       for="size-{{$size->id}}">
                                    {{$size->sizeName}}
                                </label>
                            @endforeach
                        </div>
                        @error('proSize')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label d-block fw-bold text-muted mb-3">
                            Available Colors
                        </label>

                        <div class="d-flex flex-wrap gap-4">
                            @foreach($colors as $color)
                                @if(!empty($color->color))
                                    <div class="color-selection-item">
                                        <input type="checkbox"
                                               name="proColor[]"
                                               id="color-{{$color->id}}"
                                               value="{{$color->id}}"
                                               class="color-input-hidden"
                                               {{ is_array(old('proColor')) && in_array($color->id, old('proColor')) ? 'checked' : '' }}>

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
                        @error('proColor')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- 
               [INLINE SCRIPT - UPDATED]
               মেমোরি কি নাম পরিবর্তন করা হয়েছে 'variation_create_state' যাতে পুরনো মেমোরি ক্লিয়ার হয়।
               লজিক: ডিফল্ট OFF। যদি '1' পায় তবেই অন করবে।
            --}}
            <script>
                (function() {
                    try {
                        var section = document.getElementById('variationSection');
                        var toggle = document.getElementById('variationToggle');
                        
                        // [NEW KEY] নতুন কি নাম ব্যবহার করছি যাতে পুরনো মেমোরি ঝামেলা না করে
                        var localState = localStorage.getItem('variation_create_state');
                        
                        // যদি মেমোরিতে '1' (অন) থাকে, শুধুমাত্র তখনই অন হবে
                        if (localState === '1') {
                            section.style.display = 'block';
                            toggle.checked = true;
                            
                            var inputs = section.getElementsByTagName('input');
                            for (var i = 0; i < inputs.length; i++) {
                                inputs[i].disabled = false;
                            }
                        } else {
                            // অন্যথায় সব ক্ষেত্রে বন্ধ থাকবে
                            section.style.display = 'none';
                            toggle.checked = false;
                            
                            var inputs = section.getElementsByTagName('input');
                            for (var i = 0; i < inputs.length; i++) {
                                inputs[i].disabled = true;
                            }
                        }
                    } catch (e) {
                        console.error("Variation Inline Script Error:", e);
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
                                    <input class="form-check-input" type="checkbox" role="switch" checked name="continue_sell">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Top Sale</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" name="topsale">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Reselling</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" checked name="reselling_sell">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Hot Deals</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" name="hot_deal">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Featured</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" checked name="feature_product">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-2">
                            <div class="toggle-item">
                                <span class="toggle-label">Status</span>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status">
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
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="freeShippingToggle" name="free_shipping">
                                            <label class="form-check-label fw-bold" for="freeShippingToggle">
                                                Free Shipping
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8" id="shippingChargesSection">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold">Shipping Area</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="shipping_area" id="insideDhaka" value="inside" checked>
                                                        <label class="form-check-label" for="insideDhaka">Inside Dhaka</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="shipping_area" id="outsideDhaka" value="outside">
                                                        <label class="form-check-label" for="outsideDhaka">Outside Dhaka</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold">Shipping Charge (৳)</label>
                                                    <div id="insideDhaka">
                                                        <input type="text" class="form-control" value="80" name="ঢাকার ভিতরে">
                                                    </div>
                                                    <div id="outsideDhaka" style="display: none;">
                                                        <input type="text" class="form-control" value="150" name="ঢাকার বাহিরে">
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
        
        {{-- ১. গ্যালারি ইমেজ (MULTIPLE) --}}
        <div class="mb-4">
            <label class="form-label fw-bold text-dark mb-2">Gallery Images</label>
            
            {{-- Single Browse Bar --}}
            <div class="file-manager-bar">
                {{-- এখানে একটি নির্দিষ্ট আইডি 'multi_image_manager' পাস করা হচ্ছে --}}
                <button type="button" class="file-browse-btn" onclick="openGallery('multi_image_manager')">
                    <i class="bi bi-folder2-open me-2"></i>Browse Gallery
                </button>
                <span class="file-info-text" id="gallery_count_display">
                    0 Files selected
                </span>
            </div>

            {{-- প্রিভিউ কন্টেইনার (সব ছবি এখানে জমা হবে) --}}
            <div class="d-flex flex-wrap gap-3 mt-3" id="product_gallery_container">
                {{-- JavaScript এর মাধ্যমে এখানে ছবিগুলো অ্যাপেন্ড হবে --}}
            </div>
        </div>

        <hr class="text-muted opacity-25">

        {{-- ২. থাম্বনেইল ইমেজ (SINGLE) --}}
        <div class="mb-2">
            <label class="form-label fw-bold text-dark mb-2">Thumbnail Image <span class="text-muted fw-normal">(250x250)</span></label>
            
            <div class="file-manager-bar" style="max-width: 400px;">
                <button type="button" class="file-browse-btn" onclick="openGallery('thumbnail_img')">
                    Browse
                </button>
                <span class="file-info-text" id="thumb_status">No file selected</span>
            </div>

            {{-- থাম্বনেইল প্রিভিউ --}}
            <div class="gallery-item-card" id="preview-thumbnail_img_card" style="display: none;">
                 <button type="button" class="remove-btn-small" onclick="clearThumbnail()">
                    <i class="bi bi-x"></i>
                </button>
                <div class="preview-image-box" id="preview-thumbnail_img" style="width: 100%; height: 100%;">
                    <img src="" style="display:block; width: 100%; height: 100%; object-fit: cover;">
                </div>
                <input type="hidden" id="thumbnail_img" name="banner">
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
                    <textarea name="short_dec" id="short_dec" rows="6" class="summernote form-control @error('short_dec') is-invalid @enderror" required></textarea>
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
                              placeholder="Enter detailed product description"></textarea>
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
                                   value="{{ old('pro_video') }}" 
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
            {{-- Dynamic Items will appear here --}}
        </div>
        <small class="text-muted">Select Type: Text, Video (Youtube ID), or Image (from Gallery).</small>
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
                               value="{{ old('meta_title') }}" 
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
                                  rows="4"></textarea>
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
                               value="{{ old('tag') }}" 
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
                               value="{{ old('slug') }}" 
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
                    <i class="bi bi-check-circle me-2"></i> Create Product
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
    // 1. Plugins Initialization
    $(".summernote").summernote({
        placeholder: "Write description here...",
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // ==========================================
// DYNAMIC TESTIMONIAL LOGIC (CREATE)
// ==========================================
let testiIndex = 0;

$('#addTestimonialBtn').on('click', function() {
    testiIndex++;
    let uniqueId = `testi_img_${testiIndex}`; 

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
                    <textarea name="testimonials[${testiIndex}][content]" class="form-control form-control-sm" rows="2" placeholder="Write review..."></textarea>
                </div>
                <div class="col-md-9 image-field" style="display:none;">
                    <label class="form-label small fw-bold">Select Image from Gallery</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="testimonials[${testiIndex}][image]" id="${uniqueId}" readonly>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="openGallery('${uniqueId}')">Browse</button>
                    </div>
                    <div class="mt-2" style="width: 80px; height: 80px; border: 1px dashed #ddd; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <img src="" id="preview-${uniqueId}" style="max-width: 100%; max-height: 100%; display: none;">
                    </div>
                </div>
            </div>
        </div>`;
    $('#testimonial_container').append(row);
});

$(document).on('click', '.remove-testi', function() {
    $(this).closest('.testimonial-item').remove();
});

$(document).on('change', '.testi-type', function() {
    let type = $(this).val();
    let row = $(this).closest('.testimonial-item');
    if(type === 'image') {
        row.find('.content-field').hide();
        row.find('.image-field').show();
    } else {
        row.find('.content-field').show();
        row.find('.image-field').hide();
    }
});

    $(".select2").select2({ theme: "bootstrap-5", width: '100%' });

    // 2. Global Gallery Counter Init
    window.galleryCount = 0;

    // 3. Category Dependency AJAX
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
});

// ==========================================
// MEDIA MANAGER LOGIC (Create Page)
// ==========================================

// ১. নতুন গ্যালারি বক্স তৈরি করা (Add New Image)
function addNewGalleryItem() {
    window.galleryCount++;
    let id = window.galleryCount;
    
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
    openGallery(`gallery_input_${id}`);
    updateGalleryCount();
}

// ২. বক্স রিমুভ করা
function removeGalleryRow(id) {
    $('#row_' + id).remove();
    updateGalleryCount();
}

// ৩. ফাইল কাউন্টার আপডেট
function updateGalleryCount() {
    let count = $('#gallery_container .gallery-item-card').length;
    $('#gallery_count_display').text(count + ' Files selected');
}

// ৪. থাম্বনেইল রিমুভ ফাংশন
function clearThumbnail() {
    $('#thumbnail_img').val('');
    $('#preview-thumbnail_img img').attr('src', '').hide();
    $('#preview-thumbnail_img_card').hide();
    $('#thumb_status').text('No file selected');
}

// ৫. অটোমেটিক প্রিভিউ আপডেট (Powerful Check)
// এটি প্রতি ০.৫ সেকেন্ডে চেক করে মডাল থেকে কোনো লিংক এসেছে কিনা
setInterval(function() {
    
    // A. গ্যালারি ইমেজের জন্য
    $('#gallery_container .gallery-item-card').each(function() {
        let inputField = $(this).find('input[type="hidden"]');
        let imgTag = $(this).find('img');
        
        if(inputField.val() && inputField.val().trim() !== '') {
            if(imgTag.attr('src') !== inputField.val()) {
                imgTag.attr('src', inputField.val()).show();
            }
        }
    });

    // B. থাম্বনেইল ইমেজের জন্য
    let thumbInput = $('#thumbnail_img').val();
    
    if(thumbInput && thumbInput.trim() !== '') {
        let thumbImg = $('#preview-thumbnail_img img');
        
        // প্রিভিউ কার্ড শো করানো (Display Flex জরুরি)
        $('#preview-thumbnail_img_card').css('display', 'flex'); 
        $('#thumb_status').text('1 File selected');
        
        // ইমেজ সোর্স আপডেট
        if(thumbImg.attr('src') !== thumbInput){
            thumbImg.attr('src', thumbInput).show();
        }
    } else {
        // ইনপুট খালি থাকলে হাইড
        $('#preview-thumbnail_img_card').hide();
        $('#thumb_status').text('No file selected');
    }
    
}, 500);


// ================= SHIPPING LOGIC =================
document.addEventListener('DOMContentLoaded', function () {
    const freeShippingToggle = document.getElementById('freeShippingToggle');
    const shippingSection = document.getElementById('shippingChargesSection');
    const insideRadio = document.getElementById('insideDhaka');
    const outsideRadio = document.getElementById('outsideDhaka');
    
    // চার্জের ইনপুট ডিভগুলো ধরা
    const divInside = document.getElementById('chargeInsideInput'); 
    const divOutside = document.getElementById('chargeOutsideInput'); 

    // যেহেতু create পেজে ডিভগুলোর আইডি ভিন্ন হতে পারে, তাই চেক করে নেওয়া ভালো
    // তবে আমি HTML এ ঠিক করে দিয়েছি, তাই এটি কাজ করবে।

    function toggleShipping() {
        if (freeShippingToggle.checked) {
            shippingSection.style.opacity = '0.5';
            shippingSection.style.pointerEvents = 'none';
        } else {
            shippingSection.style.opacity = '1';
            shippingSection.style.pointerEvents = 'auto';
        }
    }

    function toggleChargeFields() {
        // Create পেজে সাধারণত আমরা ডিফল্ট ইনপুটগুলো হাইড করি না, 
        // কিন্তু যদি আপনি ইনসাইড/আউটসাইড এর উপর ভিত্তি করে ইনপুট দেখাতে চান:
        if (divInside && divOutside) {
            if (insideRadio.checked) {
                divInside.style.display = 'block';
                divOutside.style.display = 'none';
            } else {
                divInside.style.display = 'none';
                divOutside.style.display = 'block';
            }
        }
    }

    if(freeShippingToggle){
        freeShippingToggle.addEventListener('change', toggleShipping);
        insideRadio.addEventListener('change', toggleChargeFields);
        outsideRadio.addEventListener('change', toggleChargeFields);
        
        // ইনিশিয়াল রান
        toggleShipping();
        toggleChargeFields();
    }
});

// ================= SEO GENERATOR =================
document.getElementById('seoGenerateBtn').addEventListener('click', function(e) {
    e.preventDefault();
    const productName = document.getElementById('name').value.trim();
    
    // সামারনোট থেকে টেক্সট নেওয়া
    let shortDesc = $('#short_dec').summernote('code');
    if(!shortDesc) shortDesc = document.querySelector('textarea[name="short_dec"]').value;

    if (!productName) { 
        alert('Please enter product name first'); 
        return; 
    }

    const btn = this;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = 'Generating...';
    btn.disabled = true;

    $.ajax({
        url: "{{ route('products.ai_seo') }}",
        type: "POST",
        data: { name: productName, description: shortDesc, _token: "{{ csrf_token() }}" },
        success: function(response) {
            if(response.status === 'success' && response.data) {
                $('#meta_title').val(response.data.meta_title);
                $('#meta_description').val(response.data.meta_description);
                $('#tag').val(response.data.tags);
                if(!$('#slug').val()) {
                     $('#slug').val(productName.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-').trim());
                }
                showNotification('SEO Generated Successfully', 'success');
            }
        },
        complete: function() { btn.innerHTML = originalHtml; btn.disabled = false; }
    });
});

// Auto Slug Generation
// Auto Slug Generation Logic
$('#name').on('input', function() {
    let slugField = $('#slug');
    
    // চেক করা হচ্ছে: স্লাগ ফিল্ড খালি আছে কিনা অথবা এটি অটো-জেনারেটেড কিনা
    // আমরা data-auto এট্রিবিউট দিয়ে ট্র্যাক রাখব
    if (slugField.val() === '' || slugField.data('auto') === true || slugField.data('auto') === undefined) {
        
        let slug = $(this).val()
            .toLowerCase()             // ছোট হাতের অক্ষর
            .trim()                    // আগে-পছনের স্পেস বাদ
            .replace(/[^\w\s-]/g, '')  // স্পেশাল ক্যারেক্টার বাদ
            .replace(/[\s_-]+/g, '-')  // স্পেস বা আন্ডারস্কোর থাকলে হাইফেন
            .replace(/^-+|-+$/g, '');  // শুরু বা শেষের হাইফেন বাদ

        slugField.val(slug);
        slugField.data('auto', true); // মার্ক করে রাখা হলো যে এটি অটো আপডেট হচ্ছে
    }
});

// যদি ইউজার ম্যানুয়ালি স্লাগ এডিট করে, তাহলে অটো-আপডেট বন্ধ হয়ে যাবে
$('#slug').on('input', function() {
    $(this).data('auto', false);
});

// Notification Helper
function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-info';
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.3s ease;`;
    notification.innerHTML = `<i class="bi bi-check-circle me-2"></i> ${message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(notification);
    setTimeout(() => { notification.remove(); }, 3000);
}
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
<script>
    // টেস্টমোনিয়াল ইমেজ এবং ভিডিও প্রিভিউ ইঞ্জিন
    setInterval(function() {
        // ১. ইমেজ প্রিভিউ লজিক
        $('#testimonial_container .image-field input').each(function() {
            let inputId = $(this).attr('id');
            let imageUrl = $(this).val();
            let previewImg = $('#preview-' + inputId);

            if(imageUrl && imageUrl.trim() !== '') {
                // ইনপুট ফিল্ডে ইউআরএল থাকলে ইমেজটি শো করবে এবং সোর্স আপডেট করবে
                if(previewImg.attr('src') !== imageUrl) {
                    previewImg.attr('src', imageUrl).show(); 
                }
            } else {
                previewImg.hide();
            }
        });

        // ২. ভিডিও (YouTube) প্রিভিউ লজিক
        $('#testimonial_container .testimonial-item').each(function() {
            let type = $(this).find('.testi-type').val();
            let content = $(this).find('.content-field textarea').val();
            
            if(type === 'video' && content && content.trim() !== '') {
                let videoId = content.trim();
                let thumbUrl = `https://img.youtube.com/vi/${videoId}/mqdefault.jpg`;
                
                // প্রিভিউ কন্টেইনার না থাকলে তৈরি করবে
                if($(this).find('.video-preview').length === 0) {
                    $(this).find('.content-field').append('<div class="video-preview mt-2"><img src="" style="width:120px; border-radius:5px; border:1px solid #ddd;"> <small class="text-muted d-block">Video Preview</small></div>');
                }
                
                let imgTag = $(this).find('.video-preview img');
                if(imgTag.attr('src') !== thumbUrl) {
                    imgTag.attr('src', thumbUrl);
                }
            } else {
                // টাইপ ভিডিও না হলে বা আইডি না থাকলে প্রিভিউ সরাবে
                $(this).find('.video-preview').remove();
            }
        });
    }, 1000); // প্রতি ১ সেকেন্ডে সব ফিল্ড চেক করবে
</script>

@endsection

