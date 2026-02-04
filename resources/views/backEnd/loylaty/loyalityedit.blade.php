@extends('backEnd.layouts.master')
@section('title', 'Loyalty Edit')
@section('css')
<link href="{{asset('backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
<style>
    #preview-image {
        max-width: 300px;
        max-height: 300px;
        margin-top: 10px;
        border: 2px solid #ddd;
        border-radius: 5px;
        padding: 5px;
    }
    .current-image {
        max-width: 300px;
        max-height: 300px;
        margin-top: 10px;
        border: 2px solid #ddd;
        border-radius: 5px;
        padding: 5px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('loylaty.manage')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Loyalty Edit</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    
   <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('loyality.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                    @csrf
                     <input type="hidden" name="id" value="{{$edit_data->id}}"/>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{$edit_data->titile}}" required/>
                           
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="author_id" class="form-label">Author *</label>
                            <select id="author_id" class="form-control @error('author_id') is-invalid @enderror" name="author_id" required>
                                <option value="">Select Option</option>
                                @foreach($user as $authors)
                                <option value="{{$authors->id}}" {{$edit_data->author_id == $authors->id ? 'selected' : ''}}>{{$authors->name}}</option>
                                @endforeach
                            </select>
                           
                            @error('author_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                     
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="product_id" class="form-label">Product *</label>
                            <select id="products" class="select2 form-control @error('product_id') is-invalid @enderror" name="product_id[]" data-placeholder="Choose products..." required multiple>
                                @php
                                $selectedProducts = json_decode($edit_data->product_id, true) ?? [];
                            @endphp
                                @foreach($allproduct as $product)
                                <option value="{{ $product->id }}" 
                                    {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                            </select>
                            @error('product_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    
                    <div class="col-sm-12">
                        <div class="form-group" id="discount_table">
                            <!-- Dynamic discount table will load here -->
                        </div>
                    </div>
                    <!-- col-end -->
                    
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="status" {{$edit_data->status == 1 ? 'checked' : ''}}>
                              <span class="slider round"></span>
                            </label>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div> 
                    <!-- col end -->
                    
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="banner" class="form-label">Image</label>
                            <input type="file" class="form-control @error('banner') is-invalid @enderror" name="banner" accept="image/*" id="banner_input">
                            
                            <!-- Current Image -->
                            @if($edit_data->banner)
                            <div class="mt-2">
                                <label class="form-label">Current Image:</label>
                                <img class="current-image" src="{{asset($edit_data->banner)}}" alt="Current Banner">
                            </div>
                            @endif
                            
                            <!-- New Image Preview -->
                            <img id="preview-image" src="#" alt="Image Preview" style="display: none;">
                           
                            @error('banner')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success" value="Update">
                    </div>

                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
   </div>
</div>
@endsection

@section('script')
<script src="{{asset('backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<!-- Plugins js -->
<script src="{{asset('backEnd/')}}/assets/libs/summernote/summernote-lite.min.js"></script>

<script>
$(document).ready(function () {
    // Initialize Select2
    $('.select2').select2();
    
    // Get saved product IDs
    var savedProducts = @json(json_decode($edit_data->product_id, true) ?? []);
    
    // Load discount table on page load if products are selected
    if(savedProducts.length > 0) {
        loadDiscountTable(savedProducts);
    }
    
    // Handle author selection change
    $('#author_id').on('change', function(){
        var author_id = $(this).val();
        
        if(author_id && author_id !== ''){
            loadAuthorProducts(author_id);
        } else {
            // Reset products selection
            $('#products').val(null).trigger('change');
            $('#discount_table').html('');
        }
    });
    
    // Handle product selection change
    $('#products').on('change', function(){
        var product_id = $('#products').val();
        
        if(product_id && product_id.length > 0){
            loadDiscountTable(product_id);
        } else {
            $('#discount_table').html('');
        }
    });
    
    // Function to load products when author changes
    function loadAuthorProducts(author_id) {
        $.ajax({
            url: '{{ route('loyality.author.products') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                author_id: author_id
            },
            dataType: 'json',
            success: function(response) {
                // Clear existing options
                $('#products').html('');
                
                // Add ALL products as options
                $.each(response.all_products, function(index, product) {
                    var isAuthorProduct = response.author_products.some(p => p.id === product.id);
                    var option = $('<option></option>')
                        .attr('value', product.id)
                        .text(product.name);
                    
                    // Auto-select author's products
                    if(isAuthorProduct) {
                        option.prop('selected', true);
                    }
                    
                    $('#products').append(option);
                });
                
                // Trigger change to refresh select2
                $('#products').trigger('change');
                
                // Load discount table for selected products
                var selectedProducts = $('#products').val();
                if(selectedProducts && selectedProducts.length > 0) {
                    loadDiscountTable(selectedProducts);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading products:', error);
                alert('Error loading products. Please try again.');
            }
        });
    }
    
    // Function to load discount table
    function loadDiscountTable(product_id) {
        $.ajax({
            url: '{{ route('loyality.product') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: product_id
            },
            success: function(data) {
                $('#discount_table').html(data);
            },
            error: function(xhr, status, error) {
                console.error('Error loading discount table:', error);
            }
        });
    }
});

// Image preview functionality
document.addEventListener('DOMContentLoaded', function() {
    const bannerInput = document.getElementById('banner_input');
    const previewImage = document.getElementById('preview-image');
    
    if(bannerInput && previewImage) {
        bannerInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            
            if(file){
                const reader = new FileReader();
                reader.onload = function(e){
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewImage.style.display = 'none';
            }
        });
    }
});
</script>
@endsection