@extends('wholesaler.master')
@section('title','Wholesale Products')
@section('css')

    <style>
        body {
            background-color: #f0f2f5;
        }
        .product-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .product-image-container {
            position: relative;
            background-color: #ffe0b2;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            height: 300px;
        }
        .product-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .info-icon, .download-icon {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            padding: 5px 8px;
            font-size: 0.8em;
            color: #333;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }
        .info-icon {
            top: 10px;
            right: 10px;
        }
        .download-icon {
            bottom: 10px;
            left: 10px;
        }
        .download-icon:hover {
            background-color: rgba(40, 167, 69, 0.9);
            color: white;
            transform: scale(1.1);
        }
        .info-icon:hover {
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
            transform: scale(1.1);
        }
        .price-section {
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
        }
        .price-item {
            text-align: center;
            flex: 1;
        }
        .price-item strong {
            display: block;
            font-size: 0.9em;
            color: #555;
        }
        .price-value {
            font-weight: bold;
            font-size: 1.1em;
        }
        .retail-price { color: #007bff; }
        .wholesale-price { color: #dc3545; }
        .profit-value { color: #28a745; }

        .top-nav-section {
            background-color: #fff;
            padding: 15px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .flash-sales-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .flash-sales-btn i {
            margin-right: 5px;
        }
        .filter-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        .filter-group .form-select {
            min-width: 120px;
            border-radius: 20px;
            padding: 8px 15px;
        }
        .filter-group .btn-primary {
            border-radius: 20px;
        }

        .badge-amr {
            background-color: #ff9800;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
        .special-offer-badge {
            background-color: #ffc107;
            color: #343a40;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            position: absolute;
            bottom: 10px;
            right: 10px;
            z-index: 10;
        }
        .ramadan-badge {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }

        .product-name {
            font-size: 0.9em;
            color: #333;
            margin: 5px 0;
            text-align: center;
        }

        .btn-clear {
            background-color: #6c757d;
            color: white;
            border-radius: 20px;
            padding: 8px 15px;
            border: none;
        }
        .btn-clear:hover {
            background-color: #545b62;
            color: white;
        }

        .products-grid {
            margin-top: 20px;
        }
        /* Clean Pagination WITHOUT Icons */
.custom-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 2rem;
    padding: 1rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.custom-pagination .pagination {
    margin: 0;
    gap: 5px;
}

.custom-pagination .page-link {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px 15px;
    color: #495057;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    min-width: 45px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-pagination .page-link:hover {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}

.custom-pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}

.custom-pagination .page-item.disabled .page-link {
    background-color: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Hide any icons that might appear */
.pagination .page-link i,
.pagination .page-link svg {
    display: none !important;
}

.pagination-info {
    text-align: center;
    margin-bottom: 1rem;
    color: #6c757d;
    font-size: 0.9rem;
}

        @media (max-width: 768px) {
            .top-nav-section {
                flex-direction: column;
                gap: 15px;
            }
            .filter-group {
                justify-content: center;
            }
        }

        /* Custom notification styles */
        .custom-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from { 
                transform: translateX(100%); 
                opacity: 0; 
            }
            to { 
                transform: translateX(0); 
                opacity: 1; 
            }
        }

        @keyframes slideOutRight {
            from { 
                transform: translateX(0); 
                opacity: 1; 
            }
            to { 
                transform: translateX(100%); 
                opacity: 0; 
            }
        }

        /* Modal custom styles */
        .description-modal .modal-dialog {
            max-width: 600px;
        }
        
        .description-modal .modal-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-bottom: none;
            border-radius: 0.375rem 0.375rem 0 0;
        }
        
        .description-modal .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .description-modal .modal-body {
            padding: 1.5rem;
        }
        
        .description-content {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #e9ecef;
            min-height: 120px;
            max-height: 300px;
            overflow-y: auto;
            font-size: 0.95rem;
            line-height: 1.6;
            word-wrap: break-word;
        }
        
        .copy-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
        }
        
        .copy-btn:hover {
            background: linear-gradient(135deg, #218838, #1ea080);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        
        .copy-btn:active {
            transform: translateY(0);
        }
        
        .copy-btn i {
            margin-right: 8px;
        }
        
        .no-description {
            color: #6c757d;
            font-style: italic;
            text-align: center;
            padding: 2rem;
        }

        /* Animation for copy success */
        .copy-success {
            animation: pulse 0.6s ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <form method="GET" action="{{ route('wholesaler.product') }}" id="filterForm">
            <div class="top-nav-section">
                <h4 class="mb-0 me-3">Wholesale Product</h4>
                <a href="#" class="btn flash-sales-btn mb-2 mb-md-0">
                    <i class="bi bi-lightning-fill"></i> Flash Sales
                </a>
                <div class="filter-group ms-auto">
                    <select class="form-select" name="brand" id="brandSelect" aria-label="Brand">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select class="form-select" name="category" id="categorySelect" aria-label="Categories">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-funnel-fill me-1"></i> Filter
                    </button>
                    
                    <a href="{{ route('wholesaler.product') }}" class="btn btn-clear d-flex align-items-center">
                        <i class="bi bi-arrow-clockwise me-1"></i> Clear
                    </a>
                </div>
            </div>
        </form>

        @if($getPro && $getPro->count() > 0)
            <div class="products-grid">
                <div class="row">
                    @foreach($getPro as $product)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="card product-card">
                                <div class="product-image-container">
                                    @if($product->image && $product->image->count() > 0)
                                        <img src="{{ asset($product->image->image) }}" 
                                             class="product-image" 
                                             alt="{{ $product->name ?? 'Product Image' }}">
                                    @else
                                        <img src="https://via.placeholder.com/250x300/f0f0f0/333333?text=No+Image" 
                                             class="product-image" 
                                             alt="No Image Available">
                                    @endif
                                    
                                    <span class="info-icon" 
                                          title="Product Info" 
                                          onclick="showDescriptionModal(this)"
                                          data-product-name="{{ $product->name ?? 'Product' }}"
                                          data-product-description="{{ $product->description ?? 'No description available' }}"
                                          data-bs-toggle="modal" 
                                          data-bs-target="#descriptionModal">
                                        <i class="bi bi-info-lg"></i>
                                    </span>
                                    
                                    <span class="download-icon"
                                          onclick="downloadImage('{{ $product->image ? asset($product->image->image) : '' }}')"
                                          title="Download Image">
                                        <i class="bi bi-download"></i>
                                    </span>
                                    
                                    @if($product->offer_price)
                                        <span class="special-offer-badge">SPECIAL OFFER</span>
                                    @endif
                                    
                                    @if($product->category)
                                        <span class="badge-amr">{{ $product->category->name }}</span>
                                    @endif
                                </div>
                                
                                @if($product->name)
                                    <div class="product-name">{{ Str::limit($product->name, 30) }}</div>
                                @endif
                                
                                <div class="card-body p-0">
                                    <div class="price-section">
                                        <div class="price-item">
                                            <strong>Retail</strong>
                                            <span class="price-value retail-price">
                                                ৳{{ number_format($product->new_price ?? 0) }}
                                            </span>
                                        </div>
                                        <div class="price-item">
                                            <strong>Wholesale</strong>
                                            <span class="price-value wholesale-price">
                                                ৳{{ number_format($product->offer_price ?? 0) }}
                                            </span>
                                        </div>
                                        <div class="price-item">
                                            <strong>Profit</strong>
                                            <span class="price-value profit-value">
                                                ৳{{ number_format(($product->new_price ?? 0) - ($product->offer_price ?? 0)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($getPro->hasPages())
    <div class="pagination-info">
        Showing {{ $getPro->firstItem() }} to {{ $getPro->lastItem() }} of {{ $getPro->total() }} results
    </div>
    
    <div class="custom-pagination">
        {{ $getPro->appends(request()->query())->links() }}
    </div>
@endif
        @else
            <div class="text-center py-5">
                <h5 class="text-muted">No products found</h5>
                <p class="text-muted">Please adjust your filters or check back later for wholesale products.</p>
            </div>
        @endif
    </div>

    <!-- Description Modal -->
    <div class="modal fade description-modal" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">
                        <i class="bi bi-info-circle me-2"></i>
                        <span id="productNameTitle">Product Information</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Description:</label>
                        <div id="descriptionContent" class="description-content">
                            <!-- Description will be loaded here -->
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn copy-btn" id="copyDescriptionBtn" onclick="copyModalDescription()">
                            <i class="bi bi-clipboard"></i>
                            Copy Description
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure all functions are available globally
    window.downloadImage = downloadImage;
    window.showDescriptionModal = showDescriptionModal;
    window.copyModalDescription = copyModalDescription;
    window.showNotification = showNotification;
    
    console.log('JavaScript functions loaded successfully');
});

let currentDescription = '';

function showDescriptionModal(element) {
    // Get data from the clicked element's data attributes
    const productName = element.getAttribute('data-product-name') || 'Product Information';
    const description = element.getAttribute('data-product-description') || '';
    
    console.log('Product Name:', productName);
    console.log('Description:', description);
    
    // Set the product name in modal title
    document.getElementById('productNameTitle').textContent = productName;
    
    // Store current description globally
    currentDescription = description;
    
    // Set the description content
    const descriptionContainer = document.getElementById('descriptionContent');
    
    if (description && description.trim() !== '' && description !== 'null' && description !== 'undefined' && description !== 'No description available') {
        descriptionContainer.innerHTML = '<div>' + description.replace(/\n/g, '<br>') + '</div>';
        descriptionContainer.classList.remove('no-description');
    } else {
        descriptionContainer.innerHTML = '<div class="no-description">No description available for this product.</div>';
        descriptionContainer.classList.add('no-description');
    }
}

// Alternative approach - if you want to keep the original function signature
function showDescriptionModalAlternative(productName, description) {
    console.log('Product Name:', productName);
    console.log('Description:', description);
    
    // Set the product name in modal title
    document.getElementById('productNameTitle').textContent = productName || 'Product Information';
    
    // Store current description globally
    currentDescription = description;
    
    // Set the description content
    const descriptionContainer = document.getElementById('descriptionContent');
    
    if (description && description.trim() !== '' && description !== 'null' && description !== 'undefined') {
        descriptionContainer.innerHTML = '<div>' + description.replace(/\n/g, '<br>') + '</div>';
        descriptionContainer.classList.remove('no-description');
    } else {
        descriptionContainer.innerHTML = '<div class="no-description">No description available for this product.</div>';
        descriptionContainer.classList.add('no-description');
    }
}

function copyModalDescription() {
    const copyBtn = document.getElementById('copyDescriptionBtn');
    
    // Handle empty or null descriptions
    if (!currentDescription || currentDescription === 'null' || currentDescription === 'undefined' || currentDescription.trim() === '') {
        showNotification('No description available to copy', 'warning');
        return;
    }
    
    // Use modern Clipboard API if available
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(currentDescription)
            .then(function() {
                // Visual feedback
                copyBtn.classList.add('copy-success');
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
                
                showNotification('Product description copied to clipboard!', 'success');
                
                // Reset button after 2 seconds
                setTimeout(function() {
                    copyBtn.innerHTML = originalText;
                    copyBtn.classList.remove('copy-success');
                }, 2000);
            })
            .catch(function(err) {
                console.error('Clipboard API failed:', err);
                fallbackCopyDescription(currentDescription);
            });
    } else {
        // Fallback for older browsers
        fallbackCopyDescription(currentDescription);
    }
}

function fallbackCopyDescription(description) {
    try {
        // Create a temporary textarea
        const tempTextArea = document.createElement('textarea');
        tempTextArea.value = description;
        tempTextArea.style.position = 'fixed';
        tempTextArea.style.left = '-9999px';
        tempTextArea.style.top = '-9999px';
        tempTextArea.style.opacity = '0';
        
        document.body.appendChild(tempTextArea);
        tempTextArea.focus();
        tempTextArea.select();
        tempTextArea.setSelectionRange(0, 99999); // For mobile devices
        
        const successful = document.execCommand('copy');
        document.body.removeChild(tempTextArea);
        
        if (successful) {
            showNotification('Product description copied to clipboard!', 'success');
            
            // Visual feedback for fallback
            const copyBtn = document.getElementById('copyDescriptionBtn');
            copyBtn.classList.add('copy-success');
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
            
            setTimeout(function() {
                copyBtn.innerHTML = originalText;
                copyBtn.classList.remove('copy-success');
            }, 2000);
        } else {
            throw new Error('Copy command failed');
        }
    } catch (err) {
        console.error('Fallback copy failed:', err);
        showNotification('Copy failed. Please select and copy the text manually.', 'error');
    }
}

function downloadImage(imageUrl) {
    if (!imageUrl || imageUrl === '') {
        showNotification('No image available to download', 'warning');
        return;
    }
    
    try {
        // Create a temporary anchor element
        const link = document.createElement('a');
        link.href = imageUrl;
        link.download = imageUrl.split('/').pop() || 'product-image.jpg';
        link.target = '_blank';
        
        // Append to body, click, then remove
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showNotification('Download started successfully', 'success');
    } catch (error) {
        console.error('Download failed:', error);
        // Fallback: open in new tab
        window.open(imageUrl, '_blank');
        showNotification('Image opened in new tab', 'info');
    }
}

function showNotification(message, type) {
    type = type || 'info';
    
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(function(notification) {
        notification.remove();
    });
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'custom-notification alert alert-' + (type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'error' ? 'danger' : 'info');
    
    notification.innerHTML = '<div style="display: flex; align-items: center; justify-content: space-between;">' +
        '<span>' + message + '</span>' +
        '<button type="button" style="background: none; border: none; font-size: 18px; cursor: pointer; margin-left: 10px;" onclick="this.parentElement.parentElement.remove()">×</button>' +
        '</div>';
    
    document.body.appendChild(notification);
    
    // Auto-remove after 4 seconds
    setTimeout(function() {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(function() {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 4000);
}
</script>
@endsection